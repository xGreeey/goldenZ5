<?php
/**
 * Phase 1 — Add Employee form. Validation, duplicate check (employee_number), audit on create.
 */
$page_title = 'Add Employee';

$errors = [];
$posted = $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee']);
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
            $pdo = get_db_connection();
            if ($employee_number !== '') {
                $dup = $pdo->prepare("SELECT id FROM employees WHERE employee_number = ?");
                $dup->execute([$employee_number]);
                if ($dup->fetch()) {
                    $errors['employee_number'] = 'Employee number already exists.';
                }
            }
            if (empty($errors)) {
                $stmt = $pdo->prepare(
                    "INSERT INTO employees (employee_number, first_name, last_name, email, department, position, hire_date, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
                );
                $stmt->execute([
                    $employee_number ?: null,
                    $first_name,
                    $last_name,
                    $email ?: null,
                    $department ?: null,
                    $position ?: null,
                    $hire_date ?: null,
                    $status ?: 'active',
                ]);
                $new_id = (int) $pdo->lastInsertId();
                if (function_exists('log_audit_event')) {
                    log_audit_event('CREATE', 'employees', $new_id, null, ['first_name' => $first_name, 'last_name' => $last_name], $_SESSION['user_id'] ?? null);
                }
                header('Location: ' . $base_url . '?page=employee-view&id=' . $new_id);
                exit;
            }
        } catch (Throwable $e) {
            $errors['form'] = 'Could not save. Please try again.';
            error_log('employee-add: ' . $e->getMessage());
        }
    }
} else {
    $first_name = $last_name = $email = $employee_number = $department = $position = $hire_date = '';
    $status = 'active';
}
?>
<div class="hr-page hr-page-employee-form">
    <header class="hr-page-header">
        <nav class="hr-breadcrumb" aria-label="Breadcrumb">
            <ol class="hr-breadcrumb-list">
                <li class="hr-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a></li>
                <li class="hr-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=employees">Employees</a></li>
                <li class="hr-breadcrumb-item hr-breadcrumb-current" aria-current="page">Add employee</li>
            </ol>
        </nav>
        <div class="hr-page-header-actions">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="hr-btn hr-btn-ghost">Back to list</a>
        </div>
    </header>

    <section class="hr-section">
        <h2 class="hr-section-title">New employee</h2>
        <?php if (!empty($errors['form'])): ?>
            <div class="hr-alert hr-alert-error">
                <div class="hr-alert-icon" aria-hidden="true">!</div>
                <div class="hr-alert-body">
                    <p class="hr-alert-message"><?php echo htmlspecialchars($errors['form']); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Material-style stepper (2 steps) -->
        <div class="hr-stepper" id="addEmployeeStepper" role="group" aria-label="Add employee steps">
            <div class="hr-stepper-step active" id="stepperStep1" data-step="1">
                <span class="hr-stepper-step-indicator"><span>1</span></span>
                <span class="hr-stepper-connector" aria-hidden="true"></span>
                <span class="hr-stepper-step-label">Personal</span>
            </div>
            <div class="hr-stepper-step" id="stepperStep2" data-step="2">
                <span class="hr-stepper-step-indicator"><span>2</span></span>
                <span class="hr-stepper-connector" aria-hidden="true"></span>
                <span class="hr-stepper-step-label">Employment</span>
            </div>
        </div>

        <form method="post" action="" class="hr-form" id="addEmployeeForm">
            <input type="hidden" name="add_employee" value="1">
            <div class="hr-form-step active" id="formStep1" data-step="1">
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
                </div>
                <div class="hr-form-actions">
                    <button type="button" class="hr-btn hr-btn-primary" id="addEmployeeNext">Next</button>
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="hr-btn hr-btn-ghost">Cancel</a>
                </div>
            </div>
            <div class="hr-form-step" id="formStep2" data-step="2">
                <div class="hr-form-grid">
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
                    <button type="button" class="hr-btn hr-btn-ghost" id="addEmployeeBack">Back</button>
                    <button type="submit" class="hr-btn hr-btn-primary">Save employee</button>
                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="hr-btn hr-btn-ghost">Cancel</a>
                </div>
            </div>
        </form>
    </section>
    <script>
    (function() {
        var form = document.getElementById('addEmployeeForm');
        var step1 = document.getElementById('formStep1');
        var step2 = document.getElementById('formStep2');
        var s1 = document.getElementById('stepperStep1');
        var s2 = document.getElementById('stepperStep2');
        var nextBtn = document.getElementById('addEmployeeNext');
        var backBtn = document.getElementById('addEmployeeBack');
        if (!form || !step1 || !step2 || !s1 || !s2) return;
        function goToStep(step) {
            step1.classList.toggle('active', step === 1);
            step2.classList.toggle('active', step === 2);
            s1.classList.toggle('active', step === 1);
            s1.classList.toggle('completed', step > 1);
            s2.classList.toggle('active', step === 2);
            s2.classList.toggle('completed', false);
        }
        if (nextBtn) nextBtn.addEventListener('click', function() { goToStep(2); });
        if (backBtn) backBtn.addEventListener('click', function() { goToStep(1); });
    })();
    </script>
</div>
