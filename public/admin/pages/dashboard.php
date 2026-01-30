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

// Map view keys to human-friendly labels for role tabs
$dashboard_view_labels = [
    'overall'        => 'Overall',
    'human-resource' => 'Human Resource',
    'administration' => 'Administration',
    'operations'     => 'Operations',
    'logistics'      => 'Logistics',
    'accounting'     => 'Accounting',
];

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

// Shared dashboard header + tabs (universal across roles)
$dashboard_scope = 'admin';
require dirname(__DIR__, 2) . '/dashboard/header-tabs.php';
// $view comes from header-tabs.php
$view_label = $dashboard_view_labels[$view] ?? 'Dashboard';
?>

<?php if ($view === 'overall'): ?>

    <!-- Employee Summary Cards & Quick Stats / KPIs -->
    <section class="portal-section portal-dashboard-card">
        <div class="portal-section-header">
            <h2 class="portal-section-title">Employee Summary &amp; Quick Stats</h2>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="portal-section-link">See all</a>
        </div>
        <div class="portal-cards portal-cards-dashboard">
            <div class="portal-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">Total employees</span>
                    <span class="portal-card-value"><?php echo (int) $stats['employees']; ?></span>
                </div>
                <i class="fas fa-users portal-card-icon" aria-hidden="true"></i>
            </div>
            <div class="portal-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">Active</span>
                    <span class="portal-card-value"><?php echo (int) $stats['active']; ?></span>
                </div>
                <i class="fas fa-user-check portal-card-icon" aria-hidden="true"></i>
            </div>
            <div class="portal-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">Departments</span>
                    <span class="portal-card-value"><?php echo (int) $stats['departments']; ?></span>
                </div>
                <i class="fas fa-building portal-card-icon" aria-hidden="true"></i>
            </div>
            <div class="portal-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">New this month</span>
                    <span class="portal-card-value"><?php echo (int) $stats['new_this_month']; ?></span>
                </div>
                <i class="fas fa-user-plus portal-card-icon" aria-hidden="true"></i>
            </div>
            <div class="portal-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">Documents</span>
                    <span class="portal-card-value"><?php echo (int) $stats['documents_count']; ?></span>
                </div>
                <i class="fas fa-file-alt portal-card-icon" aria-hidden="true"></i>
            </div>
        </div>
    </section>

    <!-- License Watch List (expiry monitoring) -->
    <section class="portal-section portal-dashboard-card">
        <div class="portal-section-header">
            <h2 class="portal-section-title">License Watch List</h2>
            <a href="<?php echo htmlspecialchars($base_url); ?>#licenses" class="portal-section-link">See all</a>
        </div>
        <?php if (empty($license_watch)): ?>
            <div class="portal-placeholder portal-placeholder-reserved">
                <p class="portal-placeholder-message">No data available.</p>
                <p class="portal-text-muted">When the licenses/certifications module is added, expiring items will appear here.</p>
            </div>
        <?php else: ?>
            <div class="portal-table-wrap">
                <table class="portal-table">
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
    <section class="portal-section portal-dashboard-card">
        <div class="portal-section-header">
            <h2 class="portal-section-title">Attendance Summary</h2>
        </div>
        <div class="portal-placeholder portal-placeholder-reserved">
            <p class="portal-placeholder-message">No data available.</p>
            <p class="portal-text-muted">Attendance module not built yet. When available, daily/weekly summary will appear here.</p>
        </div>
    </section>

    <!-- Leave Requests Summary (placeholder) -->
    <section class="portal-section portal-dashboard-card">
        <div class="portal-section-header">
            <h2 class="portal-section-title">Leave Requests Summary</h2>
        </div>
        <div class="portal-placeholder portal-placeholder-reserved">
            <p class="portal-placeholder-message">No data available.</p>
            <p class="portal-text-muted">Leave module not built yet. Pending and approved leaves will appear here.</p>
        </div>
    </section>

    <!-- Violations Summary (placeholder) -->
    <section class="portal-section portal-dashboard-card">
        <div class="portal-section-header">
            <h2 class="portal-section-title">Violations Summary</h2>
        </div>
        <div class="portal-placeholder portal-placeholder-reserved">
            <p class="portal-placeholder-message">No data available.</p>
            <p class="portal-text-muted">Violations module not built yet. When available, summary will appear here.</p>
        </div>
    </section>

    <!-- Recent activity (from audit_logs) -->
    <section class="portal-section portal-dashboard-card">
        <div class="portal-section-header">
            <h2 class="portal-section-title">Recent activity</h2>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="portal-section-link">See all</a>
        </div>
        <?php if (empty($recent_activity)): ?>
            <div class="portal-placeholder">
                <p class="portal-placeholder-message">No recent activity.</p>
                <p class="portal-text-muted">Create, update, export, or download actions will be logged here.</p>
            </div>
        <?php else: ?>
            <div class="portal-table-wrap">
                <table class="portal-table">
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
                                <td><span class="portal-badge portal-badge-neutral"><?php echo htmlspecialchars($log['action']); ?></span></td>
                                <td><?php echo htmlspecialchars($log['table_name'] ?? '—'); ?><?php echo isset($log['record_id']) && $log['record_id'] ? ' #' . (int)$log['record_id'] : ''; ?></td>
                                <td><?php echo date('M j, Y H:i', strtotime($log['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

<?php else: ?>
    <!-- Role-specific dashboard tab: empty-state banner (no container card) -->
    <div class="portal-dashboard-empty">
        <div class="portal-dashboard-empty-header">
            <h2 class="portal-dashboard-empty-title">
                <?php echo htmlspecialchars($view_label); ?> reports &amp; analytics
            </h2>
        </div>
        <div class="portal-dashboard-empty-body" role="status" aria-live="polite">
            <p class="portal-dashboard-empty-primary">No reports available yet.</p>
            <p class="portal-dashboard-empty-secondary">
                Reports and analytics will appear here when configured.
            </p>
        </div>
    </div>
<?php endif; ?>
</div>
