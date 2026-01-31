<?php
/**
 * Notifications API — Fetch notifications from employee_alerts
 * Returns JSON with notifications and unread count
 */
declare(strict_types=1);

$appRoot = dirname(__DIR__, 2);

// Bootstrap
if (is_file($appRoot . '/bootstrap/app.php')) {
    require_once $appRoot . '/bootstrap/app.php';
}

// Middleware: session, auth
require_once $appRoot . '/app/middleware/SessionMiddleware.php';
require_once $appRoot . '/app/middleware/AuthMiddleware.php';
SessionMiddleware::handle();
AuthMiddleware::check();

require_once $appRoot . '/config/database.php';
require_once $appRoot . '/includes/security.php';

header('Content-Type: application/json; charset=utf-8');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    $pdo = get_db_connection();
    
    // Fetch active employee alerts
    $alerts_sql = "SELECT 
        ea.id,
        ea.employee_id,
        ea.alert_type,
        ea.title,
        ea.description,
        ea.alert_date,
        ea.due_date,
        ea.priority,
        ea.status,
        ea.created_at,
        e.employee_number,
        e.first_name,
        e.last_name,
        e.surname
    FROM employee_alerts ea
    LEFT JOIN employees e ON ea.employee_id = e.id
    WHERE ea.status = 'active'
    ORDER BY 
        CASE ea.priority
            WHEN 'urgent' THEN 1
            WHEN 'high' THEN 2
            WHEN 'medium' THEN 3
            WHEN 'low' THEN 4
        END,
        ea.due_date ASC,
        ea.created_at DESC
    LIMIT 50";
    
    $alerts_stmt = $pdo->query($alerts_sql);
    $alerts = $alerts_stmt ? $alerts_stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    
    // Fetch read status for current user
    $notification_ids = array_map(function($alert) {
        return (string)$alert['id'];
    }, $alerts);
    
    $read_status = [];
    if (!empty($notification_ids)) {
        $placeholders = implode(',', array_fill(0, count($notification_ids), '?'));
        $status_sql = "SELECT notification_id, is_read, is_dismissed 
                       FROM notification_status 
                       WHERE user_id = ? AND notification_type = 'alert' AND notification_id IN ($placeholders)";
        $status_stmt = $pdo->prepare($status_sql);
        $status_stmt->execute(array_merge([$user_id], $notification_ids));
        $status_rows = $status_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($status_rows as $row) {
            $read_status[$row['notification_id']] = [
                'is_read' => (bool)$row['is_read'],
                'is_dismissed' => (bool)$row['is_dismissed']
            ];
        }
    }
    
    // Format notifications
    $notifications = [];
    $unread_count = 0;
    
    foreach ($alerts as $alert) {
        $notification_id = (string)$alert['id'];
        $is_read = $read_status[$notification_id]['is_read'] ?? false;
        $is_dismissed = $read_status[$notification_id]['is_dismissed'] ?? false;
        
        // Skip dismissed notifications
        if ($is_dismissed) {
            continue;
        }
        
        if (!$is_read) {
            $unread_count++;
        }
        
        // Determine icon based on alert type
        $icon_map = [
            'license_expiry' => 'fa-id-card',
            'document_expiry' => 'fa-file-alt',
            'missing_documents' => 'fa-exclamation-triangle',
            'contract_expiry' => 'fa-file-contract',
            'training_due' => 'fa-graduation-cap',
            'medical_expiry' => 'fa-heartbeat',
            'other' => 'fa-bell'
        ];
        $icon = $icon_map[$alert['alert_type']] ?? 'fa-bell';
        
        // Format time ago
        $created_at = new DateTime($alert['created_at']);
        $now = new DateTime();
        $diff = $now->diff($created_at);
        $time_ago = '';
        if ($diff->days > 0) {
            $time_ago = $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' ago';
        } elseif ($diff->h > 0) {
            $time_ago = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        } elseif ($diff->i > 0) {
            $time_ago = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
        } else {
            $time_ago = 'Just now';
        }
        
        // Employee name
        $employee_name = trim(($alert['first_name'] ?? '') . ' ' . ($alert['last_name'] ?? '') . ' ' . ($alert['surname'] ?? ''));
        if (empty($employee_name)) {
            $employee_name = 'Employee #' . ($alert['employee_number'] ?? $alert['employee_id']);
        }
        
        $notifications[] = [
            'id' => $notification_id,
            'type' => 'alert',
            'title' => $alert['title'],
            'message' => $alert['description'] ?? '',
            'employee_name' => $employee_name,
            'employee_id' => $alert['employee_id'],
            'priority' => $alert['priority'],
            'due_date' => $alert['due_date'],
            'icon' => $icon,
            'time_ago' => $time_ago,
            'unread' => !$is_read,
            'url' => '/admin?page=employees&view=' . $alert['employee_id']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'unread_count' => $unread_count
    ]);
    
} catch (Throwable $e) {
    error_log('notifications-api: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to load notifications',
        'notifications' => [],
        'unread_count' => 0
    ]);
}
