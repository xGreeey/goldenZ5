<?php
/**
 * FORGOT PASSWORD PAGE
 *
 * Standalone page for password reset requests. User enters username;
 * we show a generic message (do not reveal if account exists).
 * Links back to login (index.php).
 * Design matches login page: split panel layout with branded left panel.
 */

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

$appRoot = dirname(__DIR__, 2);

// Bootstrap (load .env) — before any output
if (is_file($appRoot . '/bootstrap/app.php')) {
    require_once $appRoot . '/bootstrap/app.php';
}

// Middleware: session first (uses config/session.php, enforces idle/absolute timeout)
require_once $appRoot . '/app/middleware/SessionMiddleware.php';
require_once $appRoot . '/app/middleware/CsrfMiddleware.php';
SessionMiddleware::handle();

// Database and CSRF/security helpers (csrf_validate, csrf_field, log_security_event)
require_once $appRoot . '/config/database.php';
require_once $appRoot . '/includes/security.php';

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <meta name="csrf-token" content="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <title>Forgot password – Golden Z-5 HR</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.php">
    <link rel="icon" type="image/jpeg" href="/assets/images/goldenz-logo.jpg">
    <link rel="apple-touch-icon" href="/assets/images/goldenz-logo.jpg">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="/assets/css/login.css" rel="stylesheet">
    
    <!-- Security Headers -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
</head>
<body>
    <!-- Floating Background Elements - Same as login -->
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
    
    <!-- Split Container - Same layout as login -->
    <div class="login-split-container">
        <!-- Left Branded Panel -->
        <div class="login-branded-panel">
            <div class="branded-content">
                <img src="/assets/images/goldenz-logo.jpg" alt="Golden Z-5 Security and Investigation Agency, Inc. Logo" class="branded-logo reveal-item" onerror="this.style.display='none'">
                <h1 class="branded-headline reveal-item">Golden Z-5 Security and Investigation Agency, Inc.</h1>
                <p class="branded-description reveal-item">
                    Human Resources Management System<br>
                    Licensed by PNP-CSG-SAGSD | Registered with SEC
                </p>
                
                <!-- See More Button -->
                <button type="button" class="see-more-btn reveal-item" id="seeMoreBtn" aria-label="View system information and features">
                    <i class="fas fa-info-circle" aria-hidden="true"></i> System Information
                </button>
                
                <!-- Social Links -->
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

        <!-- Right Form Panel -->
        <div class="login-form-panel">
            <div class="auth-form-container reveal-form">
                <div class="auth-form-card">
                    <?php if ($show_result): ?>
                        <!-- Result State -->
                        <div class="text-center">
                            <div class="mb-4">
                                <?php if ($forgot_success): ?>
                                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; border-radius: 50%; background: #f0fdf4; border: 2px solid #bbf7d0; color: #166534; font-size: 2.25rem;">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; border-radius: 50%; background: #fef2f2; border: 2px solid #fecaca; color: #991b1b; font-size: 2.25rem;">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h2 class="auth-title mb-3">
                                <?= $forgot_success ? 'Request received' : 'Please try again' ?>
                            </h2>
                            <p class="auth-subtitle mb-4" style="color: var(--text-muted);">
                                <?= htmlspecialchars($forgot_message, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <a href="/" class="btn btn-block" style="background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%); color: var(--bg-dark); border: none; border-radius: 10px; padding: 0.85rem 1.5rem; font-weight: 600;">
                                <span>Back to sign in</span>
                                <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Form State -->
                        <div class="auth-header">
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--gold-light) 0%, var(--gold-primary) 100%); border-radius: 16px; color: var(--charcoal); font-size: 1.75rem;">
                                    <i class="fas fa-key"></i>
                                </div>
                            </div>
                            <h2 class="auth-title">Forgot password?</h2>
                            <p class="auth-subtitle">Enter your username. Contact your system administrator or HR to reset your password.</p>
                        </div>

                        <form method="POST" class="auth-form" id="forgotPasswordForm" novalidate>
                            <input type="hidden" name="forgot_password" value="1">
                            <?= csrf_field() ?>
                            
                            <div class="form-group">
                                <label for="forgot_username" class="form-label">
                                    Username <span class="required-indicator">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon" aria-hidden="true"></i>
                                    <input type="text"
                                           id="forgot_username"
                                           name="forgot_username"
                                           class="form-control"
                                           placeholder="Enter your username"
                                           required
                                           autocomplete="username"
                                           autofocus
                                           minlength="1"
                                           maxlength="100"
                                           value="<?= isset($_POST['forgot_username']) ? htmlspecialchars($_POST['forgot_username'], ENT_QUOTES, 'UTF-8') : '' ?>">
                                </div>
                                <span class="form-error" id="forgotUsernameError" role="alert" aria-live="polite" style="display: none; font-size: 0.85rem; color: #dc2626; margin-top: 0.25rem;"></span>
                            </div>

                            <div class="form-submit">
                                <button type="submit" class="btn btn-block" id="forgotSubmitBtn">
                                    <span class="btn-text">Request reset</span>
                                    <span class="btn-spinner d-none" aria-hidden="true"><i class="fas fa-spinner fa-spin"></i></span>
                                </button>
                            </div>
                        </form>

                        <div class="form-footer">
                            <div class="help-text">
                                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                                <a href="/" class="forgot-password-link">Back to sign in</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="position: relative; z-index: 1; padding: 1.5rem; text-align: center;">
        <p style="font-size: 0.8rem; color: rgba(255, 255, 255, 0.5); margin: 0;">
            Golden Z-5 Security and Investigation Agency, Inc. · HR Management System
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/login.js"></script>
    <script nonce="<?= htmlspecialchars($cspNonce) ?>">
    // Forgot password form validation (reuse login.js patterns)
    (function () {
        const form = document.getElementById('forgotPasswordForm');
        if (!form) return;
        
        const usernameInput = document.getElementById('forgot_username');
        const usernameError = document.getElementById('forgotUsernameError');
        const submitBtn = document.getElementById('forgotSubmitBtn');
        
        function showError(el, message) {
            if (!el) return;
            el.textContent = message;
            el.style.display = message ? 'block' : 'none';
        }
        
        function validateUsername() {
            const value = usernameInput ? usernameInput.value.trim() : '';
            if (value.length === 0) {
                showError(usernameError, 'Please enter your username.');
                if (usernameInput) usernameInput.setAttribute('aria-invalid', 'true');
                return false;
            }
            showError(usernameError, '');
            if (usernameInput) usernameInput.setAttribute('aria-invalid', 'false');
            return true;
        }
        
        if (usernameInput) {
            usernameInput.addEventListener('blur', function () {
                if (usernameInput.value.trim().length > 0) {
                    validateUsername();
                }
            });
            
            usernameInput.addEventListener('input', function () {
                if (usernameError && usernameError.textContent) {
                    validateUsername();
                }
            });
        }
        
        form.addEventListener('submit', function (e) {
            if (!validateUsername()) {
                e.preventDefault();
                if (usernameInput) usernameInput.focus();
                return;
            }
            
            if (submitBtn) {
                const btnText = submitBtn.querySelector('.btn-text');
                const btnSpinner = submitBtn.querySelector('.btn-spinner');
                if (btnText) btnText.classList.add('d-none');
                if (btnSpinner) btnSpinner.classList.remove('d-none');
                submitBtn.disabled = true;
            }
        });
    })();
    </script>
</body>
</html>
