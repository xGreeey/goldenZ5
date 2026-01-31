<?php
/**
 * Super Admin Dashboard — KPI cards, System Health, Access & Permissions,
 * Audit feed, Charts placeholders, Global Search.
 * Uses same portal-* layout/card styles as HR dashboard. JS gates by permission.
 *
 * BACKEND: Replace mock data with real queries; keep data-* attributes for JS gating.
 */
$page_title = 'Super Admin Dashboard';
$display_name = isset($current_user['name']) && $current_user['name'] !== '' ? $current_user['name'] : ($current_user['username'] ?? 'Super Admin');
$user_initial = mb_strtoupper(mb_substr($display_name, 0, 1));

// Empty dashboard state: user has no assigned permissions (intentional, not an error)
if (empty($user_permissions)) {
?>
<div class="portal-page portal-page-dashboard sadash-page sadash-empty-dashboard" id="sadashEmptyDashboard">
    <div class="sadash-empty-state">
        <div class="sadash-empty-icon" aria-hidden="true">
            <i class="fas fa-inbox"></i>
        </div>
        <h2 class="sadash-empty-title">No modules assigned</h2>
        <p class="sadash-empty-desc">Your account does not have any dashboard modules or tabs assigned yet. This is intentional—you’re logged in successfully, but an administrator needs to assign permissions to your role before you’ll see dashboard content.</p>
        <p class="sadash-empty-hint">Contact your administrator or system owner to request access to the modules you need.</p>
        <div class="sadash-empty-actions">
            <a href="<?php echo htmlspecialchars($base_url ?? '/super_admin'); ?>?page=settings" class="portal-btn portal-btn-secondary">
                <i class="fas fa-cog" aria-hidden="true"></i>
                Account settings
            </a>
            <a href="/?logout=1" class="portal-btn portal-btn-secondary">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                Sign out
            </a>
        </div>
    </div>
</div>
<?php
    return;
}

// Mock KPI data — replace with backend
$kpis = [
    'total_users' => 1247,
    'active_users' => 1189,
    'locked_accounts' => 12,
    'pending_approvals' => 23,
    'system_alerts' => 3,
    'role_distribution' => 8,
];
$kpi_trends = [
    'total_users' => '+2%',
    'active_users' => '+1%',
    'locked_accounts' => '0%',
    'pending_approvals' => '-5%',
    'system_alerts' => null,
    'role_distribution' => null,
];

// Mock system health — replace with backend
$system_health = [
    'app' => 'ok',
    'db' => 'ok',
    'storage' => 'warn',
    'last_backup' => '2025-01-29 03:00',
    'failed_jobs' => 2,
    'security_flags' => 1,
];

