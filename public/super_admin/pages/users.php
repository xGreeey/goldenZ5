<?php
/**
 * Super Admin - User Management Page
 * Allows super admin to create user accounts with auto-generated passwords
 * Password is automatically sent to user's email via PHPMailer
 */

$page_title = 'User Management';
// Path resolution: users.php is at public/super_admin/pages/users.php
// Need to find app/services/EmailService.php which is at the src root level
// Try multiple approaches to find the correct root
$possibleRoots = [
    dirname(__DIR__, 3),  // Go up 3 levels: pages -> super_admin -> public -> root
    dirname(__DIR__, 2),  // Go up 2 levels: pages -> super_admin -> public
    dirname(dirname(__DIR__)),  // Alternative calculation
];

// Find the root that contains the app folder
$appRoot = null;
foreach ($possibleRoots as $root) {
    if (is_dir($root . '/app')) {
        $appRoot = $root;
        break;
    }
}

// Fallback: use the same as index.php if app folder not found
if (!$appRoot) {
    $appRoot = dirname(__DIR__, 2); // This is what index.php uses
}

// Handle form submission
$success_message = '';
$error_message = '';
$created_user = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    if (!csrf_validate()) {
        $error_message = 'Invalid security token. Please refresh the page and try again.';
    } else {
        // Get form data
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $role = trim($_POST['role'] ?? 'employee');
        $employee_id = !empty($_POST['employee_id']) ? (int) $_POST['employee_id'] : null;
        $department = !empty($_POST['department']) ? trim($_POST['department']) : null;
        $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
        $status = trim($_POST['status'] ?? 'active');

        // Validation
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'Username is required.';
        } elseif (strlen($username) > 50) {
            $errors[] = 'Username must be 50 characters or less.';
        }

        if (empty($email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        } elseif (strlen($email) > 100) {
            $errors[] = 'Email must be 100 characters or less.';
        }

        if (empty($name)) {
            $errors[] = 'Name is required.';
        } elseif (strlen($name) > 100) {
            $errors[] = 'Name must be 100 characters or less.';
        }

        $valid_roles = ['super_admin', 'admin', 'humanresource', 'accounting', 'operation', 'logistics', 'employee', 'developer'];
        if (!in_array($role, $valid_roles, true)) {
            $errors[] = 'Invalid role selected.';
        }

        $valid_statuses = ['active', 'inactive', 'suspended'];
        if (!in_array($status, $valid_statuses, true)) {
            $errors[] = 'Invalid status selected.';
        }

        // Check if username or email already exists
        if (empty($errors)) {
            $existing_user = db_fetch_one('SELECT id FROM users WHERE username = ? OR email = ?', [$username, $email]);
            if ($existing_user) {
                $errors[] = 'Username or email already exists.';
            }
        }

        if (empty($errors)) {
            // Generate secure password
            function generateSecurePassword(int $length = 12): string
            {
                $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $lowercase = 'abcdefghijklmnopqrstuvwxyz';
                $numbers = '0123456789';
                $symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
                
                $allChars = $uppercase . $lowercase . $numbers . $symbols;
                $password = '';
                
                // Ensure at least one character from each category
                $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
                $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
                $password .= $numbers[random_int(0, strlen($numbers) - 1)];
                $password .= $symbols[random_int(0, strlen($symbols) - 1)];
                
                // Fill the rest randomly
                for ($i = strlen($password); $i < $length; $i++) {
                    $password .= $allChars[random_int(0, strlen($allChars) - 1)];
                }
                
                // Shuffle the password
                return str_shuffle($password);
            }

            $generated_password = generateSecurePassword(16);
            $password_hash = password_hash($generated_password, PASSWORD_BCRYPT);
            $created_by = $_SESSION['user_id'] ?? null;

            try {
                // Insert user into database
                db_execute(
                    'INSERT INTO users (username, email, password_hash, name, role, status, employee_id, department, phone, created_by, created_at, updated_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
                    [$username, $email, $password_hash, $name, $role, $status, $employee_id, $department, $phone, $created_by]
                );

                $new_user_id = get_db_connection()->lastInsertId();

                // Send welcome email with password
                // Try multiple possible paths for EmailService (handles different Docker/server setups)
                $possiblePaths = [
                    $appRoot . '/app/services/EmailService.php',  // From public directory (if app is sibling to public)
                    dirname($appRoot) . '/app/services/EmailService.php',  // From parent of public (standard structure)
                    __DIR__ . '/../../../app/services/EmailService.php',  // Relative: from users.php going up 3 levels
                    dirname(__DIR__, 3) . '/app/services/EmailService.php',  // Direct calculation from users.php
                ];
                
                $emailServicePath = null;
                foreach ($possiblePaths as $path) {
                    $realPath = realpath($path);
                    if ($realPath && file_exists($realPath)) {
                        $emailServicePath = $realPath;
                        break;
                    }
                }
                
                if ($emailServicePath) {
                    require_once $emailServicePath;
                    $emailService = new EmailService();
                    $emailResult = $emailService->sendWelcomeEmail($email, $username, $name, $generated_password, $role);
                } else {
                    // Email service not found - user will be created but email won't be sent
                    error_log('EmailService.php not found. Tried paths: ' . implode(', ', $possiblePaths));
                    $emailResult = ['success' => false, 'message' => 'Email service not available'];
                    $created_user = [
                        'id' => $new_user_id,
                        'username' => $username,
                        'email' => $email,
                        'name' => $name,
                        'role' => $role,
                        'password' => $generated_password, // Show password if email service unavailable
                    ];
                }

                if ($emailResult['success']) {
                    $success_message = 'User account created successfully! Password has been sent to ' . htmlspecialchars($email) . '.';
                    $created_user = [
                        'id' => $new_user_id,
                        'username' => $username,
                        'email' => $email,
                        'name' => $name,
                        'role' => $role,
                    ];

                    // Log audit event
                    if (function_exists('log_audit_event')) {
                        log_audit_event(
                            'CREATE',
                            'users',
                            $new_user_id,
                            null,
                            ['username' => $username, 'email' => $email, 'role' => $role],
                            $created_by
                        );
                    }
                } else {
                    // User created but email failed
                    $error_message = 'User account created, but failed to send email: ' . htmlspecialchars($emailResult['message']) . 
                                    '. Please contact the user directly with their password.';
                    $created_user = [
                        'id' => $new_user_id,
                        'username' => $username,
                        'email' => $email,
                        'name' => $name,
                        'role' => $role,
                        'password' => $generated_password, // Show password if email failed
                    ];
                }
            } catch (Exception $e) {
                error_log('User creation error: ' . $e->getMessage());
                $error_message = 'Failed to create user account: ' . htmlspecialchars($e->getMessage());
            }
        } else {
            $error_message = implode(' ', $errors);
        }
    }
}

