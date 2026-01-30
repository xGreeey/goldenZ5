<?php

declare(strict_types=1);

/**
 * CSRF and security helpers — thin wrapper around app/middleware and file logging.
 * Session must be started before using. Load app/middleware/CsrfMiddleware.php before this file
 * when using csrf_token / csrf_field / csrf_validate / csrf_rotate.
 */

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        $appRoot = dirname(__DIR__);
        require_once $appRoot . '/app/middleware/CsrfMiddleware.php';
        return CsrfMiddleware::token();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Return HTML hidden input for CSRF token (use in every form).
     */
    function csrf_field(): string
    {
        $token = csrf_token();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('csrf_validate')) {
    /**
     * Validate request token (POST/PUT/PATCH/DELETE). Returns true if valid.
     * On invalid, use CsrfMiddleware::reject() or respond with 419/error in caller.
     */
    function csrf_validate(): bool
    {
        $appRoot = dirname(__DIR__);
        require_once $appRoot . '/app/middleware/CsrfMiddleware.php';
        return CsrfMiddleware::verify();
    }
}

if (!function_exists('csrf_rotate')) {
    function csrf_rotate(): void
    {
        $appRoot = dirname(__DIR__);
        require_once $appRoot . '/app/middleware/CsrfMiddleware.php';
        CsrfMiddleware::rotate();
    }
}

if (!function_exists('log_security_event')) {
    /**
     * Append one JSON line to storage/logs/security.log.
     *
     * @param string $action Event name (e.g. "Login Success", "Document Download").
     * @param array|string $meta Optional details; if string, stored as ['details' => $meta].
     */
    function log_security_event(string $action, $meta = []): void
    {
        if (is_string($meta)) {
            $meta = ['details' => $meta];
        }
        $appRoot = dirname(__DIR__);
        $logDir = $appRoot . '/storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . '/security.log';
        $line = json_encode([
            'time'   => date('Y-m-d\TH:i:s\Z'),
            'action' => $action,
            'meta'   => $meta,
            'ip'     => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_id' => $_SESSION['user_id'] ?? null,
        ]) . "\n";
        @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
    }
}
