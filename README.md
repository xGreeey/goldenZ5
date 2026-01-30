# Golden Z-5 HR Management System

Human Resources Management System for **Golden Z-5 Security and Investigation Agency, Inc.** — licensed by PNP-CSG-SAGSD and registered with SEC.

## Overview

A PHP-based web application for workforce administration, employee records, documents, posts & assignments, reporting, and role-based dashboards. Includes authentication (login, remember-me, optional 2FA), session management, audit logging, and automated database backups.

## Features

- **Authentication** — Login, logout, remember-me, optional two-factor authentication, first-time password change
- **Role-based access (RBA)** — Redirects to the correct portal by `users.role` (super_admin, developer, hr, hr_admin, admin, accounting, operation, logistics, employee)
- **Human Resource portal** — Dashboard, employees, documents, reporting, posts, tasks, settings
- **Security** — CSRF protection, secure sessions, account lockout, security event logging
- **Database** — MySQL with PDO, schema in `database/schema/`
- **Backups** — Cron-driven backups to MinIO (see `cron/README.md`)

## Tech Stack

- **Backend:** PHP 7.4+ (strict types, PDO)
- **Frontend:** HTML5, CSS3, JavaScript (Bootstrap 5, Font Awesome)
- **Database:** MySQL / MariaDB
- **Config:** `.env` for DB and app settings; Docker-friendly

## Project Structure

```
.
├── app/
│   ├── middleware/               # Reusable middleware (Session, Auth, Role, CSRF, RateLimit)
│   │   ├── SessionMiddleware.php
│   │   ├── AuthMiddleware.php
│   │   ├── RoleMiddleware.php
│   │   ├── CsrfMiddleware.php
│   │   └── RateLimitMiddleware.php
│   └── services/
│       └── storage.php          # Storage / file helpers, storage_resolve_document_by_id()
├── bootstrap/
│   └── app.php                 # Load .env into $_ENV
├── config/
│   ├── database.php            # DB connection, env(), get_db_connection(), db_* helpers
│   └── session.php             # Session config (used by public/index.php)
├── cron/
│   ├── backup-to-minio.php     # Scheduled DB backup to MinIO
│   └── README.md               # Backup docs
├── database/
│   ├── schema/
│   │   ├── goldenz_hr.sql      # Main schema
│   │   └── phase1_hr.sql       # Phase 1 migrations
│   └── README.md               # DB setup instructions
├── includes/
│   └── security.php            # CSRF, csrf_field(), csrf_validate(), log_security_event(), etc.
├── public/                     # Web document root
│   ├── .htaccess               # Rewrite rules (human-resource, hr-admin, super-admin)
│   ├── index.php               # Login & auth flow (role-based redirect)
│   ├── forgot-password.php
│   ├── assets/                 # Login page CSS, JS, images
│   └── human-resource/         # HR portal (all HR roles)
│       ├── index.php           # Entry: ?page=dashboard|employees|...
│       ├── document-download.php
│       ├── includes/
│       │   └── layout.php      # Sidebar + main layout
│       ├── pages/              # Dashboard, employees, documents, reporting, posts, tasks, settings
│       └── assets/             # HR portal CSS & JS
├── storage/
│   ├── backups/                # Local backup output (optional)
│   ├── cache/                  # Rate limit data (ratelimit.json)
│   ├── logs/                   # Application, backup, security.log
│   └── sessions/               # PHP session files
├── .gitignore
├── LICENSE                     # EULA (Golden Z-5)
└── README.md                   # This file
```

## Role-Based Routing (after login)

| Role           | Redirect                    |
|----------------|-----------------------------|
| `super_admin`  | `/super-admin/dashboard`    |
| `developer`    | `/developer/dashboard`     |
| `hr`           | `/human-resource/`          |
| `hr_admin`     | `/human-resource/`          |
| `admin`        | `/human-resource/`          |
| `accounting`   | `/human-resource/`          |
| `operation`    | `/human-resource/`          |
| `logistics`    | `/human-resource/`          |
| `employee`     | `/human-resource/`          |

## Getting Started

### Requirements

- PHP 7.4+ with extensions: `pdo_mysql`, `mbstring`, `openssl`, `json`, `session`
- MySQL 5.7+ / MariaDB
- Web server (Apache with `mod_rewrite` or nginx with equivalent rules)

### Configuration

1. Copy environment config (if you have a sample):
   ```bash
   cp .env.example .env
   ```
2. Set in `.env` (or environment):
   - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - `APP_KEY` (optional; used for remember-me encryption)
3. Point the document root to `public/`.
4. Apply the database schema (see `database/README.md`).

### Database

- Schema: `database/schema/goldenz_hr.sql`
- Default login (after applying schema): see `database/README.md` (e.g. `admin` / `password` — change in production).

### Docker (if used)

- DB is often initialized via `mysql-init.sql` in the project root.
- Cron backup: see `cron/README.md` for backup-to-MinIO setup.

## Middleware & Security

### Protecting forms (CSRF)

Include the CSRF token in every form that submits via POST/PUT/PATCH/DELETE:

```php
<?= csrf_field() ?>
```

Or output a hidden input manually: `name="csrf_token"` or `name="_csrf"` with value from `csrf_token()`. Validate in the handler with `csrf_validate()`; on failure call `CsrfMiddleware::reject()` or return 419/error.

### Securing new endpoints

Run middleware in this order for protected routes:

1. **Session** — `SessionMiddleware::handle()` (starts session from config, enforces idle/absolute timeout).
2. **Auth** — `AuthMiddleware::check()` (redirects or 401 if not logged in); use `AuthMiddleware::user()` for current user.
3. **Role** — `RoleMiddleware::requireRole($roles)` (string or array; case-insensitive; 403 or "Access denied" on failure).
4. **CSRF** — For POST/PUT/PATCH/DELETE, call `CsrfMiddleware::verify()` and `CsrfMiddleware::reject()` if false.

Example (protected HR page):

```php
require_once $appRoot . '/app/middleware/SessionMiddleware.php';
require_once $appRoot . '/app/middleware/AuthMiddleware.php';
require_once $appRoot . '/app/middleware/RoleMiddleware.php';
require_once $appRoot . '/app/middleware/CsrfMiddleware.php';
SessionMiddleware::handle();
AuthMiddleware::check();
RoleMiddleware::requireRole(['hr_admin', 'hr', 'super_admin']);
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !CsrfMiddleware::verify()) {
    CsrfMiddleware::reject();
}
```

Login throttling is applied in `public/index.php` via `RateLimitMiddleware::checkLogin($username)`, `recordFail($username)`, and `clear($username)` on success.

## Human Resource Portal

- **URL:** `/human-resource/` or `/human-resource/dashboard`
- **Allowed roles:** super_admin, hr_admin, hr, admin, accounting, operation, logistics, employee
- **Pages:** Dashboard, Employees, Documents, Reporting, Posts, Tasks, Settings, Personal
- **Conventions:** See `public/human-resource/CONVENTION.md` (JS in `assets/js/`, CSS in `assets/css/`, no inline scripts/styles).

## License

See [LICENSE](LICENSE). Copyright © 2026 Michaella Obona, Christian Amor, John Aldrin Inocencio. Licensed to Golden Z-5 Security and Intelligence under the project EULA.