// Get user statistics
$total_users = db_fetch_one('SELECT COUNT(*) as count FROM users')['count'] ?? 0;
$active_users = db_fetch_one('SELECT COUNT(*) as count FROM users WHERE status = ?', ['active'])['count'] ?? 0;
$inactive_users = db_fetch_one('SELECT COUNT(*) as count FROM users WHERE status = ?', ['inactive'])['count'] ?? 0;
$suspended_users = db_fetch_one('SELECT COUNT(*) as count FROM users WHERE status = ?', ['suspended'])['count'] ?? 0;

// Get recent users (for quick reference)
$recent_users = db_fetch_all('SELECT id, username, email, name, role, status, created_at FROM users ORDER BY created_at DESC LIMIT 5');
?>

<div class="portal-page portal-page-users">
    <div class="portal-dashboard-header">
        <div class="portal-dashboard-welcome">
            <h1 class="portal-dashboard-user-name">User Management</h1>
            <span class="portal-dashboard-greeting">Create and manage user accounts</span>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if ($success_message): ?>
    <div class="portal-alert portal-alert-success portal-alert-auto-hide" role="alert" id="successAlert">
        <i class="fas fa-check-circle" aria-hidden="true"></i>
        <span><?php echo $success_message; ?></span>
        <button type="button" class="portal-alert-close" aria-label="Close notification" onclick="this.parentElement.remove()">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
    <div class="portal-alert portal-alert-error" role="alert" id="errorAlert">
        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
        <span><?php echo htmlspecialchars($error_message); ?></span>
        <button type="button" class="portal-alert-close" aria-label="Close notification" onclick="this.parentElement.remove()">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if ($created_user && isset($created_user['password'])): ?>
    <div class="portal-alert portal-alert-warning" role="alert" id="warningAlert">
        <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
        <div>
            <strong>Email delivery failed. Please provide this password to the user manually:</strong>
            <div style="margin-top: 10px; padding: 10px; background: #f5f5f5; border-radius: 5px; font-family: monospace; font-size: 14px;">
                Password: <strong><?php echo htmlspecialchars($created_user['password']); ?></strong>
            </div>
        </div>
        <button type="button" class="portal-alert-close" aria-label="Close notification" onclick="this.parentElement.remove()">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
    </div>
    <?php endif; ?>

    <div class="sadash-grid sadash-two-col">
        <!-- Create User Form -->
        <section class="portal-section portal-dashboard-card sadash-section">
            <div class="portal-section-header">
                <h2 class="portal-section-title">Create New User</h2>
            </div>
            <div class="sadash-panel-content">
                <form method="POST" class="portal-form portal-form-compact" id="createUserForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="create_user" value="1">
                    <?php 
                    // Don't populate form fields if user was successfully created
                    $populateFields = empty($success_message);
                    ?>

                    <div class="portal-form-row">
                        <div class="portal-form-group">
                            <label for="username" class="portal-form-label">
                                Username <span class="required-indicator">*</span>
                            </label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   class="portal-form-input" 
                                   required 
                                   maxlength="50"
                                   value="<?php echo ($populateFields && isset($_POST['username'])) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                   placeholder="Enter username">
                        </div>

                        <div class="portal-form-group">
                            <label for="name" class="portal-form-label">
                                Full Name <span class="required-indicator">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="portal-form-input" 
                                   required 
                                   maxlength="100"
                                   value="<?php echo ($populateFields && isset($_POST['name'])) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                   placeholder="Enter full name">
                        </div>
                    </div>

                    <div class="portal-form-group">
                        <label for="email" class="portal-form-label">
                            Email <span class="required-indicator">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="portal-form-input" 
                               required 
                               maxlength="100"
                               value="<?php echo ($populateFields && isset($_POST['email'])) ? htmlspecialchars($_POST['email']) : ''; ?>"
                               placeholder="user@example.com">
                        <small class="portal-form-hint">Password will be sent to this email</small>
                    </div>

                    <div class="portal-form-row">
                        <div class="portal-form-group">
                            <label for="role" class="portal-form-label">
                                Role <span class="required-indicator">*</span>
                            </label>
                            <select id="role" name="role" class="portal-form-input" required>
                                <option value="employee" <?php echo ($populateFields && isset($_POST['role']) && $_POST['role'] === 'employee') ? 'selected' : 'selected'; ?>>Employee</option>
                                <option value="admin" <?php echo ($populateFields && isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="humanresource" <?php echo ($populateFields && isset($_POST['role']) && $_POST['role'] === 'humanresource') ? 'selected' : ''; ?>>Human Resource</option>
                                <option value="accounting" <?php echo ($populateFields && isset($_POST['role']) && $_POST['role'] === 'accounting') ? 'selected' : ''; ?>>Accounting</option>
                                <option value="operation" <?php echo ($populateFields && isset($_POST['role']) && $_POST['role'] === 'operation') ? 'selected' : ''; ?>>Operation</option>
                                <option value="logistics" <?php echo ($populateFields && isset($_POST['role']) && $_POST['role'] === 'logistics') ? 'selected' : ''; ?>>Logistics</option>
                                <option value="developer" <?php echo ($populateFields && isset($_POST['role']) && $_POST['role'] === 'developer') ? 'selected' : ''; ?>>Developer</option>
                                <option value="super_admin" <?php echo ($populateFields && isset($_POST['role']) && $_POST['role'] === 'super_admin') ? 'selected' : ''; ?>>Super Admin</option>
                            </select>
                        </div>

                        <div class="portal-form-group">
                            <label for="status" class="portal-form-label">
                                Status <span class="required-indicator">*</span>
                            </label>
                            <select id="status" name="status" class="portal-form-input" required>
                                <option value="active" <?php echo ($populateFields && isset($_POST['status']) && $_POST['status'] === 'active') ? 'selected' : 'selected'; ?>>Active</option>
                                <option value="inactive" <?php echo ($populateFields && isset($_POST['status']) && $_POST['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                <option value="suspended" <?php echo ($populateFields && isset($_POST['status']) && $_POST['status'] === 'suspended') ? 'selected' : ''; ?>>Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div class="portal-form-row">
                        <div class="portal-form-group">
                            <label for="employee_id" class="portal-form-label">Employee ID</label>
                            <input type="number" 
                                   id="employee_id" 
                                   name="employee_id" 
                                   class="portal-form-input"
                                   value="<?php echo ($populateFields && isset($_POST['employee_id'])) ? htmlspecialchars($_POST['employee_id']) : ''; ?>"
                                   placeholder="Optional">
                        </div>

                        <div class="portal-form-group">
                            <label for="department" class="portal-form-label">Department</label>
                            <input type="text" 
                                   id="department" 
                                   name="department" 
                                   class="portal-form-input"
                                   maxlength="100"
                                   value="<?php echo ($populateFields && isset($_POST['department'])) ? htmlspecialchars($_POST['department']) : ''; ?>"
                                   placeholder="Optional">
                        </div>
                    </div>

                    <div class="portal-form-group">
                        <label for="phone" class="portal-form-label">Phone</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               class="portal-form-input"
                               maxlength="20"
                               value="<?php echo ($populateFields && isset($_POST['phone'])) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                               placeholder="Optional">
                    </div>

                    <div class="portal-form-actions">
                        <button type="submit" class="portal-btn portal-btn-primary">
                            <i class="fas fa-user-plus" aria-hidden="true"></i>
                            Create User
                        </button>
                        <button type="reset" class="portal-btn portal-btn-secondary">
                            <i class="fas fa-redo" aria-hidden="true"></i>
                            Reset
                        </button>
                    </div>

                    <div class="portal-form-hint portal-form-hint-compact">
                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                        <span>A secure password will be auto-generated and sent via email.</span>
                    </div>
                </form>
            </div>
        </section>

        <!-- User Stats & Info Panel -->
        <section class="portal-section portal-dashboard-card sadash-section">
            <div class="portal-section-header">
                <h2 class="portal-section-title">User Statistics</h2>
            </div>
            <div class="sadash-panel-content">
                <!-- Quick Stats -->
                <div class="user-stats-grid">
                    <div class="user-stat-card">
                        <div class="user-stat-icon" style="background: #dbeafe;">
                            <i class="fas fa-users" style="color: #1e40af;"></i>
                        </div>
                        <div class="user-stat-content">
                            <span class="user-stat-value"><?php echo number_format($total_users); ?></span>
                            <span class="user-stat-label">Total Users</span>
                        </div>
                    </div>
                    <div class="user-stat-card">
                        <div class="user-stat-icon" style="background: #d1fae5;">
                            <i class="fas fa-user-check" style="color: #065f46;"></i>
                        </div>
                        <div class="user-stat-content">
                            <span class="user-stat-value"><?php echo number_format($active_users); ?></span>
                            <span class="user-stat-label">Active</span>
                        </div>
                    </div>
                    <div class="user-stat-card">
                        <div class="user-stat-icon" style="background: #fef3c7;">
                            <i class="fas fa-user-clock" style="color: #92400e;"></i>
                        </div>
                        <div class="user-stat-content">
                            <span class="user-stat-value"><?php echo number_format($inactive_users); ?></span>
                            <span class="user-stat-label">Inactive</span>
                        </div>
                    </div>
                    <div class="user-stat-card">
                        <div class="user-stat-icon" style="background: #fee2e2;">
                            <i class="fas fa-user-lock" style="color: #991b1b;"></i>
                        </div>
                        <div class="user-stat-content">
                            <span class="user-stat-value"><?php echo number_format($suspended_users); ?></span>
                            <span class="user-stat-label">Suspended</span>
                        </div>
                    </div>
                </div>

                <!-- User Creation Guidelines -->
                <div class="user-info-box">
                    <h3 class="user-info-title">
                        <i class="fas fa-lightbulb" aria-hidden="true"></i>
                        Quick Guidelines
                    </h3>
                    <ul class="user-info-list">
                        <li>
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <span>Username must be unique and 50 characters or less</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <span>Email must be valid and will receive the auto-generated password</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <span>Password is automatically generated (16 characters with mixed case, numbers, and symbols)</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <span>User must change password on first login for security</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <span>Role determines access level and dashboard permissions</span>
                        </li>
                    </ul>
                </div>

                <!-- Role Information -->
                <div class="user-info-box">
                    <h3 class="user-info-title">
                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                        Available Roles
                    </h3>
                    <div class="role-tags">
                        <span class="role-tag">Super Admin</span>
                        <span class="role-tag">Admin</span>
                        <span class="role-tag">Human Resource</span>
                        <span class="role-tag">Accounting</span>
                        <span class="role-tag">Operation</span>
                        <span class="role-tag">Logistics</span>
                        <span class="role-tag">Employee</span>
                        <span class="role-tag">Developer</span>
                    </div>
                </div>

                <!-- Recent Users (Compact) -->
                <?php if (!empty($recent_users)): ?>
                <div class="user-info-box">
                    <h3 class="user-info-title">
                        <i class="fas fa-clock" aria-hidden="true"></i>
                        Recently Created
                    </h3>
                    <div class="recent-users-list">
                        <?php foreach ($recent_users as $user): ?>
                        <div class="recent-user-item">
                            <div class="recent-user-avatar">
                                <?php echo mb_strtoupper(mb_substr($user['name'], 0, 1)); ?>
                            </div>
                            <div class="recent-user-info">
                                <span class="recent-user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                                <span class="recent-user-meta"><?php echo htmlspecialchars($user['username']); ?> · <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $user['role']))); ?></span>
                            </div>
                            <span class="recent-user-status <?php echo $user['status'] === 'active' ? 'status-active' : ($user['status'] === 'suspended' ? 'status-suspended' : 'status-inactive'); ?>"></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<style>
.portal-alert {
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    position: relative;
    animation: fadeInSlideDown 0.4s ease-out;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: opacity 0.3s ease-out, transform 0.3s ease-out, margin 0.3s ease-out;
}

.portal-alert.portal-alert-hiding {
    opacity: 0;
    transform: translateY(-10px);
    margin-bottom: 0;
    padding-top: 0;
    padding-bottom: 0;
    overflow: hidden;
}

.portal-alert-success {
    background: #f0fdf4;
    border-left: 4px solid #22c55e;
    color: #166534;
}

.portal-alert-error {
    background: #fef2f2;
    border-left: 4px solid #ef4444;
    color: #991b1b;
}

.portal-alert-warning {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    color: #92400e;
}

.portal-alert-close {
    position: absolute;
    top: 12px;
    right: 12px;
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    opacity: 0.7;
    transition: opacity 0.2s, background 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
}

.portal-alert-close:hover {
    opacity: 1;
    background: rgba(0, 0, 0, 0.1);
}

.portal-alert-close i {
    font-size: 12px;
}

@keyframes fadeInSlideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.portal-form-compact .portal-form-group {
    margin-bottom: 14px;
}

.portal-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}

@media (max-width: 768px) {
    .portal-form-row {
        grid-template-columns: 1fr;
    }
}

.portal-form-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    font-size: 13px;
    color: var(--text-primary, #1f2937);
}

.required-indicator {
    color: #ef4444;
}

.portal-form-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.portal-form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.portal-form-hint {
    display: block;
    margin-top: 4px;
    font-size: 11px;
    color: #6b7280;
}

.portal-form-hint-compact {
    margin-top: 12px;
    padding: 8px 12px;
    background: #f0f9ff;
    border-left: 3px solid #3b82f6;
    border-radius: 4px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.portal-form-hint-compact i {
    color: #3b82f6;
    font-size: 14px;
}

.portal-form-actions {
    display: flex;
    gap: 10px;
    margin-top: 18px;
}

.portal-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}

.portal-btn-primary {
    background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
    color: #1f2937;
}

.portal-btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.portal-btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

.portal-btn-secondary:hover {
    background: #e5e7eb;
}

.portal-table-wrapper {
    overflow-x: auto;
}

.portal-table {
    width: 100%;
    border-collapse: collapse;
}

.portal-table th {
    text-align: left;
    padding: 12px;
    background: #f9fafb;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    color: #6b7280;
    border-bottom: 2px solid #e5e7eb;
}

.portal-table td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
}

.portal-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.portal-badge-success {
    background: #d1fae5;
    color: #065f46;
}

.portal-badge-error {
    background: #fee2e2;
    color: #991b1b;
}

.portal-badge-secondary {
    background: #f3f4f6;
    color: #374151;
}

.portal-badge-info {
    background: #dbeafe;
    color: #1e40af;
}

/* User Stats Grid */
.user-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}

