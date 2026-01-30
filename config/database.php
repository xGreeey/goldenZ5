<?php

declare(strict_types=1);

/**
 * Database connection and helpers for Golden Z HR System.
 * Reads DB_* from environment (Docker or .env).
 */

// Ensure .env is loaded when used outside bootstrap (e.g. cron)
if (!array_key_exists('DB_HOST', $_ENV) && getenv('DB_HOST') === false) {
    $envFile = dirname(__DIR__) . '/.env';
    if (is_file($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                continue;
            }
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
                putenv("$name=$value");
            }
        }
    }
}

if (!function_exists('env')) {
    function env(string $key, ?string $default = null): ?string {
        $v = getenv($key);
        if ($v !== false) {
            return $v;
        }
        return $_ENV[$key] ?? $default;
    }
}

$dbHost = env('DB_HOST', 'localhost');
$dbPort = env('DB_PORT', '3306');
$dbName = env('DB_DATABASE', 'goldenz_hr');
$dbUser = env('DB_USERNAME', 'root');
$dbPass = env('DB_PASSWORD', '');

/** @var PDO|null */
$pdoConnection = null;

/**
 * Get PDO connection to goldenz_hr database.
 * @return PDO
 * @throws Exception
 */
function get_db_connection(): PDO {
    global $dbHost, $dbPort, $dbName, $dbUser, $dbPass, $pdoConnection;
    if ($pdoConnection !== null) {
        return $pdoConnection;
    }
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $dbHost,
        $dbPort,
        $dbName
    );
    $opts = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdoConnection = new PDO($dsn, $dbUser, $dbPass, $opts);
    return $pdoConnection;
}

/**
 * Run a prepared statement (SQL injection safe). All dynamic values must be in $params.
 * Use ? placeholders in SQL and pass an array of values in order.
 *
 * @param string $sql    SQL with ? placeholders (no string concatenation of user input)
 * @param array  $params Ordered list of bound values
 * @return PDOStatement
 * @throws Throwable
 */
function db_prepare(string $sql, array $params = []): PDOStatement
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Execute SQL (INSERT/UPDATE/DELETE) via prepared statement. Returns true on success.
 *
 * @param string $sql    SQL with ? placeholders
 * @param array  $params Bound values
 * @return bool
 */
function db_execute(string $sql, array $params = []): bool
{
    db_prepare($sql, $params);
    return true;
}

/**
 * Run SELECT and fetch one row. Returns associative array or null.
 *
 * @param string $sql    SQL with ? placeholders
 * @param array  $params Bound values
 * @return array<string, mixed>|null
 */
function db_fetch_one(string $sql, array $params = []): ?array
{
    $stmt = db_prepare($sql, $params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

/**
 * Run SELECT and fetch all rows. Returns list of associative arrays.
 *
 * @param string $sql    SQL with ? placeholders
 * @param array  $params Bound values
 * @return array<int, array<string, mixed>>
 */
function db_fetch_all(string $sql, array $params = []): array
{
    $stmt = db_prepare($sql, $params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows ?: [];
}

/**
 * Log audit event to audit_logs table (no-op if table missing).
 */
function log_audit_event(
    string $action,
    string $table_name,
    ?int $record_id,
    ?array $old_values,
    ?array $new_values,
    ?int $user_id
): void {
    try {
        db_execute(
            'INSERT INTO audit_logs (action, table_name, record_id, old_values, new_values, user_id, ip_address, user_agent, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
            [
                $action,
                $table_name,
                $record_id,
                $old_values ? json_encode($old_values) : null,
                $new_values ? json_encode($new_values) : null,
                $user_id,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
            ]
        );
    } catch (Throwable $e) {
        error_log('log_audit_event: ' . $e->getMessage());
    }
}

/**
 * Log security event (stored in audit_logs with action prefix).
 */
function log_security_event(string $event, string $details): void {
    try {
        db_execute(
            'INSERT INTO audit_logs (action, table_name, record_id, new_values, user_id, ip_address, user_agent, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())',
            [
                'SECURITY_' . $event,
                'security',
                null,
                json_encode(['details' => $details]),
                $_SESSION['user_id'] ?? null,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
            ]
        );
    } catch (Throwable $e) {
        error_log('log_security_event: ' . $e->getMessage());
    }
}

/**
 * Log system event (e.g. info/warning/error); stored in audit_logs.
 */
function log_system_event(string $level, string $message, string $category = 'app', array $context = []): void {
    try {
        db_execute(
            'INSERT INTO audit_logs (action, table_name, record_id, new_values, user_id, ip_address, user_agent, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())',
            [
                'SYSTEM_' . strtoupper($level),
                $category,
                null,
                json_encode(['message' => $message, 'context' => $context]),
                $_SESSION['user_id'] ?? null,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
            ]
        );
    } catch (Throwable $e) {
        error_log('log_system_event: ' . $e->getMessage());
    }
}

/**
 * Log employee field change to employee_history (Phase 1).
 */
function log_employee_history(
    int $employee_id,
    string $field_name,
    ?string $old_value,
    ?string $new_value,
    ?int $changed_by
): void {
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare(
            'INSERT INTO employee_history (employee_id, field_name, old_value, new_value, changed_by, changed_at) VALUES (?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([$employee_id, $field_name, $old_value, $new_value, $changed_by]);
    } catch (Throwable $e) {
        error_log('log_employee_history: ' . $e->getMessage());
    }
}

/**
 * Create a database backup (for cron). Returns result array.
 * Writes to storage/backups/ and optionally compresses.
 */
function create_database_backup(): array {
    $dbHost = env('DB_HOST', 'localhost');
    $dbPort = env('DB_PORT', '3306');
    $dbName = env('DB_DATABASE', 'goldenz_hr');
    $dbUser = env('DB_USERNAME', 'root');
    $dbPass = env('DB_PASSWORD', '');
    $backupDir = dirname(__DIR__) . '/storage/backups';
    if (!is_dir($backupDir)) {
        @mkdir($backupDir, 0755, true);
    }
    $filename = 'goldenz_hr_' . date('Y-m-d_His') . '.sql';
    $path = $backupDir . '/' . $filename;
    $passArg = $dbPass !== '' ? '-p' . escapeshellarg($dbPass) : '';
    $cmd = sprintf(
        'mysqldump -h %s -P %s -u %s %s --single-transaction --routines --triggers %s 2>&1',
        escapeshellarg($dbHost),
        escapeshellarg($dbPort),
        escapeshellarg($dbUser),
        $passArg,
        escapeshellarg($dbName)
    );
    exec($cmd, $output, $code);
    $content = implode("\n", $output);
    if ($code !== 0 || strpos($content, 'ERROR') !== false) {
        return ['success' => false, 'message' => 'mysqldump failed: ' . implode(' ', $output)];
    }
    $written = @file_put_contents($path, $content);
    if ($written === false) {
        return ['success' => false, 'message' => 'Could not write backup file'];
    }
    $size = (int) $written;
    return [
        'success' => true,
        'filename' => $filename,
        'path'     => $path,
        'size'     => $size,
    ];
}
