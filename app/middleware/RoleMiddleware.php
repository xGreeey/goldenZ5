<?php

declare(strict_types=1);

/**
 * Role middleware: require one of the given roles (case-insensitive).
 * Session key: user_role. Compares with normalized lowercase.
 */
class RoleMiddleware
{
    /**
     * Require the current user to have one of the given roles. Exits with 403 or "Access denied" if not.
     *
     * @param string|string[] $roles Single role or list (e.g. 'humanresource' or ['admin', 'humanresource', 'super_admin'] — must match users.role enum)
     */
    public static function requireRole($roles): void
    {
        $allowed = is_array($roles) ? $roles : [$roles];
        $allowed = array_map('strtolower', $allowed);
        $userRole = strtolower((string) ($_SESSION['user_role'] ?? ''));

        if (in_array($userRole, $allowed, true)) {
            return;
        }
        self::reject();
    }

    private static function reject(): void
    {
        $wantsJson = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        if ($wantsJson) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(403);
            echo json_encode(['error' => 'forbidden', 'message' => 'Access denied']);
            exit;
        }
        http_response_code(403);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Access denied</title></head><body><h1>Access denied</h1><p>You do not have permission to access this resource.</p></body></html>';
        exit;
    }
}
