<?php
/**
 * Human Resource Portal — Administration, Evaluation & Assessments (alternate URL)
 * Routes: ?page=dashboard | employees | reporting | ...
 * Middleware: Session → Auth → Role. CSRF verified on POST.
 * Same roles as /admin/. For Hiring (hr role only) use /hr/.
 *
 * CONVENTION: JS in assets/js/, CSS in assets/css/, markup in includes/ and pages/.
 */
declare(strict_types=1);

$hrAdminRoot = __DIR__;
$appRoot = dirname(__DIR__, 2);

// Bootstrap
if (is_file($appRoot . '/bootstrap/app.php')) {
    require_once $appRoot . '/bootstrap/app.php';
}

// Middleware: session, auth, role
require_once $appRoot . '/app/middleware/SessionMiddleware.php';
require_once $appRoot . '/app/middleware/AuthMiddleware.php';
require_once $appRoot . '/app/middleware/RoleMiddleware.php';
require_once $appRoot . '/app/middleware/CsrfMiddleware.php';

SessionMiddleware::handle();
AuthMiddleware::check();
RoleMiddleware::requireRole(['super_admin', 'hr_admin', 'hr', 'admin', 'accounting', 'operation', 'logistics', 'employee']);

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

$base_url = '/human-resource';
$assets_url = $base_url . '/assets';

$page_file = $hrAdminRoot . '/pages/' . $page . '.php';
if (!is_file($page_file)) {
    $page_file = $hrAdminRoot . '/pages/dashboard.php';
    $page = 'dashboard';
}

ob_start();
include $page_file;
$page_content = ob_get_clean();

include $hrAdminRoot . '/includes/layout.php';
