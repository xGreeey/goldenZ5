# Golden Z-5 HR Management System

Human Resources Management System for **Golden Z-5 Security and Investigation Agency, Inc.** — licensed by PNP-CSG-SAGSD and registered with SEC.

## Overview

A PHP-based web application for workforce administration, employee records, documents, posts & assignments, reporting, and role-based dashboards. Includes authentication (login, remember-me, optional 2FA), session management, audit logging, and automated database backups.

## Features

### Authentication & Security
- **Login System** — Secure login with remember-me functionality, optional two-factor authentication
- **First-Time Password Change** — Forced password change modal on first login with temporary passwords
- **Forgot Password** — Standalone forgot password page with security event logging
- **Password Requirements** — Enforced password complexity (min 8 chars, uppercase, lowercase, numbers, symbols)
- **Account Lockout** — Automatic lockout after 5 failed login attempts (30-minute lockout period)
- **CSRF Protection** — All forms protected with CSRF tokens
- **Secure Sessions** — Session management with idle/absolute timeout enforcement
- **Security Event Logging** — Comprehensive logging of login attempts, password changes, and security events
- **Audit Trail** — Complete audit logging for user actions and system changes

### Role-Based Access Control (RBA)
- **Multi-Portal System** — Separate portals for different roles:
  - `super_admin` → Super Admin Portal (`/super-admin/`)
  - `admin` → Admin Portal (`/admin/`)
  - `humanresource` → Human Resource Portal (`/human-resource/`)
  - `developer` → Developer Portal (`/developer/`)
  - Other roles → Human Resource Portal
- **Role-Based Redirects** — Automatic routing based on user role after login
- **Permission-Based Access** — Middleware-based access control for protected routes

### Super Admin Portal
- **User Management** — Create, view, and manage user accounts
- **Auto-Generated Passwords** — Secure 16-character passwords automatically generated for new users
- **Email Notifications** — Welcome emails sent automatically with credentials via PHPMailer
- **User Statistics** — Dashboard with user counts, status distribution, and recent activity
- **Form Auto-Clear** — Form fields automatically cleared after successful user creation
- **Role Assignment** — Assign roles: super_admin, admin, humanresource, accounting, operation, logistics, employee, developer
- **Status Management** — Set user status: active, inactive, suspended

### Human Resource Portal
- **Dashboard** — Overview with KPIs and statistics
- **Employee Management** — Add, edit, view employees
- **Documents** — Document management and downloads
- **Reporting** — Generate reports and analytics
- **Posts & Tasks** — Internal communication and task management
- **Settings** — Portal configuration and preferences
- **Personal** — User profile and personal settings

### Email Service
- **PHPMailer Integration** — SMTP-based email sending
- **Welcome Emails** — Automated welcome emails with credentials for new users
- **Configurable SMTP** — Environment-based SMTP configuration (.env)
- **Email Templates** — HTML email templates with plain text fallback

### Database & Infrastructure
- **MySQL/MariaDB** — PDO-based database access with prepared statements
- **Schema Management** — Database schema in `database/schema/`
- **Backups** — Cron-driven automated backups to MinIO (see `cron/README.md`)
- **Environment Configuration** — `.env` file for database and application settings

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
│       ├── EmailService.php     # PHPMailer email service (welcome emails, notifications)
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
│   ├── .htaccess               # Rewrite rules (human-resource, admin, super-admin, forgot-password)
│   ├── index.php               # Login & auth flow (role-based redirect, first-time password change)
│   ├── forgot-password/        # Forgot password functionality
│   │   └── forgot-password.php
│   ├── assets/                 # Shared assets (login, forgot password, change password)
│   │   ├── css/
│   │   │   ├── login.css
│   │   │   ├── forgot_password.css
│   │   │   └── change_password.css
│   │   ├── js/
│   │   │   ├── login.js
│   │   │   ├── forgot_password.js
│   │   │   └── change_password.js
│   │   └── images/
│   ├── super-admin/            # Super Admin Portal
│   │   ├── index.php           # Entry: ?page=dashboard|users|...
│   │   ├── includes/
│   │   │   └── layout.php      # Sidebar + main layout
│   │   ├── pages/
│   │   │   ├── dashboard.php   # Super admin dashboard
│   │   │   └── users.php       # User management (create, view users)
│   │   └── assets/             # Super admin CSS & JS
│   ├── admin/                  # Admin Portal
│   │   ├── index.php           # Entry: ?page=dashboard|employees|...
│   │   ├── document-download.php
│   │   ├── includes/
│   │   │   └── layout.php      # Sidebar + main layout
│   │   ├── pages/              # Dashboard, employees, documents, reporting, posts, tasks, settings
│   │   └── assets/             # Admin portal CSS & JS
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

