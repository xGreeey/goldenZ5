<?php
/**
 * Roles & Permissions API — GET role permissions, POST save assignments.
 * Super Admin only. Returns JSON.
 */
declare(strict_types=1);

$appRoot = dirname(__DIR__, 2);

if (is_file($appRoot . '/bootstrap/app.php')) {
    require_once $appRoot . '/bootstrap/app.php';
}

require_once $appRoot . '/app/middleware/SessionMiddleware.php';
require_once $appRoot . '/app/middleware/AuthMiddleware.php';
require_once $appRoot . '/app/middleware/RoleMiddleware.php';
require_once $appRoot . '/app/middleware/CsrfMiddleware.php';

SessionMiddleware::handle();
AuthMiddleware::check();
RoleMiddleware::requireRole(['super_admin']);

require_once $appRoot . '/config/database.php';
require_once $appRoot . '/includes/security.php';
require_once $appRoot . '/includes/permissions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $roles = permissions_available_roles();
        $grouped = permissions_get_all_grouped();
        $rolePermissions = [];
        foreach ($roles as $role) {
            $codes = permissions_get_for_role($role);
            $ids = [];
            foreach ($grouped as $perms) {
                foreach ($perms as $p) {
                    if (in_array($p['code'], $codes, true)) {
                        $ids[] = $p['id'];
                    }
                }
            }
            $rolePermissions[$role] = $ids;
        }
        echo json_encode([
            'success' => true,
            'roles' => $roles,
            'permissionsGrouped' => $grouped,
            'rolePermissions' => $rolePermissions,
        ]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to load permissions']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!CsrfMiddleware::verify()) {
        http_response_code(419);
        echo json_encode(['success' => false, 'error' => 'Invalid security token']);
        exit;
    }
    $role = isset($_POST['role']) ? trim((string) $_POST['role']) : '';
    $ids = isset($_POST['permission_ids']) && is_array($_POST['permission_ids'])
        ? array_map('intval', $_POST['permission_ids'])
        : [];
    $ids = array_values(array_unique(array_filter($ids)));
    $validRoles = permissions_available_roles();
    if (!in_array($role, $validRoles, true)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid role']);
        exit;
    }
    try {
        permissions_update_for_role($role, $ids);
        echo json_encode(['success' => true]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to save']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed']);
