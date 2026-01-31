<?php
/**
 * Two-factor authentication verification (TOTP or recovery code).
 * Shown after login when user has two_factor_enabled. Completes login on success.
 */
declare(strict_types=1);

$appRoot = dirname(__DIR__);
if (ob_get_level() === 0) {
    ob_start();
}

require_once $appRoot . '/app/middleware/SessionMiddleware.php';
require_once $appRoot . '/app/middleware/CsrfMiddleware.php';
SessionMiddleware::handle();

require_once $appRoot . '/config/database.php';
require_once $appRoot . '/includes/security.php';
require_once $appRoot . '/includes/totp.php';

// Must have pending 2FA context from login
$pending_user_id = (int) ($_SESSION['pending_2fa_user_id'] ?? 0);
if ($pending_user_id < 1) {
    header('Location: /');
    exit;
}

$error = '';
$code_type = 'totp'; // totp or recovery
$debug_2fa = isset($_GET['debug']) && $_GET['debug'] === '1';
$debug_info = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (CsrfMiddleware::verify() === false) {
        $error = 'Invalid request. Please try again.';
    } else {
        $code = trim((string) ($_POST['code'] ?? ''));
        $code = preg_replace('/\s+/', '', $code);

        if ($code === '') {
            $error = 'Please enter your authentication code or a recovery code.';
        } else {
            $user = db_fetch_one(
                'SELECT id, username, name, role, employee_id, department, two_factor_secret, two_factor_recovery_codes FROM users WHERE id = ?',
                [$pending_user_id]
            );
            if (!$user) {
                $error = 'Session expired. Please log in again.';
                unset($_SESSION['pending_2fa_user_id'], $_SESSION['pending_2fa_username'], $_SESSION['pending_2fa_name'], $_SESSION['pending_2fa_role'], $_SESSION['pending_2fa_employee_id'], $_SESSION['pending_2fa_department']);
                header('Location: /');
                exit;
            }

            $verified = false;

            // Try TOTP first (6 digits). Use same Base32 secret as in QR (from DB).
            $secretFromDb = trim((string) ($user['two_factor_secret'] ?? ''));
            if (strlen($code) === 6 && ctype_digit($code) && $secretFromDb !== '') {
                if (totp_verify($secretFromDb, $code)) {
                    $verified = true;
                }
            }

            // Try recovery code (8 chars, alphanumeric)
            if (!$verified && !empty($user['two_factor_recovery_codes'])) {
                $recovery_codes = json_decode($user['two_factor_recovery_codes'], true);
                if (is_array($recovery_codes)) {
                    $code_upper = strtoupper($code);
                    $idx = array_search($code_upper, array_map('strtoupper', $recovery_codes), true);
                    if ($idx !== false) {
                        array_splice($recovery_codes, (int) $idx, 1);
                        db_execute(
                            'UPDATE users SET two_factor_recovery_codes = ?, updated_at = NOW() WHERE id = ?',
                            [json_encode(array_values($recovery_codes)), $user['id']]
                        );
                        $verified = true;
                    }
                }
            }

            if ($verified) {
                db_execute(
                    'UPDATE users SET last_login = NOW(), last_login_ip = ?, failed_login_attempts = 0, locked_until = NULL WHERE id = ?',
                    [$_SERVER['REMOTE_ADDR'] ?? null, $user['id']]
                );
                if (function_exists('log_security_event')) {
                    log_security_event('2FA Verified – Login', "User: {$user['username']} ({$user['name']})");
                }
                SessionMiddleware::regenerate();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['employee_id'] = $user['employee_id'] ?? null;
                $_SESSION['department'] = $user['department'] ?? null;
                unset(
                    $_SESSION['pending_2fa_user_id'],
                    $_SESSION['pending_2fa_username'],
                    $_SESSION['pending_2fa_name'],
                    $_SESSION['pending_2fa_role'],
                    $_SESSION['pending_2fa_employee_id'],
                    $_SESSION['pending_2fa_department']
                );
                csrf_rotate();

                $role = $user['role'];
                if ($role === 'super_admin') {
                    header('Location: /super-admin/dashboard');
                    exit;
                }
                if ($role === 'hr' || $role === 'humanresource') {
                    header('Location: /human-resource/dashboard');
                    exit;
                }
                if ($role === 'admin') {
                    header('Location: /admin/dashboard');
                    exit;
                }
                if ($role === 'developer') {
                    header('Location: /developer/dashboard');
                    exit;
                }
                header('Location: /');
                exit;
            }

            $error = 'Invalid code. Please enter the 6-digit code from your authenticator app or a recovery code.';
            // When debug=1 and user entered a 6-digit code, collect TOTP debug info for display
            if ($debug_2fa && strlen($code) === 6 && ctype_digit($code)) {
                $secretFromDb = trim((string) ($user['two_factor_secret'] ?? ''));
                $debug_info = totp_get_debug_info($secretFromDb, $code);
            }
        }
    }
}

