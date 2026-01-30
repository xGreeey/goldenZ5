<?php

declare(strict_types=1);

/**
 * CSRF and security helpers. Session must be started before using.
 * PHP 7.4+ compatible. Server-stored token (double-submit not used).
 */

if (!function_exists('csrf_token')) {
    /**
     * Generate and store a CSRF token in session (32 bytes). Returns existing token if present.
     */
    function csrf_token(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Return HTML hidden input for CSRF token (for forms).
     */
    function csrf_field(): string
    {
        $token = csrf_token();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('csrf_validate')) {
    /**
     * Validate request token against session token (header X-CSRF-Token or body csrf_token).
     * Returns true if valid, false otherwise. Use hash_equals to prevent timing attacks.
     */
    function csrf_validate(): bool
    {
        $sessionToken = $_SESSION['_csrf_token'] ?? '';
        if ($sessionToken === '') {
            return false;
        }
        $requestToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if ($requestToken === '') {
            return false;
        }
        return hash_equals($sessionToken, $requestToken);
    }
}

if (!function_exists('csrf_rotate')) {
    /**
     * Regenerate CSRF token after successful state-changing request (recommended).
     */
    function csrf_rotate(): void
    {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
}

// CSP nonce: generate per-request in the entry script (e.g. $cspNonce = base64_encode(random_bytes(16)))
// and use the same value in Content-Security-Policy and in <script nonce="...">.
