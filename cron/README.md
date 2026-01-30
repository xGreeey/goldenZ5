# Automated Database Backups to MinIO

## Overview

The system automatically creates database backups every 30 minutes and uploads them to MinIO in the `db-backups` folder.

## How It Works

1. **Cron Job**: Runs `backup-to-minio.php` every 30 minutes
2. **Backup Creation**: Uses `mysqldump` or PHP fallback to create SQL backup
3. **MinIO Upload**: Automatically uploads to MinIO bucket `goldenz-uploads` in folder `db-backups/`
4. **Logging**: All backup operations are logged to `storage/logs/backup-cron.log`

## Manual Backup

You can manually trigger a backup:

```bash
docker exec hr_web php /var/www/html/cron/backup-to-minio.php
```

Or via web:
- Go to: `http://localhost/test-minio-backup.php`
- Click "Start Backup Test"

## Backup Location in MinIO

- **Bucket**: `goldenz-uploads`
- **Folder**: `db-backups/`
- **Filename format**: `backup_goldenz_hr_YYYY-MM-DD_HHMMSS.sql`

## Viewing Backups

1. **MinIO Console**: `http://localhost:9001`
   - Login: `goldenz` / `SUOMYNONA`
   - Go to `goldenz-uploads` bucket
   - Open `db-backups` folder

2. **Check Logs**:
   ```bash
   docker exec hr_web tail -f /var/www/html/storage/logs/backup-cron.log
   ```

## Backup Retention

Old backups are cleaned up based on the retention policy set in `get_backup_settings()` (default: 90 days).

## Configuration

The backup schedule can be changed in `docker-compose.yml` by modifying the cron schedule in the Dockerfile, or by editing the cron job directly:

```bash
docker exec hr_web crontab -e
```

Current schedule: `*/30 * * * *` (every 30 minutes)

## Troubleshooting

If backups aren't running:

1. Check if cron is running:
   ```bash
   docker exec hr_web service cron status
   ```

2. Check cron logs:
   ```bash
   docker exec hr_web tail -f /var/log/cron.log
   ```

3. Test backup manually:
   ```bash
   docker exec hr_web php /var/www/html/cron/backup-to-minio.php
   ```

4. Check MinIO connection:
   ```bash
   docker exec hr_web mc alias set test http://minio:9000 goldenz SUOMYNONA
   docker exec hr_web mc ls test/goldenz-uploads/db-backups/
   ```
