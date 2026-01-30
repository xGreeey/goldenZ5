<?php
/**
 * AUTHENTICATION FLOW - LANDING PAGE (LOGIN & FIRST-TIME PASSWORD CHANGE)
 * 
 * This file handles the complete authentication flow:
 * 
 * 1. FIRST-TIME LOGIN DETECTION:
 *    - Users log in using temporary password
 *    - System checks if password_changed_at is NULL (first-time login)
 *    - If NULL: Shows password change modal, blocks access until changed
 *    - If NOT NULL: Normal login, proceeds to dashboard
 * 
 * 2. PASSWORD RESET (First Login):
 *    - Modal displayed automatically on first login
 *    - Validates new password (min 8 chars, passwords match)
 *    - Hashes password with bcrypt
 *    - Updates password_changed_at timestamp
 *    - Auto-logs user in after password change
 * 
 * 3. ROLE-BASED DASHBOARD ACCESS (users.role RBA):
 *    - super_admin → /super-admin/dashboard
 *    - developer → /developer/dashboard
 *    - hr, hr_admin, admin, accounting, operation, logistics, employee → /human-resource/
 *    - Sets session variables: user_id, user_role, username, name, employee_id, department
 * 
 * 4. SECURITY & AUDIT:
 *    - Account lockout check (locked_until > current time)
 *    - Failed login attempt tracking (5 attempts = 30 min lockout)
 *    - IP address and user agent logging
 *    - Security event logging (login attempts, password changes)
 *    - Audit trail logging
 * 
 * Flow Diagram:
 *   User Login → Verify Password → Check password_changed_at
 *   → If NULL: Show Password Change Modal → User Sets New Password → Auto-Login → Redirect
 *   → If NOT NULL: Update Last Login → Set Session → Redirect to Role-Based Dashboard
 */

ob_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);


// Start session first (before any output) — secure params in config/session.php
require_once __DIR__ . '/../config/session.php';

// Per-request CSP nonce (used in CSP header and in inline <script nonce="...">)
$cspNonce = base64_encode(random_bytes(16));

// Security headers (local HTTPS friendly)
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443');
if ($isHttps) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}
// CSP: same-origin + CDNs; inline script only via nonce (no unsafe-inline)
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

// Bootstrap application (with error handling)
try {
    if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
        require_once __DIR__ . '/../bootstrap/app.php';
    } else {
        // Fallback if bootstrap doesn't exist
        require_once __DIR__ . '/../bootstrap/autoload.php';
    }
} catch (Exception $e) {
    error_log('Bootstrap error: ' . $e->getMessage());
    // Continue anyway
}

// Include database and CSRF/security helpers
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/security.php';

/**
 * Encryption helper functions for Remember Me password storage
 */
