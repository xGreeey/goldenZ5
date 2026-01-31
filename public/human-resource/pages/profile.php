<?php
/**
 * My Profile — shared UI for HR (and all roles).
 * Content from shared partial; $base_url, $current_user set by index.
 */
$page_title = 'My Profile';
$role_permissions = isset($_SESSION['permissions']) && is_array($_SESSION['permissions'])
    ? array_slice($_SESSION['permissions'], 0, 10)
    : ['Dashboard access', 'View reports', 'Manage profile'];
require dirname(__DIR__, 2) . '/shared/profile-page-content.php';
