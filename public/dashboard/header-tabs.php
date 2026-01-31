<?php
/**
 * Shared dashboard header + view tabs.
 *
 * EXPECTS (from caller):
 * - $base_url     : base URL for this portal (e.g. /admin, /human-resource, /super-admin)
 * - $current_user : array with keys [name, username]
 *
 * OPTIONAL:
 * - $dashboard_scope : 'admin' | 'human-resource' | 'super_admin' | 'generic'
 */

$dashboard_scope = $dashboard_scope ?? 'generic';

$view = isset($_GET['view']) ? trim((string) $_GET['view']) : 'overall';
$allowed_views = ['overall', 'human-resource', 'administration', 'operations', 'logistics', 'accounting'];
if (!in_array($view, $allowed_views, true)) {
    $view = 'overall';
}

$display_name = isset($current_user['name']) && $current_user['name'] !== ''
    ? $current_user['name']
    : ($current_user['username'] ?? 'User');
$user_initial = mb_strtoupper(mb_substr($display_name, 0, 1));

// Scope-specific greeting
switch ($dashboard_scope) {
    case 'admin':
        $greeting = 'Administration · Evaluation & Assessments';
        break;
    case 'human-resource':
    case 'humanresource':
    case 'hr':
        $greeting = 'Human Resource · Hiring';
        break;
    case 'super_admin':
    case 'super-admin':
        $greeting = 'Super Admin · System Overview';
        break;
    default:
        $greeting = 'Golden Z-5 Dashboard';
        break;
}
?>
<div class="portal-page portal-page-dashboard">
    <div class="portal-dashboard-header">
        <div class="portal-dashboard-welcome">
            <span class="portal-dashboard-avatar" aria-hidden="true"><?php echo htmlspecialchars($user_initial); ?></span>
            <div class="portal-dashboard-welcome-text">
                <span class="portal-dashboard-user-name"><?php echo htmlspecialchars($display_name); ?></span>
                <span class="portal-dashboard-greeting"><?php echo htmlspecialchars($greeting); ?></span>
            </div>
        </div>
    </div>
    <div class="portal-view-tabs-wrap">
        <nav class="portal-view-tabs" role="tablist" aria-label="Dashboard views">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=overall"
               class="portal-view-tab <?php echo $view === 'overall' ? 'active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo $view === 'overall' ? 'true' : 'false'; ?>">
                Overall
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=human-resource"
               class="portal-view-tab <?php echo $view === 'human-resource' ? 'active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo $view === 'human-resource' ? 'true' : 'false'; ?>">
                Human Resource
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=administration"
               class="portal-view-tab <?php echo $view === 'administration' ? 'active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo $view === 'administration' ? 'true' : 'false'; ?>">
                Administration
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=operations"
               class="portal-view-tab <?php echo $view === 'operations' ? 'active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo $view === 'operations' ? 'true' : 'false'; ?>">
                Operations
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=logistics"
               class="portal-view-tab <?php echo $view === 'logistics' ? 'active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo $view === 'logistics' ? 'true' : 'false'; ?>">
                Logistics
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard&view=accounting"
               class="portal-view-tab <?php echo $view === 'accounting' ? 'active' : ''; ?>"
               role="tab"
               aria-selected="<?php echo $view === 'accounting' ? 'true' : 'false'; ?>">
                Accounting
            </a>
        </nav>
    </div>
