<?php
/**
 * FORGOT PASSWORD PAGE
 *
 * Standalone page for password reset requests. User enters username;
 * we show a generic message (do not reveal if account exists).
 * Links back to login (index.php).
 */

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

require_once __DIR__ . '/../config/session.php';

$cspNonce = base64_encode(random_bytes(16));

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443');
if ($isHttps) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}
$cspLines = [
    "default-src 'self'",
    "script-src 'self' 'nonce-{$cspNonce}' https://cdn.jsdelivr.net",
    "style-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com",
    "font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com",
    "img-src 'self' data:",
    "connect-src 'self'",
    "frame-ancestors 'none'",
    "base-uri 'self'",
    "form-action 'self'",
];
header('Content-Security-Policy: ' . implode('; ', $cspLines));

try {
    if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
        require_once __DIR__ . '/../bootstrap/app.php';
    } else {
        require_once __DIR__ . '/../bootstrap/autoload.php';
    }
} catch (Exception $e) {
    error_log('Bootstrap error: ' . $e->getMessage());
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/security.php';

$forgot_message = '';
$forgot_success = false;
$show_result = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_password'])) {
    if (!csrf_validate()) {
        $forgot_message = 'Invalid security token. Please refresh the page and try again.';
        $show_result = true;
    } else {
        $forgot_username = trim($_POST['forgot_username'] ?? '');
        if (empty($forgot_username)) {
            $forgot_message = 'Please enter your username.';
            $show_result = true;
        } else {
            $forgot_success = true;
            $forgot_message = 'If an account exists for that username, your administrator can reset your password. Please contact your system administrator or HR for assistance.';
            $show_result = true;
            if (function_exists('log_security_event')) {
                log_security_event('Forgot Password Request', 'Username requested: ' . $forgot_username . ' - IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown'));
            }
        }
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <title>Forgot password – Golden Z-5 HR</title>
    <link rel="icon" type="image/svg+xml" href="/admin/assets/favicon.php">
    <link rel="icon" type="image/jpeg" href="/admin/assets/images/goldenz-logo.jpg">
    <link rel="apple-touch-icon" href="/admin/assets/images/goldenz-logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="/admin/assets/css/forgot_password.css" rel="stylesheet">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
</head>
<body class="forgot-page">
    <div class="forgot-bg">
        <div class="forgot-bg-gradient"></div>
        <div class="forgot-bg-noise"></div>
        <div class="forgot-bg-shapes">
            <span class="shape shape-1" aria-hidden="true"></span>
            <span class="shape shape-2" aria-hidden="true"></span>
            <span class="shape shape-3" aria-hidden="true"></span>
            <span class="shape shape-4" aria-hidden="true"></span>
            <span class="shape shape-5" aria-hidden="true"></span>
        </div>
    </div>

    <a href="index.php" class="forgot-back" aria-label="Back to sign in">
        <i class="fas fa-arrow-left" aria-hidden="true"></i>
        <span>Back to sign in</span>
    </a>

    <main class="forgot-main">
        <div class="forgot-card-wrapper">
            <div class="forgot-card <?= $show_result ? 'forgot-card--result' : '' ?>">
                <?php if ($show_result): ?>
                    <div class="forgot-result">
                        <div class="forgot-result-icon <?= $forgot_success ? 'forgot-result-icon--success' : 'forgot-result-icon--error' ?>">
                            <?php if ($forgot_success): ?>
                                <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <?php else: ?>
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                            <?php endif; ?>
                        </div>
                        <h1 class="forgot-result-title">
                            <?= $forgot_success ? 'Request received' : 'Please try again' ?>
                        </h1>
                        <p class="forgot-result-message"><?= htmlspecialchars($forgot_message, ENT_QUOTES, 'UTF-8') ?></p>
                        <a href="index.php" class="forgot-btn forgot-btn--primary">
                            <span>Back to sign in</span>
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="forgot-header">
                        <div class="forgot-key-visual" aria-hidden="true">
                            <i class="fas fa-key"></i>
                        </div>
                        <h1 class="forgot-title">Forgot password?</h1>
                        <p class="forgot-subtitle">Enter your username. Contact your system administrator or HR to reset your password.</p>
                    </div>
                    <form method="POST" class="forgot-form" id="forgotPasswordForm" novalidate>
                        <input type="hidden" name="forgot_password" value="1">
                        <?= csrf_field() ?>
                        <div class="forgot-field">
                            <label for="forgot_username" class="forgot-label">Username</label>
                            <div class="forgot-input-wrap">
                                <i class="fas fa-user forgot-input-icon" aria-hidden="true"></i>
                                <input type="text"
                                       id="forgot_username"
                                       name="forgot_username"
                                       class="forgot-input"
                                       placeholder="Enter your username"
                                       required
                                       autocomplete="username"
                                       autofocus
                                       minlength="1"
                                       maxlength="100"
                                       value="<?= isset($_POST['forgot_username']) ? htmlspecialchars($_POST['forgot_username'], ENT_QUOTES, 'UTF-8') : '' ?>">
                            </div>
                            <span class="forgot-error" id="forgotUsernameError" role="alert" aria-live="polite"></span>
                        </div>
                        <button type="submit" class="forgot-btn forgot-btn--primary forgot-btn--submit" id="forgotSubmitBtn">
                            <span class="btn-text">Request reset</span>
                            <span class="btn-spinner d-none" aria-hidden="true"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="forgot-footer">
        <p class="forgot-footer-text">Golden Z-5 Security and Investigation Agency, Inc. · HR Management System</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/assets/js/forgot_password.js"></script>
</body>
</html>
