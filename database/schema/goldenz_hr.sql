-- Golden Z HR System - Database Schema
-- Run this inside the goldenz_hr database (created by Docker MYSQL_DATABASE).

USE goldenz_hr;

-- ---------------------------------------------------------------------------
-- users (login, roles, 2FA, lockout)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(80) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL DEFAULT '',
  role VARCHAR(50) NOT NULL DEFAULT 'hr',
  status VARCHAR(20) NOT NULL DEFAULT 'active',
  employee_id INT UNSIGNED NULL DEFAULT NULL,
  department VARCHAR(100) NULL DEFAULT NULL,
  remember_token VARCHAR(255) NULL DEFAULT NULL,
  failed_login_attempts INT UNSIGNED NOT NULL DEFAULT 0,
  locked_until DATETIME NULL DEFAULT NULL,
  password_changed_at DATETIME NULL DEFAULT NULL,
  two_factor_enabled TINYINT(1) NOT NULL DEFAULT 0,
  two_factor_secret VARCHAR(255) NULL DEFAULT NULL,
  last_login DATETIME NULL DEFAULT NULL,
  last_login_ip VARCHAR(45) NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY users_username_unique (username),
  KEY users_employee_id (employee_id),
  KEY users_role (role),
  KEY users_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- audit_logs (audit trail, security events)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  action VARCHAR(100) NOT NULL,
  table_name VARCHAR(80) NULL DEFAULT NULL,
  record_id INT UNSIGNED NULL DEFAULT NULL,
  old_values JSON NULL DEFAULT NULL,
  new_values JSON NULL DEFAULT NULL,
  user_id INT UNSIGNED NULL DEFAULT NULL,
  ip_address VARCHAR(45) NULL DEFAULT NULL,
  user_agent VARCHAR(500) NULL DEFAULT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY audit_logs_action (action),
  KEY audit_logs_table (table_name),
  KEY audit_logs_user_id (user_id),
  KEY audit_logs_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ---------------------------------------------------------------------------
