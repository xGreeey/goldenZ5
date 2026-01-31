<?php
/**
 * Admin — profile form POST handler (personal, account, security, 2FA).
 * Receives POST from profile forms and runs shared profile-handle-post.php, then redirects.
 */
declare(strict_types=1);

if (ob_get_level() === 0) {
    ob_start();
}

$adminRoot = __DIR__;
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
RoleMiddleware::requireRole(['admin']);

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: /admin?page=profile');
    exit;
}
if (!CsrfMiddleware::verify()) {
    CsrfMiddleware::reject();
}

require_once $appRoot . '/config/database.php';
require_once $appRoot . '/includes/security.php';

$profile_user_id = (int) (AuthMiddleware::user()['id'] ?? 0);
$profile_base_url = '/admin';

require $adminRoot . '/../shared/profile-handle-post.php';
