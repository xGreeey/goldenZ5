<?php
/**
 * Two-factor authentication verification (TOTP or recovery code).
 * Shown after login when user has two_factor_enabled. Completes login on success.
 * Layout matches login and forgot-password: split panel with branded left, form right.
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
        }
    }
}

$page_title = 'Two-factor authentication';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($page_title); ?> – Golden Z-5</title>

    <link rel="icon" type="image/svg+xml" href="/assets/favicon.php">
    <link rel="icon" type="image/png" href="/assets/images/logo.png">
    <link rel="apple-touch-icon" href="/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="/assets/css/login.css" rel="stylesheet">
    <link href="/assets/css/forgot_password.css" rel="stylesheet">

    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <style>
        .twofa-code-input { font-size: 1.25rem; letter-spacing: 0.35em; text-align: center; }
        .twofa-help { font-size: 0.875rem; color: var(--text-muted); margin-top: 0.5rem; }
    </style>
</head>
<body>
    <!-- Floating Background Elements - Same as login / forgot password -->
    <div class="floating-elements">
        <i class="fas fa-shield-alt floating-icon shield size-xl" style="top: 18%; left: 8%; --float-duration: 32s;"></i>
        <i class="fas fa-star floating-icon star size-lg" style="top: 68%; left: 15%; --float-duration: 28s;"></i>
        <i class="fas fa-certificate floating-icon badge size-md" style="top: 42%; left: 12%; --float-duration: 26s;"></i>
        <div class="floating-icon circle size-xl" style="top: 25%; left: 48%; --float-duration: 22s;"></div>
        <i class="fas fa-user-shield floating-icon cap size-lg" style="top: 55%; left: 45%; --float-duration: 30s;"></i>
        <div class="floating-icon circle size-lg" style="top: 78%; left: 42%; --float-duration: 24s;"></div>
        <i class="fas fa-award floating-icon badge size-lg" style="top: 15%; left: 82%; --float-duration: 29s;"></i>
        <i class="fas fa-star floating-icon star size-md" style="top: 48%; left: 88%; --float-duration: 27s;"></i>
        <i class="fas fa-shield-alt floating-icon shield size-md" style="top: 72%; left: 85%; --float-duration: 25s;"></i>
        <i class="fas fa-star floating-icon star size-sm" style="top: 8%; left: 35%; --float-duration: 26s;"></i>
        <i class="fas fa-id-badge floating-icon badge size-sm" style="top: 88%; left: 28%; --float-duration: 28s;"></i>
        <div class="floating-icon circle size-md" style="top: 35%; left: 92%; --float-duration: 23s;"></div>
    </div>

    <div class="login-split-container">
        <!-- Left Branded Panel - Same as login / forgot password -->
        <div class="login-branded-panel">
            <div class="branded-content">
                <img src="/assets/images/goldenz-logo.jpg" alt="Golden Z-5 Security and Investigation Agency, Inc. Logo" class="branded-logo reveal-item" onerror="this.style.display='none'">
                <h1 class="branded-headline reveal-item">Golden Z-5 Security and Investigation Agency, Inc.</h1>
                <p class="branded-description reveal-item">
                    Human Resources Management System<br>
                    Licensed by PNP-CSG-SAGSD | Registered with SEC
                </p>
                <button type="button" class="see-more-btn reveal-item" id="seeMoreBtn" aria-label="View system information and features">
                    <i class="fas fa-info-circle" aria-hidden="true"></i> System Information
                </button>
                <div class="social-links reveal-item">
                    <a href="mailto:goldenzfive@yahoo.com.ph" class="social-link" title="Email us">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <a href="https://www.facebook.com/goldenZ5SA" target="_blank" rel="noopener noreferrer" class="social-link" title="Visit our Facebook page">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Form Panel - Same position as login / forgot password -->
        <div class="login-form-panel">
            <div class="auth-form-container reveal-form">
                <div class="auth-form-card">
                    <div class="auth-header">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center mb-3 forgot-password-header-icon">
                                <i class="fas fa-mobile-alt" aria-hidden="true"></i>
                            </div>
                        </div>
                        <h2 class="auth-title">Two-factor authentication</h2>
                        <p class="auth-subtitle">Enter the 6-digit code from your authenticator app, or an 8-character recovery code.</p>
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

                    <form method="post" action="" class="auth-form" id="twofaForm">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="code" class="form-label">Authentication or recovery code <span class="required-indicator">*</span></label>
                            <div class="input-wrapper">
                                <div class="input-icon"><i class="fas fa-key"></i></div>
                                <input type="text" id="code" name="code" class="form-control twofa-code-input" inputmode="numeric" autocomplete="one-time-code" placeholder="000000" maxlength="8" autofocus required>
                            </div>
                            <p class="twofa-help">6-digit code from your app, or 8-character recovery code</p>
                        </div>
                        <div class="form-submit">
                            <button type="submit" class="btn btn-primary btn-block">
                                <span class="btn-text">Verify and continue</span>
                            </button>
                        </div>
                    </form>

                    <div class="form-footer">
                        <div class="help-text">
                            <i class="fas fa-arrow-left" aria-hidden="true"></i>
                            <a href="/?logout=1" class="forgot-password-link">Use a different account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="forgot-password-footer">
        <p>Golden Z-5 Security and Investigation Agency, Inc. · HR Management System</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/login.js"></script>
</body>
</html>