if (!function_exists('encrypt_remember_password')) {
    /**
     * Encrypt password for secure cookie storage
     * @param string $password
     * @return string Base64 encoded encrypted password
     */
    function encrypt_remember_password($password) {
        // Use a secret key - in production, this should be in .env file
        $secret_key = $_ENV['APP_KEY'] ?? 'goldenz_hr_secret_key_change_in_production_' . md5(__FILE__);
        $key = hash('sha256', $secret_key, true);
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($password, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
}

if (!function_exists('decrypt_remember_password')) {
    /**
     * Decrypt password from cookie
     * @param string $encrypted_password Base64 encoded encrypted password
     * @return string|false Decrypted password or false on failure
     */
    function decrypt_remember_password($encrypted_password) {
        try {
            $secret_key = $_ENV['APP_KEY'] ?? 'goldenz_hr_secret_key_change_in_production_' . md5(__FILE__);
            $key = hash('sha256', $secret_key, true);
            $data = base64_decode($encrypted_password);
            if ($data === false || strlen($data) < 16) {
                return false;
            }
            $iv = substr($data, 0, 16);
            $encrypted = substr($data, 16);
            $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
            return $decrypted !== false ? $decrypted : false;
        } catch (Exception $e) {
            error_log('Password decryption error: ' . $e->getMessage());
            return false;
        }
    }
}

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    // Clear remember token from database if user is logged in
    if (isset($_SESSION['user_id'])) {
        try {
            db_execute('UPDATE users SET remember_token = NULL WHERE id = ?', [$_SESSION['user_id']]);
        } catch (Exception $e) {
            error_log('Error clearing remember token on logout: ' . $e->getMessage());
        }
    }
    
    // Clear remember token cookie (this prevents auto-login)
    // BUT keep remembered_username cookie so username field can be pre-filled
    if (isset($_COOKIE['remember_token'])) {
        $cookie_params = session_get_cookie_params();
        $cookie_domain = $cookie_params['domain'] ?? '';
        $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                   (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
        if (PHP_VERSION_ID >= 70300) {
            setcookie('remember_token', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'domain' => $cookie_domain,
                'secure' => $is_https,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        } else {
            setcookie('remember_token', '', time() - 3600, '/', $cookie_domain, $is_https, true);
        }
    }
    // NOTE: We intentionally do NOT clear remembered_username and remembered_password cookies here
    // so both username and password fields can be pre-filled after logout
    
    $_SESSION = [];
    session_unset();
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        if (PHP_VERSION_ID >= 70300) {
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        } else {
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
    }
    session_destroy();
    header('Location: /');
    exit;
}

// If already logged in (and password changed), redirect to appropriate portal (role-based from users.role)
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_role']) && !isset($_SESSION['require_password_change'])) {
    $role = $_SESSION['user_role'];
    if ($role === 'super_admin') {
        header('Location: /super-admin/dashboard');
        exit;
    }
    if ($role === 'developer') {
        header('Location: /developer/dashboard');
        exit;
    }
    // human-resource portal: hr, hr_admin, admin, accounting, operation, logistics, employee
    if (in_array($role, ['hr', 'hr_admin', 'admin', 'accounting', 'operation', 'logistics', 'employee'], true)) {
        header('Location: /human-resource/');
        exit;
    }
}

/**
 * PASSWORD RESET HANDLER (First-Time Login)
 * 
 * When a user logs in with a temporary password (password_changed_at = NULL),
 * they are required to set a new permanent password before accessing the system.
 * 
 * Process:
 * 1. Validates password requirements (min 8 chars, passwords match)
 * 2. Hashes new password using bcrypt (password_hash with PASSWORD_DEFAULT)
 * 3. Updates password_hash and sets password_changed_at = NOW()
 * 4. Logs security event and audit trail
 * 5. Auto-logs user in and redirects to role-based dashboard
 */
// Handle password change (first login)
$password_change_error = '';
$password_change_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    if (!csrf_validate()) {
        header('HTTP/1.1 403 Forbidden');
        $password_change_error = 'Invalid security token. Please refresh the page and try again.';
    } elseif (!isset($_SESSION['require_password_change']) || !$_SESSION['require_password_change']) {
        $password_change_error = 'Invalid request. Please login again.';
    } else {
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $user_id = $_SESSION['temp_user_id'] ?? null;
        
        // Validate passwords
        if (empty($new_password) || empty($confirm_password)) {
            $password_change_error = 'Please fill in all password fields.';
        } elseif (strlen($new_password) < 8) {
            $password_change_error = 'Password must be at least 8 characters long.';
        } elseif ($new_password !== $confirm_password) {
            $password_change_error = 'Passwords do not match.';
        } elseif ($user_id) {
            try {
                // Hash new password
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update password in database using helper function if available
                if (function_exists('update_user_password')) {
                    $update_result = update_user_password($user_id, $new_password);
                } else {
                    // Fallback: prepared statement only (SQL injection safe)
                    db_execute('UPDATE users SET password_hash = ?, password_changed_at = NOW() WHERE id = ?', [$new_password_hash, $user_id]);
                    $update_result = true;
                }
                
                if (!$update_result) {
                    throw new Exception('Failed to update password in database');
                }
                
                // Regenerate session ID to prevent fixation
                session_regenerate_id(true);
                // Set session variables for login (no passwords stored)
                $_SESSION['user_id'] = $_SESSION['temp_user_id'];
                $_SESSION['user_role'] = $_SESSION['temp_role'];
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $_SESSION['temp_username'];
                $_SESSION['name'] = $_SESSION['temp_name'];
                $_SESSION['employee_id'] = $_SESSION['temp_employee_id'] ?? null;
                $_SESSION['department'] = $_SESSION['temp_department'] ?? null;

                // Update last login and reset failed attempts
                db_execute(
                    'UPDATE users SET last_login = NOW(), last_login_ip = ?, failed_login_attempts = 0, locked_until = NULL WHERE id = ?',
                    [$_SERVER['REMOTE_ADDR'] ?? null, $user_id]
                );
                
                // Log security event
                if (function_exists('log_security_event')) {
                    log_security_event('Password Changed - First Login', "User ID: $user_id - Username: " . ($_SESSION['temp_username'] ?? 'Unknown'));
                }
                
                // Clear temporary session variables
                unset($_SESSION['temp_user_id'], $_SESSION['temp_username'], $_SESSION['temp_name'], 
                      $_SESSION['temp_role'], $_SESSION['temp_employee_id'], $_SESSION['temp_department'], 
                      $_SESSION['require_password_change']);

                csrf_rotate();

                // Redirect based on role (RBA from users.role)
                $role = $_SESSION['user_role'];
                if ($role === 'super_admin') {
                    header('Location: /super-admin/dashboard');
                    exit;
                } elseif ($role === 'developer') {
                    header('Location: /developer/dashboard');
                    exit;
                } else {
                    header('Location: /human-resource/');
                    exit;
                }
            } catch (Exception $e) {
                $password_change_error = 'Error updating password. Please try again.';
                error_log('Password change error: ' . $e->getMessage());
            }
        } else {
            $password_change_error = 'Invalid request. Please login again.';
        }
    }
}

/**
 * LOGIN HANDLER
 * 
 * Handles user authentication with the following security features:
 * 
 * Security Checks:
 * - Account lockout verification (locked_until > current time)
 * - Failed login attempt tracking (increments on failure, resets on success)
 * - Account lockout after 5 failed attempts (30 minutes)
 * - IP address and user agent logging
 * 
 * First-Time Login Detection:
 * - Checks if password_changed_at is NULL
 * - If NULL: Stores user data in temporary session, shows password change modal
 * - If NOT NULL: Normal login flow, redirects to dashboard
 * 
 * Role-Based Redirect:
 * - developer → Developer Portal
 * - hr → Human Resource Portal
 * - All other roles → HR Admin Portal
 */
// Handle login
$error = '';
$debug_info = [];
$login_status_error = $_SESSION['login_status_error'] ?? '';
$login_status_message = $_SESSION['login_status_message'] ?? '';
// Clear status error from session after reading
unset($_SESSION['login_status_error']);
unset($_SESSION['login_status_message']);

$show_password_change_modal = isset($_SESSION['require_password_change']) && $_SESSION['require_password_change'];

/**
 * REMEMBER ME TOKEN CHECK
 * Check for remember token cookie and auto-login if valid
 * This runs before POST handling to restore sessions from remember tokens
 */
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if (isset($_COOKIE['remember_token']) && !empty($_COOKIE['remember_token'])) {
        try {
            $token_data = $_COOKIE['remember_token'];
            
            // Token format: user_id|token (for optimization) or just token (backward compatibility)
            $token_parts = explode('|', $token_data, 2);
            $user_id = null;
            $token = $token_data;
            
            if (count($token_parts) === 2 && is_numeric($token_parts[0])) {
                // New format: user_id|token
                $user_id = (int)$token_parts[0];
                $token = $token_parts[1];
            }
            
            if ($user_id) {
                // Optimized: Lookup by user_id first (prepared statement - SQL injection safe)
                $user = db_fetch_one(
                    "SELECT id, username, password_hash, name, role, status, employee_id, department,
                            remember_token, failed_login_attempts, locked_until, password_changed_at,
                            two_factor_enabled, two_factor_secret
                     FROM users
                     WHERE id = ? AND remember_token IS NOT NULL AND remember_token != ''",
                    [$user_id]
                );
                $token_valid = $user && password_verify($token, $user['remember_token']);
            } else {
                // Fallback: Check all users (for backward compatibility with old tokens)
                $users = db_fetch_all(
                    "SELECT id, username, password_hash, name, role, status, employee_id, department,
                            remember_token, failed_login_attempts, locked_until, password_changed_at,
                            two_factor_enabled, two_factor_secret
                     FROM users
                     WHERE remember_token IS NOT NULL AND remember_token != ''"
                );
                $user = null;
                $token_valid = false;
                foreach ($users as $u) {
                    if (password_verify($token, $u['remember_token'])) {
                        $user = $u;
                        $token_valid = true;
                        break;
                    }
                }
            }
            
            if ($token_valid && $user) {
                // Check user status
                if ($user['status'] === 'inactive' || $user['status'] === 'suspended') {
                    // Invalid status - clear remember token
                    db_execute('UPDATE users SET remember_token = NULL WHERE id = ?', [$user['id']]);
                    setcookie('remember_token', '', time() - 3600, '/');
                } elseif (!empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {
                    // Account is locked - don't auto-login
                    // Token remains valid for when account is unlocked
                } else {
                    // Regenerate session ID after remember-me restore
                    session_regenerate_id(true);
                    // Valid token - restore session (no passwords in session)
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['employee_id'] = $user['employee_id'] ?? null;
                    $_SESSION['department'] = $user['department'] ?? null;

                    // Update last login
                    db_execute('UPDATE users SET last_login = NOW(), last_login_ip = ? WHERE id = ?', [$_SERVER['REMOTE_ADDR'] ?? null, $user['id']]);
                    
                    // Redirect based on role (RBA from users.role)
                    if ($user['role'] === 'super_admin') {
                        header('Location: /super-admin/dashboard');
                        exit;
                    } elseif ($user['role'] === 'developer') {
                        header('Location: /developer/dashboard');
                        exit;
                    } else {
                        header('Location: /human-resource/');
                        exit;
                    }
                }
            }
        } catch (Exception $e) {
            error_log('Remember token check error: ' . $e->getMessage());
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    $wantsJson = $isAjaxRequest || (isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    $respondJson = function (array $payload, int $statusCode = 200) {
        if ($statusCode !== 200) {
            header('HTTP/1.1 ' . (int) $statusCode . ' ' . ($statusCode === 403 ? 'Forbidden' : 'OK'));
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    };

    if (!csrf_validate()) {
        header('HTTP/1.1 403 Forbidden');
        if ($wantsJson) {
            $respondJson(['success' => false, 'error' => 'csrf', 'message' => 'Invalid security token. Please refresh and try again.'], 403);
        }
        $error = 'Invalid security token. Please refresh the page and try again.';
    } else {

    $debug_info[] = "POST request received";
    $debug_info[] = "POST data: " . print_r($_POST, true);
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $debug_info[] = "Username: " . ($username ?: '(empty)');
    $debug_info[] = "Password: " . ($password ? '(provided)' : '(empty)');
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
        $debug_info[] = "Validation failed: empty fields";
        if ($wantsJson) {
            $respondJson([
                'success' => false,
                'error' => 'validation',
                'message' => 'Please enter both username and password.'
            ]);
        }
    } else {
        try {
            $debug_info[] = "Database connection successful";
            
            // Lookup user by username (prepared statement - SQL injection safe)
            $user = db_fetch_one(
                "SELECT id, username, password_hash, name, role, status, employee_id, department,
                        failed_login_attempts, locked_until, password_changed_at,
                        two_factor_enabled, two_factor_secret
                 FROM users WHERE username = ? LIMIT 1",
                [$username]
            );
            
            if ($user) {
                $debug_info[] = "User found: " . $user['username'] . " (Role: " . $user['role'] . ", Status: " . $user['status'] . ")";
                
                // Check user status first (before password verification)
                // IMPORTANT: Check status BEFORE password verification so error shows immediately
                if ($user['status'] === 'inactive') {
                    $_SESSION['login_status_error'] = 'inactive';
                    $_SESSION['login_status_message'] = 'Your account is currently inactive. Please contact your administrator to activate your account.';
                    $error = 'inactive';
                    $debug_info[] = "User account is inactive - blocking login";
                    if ($wantsJson) {
                        $respondJson([
                            'success' => false,
                            'error' => 'status',
                            'status' => 'inactive',
                            'message' => $_SESSION['login_status_message']
                        ]);
                    }
                    // Redirect back to login page to show the modal (non-AJAX fallback)
                    header('Location: /');
                    exit;
                } elseif ($user['status'] === 'suspended') {
                    $_SESSION['login_status_error'] = 'suspended';
                    
                    // Build a suspension message with remaining days if locked_until is set
                    $suspension_message = 'Your account has been suspended.';
                    if (!empty($user['locked_until'])) {
                        $locked_ts = strtotime($user['locked_until']);
                        if ($locked_ts && $locked_ts > time()) {
                            $seconds_remaining = $locked_ts - time();
                            $days_remaining = (int)ceil($seconds_remaining / 86400);
                            $end_date = date('M d, Y H:i', $locked_ts);
                            $day_label = $days_remaining === 1 ? 'day' : 'days';
                            $suspension_message .= " Suspension ends in {$days_remaining} {$day_label} (until {$end_date}).";
                        }
                    }
                    $suspension_message .= ' Please contact your administrator for assistance.';
                    
                    $_SESSION['login_status_message'] = $suspension_message;
                    $error = 'suspended';
                    $debug_info[] = "User account is suspended - blocking login";
                    if ($wantsJson) {
                        $respondJson([
                            'success' => false,
                            'error' => 'status',
                            'status' => 'suspended',
                            'message' => $_SESSION['login_status_message']
                        ]);
                    }
                    // Redirect back to login page to show the modal (non-AJAX fallback)
                    header('Location: /');
                    exit;
                } elseif (!empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {
                    $error = 'Account is temporarily locked. Please try again later.';
                    $debug_info[] = "Account locked";
                    if ($wantsJson) {
                        $respondJson([
                            'success' => false,
                            'error' => 'locked',
                            'message' => $error
                        ]);
                    }
                } elseif (password_verify($password, $user['password_hash'])) {
                    $debug_info[] = "Password verified successfully";
                    
                    // Log successful login attempt (Security & Audit)
                    if (function_exists('log_security_event')) {
                        log_security_event('Login Attempt', "User: {$user['username']} - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown'));
                    }
                    if (function_exists('log_audit_event')) {
                        log_audit_event('LOGIN_ATTEMPT', 'users', $user['id'], null, ['login_time' => date('Y-m-d H:i:s')], $user['id']);
                    }
                    
                    // First-time password change check DISABLED
                    // Previously checked if password_changed_at is NULL to force password change
                    // Now users can login directly without changing password
                    $is_temporary_password = false; // Disabled - always allow direct login
                    
                    if ($is_temporary_password) {
                        // First login with temporary password - show password change modal
                        // This block is now disabled (is_temporary_password always false)
                        $_SESSION['temp_user_id'] = $user['id'];
                        $_SESSION['temp_username'] = $user['username'];
                        $_SESSION['temp_name'] = $user['name'];
                        $_SESSION['temp_role'] = $user['role'];
                        $_SESSION['temp_employee_id'] = $user['employee_id'] ?? null;
                        $_SESSION['temp_department'] = $user['department'] ?? null;
                        $_SESSION['require_password_change'] = true;
                        $debug_info[] = "Temporary password detected - requiring password change";
                        // Don't redirect, show password change modal instead
                        if ($wantsJson) {
                            $respondJson([
                                'success' => true,
                                'redirect' => '/' // shows password-change UI when enabled
                            ]);
                        }
                    } else {
                        // Allowed roles for login (must match users.role enum: super_admin, hr_admin, hr, admin, accounting, operation, logistics, employee, developer)
                        if (!in_array($user['role'], ['super_admin', 'hr_admin', 'hr', 'admin', 'accounting', 'operation', 'logistics', 'employee', 'developer'], true)) {
                            $error = 'This account role is not permitted to sign in.';
                            $debug_info[] = "Role not allowed: " . $user['role'];
                            if ($wantsJson) {
                                $respondJson([
                                    'success' => false,
                                    'error' => 'role_not_permitted',
                                    'message' => $error
                                ]);
                            }
                        } else {
                            // Determine if this user must pass 2FA before accessing the dashboard
                            $requires_2fa = in_array($user['role'], ['super_admin', 'admin'], true)
                                && !empty($user['two_factor_enabled'])
                                && !empty($user['two_factor_secret']);

                            if ($requires_2fa) {
                                // Store minimal user context for the 2FA step
                                $_SESSION['pending_2fa_user_id'] = $user['id'];
                                $_SESSION['pending_2fa_username'] = $user['username'];
                                $_SESSION['pending_2fa_name'] = $user['name'];
                                $_SESSION['pending_2fa_role'] = $user['role'];
                                $_SESSION['pending_2fa_employee_id'] = $user['employee_id'] ?? null;
                                $_SESSION['pending_2fa_department'] = $user['department'] ?? null;

                                $debug_info[] = "2FA required - redirecting to 2FA verification page";
                                if ($wantsJson) {
                                    $respondJson([
                                        'success' => true,
                                        'redirect' => '2fa.php'
                                    ]);
                                }
                                header('Location: 2fa.php');
                                exit;
                            }

                            // No 2FA required – complete login immediately
                            // Update last login and reset failed attempts
                            db_execute(
                                'UPDATE users SET last_login = NOW(), last_login_ip = ?, failed_login_attempts = 0, locked_until = NULL WHERE id = ?',
                                [$_SERVER['REMOTE_ADDR'] ?? null, $user['id']]
                            );
                            
                            // Log successful login
                            // Log to system logs
                            if (function_exists('log_system_event')) {
                                log_system_event('info', "User logged in: {$user['username']} ({$user['name']})", 'authentication', [
                                    'user_id' => $user['id'],
                                    'role' => $user['role'],
                                    'ip' => $_SERVER['REMOTE_ADDR'] ?? null
                                ]);
                            }
                            
                            if (function_exists('log_security_event')) {
                                log_security_event('Login Success', "User: {$user['username']} ({$user['name']}) - Role: {$user['role']} - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown'));
                            }
                            
                            // Regenerate session ID on login to prevent fixation
                            session_regenerate_id(true);
                            // Set session variables (no passwords in session)
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user_role'] = $user['role'];
                            $_SESSION['logged_in'] = true;
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['name'] = $user['name'];
                            $_SESSION['employee_id'] = $user['employee_id'] ?? null;
                            $_SESSION['department'] = $user['department'] ?? null;

                            $debug_info[] = "Session variables set";

                            csrf_rotate();

                            // Handle "Remember Me" functionality
                            $remember_me = isset($_POST['remember_me']) && $_POST['remember_me'] == '1';
                            $cookie_expiry = time() + (7 * 24 * 60 * 60); // 7 days
                            $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                                       (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
                            
                            if ($remember_me) {
                                // Generate a secure random token
                                $remember_token = bin2hex(random_bytes(32)); // 64 character token
                                
                                // Hash the token before storing in database
                                $hashed_token = password_hash($remember_token, PASSWORD_DEFAULT);
                                
                                // Store hashed token in database
                                db_execute('UPDATE users SET remember_token = ? WHERE id = ?', [$hashed_token, $user['id']]);
                                
                                // Set remember token cookie with 7 days expiry
                                // Format: user_id|token for optimized lookup
                                $token_cookie_value = $user['id'] . '|' . $remember_token;
                                // Set cookie with proper parameters
                                $cookie_params = session_get_cookie_params();
                                $cookie_domain = $cookie_params['domain'] ?? '';
                                
                                // Use array syntax for PHP 7.3+, fallback for older versions
                                if (PHP_VERSION_ID >= 70300) {
                                    setcookie('remember_token', $token_cookie_value, [
                                        'expires' => $cookie_expiry,
                                        'path' => '/',
                                        'domain' => $cookie_domain,
                                        'secure' => $is_https,
                                        'httponly' => true,
                                        'samesite' => 'Lax'
                                    ]);
                                    
                                    // Store username in cookie for convenience (even after logout)
                                    // This cookie persists even after logout so username can be pre-filled
                                    setcookie('remembered_username', $user['username'], [
                                        'expires' => $cookie_expiry,
                                        'path' => '/',
                                        'domain' => $cookie_domain,
                                        'secure' => $is_https,
                                        'httponly' => false, // Not HttpOnly so JavaScript can access if needed
                                        'samesite' => 'Lax'
                                    ]);
                                    
                                    // Store encrypted password in cookie (even after logout)
                                    // Password is encrypted for security, but not HttpOnly so JS can read it
                                    $encrypted_password = encrypt_remember_password($password);
                                    if ($encrypted_password) {
                                        setcookie('remembered_password', $encrypted_password, [
                                            'expires' => $cookie_expiry,
                                            'path' => '/',
                                            'domain' => $cookie_domain,
                                            'secure' => $is_https,
                                            'httponly' => false, // Not HttpOnly so JavaScript can read and fill password field
                                            'samesite' => 'Lax'
                                        ]);
                                    }
                                } else {
                                    // Fallback for PHP < 7.3
                                    setcookie('remember_token', $token_cookie_value, $cookie_expiry, '/', $cookie_domain, $is_https, true);
                                    setcookie('remembered_username', $user['username'], $cookie_expiry, '/', $cookie_domain, $is_https, false);
                                    
                                    // Store encrypted password
                                    $encrypted_password = encrypt_remember_password($password);
                                    if ($encrypted_password) {
                                        setcookie('remembered_password', $encrypted_password, $cookie_expiry, '/', $cookie_domain, $is_https, false);
                                    }
                                }
                                
                                $debug_info[] = "Remember me token set for 7 days";
                            } else {
                                // Clear any existing remember token if user didn't check remember me
                                db_execute('UPDATE users SET remember_token = NULL WHERE id = ?', [$user['id']]);
                                
                                // Clear remember token cookie if it exists
                                if (isset($_COOKIE['remember_token'])) {
                                    $cookie_params = session_get_cookie_params();
                                    $cookie_domain = $cookie_params['domain'] ?? '';
                                    if (PHP_VERSION_ID >= 70300) {
                                        setcookie('remember_token', '', [
                                            'expires' => time() - 3600,
                                            'path' => '/',
                                            'domain' => $cookie_domain,
                                            'secure' => $is_https,
                                            'httponly' => true,
                                            'samesite' => 'Lax'
                                        ]);
                                    } else {
                                        setcookie('remember_token', '', time() - 3600, '/', $cookie_domain, $is_https, true);
                                    }
                                }
                                
                                // Clear remembered username and password cookies (only if user unchecks remember me)
                                if (isset($_COOKIE['remembered_username'])) {
                                    $cookie_params = session_get_cookie_params();
                                    $cookie_domain = $cookie_params['domain'] ?? '';
                                    if (PHP_VERSION_ID >= 70300) {
                                        setcookie('remembered_username', '', [
                                            'expires' => time() - 3600,
                                            'path' => '/',
                                            'domain' => $cookie_domain,
                                            'secure' => $is_https,
                                            'httponly' => false,
                                            'samesite' => 'Lax'
                                        ]);
                                    } else {
                                        setcookie('remembered_username', '', time() - 3600, '/', $cookie_domain, $is_https, false);
                                    }
                                }
                                
                                // Clear remembered password cookie
                                if (isset($_COOKIE['remembered_password'])) {
                                    $cookie_params = session_get_cookie_params();
                                    $cookie_domain = $cookie_params['domain'] ?? '';
                                    if (PHP_VERSION_ID >= 70300) {
                                        setcookie('remembered_password', '', [
                                            'expires' => time() - 3600,
                                            'path' => '/',
                                            'domain' => $cookie_domain,
                                            'secure' => $is_https,
                                            'httponly' => true,
                                            'samesite' => 'Lax'
                                        ]);
                                    } else {
                                        setcookie('remembered_password', '', time() - 3600, '/', $cookie_domain, $is_https, false);
                                    }
                                }
                            }
                            
                            // Redirect based on role (Role-Based Dashboard Access)
                            if ($user['role'] === 'super_admin') {
                                $debug_info[] = "Redirecting to: /super-admin/dashboard";
                                if ($wantsJson) {
                                    $respondJson([
                                        'success' => true,
                                        'redirect' => '/super-admin/dashboard'
                                    ]);
                                }
                                header('Location: /super-admin/dashboard');
                                exit;
                            } elseif ($user['role'] === 'developer') {
                                $debug_info[] = "Redirecting to: ../developer/dashboard";
                                if ($wantsJson) {
                                    $respondJson([
                                        'success' => true,
                                        'redirect' => '/developer/dashboard'
                                    ]);
                                }
                                header('Location: /developer/dashboard');
                                exit;
                            } else {
                                // human-resource portal: hr, hr_admin, admin, accounting, operation, logistics, employee
                                $debug_info[] = "Redirecting to: /human-resource/";
                                if ($wantsJson) {
                                    $respondJson([
                                        'success' => true,
                                        'redirect' => '/human-resource/'
                                    ]);
                                }
                                header('Location: /human-resource/');
                                exit;
                            }
                        }
                    }
                } else {
                    $error = 'Invalid username or password';
                    $debug_info[] = "Password verification failed";
                    if ($wantsJson) {
                        $respondJson([
                            'success' => false,
                            'error' => 'invalid_credentials',
                            'message' => 'Invalid credentials. Verify your username and password and try again.'
                        ]);
                    }
                    
                    // Log failed login attempt
                    // Log to system logs
                    if (function_exists('log_system_event')) {
                        log_system_event('warning', "Failed login attempt for user: {$user['username']}", 'authentication', [
                            'user_id' => $user['id'],
                            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                            'failed_attempts' => $user['failed_login_attempts'] + 1
                        ]);
                    }
                    
                    if (function_exists('log_security_event')) {
                        log_security_event('Login Failed', "User: {$user['username']} - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown'));
                    }
                    
                    // Increment failed login attempts (Security & Audit: Login attempt limits)
                    $failed_attempts = ($user['failed_login_attempts'] ?? 0) + 1;
                    $locked_until = null;
                    if ($failed_attempts >= 5) {
                        $locked_until = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                        // Log account lockout
                        // Log to system logs
                        if (function_exists('log_system_event')) {
                            log_system_event('error', "Account locked: {$user['username']} - 5 failed login attempts", 'authentication', [
                                'user_id' => $user['id'],
                                'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                                'lockout_duration' => '30 minutes'
                            ]);
                        }
                        
                        if (function_exists('log_security_event')) {
                            log_security_event('Account Locked', "User: {$user['username']} - Locked for 30 minutes due to 5 failed login attempts");
                        }
                    }
                    db_execute('UPDATE users SET failed_login_attempts = ?, locked_until = ? WHERE id = ?', [$failed_attempts, $locked_until, $user['id']]);
                }
            } else {
                $error = 'Invalid username or password';
                $debug_info[] = "User not found or inactive";
                if ($wantsJson) {
                    $respondJson([
                        'success' => false,
                        'error' => 'invalid_credentials',
                        'message' => 'Invalid credentials. Verify your username and password and try again.'
                    ]);
                }
            }
        } catch (Exception $e) {
            $error = 'Login failed. Please try again.';
            $debug_info[] = "Exception: " . $e->getMessage();
            error_log('Login error: ' . $e->getMessage());
            if ($wantsJson) {
                $respondJson([
                    'success' => false,
                    'error' => 'server',
                    'message' => $error
                ]);
            }
        }
    }
    }
}

// Log debug info
if (!empty($debug_info)) {
    error_log('Login debug: ' . implode(' | ', $debug_info));
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <meta name="csrf-token" content="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <title>Login</title>
    
    <!-- Favicon: circular SVG with embedded logo (favicon.php) so logo is visible; JPG fallback -->
    <link rel="icon" type="image/svg+xml" href="assets/favicon.php">
    <link rel="icon" type="image/jpeg" href="assets/images/goldenz-logo.jpg">
    <link rel="apple-touch-icon" href="assets/images/goldenz-logo.jpg">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="assets/css/login.css" rel="stylesheet">

    <!-- Security Headers -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
</head>
<body>
    <!-- Floating Background Elements - Professional Design -->
    <div class="floating-elements">
        <!-- Strategic Placement: Left Panel Area -->
        <i class="fas fa-shield-alt floating-icon shield size-xl" style="top: 18%; left: 8%; --float-duration: 32s;"></i>
        <i class="fas fa-star floating-icon star size-lg" style="top: 68%; left: 15%; --float-duration: 28s;"></i>
        <i class="fas fa-certificate floating-icon badge size-md" style="top: 42%; left: 12%; --float-duration: 26s;"></i>
        
        <!-- Center Accent Elements -->
        <div class="floating-icon circle size-xl" style="top: 25%; left: 48%; --float-duration: 22s;"></div>
        <i class="fas fa-user-shield floating-icon cap size-lg" style="top: 55%; left: 45%; --float-duration: 30s;"></i>
        <div class="floating-icon circle size-lg" style="top: 78%; left: 42%; --float-duration: 24s;"></div>
        
        <!-- Strategic Placement: Right Panel Area -->
        <i class="fas fa-award floating-icon badge size-lg" style="top: 15%; left: 82%; --float-duration: 29s;"></i>
        <i class="fas fa-star floating-icon star size-md" style="top: 48%; left: 88%; --float-duration: 27s;"></i>
        <i class="fas fa-shield-alt floating-icon shield size-md" style="top: 72%; left: 85%; --float-duration: 25s;"></i>
        
        <!-- Accent Highlights -->
        <i class="fas fa-star floating-icon star size-sm" style="top: 8%; left: 35%; --float-duration: 26s;"></i>
        <i class="fas fa-id-badge floating-icon badge size-sm" style="top: 88%; left: 28%; --float-duration: 28s;"></i>
        <div class="floating-icon circle size-md" style="top: 35%; left: 92%; --float-duration: 23s;"></div>
    </div>
    
    <!-- 
    ================================================================
    RESPONSIVE LOGIN LAYOUT - ORIENTATION-FIRST DESIGN
    ================================================================
    
    DESKTOP (Landscape):
    - Two-column layout: 55% branding (left) | 45% login (right)
    - Full content visibility
    
    TABLET (Portrait):
    - Vertical stacking: Branding (top) | Login (bottom)
    - Reduced content, centered login
    
    TABLET (Landscape):
    - Horizontal layout: 40% branding | 60% login
    - Full content visibility
    
    MOBILE (≤767px Portrait):
    - Single-column, task-focused
    - Minimal branding (logo + name)
    - Hidden: description, social links, buttons
    - Full-width login form priority
    
    MOBILE (≤767px Landscape, Short Height):
    - Compact horizontal: 35% branding | 65% login
    - Minimal content
    
    The layout adapts to ACTUAL device orientation and capabilities,
    not just browser window resizing.
    ================================================================
    -->
    <div class="login-split-container">
        <!-- Left Branded Panel -->
        <div class="login-branded-panel">
            <div class="branded-content">
                <img src="assets/images/goldenz-logo.jpg" alt="Golden Z-5 Security and Investigation Agency, Inc. Logo" class="branded-logo reveal-item" onerror="this.style.display='none'">
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

        <!-- Right Form Panel - Centered Card -->
        <div class="login-form-panel">
            <div class="auth-form-container reveal-form">
                <div class="auth-form-card">
                    <div class="auth-header">
                        <h2 class="auth-title">
                            Sign In
                        </h2>
                        <p class="auth-subtitle">Enter your authorized credentials to access the system</p>
                    </div>

                <?php if ($error && $error !== 'inactive' && $error !== 'suspended'): ?>
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Access Denied</strong>
                            <p>Invalid credentials. Verify your username and password and try again.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- AJAX login error (hidden by default; used when page does not reload) -->
                <div class="alert alert-danger d-none" id="loginErrorAlert" role="alert" aria-live="polite">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Access Denied</strong>
                        <p id="loginErrorMessage">Invalid credentials. Verify your username and password and try again.</p>
                    </div>
                </div>
                
                <?php if ($show_password_change_modal): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        Password change required. You must set a new password to continue.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (!$show_password_change_modal): ?>
                <form method="POST" action="" id="loginForm" class="auth-form" novalidate>
                    <input type="hidden" name="login" value="1">
                    <?= csrf_field() ?>
                    
                    <!-- Validation Alert (Hidden by default) -->
                    <div class="system-alert system-alert-warning d-none" id="validationAlert" role="alert">
                        <div class="system-alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="system-alert-content">
                            <strong id="alertTitle">Required Information</strong>
                            <p id="alertMessage">All fields must be completed.</p>
                        </div>
                        <button type="button" class="system-alert-close" id="closeAlert" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <label for="username" class="form-label">
                            Username
                            <span class="required-indicator" aria-label="Required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Username" 
                                   required 
                                   autocomplete="username"
                                   autofocus
                                   minlength="3"
                                   maxlength="100"
                                   pattern="^[a-zA-Z0-9._@+-]+$"
                                   aria-required="true"
                                   aria-describedby="username-error"
                                   data-validation-message="Enter a valid username"
                                   value="<?php 
                                       if (isset($_POST['username'])) {
                                           echo htmlspecialchars(trim($_POST['username']));
                                       } elseif (isset($_COOKIE['remembered_username'])) {
                                           echo htmlspecialchars(trim($_COOKIE['remembered_username']));
                                       }
                                   ?>">
                            <div class="invalid-feedback" id="username-error" role="alert"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password
                            <span class="required-indicator" aria-label="Required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" 
                                   class="form-control password-input" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Password" 
                                   required 
                                   autocomplete="current-password"
                                   minlength="8"
                                   maxlength="255"
                                   aria-required="true"
                                   <?php if (isset($_POST['password'])): ?>
                                   value="<?php echo htmlspecialchars($_POST['password']); ?>"
                                   <?php endif; ?>
                                   data-remembered-password="<?php 
                                       if (isset($_COOKIE['remembered_password'])) {
                                           $decrypted = decrypt_remember_password($_COOKIE['remembered_password']);
                                           if ($decrypted !== false) {
                                               echo htmlspecialchars($decrypted, ENT_QUOTES);
                                           }
                                       }
                                   ?>"
                                   aria-describedby="password-error"
                                   data-validation-message="Minimum 8 characters required">
                            <button class="password-toggle" type="button" id="togglePassword" aria-label="Show password" tabindex="-1">
                                <i class="fas fa-eye" id="togglePasswordIcon" aria-hidden="true"></i>
                            </button>
                            <div class="invalid-feedback" id="password-error" role="alert"></div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="form-check remember-me">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember_me" value="1">
                            <label class="form-check-label" for="rememberMe">
                                Remember me
                            </label>
                        </div>
                        <a href="forgot-password.php" class="forgot-password-link" id="resetPasswordLink">
                            <span class="link-text">Forgot password?</span>
                            <span class="link-spinner d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </a>
                    </div>

                    <div class="form-submit">
                        <button type="submit" name="login" class="btn btn-primary btn-block" id="submitBtn">
                            <span class="btn-text">Sign In</span>
                            <span class="btn-spinner d-none" id="submitSpinner">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>

                    <div class="form-footer">
                        <div class="help-text">
                            <i class="fas fa-info-circle"></i>
                            <span>For assistance, contact your system administrator.</span>
                        </div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Notification Icon - Upper Right -->
    <a href="alerts-display.php" class="notification-icon" title="View License Alerts">
        <i class="fas fa-bell"></i>
    </a>

    <!-- AI Help floating button & panel -->
    <button type="button"
            class="ai-help-toggle-btn"
            id="aiHelpToggleBtn"
            aria-label="Open AI Help chat"
            aria-haspopup="dialog"
            aria-expanded="false">
        <i class="fas fa-comments"></i>
    </button>

    <section class="ai-help-panel"
             id="aiHelpPanel"
             role="dialog"
             aria-modal="true"
             aria-labelledby="aiHelpTitle"
             aria-describedby="aiHelpDescription">
        <header class="ai-help-header">
            <div class="ai-help-title" id="aiHelpTitle">
                <i class="fas fa-robot" aria-hidden="true"></i>
                <span>AI Help</span>
            </div>
            <div class="ai-help-header-actions">
                <button type="button"
                        class="ai-help-icon-btn"
                        id="aiHelpClearBtn"
                        aria-label="Clear AI Help conversation">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <button type="button"
                        class="ai-help-icon-btn"
                        id="aiHelpCloseBtn"
                        aria-label="Close AI Help panel">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </header>
        <div class="ai-help-body">
            <div class="ai-help-messages" id="aiHelpMessages" aria-live="polite">
                <div class="ai-help-message ai-assistant">
                    <div class="ai-help-bubble ai-assistant">
                        <strong id="aiHelpDescription">Welcome.</strong>
                        <br>You can ask how to log in, reset your password, or who to contact for help.
                    </div>
                </div>
            </div>
            <div class="ai-help-input-row">
                <form class="ai-help-form" id="aiHelpForm" autocomplete="off">
                    <div class="ai-help-input-wrapper">
                        <textarea
                            class="ai-help-input"
                            id="aiHelpInput"
                            name="ai_help_input"
                            rows="2"
                            placeholder="Ask a quick question…"
                            aria-label="Ask AI Help a question"></textarea>
                    </div>
                    <button type="submit"
                            class="ai-help-send-btn"
                            id="aiHelpSendBtn"
                            aria-label="Send message to AI Help">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
                <div class="ai-help-footer-hint">
                    <i class="fas fa-shield-alt"></i>
                    <span>AI Help won’t ask for passwords or codes.</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- System Information Modal -->
    <div class="system-info-modal" id="systemInfoModal" role="dialog" aria-modal="true" aria-labelledby="systemInfoTitle" aria-describedby="systemInfoDescription">
        <div class="system-info-overlay" id="systemInfoOverlay" aria-hidden="true"></div>
        <div class="system-info-content">
            <button type="button" class="system-info-close" id="closeModalBtn" aria-label="Close system information modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            
            <div class="system-info-header">
                <img src="assets/images/goldenz-logo.jpg" alt="Golden Z-5 Logo" class="modal-logo" onerror="this.style.display='none'" width="80" height="80">
                <h2 id="systemInfoTitle">Golden Z-5 HR Management System</h2>
                <p class="modal-subtitle" id="systemInfoDescription">Comprehensive Workforce Management Solution</p>
            </div>
            
            <div class="system-info-body">
                <section class="info-section">
                    <h3><i class="fas fa-desktop"></i> System Overview</h3>
                    <p>
                        The Golden Z-5 HR Management System is a comprehensive digital platform designed to streamline 
                        workforce administration, enhance operational efficiency, and maintain compliance with regulatory 
                        requirements.
                    </p>
                </section>

                <section class="info-section">
                    <h3><i class="fas fa-users"></i> Departments & Users</h3>
                    <div class="features-grid">
                        <div class="feature-item">
                            <i class="fas fa-user-shield"></i>
                            <h4>Super Admin</h4>
                            <p>System-wide control and configuration</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-user-tie"></i>
                            <h4>HR Admin</h4>
                            <p>Employee management and HR operations</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-calculator"></i>
                            <h4>Accounting</h4>
                            <p>Financial and payroll management</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-cogs"></i>
                            <h4>Operations</h4>
                            <p>Daily operations and deployment</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-truck"></i>
                            <h4>Logistics</h4>
                            <p>Resource and equipment management</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-user"></i>
                            <h4>Employees</h4>
                            <p>Staff access and self-service</p>
                        </div>
                    </div>
                </section>

                <section class="info-section">
                    <h3><i class="fas fa-star"></i> Key Features</h3>
                    <ul class="features-list">
                        <li><i class="fas fa-check-circle"></i> <strong>Employee Management:</strong> Complete employee records, profiles, and documentation</li>
                        <li><i class="fas fa-check-circle"></i> <strong>Posts & Assignments:</strong> Security post management and guard deployment tracking</li>
                        <li><i class="fas fa-check-circle"></i> <strong>Team Management:</strong> Department and team organization</li>
                        <li><i class="fas fa-check-circle"></i> <strong>User Management:</strong> Role-based access control and permissions</li>
                        <li><i class="fas fa-check-circle"></i> <strong>Alerts System:</strong> License expiry and compliance notifications</li>
                        <li><i class="fas fa-check-circle"></i> <strong>Audit Trail:</strong> Complete activity logging and tracking</li>
                        <li><i class="fas fa-check-circle"></i> <strong>System Logs:</strong> Security and system monitoring</li>
                        <li><i class="fas fa-check-circle"></i> <strong>Dashboard:</strong> Real-time insights and analytics</li>
                    </ul>
                </section>

                <section class="info-section">
                    <h3><i class="fas fa-shield-alt"></i> Security & Compliance</h3>
                    <p>
                        Built with enterprise-grade security features including role-based access control, 
                        two-factor authentication, audit trails, password policies, and session management 
                        to ensure data protection and regulatory compliance.
                    </p>
                </section>

                <section class="info-section">
                    <h3><i class="fas fa-building"></i> About Golden Z-5</h3>
                    <p>
                        <strong>Golden Z-5 Security and Investigation Agency, Inc.</strong> is duly licensed by the 
                        PNP-CSG-SAGSD (Philippine National Police - Civil Security Group - Security Agencies and Guards 
                        Supervision Division) and registered with the Securities and Exchange Commission to provide 
                        professional Security Services.
                    </p>
                </section>

                <section class="info-section contact-section">
                    <h3><i class="fas fa-envelope"></i> Contact Information</h3>
                    <div class="contact-info">
                        <p><strong>Email:</strong> <a href="mailto:goldenzfive@yahoo.com.ph">goldenzfive@yahoo.com.ph</a></p>
                        <p><strong>Facebook:</strong> <a href="https://www.facebook.com/goldenZ5SA" target="_blank" rel="noopener noreferrer">Golden Z-5 Security Agency</a></p>
                    </div>
                </section>
            </div>
        </div>
    </div>
    </div>

    <!-- Password Change Modal (shown on first login) -->
    <?php if ($show_password_change_modal): ?>
    <div class="modal fade show" id="passwordChangeModal" tabindex="-1" aria-labelledby="passwordChangeModalLabel" aria-modal="true" role="dialog" style="display: block !important; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-link text-decoration-none p-0 me-2 fs-lg" onclick="window.location.href='?logout=1'" aria-label="Back" style="border: none; background: transparent; color: #6b7280;">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h5 class="modal-title mb-0" id="passwordChangeModalLabel">
                        Set a new password
                    </h5>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">
                        Create a strong password to keep your account safe and secure.
                    </p>
                    
                    <?php if ($password_change_error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($password_change_error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="passwordChangeForm">
                        <input type="hidden" name="change_password" value="1">
                        <?= csrf_field() ?>
                        
                        <div class="form-group mb-3">
                            <label for="new_password" class="form-label">
                                New Password
                            </label>
                            <div class="input-group password-input-group" style="position: relative;">
                                <input type="password" 
                                       class="form-control" 
                                       id="new_password" 
                                       name="new_password" 
                                       placeholder="Create strong password" 
                                       required 
                                       autocomplete="new-password"
                                       minlength="8">
                                <button class="password-toggle" type="button" id="toggleNewPassword">
                                    <i class="fas fa-eye" id="toggleNewPasswordIcon"></i>
                                </button>
                            </div>
                            <small class="text-muted fs-13">Must be at least 8 characters long</small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="confirm_password" class="form-label">
                                Confirm New Password
                            </label>
                            <div class="input-group password-input-group" style="position: relative;">
                                <input type="password" 
                                       class="form-control" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       placeholder="Re-enter your new password" 
                                       required 
                                       autocomplete="new-password"
                                       minlength="8">
                                <button class="password-toggle" type="button" id="toggleConfirmPassword">
                                    <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="changePasswordBtn">
                                Create New Password
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <p class="text-center text-muted small mb-0" style="width: 100%;">
                        Remembered it? <a href="?logout=1">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/login.js"></script>
    <!-- Login/AI-help logic is in assets/js/login.js -->

    <!-- Futuristic Status Error Modal -->
    <div class="modal fade" id="statusErrorModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content futuristic-status-modal">
                <div class="futuristic-status-header">
                    <div class="futuristic-status-icon-wrapper">
                        <i class="fas fa-ban futuristic-status-icon" id="statusErrorIcon"></i>
                        <div class="futuristic-status-pulse"></div>
                    </div>
                    <h5 class="futuristic-status-title" id="statusErrorTitle">Account Status</h5>
                </div>
                <div class="futuristic-status-body">
                    <p class="futuristic-status-message" id="statusErrorMessage"></p>
                    <div class="futuristic-status-info-box" id="statusErrorInfoBox">
                        <i class="fas fa-info-circle"></i>
                        <span id="statusErrorInfoText">Please contact your administrator for assistance.</span>
                    </div>
                </div>
                <div class="futuristic-status-footer">
                    <button type="button" class="btn futuristic-status-btn-ok" id="statusErrorOkBtn" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>Understood
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script nonce="<?= htmlspecialchars($cspNonce, ENT_QUOTES, 'UTF-8') ?>">
        // Show status error modal if status error exists
        <?php if ($login_status_error): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('statusErrorModal');
            const messageEl = document.getElementById('statusErrorMessage');
            const titleEl = document.getElementById('statusErrorTitle');
            const iconEl = document.getElementById('statusErrorIcon');
            const infoBoxEl = document.getElementById('statusErrorInfoBox');
            const infoTextEl = document.getElementById('statusErrorInfoText');
            
            if (modal && messageEl) {
                const statusType = '<?php echo htmlspecialchars($login_status_error); ?>';
                const statusMessage = '<?php echo htmlspecialchars($login_status_message); ?>';
                
                messageEl.textContent = statusMessage;
                
                if (statusType === 'inactive') {
                    titleEl.textContent = 'Account Inactive';
                    iconEl.className = 'fas fa-pause-circle futuristic-status-icon';
                    iconEl.style.color = '#94a3b8';
                    infoBoxEl.style.borderColor = 'rgba(148, 163, 184, 0.3)';
                    infoBoxEl.style.background = 'rgba(148, 163, 184, 0.1)';
                    infoTextEl.textContent = 'Your account has been deactivated. Contact your administrator to reactivate it.';
                } else if (statusType === 'suspended') {
                    titleEl.textContent = 'Account Suspended';
                    iconEl.className = 'fas fa-ban futuristic-status-icon';
                    iconEl.style.color = '#ef4444';
                    infoBoxEl.style.borderColor = 'rgba(239, 68, 68, 0.3)';
                    infoBoxEl.style.background = 'rgba(239, 68, 68, 0.1)';
                    infoTextEl.textContent = 'Your account has been suspended. This action may be due to policy violations or security concerns.';
                }
                
                // Show modal using Bootstrap
                const modalInstance = new bootstrap.Modal(modal, {
                    backdrop: 'static',
                    keyboard: false,
                    focus: true
                });
                
                modalInstance.show();
                
                // Ensure modal is visible and properly positioned
                setTimeout(() => {
                    modal.style.display = 'block';
                    modal.style.zIndex = '1060';
                    modal.classList.add('show');
                    modal.setAttribute('aria-hidden', 'false');
                    modal.setAttribute('aria-modal', 'true');
                    
                    const modalDialog = modal.querySelector('.modal-dialog');
                    if (modalDialog) {
                        modalDialog.style.zIndex = '1061';
                        modalDialog.style.pointerEvents = 'auto';
                        modalDialog.style.margin = '1.75rem auto';
                    }
                    
                    // Ensure backdrop exists
                    let backdrop = document.querySelector('.modal-backdrop');
                    if (!backdrop) {
                        backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    }
                    backdrop.style.zIndex = '1059';
                    backdrop.classList.add('show');
                    
                    document.body.classList.add('modal-open');
                    document.body.style.overflow = 'hidden';
                }, 50);
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>