-- employees
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS employees (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_number VARCHAR(50) NULL DEFAULT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NULL DEFAULT NULL,
  department VARCHAR(100) NULL DEFAULT NULL,
  position VARCHAR(100) NULL DEFAULT NULL,
  hire_date DATE NULL DEFAULT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'active',
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY employees_employee_number (employee_number),
  KEY employees_department (department),
  KEY employees_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
-- backup_history
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS backup_history (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  filename VARCHAR(255) NOT NULL,
  path VARCHAR(500) NULL DEFAULT NULL,
  size_bytes BIGINT UNSIGNED NULL DEFAULT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'completed',
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY backup_history_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- chat_conversations
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS chat_conversations (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id_1 INT UNSIGNED NOT NULL,
  user_id_2 INT UNSIGNED NOT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY chat_conversations_user_1 (user_id_1),
  KEY chat_conversations_user_2 (user_id_2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- chat_messages
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS chat_messages (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  conversation_id INT UNSIGNED NOT NULL,
  sender_id INT UNSIGNED NOT NULL,
  message TEXT NOT NULL,
  read_at DATETIME NULL DEFAULT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY chat_messages_conversation (conversation_id),
  KEY chat_messages_sender (sender_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- chat_typing_status
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS chat_typing_status (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  conversation_id INT UNSIGNED NOT NULL,
  is_typing TINYINT(1) NOT NULL DEFAULT 0,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY chat_typing_user_conv (user_id, conversation_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- dtr_adjustments
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS dtr_adjustments (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_id INT UNSIGNED NOT NULL,
  dtr_date DATE NOT NULL,
  time_in TIME NULL DEFAULT NULL,
  time_out TIME NULL DEFAULT NULL,
  reason VARCHAR(255) NULL DEFAULT NULL,
  approved_by INT UNSIGNED NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY dtr_adjustments_employee (employee_id),
  KEY dtr_adjustments_date (dtr_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- dtr_entries
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS dtr_entries (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_id INT UNSIGNED NOT NULL,
  dtr_date DATE NOT NULL,
  time_in DATETIME NULL DEFAULT NULL,
  time_out DATETIME NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY dtr_entries_employee (employee_id),
  KEY dtr_entries_date (dtr_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
-- dtr_summary (view)
-- ---------------------------------------------------------------------------
CREATE OR REPLACE VIEW dtr_summary AS
SELECT
  e.id AS employee_id,
  e.employee_number,
  CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
  d.dtr_date,
  d.time_in,
  d.time_out
FROM employees e
LEFT JOIN dtr_entries d ON d.employee_id = e.id;

-- ---------------------------------------------------------------------------
-- employee_alerts
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS employee_alerts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_id INT UNSIGNED NOT NULL,
  type VARCHAR(50) NOT NULL,
  message TEXT NOT NULL,
  read_at DATETIME NULL DEFAULT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY employee_alerts_employee (employee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- employee_checklist
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS employee_checklist (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_id INT UNSIGNED NOT NULL,
  item_name VARCHAR(255) NOT NULL,
  completed TINYINT(1) NOT NULL DEFAULT 0,
  completed_at DATETIME NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY employee_checklist_employee (employee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- employee_details (view)
-- ---------------------------------------------------------------------------
CREATE OR REPLACE VIEW employee_details AS
SELECT
  e.id,
  e.employee_number,
  e.first_name,
  e.last_name,
  CONCAT(e.first_name, ' ', e.last_name) AS full_name,
  e.email,
  e.department,
  e.position,
  e.hire_date,
  e.status,
  e.created_at,
  e.updated_at
FROM employees e;

-- ---------------------------------------------------------------------------
-- employee_files
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS employee_files (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_id INT UNSIGNED NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  file_path VARCHAR(500) NOT NULL,
  file_type VARCHAR(50) NULL DEFAULT NULL,
  uploaded_by INT UNSIGNED NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY employee_files_employee (employee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- employee_violations
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS employee_violations (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_id INT UNSIGNED NOT NULL,
  violation_type_id INT UNSIGNED NOT NULL,
  description TEXT NULL DEFAULT NULL,
  violation_date DATE NOT NULL,
  resolved_at DATETIME NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY employee_violations_employee (employee_id),
  KEY employee_violations_type (violation_type_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- events
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS events (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL DEFAULT NULL,
  event_date DATE NOT NULL,
  event_time TIME NULL DEFAULT NULL,
  location VARCHAR(255) NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY events_date (event_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- file_audit_logs
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS file_audit_logs (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NULL DEFAULT NULL,
  action VARCHAR(50) NOT NULL,
  file_path VARCHAR(500) NULL DEFAULT NULL,
  details JSON NULL DEFAULT NULL,
  ip_address VARCHAR(45) NULL DEFAULT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY file_audit_logs_user (user_id),
  KEY file_audit_logs_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- hr_tasks
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS hr_tasks (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL DEFAULT NULL,
  assigned_to INT UNSIGNED NULL DEFAULT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending',
  due_date DATE NULL DEFAULT NULL,
  completed_at DATETIME NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY hr_tasks_assigned (assigned_to),
  KEY hr_tasks_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- leave_balances
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS leave_balances (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_id INT UNSIGNED NOT NULL,
  leave_type VARCHAR(50) NOT NULL,
  balance_days DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  year YEAR NOT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY leave_balances_employee (employee_id),
  KEY leave_balances_year (year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ---------------------------------------------------------------------------
-- leave_balance_summary (view)
-- ---------------------------------------------------------------------------
CREATE OR REPLACE VIEW leave_balance_summary AS
SELECT
  lb.employee_id,
  e.first_name,
  e.last_name,
  lb.leave_type,
  lb.balance_days,
  lb.year
FROM leave_balances lb
JOIN employees e ON e.id = lb.employee_id;

-- ---------------------------------------------------------------------------
-- notification_status
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS notification_status (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  notification_type VARCHAR(50) NOT NULL,
  read_at DATETIME NULL DEFAULT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY notification_status_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- posts
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS posts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NULL DEFAULT NULL,
  content TEXT NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY posts_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- post_statistics (view)
-- ---------------------------------------------------------------------------
CREATE OR REPLACE VIEW post_statistics AS
SELECT
  p.id AS post_id,
  p.user_id,
  p.created_at,
  COUNT(DISTINCT p.id) AS post_count
FROM posts p
GROUP BY p.id, p.user_id, p.created_at;

-- ---------------------------------------------------------------------------
-- security_settings
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS security_settings (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  setting_key VARCHAR(80) NOT NULL,
  setting_value TEXT NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY security_settings_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- support_tickets
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS support_tickets (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  subject VARCHAR(255) NOT NULL,
  description TEXT NULL DEFAULT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'open',
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY support_tickets_user (user_id),
  KEY support_tickets_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
-- ticket_replies
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS ticket_replies (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ticket_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY ticket_replies_ticket (ticket_id),
  KEY ticket_replies_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
-- time_off_requests
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS time_off_requests (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_id INT UNSIGNED NOT NULL,
  leave_type VARCHAR(50) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  reason TEXT NULL DEFAULT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending',
  approved_by INT UNSIGNED NULL DEFAULT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY time_off_requests_employee (employee_id),
  KEY time_off_requests_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- violation_types
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS violation_types (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  description TEXT NULL DEFAULT NULL,
  severity VARCHAR(20) NOT NULL DEFAULT 'medium',
  created_at DATETIME NULL DEFAULT NULL,
  updated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY violation_types_severity (severity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Seed: default admin user (username: admin, password: password) - change after first login
-- ---------------------------------------------------------------------------
INSERT IGNORE INTO users (id, username, password_hash, name, role, status, password_changed_at, created_at, updated_at)
VALUES (
  1,
  'admin',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'System Administrator',
  'super_admin',
  'active',
  NOW(),
  NOW(),
  NOW()
);
