<?php
/**
 * Super Admin Portal — Dashboard entry.
 * Route: /super-admin/ or /super-admin/dashboard (htaccess: super-admin → super_admin if alias).
 * Same layout system as HR dashboard: fixed header, sidebar, card grid.
 * Permission-based tab/action visibility is driven by JS (mock permissions for now).
 *
 * BACKEND: Replace $mock_permissions with session/API permissions when ready.
 */
declare(strict_types=1);

$saRoot = __DIR__;
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
// Super Admin portal: only super_admin role (or add RoleMiddleware::requireRole(['super_admin']) when ready)
if (file_exists($appRoot . '/app/middleware/RoleMiddleware.php')) {
    RoleMiddleware::requireRole(['super_admin']);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && CsrfMiddleware::verify() === false) {
    CsrfMiddleware::reject();
}

require_once $appRoot . '/config/database.php';
require_once $appRoot . '/includes/security.php';

$page = isset($_GET['page']) ? trim($_GET['page']) : 'dashboard';
$page = preg_replace('/[^a-z0-9_-]/i', '', $page) ?: 'dashboard';

$allowed_pages = ['dashboard', 'users'];
if (!in_array($page, $allowed_pages, true)) {
    $page = 'dashboard';
}

$current_user = AuthMiddleware::user();
$current_user['role'] = $_SESSION['user_role'] ?? $current_user['role'] ?? 'super_admin';
$current_user['department'] = $_SESSION['department'] ?? $current_user['department'] ?? null;

// Mock permissions for frontend RBAC. Replace with: $_SESSION['permissions'] or API call.
$mock_permissions = [
    'permissions.manage.system',
    'users.manage',
    'roles.manage',
    'audit.view',
    'system.settings.manage',
    'dashboard.view.super_admin',
    'modules.enable_disable',
    'interview.conduct',
    'interview.schedule',
    'reports.view.all',
];

$base_url = '/super_admin';
$assets_url = $base_url . '/assets';

$page_file = $saRoot . '/pages/' . $page . '.php';
if (!is_file($page_file)) {
    $page_file = $saRoot . '/pages/dashboard.php';
    $page = 'dashboard';
}

$page_title = match($page) {
    'users' => 'User Management',
    default => 'Super Admin Dashboard',
};
$is_super_admin_dashboard = ($page === 'dashboard');

// Pass to layout and to dashboard page for JS permission engine
$permissions_json = json_encode($mock_permissions);
$current_user_json = json_encode([
    'id' => $current_user['id'] ?? null,
    'username' => $current_user['username'] ?? '',
    'name' => $current_user['name'] ?? '',
    'role' => $current_user['role'] ?? 'super_admin',
]);

ob_start();
include $page_file;
$page_content = ob_get_clean();

include $saRoot . '/includes/layout.php';
