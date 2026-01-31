<?php
/**
 * Super Admin — profile form POST handler (personal, account, security, 2FA).
 * Receives POST from profile forms and runs shared profile-handle-post.php, then redirects.
 * This ensures 2FA disable and other profile actions always hit a script that handles them.
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
if (file_exists($appRoot . '/app/middleware/RoleMiddleware.php')) {
    RoleMiddleware::requireRole(['super_admin']);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: /super_admin?page=profile');
    exit;
}
if (CsrfMiddleware::verify() === false) {
    CsrfMiddleware::reject();
}

require_once $appRoot . '/config/database.php';
require_once $appRoot . '/includes/security.php';

$profile_user_id = (int) (AuthMiddleware::user()['id'] ?? 0);
$profile_base_url = '/super_admin';

require $saRoot . '/../shared/profile-handle-post.php';
