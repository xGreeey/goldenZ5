<?php
/**
 * Human Resource layout: collapsible sidebar (Untitled UI style) + main content.
 * Light/dark mode via data-theme on html.
 *
 * CONVENTION: Markup only here. No inline <script> or style= ; use .js and .css files.
 */
// Ensure csrf_token() is available
if (!function_exists('csrf_token')) {
    $appRoot = dirname(__DIR__, 2);
    require_once $appRoot . '/includes/security.php';
}
if (!isset($page_content)) {
    $page_content = '';
}
if (!isset($page)) {
    $page = 'dashboard';
}
if (!isset($base_url)) {
    $base_url = '/human-resource';
}
if (!isset($assets_url)) {
    $assets_url = $base_url . '/assets';
}
if (!isset($current_user) || !is_array($current_user)) {
    $current_user = [
        'id' => null,
        'username' => '',
        'name' => '',
        'role' => '',
        'department' => null,
    ];
}
$page_title = $page_title ?? ucfirst(str_replace(['-', '_'], ' ', $page));
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <!-- JS: theme init (head) — all script logic in .js files only -->
    <script src="<?php echo htmlspecialchars($assets_url); ?>/js/theme-init.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($page_title); ?> · Human Resource · Golden Z-5</title>
    <!-- Single font: Inter (variables.css --hr-font). Icons: Font Awesome. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Favicon: circular SVG with embedded logo -->
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.php">
    <link rel="icon" type="image/png" href="/assets/images/logo.png">
    <link rel="apple-touch-icon" href="/assets/images/logo.png">
    <!-- STYLES: single entry portal.css (variables, layout, sidebar, main, components, responsive) -->
    <link href="<?php echo htmlspecialchars($assets_url); ?>/css/portal.css" rel="stylesheet">
    <?php if (isset($page) && $page === 'profile'): ?>
    <link href="/assets/css/profile.css" rel="stylesheet">
    <?php endif; ?>
