<?php
/**
 * Company Dashboards Overview – Standalone page (no auth required)
 * UI-only preview of what each role's dashboard contains.
 */
$pageTitle = 'Company Dashboards Overview';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> – Golden Z-5 HR</title>
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.php">
    <link rel="icon" type="image/png" href="/assets/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="/assets/css/dashboards-overview.css" rel="stylesheet">
</head>
<body class="dashboards-overview-page">
    <header class="dashboards-overview-topbar">
        <div class="dashboards-overview-topbar-inner">
            <a href="/" class="dashboards-overview-back-link">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Login</span>
            </a>
            <div class="dashboards-overview-topbar-title">
                <h1 class="dashboards-overview-title">Company Dashboards Overview</h1>
                <p class="dashboards-overview-subtitle">A detailed preview of what each department sees — statistics, KPIs, and key sections.</p>
            </div>
        </div>
    </header>

    <main class="dashboards-overview-main">
        <div class="dashboards-overview-container">
            <div class="dashboards-overview-view-toggle">
                <button type="button" class="dashboards-overview-view-btn active" id="dashboardsOverviewViewTabs">By role</button>
                <button type="button" class="dashboards-overview-view-btn" id="dashboardsOverviewViewGrid">Grid overview</button>
            </div>

            <div id="dashboardsOverviewTabsContainer">
                <div class="dashboards-overview-tabs">
                    <button type="button" class="dashboards-overview-tab active" data-dashboards-role-tab="admin">Admin</button>
                    <button type="button" class="dashboards-overview-tab" data-dashboards-role-tab="hr">Human Resources</button>
                    <button type="button" class="dashboards-overview-tab" data-dashboards-role-tab="accounting">Accounting</button>
                    <button type="button" class="dashboards-overview-tab" data-dashboards-role-tab="logistics">Logistics</button>
                    <button type="button" class="dashboards-overview-tab" data-dashboards-role-tab="operations">Operations</button>
                </div>
            </div>

            <div class="dashboards-overview-body">
                <div class="dashboards-overview-grid hidden" id="dashboardsOverviewGridContainer">
                    <div class="dashboards-overview-role-tile">
                        <div class="dashboards-overview-role-tile-icon"><i class="fas fa-user-shield"></i></div>
                        <div class="dashboards-overview-role-tile-name">Admin</div>
                        <div class="dashboards-overview-role-tile-chips">
                            <span class="dashboards-overview-kpi-chip">Employees</span>
                            <span class="dashboards-overview-kpi-chip">Alerts</span>
                            <span class="dashboards-overview-kpi-chip">Approvals</span>
                        </div>
                        <button type="button" class="dashboards-overview-role-tile-preview-btn" data-dashboards-role-preview="admin">Preview</button>
                    </div>
                    <div class="dashboards-overview-role-tile">
                        <div class="dashboards-overview-role-tile-icon"><i class="fas fa-users"></i></div>
                        <div class="dashboards-overview-role-tile-name">Human Resources</div>
                        <div class="dashboards-overview-role-tile-chips">
                            <span class="dashboards-overview-kpi-chip">Attendance</span>
                            <span class="dashboards-overview-kpi-chip">Leave</span>
                            <span class="dashboards-overview-kpi-chip">Applicants</span>
                        </div>
                        <button type="button" class="dashboards-overview-role-tile-preview-btn" data-dashboards-role-preview="hr">Preview</button>
                    </div>
                    <div class="dashboards-overview-role-tile">
                        <div class="dashboards-overview-role-tile-icon"><i class="fas fa-calculator"></i></div>
                        <div class="dashboards-overview-role-tile-name">Accounting</div>
                        <div class="dashboards-overview-role-tile-chips">
                            <span class="dashboards-overview-kpi-chip">Payroll</span>
                            <span class="dashboards-overview-kpi-chip">Expenses</span>
                            <span class="dashboards-overview-kpi-chip">Reimbursements</span>
                        </div>
                        <button type="button" class="dashboards-overview-role-tile-preview-btn" data-dashboards-role-preview="accounting">Preview</button>
                    </div>
                    <div class="dashboards-overview-role-tile">
                        <div class="dashboards-overview-role-tile-icon"><i class="fas fa-truck"></i></div>
                        <div class="dashboards-overview-role-tile-name">Logistics</div>
                        <div class="dashboards-overview-role-tile-chips">
                            <span class="dashboards-overview-kpi-chip">Inventory</span>
                            <span class="dashboards-overview-kpi-chip">Equipment</span>
                            <span class="dashboards-overview-kpi-chip">Low stock</span>
                        </div>
                        <button type="button" class="dashboards-overview-role-tile-preview-btn" data-dashboards-role-preview="logistics">Preview</button>
                    </div>
                    <div class="dashboards-overview-role-tile">
                        <div class="dashboards-overview-role-tile-icon"><i class="fas fa-cogs"></i></div>
                        <div class="dashboards-overview-role-tile-name">Operations</div>
                        <div class="dashboards-overview-role-tile-chips">
                            <span class="dashboards-overview-kpi-chip">Deployed</span>
                            <span class="dashboards-overview-kpi-chip">Posts</span>
                            <span class="dashboards-overview-kpi-chip">Incidents</span>
                        </div>
                        <button type="button" class="dashboards-overview-role-tile-preview-btn" data-dashboards-role-preview="operations">Preview</button>
                    </div>
                </div>

                <div id="dashboardsOverviewPreviewContainer" class="dashboards-overview-preview-wrap">
                    <!-- Admin -->
                    <div class="dashboards-overview-preview active" id="dashboardsPreview-admin">
                        <p class="dashboards-overview-tone">System-wide visibility and control</p>
                        <div class="dashboards-overview-stats-row">
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Total users</span></div>
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Active sessions</span></div>
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Last backup</span></div>
                        </div>
                        <div class="dashboards-overview-kpi-grid">
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Total Employees</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Active Accounts</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">System Alerts</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Pending Approvals</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Roles Configured</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Audit Events (30d)</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                        </div>
                        <div class="dashboards-overview-sections-grid">
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-history"></i> Recent Activities</h3>
                                <ul class="dashboards-overview-section-list"><li>Login activity</li><li>Password change</li><li>Role assignment</li><li>Permission update</li><li>Config change</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-heartbeat"></i> System Health</h3>
                                <ul class="dashboards-overview-section-list"><li>API: OK</li><li>Database: OK</li><li>Backups: OK</li><li>Storage: OK</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-user-plus"></i> Access Requests</h3>
                                <ul class="dashboards-overview-section-list"><li>Pending role requests</li><li>Permission requests</li><li>Approval queue</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-bell"></i> Alerts Summary</h3>
                                <ul class="dashboards-overview-section-list"><li>License expiries</li><li>Security events</li><li>System notifications</li></ul>
                            </div>
                        </div>
                        <div class="dashboards-overview-chart-placeholder dashboards-overview-chart-large">Chart placeholder — Activity over time (last 30 days)</div>
                        <div class="dashboards-overview-chart-placeholder">Chart placeholder — User activity by role</div>
                    </div>

                    <!-- Human Resources -->
                    <div class="dashboards-overview-preview" id="dashboardsPreview-hr">
                        <p class="dashboards-overview-tone">People management &amp; compliance</p>
                        <div class="dashboards-overview-stats-row">
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Present today</span></div>
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">On leave</span></div>
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Licenses expiring (90d)</span></div>
                        </div>
                        <div class="dashboards-overview-kpi-grid">
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Attendance Today</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Leave Requests</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Violations Logged</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">License Watchlist</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">New Applicants</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Pending Onboarding</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                        </div>
                        <div class="dashboards-overview-sections-grid">
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-user-graduate"></i> New Applicants</h3>
                                <ul class="dashboards-overview-section-list"><li>Applicant 1 — Pending review</li><li>Applicant 2 — Interview scheduled</li><li>Applicant 3 — Documents pending</li><li>Applicant 4 — Offer sent</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-user-check"></i> Staff Status Updates</h3>
                                <ul class="dashboards-overview-section-list"><li>Transfers</li><li>Promotions</li><li>Resignations</li><li>Contract renewals</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-tasks"></i> Pending HR Actions</h3>
                                <ul class="dashboards-overview-section-list"><li>Leave approvals</li><li>Document reviews</li><li>Compliance checks</li><li>Training completion</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-id-card"></i> License &amp; Compliance</h3>
                                <ul class="dashboards-overview-section-list"><li>Expiring in 30 days</li><li>Expiring in 90 days</li><li>Renewal due</li></ul>
                            </div>
                        </div>
                        <div class="dashboards-overview-chart-placeholder dashboards-overview-chart-large">Chart placeholder — Attendance trend (last 7 days)</div>
                        <div class="dashboards-overview-chart-placeholder">Chart placeholder — Headcount by department</div>
                    </div>

                    <!-- Accounting -->
                    <div class="dashboards-overview-preview" id="dashboardsPreview-accounting">
                        <p class="dashboards-overview-tone">Financial tracking &amp; approvals</p>
                        <div class="dashboards-overview-stats-row">
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Payroll this month</span></div>
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Pending approvals</span></div>
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Reimbursements due</span></div>
                        </div>
                        <div class="dashboards-overview-kpi-grid">
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Payroll Status</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Pending Reimbursements</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Expense Reports</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Monthly Summary</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Budget vs Actual</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Vendor Payments</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                        </div>
                        <div class="dashboards-overview-sections-grid">
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-exchange-alt"></i> Recent Transactions</h3>
                                <ul class="dashboards-overview-section-list"><li>Payroll run — Jan 2025</li><li>Reimbursement batch</li><li>Expense approval</li><li>Vendor payment</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-calendar-alt"></i> Payment Calendar</h3>
                                <ul class="dashboards-overview-section-list"><li>Next payroll date</li><li>Tax filing deadlines</li><li>Vendor due dates</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-clipboard-check"></i> Approvals Queue</h3>
                                <ul class="dashboards-overview-section-list"><li>Expense approvals</li><li>Reimbursement approvals</li><li>Budget override requests</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-file-invoice-dollar"></i> Reports</h3>
                                <ul class="dashboards-overview-section-list"><li>Monthly P&amp;L</li><li>Department spend</li><li>Payroll summary</li></ul>
                            </div>
                        </div>
                        <div class="dashboards-overview-chart-placeholder dashboards-overview-chart-large">Chart placeholder — Expenses vs budget (YTD)</div>
                        <div class="dashboards-overview-chart-placeholder">Chart placeholder — Payroll by department</div>
                    </div>

                    <!-- Logistics -->
                    <div class="dashboards-overview-preview" id="dashboardsPreview-logistics">
                        <p class="dashboards-overview-tone">Assets, inventory, and readiness</p>
                        <div class="dashboards-overview-stats-row">
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Total items</span></div>
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Low stock</span></div>
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Requests open</span></div>
                        </div>
                        <div class="dashboards-overview-kpi-grid">
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Inventory Items</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Equipment Assigned</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Low Stock Alerts</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Requests Pending</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Dispatch Today</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Returns Pending</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                        </div>
                        <div class="dashboards-overview-sections-grid">
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-truck-loading"></i> Dispatch Queue</h3>
                                <ul class="dashboards-overview-section-list"><li>Site A — Equipment set</li><li>Site B — Restock</li><li>Site C — Collection</li><li>Site D — Delivery</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-warehouse"></i> Asset Movement</h3>
                                <ul class="dashboards-overview-section-list"><li>Check-in/out log</li><li>Transfer requests</li><li>Maintenance schedule</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-boxes"></i> Restock Requests</h3>
                                <ul class="dashboards-overview-section-list"><li>High priority</li><li>Routine restock</li><li>New item requests</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-clipboard-list"></i> Inventory by Category</h3>
                                <ul class="dashboards-overview-section-list"><li>Equipment</li><li>Supplies</li><li>Uniforms</li><li>Other</li></ul>
                            </div>
                        </div>
                        <div class="dashboards-overview-chart-placeholder dashboards-overview-chart-large">Chart placeholder — Inventory levels by category</div>
                        <div class="dashboards-overview-chart-placeholder">Chart placeholder — Dispatch volume (last 7 days)</div>
                    </div>

                    <!-- Operations -->
                    <div class="dashboards-overview-preview" id="dashboardsPreview-operations">
                        <p class="dashboards-overview-tone">Deployment and field execution</p>
                        <div class="dashboards-overview-stats-row">
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Deployed now</span></div>
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Active posts</span></div>
                            <div class="dashboards-overview-stat"><span class="dashboards-overview-stat-value">—</span><span class="dashboards-overview-stat-label">Incidents (7d)</span></div>
                        </div>
                        <div class="dashboards-overview-kpi-grid">
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Deployed Staff</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Posts Active</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Attendance Exceptions</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Incidents Logged</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Shift Coverage %</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                            <div class="dashboards-overview-mini-kpi"><div class="dashboards-overview-mini-kpi-label">Overtime Hours</div><div class="dashboards-overview-mini-kpi-value">—</div></div>
                        </div>
                        <div class="dashboards-overview-sections-grid">
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-map-marker-alt"></i> Assignment Summary</h3>
                                <ul class="dashboards-overview-section-list"><li>Post 1 — 3 guards</li><li>Post 2 — 2 guards</li><li>Post 3 — Coverage needed</li><li>Post 4 — Full</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-clock"></i> Shift Coverage</h3>
                                <ul class="dashboards-overview-section-list"><li>Morning shift</li><li>Afternoon shift</li><li>Night shift</li><li>Gaps &amp; replacements</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-sticky-note"></i> Operational Notes</h3>
                                <ul class="dashboards-overview-section-list"><li>Site notes</li><li>Incident follow-ups</li><li>Client feedback</li></ul>
                            </div>
                            <div class="dashboards-overview-section">
                                <h3 class="dashboards-overview-section-title"><i class="fas fa-exclamation-triangle"></i> Exceptions &amp; Alerts</h3>
                                <ul class="dashboards-overview-section-list"><li>Late check-in</li><li>No-show</li><li>Equipment issue</li><li>Post change</li></ul>
                            </div>
                        </div>
                        <div class="dashboards-overview-chart-placeholder dashboards-overview-chart-large">Chart placeholder — Deployment by post (today)</div>
                        <div class="dashboards-overview-chart-placeholder">Chart placeholder — Incidents trend (last 30 days)</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="dashboards-overview-footer">
        <a href="/" class="dashboards-overview-back-btn">Back to Login</a>
        <span class="dashboards-overview-request-link">Request Access</span>
    </footer>

    <script src="/assets/js/dashboards-overview.js"></script>
</body>
</html>