.user-stat-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.user-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.user-stat-content {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.user-stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.2;
}

.user-stat-label {
    font-size: 11px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
}

/* User Info Boxes */
.user-info-box {
    margin-bottom: 20px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.user-info-title {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.user-info-title i {
    color: #3b82f6;
    font-size: 16px;
}

.user-info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.user-info-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 8px 0;
    font-size: 13px;
    color: #4b5563;
    line-height: 1.5;
}

.user-info-list li i {
    color: #22c55e;
    font-size: 14px;
    margin-top: 2px;
    flex-shrink: 0;
}

/* Role Tags */
.role-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.role-tag {
    display: inline-block;
    padding: 4px 10px;
    background: #e0e7ff;
    color: #3730a3;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize;
}

/* Recent Users List */
.recent-users-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.recent-user-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    background: #fff;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.recent-user-item:hover {
    border-color: #d1d5db;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.recent-user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
    color: #1f2937;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

.recent-user-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}

.recent-user-name {
    font-size: 13px;
    font-weight: 500;
    color: #1f2937;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.recent-user-meta {
    font-size: 11px;
    color: #6b7280;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.recent-user-status {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.recent-user-status.status-active {
    background: #22c55e;
}

.recent-user-status.status-inactive {
    background: #f59e0b;
}

