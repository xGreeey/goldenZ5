# Database migrations

Run SQL files in order when setting up or upgrading the application.

## Permissions & role_permissions

**File:** `001_permissions_and_role_permissions.sql`

Creates the `permissions` and `role_permissions` tables and seeds Super Admin portal permissions. Required for the Roles & Permissions UI and permission-based dashboard visibility.

**Role names** in `role_permissions.role_name` must match your `users.role` enum exactly. The codebase expects: `super_admin`, `hr`, `admin`, `accounting`, `operation`, `logistics`, `employee`, `developer`.

```bash
# Example (MySQL client)
mysql -u user -p goldenz_hr < storage/migrations/001_permissions_and_role_permissions.sql
```

Or run the SQL contents in phpMyAdmin / your DB tool. After running, the Super Admin can assign permissions to roles from **Roles & Permissions** in the sidebar.
