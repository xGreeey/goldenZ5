<?php
/**
 * Phase 1 — Documents: 201 files, staff files. List, filter, upload.
 * Access: HR/Admin only (enforced by index). Audit: upload/download logged.
 */
$page_title = 'Documents';

$documents_dir = dirname(__DIR__, 3) . '/storage/documents';
if (!is_dir($documents_dir)) {
    @mkdir($documents_dir, 0755, true);
}

$employee_id_filter = isset($_GET['employee_id']) ? (int) $_GET['employee_id'] : 0;
$type_filter = isset($_GET['type']) ? trim($_GET['type']) : '';
if ($type_filter !== '' && !in_array($type_filter, ['201_file', 'staff_file'], true)) {
    $type_filter = '';
}

$documents = [];
$employees_list = [];
$upload_error = '';
$upload_success = false;
$delete_success = false;

// Handle delete (POST) — audit logged
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_delete'], $_POST['document_id'])) {
    $del_id = (int) $_POST['document_id'];
    if ($del_id > 0) {
        try {
            $pdo = get_db_connection();
            $pdo->query("SELECT 1 FROM employee_documents LIMIT 1");
            $stmt = $pdo->prepare("SELECT id, employee_id, file_name, file_path FROM employee_documents WHERE id = ?");
            $stmt->execute([$del_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $storageRoot = dirname(__DIR__, 3) . '/storage';
                $full_path = $storageRoot . '/' . $row['file_path'];
                if (is_file($full_path)) {
                    @unlink($full_path);
                }
                $pdo->prepare("DELETE FROM employee_documents WHERE id = ?")->execute([$del_id]);
                if (function_exists('log_audit_event')) {
                    log_audit_event('DELETE', 'employee_documents', $del_id, ['employee_id' => $row['employee_id'], 'file_name' => $row['file_name']], null, $_SESSION['user_id'] ?? null);
                }
                $redirect = $base_url . '?page=documents';
                if (!empty($_POST['employee_id'])) {
                    $redirect .= '&employee_id=' . (int) $_POST['employee_id'];
                }
                if (!empty($_POST['type'])) {
                    $redirect .= '&type=' . rawurlencode($_POST['type']);
                }
                $redirect .= '&deleted=1';
                header('Location: ' . $redirect);
                exit;
            }
        } catch (Throwable $e) {
            error_log('documents delete: ' . $e->getMessage());
        }
    }
}

if (isset($_GET['deleted']) && $_GET['deleted'] === '1') {
    $delete_success = true;
}

