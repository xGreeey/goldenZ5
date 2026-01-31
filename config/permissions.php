<?php

declare(strict_types=1);

/**
 * Permission definitions: module groups and default list.
 * Used for UI grouping and for syncing/seed. Actual assignments live in role_permissions.
 */

return [
    'modules_order' => [
        'Dashboard',
        'Users',
        'Roles & Permissions',
        'Settings',
        'Audit',
        'Modules',
        'Reports',
    ],
    'permissions' => [
        ['code' => 'dashboard.view.super_admin', 'module' => 'Dashboard', 'label' => 'View Super Admin Dashboard', 'description' => 'Access to main dashboard and overview', 'sort_order' => 10],
        ['code' => 'users.manage', 'module' => 'Users', 'label' => 'Manage Users', 'description' => 'Create, edit, and manage user accounts', 'sort_order' => 20],
        ['code' => 'roles.manage', 'module' => 'Roles & Permissions', 'label' => 'Manage Roles', 'description' => 'View and select roles in Roles & Permissions', 'sort_order' => 30],
        ['code' => 'permissions.manage.system', 'module' => 'Roles & Permissions', 'label' => 'Manage Permissions', 'description' => 'Assign or revoke permissions for roles', 'sort_order' => 40],
        ['code' => 'system.settings.manage', 'module' => 'Settings', 'label' => 'System Settings', 'description' => 'Access system configuration', 'sort_order' => 50],
        ['code' => 'audit.view', 'module' => 'Audit', 'label' => 'View Audit Logs', 'description' => 'View audit and security logs', 'sort_order' => 60],
        ['code' => 'modules.enable_disable', 'module' => 'Modules', 'label' => 'Enable/Disable Modules', 'description' => 'Manage module visibility', 'sort_order' => 70],
        ['code' => 'reports.view.all', 'module' => 'Reports', 'label' => 'View All Reports', 'description' => 'Access reports and analytics', 'sort_order' => 80],
    ],
];
