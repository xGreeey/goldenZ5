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

if (ob_get_level() === 0) {
    ob_start();
}

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
require_once $appRoot . '/includes/permissions.php';

$page = isset($_GET['page']) ? trim($_GET['page']) : 'dashboard';
$page = preg_replace('/[^a-z0-9_-]/i', '', $page) ?: 'dashboard';

$allowed_pages = ['dashboard', 'users', 'roles', 'profile'];
if (!in_array($page, $allowed_pages, true)) {
    $page = 'dashboard';
}

$current_user = AuthMiddleware::user();
$current_user['role'] = $_SESSION['user_role'] ?? $current_user['role'] ?? 'super_admin';
$current_user['department'] = $_SESSION['department'] ?? $current_user['department'] ?? null;

// Permissions from DB (role_permissions). Fallback to full set for super_admin if tables missing.
$user_permissions = [];
try {
    $user_permissions = permissions_get_for_role($current_user['role']);
    if ($current_user['role'] === 'super_admin' && empty($user_permissions)) {
        $user_permissions = array_column(
            (require $appRoot . '/config/permissions.php')['permissions'],
            'code'
        );
    }
} catch (Throwable $e) {
    $user_permissions = ($current_user['role'] === 'super_admin')
        ? array_column((require $appRoot . '/config/permissions.php')['permissions'], 'code')
        : [];
}
$_SESSION['permissions'] = $user_permissions;

$base_url = '/super_admin';
$assets_url = $base_url . '/assets';

// Profile POST: update personal/account/security/2FA then redirect
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && $page === 'profile') {
    $profile_user_id = (int) (AuthMiddleware::user()['id'] ?? 0);
    $profile_base_url = $base_url;
    require $saRoot . '/../shared/profile-handle-post.php';
}

$page_file = $saRoot . '/pages/' . $page . '.php';
if (!is_file($page_file)) {
    $page_file = $saRoot . '/pages/dashboard.php';
    $page = 'dashboard';
}

// Backend permission checks: require permission for page access
if ($page === 'roles') {
    if (!in_array('roles.manage', $user_permissions, true) && !in_array('permissions.manage.system', $user_permissions, true)) {
        $page = 'dashboard';
        $page_file = $saRoot . '/pages/dashboard.php';
    }
} elseif ($page === 'users' && !in_array('users.manage', $user_permissions, true)) {
    $page = 'dashboard';
    $page_file = $saRoot . '/pages/dashboard.php';
}

$page_title = match($page) {
    'users' => 'User Management',
    'roles' => 'Roles & Permissions',
    default => 'Super Admin Dashboard',
};
$is_super_admin_dashboard = true;

// Pass to layout for JS permission engine (sidebar/tab visibility)
$permissions_json = json_encode($user_permissions);
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
