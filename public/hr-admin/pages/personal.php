<?php
/**
 * Personal dashboard — user's main site / personal overview.
 * Quick links, my tasks, recent activity. Linked from popup "Main site".
 */
$page_title = 'Personal';

$recent_activity = [];
try {
    $pdo = get_db_connection();
    try {
        $pdo->query("SELECT 1 FROM audit_logs LIMIT 1");
        $stmt = $pdo->prepare("SELECT action, table_name, record_id, created_at FROM audit_logs ORDER BY created_at DESC LIMIT 10");
        $stmt->execute();
        $recent_activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        // no audit_logs
    }
} catch (Throwable $e) {
    // ignore
}
?>
<div class="hr-page hr-page-personal">
    <header class="hr-page-header">
        <nav class="hr-breadcrumb" aria-label="Breadcrumb">
            <ol class="hr-breadcrumb-list">
                <li class="hr-breadcrumb-item">
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a>
                </li>
                <li class="hr-breadcrumb-item hr-breadcrumb-current" aria-current="page">Personal</li>
            </ol>
        </nav>
        <div class="hr-page-header-actions">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings" class="hr-btn hr-btn-ghost">Settings</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard" class="hr-btn hr-btn-primary">
                <i class="fas fa-th-large" aria-hidden="true"></i>
                HR Dashboard
            </a>
        </div>
    </header>

    <section class="hr-section">
        <h2 class="hr-section-title">Personal dashboard</h2>
        <p class="hr-text-muted hr-mb-24">Your overview and quick access. Use the links below or the sidebar to navigate.</p>
        <div class="hr-cards hr-cards-dashboard">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard" class="hr-card hr-card-link">
                <div class="hr-card-body">
                    <span class="hr-card-label">HR Dashboard</span>
                    <span class="hr-card-value">Overview</span>
                </div>
                <i class="fas fa-th-large hr-card-icon" aria-hidden="true"></i>
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="hr-card hr-card-link">
                <div class="hr-card-body">
                    <span class="hr-card-label">Employees</span>
                    <span class="hr-card-value">Directory</span>
                </div>
                <i class="fas fa-users hr-card-icon" aria-hidden="true"></i>
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=documents" class="hr-card hr-card-link">
                <div class="hr-card-body">
                    <span class="hr-card-label">Documents</span>
                    <span class="hr-card-value">Files</span>
                </div>
                <i class="fas fa-folder-open hr-card-icon" aria-hidden="true"></i>
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=tasks" class="hr-card hr-card-link">
                <div class="hr-card-body">
                    <span class="hr-card-label">My tasks</span>
                    <span class="hr-card-value">Tasks</span>
                </div>
                <i class="fas fa-tasks hr-card-icon" aria-hidden="true"></i>
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings" class="hr-card hr-card-link">
                <div class="hr-card-body">
                    <span class="hr-card-label">Account</span>
                    <span class="hr-card-value">Settings</span>
                </div>
                <i class="fas fa-cog hr-card-icon" aria-hidden="true"></i>
            </a>
        </div>
    </section>

    <section class="hr-section">
        <h2 class="hr-section-title">Recent activity</h2>
        <?php if (empty($recent_activity)): ?>
            <div class="hr-placeholder">
                <p class="hr-placeholder-message">No recent activity.</p>
                <p class="hr-text-muted">Create, update, or export actions in HR Admin will appear here.</p>
            </div>
        <?php else: ?>
            <div class="hr-table-wrap">
                <table class="hr-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Resource</th>
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
