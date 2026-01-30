<?php
/**
 * Phase 1 — Edit employee. Logs history per field change + audit.
 */
$page_title = 'Edit Employee';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: ' . $base_url . '?page=employees');
    exit;
}

$employee = null;
$errors = [];
try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("SELECT id, employee_number, first_name, last_name, email, department, position, hire_date, status, created_at, updated_at FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$employee) {
        header('Location: ' . $base_url . '?page=employees');
        exit;
    }
} catch (Throwable $e) {
    header('Location: ' . $base_url . '?page=employees');
    exit;
}

$posted = $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_employee']);
if ($posted) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $employee_number = trim($_POST['employee_number'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $hire_date = trim($_POST['hire_date'] ?? '');
    $status = trim($_POST['status'] ?? 'active');

    if ($first_name === '') {
        $errors['first_name'] = 'First name is required.';
    }
    if ($last_name === '') {
        $errors['last_name'] = 'Last name is required.';
    }
    if ($status !== '' && !in_array($status, ['active', 'inactive', 'terminated', 'on_leave'], true)) {
        $status = 'active';
    }
    if ($hire_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $hire_date)) {
        $errors['hire_date'] = 'Invalid date format (YYYY-MM-DD).';
    }

    if (empty($errors)) {
        try {
            if ($employee_number !== '' && $employee_number !== ($employee['employee_number'] ?? '')) {
                $dup = $pdo->prepare("SELECT id FROM employees WHERE employee_number = ? AND id != ?");
                $dup->execute([$employee_number, $id]);
                if ($dup->fetch()) {
                    $errors['employee_number'] = 'Employee number already exists.';
                }
            }
            if (empty($errors)) {
                $userId = $_SESSION['user_id'] ?? null;
                $fields = ['employee_number' => $employee_number ?: null, 'first_name' => $first_name, 'last_name' => $last_name, 'email' => $email ?: null, 'department' => $department ?: null, 'position' => $position ?: null, 'hire_date' => $hire_date ?: null, 'status' => $status ?: 'active'];
                foreach ($fields as $field => $newVal) {
                    $oldVal = $employee[$field] ?? null;
                    if ((string)$oldVal !== (string)$newVal && function_exists('log_employee_history')) {
                        log_employee_history($id, $field, $oldVal === null ? null : (string)$oldVal, $newVal === null ? null : (string)$newVal, $userId);
                    }
                }
                $stmt = $pdo->prepare(
                    "UPDATE employees SET employee_number = ?, first_name = ?, last_name = ?, email = ?, department = ?, position = ?, hire_date = ?, status = ?, updated_at = NOW() WHERE id = ?"
                );
                $stmt->execute([$fields['employee_number'], $fields['first_name'], $fields['last_name'], $fields['email'], $fields['department'], $fields['position'], $fields['hire_date'], $fields['status'], $id]);
                if (function_exists('log_audit_event')) {
                    log_audit_event('UPDATE', 'employees', $id, $employee, $fields, $userId);
                }
                header('Location: ' . $base_url . '?page=employee-view&id=' . $id);
                exit;
            }
        } catch (Throwable $e) {
            $errors['form'] = 'Could not save. Please try again.';
            error_log('employee-edit: ' . $e->getMessage());
        }
    }
} else {
    $first_name = $employee['first_name'];
    $last_name = $employee['last_name'];
    $email = $employee['email'] ?? '';
    $employee_number = $employee['employee_number'] ?? '';
    $department = $employee['department'] ?? '';
    $position = $employee['position'] ?? '';
    $hire_date = $employee['hire_date'] ?? '';
    $status = $employee['status'] ?? 'active';
}
?>
<div class="hr-page hr-page-employee-form">
    <header class="hr-page-header">
        <nav class="hr-breadcrumb" aria-label="Breadcrumb">
            <ol class="hr-breadcrumb-list">
                <li class="hr-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a></li>
                <li class="hr-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=employees">Employees</a></li>
                <li class="hr-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=employee-view&id=<?php echo $id; ?>"><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></a></li>
                <li class="hr-breadcrumb-item hr-breadcrumb-current" aria-current="page">Edit</li>
            </ol>
        </nav>
        <div class="hr-page-header-actions">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employee-view&id=<?php echo $id; ?>" class="hr-btn hr-btn-ghost">View profile</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="hr-btn hr-btn-ghost">Back to list</a>
        </div>
    </header>

    <section class="hr-section">
        <h2 class="hr-section-title">Edit employee</h2>
        <?php if (!empty($errors['form'])): ?>
            <div class="hr-alert hr-alert-error">
                <div class="hr-alert-icon" aria-hidden="true">!</div>
                <div class="hr-alert-body">
                    <p class="hr-alert-message"><?php echo htmlspecialchars($errors['form']); ?></p>
                </div>
            </div>
        <?php endif; ?>
        <form method="post" action="" class="hr-form">
            <input type="hidden" name="edit_employee" value="1">
            <div class="hr-form-grid">
                <div class="hr-form-group">
                    <label for="first_name">First name <span class="hr-required">*</span></label>
                    <input type="text" id="first_name" name="first_name" class="hr-input" required maxlength="100" value="<?php echo htmlspecialchars($first_name); ?>">
                    <?php if (!empty($errors['first_name'])): ?>
                        <span class="hr-form-error"><?php echo htmlspecialchars($errors['first_name']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="hr-form-group">
                    <label for="last_name">Last name <span class="hr-required">*</span></label>
                    <input type="text" id="last_name" name="last_name" class="hr-input" required maxlength="100" value="<?php echo htmlspecialchars($last_name); ?>">
                    <?php if (!empty($errors['last_name'])): ?>
                        <span class="hr-form-error"><?php echo htmlspecialchars($errors['last_name']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="hr-form-group">
                    <label for="employee_number">Employee number</label>
                    <input type="text" id="employee_number" name="employee_number" class="hr-input" maxlength="50" value="<?php echo htmlspecialchars($employee_number); ?>">
                    <?php if (!empty($errors['employee_number'])): ?>
                        <span class="hr-form-error"><?php echo htmlspecialchars($errors['employee_number']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="hr-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="hr-input" maxlength="255" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="hr-form-group">
                    <label for="department">Department</label>
                    <input type="text" id="department" name="department" class="hr-input" maxlength="100" value="<?php echo htmlspecialchars($department); ?>">
                </div>
                <div class="hr-form-group">
                    <label for="position">Position</label>
                    <input type="text" id="position" name="position" class="hr-input" maxlength="100" value="<?php echo htmlspecialchars($position); ?>">
                </div>
                <div class="hr-form-group">
                    <label for="hire_date">Hire date</label>
                    <input type="date" id="hire_date" name="hire_date" class="hr-input" value="<?php echo htmlspecialchars($hire_date); ?>">
                    <?php if (!empty($errors['hire_date'])): ?>
                        <span class="hr-form-error"><?php echo htmlspecialchars($errors['hire_date']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="hr-form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="hr-select">
                        <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        <option value="terminated" <?php echo $status === 'terminated' ? 'selected' : ''; ?>>Terminated</option>
                        <option value="on_leave" <?php echo $status === 'on_leave' ? 'selected' : ''; ?>>On leave</option>
                    </select>
                </div>
            </div>
            <div class="hr-form-actions">
                <button type="submit" class="hr-btn hr-btn-primary">Save changes</button>
                <a href="<?php echo htmlspecialchars($base_url); ?>?page=employee-view&id=<?php echo $id; ?>" class="hr-btn hr-btn-ghost">Cancel</a>
            </div>
        </form>
    </section>
</div>