try {
    $pdo = get_db_connection();
    $employees_stmt = $pdo->query("SELECT id, employee_number, first_name, last_name FROM employees ORDER BY last_name, first_name");
    $employees_list = $employees_stmt->fetchAll(PDO::FETCH_ASSOC);

    $has_table = false;
    try {
        $pdo->query("SELECT 1 FROM employee_documents LIMIT 1");
        $has_table = true;
    } catch (Throwable $e) {
        // table missing
    }

    if ($has_table) {
        $sql = "SELECT d.id, d.employee_id, d.document_type, d.category, d.file_name, d.file_path, d.file_size, d.created_at, e.first_name, e.last_name, e.employee_number FROM employee_documents d JOIN employees e ON e.id = d.employee_id WHERE 1=1";
        $params = [];
        if ($employee_id_filter > 0) {
            $sql .= " AND d.employee_id = ?";
            $params[] = $employee_id_filter;
        }
        if ($type_filter !== '') {
            $sql .= " AND d.document_type = ?";
            $params[] = $type_filter;
        }
        $sql .= " ORDER BY d.created_at DESC LIMIT 200";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Throwable $e) {
    // ignore
}

// Handle upload (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_upload'])) {
    $up_employee_id = isset($_POST['employee_id']) ? (int) $_POST['employee_id'] : 0;
    $up_type = trim($_POST['document_type'] ?? '');
    $up_category = trim($_POST['category'] ?? '');
    if (!in_array($up_type, ['201_file', 'staff_file'], true)) {
        $up_type = 'staff_file';
    }
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $max_size = 10 * 1024 * 1024; // 10MB
    if ($up_employee_id <= 0) {
        $upload_error = 'Select an employee.';
    } elseif (!isset($_FILES['document_file']) || $_FILES['document_file']['error'] !== UPLOAD_ERR_OK) {
        $upload_error = 'Select a file to upload.';
    } elseif ($_FILES['document_file']['size'] > $max_size) {
        $upload_error = 'File too large (max 10MB).';
    } else {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['document_file']['tmp_name']);
        if (!in_array($mime, $allowed_types, true)) {
            $upload_error = 'File type not allowed (PDF, DOC, DOCX, JPG, PNG only).';
        } else {
            try {
                $pdo = get_db_connection();
                $emp_dir = $documents_dir . '/employee_' . $up_employee_id;
                if (!is_dir($emp_dir)) {
                    mkdir($emp_dir, 0755, true);
                }
                $ext = pathinfo($_FILES['document_file']['name'], PATHINFO_EXTENSION) ?: 'bin';
                $safe_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['document_file']['name']));
                $unique = uniqid('', true);
                $filename = $unique . '_' . $safe_name;
                $rel_path = 'documents/employee_' . $up_employee_id . '/' . $filename;
                $full_path = $documents_dir . '/employee_' . $up_employee_id . '/' . $filename;
                if (move_uploaded_file($_FILES['document_file']['tmp_name'], $full_path)) {
                    $stmt = $pdo->prepare("INSERT INTO employee_documents (employee_id, document_type, category, file_name, file_path, file_size, mime_type, uploaded_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                    $stmt->execute([
                        $up_employee_id,
                        $up_type,
                        $up_category ?: null,
                        $safe_name,
                        $rel_path,
                        (int) $_FILES['document_file']['size'],
                        $mime,
                        $_SESSION['user_id'] ?? null,
                    ]);
                    if (function_exists('log_audit_event')) {
                        log_audit_event('UPLOAD', 'employee_documents', (int) $pdo->lastInsertId(), null, ['employee_id' => $up_employee_id, 'file_name' => $safe_name], $_SESSION['user_id'] ?? null);
                    }
                    $upload_success = true;
                    $employee_id_filter = $up_employee_id;
                    $sql = "SELECT d.id, d.employee_id, d.document_type, d.category, d.file_name, d.file_path, d.file_size, d.created_at, e.first_name, e.last_name, e.employee_number FROM employee_documents d JOIN employees e ON e.id = d.employee_id WHERE d.employee_id = ? ORDER BY d.created_at DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$up_employee_id]);
                    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $upload_error = 'Could not save file.';
                }
            } catch (Throwable $e) {
                $upload_error = 'Upload failed. Run Phase 1 schema (phase1_hr.sql) if you have not.';
                error_log('documents upload: ' . $e->getMessage());
            }
        }
    }
}
?>
<div class="portal-page portal-page-documents">
    <header class="portal-page-header">
        <nav class="portal-breadcrumb" aria-label="Breadcrumb">
            <ol class="portal-breadcrumb-list">
                <li class="portal-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a></li>
                <li class="portal-breadcrumb-item portal-breadcrumb-current" aria-current="page">Documents</li>
            </ol>
        </nav>
        <div class="portal-page-header-actions">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="portal-btn portal-btn-ghost">Employees</a>
        </div>
    </header>

    <section class="portal-section">
        <h2 class="portal-section-title">Upload document</h2>
        <?php if ($upload_success): ?>
            <div class="portal-alert portal-alert-success">
                <div class="portal-alert-icon" aria-hidden="true"><i class="fas fa-check"></i></div>
                <div class="portal-alert-body">
                    <p class="portal-alert-message">Document uploaded successfully.</p>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($delete_success): ?>
            <div class="portal-alert portal-alert-success">
                <div class="portal-alert-icon" aria-hidden="true"><i class="fas fa-check"></i></div>
                <div class="portal-alert-body">
                    <p class="portal-alert-message">Document deleted.</p>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($upload_error !== ''): ?>
            <div class="portal-alert portal-alert-error">
                <div class="portal-alert-icon" aria-hidden="true">!</div>
                <div class="portal-alert-body">
                    <p class="portal-alert-message"><?php echo htmlspecialchars($upload_error); ?></p>
                </div>
            </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" action="" class="portal-form">
            <input type="hidden" name="document_upload" value="1">
            <div class="portal-form-grid">
                <div class="portal-form-group">
                    <label for="employee_id">Employee <span class="portal-required">*</span></label>
                    <select id="employee_id" name="employee_id" class="portal-select" required>
                        <option value="">Select employee</option>
                        <?php foreach ($employees_list as $e): ?>
                            <option value="<?php echo (int)$e['id']; ?>" <?php echo $employee_id_filter === (int)$e['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($e['last_name'] . ', ' . $e['first_name'] . ($e['employee_number'] ? ' (' . $e['employee_number'] . ')' : '')); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="portal-form-group">
                    <label for="document_type">Type</label>
                    <select id="document_type" name="document_type" class="portal-select">
                        <option value="staff_file" <?php echo ($_POST['document_type'] ?? '') === 'staff_file' ? 'selected' : ''; ?>>Staff file</option>
                        <option value="201_file" <?php echo ($_POST['document_type'] ?? '') === '201_file' ? 'selected' : ''; ?>>201 file (confidential)</option>
                    </select>
                </div>
                <div class="portal-form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" class="portal-input" placeholder="e.g. contract, ID, certification" value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
                </div>
                <div class="portal-form-group">
                    <label for="document_file">File <span class="portal-required">*</span></label>
                    <input type="file" id="document_file" name="document_file" class="portal-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                    <span class="portal-text-muted">PDF, DOC, DOCX, JPG, PNG. Max 10MB.</span>
                </div>
            </div>
            <div class="portal-form-actions">
                <button type="submit" class="portal-btn portal-btn-primary">Upload</button>
            </div>
        </form>
    </section>

    <section class="portal-section">
        <h2 class="portal-section-title">Documents</h2>
        <form method="get" action="<?php echo htmlspecialchars($base_url); ?>" class="portal-filters">
            <input type="hidden" name="page" value="documents">
            <select name="employee_id" class="portal-select">
                <option value="">All employees</option>
                <?php foreach ($employees_list as $e): ?>
                    <option value="<?php echo (int)$e['id']; ?>" <?php echo $employee_id_filter === (int)$e['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($e['last_name'] . ', ' . $e['first_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="type" class="portal-select">
                <option value="">All types</option>
                <option value="201_file" <?php echo $type_filter === '201_file' ? 'selected' : ''; ?>>201 file</option>
                <option value="staff_file" <?php echo $type_filter === 'staff_file' ? 'selected' : ''; ?>>Staff file</option>
            </select>
            <button type="submit" class="portal-btn portal-btn-secondary">Filter</button>
        </form>

        <?php if (empty($documents)): ?>
            <p class="portal-text-muted">No documents found. Upload above or run <code>database/schema/phase1_hr.sql</code> if the Documents table is missing.</p>
        <?php else: ?>
            <div class="portal-table-wrap">
                <table class="portal-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>File name</th>
                            <th>Size</th>
                            <th>Uploaded</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($doc['first_name'] . ' ' . $doc['last_name']); ?></td>
                                <td><span class="portal-badge portal-badge-neutral"><?php echo htmlspecialchars($doc['document_type']); ?></span></td>
                                <td><?php echo htmlspecialchars($doc['category'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($doc['file_name']); ?></td>
                                <td><?php echo $doc['file_size'] ? number_format($doc['file_size'] / 1024, 1) . ' KB' : '—'; ?></td>
                                <td><?php echo date('M j, Y', strtotime($doc['created_at'])); ?></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($base_url); ?>/document-download?id=<?php echo (int)$doc['id']; ?>" class="portal-btn portal-btn-ghost portal-btn-sm">Download</a>
                                    <form method="post" action="" class="portal-inline-form" onsubmit="return confirm('Delete this document?');">
                                        <input type="hidden" name="document_delete" value="1">
                                        <input type="hidden" name="document_id" value="<?php echo (int)$doc['id']; ?>">
                                        <?php if ($employee_id_filter): ?><input type="hidden" name="employee_id" value="<?php echo $employee_id_filter; ?>"><?php endif; ?>
                                        <?php if ($type_filter): ?><input type="hidden" name="type" value="<?php echo htmlspecialchars($type_filter); ?>"><?php endif; ?>
                                        <button type="submit" class="portal-btn portal-btn-ghost portal-btn-sm portal-btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>