$pending_username = $_SESSION['pending_2fa_username'] ?? '';
$page_title = 'Two-factor authentication';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($page_title); ?> · Golden Z-5</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/login.css" rel="stylesheet">
    <style>
        .auth-form-card { max-width: 400px; }
        .twofa-code-input { font-size: 1.25rem; letter-spacing: 0.35em; text-align: center; }
        .twofa-help { font-size: 0.875rem; color: var(--text-muted); margin-top: 0.5rem; }
        .auth-footer-link { margin-top: 1rem; text-align: center; font-size: 0.9rem; }
        .auth-footer-link a { color: var(--gold-dark); text-decoration: none; font-weight: 500; }
        .auth-footer-link a:hover { color: var(--gold-primary); text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-form-panel">
            <div class="auth-form-container">
                <div class="auth-form-card">
                    <div class="auth-header">
                        <h2 class="auth-title">Two-factor authentication</h2>
                        <p class="auth-subtitle">Enter the 6-digit code from your authenticator app, or a recovery code</p>
                    </div>
                    <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
                        <div class="alert-content">
                            <strong>Invalid code</strong>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($debug_2fa && $debug_info !== null): ?>
                    <div class="alert twofa-debug-box" role="status" style="background: #1a1d21; color: #e2e4e8; border: 1px solid #333; padding: 1rem; margin-bottom: 1rem; font-family: monospace; font-size: 0.85rem; text-align: left;">
                        <strong style="color: #f0ad4e;">2FA debug (remove ?debug=1 in production)</strong>
                        <table style="width:100%; margin-top:0.5rem; border-collapse: collapse;">
                            <tr><td style="padding:0.2rem 0.5rem 0.2rem 0;">Secret from DB</td><td>length=<?php echo (int) $debug_info['secret_length']; ?>, preview <?php echo htmlspecialchars($debug_info['secret_preview']); ?></td></tr>
                            <tr><td style="padding:0.2rem 0.5rem 0.2rem 0;">Base32 decode</td><td><?php echo $debug_info['decode_ok'] ? 'OK' : 'FAIL'; ?><?php if ($debug_info['decode_error']): ?> — <?php echo htmlspecialchars($debug_info['decode_error']); ?><?php endif; ?></td></tr>
                            <?php if ($debug_info['decode_ok']): ?>
                            <tr><td style="padding:0.2rem 0.5rem 0.2rem 0;">Decoded secret</td><td><?php echo (int) $debug_info['decoded_bytes']; ?> bytes</td></tr>
                            <?php endif; ?>
                            <tr><td style="padding:0.2rem 0.5rem 0.2rem 0;">Server time (UTC)</td><td><?php echo htmlspecialchars($debug_info['server_time_utc']); ?> (slice <?php echo (int) $debug_info['time_slice']; ?>)</td></tr>
                            <tr><td style="padding:0.2rem 0.5rem 0.2rem 0;">Code you entered</td><td><strong><?php echo htmlspecialchars($debug_info['code_entered']); ?></strong></td></tr>
                            <?php if ($debug_info['expected_current'] !== null): ?>
                            <tr><td style="padding:0.2rem 0.5rem 0.2rem 0;">Expected (current)</td><td><?php echo htmlspecialchars($debug_info['expected_current']); ?><?php if ($debug_info['code_matches_current']): ?> <span style="color:#5cb85c;">✓ matches</span><?php endif; ?></td></tr>
                            <tr><td style="padding:0.2rem 0.5rem 0.2rem 0;">Expected (prev 30s)</td><td><?php echo htmlspecialchars($debug_info['expected_prev'] ?? '-'); ?><?php if ($debug_info['code_matches_prev'] ?? false): ?> <span style="color:#5cb85c;">✓ matches</span><?php endif; ?></td></tr>
                            <tr><td style="padding:0.2rem 0.5rem 0.2rem 0;">Expected (next 30s)</td><td><?php echo htmlspecialchars($debug_info['expected_next'] ?? '-'); ?><?php if ($debug_info['code_matches_next'] ?? false): ?> <span style="color:#5cb85c;">✓ matches</span><?php endif; ?></td></tr>
                            <?php endif; ?>
                        </table>
                        <p style="margin:0.5rem 0 0 0; font-size:0.8rem; color:#999;">Compare “Expected (current)” with the code shown in Google Authenticator. If they differ, secret in DB may not match the one in the app (re-setup 2FA). If server time is wrong, sync server clock.</p>
                    </div>
                    <?php endif; ?>
                    <form method="post" action="<?php echo $debug_2fa ? '/2fa?debug=1' : ''; ?>" class="auth-form" id="twofaForm">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="code" class="form-label">Authentication or recovery code</label>
                            <div class="input-wrapper">
                                <div class="input-icon"><i class="fas fa-key"></i></div>
                                <input type="text" id="code" name="code" class="form-control twofa-code-input" inputmode="numeric" autocomplete="one-time-code" placeholder="000000" maxlength="8" autofocus required>
                            </div>
                            <p class="twofa-help">6-digit code from your app, or 8-character recovery code</p>
                        </div>
                        <div class="form-submit">
                            <button type="submit" class="btn btn-primary btn-block">Verify and continue</button>
                        </div>
                    </form>
                    <?php if ($debug_2fa && $debug_info === null): ?>
                    <p class="twofa-help" style="margin-top:0.5rem; color: var(--gold-dark);">Debug mode: submit a code to see server-side diagnostics below.</p>
                    <?php endif; ?>
                    <p class="auth-footer-link">
                        <a href="/?logout=1">Use a different account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