</head>
<body class="portal-body">
    <div class="portal-app">

        <!-- =================================================================
             SIDEBAR (Untitled UI style: brand, nav with labels, section heading, user dropdown)
             IDs: #portal-sidebar, #sidebarToggle (collapse), #statusTrigger, #statusPopup (system status at bottom), #sidebarOverlay, #themeToggle, #userMenuTrigger, #userMenuPopup
             ================================================================= -->
        <aside id="portal-sidebar" class="portal-sidebar portal-sidebar-untitled sidebar" aria-label="Main navigation">
            <div class="portal-sidebar-inner">
                <!-- Header: brand + collapse button -->
                <div class="portal-sidebar-header">
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard" class="portal-sidebar-brand" aria-label="Human Resource — Hiring">
                        <span class="portal-sidebar-brand-text">Human Resource</span>
                    </a>
                    <button type="button" class="portal-sidebar-collapse-btn" id="sidebarToggle" aria-label="Collapse sidebar" title="Collapse menu">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Primary navigation -->
                <nav class="portal-sidebar-nav" aria-label="Primary">
                    <ul class="portal-sidebar-list">
                        <li class="portal-sidebar-item">
                            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard" class="portal-sidebar-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                                <i class="fas fa-th-large" aria-hidden="true"></i>
                                <span class="portal-sidebar-link-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="portal-sidebar-item">
                            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="portal-sidebar-link <?php echo $page === 'employees' ? 'active' : ''; ?>">
                                <i class="fas fa-users" aria-hidden="true"></i>
                                <span class="portal-sidebar-link-text">Employees</span>
                            </a>
                        </li>
                        <li class="portal-sidebar-item">
                            <a href="<?php echo htmlspecialchars($base_url); ?>?page=documents" class="portal-sidebar-link <?php echo $page === 'documents' ? 'active' : ''; ?>">
                                <i class="fas fa-folder-open" aria-hidden="true"></i>
                                <span class="portal-sidebar-link-text">Documents</span>
                            </a>
                        </li>
                        <li class="portal-sidebar-item">
                            <a href="<?php echo htmlspecialchars($base_url); ?>?page=reporting" class="portal-sidebar-link <?php echo $page === 'reporting' ? 'active' : ''; ?>">
                                <i class="fas fa-chart-line" aria-hidden="true"></i>
                                <span class="portal-sidebar-link-text">Reporting</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Section: MY TEAM (section menu toggles collapse) -->
                    <div class="portal-sidebar-section" id="portal-sidebar-section-team" aria-expanded="true">
                        <div class="portal-sidebar-section-head">
                            <span class="portal-sidebar-section-title">MY TEAM</span>
                            <button type="button" class="portal-sidebar-section-menu" id="sectionMenuBtn" aria-label="Collapse or expand MY TEAM section" aria-expanded="true" aria-controls="portal-sidebar-section-team-list"><i class="fas fa-ellipsis-v" aria-hidden="true"></i></button>
                        </div>
                        <ul class="portal-sidebar-list" id="portal-sidebar-section-team-list">
                            <li class="portal-sidebar-item">
                                <a href="<?php echo htmlspecialchars($base_url); ?>?page=tasks" class="portal-sidebar-link <?php echo $page === 'tasks' ? 'active' : ''; ?>">
                                    <i class="fas fa-tasks" aria-hidden="true"></i>
                                    <span class="portal-sidebar-link-text">My tasks</span>
                                </a>
                            </li>
                            <li class="portal-sidebar-item">
                                <a href="<?php echo htmlspecialchars($base_url); ?>?page=posts" class="portal-sidebar-link <?php echo $page === 'posts' ? 'active' : ''; ?>">
                                    <i class="fas fa-folder" aria-hidden="true"></i>
                                    <span class="portal-sidebar-link-text">Posts</span>
                                </a>
                            </li>
                            <li class="portal-sidebar-item">
                                <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees&export=csv" class="portal-sidebar-link">
                                    <i class="fas fa-file-export" aria-hidden="true"></i>
                                    <span class="portal-sidebar-link-text">Export data</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Sidebar bottom: system status (Active, Do not disturb, etc.) -->
                <div class="portal-sidebar-status-wrap">
                    <button type="button" class="portal-sidebar-status-trigger" id="statusTrigger" aria-haspopup="true" aria-expanded="false" aria-controls="statusPopup" aria-label="System status">
                        <span class="portal-sidebar-status-dot portal-sidebar-status-dot-active" id="statusDot" aria-hidden="true"></span>
                        <span class="portal-sidebar-status-label" id="statusLabel">Active</span>
                        <i class="fas fa-chevron-up portal-sidebar-status-chevron" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- User profile (opens dropdown) -->
                <div class="portal-sidebar-footer">
                    <button type="button" class="portal-sidebar-user-trigger" id="userMenuTrigger" aria-haspopup="true" aria-expanded="false" aria-controls="userMenuPopup">
                        <span class="portal-sidebar-user-avatar">
                            <i class="fas fa-user" aria-hidden="true"></i>
                        </span>
                        <span class="portal-sidebar-user-info">
                            <span class="portal-sidebar-user-name"><?php echo htmlspecialchars($current_user['name'] ?: $current_user['username']); ?></span>
                            <span class="portal-sidebar-user-meta"><?php echo htmlspecialchars($current_user['role'] ?: 'HR'); ?></span>
                        </span>
                        <i class="fas fa-chevron-up portal-sidebar-user-chevron" aria-hidden="true"></i>
                    </button>

                </div>
            </div>
        </aside>

        <!-- System status dropdown (positioned above status trigger via JS) -->
        <div id="statusPopup" class="portal-sidebar-status-popup" role="menu" aria-label="System status" hidden>
            <div class="portal-sidebar-status-popup-inner">
                <button type="button" class="portal-sidebar-status-option active" data-status="active" role="menuitem">
                    <span class="portal-sidebar-status-option-dot portal-sidebar-status-dot-active"></span>
                    <span>Active</span>
                </button>
                <button type="button" class="portal-sidebar-status-option" data-status="busy" role="menuitem">
                    <span class="portal-sidebar-status-option-dot portal-sidebar-status-dot-busy"></span>
                    <span>Busy</span>
                </button>
                <button type="button" class="portal-sidebar-status-option" data-status="dnd" role="menuitem">
                    <span class="portal-sidebar-status-option-dot portal-sidebar-status-dot-dnd"><i class="fas fa-minus"></i></span>
                    <span>Do not disturb</span>
                </button>
                <button type="button" class="portal-sidebar-status-option" data-status="brb" role="menuitem">
                    <span class="portal-sidebar-status-option-dot portal-sidebar-status-dot-brb"><i class="fas fa-clock"></i></span>
                    <span>Be right back</span>
                </button>
                <button type="button" class="portal-sidebar-status-option" data-status="away" role="menuitem">
                    <span class="portal-sidebar-status-option-dot portal-sidebar-status-dot-away"><i class="fas fa-clock"></i></span>
                    <span>Appear away</span>
                </button>
                <button type="button" class="portal-sidebar-status-option" data-status="offline" role="menuitem">
                    <span class="portal-sidebar-status-option-dot portal-sidebar-status-dot-offline"></span>
                    <span>Appear offline</span>
                </button>
                <div class="portal-sidebar-status-popup-divider"></div>
                <button type="button" class="portal-sidebar-status-option portal-sidebar-status-reset" data-status="reset" role="menuitem">
                    <i class="fas fa-sync-alt" aria-hidden="true"></i>
                    <span>Reset status</span>
                </button>
            </div>
        </div>

        <!-- User dropdown popup (positioned beside sidebar via JS) -->
        <div id="userMenuPopup" class="portal-sidebar-user-popup" role="menu" aria-label="User menu" hidden>
            <div class="portal-sidebar-popup-inner">
                <div class="portal-sidebar-popup-apps">
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard" class="portal-sidebar-popup-app-item active">
                        <span class="portal-sidebar-popup-app-icon"><i class="fas fa-user-plus"></i></span>
                        <div class="portal-sidebar-popup-app-detail">
                            <span class="portal-sidebar-popup-app-name">Human Resource</span>
                            <span class="portal-sidebar-popup-app-url">Hiring</span>
                        </div>
                        <i class="fas fa-check portal-sidebar-popup-app-check" aria-hidden="true"></i>
                    </a>
                    <a href="/admin?page=dashboard" class="portal-sidebar-popup-app-item">
                        <span class="portal-sidebar-popup-app-icon"><i class="fas fa-clipboard-check"></i></span>
                        <div class="portal-sidebar-popup-app-detail">
                            <span class="portal-sidebar-popup-app-name">Admin</span>
                            <span class="portal-sidebar-popup-app-url">Administration, Evaluation &amp; Assessments</span>
                        </div>
                    </a>
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=personal" class="portal-sidebar-popup-app-item">
                        <span class="portal-sidebar-popup-app-icon"><i class="fas fa-home"></i></span>
                        <div class="portal-sidebar-popup-app-detail">
                            <span class="portal-sidebar-popup-app-name">Main site</span>
                            <span class="portal-sidebar-popup-app-url">Personal dashboard</span>
                        </div>
                    </a>
                </div>
                <div class="portal-sidebar-popup-divider"></div>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=profile" class="portal-sidebar-popup-item">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    <span>My profile</span>
                </a>
                <button type="button" class="portal-sidebar-popup-item portal-sidebar-popup-item-toggle" id="accountSettingsToggle" aria-expanded="false" aria-controls="accountSettingsPopup">
                    <i class="fas fa-cog" aria-hidden="true"></i>
                    <span>Account settings</span>
                    <i class="fas fa-chevron-right portal-sidebar-popup-arrow" aria-hidden="true"></i>
                </button>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings" class="portal-sidebar-popup-item">
                    <i class="fas fa-mobile-alt" aria-hidden="true"></i>
                    <span>Device management</span>
                </a>
                <button type="button" class="portal-sidebar-popup-item portal-sidebar-popup-item-theme" id="themeToggle" aria-label="Toggle theme">
                    <span class="portal-theme-switch" aria-hidden="true">
                        <span class="portal-theme-track">
                            <span class="portal-theme-thumb"></span>
                            <span class="portal-theme-icon portal-theme-icon-light"><i class="fas fa-sun" aria-hidden="true"></i></span>
                            <span class="portal-theme-icon portal-theme-icon-dark"><i class="fas fa-moon" aria-hidden="true"></i></span>
                        </span>
                    </span>
                    <span class="portal-theme-label">Theme</span>
                </button>
                <div class="portal-sidebar-popup-divider"></div>
                <a href="/?logout=1" class="portal-sidebar-popup-item portal-sidebar-popup-item-signout">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                    <span>Sign out</span>
                </a>
                <div class="portal-sidebar-popup-footer">
                    Golden Z-5 · Human Resource ©<?php echo date('Y'); ?>
                </div>
            </div>
        </div>

        <!-- Account settings popup (opens to the right of user menu) -->
        <div id="accountSettingsPopup" class="portal-sidebar-user-popup portal-sidebar-account-popup" role="menu" aria-label="Account settings" hidden>
            <div class="portal-sidebar-popup-inner">
                <div class="portal-sidebar-account-popup-header">
                    <span class="portal-sidebar-account-popup-title">Account settings</span>
                    <button type="button" class="portal-sidebar-account-popup-back" id="accountSettingsClose" aria-label="Close account settings">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=profile" class="portal-sidebar-popup-item">
                    <i class="fas fa-user-circle" aria-hidden="true"></i>
                    <span>Profile</span>
                </a>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings#notifications" class="portal-sidebar-popup-item">
                    <i class="fas fa-bell" aria-hidden="true"></i>
                    <span>Notifications</span>
                </a>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings#security" class="portal-sidebar-popup-item">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    <span>Security</span>
                </a>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings#preferences" class="portal-sidebar-popup-item">
                    <i class="fas fa-sliders-h" aria-hidden="true"></i>
                    <span>Preferences</span>
                </a>
            </div>
        </div>

        <!-- Overlay when sidebar open on mobile (click to close) -->
        <div class="portal-sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

        <!-- Mobile: floating menu button (opens sidebar); desktop: hidden -->
        <button type="button" class="portal-mobile-menu-fab" id="mobileMenuBtn" aria-label="Open menu">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>

        <main id="portal-main" class="portal-main main-content" role="main">
            <header class="portal-main-header" role="banner">
                <div class="portal-main-header-left">
                    <h1 class="portal-main-header-title"><?php echo htmlspecialchars($page_title); ?></h1>
                </div>
                <div class="portal-main-header-actions">
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="portal-main-header-icon" aria-label="Search" title="Search"><i class="fas fa-search" aria-hidden="true"></i></a>
                    <div class="portal-notification-wrap">
                        <button type="button" class="portal-main-header-icon portal-notification-trigger" id="notificationTrigger" aria-label="Notifications" title="Notifications" aria-haspopup="true" aria-expanded="false" aria-controls="notificationPopup">
                            <i class="fas fa-bell" aria-hidden="true"></i>
                            <span class="portal-notification-badge" id="notificationBadge" aria-hidden="true">0</span>
                        </button>
                        <div id="notificationPopup" class="portal-notification-popup" role="menu" aria-label="Notifications" hidden>
                            <div class="portal-notification-popup-header">
                                <h3 class="portal-notification-popup-title">Notifications</h3>
                                <button type="button" class="portal-notification-popup-mark-all" id="markAllRead" aria-label="Mark all as read">Mark all as read</button>
                            </div>
                            <div class="portal-notification-popup-body" id="notificationList">
                                <div class="portal-notification-empty">
                                    <i class="fas fa-bell-slash" aria-hidden="true"></i>
                                    <p>No new notifications</p>
                                </div>
                            </div>
                            <div class="portal-notification-popup-footer">
                                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings#notifications" class="portal-notification-popup-view-all">View all notifications</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="portal-main-content">
                <?php echo $page_content; ?>
            </div>
        </main>
    </div>
    <!-- SCRIPTS: sidebar collapse, theme toggle, mobile menu, submenu expand -->
    <script src="<?php echo htmlspecialchars($assets_url); ?>/js/portal.js"></script>
    <?php if (isset($page) && $page === 'profile'): ?>
    <script src="/assets/js/profile.js"></script>
    <?php endif; ?>
</body>
</html>
