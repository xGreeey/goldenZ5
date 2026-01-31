<?php
/**
 * My Profile — shared content for all roles (Super Admin, Admin, HR).
 * Expects: $base_url, $current_user (name, username, role, email?, department?).
 * Optional: $role_permissions (array of permission labels for Role & Access Summary).
 */
$display_name = !empty($current_user['name']) ? $current_user['name'] : $current_user['username'];
$role_label = !empty($current_user['role']) ? ucfirst(str_replace('_', ' ', $current_user['role'])) : 'User';
$email = $current_user['email'] ?? $current_user['username'] . '@example.com';
$department = $current_user['department'] ?? null;
$role_permissions = $role_permissions ?? ['Dashboard access', 'View reports', 'Manage profile'];
?>
<div class="portal-page portal-page-profile profile-page" data-profile-page>
    <header class="portal-page-header profile-page-header">
        <nav class="portal-breadcrumb" aria-label="Breadcrumb">
            <ol class="portal-breadcrumb-list">
                <li class="portal-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url); ?>?page=dashboard">Dashboard</a></li>
                <li class="portal-breadcrumb-item portal-breadcrumb-current" aria-current="page">My Profile</li>
            </ol>
        </nav>
    </header>

    <!-- Profile header: avatar, name, role badge, basic identifiers -->
    <section class="profile-header" aria-labelledby="profile-heading">
        <div class="profile-header-inner">
            <div class="profile-header-avatar" aria-hidden="true">
                <i class="fas fa-user" aria-hidden="true"></i>
            </div>
            <div class="profile-header-info">
                <h1 id="profile-heading" class="profile-header-name"><?php echo htmlspecialchars($display_name); ?></h1>
                <div class="profile-header-meta">
                    <span class="profile-header-badge portal-badge portal-badge-weak">
                        <i class="fas fa-user-shield" aria-hidden="true"></i>
                        <?php echo htmlspecialchars($role_label); ?>
                    </span>
                    <?php if ($department): ?>
                    <span class="profile-header-identifier">
                        <i class="fas fa-building" aria-hidden="true"></i>
                        <?php echo htmlspecialchars($department); ?>
                    </span>
                    <?php endif; ?>
                    <span class="profile-header-identifier">
                        <i class="fas fa-at" aria-hidden="true"></i>
                        <?php echo htmlspecialchars($current_user['username'] ?? ''); ?>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <div class="profile-grid">
        <!-- Personal Information -->
        <section class="profile-section-card portal-card" id="personal-information" aria-labelledby="personal-info-heading">
            <div class="profile-card-header">
                <h2 id="personal-info-heading" class="profile-card-title">
                    <i class="fas fa-user-circle" aria-hidden="true"></i>
                    Personal Information
                </h2>
                <button type="button" class="portal-btn portal-btn-ghost portal-btn-sm profile-edit-trigger" data-profile-section="personal" aria-label="Edit personal information">
                    <i class="fas fa-pen" aria-hidden="true"></i>
                    Edit
                </button>
            </div>
            <div class="profile-card-body" data-profile-section-content="personal">
                <div class="profile-view" data-profile-view="personal">
                    <dl class="profile-dl">
                        <dt>Full name</dt>
                        <dd><?php echo htmlspecialchars($display_name); ?></dd>
                        <dt>Email</dt>
                        <dd><?php echo htmlspecialchars($email); ?></dd>
                        <dt>Phone</dt>
                        <dd class="profile-value-placeholder">—</dd>
                    </dl>
                </div>
                <div class="profile-edit profile-edit-hidden" data-profile-edit="personal">
                    <form class="portal-form profile-form" data-profile-form="personal" aria-label="Edit personal information">
                        <div class="portal-form-group">
                            <label for="profile-full-name">Full name</label>
                            <input type="text" id="profile-full-name" class="portal-input" name="full_name" value="<?php echo htmlspecialchars($display_name); ?>" autocomplete="name">
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-email">Email</label>
                            <input type="email" id="profile-email" class="portal-input" name="email" value="<?php echo htmlspecialchars($email); ?>" autocomplete="email">
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-phone">Phone</label>
                            <input type="tel" id="profile-phone" class="portal-input" name="phone" placeholder="+1 234 567 8900" autocomplete="tel">
                        </div>
                        <div class="profile-form-actions">
                            <button type="submit" class="portal-btn portal-btn-primary profile-save">Save</button>
                            <button type="button" class="portal-btn portal-btn-secondary profile-cancel" data-profile-cancel="personal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Account Information -->
        <section class="profile-section-card portal-card" id="account-information" aria-labelledby="account-info-heading">
            <div class="profile-card-header">
                <h2 id="account-info-heading" class="profile-card-title">
                    <i class="fas fa-id-card" aria-hidden="true"></i>
                    Account Information
                </h2>
                <button type="button" class="portal-btn portal-btn-ghost portal-btn-sm profile-edit-trigger" data-profile-section="account" aria-label="Edit account information">
                    <i class="fas fa-pen" aria-hidden="true"></i>
                    Edit
                </button>
            </div>
            <div class="profile-card-body" data-profile-section-content="account">
                <div class="profile-view" data-profile-view="account">
                    <dl class="profile-dl">
                        <dt>Username</dt>
                        <dd><?php echo htmlspecialchars($current_user['username'] ?? '—'); ?></dd>
                        <dt>Account created</dt>
                        <dd class="profile-value-placeholder">—</dd>
                        <dt>Last login</dt>
                        <dd class="profile-value-placeholder">—</dd>
                    </dl>
                </div>
                <div class="profile-edit profile-edit-hidden" data-profile-edit="account">
                    <form class="portal-form profile-form" data-profile-form="account" aria-label="Edit account information">
                        <div class="portal-form-group">
                            <label for="profile-username">Username</label>
                            <input type="text" id="profile-username" class="portal-input" name="username" value="<?php echo htmlspecialchars($current_user['username'] ?? ''); ?>" autocomplete="username">
                        </div>
                        <p class="profile-field-hint portal-text-muted">Account metadata is read-only from your administrator.</p>
                        <div class="profile-form-actions">
                            <button type="submit" class="portal-btn portal-btn-primary profile-save">Save</button>
                            <button type="button" class="portal-btn portal-btn-secondary profile-cancel" data-profile-cancel="account">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Security Settings -->
        <section class="profile-section-card portal-card" id="security-settings" aria-labelledby="security-heading">
            <div class="profile-card-header">
                <h2 id="security-heading" class="profile-card-title">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    Security Settings
                </h2>
                <button type="button" class="portal-btn portal-btn-ghost portal-btn-sm profile-edit-trigger" data-profile-section="security" aria-label="Change password">
                    <i class="fas fa-pen" aria-hidden="true"></i>
                    Update
                </button>
            </div>
            <div class="profile-card-body" data-profile-section-content="security">
                <div class="profile-view" data-profile-view="security">
                    <dl class="profile-dl">
                        <dt>Password</dt>
                        <dd><span class="profile-value-muted">••••••••</span></dd>
                        <dt>Two-factor authentication</dt>
                        <dd class="profile-value-placeholder">Not enabled</dd>
                    </dl>
                </div>
                <div class="profile-edit profile-edit-hidden" data-profile-edit="security">
                    <form class="portal-form profile-form" data-profile-form="security" aria-label="Update security settings">
                        <div class="portal-form-group">
                            <label for="profile-current-password">Current password</label>
                            <input type="password" id="profile-current-password" class="portal-input" name="current_password" autocomplete="current-password">
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-new-password">New password</label>
                            <input type="password" id="profile-new-password" class="portal-input" name="new_password" autocomplete="new-password">
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-confirm-password">Confirm new password</label>
                            <input type="password" id="profile-confirm-password" class="portal-input" name="confirm_password" autocomplete="new-password">
                        </div>
                        <div class="profile-form-actions">
                            <button type="submit" class="portal-btn portal-btn-primary profile-save">Update password</button>
                            <button type="button" class="portal-btn portal-btn-secondary profile-cancel" data-profile-cancel="security">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Role & Access Summary (read-only) -->
        <section class="profile-section-card portal-card profile-section-readonly" id="role-access" aria-labelledby="role-access-heading">
            <div class="profile-card-header">
                <h2 id="role-access-heading" class="profile-card-title">
                    <i class="fas fa-key" aria-hidden="true"></i>
                    Role &amp; Access Summary
                </h2>
            </div>
            <div class="profile-card-body">
                <dl class="profile-dl">
                    <dt>Role</dt>
                    <dd>
                        <span class="profile-header-badge portal-badge portal-badge-weak">
                            <?php echo htmlspecialchars($role_label); ?>
                        </span>
                    </dd>
                    <dt>Permissions</dt>
                    <dd>
                        <ul class="profile-permissions-list" aria-label="Your permissions">
                            <?php foreach ($role_permissions as $perm): ?>
                            <li><i class="fas fa-check-circle profile-permission-icon" aria-hidden="true"></i><?php echo htmlspecialchars($perm); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </dd>
                </dl>
            </div>
        </section>
    </div>

    <!-- Toast for save/cancel feedback (hidden by default) -->
    <div class="profile-toast profile-toast-hidden" id="profileToast" role="status" aria-live="polite" aria-atomic="true">
        <i class="fas fa-check-circle profile-toast-icon" aria-hidden="true"></i>
        <span class="profile-toast-message" id="profileToastMessage">Saved</span>
    </div>
</div>
