<?php

declare(strict_types=1);

/**
 * Permission helpers: load/save role permissions. Requires config/database.php and DB tables.
 */

if (!function_exists('permissions_get_for_role')) {
    /**
     * Get permission codes for a role from role_permissions.
     * @return string[] Permission codes
     */
    function permissions_get_for_role(string $role): array
    {
        $rows = db_fetch_all(
            'SELECT p.code FROM role_permissions rp
             JOIN permissions p ON p.id = rp.permission_id
             WHERE rp.role_name = ?
             ORDER BY p.module, p.sort_order, p.id',
            [$role]
        );
        return array_column($rows, 'code');
    }
}

if (!function_exists('permissions_get_all_grouped')) {
    /**
     * Get all permissions from DB grouped by module (for Roles UI matrix).
     * @return array<string, array<int, array{id: int, code: string, module: string, label: string, description: string|null}>>
     */
    function permissions_get_all_grouped(): array
    {
        $rows = db_fetch_all('SELECT id, code, module, label, description, sort_order FROM permissions ORDER BY sort_order, id');
        $grouped = [];
        foreach ($rows as $row) {
            $module = $row['module'];
            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            $grouped[$module][] = [
                'id' => (int) $row['id'],
                'code' => $row['code'],
                'module' => $row['module'],
                'label' => $row['label'],
                'description' => $row['description'],
            ];
        }
        return $grouped;
    }
}

if (!function_exists('permissions_update_for_role')) {
    /**
     * Replace all permissions for a role with the given permission IDs.
     * @param string $role Role name (users.role enum value)
     * @param int[] $permissionIds Permission IDs to assign
     */
    function permissions_update_for_role(string $role, array $permissionIds): void
    {
        $pdo = get_db_connection();
        $pdo->beginTransaction();
        try {
            db_execute('DELETE FROM role_permissions WHERE role_name = ?', [$role]);
            foreach ($permissionIds as $id) {
                db_execute('INSERT INTO role_permissions (role_name, permission_id) VALUES (?, ?)', [$role, $id]);
            }
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}

if (!function_exists('permissions_user_can')) {
    /**
     * Check if current user (by role in session) has a permission.
     * Uses $_SESSION['user_role'] and DB role_permissions. Super_admin can bypass if desired.
     */
    function permissions_user_can(string $permission): bool
    {
        $role = $_SESSION['user_role'] ?? '';
        if ($role === '') {
            return false;
        }
        if ($role === 'super_admin') {
            return true; // super_admin sees everything; optional: check DB for granular SA permissions
        }
        $codes = permissions_get_for_role($role);
        return in_array($permission, $codes, true);
    }
}

if (!function_exists('permissions_available_roles')) {
    /**
     * List of role names (matches users.role enum) for Roles UI.
     * @return string[]
     */
    function permissions_available_roles(): array
    {
        return [
            'super_admin',
            'hr',
            'admin',
            'accounting',
            'operation',
            'logistics',
            'employee',
            'developer',
        ];
    }
}
