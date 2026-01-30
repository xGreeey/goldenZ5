-- Phase 1 — Core HR Foundation
-- Run after goldenz_hr.sql. Adds employee_history, employee_documents.

USE goldenz_hr;

-- ---------------------------------------------------------------------------
-- employee_history (track changes: status, department, position, etc.)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS employee_history (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_id INT UNSIGNED NOT NULL,
  field_name VARCHAR(80) NOT NULL,
  old_value VARCHAR(500) NULL DEFAULT NULL,
  new_value VARCHAR(500) NULL DEFAULT NULL,
  changed_by INT UNSIGNED NULL DEFAULT NULL,
  changed_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY employee_history_employee (employee_id),
  KEY employee_history_changed_at (changed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- employee_documents (201 files, staff files — confidential, access-controlled)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS employee_documents (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  employee_id INT UNSIGNED NOT NULL,
  document_type VARCHAR(50) NOT NULL COMMENT '201_file, staff_file',
  category VARCHAR(100) NULL DEFAULT NULL COMMENT 'contract, id, certification, etc.',
  file_name VARCHAR(255) NOT NULL,
  file_path VARCHAR(500) NOT NULL,
  file_size INT UNSIGNED NULL DEFAULT NULL,
  mime_type VARCHAR(100) NULL DEFAULT NULL,
  uploaded_by INT UNSIGNED NULL DEFAULT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY employee_documents_employee (employee_id),
  KEY employee_documents_type (document_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
