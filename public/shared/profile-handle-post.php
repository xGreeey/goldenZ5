<?php

declare(strict_types=1);

/**
 * My Profile — handle POST (personal, account, security, 2fa_enable, 2fa_disable).
 * Expects: $profile_user_id (int), $profile_base_url (string). Requires profile-actions.php.
 * Ensures database is loaded so profile-actions can use db_fetch_one/db_execute.
 */

$appRoot = dirname(__DIR__, 2);
if (!function_exists('db_fetch_one')) {
    require_once $appRoot . '/config/database.php';
}

$profile_send_redirect = function (string $url): void {
    while (ob_get_level()) {
        ob_end_clean();
    }
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    }
    echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($url) . '"></head><body><p>Redirecting… <a href="' . htmlspecialchars($url) . '">Click here</a>.</p></body></html>';
    exit;
};

$user_id = (int) ($profile_user_id ?? 0);
$redirect_base = ($profile_base_url ?? '') . '?page=profile';

if ($user_id < 1) {
    $profile_send_redirect($redirect_base . '&error=' . urlencode('Not authenticated.'));
}

require_once __DIR__ . '/profile-actions.php';

$profile_section = isset($_POST['profile_section']) ? trim((string) $_POST['profile_section']) : '';

// Detect 2FA disable form: by profile_section or by _2fa_disable + password (so it works even if profile_section is missing)
if ((!empty($_POST['_2fa_disable']) && isset($_POST['password'])) || $profile_section === '2fa_disable') {
    $profile_section = '2fa_disable';
}

if ($profile_section === 'personal') {
    $result = profile_update_personal($user_id, $_POST);
    if (!empty($result['error'])) {
        $profile_send_redirect($redirect_base . '&error=' . urlencode($result['error']));
    }
    $profile_send_redirect($redirect_base . '&success=personal');
}

if ($profile_section === 'account') {
    $result = profile_update_account($user_id, (string) ($_POST['username'] ?? ''));
    if (!empty($result['error'])) {
        $profile_send_redirect($redirect_base . '&error=' . urlencode($result['error']));
    }
    $profile_send_redirect($redirect_base . '&success=account');
}

if ($profile_section === 'security') {
    $current = (string) ($_POST['current_password'] ?? '');
    $new = (string) ($_POST['new_password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');
    if ($new !== $confirm) {
        $profile_send_redirect($redirect_base . '&error=' . urlencode('New password and confirmation do not match.'));
    }
    $result = profile_update_password($user_id, $current, $new);
    if (!empty($result['error'])) {
        $profile_send_redirect($redirect_base . '&error=' . urlencode($result['error']));
    }
    $profile_send_redirect($redirect_base . '&success=security');
}

if ($profile_section === '2fa_enable') {
    $result = profile_2fa_enable($user_id);
    if (!empty($result['error'])) {
        $profile_send_redirect($redirect_base . '&error=' . urlencode($result['error']));
    }
    $_SESSION['profile_2fa_secret'] = $result['secret'] ?? '';
    $_SESSION['profile_2fa_recovery_codes'] = $result['recovery_codes'] ?? [];
    $profile_send_redirect($redirect_base . '&2fa_setup=1');
}

if ($profile_section === '2fa_disable') {
    $password = (string) ($_POST['password'] ?? '');
    $result = profile_2fa_disable($user_id, $password);
    if (!empty($result['error'])) {
        $profile_send_redirect($redirect_base . '&error=' . urlencode($result['error']));
    }
    $profile_send_redirect($redirect_base . '&success=2fa_disable');
}

$profile_send_redirect($redirect_base);
