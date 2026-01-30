<?php

declare(strict_types=1);

/**
 * Storage helpers for Golden Z HR System (MinIO, backups, document resolution).
 * Extend with upload_to_minio() / rclone when needed.
 */

/**
 * Resolve document file path by employee_documents.id. Prevents directory traversal.
 * Returns absolute path if file exists and is under storage root; null otherwise.
 *
 * @param int $documentId employee_documents.id
 * @param string $storageRoot Absolute path to storage directory (e.g. app root /storage)
 * @return array{path: string, file_name: string, mime_type: string}|null Row with path, file_name, mime_type or null
 */
function storage_resolve_document_by_id(int $documentId, string $storageRoot): ?array
{
    if ($documentId <= 0) {
        return null;
    }
    $storageRoot = rtrim(str_replace('\\', '/', $storageRoot), '/');
    if (!is_dir($storageRoot)) {
        return null;
    }
    $realRoot = realpath($storageRoot);
    if ($realRoot === false) {
        return null;
    }

    if (!function_exists('get_db_connection')) {
        $config = dirname(__DIR__, 2) . '/config/database.php';
        if (is_file($config)) {
            require_once $config;
        }
    }
    if (!function_exists('get_db_connection')) {
        return null;
    }

    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare('SELECT id, file_path, file_name, mime_type FROM employee_documents WHERE id = ? LIMIT 1');
        $stmt->execute([$documentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || empty($row['file_path'])) {
            return null;
        }

        $relativePath = $row['file_path'];
        $relativePath = str_replace('\\', '/', trim($relativePath));
        if ($relativePath === '' || $relativePath[0] === '/' || preg_match('#\.\./#', $relativePath)) {
            return null;
        }

        $fullPath = $realRoot . '/' . $relativePath;
        $resolved = realpath($fullPath);
        if ($resolved === false || !is_file($resolved) || !is_readable($resolved)) {
            return null;
        }
        if (strpos($resolved, $realRoot) !== 0) {
            return null;
        }

        return [
            'path'      => $resolved,
            'file_name' => $row['file_name'] ?? basename($resolved),
            'mime_type' => $row['mime_type'] ?? 'application/octet-stream',
        ];
    } catch (Throwable $e) {
        error_log('storage_resolve_document_by_id: ' . $e->getMessage());
        return null;
    }
}
