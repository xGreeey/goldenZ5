<?php
/**
 * Human Resource — secure document download. Resolves path by employee_documents.id (no raw paths).
 * Middleware: Session → Auth → Role (humanresource). Logs each download via log_security_event.
 * Call: /human-resource/document-download.php?id=<employee_documents.id>
 */
declare(strict_types=1);

$appRoot = dirname(__DIR__, 2);

if (is_file($appRoot . '/bootstrap/app.php')) {
    require_once $appRoot . '/bootstrap/app.php';
}

require_once $appRoot . '/app/middleware/SessionMiddleware.php';
require_once $appRoot . '/app/middleware/AuthMiddleware.php';
require_once $appRoot . '/app/middleware/RoleMiddleware.php';
require_once $appRoot . '/config/database.php';
require_once $appRoot . '/includes/security.php';
require_once $appRoot . '/app/services/storage.php';

SessionMiddleware::handle();
AuthMiddleware::check();
RoleMiddleware::requireRole(['humanresource']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    header('Content-Type: text/plain');
    echo 'Bad Request';
    exit;
}

$storageRoot = $appRoot . '/storage';
$resolved = storage_resolve_document_by_id($id, $storageRoot);
if ($resolved === null) {
    http_response_code(404);
    header('Content-Type: text/plain');
    echo 'Not Found';
    exit;
}

if (function_exists('log_security_event')) {
    log_security_event('Document Download', [
        'document_id' => $id,
        'file_name'   => $resolved['file_name'],
        'user_id'     => $_SESSION['user_id'] ?? null,
    ]);
}
if (function_exists('log_audit_event')) {
    log_audit_event('DOWNLOAD', 'employee_documents', $id, null, [
        'file_name'   => $resolved['file_name'],
        'employee_id' => null,
    ], $_SESSION['user_id'] ?? null);
}

$path = $resolved['path'];
$name = $resolved['file_name'];
$mime = $resolved['mime_type'];

header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . preg_replace('/[^\w.-]/', '_', $name) . '"');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Content-Length: ' . (string) filesize($path));

$handle = fopen($path, 'rb');
if ($handle !== false) {
    while (!feof($handle)) {
        echo fread($handle, 65536);
    }
    fclose($handle);
}
exit;
