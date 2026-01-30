<?php

declare(strict_types=1);

/**
 * CSRF middleware: token in $_SESSION['_csrf'], verify on POST/PUT/PATCH/DELETE.
 * Accept token from POST _csrf or header X-CSRF-Token. Compatible with includes/security.php helpers.
 */
class CsrfMiddleware
{
    private const SESSION_KEY = '_csrf';

    /**
     * Get or generate CSRF token (for csrf_token() / csrf_field() in security.php).
     */
    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * Verify request token. Returns true if valid, false otherwise.
     * Check method: POST, PUT, PATCH, DELETE require validation.
     */
    public static function verify(): bool
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return true;
        }
        $sessionToken = $_SESSION[self::SESSION_KEY] ?? '';
        if ($sessionToken === '') {
            return false;
        }
        $requestToken = (string) ($_POST['_csrf'] ?? $_POST['csrf_token'] ?? '');
        if ($requestToken === '' && isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            $requestToken = (string) $_SERVER['HTTP_X_CSRF_TOKEN'];
        }
        if ($requestToken === '') {
            return false;
        }
        return hash_equals($sessionToken, $requestToken);
    }

    /**
     * Reject with 419 (JSON) or HTML message. Call after verify() === false.
     */
    public static function reject(): void
    {
        $wantsJson = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        if ($wantsJson) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(419);
            echo json_encode(['error' => 'csrf', 'message' => 'Invalid security token. Please refresh and try again.']);
            exit;
        }
        http_response_code(419);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Invalid request</title></head><body><h1>Invalid request</h1><p>Invalid security token. Please refresh the page and try again.</p></body></html>';
        exit;
    }

    /**
     * Rotate token (e.g. after login). Keeps compatibility with security.php csrf_rotate().
     */
    public static function rotate(): void
    {
        $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
    }
}
