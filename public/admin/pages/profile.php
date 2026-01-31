<?php
/**
 * My Profile — shared UI for Admin (and all roles). Loads full user from DB.
 */
$page_title = 'My Profile';
require_once dirname(__DIR__, 2) . '/shared/profile-actions.php';
$user_id = (int) (AuthMiddleware::user()['id'] ?? 0);
$profile_row = profile_load_user($user_id);
if ($profile_row) {
    $current_user = array_merge($current_user, $profile_row);
}
$profile_success = isset($_GET['success']) ? (string) $_GET['success'] : null;
$profile_error = isset($_GET['error']) ? (string) $_GET['error'] : null;
$profile_2fa_setup = isset($_GET['2fa_setup']) && !empty($_SESSION['profile_2fa_secret']);
$profile_2fa_secret = $_SESSION['profile_2fa_secret'] ?? '';
$profile_2fa_recovery_codes = $_SESSION['profile_2fa_recovery_codes'] ?? [];
if ($profile_2fa_setup) {
    unset($_SESSION['profile_2fa_secret'], $_SESSION['profile_2fa_recovery_codes']);
}
$role_permissions = isset($_SESSION['permissions']) && is_array($_SESSION['permissions'])
    ? array_slice($_SESSION['permissions'], 0, 10)
    : ['Dashboard access', 'View reports', 'Manage profile'];
require dirname(__DIR__, 2) . '/shared/profile-page-content.php';
