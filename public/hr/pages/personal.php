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
<div class="portal-page portal-page-personal">
    <header class="portal-page-header">
        <nav class="portal-breadcrumb" aria-label="Breadcrumb">
            <ol class="portal-breadcrumb-list">
                <li class="portal-breadcrumb-item">
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a>
                </li>
                <li class="portal-breadcrumb-item portal-breadcrumb-current" aria-current="page">Personal</li>
            </ol>
        </nav>
        <div class="portal-page-header-actions">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings" class="portal-btn portal-btn-ghost">Settings</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard" class="portal-btn portal-btn-primary">
                <i class="fas fa-th-large" aria-hidden="true"></i>
                HR Dashboard
            </a>
        </div>
    </header>

    <section class="portal-section">
        <h2 class="portal-section-title">Personal dashboard</h2>
        <p class="portal-text-muted portal-mb-24">Your overview and quick access. Use the links below or the sidebar to navigate.</p>
        <div class="portal-cards portal-cards-dashboard">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard" class="portal-card portal-card-link">
                <div class="portal-card-body">
                    <span class="portal-card-label">HR Dashboard</span>
                    <span class="portal-card-value">Overview</span>
                </div>
                <i class="fas fa-th-large portal-card-icon" aria-hidden="true"></i>
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="portal-card portal-card-link">
                <div class="portal-card-body">
                    <span class="portal-card-label">Employees</span>
                    <span class="portal-card-value">Directory</span>
                </div>
                <i class="fas fa-users portal-card-icon" aria-hidden="true"></i>
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=documents" class="portal-card portal-card-link">
                <div class="portal-card-body">
                    <span class="portal-card-label">Documents</span>
                    <span class="portal-card-value">Files</span>
                </div>
                <i class="fas fa-folder-open portal-card-icon" aria-hidden="true"></i>
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=tasks" class="portal-card portal-card-link">
                <div class="portal-card-body">
                    <span class="portal-card-label">My tasks</span>
                    <span class="portal-card-value">Tasks</span>
                </div>
                <i class="fas fa-tasks portal-card-icon" aria-hidden="true"></i>
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings" class="portal-card portal-card-link">
                <div class="portal-card-body">
                    <span class="portal-card-label">Account</span>
                    <span class="portal-card-value">Settings</span>
                </div>
                <i class="fas fa-cog portal-card-icon" aria-hidden="true"></i>
            </a>
        </div>
    </section>

    <section class="portal-section">
        <h2 class="portal-section-title">Recent activity</h2>
        <?php if (empty($recent_activity)): ?>
            <div class="portal-placeholder">
                <p class="portal-placeholder-message">No recent activity.</p>
                <p class="portal-text-muted">Create, update, or export actions in HR Admin will appear here.</p>
            </div>
        <?php else: ?>
            <div class="portal-table-wrap">
                <table class="portal-table">
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
</div>
