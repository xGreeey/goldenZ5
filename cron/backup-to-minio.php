<?php
// Scheduled Database Backup to MinIO
// This script should be run every 30 minutes via cron.
//
// Usage:
//   php backup-to-minio.php
//
// Example crontab (every 30 minutes):
//   0,30 * * * * /usr/local/bin/php /var/www/html/cron/backup-to-minio.php >> /var/www/html/storage/logs/backup-cron.log 2>&1

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set time limit for long-running backup
set_time_limit(600); // 10 minutes

// Include required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/services/storage.php';

// Log file
$log_file = __DIR__ . '/../storage/logs/backup-cron.log';
$log_dir = dirname($log_file);
if (!is_dir($log_dir)) {
    @mkdir($log_dir, 0755, true);
}

function log_message($message) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $message\n";
    @file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    echo $log_entry;
}

log_message("=== Starting scheduled database backup ===");

try {
    // Create backup
    log_message("Creating database backup...");
    $result = create_database_backup();
    
    if ($result['success']) {
        log_message("Backup created successfully: " . $result['filename']);
        log_message("Backup size: " . number_format($result['size'] / 1024, 2) . " KB");
        
        if (isset($result['compressed']) && $result['compressed']) {
            $compression_ratio = $result['compressed_size'] > 0 
                ? number_format((1 - $result['compressed_size'] / $result['size']) * 100, 1) 
                : 0;
            log_message("✓ Backup compressed: " . $result['compressed_filename'] . " (" . 
                       number_format($result['compressed_size'] / 1024, 2) . " KB, " . 
                       $compression_ratio . "% reduction)");
        }
        
        if (isset($result['minio_uploaded']) && $result['minio_uploaded']) {
            log_message("✓ Backup uploaded to MinIO: " . $result['minio_path']);
        } else {
            log_message("⚠ Backup NOT uploaded to MinIO (check storage configuration)");
        }
        
        if (isset($result['gdrive_uploaded']) && $result['gdrive_uploaded']) {
            log_message("✓ Backup uploaded to Google Drive: " . $result['gdrive_path']);
        } else {
            log_message("⚠ Backup NOT uploaded to Google Drive (check rclone configuration)");
        }
        
        log_message("=== Backup completed successfully ===");
        exit(0);
    } else {
        log_message("✗ Backup failed: " . $result['message']);
        log_message("=== Backup failed ===");
        exit(1);
    }
} catch (Exception $e) {
    log_message("✗ Exception during backup: " . $e->getMessage());
    log_message("Stack trace: " . $e->getTraceAsString());
    log_message("=== Backup failed ===");
    exit(1);
}
