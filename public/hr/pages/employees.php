<?php
/**
 * Phase 1 — Employee master list: search, filter, export CSV.
 * Audit: export logged via log_audit_event.
 */
$page_title = 'Employees';

$filter_status = isset($_GET['status']) ? trim($_GET['status']) : 'active';
if (!in_array($filter_status, ['active', 'inactive', 'all'], true)) {
    $filter_status = 'active';
}
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$department_filter = isset($_GET['department']) ? trim($_GET['department']) : '';
$export = isset($_GET['export']) ? strtolower(trim($_GET['export'])) : '';

$employees = [];
$departments = [];
try {
    $pdo = get_db_connection();
    $departments_stmt = $pdo->query("SELECT DISTINCT department FROM employees WHERE department IS NOT NULL AND department != '' ORDER BY department");
    if ($departments_stmt) {
        $departments = $departments_stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    $sql = "SELECT id, employee_number, first_name, last_name, email, department, position, hire_date, status, created_at FROM employees WHERE 1=1";
    $params = [];
    if ($filter_status === 'active') {
        $sql .= " AND status = 'active'";
    } elseif ($filter_status === 'inactive') {
        $sql .= " AND status != 'active'";
    }
    if ($department_filter !== '') {
        $sql .= " AND department = ?";
        $params[] = $department_filter;
    }
    if ($search !== '') {
        $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR employee_number LIKE ? OR email LIKE ?)";
        $term = '%' . $search . '%';
        $params = array_merge($params, [$term, $term, $term, $term]);
    }
    $sql .= " ORDER BY last_name, first_name LIMIT 2000";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    // ignore
}

// Export CSV (before any HTML)
if ($export === 'csv' && !empty($employees)) {
    if (function_exists('log_audit_event')) {
        log_audit_event('EXPORT', 'employees', null, null, ['format' => 'csv', 'count' => count($employees)], $_SESSION['user_id'] ?? null);
    }
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="employees_' . date('Y-m-d_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID', 'Employee Number', 'First Name', 'Last Name', 'Email', 'Department', 'Position', 'Hire Date', 'Status']);
    foreach ($employees as $row) {
        fputcsv($out, [
            $row['id'],
            $row['employee_number'] ?? '',
            $row['first_name'] ?? '',
            $row['last_name'] ?? '',
            $row['email'] ?? '',
            $row['department'] ?? '',
            $row['position'] ?? '',
            $row['hire_date'] ?? '',
            $row['status'] ?? '',
        ]);
    }
    fclose($out);
    exit;
}

$base_full = $base_url . '?page=employees';
$query_params = array_filter([
    'page' => 'employees',
    'status' => $filter_status,
    'q' => $search !== '' ? $search : null,
    'department' => $department_filter !== '' ? $department_filter : null,
]);
?>
<div class="portal-page portal-page-employees">
    <header class="portal-page-header">
        <nav class="portal-breadcrumb" aria-label="Breadcrumb">
            <ol class="portal-breadcrumb-list">
                <li class="portal-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a></li>
                <li class="portal-breadcrumb-item portal-breadcrumb-current" aria-current="page">Employees</li>
            </ol>
        </nav>
        <div class="portal-page-header-actions">
            <a href="<?php echo htmlspecialchars($base_url); ?>?<?php echo http_build_query(array_merge($query_params, ['export' => 'csv'])); ?>" class="portal-btn portal-btn-ghost">Export CSV</a>
            <a href="<?php echo htmlspecialchars($base_url); ?>?page=employee-add" class="portal-btn portal-btn-primary">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Add employee
            </a>
        </div>
    </header>

    <div class="portal-page-actions">
        <div class="portal-pill-tabs">
            <a href="<?php echo htmlspecialchars($base_url . '?' . http_build_query(array_merge($query_params, ['status' => 'active']))); ?>" class="portal-pill-tab <?php echo $filter_status === 'active' ? 'active' : ''; ?>">Active</a>
            <a href="<?php echo htmlspecialchars($base_url . '?' . http_build_query(array_merge($query_params, ['status' => 'inactive']))); ?>" class="portal-pill-tab <?php echo $filter_status === 'inactive' ? 'active' : ''; ?>">Inactive</a>
            <a href="<?php echo htmlspecialchars($base_url . '?' . http_build_query(array_merge($query_params, ['status' => 'all']))); ?>" class="portal-pill-tab <?php echo $filter_status === 'all' ? 'active' : ''; ?>">All</a>
        </div>
    </div>

    <section class="portal-section">
        <h2 class="portal-section-title">Employee list</h2>
        <form method="get" action="<?php echo htmlspecialchars($base_url); ?>" class="portal-filters">
            <input type="hidden" name="page" value="employees">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($filter_status); ?>">
            <div class="portal-search-wrap">
                <i class="fas fa-search portal-search-icon" aria-hidden="true"></i>
                <input type="search" name="q" class="portal-search-input" placeholder="Search by name, ID, or email" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <select name="department" class="portal-select">
                <option value="">All departments</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?php echo htmlspecialchars($dept); ?>" <?php echo $department_filter === $dept ? 'selected' : ''; ?>><?php echo htmlspecialchars($dept); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="portal-btn portal-btn-secondary">Apply</button>
            <?php if ($search !== '' || $department_filter !== ''): ?>
                <a href="<?php echo htmlspecialchars($base_url . '?' . http_build_query(['page' => 'employees', 'status' => $filter_status])); ?>" class="portal-btn portal-btn-ghost">Clear</a>
            <?php endif; ?>
        </form>

        <div class="portal-table-wrap">
            <table class="portal-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Hire date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($employees)): ?>
                        <tr>
                            <td colspan="6" class="portal-table-empty">No employees found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($employees as $emp): ?>
                            <tr>
                                <td>
                                    <div class="portal-table-cell-primary">
                                        <span class="portal-table-name"><?php echo htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']); ?></span>
                                        <?php if (!empty($emp['employee_number'])): ?>
                                            <span class="portal-table-meta"><?php echo htmlspecialchars($emp['employee_number']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($emp['department'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($emp['position'] ?? '—'); ?></td>
                                <td>
                                    <span class="portal-badge portal-badge-<?php echo $emp['status'] === 'active' ? 'success' : 'neutral'; ?>"><?php echo htmlspecialchars($emp['status']); ?></span>
                                </td>
                                <td><?php echo $emp['hire_date'] ? date('M j, Y', strtotime($emp['hire_date'])) : '—'; ?></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=employee-view&id=<?php echo (int)$emp['id']; ?>" class="portal-btn portal-btn-ghost portal-btn-sm">View</a>
                                    <a href="<?php echo htmlspecialchars($base_url); ?>?page=employee-edit&id=<?php echo (int)$emp['id']; ?>" class="portal-btn portal-btn-ghost portal-btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
