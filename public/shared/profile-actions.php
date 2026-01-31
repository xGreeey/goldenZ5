<?php

declare(strict_types=1);

/**
 * My Profile — backend actions (load user, update personal/account/password, 2FA).
 * Requires: config/database.php (db_fetch_one, db_execute).
 */

if (!function_exists('db_fetch_one') || !function_exists('db_execute')) {
    return;
}

function profile_load_user(int $user_id): ?array
{
    $row = db_fetch_one(
        'SELECT id, username, email, name, role, department, phone, created_at, last_login, two_factor_enabled
         FROM users WHERE id = ?',
        [$user_id]
    );
    return $row;
}

function profile_update_personal(int $user_id, array $data): array
{
    $name = trim((string) ($data['name'] ?? $data['full_name'] ?? ''));
    $email = trim((string) ($data['email'] ?? ''));
    $phone = trim((string) ($data['phone'] ?? ''));

    if ($name === '') {
        return ['success' => false, 'error' => 'Full name is required.'];
    }
    if (strlen($name) > 100) {
        return ['success' => false, 'error' => 'Full name is too long.'];
    }
    if ($email === '') {
        return ['success' => false, 'error' => 'Email is required.'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Please enter a valid email address.'];
    }
    if (strlen($email) > 100) {
        return ['success' => false, 'error' => 'Email is too long.'];
    }
    if ($phone !== '' && strlen($phone) > 20) {
        return ['success' => false, 'error' => 'Phone number is too long.'];
    }

    $existing = db_fetch_one('SELECT id FROM users WHERE email = ? AND id != ?', [$email, $user_id]);
    if ($existing !== null) {
        return ['success' => false, 'error' => 'That email is already in use.'];
    }

    db_execute(
        'UPDATE users SET name = ?, email = ?, phone = ?, updated_at = NOW() WHERE id = ?',
        [$name, $email, $phone ?: null, $user_id]
    );

    $_SESSION['name'] = $name;
    return ['success' => true];
}

function profile_update_account(int $user_id, string $username): array
{
    $username = trim($username);
    if ($username === '') {
        return ['success' => false, 'error' => 'Username is required.'];
    }
    if (strlen($username) > 50 || !preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
        return ['success' => false, 'error' => 'Username may only contain letters, numbers, dots, hyphens and underscores.'];
    }

    $existing = db_fetch_one('SELECT id FROM users WHERE username = ? AND id != ?', [$username, $user_id]);
    if ($existing !== null) {
        return ['success' => false, 'error' => 'That username is already in use.'];
    }

    db_execute('UPDATE users SET username = ?, updated_at = NOW() WHERE id = ?', [$username, $user_id]);
    $_SESSION['username'] = $username;
    return ['success' => true];
}

function profile_update_password(int $user_id, string $current_password, string $new_password): array
{
    if ($current_password === '') {
        return ['success' => false, 'error' => 'Current password is required.'];
    }
    if ($new_password === '') {
        return ['success' => false, 'error' => 'New password is required.'];
    }

    $user = db_fetch_one('SELECT password_hash FROM users WHERE id = ?', [$user_id]);
    if (!$user || !password_verify($current_password, $user['password_hash'])) {
        return ['success' => false, 'error' => 'Current password is incorrect.'];
    }

    if (strlen($new_password) < 8) {
        return ['success' => false, 'error' => 'New password must be at least 8 characters.'];
    }
    $has_upper = preg_match('/[A-Z]/', $new_password);
    $has_lower = preg_match('/[a-z]/', $new_password);
    $has_number = preg_match('/[0-9]/', $new_password);
    $has_symbol = preg_match('/[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]/', $new_password);
    if (!$has_upper || !$has_lower || !$has_number || !$has_symbol) {
        return ['success' => false, 'error' => 'New password must contain uppercase, lowercase, number and symbol.'];
    }

    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    db_execute('UPDATE users SET password_hash = ?, password_changed_at = NOW(), updated_at = NOW() WHERE id = ?', [$hash, $user_id]);
    return ['success' => true];
}

function profile_base32_encode(string $data): string
{
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $output = '';
    $v = 0;
    $vBits = 0;
    for ($i = 0, $len = strlen($data); $i < $len; $i++) {
        $v = ($v << 8) | ord($data[$i]);
        $vBits += 8;
        while ($vBits >= 5) {
            $vBits -= 5;
            $output .= $alphabet[($v >> $vBits) & 31];
            $v = $v & ((1 << $vBits) - 1);
        }
    }
    if ($vBits > 0) {
        $output .= $alphabet[($v << (5 - $vBits)) & 31];
    }
    return $output;
}

function profile_generate_recovery_codes(): array
{
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $codes = [];
    for ($i = 0; $i < 8; $i++) {
        $code = '';
        for ($j = 0; $j < 8; $j++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        $codes[] = $code;
    }
    return array_values($codes);
}

function profile_2fa_enable(int $user_id): array
{
    $secretBinary = random_bytes(20);
    $secret = profile_base32_encode($secretBinary);
    $recovery_codes = profile_generate_recovery_codes();
    $recovery_json = json_encode($recovery_codes);

    db_execute(
        'UPDATE users SET two_factor_secret = ?, two_factor_enabled = 1, two_factor_recovery_codes = ?, updated_at = NOW() WHERE id = ?',
        [$secret, $recovery_json, $user_id]
    );

    return ['success' => true, 'secret' => $secret, 'recovery_codes' => $recovery_codes];
}

function profile_2fa_disable(int $user_id, string $password): array
{
    if ($password === '') {
        return ['success' => false, 'error' => 'Password is required to disable two-factor authentication.'];
    }

    $user = db_fetch_one('SELECT password_hash FROM users WHERE id = ?', [$user_id]);
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return ['success' => false, 'error' => 'Password is incorrect.'];
    }

    db_execute(
        'UPDATE users SET two_factor_enabled = 0, two_factor_secret = NULL, two_factor_recovery_codes = NULL, updated_at = NOW() WHERE id = ?',
        [$user_id]
    );

    return ['success' => true];
}
