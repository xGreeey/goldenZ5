<?php
/**
 * Admin Portal — Administration, Evaluation & Assessments
 * Routes: ?page=dashboard | employees | reporting | ...
 * Middleware: Session → Auth → Role. CSRF verified on POST.
 * For Hiring (hr role) use /hr/ or /human-resource/.
 *
 * CONVENTION: JS in assets/js/, CSS in assets/css/, markup in includes/ and pages/.
 */
declare(strict_types=1);

$adminRoot = __DIR__;
$appRoot = dirname(__DIR__, 2);

// Bootstrap
if (is_file($appRoot . '/bootstrap/app.php')) {
    require_once $appRoot . '/bootstrap/app.php';
}

// Middleware: session, auth, role (must match main app session config to avoid redirect loops)
require_once $appRoot . '/app/middleware/SessionMiddleware.php';
require_once $appRoot . '/app/middleware/AuthMiddleware.php';
require_once $appRoot . '/app/middleware/RoleMiddleware.php';
require_once $appRoot . '/app/middleware/CsrfMiddleware.php';

SessionMiddleware::handle();
AuthMiddleware::check();
// Roles that index.php sends to /admin/dashboard: hr_admin, admin, accounting, operation, logistics, employee (+ super_admin if ever)
RoleMiddleware::requireRole(['super_admin', 'hr_admin', 'admin', 'accounting', 'operation', 'logistics', 'employee']);

// CSRF for POST (form submissions, uploads)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!CsrfMiddleware::verify()) {
        CsrfMiddleware::reject();
    }
}

require_once $appRoot . '/config/database.php';
require_once $appRoot . '/includes/security.php';

$page = isset($_GET['page']) ? trim($_GET['page']) : 'dashboard';
$page = preg_replace('/[^a-z0-9_-]/i', '', $page) ?: 'dashboard';

$allowed_pages = ['dashboard', 'personal', 'employees', 'employee-add', 'employee-view', 'employee-edit', 'documents', 'reporting', 'posts', 'tasks', 'settings'];
if (!in_array($page, $allowed_pages, true)) {
    $page = 'dashboard';
}

$current_user = AuthMiddleware::user();
$current_user['role'] = $_SESSION['user_role'] ?? $current_user['role'];
$current_user['department'] = $_SESSION['department'] ?? $current_user['department'];

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
