<?php
/**
 * Phase 2 — HR Dashboard: Employee Summary Cards, KPIs, placeholders for
 * License Watch, Attendance, Leave, Violations. Recent activity from audit_logs.
 */
$page_title = 'Dashboard';

$stats = [
    'employees' => 0,
    'active' => 0,
    'departments' => 0,
    'new_this_month' => 0,
    'documents_count' => 0,
];
$license_watch = [];
$recent_activity = [];

try {
    $pdo = get_db_connection();

    $stmt = $pdo->query("SELECT COUNT(*) FROM employees");
    if ($stmt) {
        $stats['employees'] = (int) $stmt->fetchColumn();
    }
    $stmt = $pdo->query("SELECT COUNT(*) FROM employees WHERE status = 'active'");
    if ($stmt) {
        $stats['active'] = (int) $stmt->fetchColumn();
    }
    $stmt = $pdo->query("SELECT COUNT(DISTINCT department) FROM employees WHERE department IS NOT NULL AND department != ''");
    if ($stmt) {
        $stats['departments'] = (int) $stmt->fetchColumn();
    }
    $stmt = $pdo->query("SELECT COUNT(*) FROM employees WHERE DATE(hire_date) >= DATE_FORMAT(NOW(), '%Y-%m-01')");
    if ($stmt) {
        $stats['new_this_month'] = (int) $stmt->fetchColumn();
    }

    try {
        $pdo->query("SELECT 1 FROM employee_documents LIMIT 1");
        $stmt = $pdo->query("SELECT COUNT(*) FROM employee_documents");
        if ($stmt) {
            $stats['documents_count'] = (int) $stmt->fetchColumn();
        }
    } catch (Throwable $e) {
        // table missing
    }

    try {
        $pdo->query("SELECT 1 FROM audit_logs LIMIT 1");
        $stmt = $pdo->prepare("SELECT action, table_name, record_id, created_at FROM audit_logs ORDER BY created_at DESC LIMIT 15");
        $stmt->execute();
        $recent_activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        // no audit_logs
    }

    // License / certification expiry: placeholder — when licenses table exists, query expiring soon
    try {
        $pdo->query("SELECT 1 FROM employee_licenses LIMIT 1");
        $stmt = $pdo->query("SELECT id, employee_id, license_type, expiry_date FROM employee_licenses WHERE expiry_date IS NOT NULL AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL 90 DAY) ORDER BY expiry_date ASC LIMIT 10");
        if ($stmt) {
            $license_watch = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Throwable $e) {
        // no licenses table
    }
} catch (Throwable $e) {
    // ignore
}

$view = isset($_GET['view']) ? trim($_GET['view']) : 'overview';
$allowed_views = ['overview', 'list', 'board', 'timeline', 'dashboard', 'calendar', 'file'];
if (!in_array($view, $allowed_views, true)) {
    $view = 'overview';
}
$display_name = isset($current_user['name']) && $current_user['name'] !== '' ? $current_user['name'] : ($current_user['username'] ?? 'User');
$user_initial = mb_strtoupper(mb_substr($display_name, 0, 1));
?>
<div class="hr-page hr-page-dashboard">
    <div class="hr-dashboard-header">
        <div class="hr-dashboard-welcome">
            <span class="hr-dashboard-avatar" aria-hidden="true"><?php echo htmlspecialchars($user_initial); ?></span>
            <div class="hr-dashboard-welcome-text">
                <span class="hr-dashboard-user-name"><?php echo htmlspecialchars($display_name); ?></span>
                <span class="hr-dashboard-greeting">Welcome back to Golden Z-5 HR</span>
            </div>
        </div>
    </div>
    <div class="hr-view-tabs-wrap">
        <nav class="hr-view-tabs" role="tablist" aria-label="Dashboard views">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=overview" class="hr-view-tab <?php echo $view === 'overview' ? 'active' : ''; ?>" role="tab" aria-selected="<?php echo $view === 'overview' ? 'true' : 'false'; ?>">Overview</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=list" class="hr-view-tab <?php echo $view === 'list' ? 'active' : ''; ?>" role="tab" aria-selected="<?php echo $view === 'list' ? 'true' : 'false'; ?>">List</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=board" class="hr-view-tab <?php echo $view === 'board' ? 'active' : ''; ?>" role="tab" aria-selected="<?php echo $view === 'board' ? 'true' : 'false'; ?>">Board</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=timeline" class="hr-view-tab <?php echo $view === 'timeline' ? 'active' : ''; ?>" role="tab" aria-selected="<?php echo $view === 'timeline' ? 'true' : 'false'; ?>">Timeline</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=dashboard" class="hr-view-tab <?php echo $view === 'dashboard' ? 'active' : ''; ?>" role="tab" aria-selected="<?php echo $view === 'dashboard' ? 'true' : 'false'; ?>">Dashboard</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=calendar" class="hr-view-tab <?php echo $view === 'calendar' ? 'active' : ''; ?>" role="tab" aria-selected="<?php echo $view === 'calendar' ? 'true' : 'false'; ?>">Calendar</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=file" class="hr-view-tab <?php echo $view === 'file' ? 'active' : ''; ?>" role="tab" aria-selected="<?php echo $view === 'file' ? 'true' : 'false'; ?>">File</a>
        </nav>
    </div>

    <!-- Employee Summary Cards & Quick Stats / KPIs -->
    <section class="hr-section hr-dashboard-card">
        <div class="hr-section-header">
            <h2 class="hr-section-title">Employee Summary &amp; Quick Stats</h2>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="hr-section-link">See all</a>
        </div>
        <div class="hr-cards hr-cards-dashboard">
            <div class="hr-card">
                <div class="hr-card-body">
                    <span class="hr-card-label">Total employees</span>
                    <span class="hr-card-value"><?php echo (int) $stats['employees']; ?></span>
                </div>
                <i class="fas fa-users hr-card-icon" aria-hidden="true"></i>
            </div>
            <div class="hr-card">
                <div class="hr-card-body">
                    <span class="hr-card-label">Active</span>
                    <span class="hr-card-value"><?php echo (int) $stats['active']; ?></span>
                </div>
                <i class="fas fa-user-check hr-card-icon" aria-hidden="true"></i>
            </div>
            <div class="hr-card">
                <div class="hr-card-body">
                    <span class="hr-card-label">Departments</span>
                    <span class="hr-card-value"><?php echo (int) $stats['departments']; ?></span>
                </div>
                <i class="fas fa-building hr-card-icon" aria-hidden="true"></i>
            </div>
            <div class="hr-card">
                <div class="hr-card-body">
                    <span class="hr-card-label">New this month</span>
                    <span class="hr-card-value"><?php echo (int) $stats['new_this_month']; ?></span>
                </div>
                <i class="fas fa-user-plus hr-card-icon" aria-hidden="true"></i>
            </div>
            <div class="hr-card">
                <div class="hr-card-body">
                    <span class="hr-card-label">Documents</span>
                    <span class="hr-card-value"><?php echo (int) $stats['documents_count']; ?></span>
                </div>
                <i class="fas fa-file-alt hr-card-icon" aria-hidden="true"></i>
            </div>
        </div>
    </section>

    <!-- License Watch List (expiry monitoring) -->
    <section class="hr-section hr-dashboard-card">
        <div class="hr-section-header">
            <h2 class="hr-section-title">License Watch List</h2>
            <a href="<?php echo htmlspecialchars($base_url); ?>#licenses" class="hr-section-link">See all</a>
        </div>
        <?php if (empty($license_watch)): ?>
            <div class="hr-placeholder hr-placeholder-reserved">
                <p class="hr-placeholder-message">No data available.</p>
                <p class="hr-text-muted">When the licenses/certifications module is added, expiring items will appear here.</p>
            </div>
        <?php else: ?>
            <div class="hr-table-wrap">
                <table class="hr-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>License / Certification</th>
                            <th>Expiry date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($license_watch as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['employee_id'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($row['license_type'] ?? '—'); ?></td>
                                <td><?php echo isset($row['expiry_date']) ? date('M j, Y', strtotime($row['expiry_date'])) : '—'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <!-- Attendance Summary (placeholder) -->
    <section class="hr-section hr-dashboard-card">
        <div class="hr-section-header">
            <h2 class="hr-section-title">Attendance Summary</h2>
        </div>
        <div class="hr-placeholder hr-placeholder-reserved">
            <p class="hr-placeholder-message">No data available.</p>
            <p class="hr-text-muted">Attendance module not built yet. When available, daily/weekly summary will appear here.</p>
        </div>
    </section>

    <!-- Leave Requests Summary (placeholder) -->
    <section class="hr-section hr-dashboard-card">
        <div class="hr-section-header">
            <h2 class="hr-section-title">Leave Requests Summary</h2>
        </div>
        <div class="hr-placeholder hr-placeholder-reserved">
            <p class="hr-placeholder-message">No data available.</p>
            <p class="hr-text-muted">Leave module not built yet. Pending and approved leaves will appear here.</p>
        </div>
    </section>

    <!-- Violations Summary (placeholder) -->
    <section class="hr-section hr-dashboard-card">
        <div class="hr-section-header">
            <h2 class="hr-section-title">Violations Summary</h2>
        </div>
        <div class="hr-placeholder hr-placeholder-reserved">
            <p class="hr-placeholder-message">No data available.</p>
            <p class="hr-text-muted">Violations module not built yet. When available, summary will appear here.</p>
        </div>
    </section>

    <!-- Recent activity (from audit_logs) -->
    <section class="hr-section hr-dashboard-card">
        <div class="hr-section-header">
            <h2 class="hr-section-title">Recent activity</h2>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="hr-section-link">See all</a>
        </div>
        <?php if (empty($recent_activity)): ?>
            <div class="hr-placeholder">
                <p class="hr-placeholder-message">No recent activity.</p>
                <p class="hr-text-muted">Create, update, export, or download actions will be logged here.</p>
            </div>
        <?php else: ?>
            <div class="hr-table-wrap">
                <table class="hr-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Table / Resource</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_activity as $log): ?>
                            <tr>
                                <td><span class="hr-badge hr-badge-neutral"><?php echo htmlspecialchars($log['action']); ?></span></td>
                                <td><?php echo htmlspecialchars($log['table_name'] ?? '—'); ?><?php echo isset($log['record_id']) && $log['record_id'] ? ' #' . (int)$log['record_id'] : ''; ?></td>
                                <td><?php echo date('M j, Y H:i', strtotime($log['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>
