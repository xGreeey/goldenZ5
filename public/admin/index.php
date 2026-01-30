<?php
/**
 * Admin Portal — Administration, Evaluation & Assessments
 * Routes: ?page=dashboard | employees | reporting | ...
 * Requires session (redirect to / if not logged in).
 * For Hiring use /hr/.
 *
 * CONVENTION: JS in assets/js/, CSS in assets/css/, markup in includes/ and pages/.
 */

declare(strict_types=1);

$adminRoot = __DIR__;
$appRoot = dirname(__DIR__, 2); // src/

// Session (must match main app)
if (session_status() === PHP_SESSION_NONE) {
    $sessionPath = $appRoot . '/storage/sessions';
    if (is_dir($sessionPath) || @mkdir($sessionPath, 0755, true)) {
        session_save_path($sessionPath);
    }
    session_start();
}

// Auth check — roles that can access human-resource portal (RBA from users.role)
$allowed_roles = ['super_admin', 'hr_admin', 'hr', 'admin', 'accounting', 'operation', 'logistics', 'employee'];
if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_role']) ||
    !in_array($_SESSION['user_role'], $allowed_roles, true)) {
    header('Location: /');
    exit;
}

// Bootstrap & DB
if (is_file($appRoot . '/bootstrap/app.php')) {
    require_once $appRoot . '/bootstrap/app.php';
}
require_once $appRoot . '/config/database.php';

$page = isset($_GET['page']) ? trim($_GET['page']) : 'dashboard';
$page = preg_replace('/[^a-z0-9_-]/i', '', $page) ?: 'dashboard';

$allowed_pages = ['dashboard', 'personal', 'employees', 'employee-add', 'employee-view', 'employee-edit', 'documents', 'reporting', 'posts', 'tasks', 'settings'];
if (!in_array($page, $allowed_pages, true)) {
    $page = 'dashboard';
}

$current_user = [
    'id' => $_SESSION['user_id'] ?? null,
    'username' => $_SESSION['username'] ?? '',
    'name' => $_SESSION['name'] ?? '',
    'role' => $_SESSION['user_role'] ?? '',
    'department' => $_SESSION['department'] ?? null,
];

// Base URL for admin (no trailing slash)
$base_url = '/admin';
$assets_url = $base_url . '/assets';

// Page content file
$page_file = $adminRoot . '/pages/' . $page . '.php';
if (!is_file($page_file)) {
    $page_file = $adminRoot . '/pages/dashboard.php';
    $page = 'dashboard';
}

ob_start();
include $page_file;
$page_content = ob_get_clean();

include $adminRoot . '/includes/layout.php';
