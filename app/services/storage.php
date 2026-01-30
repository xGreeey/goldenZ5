<?php

declare(strict_types=1);

/**
 * Storage helpers for Golden Z HR System (MinIO, backups).
 * Extend with upload_to_minio() / rclone when needed.
 */

// Optional: implement upload_to_minio($localPath) and have create_database_backup() call it.
// For now cron uses create_database_backup() from config/database.php only.
