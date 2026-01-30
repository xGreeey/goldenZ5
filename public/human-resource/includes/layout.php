<?php
/**
 * HR Admin layout: collapsible sidebar (Untitled UI style) + main content.
 * Light/dark mode via data-theme on html.
 *
 * CONVENTION: Markup only here. No inline <script> or style= ; use .js and .css files.
 */
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
    <title><?php echo htmlspecialchars($page_title); ?> · Golden Z-5 HR</title>
    <!-- Single font: Inter (variables.css --hr-font). Icons: Font Awesome. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- STYLES: single entry hr-admin.css (variables, layout, sidebar, main, components, responsive) -->
    <link href="<?php echo htmlspecialchars($assets_url); ?>/css/hr-admin.css" rel="stylesheet">
</head>
<body class="hr-admin-body">
    <div class="hr-admin-app">

        <!-- =================================================================
             SIDEBAR (Untitled UI style: brand, nav with labels, section heading, user dropdown)
             IDs: #hr-sidebar, #sidebarToggle (collapse), #statusTrigger, #statusPopup (system status at bottom), #sidebarOverlay, #themeToggle, #userMenuTrigger, #userMenuPopup
             ================================================================= -->
        <aside id="hr-sidebar" class="hr-sidebar hr-sidebar-untitled sidebar" aria-label="Main navigation">
            <div class="hr-sidebar-inner">
                <!-- Header: brand + collapse button -->
                <div class="hr-sidebar-header">
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard" class="hr-sidebar-brand" aria-label="Golden Z-5 HR Home">
                        <span class="hr-sidebar-brand-text">Golden Z-5 HR</span>
                    </a>
                    <button type="button" class="hr-sidebar-collapse-btn" id="sidebarToggle" aria-label="Collapse sidebar" title="Collapse menu">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Primary navigation -->
                <nav class="hr-sidebar-nav" aria-label="Primary">
                    <ul class="hr-sidebar-list">
                        <li class="hr-sidebar-item">
                            <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard" class="hr-sidebar-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                                <i class="fas fa-th-large" aria-hidden="true"></i>
                                <span class="hr-sidebar-link-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="hr-sidebar-item">
                            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="hr-sidebar-link <?php echo $page === 'employees' ? 'active' : ''; ?>">
                                <i class="fas fa-users" aria-hidden="true"></i>
                                <span class="hr-sidebar-link-text">Employees</span>
                            </a>
                        </li>
                        <li class="hr-sidebar-item">
                            <a href="<?php echo htmlspecialchars($base_url); ?>?page=documents" class="hr-sidebar-link <?php echo $page === 'documents' ? 'active' : ''; ?>">
                                <i class="fas fa-folder-open" aria-hidden="true"></i>
                                <span class="hr-sidebar-link-text">Documents</span>
                            </a>
                        </li>
                        <li class="hr-sidebar-item">
                            <a href="<?php echo htmlspecialchars($base_url); ?>?page=reporting" class="hr-sidebar-link <?php echo $page === 'reporting' ? 'active' : ''; ?>">
                                <i class="fas fa-chart-line" aria-hidden="true"></i>
                                <span class="hr-sidebar-link-text">Reporting</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Section: MY TEAM (section menu toggles collapse) -->
                    <div class="hr-sidebar-section" id="hr-sidebar-section-team" aria-expanded="true">
                        <div class="hr-sidebar-section-head">
                            <span class="hr-sidebar-section-title">MY TEAM</span>
                            <button type="button" class="hr-sidebar-section-menu" id="sectionMenuBtn" aria-label="Collapse or expand MY TEAM section" aria-expanded="true" aria-controls="hr-sidebar-section-team-list"><i class="fas fa-ellipsis-v" aria-hidden="true"></i></button>
                        </div>
                        <ul class="hr-sidebar-list" id="hr-sidebar-section-team-list">
                            <li class="hr-sidebar-item">
                                <a href="<?php echo htmlspecialchars($base_url); ?>?page=tasks" class="hr-sidebar-link <?php echo $page === 'tasks' ? 'active' : ''; ?>">
                                    <i class="fas fa-tasks" aria-hidden="true"></i>
                                    <span class="hr-sidebar-link-text">My tasks</span>
                                </a>
                            </li>
                            <li class="hr-sidebar-item">
                                <a href="<?php echo htmlspecialchars($base_url); ?>?page=posts" class="hr-sidebar-link <?php echo $page === 'posts' ? 'active' : ''; ?>">
                                    <i class="fas fa-folder" aria-hidden="true"></i>
                                    <span class="hr-sidebar-link-text">Posts</span>
                                </a>
                            </li>
                            <li class="hr-sidebar-item">
                                <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees&export=csv" class="hr-sidebar-link">
                                    <i class="fas fa-file-export" aria-hidden="true"></i>
                                    <span class="hr-sidebar-link-text">Export data</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Sidebar bottom: system status (Active, Do not disturb, etc.) -->
                <div class="hr-sidebar-status-wrap">
                    <button type="button" class="hr-sidebar-status-trigger" id="statusTrigger" aria-haspopup="true" aria-expanded="false" aria-controls="statusPopup" aria-label="System status">
                        <span class="hr-sidebar-status-dot hr-sidebar-status-dot-active" id="statusDot" aria-hidden="true"></span>
                        <span class="hr-sidebar-status-label" id="statusLabel">Active</span>
                        <i class="fas fa-chevron-up hr-sidebar-status-chevron" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- User profile (opens dropdown) -->
                <div class="hr-sidebar-footer">
                    <button type="button" class="hr-sidebar-user-trigger" id="userMenuTrigger" aria-haspopup="true" aria-expanded="false" aria-controls="userMenuPopup">
                        <span class="hr-sidebar-user-avatar">
                            <i class="fas fa-user" aria-hidden="true"></i>
                        </span>
                        <span class="hr-sidebar-user-info">
                            <span class="hr-sidebar-user-name"><?php echo htmlspecialchars($current_user['name'] ?: $current_user['username']); ?></span>
                            <span class="hr-sidebar-user-meta"><?php echo htmlspecialchars($current_user['role'] ?: 'HR Admin'); ?></span>
                        </span>
                        <i class="fas fa-chevron-up hr-sidebar-user-chevron" aria-hidden="true"></i>
                    </button>

                </div>
            </div>
        </aside>

        <!-- System status dropdown (positioned above status trigger via JS) -->
        <div id="statusPopup" class="hr-sidebar-status-popup" role="menu" aria-label="System status" hidden>
            <div class="hr-sidebar-status-popup-inner">
                <button type="button" class="hr-sidebar-status-option active" data-status="active" role="menuitem">
                    <span class="hr-sidebar-status-option-dot hr-sidebar-status-dot-active"></span>
                    <span>Active</span>
                </button>
                <button type="button" class="hr-sidebar-status-option" data-status="busy" role="menuitem">
                    <span class="hr-sidebar-status-option-dot hr-sidebar-status-dot-busy"></span>
                    <span>Busy</span>
                </button>
                <button type="button" class="hr-sidebar-status-option" data-status="dnd" role="menuitem">
                    <span class="hr-sidebar-status-option-dot hr-sidebar-status-dot-dnd"><i class="fas fa-minus"></i></span>
                    <span>Do not disturb</span>
                </button>
                <button type="button" class="hr-sidebar-status-option" data-status="brb" role="menuitem">
                    <span class="hr-sidebar-status-option-dot hr-sidebar-status-dot-brb"><i class="fas fa-clock"></i></span>
                    <span>Be right back</span>
                </button>
                <button type="button" class="hr-sidebar-status-option" data-status="away" role="menuitem">
                    <span class="hr-sidebar-status-option-dot hr-sidebar-status-dot-away"><i class="fas fa-clock"></i></span>
                    <span>Appear away</span>
                </button>
                <button type="button" class="hr-sidebar-status-option" data-status="offline" role="menuitem">
                    <span class="hr-sidebar-status-option-dot hr-sidebar-status-dot-offline"></span>
                    <span>Appear offline</span>
                </button>
                <div class="hr-sidebar-status-popup-divider"></div>
                <button type="button" class="hr-sidebar-status-option hr-sidebar-status-reset" data-status="reset" role="menuitem">
                    <i class="fas fa-sync-alt" aria-hidden="true"></i>
                    <span>Reset status</span>
                </button>
            </div>
        </div>

        <!-- User dropdown popup (positioned beside sidebar via JS) -->
        <div id="userMenuPopup" class="hr-sidebar-user-popup" role="menu" aria-label="User menu" hidden>
            <div class="hr-sidebar-popup-inner">
                <div class="hr-sidebar-popup-apps">
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard" class="hr-sidebar-popup-app-item active">
                        <span class="hr-sidebar-popup-app-icon"><i class="fas fa-building"></i></span>
                        <div class="hr-sidebar-popup-app-detail">
                            <span class="hr-sidebar-popup-app-name">HR Admin</span>
                            <span class="hr-sidebar-popup-app-url"><?php echo htmlspecialchars($base_url); ?></span>
                        </div>
                        <i class="fas fa-check hr-sidebar-popup-app-check" aria-hidden="true"></i>
                    </a>
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=personal" class="hr-sidebar-popup-app-item">
                        <span class="hr-sidebar-popup-app-icon"><i class="fas fa-home"></i></span>
                        <div class="hr-sidebar-popup-app-detail">
                            <span class="hr-sidebar-popup-app-name">Main site</span>
                            <span class="hr-sidebar-popup-app-url">Personal dashboard</span>
                        </div>
                    </a>
                </div>
                <div class="hr-sidebar-popup-divider"></div>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings" class="hr-sidebar-popup-item">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    <span>My profile</span>
                </a>
                <button type="button" class="hr-sidebar-popup-item hr-sidebar-popup-item-toggle" id="accountSettingsToggle" aria-expanded="false" aria-controls="accountSettingsPopup">
                    <i class="fas fa-cog" aria-hidden="true"></i>
                    <span>Account settings</span>
                    <i class="fas fa-chevron-right hr-sidebar-popup-arrow" aria-hidden="true"></i>
                </button>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings" class="hr-sidebar-popup-item">
                    <i class="fas fa-mobile-alt" aria-hidden="true"></i>
                    <span>Device management</span>
                </a>
                <button type="button" class="hr-sidebar-popup-item hr-sidebar-popup-item-theme" id="themeToggle" aria-label="Toggle theme">
                    <i class="fas fa-moon" aria-hidden="true"></i>
                    <span>Switch theme</span>
                </button>
                <div class="hr-sidebar-popup-divider"></div>
                <a href="/?logout=1" class="hr-sidebar-popup-item hr-sidebar-popup-item-signout">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                    <span>Sign out</span>
                </a>
                <div class="hr-sidebar-popup-footer">
                    Golden Z-5 HR ©<?php echo date('Y'); ?>
                </div>
            </div>
        </div>

        <!-- Account settings popup (opens to the right of user menu) -->
        <div id="accountSettingsPopup" class="hr-sidebar-user-popup hr-sidebar-account-popup" role="menu" aria-label="Account settings" hidden>
            <div class="hr-sidebar-popup-inner">
                <div class="hr-sidebar-account-popup-header">
                    <span class="hr-sidebar-account-popup-title">Account settings</span>
                    <button type="button" class="hr-sidebar-account-popup-back" id="accountSettingsClose" aria-label="Close account settings">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings" class="hr-sidebar-popup-item">
                    <i class="fas fa-user-circle" aria-hidden="true"></i>
                    <span>Profile</span>
                </a>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings#notifications" class="hr-sidebar-popup-item">
                    <i class="fas fa-bell" aria-hidden="true"></i>
                    <span>Notifications</span>
                </a>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings#security" class="hr-sidebar-popup-item">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    <span>Security</span>
                </a>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings#preferences" class="hr-sidebar-popup-item">
                    <i class="fas fa-sliders-h" aria-hidden="true"></i>
                    <span>Preferences</span>
                </a>
            </div>
        </div>

        <!-- Overlay when sidebar open on mobile (click to close) -->
        <div class="hr-sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

        <!-- Mobile: floating menu button (opens sidebar); desktop: hidden -->
        <button type="button" class="hr-mobile-menu-fab" id="mobileMenuBtn" aria-label="Open menu">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>

        <main id="hr-main" class="hr-main main-content" role="main">
            <header class="hr-main-header" role="banner">
                <div class="hr-main-header-left">
                    <h1 class="hr-main-header-title"><?php echo htmlspecialchars($page_title); ?></h1>
                    <span class="hr-main-header-breadcrumb" aria-hidden="true"><?php echo htmlspecialchars($page_title); ?></span>
                </div>
                <div class="hr-main-header-actions">
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="hr-main-header-icon" aria-label="Search" title="Search"><i class="fas fa-search" aria-hidden="true"></i></a>
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=settings#notifications" class="hr-main-header-icon" aria-label="Notifications" title="Notifications"><i class="fas fa-bell" aria-hidden="true"></i></a>
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=personal" class="hr-main-header-icon hr-main-header-schedule" aria-label="Schedule" title="Schedule"><i class="fas fa-calendar-alt" aria-hidden="true"></i><span>Schedule</span></a>
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=employee-add" class="hr-btn hr-btn-primary hr-main-header-cta">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Add Employee
                    </a>
                </div>
            </header>
            <div class="hr-main-content">
                <?php echo $page_content; ?>
            </div>
        </main>
    </div>
    <!-- SCRIPTS: sidebar collapse, theme toggle, mobile menu, submenu expand -->
    <script src="<?php echo htmlspecialchars($assets_url); ?>/js/hr-admin.js"></script>
</body>
</html>