.recent-user-status.status-suspended {
    background: #ef4444;
}

@media (max-width: 768px) {
    .user-stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
(function() {
    'use strict';
    
    // Clear form fields when user is successfully created
    const successAlert = document.getElementById('successAlert');
    const createUserForm = document.getElementById('createUserForm');
    
    if (successAlert && createUserForm) {
        // Clear all form fields
        createUserForm.reset();
        
        // Also clear any values that might be set via PHP (for extra safety)
        const formInputs = createUserForm.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="number"], select');
        formInputs.forEach(function(input) {
            if (input.type === 'select-one') {
                // Reset select to default value
                if (input.id === 'role') {
                    input.value = 'employee';
                } else if (input.id === 'status') {
                    input.value = 'active';
                }
            } else {
                input.value = '';
            }
        });
    }
    
    // Auto-hide success alerts after 5 seconds
    if (successAlert && successAlert.classList.contains('portal-alert-auto-hide')) {
        setTimeout(function() {
            if (successAlert && successAlert.parentNode) {
                successAlert.classList.add('portal-alert-hiding');
                setTimeout(function() {
                    if (successAlert && successAlert.parentNode) {
                        successAlert.remove();
                    }
                }, 300); // Wait for fade out animation
            }
        }, 5000); // Show for 5 seconds
    }
    
    // Auto-hide error alerts after 8 seconds (longer for errors)
    const errorAlert = document.getElementById('errorAlert');
    if (errorAlert) {
        setTimeout(function() {
            if (errorAlert && errorAlert.parentNode) {
                errorAlert.classList.add('portal-alert-hiding');
                setTimeout(function() {
                    if (errorAlert && errorAlert.parentNode) {
                        errorAlert.remove();
                    }
                }, 300);
            }
        }, 8000);
    }
    
    // Auto-hide warning alerts after 10 seconds (longer for password display)
    const warningAlert = document.getElementById('warningAlert');
    if (warningAlert) {
        setTimeout(function() {
            if (warningAlert && warningAlert.parentNode) {
                warningAlert.classList.add('portal-alert-hiding');
                setTimeout(function() {
                    if (warningAlert && warningAlert.parentNode) {
                        warningAlert.remove();
                    }
                }, 300);
            }
        }, 10000);
    }
})();
</script>