| Role           | Redirect                    | Portal Access |
|----------------|-----------------------------|---------------|
| `super_admin`  | `/super-admin/dashboard`    | Super Admin Portal |
| `developer`    | `/developer/dashboard`      | Developer Portal |
| `admin`        | `/admin/dashboard`          | Admin Portal |
| `humanresource`| `/human-resource/dashboard` | Human Resource Portal |
| `accounting`   | `/human-resource/dashboard` | Human Resource Portal |
| `operation`    | `/human-resource/dashboard` | Human Resource Portal |
| `logistics`    | `/human-resource/dashboard` | Human Resource Portal |
| `employee`     | `/human-resource/dashboard` | Human Resource Portal |

**Note:** After first-time login with a temporary password, users are required to change their password before accessing their portal.

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
   - **Database:**
     - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - **Application:**
     - `APP_KEY` (optional; used for remember-me encryption)
   - **Email Service (for user creation notifications):**
     - `SMTP_HOST` (default: smtp.gmail.com)
     - `SMTP_PORT` (default: 587)
     - `SMTP_ENCRYPTION` (default: tls)
     - `SMTP_USERNAME` (your SMTP username)
     - `SMTP_PASSWORD` (your SMTP password/app password)
     - `MAIL_FROM_ADDRESS` (sender email address)
     - `MAIL_FROM_NAME` (default: Golden Z-5 HR System)
3. Point the document root to `public/`.
4. Apply the database schema (see `database/README.md`).
5. Install PHPMailer (if not already installed):
   ```bash
   composer require phpmailer/phpmailer
   ```

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

## Portals

### Super Admin Portal
- **URL:** `/super-admin/` or `/super-admin/dashboard`
- **Allowed roles:** `super_admin` only
- **Features:**
  - User Management (create, view users)
  - System Dashboard (KPIs, system health, activity feed)
  - Auto-generated secure passwords for new users
  - Email notifications via PHPMailer
  - User statistics and analytics
- **Pages:** Dashboard, Users

### Admin Portal
- **URL:** `/admin/` or `/admin/dashboard`
- **Allowed roles:** `admin` only
- **Pages:** Dashboard, Employees, Documents, Reporting, Posts, Tasks, Settings, Personal

### Human Resource Portal
- **URL:** `/human-resource/` or `/human-resource/dashboard`
- **Allowed roles:** super_admin, humanresource, admin, accounting, operation, logistics, employee
- **Pages:** Dashboard, Employees, Documents, Reporting, Posts, Tasks, Settings, Personal
- **Conventions:** See `public/human-resource/CONVENTION.md` (JS in `assets/js/`, CSS in `assets/css/`, no inline scripts/styles).

### Developer Portal
- **URL:** `/developer/` or `/developer/dashboard`
- **Allowed roles:** `developer` only
- **Pages:** Dashboard (customizable)

## Authentication Flow

### First-Time Login
1. User logs in with temporary password
2. System detects `password_changed_at` is NULL
3. Password change modal is displayed (cannot be dismissed)
4. User must set a new password meeting requirements:
   - Minimum 8 characters
   - At least one uppercase letter
   - At least one lowercase letter
   - At least one number
   - At least one symbol
5. After password change, user is automatically logged in and redirected to their portal

### Forgot Password
- **URL:** `/forgot-password`
- Users can request password reset assistance
- Security event logged for audit purposes
- Generic message displayed (does not reveal if account exists)

### User Creation (Super Admin)
- Super admin creates new users with auto-generated passwords
- Secure 16-character passwords generated automatically
- Welcome email sent via PHPMailer with credentials
- Form fields automatically cleared after successful creation
- User must change password on first login

## Recent Updates

### User Management Enhancements
- **Auto-Clear Forms:** Form fields automatically clear after successful user creation
- **Email Integration:** PHPMailer integration for automated welcome emails
- **Password Generation:** Secure 16-character password generation for new users
- **User Statistics:** Real-time user statistics dashboard

### Security Improvements
- **First-Time Password Change:** Forced password change on first login with temporary passwords
- **Password Requirements:** Enhanced password complexity requirements
- **Forgot Password:** Standalone forgot password page with security logging
- **Account Lockout:** Improved account lockout mechanism (5 attempts = 30 min lockout)

### Portal Enhancements
- **Super Admin Portal:** Complete user management system
- **Multi-Portal Architecture:** Separate portals for super_admin, admin, humanresource, and developer roles
- **Enhanced Dashboards:** KPI cards, system health monitoring, activity feeds

## License

See [LICENSE](LICENSE). Copyright © 2026 Michaella Obona, Christian Amor, John Aldrin Inocencio. Licensed to Golden Z-5 Security and Intelligence under the project EULA.
