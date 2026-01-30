<?php
/**
 * Phase 1 — Employee profile view + history (track changes).
 */
$page_title = 'Employee';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: ' . $base_url . '?page=employees');
    exit;
}

$employee = null;
$history = [];
try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("SELECT id, employee_number, first_name, last_name, email, department, position, hire_date, status, created_at, updated_at FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$employee) {
        header('Location: ' . $base_url . '?page=employees');
        exit;
    }
    $page_title = $employee['first_name'] . ' ' . $employee['last_name'];
    try {
        $hist_stmt = $pdo->prepare("SELECT h.id, h.field_name, h.old_value, h.new_value, h.changed_at, u.name AS changed_by_name FROM employee_history h LEFT JOIN users u ON u.id = h.changed_by WHERE h.employee_id = ? ORDER BY h.changed_at DESC LIMIT 50");
        $hist_stmt->execute([$id]);
        $history = $hist_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        $history = [];
    }
} catch (Throwable $e) {
    header('Location: ' . $base_url . '?page=employees');
    exit;
}
?>
<div class="hr-page hr-page-employee-view">
    <header class="hr-page-header">
        <nav class="hr-breadcrumb" aria-label="Breadcrumb">
            <ol class="hr-breadcrumb-list">
                <li class="hr-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a></li>
                <li class="hr-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=employees">Employees</a></li>
                <li class="hr-breadcrumb-item hr-breadcrumb-current" aria-current="page"><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></li>
            </ol>
        </nav>
        <div class="hr-page-header-actions">
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employee-edit&id=<?php echo $id; ?>" class="hr-btn hr-btn-primary">Edit</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=documents&employee_id=<?php echo $id; ?>" class="hr-btn hr-btn-ghost">Documents</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employees" class="hr-btn hr-btn-ghost">Back to list</a>
        </div>
    </header>

    <section class="hr-section">
        <h2 class="hr-section-title">Profile</h2>
        <div class="hr-card hr-profile-card">
            <dl class="hr-dl">
                <dt>Employee number</dt>
                <dd><?php echo htmlspecialchars($employee['employee_number'] ?? '—'); ?></dd>
                <dt>First name</dt>
                <dd><?php echo htmlspecialchars($employee['first_name']); ?></dd>
                <dt>Last name</dt>
                <dd><?php echo htmlspecialchars($employee['last_name']); ?></dd>
                <dt>Email</dt>
                <dd><?php echo htmlspecialchars($employee['email'] ?? '—'); ?></dd>
                <dt>Department</dt>
                <dd><?php echo htmlspecialchars($employee['department'] ?? '—'); ?></dd>
                <dt>Position</dt>
                <dd><?php echo htmlspecialchars($employee['position'] ?? '—'); ?></dd>
                <dt>Hire date</dt>
                <dd><?php echo $employee['hire_date'] ? date('M j, Y', strtotime($employee['hire_date'])) : '—'; ?></dd>
                <dt>Status</dt>
                <dd><span class="hr-badge hr-badge-<?php echo $employee['status'] === 'active' ? 'success' : 'neutral'; ?>"><?php echo htmlspecialchars($employee['status']); ?></span></dd>
                <dt>Created</dt>
                <dd><?php echo $employee['created_at'] ? date('M j, Y H:i', strtotime($employee['created_at'])) : '—'; ?></dd>
                <dt>Updated</dt>
                <dd><?php echo $employee['updated_at'] ? date('M j, Y H:i', strtotime($employee['updated_at'])) : '—'; ?></dd>
            </dl>
        </div>
    </section>

    <section class="hr-section">
        <h2 class="hr-section-title">History</h2>
        <?php if (empty($history)): ?>
            <p class="hr-text-muted">No change history yet.</p>
        <?php else: ?>
            <div class="hr-table-wrap">
                <table class="hr-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Old value</th>
                            <th>New value</th>
                            <th>Changed by</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['field_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['old_value'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($row['new_value'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($row['changed_by_name'] ?? '—'); ?></td>
                                <td><?php echo date('M j, Y H:i', strtotime($row['changed_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>
