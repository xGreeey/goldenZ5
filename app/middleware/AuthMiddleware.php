<?php

declare(strict_types=1);

/**
 * Auth middleware: verify user is logged in via $_SESSION['user_id'].
 * JSON requests get 401; HTML requests redirect to /index.php.
 */
class AuthMiddleware
{
    /**
     * Check that the user is authenticated. Exits with 401 or redirect if not.
     */
    public static function check(): void
    {
        if (self::authenticated()) {
            return;
        }
        self::reject();
    }

    /**
     * Whether the current request has an authenticated user.
     */
    public static function authenticated(): bool
    {
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] !== '' && $_SESSION['user_id'] !== null;
    }

    /**
     * Return minimal user info from session (for use after check()).
     *
     * @return array{id: mixed, username: string, name: string, role: string, department: mixed}
     */
    public static function user(): array
    {
        return [
            'id'         => $_SESSION['user_id'] ?? null,
            'username'  => (string) ($_SESSION['username'] ?? ''),
            'name'      => (string) ($_SESSION['name'] ?? ''),
            'role'      => (string) ($_SESSION['user_role'] ?? ''),
            'department' => $_SESSION['department'] ?? null,
        ];
    }

    private static function reject(): void
    {
        $wantsJson = self::wantsJson();
        if ($wantsJson) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(401);
            echo json_encode(['error' => 'unauthorized', 'message' => 'Authentication required']);
            exit;
        }
        header('Location: /index.php');
        exit;
    }

    private static function wantsJson(): bool
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
}
