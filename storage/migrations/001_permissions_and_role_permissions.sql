-- Roles & Permissions: permissions table + role_permissions junction
-- Run once to add permission-based access control. Roles = users.role enum values.

SET NAMES utf8mb4;

-- Permissions (one row per permission; module groups for UI)
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(80) NOT NULL COMMENT 'Unique permission key e.g. dashboard.view.super_admin',
  `module` varchar(50) NOT NULL COMMENT 'Group label: Dashboard, Users, etc.',
  `label` varchar(120) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_code` (`code`),
  KEY `permissions_module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Role-permission assignment (role_name must match users.role enum exactly)
-- users.role enum: super_admin, hr, admin, accounting, operation, logistics, employee, developer
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_name` varchar(32) NOT NULL,
  `permission_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_name`, `permission_id`),
  KEY `role_permissions_permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_permission_fk` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed: Super Admin portal permissions (dashboard tabs / modules)
INSERT IGNORE INTO `permissions` (`id`, `code`, `module`, `label`, `description`, `sort_order`) VALUES
(1, 'dashboard.view.super_admin', 'Dashboard', 'View Super Admin Dashboard', 'Access to main dashboard and overview', 10),
(2, 'users.manage', 'Users', 'Manage Users', 'Create, edit, and manage user accounts', 20),
(3, 'roles.manage', 'Roles & Permissions', 'Manage Roles', 'View and select roles in Roles & Permissions', 30),
(4, 'permissions.manage.system', 'Roles & Permissions', 'Manage Permissions', 'Assign or revoke permissions for roles', 40),
(5, 'system.settings.manage', 'Settings', 'System Settings', 'Access system configuration', 50),
(6, 'audit.view', 'Audit', 'View Audit Logs', 'View audit and security logs', 60),
(7, 'modules.enable_disable', 'Modules', 'Enable/Disable Modules', 'Manage module visibility', 70),
(8, 'reports.view.all', 'Reports', 'View All Reports', 'Access reports and analytics', 80);

-- Optional: assign all permissions to super_admin by default (so existing SA users see everything until customized)
INSERT IGNORE INTO `role_permissions` (`role_name`, `permission_id`)
SELECT 'super_admin', id FROM `permissions` WHERE 1=1;
