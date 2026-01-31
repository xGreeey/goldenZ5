<?php
/**
 * My Profile — shared UI for Super Admin (and all roles).
 * Content from shared partial; $base_url, $current_user, $user_permissions set by index.
 */
$page_title = 'My Profile';
$role_permissions = isset($user_permissions) && is_array($user_permissions)
    ? array_slice($user_permissions, 0, 12)
    : ['Dashboard access', 'View reports', 'Manage profile'];
require dirname(__DIR__, 2) . '/shared/profile-page-content.php';
