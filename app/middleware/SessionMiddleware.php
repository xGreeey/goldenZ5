<?php

declare(strict_types=1);

/**
 * Session middleware: start session from config, enforce idle/absolute lifetime, provide regenerate().
 * Run before any output. Session must be started before Auth/Role/CSRF.
 */
class SessionMiddleware
{
    /** Idle timeout in seconds (default 30 min) */
    public static int $idleTimeout = 1800;

    /** Absolute session lifetime in seconds (default 8 hours) */
    public static int $absoluteLifetime = 28800;

    /**
     * Start session using config/session.php and enforce idle/absolute timeout.
     * Sets $_SESSION['last_activity'] and $_SESSION['created_at'] if missing.
     */
    public static function handle(): void
    {
        $appRoot = dirname(__DIR__, 2);
        $sessionConfig = $appRoot . '/config/session.php';
        if (is_file($sessionConfig)) {
            require_once $sessionConfig;
        } else {
            self::startFallback($appRoot);
        }

        $now = time();
        if (!isset($_SESSION['last_activity'])) {
            $_SESSION['last_activity'] = $now;
        }
        if (!isset($_SESSION['created_at'])) {
            $_SESSION['created_at'] = $now;
        }

        // Idle timeout
        if (($now - ($_SESSION['last_activity'] ?? 0)) > self::$idleTimeout) {
            self::destroy();
            return;
        }
        // Absolute lifetime
        if (($now - ($_SESSION['created_at'] ?? 0)) > self::$absoluteLifetime) {
            self::destroy();
            return;
        }

        $_SESSION['last_activity'] = $now;
    }

    /**
     * Fallback session start when config/session.php is missing (e.g. tests).
     */
    private static function startFallback(string $appRoot): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }
        $path = $appRoot . '/storage/sessions';
        if (!is_dir($path)) {
            @mkdir($path, 0755, true);
        }
        if (is_dir($path)) {
            session_save_path($path);
        }
        ini_set('session.use_strict_mode', '1');
        ini_set('session.cookie_httponly', '1');
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443');
        if (PHP_VERSION_ID >= 70300) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        } else {
            session_set_cookie_params(0, '/', '', $isHttps, true);
        }
        session_start();
    }

    /**
     * Regenerate session ID (call after login to prevent fixation).
     */
    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    private static function destroy(): void
    {
        $_SESSION = [];
        session_unset();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            if (PHP_VERSION_ID >= 70300) {
                setcookie(session_name(), '', [
                    'expires'  => time() - 42000,
                    'path'     => $params['path'],
                    'domain'   => $params['domain'],
                    'secure'   => $params['secure'],
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
    }
}
