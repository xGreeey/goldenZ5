<?php
/**
 * Notifications API — Mark notifications as read
 */
declare(strict_types=1);

$appRoot = dirname(__DIR__, 2);

// Bootstrap
if (is_file($appRoot . '/bootstrap/app.php')) {
    require_once $appRoot . '/bootstrap/app.php';
}

// Middleware: session, auth, CSRF
require_once $appRoot . '/app/middleware/SessionMiddleware.php';
require_once $appRoot . '/app/middleware/AuthMiddleware.php';
require_once $appRoot . '/app/middleware/CsrfMiddleware.php';
SessionMiddleware::handle();
AuthMiddleware::check();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

if (!CsrfMiddleware::verify()) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
    exit;
}

require_once $appRoot . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_POST['action'] ?? '';
$notification_ids = $input['notification_ids'] ?? $_POST['notification_ids'] ?? [];

try {
    $pdo = get_db_connection();
    
    if ($action === 'mark_all_read') {
        // Get all unread alert notification IDs for this user
        $alerts_sql = "SELECT id FROM employee_alerts WHERE status = 'active'";
        $alerts_stmt = $pdo->query($alerts_sql);
        $all_alerts = $alerts_stmt ? $alerts_stmt->fetchAll(PDO::FETCH_COLUMN) : [];
        
        foreach ($all_alerts as $alert_id) {
            $notification_id = (string)$alert_id;
            // Insert or update notification_status
            $upsert_sql = "INSERT INTO notification_status (user_id, notification_id, notification_type, is_read, read_at, updated_at)
                          VALUES (?, ?, 'alert', 1, NOW(), NOW())
                          ON DUPLICATE KEY UPDATE is_read = 1, read_at = NOW(), updated_at = NOW()";
            $pdo->prepare($upsert_sql)->execute([$user_id, $notification_id]);
        }
        
        echo json_encode(['success' => true, 'message' => 'All notifications marked as read']);
    } elseif ($action === 'mark_read' && !empty($notification_ids)) {
        // Mark specific notifications as read
        foreach ($notification_ids as $notification_id) {
            $notification_id = (string)$notification_id;
            $upsert_sql = "INSERT INTO notification_status (user_id, notification_id, notification_type, is_read, read_at, updated_at)
                          VALUES (?, ?, 'alert', 1, NOW(), NOW())
                          ON DUPLICATE KEY UPDATE is_read = 1, read_at = NOW(), updated_at = NOW()";
            $pdo->prepare($upsert_sql)->execute([$user_id, $notification_id]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Notifications marked as read']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid action or missing notification IDs']);
    }
    
} catch (Throwable $e) {
    error_log('notifications-mark-read: ' . $e->getMessage');
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to update notifications']);
}