// Mock activity feed — replace with audit_logs
$activity_feed = [
    ['type' => 'login', 'title' => 'Recent login', 'desc' => 'j.smith from 192.168.1.10', 'ts' => '2025-01-30 09:15'],
    ['type' => 'role_change', 'title' => 'Role/permission change', 'desc' => 'Role "hr_manager" updated', 'ts' => '2025-01-30 08:42'],
    ['type' => 'failed_login', 'title' => 'Failed login attempt', 'desc' => 'unknown@example.com from 10.0.0.5', 'ts' => '2025-01-30 08:30'],
    ['type' => 'settings', 'title' => 'Settings change', 'desc' => 'SMTP config updated', 'ts' => '2025-01-29 18:00'],
    ['type' => 'login', 'title' => 'Recent login', 'desc' => 'admin from office', 'ts' => '2025-01-29 17:55'],
];
?>
<div class="portal-page portal-page-dashboard sadash-page">
    <div class="portal-dashboard-header">
        <div class="portal-dashboard-welcome">
            <span class="portal-dashboard-avatar" aria-hidden="true"><?php echo htmlspecialchars($user_initial); ?></span>
            <div class="portal-dashboard-welcome-text">
                <span class="portal-dashboard-user-name"><?php echo htmlspecialchars($display_name); ?></span>
                <span class="portal-dashboard-greeting">Welcome to Super Admin</span>
            </div>
        </div>
    </div>

    <!-- A. KPI Cards Row (same style as HR dashboard) -->
    <section class="portal-section portal-dashboard-card sadash-section" aria-labelledby="sadash-kpi-title">
        <div class="portal-section-header">
            <h2 id="sadash-kpi-title" class="portal-section-title">System Overview</h2>
        </div>
        <div class="portal-cards portal-cards-dashboard sadash-kpi-cards">
            <div class="portal-card sadash-kpi-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">Total Users</span>
                    <span class="portal-card-value" data-sadash-mock="total_users"><?php echo (int) $kpis['total_users']; ?></span>
                    <?php if (!empty($kpi_trends['total_users'])): ?>
                    <span class="sadash-trend sadash-trend-up" aria-label="Trend up"><?php echo htmlspecialchars($kpi_trends['total_users']); ?></span>
                    <?php endif; ?>
                </div>
                <i class="fas fa-users portal-card-icon" aria-hidden="true"></i>
            </div>
            <div class="portal-card sadash-kpi-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">Active Users</span>
                    <span class="portal-card-value" data-sadash-mock="active_users"><?php echo (int) $kpis['active_users']; ?></span>
                    <?php if (!empty($kpi_trends['active_users'])): ?>
                    <span class="sadash-trend sadash-trend-up" aria-label="Trend up"><?php echo htmlspecialchars($kpi_trends['active_users']); ?></span>
                    <?php endif; ?>
                </div>
                <i class="fas fa-user-check portal-card-icon" aria-hidden="true"></i>
            </div>
            <div class="portal-card sadash-kpi-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">Locked Accounts</span>
                    <span class="portal-card-value" data-sadash-mock="locked"><?php echo (int) $kpis['locked_accounts']; ?></span>
                </div>
                <i class="fas fa-lock portal-card-icon" aria-hidden="true"></i>
            </div>
            <div class="portal-card sadash-kpi-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">Pending Approvals</span>
                    <span class="portal-card-value" data-sadash-mock="pending"><?php echo (int) $kpis['pending_approvals']; ?></span>
                    <?php if (!empty($kpi_trends['pending_approvals'])): ?>
                    <span class="sadash-trend sadash-trend-down" aria-label="Trend down"><?php echo htmlspecialchars($kpi_trends['pending_approvals']); ?></span>
                    <?php endif; ?>
                </div>
                <i class="fas fa-clock portal-card-icon" aria-hidden="true"></i>
            </div>
            <div class="portal-card sadash-kpi-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">System Alerts</span>
                    <span class="portal-card-value" data-sadash-mock="alerts"><?php echo (int) $kpis['system_alerts']; ?></span>
                </div>
                <i class="fas fa-exclamation-triangle portal-card-icon" aria-hidden="true"></i>
            </div>
            <div class="portal-card sadash-kpi-card">
                <div class="portal-card-body">
                    <span class="portal-card-label">Role Distribution</span>
                    <span class="portal-card-value" data-sadash-mock="roles"><?php echo (int) $kpis['role_distribution']; ?></span>
                </div>
                <i class="fas fa-user-tag portal-card-icon" aria-hidden="true"></i>
            </div>
        </div>
    </section>

    <div class="sadash-grid sadash-two-col">
        <!-- B. System Health Panel (watchlist-style card) -->
        <section class="portal-section portal-dashboard-card sadash-section sadash-health" aria-labelledby="sadash-health-title">
            <div class="portal-section-header">
                <h2 id="sadash-health-title" class="portal-section-title">System Health</h2>
            </div>
            <div class="sadash-health-list">
                <div class="sadash-health-item">
                    <span class="sadash-health-label">App</span>
                    <span class="sadash-health-status sadash-status-ok" data-status="ok" aria-label="Status OK">OK</span>
                </div>
                <div class="sadash-health-item">
                    <span class="sadash-health-label">DB</span>
                    <span class="sadash-health-status sadash-status-ok" data-status="ok" aria-label="Status OK">OK</span>
                </div>
                <div class="sadash-health-item">
                    <span class="sadash-health-label">Storage</span>
                    <span class="sadash-health-status sadash-status-warn" data-status="warn" aria-label="Status Warning">Warn</span>
                </div>
                <div class="sadash-health-meta">
                    <span class="sadash-health-meta-label">Last Backup</span>
                    <span class="sadash-health-meta-value" data-sadash-mock="last_backup"><?php echo htmlspecialchars($system_health['last_backup']); ?></span>
                </div>
                <div class="sadash-health-meta">
                    <span class="sadash-health-meta-label">Failed Jobs</span>
                    <span class="sadash-health-meta-value" data-sadash-mock="failed_jobs"><?php echo (int) $system_health['failed_jobs']; ?></span>
                </div>
                <div class="sadash-health-meta">
                    <span class="sadash-health-meta-label">Security Flags</span>
                    <span class="sadash-health-meta-value" data-sadash-mock="security_flags"><?php echo (int) $system_health['security_flags']; ?></span>
                </div>
            </div>
        </section>

        <!-- C. Access & Permissions Panel (permission-gated actions) -->
        <section class="portal-section portal-dashboard-card sadash-section sadash-access" id="sadash-access-panel" data-permission-any="roles.manage|permissions.manage.system|modules.enable_disable" aria-labelledby="sadash-access-title">
            <div class="portal-section-header">
                <h2 id="sadash-access-title" class="portal-section-title">Access &amp; Permissions</h2>
            </div>
            <div class="sadash-panel-content">
                <p class="sadash-hint portal-text-muted">Visibility is permission-based.</p>
                <div class="sadash-quick-actions">
                    <a href="#" class="portal-btn portal-btn-primary sadash-action" data-permission="roles.manage" aria-label="Manage Roles" id="sadash-btn-roles">Manage Roles</a>
                    <a href="#" class="portal-btn portal-btn-primary sadash-action" data-permission="permissions.manage.system" aria-label="Manage Permissions" id="sadash-btn-permissions">Manage Permissions</a>
                    <a href="#" class="portal-btn portal-btn-secondary sadash-action" data-permission="permissions.manage.system" aria-label="Assign Access" id="sadash-btn-assign">Assign Access</a>
                    <a href="#" class="portal-btn portal-btn-secondary sadash-action" data-permission="modules.enable_disable" aria-label="Module Visibility" id="sadash-btn-modules">Module Visibility</a>
                </div>
            </div>
            <div class="sadash-no-access" id="sadash-access-no-access" hidden aria-live="polite">You don’t have access to any of these actions.</div>
        </section>
    </div>

    <!-- Global Search / Admin Jump (permission-gated) -->
    <section class="portal-section portal-dashboard-card sadash-section sadash-search-wrap" id="sadash-search-panel" data-permission="users.manage" aria-labelledby="sadash-search-title">
        <div class="portal-section-header">
            <h2 id="sadash-search-title" class="portal-section-title">Global Search</h2>
        </div>
        <div class="sadash-panel-content">
            <p class="portal-text-muted" style="margin-bottom:12px">Search user by username or email (UI only).</p>
            <div class="sadash-search-row">
                <div class="portal-search-wrap sadash-search-input-wrap">
                    <i class="fas fa-search portal-search-icon" aria-hidden="true"></i>
                    <input type="text" class="portal-search-input sadash-search-input" placeholder="Username or email" aria-label="Search user by username or email" id="sadash-search-input">
                </div>
                <div class="sadash-search-actions">
                    <button type="button" class="portal-btn portal-btn-primary sadash-search-btn" data-permission="users.manage" aria-label="Open User" id="sadash-btn-open-user">Open User</button>
                    <button type="button" class="portal-btn portal-btn-secondary sadash-search-btn" data-permission="users.manage" aria-label="Reset Password" id="sadash-btn-reset-pw">Reset Password</button>
                    <button type="button" class="portal-btn portal-btn-secondary sadash-search-btn" data-permission="users.manage" aria-label="Suspend" id="sadash-btn-suspend">Suspend</button>
                </div>
            </div>
        </div>
        <div class="sadash-no-access" id="sadash-search-no-access" hidden aria-live="polite">You don’t have permission to manage users.</div>
    </section>

    <div class="sadash-grid sadash-two-col">
        <!-- D. Audit & Activity Feed (gated by audit.view) -->
        <section class="portal-section portal-dashboard-card sadash-section sadash-audit" id="sadash-audit-panel" data-permission="audit.view" aria-labelledby="sadash-audit-title">
            <div class="portal-section-header">
                <h2 id="sadash-audit-title" class="portal-section-title">Audit &amp; Activity Feed</h2>
                <a href="<?php echo htmlspecialchars($base_url); ?>#audit" class="portal-section-link">See all</a>
            </div>
            <div class="sadash-panel-content">
                <ul class="sadash-activity-list" aria-label="Recent activity">
                    <?php foreach ($activity_feed as $item): ?>
                    <li class="sadash-activity-item">
                        <span class="sadash-activity-icon" aria-hidden="true"><i class="fas fa-<?php echo $item['type'] === 'login' ? 'sign-in-alt' : ($item['type'] === 'failed_login' ? 'times-circle' : ($item['type'] === 'role_change' ? 'user-cog' : 'cog')); ?>"></i></span>
                        <div class="sadash-activity-body">
                            <span class="sadash-activity-title"><?php echo htmlspecialchars($item['title']); ?></span>
                            <span class="sadash-activity-desc"><?php echo htmlspecialchars($item['desc']); ?></span>
                            <span class="sadash-activity-ts"><?php echo htmlspecialchars($item['ts']); ?></span>
                        </div>
                        <a href="#audit-detail" class="portal-section-link sadash-activity-link">View details</a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="sadash-no-access" id="sadash-audit-no-access" hidden aria-live="polite">You don’t have permission to view audit logs.</div>
        </section>

        <!-- E. System-Wide Stats Charts (gated by reports.view.all) -->
        <section class="portal-section portal-dashboard-card sadash-section sadash-reports" id="sadash-reports-panel" data-permission="reports.view.all" aria-labelledby="sadash-reports-title">
            <div class="portal-section-header">
                <h2 id="sadash-reports-title" class="portal-section-title">System-Wide Stats</h2>
            </div>
            <div class="sadash-panel-content">
                <div class="sadash-chart-placeholder" aria-label="User Status Breakdown placeholder">
                    <span class="sadash-chart-label">User Status (Active / Inactive / Suspended)</span>
                    <div class="sadash-bar-placeholder">
                        <div class="sadash-bar sadash-bar-active" style="width:70%"></div>
                        <div class="sadash-bar sadash-bar-inactive" style="width:20%"></div>
                        <div class="sadash-bar sadash-bar-suspended" style="width:10%"></div>
                    </div>
                </div>
                <div class="sadash-chart-placeholder" aria-label="Login Attempts Trend placeholder">
                    <span class="sadash-chart-label">Login Attempts Trend</span>
                    <div class="sadash-line-placeholder"></div>
                </div>
            </div>
            <div class="sadash-no-access" id="sadash-reports-no-access" hidden aria-live="polite">You don’t have permission to view reports.</div>
        </section>
    </div>
    <!-- sadashConfig is set in layout.php; super_admin_dashboard.js applies permission gating -->
</div>
