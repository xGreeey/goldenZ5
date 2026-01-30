<?php
/**
 * Admin portal — document download. Roles from users.role enum: super_admin, admin, accounting, operation, logistics, employee.
 * Call: /admin/document-download.php?id=<employee_documents.id>
 */
declare(strict_types=1);

$appRoot = dirname(__DIR__, 2);
$storageRoot = $appRoot . '/storage';

if (is_file($appRoot . '/bootstrap/app.php')) {
    require_once $appRoot . '/bootstrap/app.php';
}
require_once $appRoot . '/app/middleware/SessionMiddleware.php';
require_once $appRoot . '/app/middleware/AuthMiddleware.php';
require_once $appRoot . '/app/middleware/RoleMiddleware.php';

SessionMiddleware::handle();
AuthMiddleware::check();
// Only admin role can access admin portal (humanresource uses /human-resource/document-download.php)
RoleMiddleware::requireRole(['admin']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    exit;
}

require_once $appRoot . '/config/database.php';

try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("SELECT id, employee_id, file_name, file_path, mime_type FROM employee_documents WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        http_response_code(404);
        exit;
    }
    $full_path = $storageRoot . '/' . $row['file_path'];
    if (!is_file($full_path) || !is_readable($full_path)) {
        http_response_code(404);
        exit;
    }
    if (function_exists('log_audit_event')) {
        log_audit_event('DOWNLOAD', 'employee_documents', $id, null, ['file_name' => $row['file_name'], 'employee_id' => $row['employee_id']], $_SESSION['user_id'] ?? null);
    }
    $mime = $row['mime_type'] ?: 'application/octet-stream';
    $name = $row['file_name'];
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . preg_replace('/[^\w.-]/', '_', $name) . '"');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Content-Length: ' . (string) filesize($full_path));
    readfile($full_path);
    exit;
} catch (Throwable $e) {
    error_log('document-download: ' . $e->getMessage());
    http_response_code(500);
    exit;
}
