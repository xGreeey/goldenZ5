<?php
/**
 * Admin Portal — Administration, Evaluation & Assessments
 * Routes: ?page=dashboard | employees | reporting | ...
<<<<<<< HEAD:public/human-resource/index.php
 * Middleware: Session → Auth → Role. CSRF verified on POST.
=======
 * Requires session (redirect to / if not logged in).
 * For Hiring use /hr/.
>>>>>>> 1fae824f460fdd9bda35bcd72d7eb765515b9038:public/admin/index.php
 *
 * CONVENTION: JS in assets/js/, CSS in assets/css/, markup in includes/ and pages/.
 */
declare(strict_types=1);

<<<<<<< HEAD:public/human-resource/index.php
$hrAdminRoot = __DIR__;
$appRoot = dirname(__DIR__, 2);
=======
$adminRoot = __DIR__;
$appRoot = dirname(__DIR__, 2); // src/
>>>>>>> 1fae824f460fdd9bda35bcd72d7eb765515b9038:public/admin/index.php

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

<<<<<<< HEAD:public/human-resource/index.php
$base_url = '/human-resource';
$assets_url = $base_url . '/assets';

$page_file = $hrAdminRoot . '/pages/' . $page . '.php';
=======
// Base URL for admin (no trailing slash)
$base_url = '/admin';
$assets_url = $base_url . '/assets';

// Page content file
$page_file = $adminRoot . '/pages/' . $page . '.php';
>>>>>>> 1fae824f460fdd9bda35bcd72d7eb765515b9038:public/admin/index.php
if (!is_file($page_file)) {
    $page_file = $adminRoot . '/pages/dashboard.php';
    $page = 'dashboard';
}

ob_start();
include $page_file;
$page_content = ob_get_clean();

include $adminRoot . '/includes/layout.php';
