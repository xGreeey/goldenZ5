<?php
/**
 * My Profile — shared content for all roles (Super Admin, Admin, HR).
 * Expects: $base_url, $current_user (name, username, role, email?, phone?, department?, created_at?, last_login?, two_factor_enabled?).
 * Optional: $role_permissions, $profile_success, $profile_error, $profile_2fa_setup, $profile_2fa_secret, $profile_2fa_recovery_codes.
 */
$display_name = !empty($current_user['name']) ? $current_user['name'] : $current_user['username'];
$role_label = !empty($current_user['role']) ? ucfirst(str_replace('_', ' ', $current_user['role'])) : 'User';
$email = $current_user['email'] ?? $current_user['username'] . '@example.com';
$phone = $current_user['phone'] ?? '';
$department = $current_user['department'] ?? null;
$created_at = $current_user['created_at'] ?? null;
$last_login = $current_user['last_login'] ?? null;
$two_factor_enabled = !empty($current_user['two_factor_enabled']);
$role_permissions = $role_permissions ?? ['Dashboard access', 'View reports', 'Manage profile'];

$created_at_formatted = $created_at ? date('M j, Y', strtotime($created_at)) : '—';
$last_login_formatted = $last_login ? date('M j, Y g:i A', strtotime($last_login)) : '—';

$profile_action = htmlspecialchars($base_url) . '?page=profile';
$csrf = function_exists('csrf_field') ? csrf_field() : '';
?>
<div class="portal-page portal-page-profile profile-page" data-profile-page>
    <?php if (!empty($profile_success) || !empty($profile_error)): ?>
    <div id="profile-flash" class="profile-flash-data" data-success="<?php echo htmlspecialchars($profile_success ?? ''); ?>" data-error="<?php echo htmlspecialchars($profile_error ?? ''); ?>"></div>
    <?php endif; ?>

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

    <?php if ($profile_2fa_setup && !empty($profile_2fa_secret)): ?>
    <?php
    $twofa_issuer = 'GoldenZ5';
    $twofa_label = ($current_user['email'] ?? $current_user['username'] ?? 'User');
    $twofa_otpauth = 'otpauth://totp/' . rawurlencode($twofa_issuer) . ':' . rawurlencode($twofa_label) . '?secret=' . rawurlencode($profile_2fa_secret) . '&issuer=' . rawurlencode($twofa_issuer);
    $twofa_qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . rawurlencode($twofa_otpauth);
    ?>
    <!-- One-time 2FA setup: QR code + secret and recovery codes (show once after enable) -->
    <div class="profile-2fa-setup portal-alert portal-alert-success portal-mb-24" role="alert">
        <div class="portal-alert-icon"><i class="fas fa-check" aria-hidden="true"></i></div>
        <div class="portal-alert-body">
            <p class="portal-alert-message"><strong>Two-factor authentication is now enabled.</strong> Scan the QR code with your authenticator app, or enter the secret key manually. Save your recovery codes in a safe place — they will not be shown again.</p>
            <div class="profile-2fa-setup-content">
                <div class="profile-2fa-qr-box">
                    <label class="profile-2fa-secret-label">Scan with your app</label>
                    <img src="<?php echo htmlspecialchars($twofa_qr_url); ?>" width="200" height="200" alt="QR code for authenticator app" class="profile-2fa-qr-img">
                </div>
                <div class="profile-2fa-codes-box">
                    <div class="profile-2fa-secret-box">
                        <label class="profile-2fa-secret-label">Or enter secret key manually</label>
                        <code class="profile-2fa-secret-value" id="profile2faSecret"><?php echo htmlspecialchars($profile_2fa_secret); ?></code>
                        <button type="button" class="portal-btn portal-btn-ghost portal-btn-sm profile-copy-secret" data-copy-target="profile2faSecret" aria-label="Copy secret key"><i class="fas fa-copy" aria-hidden="true"></i> Copy</button>
                    </div>
                    <div class="profile-2fa-recovery-box">
                        <label class="profile-2fa-recovery-label">Recovery codes</label>
                        <ul class="profile-2fa-recovery-list">
                            <?php foreach ($profile_2fa_recovery_codes as $code): ?>
                            <li><code><?php echo htmlspecialchars($code); ?></code></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

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
                        <dd><?php echo $phone !== '' ? htmlspecialchars($phone) : '<span class="profile-value-placeholder">—</span>'; ?></dd>
                    </dl>
                </div>
                <div class="profile-edit profile-edit-hidden" data-profile-edit="personal">
                    <form class="portal-form profile-form" method="post" action="<?php echo $profile_action; ?>" data-profile-form="personal" aria-label="Edit personal information">
                        <?php echo $csrf; ?>
                        <input type="hidden" name="profile_section" value="personal">
                        <div class="portal-form-group">
                            <label for="profile-full-name">Full name</label>
                            <input type="text" id="profile-full-name" class="portal-input" name="name" value="<?php echo htmlspecialchars($display_name); ?>" autocomplete="name" required>
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-email">Email</label>
                            <input type="email" id="profile-email" class="portal-input" name="email" value="<?php echo htmlspecialchars($email); ?>" autocomplete="email" required>
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-phone">Phone</label>
                            <input type="tel" id="profile-phone" class="portal-input" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="+1 234 567 8900" autocomplete="tel">
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
                        <dd><?php echo $created_at_formatted; ?></dd>
                        <dt>Last login</dt>
                        <dd><?php echo $last_login_formatted; ?></dd>
                    </dl>
                </div>
                <div class="profile-edit profile-edit-hidden" data-profile-edit="account">
                    <form class="portal-form profile-form" method="post" action="<?php echo $profile_action; ?>" data-profile-form="account" aria-label="Edit account information">
                        <?php echo $csrf; ?>
                        <input type="hidden" name="profile_section" value="account">
                        <div class="portal-form-group">
                            <label for="profile-username">Username</label>
                            <input type="text" id="profile-username" class="portal-input" name="username" value="<?php echo htmlspecialchars($current_user['username'] ?? ''); ?>" autocomplete="username" required>
                        </div>
                        <p class="profile-field-hint portal-text-muted">Account created and last login are read-only.</p>
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
                    Update password
                </button>
            </div>
            <div class="profile-card-body" data-profile-section-content="security">
                <div class="profile-view" data-profile-view="security">
                    <dl class="profile-dl">
                        <dt>Password</dt>
                        <dd><span class="profile-value-muted">••••••••</span></dd>
                        <dt>Two-factor authentication</dt>
                        <dd>
                            <?php if ($two_factor_enabled): ?>
                            <span class="portal-badge portal-badge-success"><i class="fas fa-check" aria-hidden="true"></i> Enabled</span>
                            <?php else: ?>
                            <span class="portal-badge portal-badge-weak">Not enabled</span>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
                <div class="profile-edit profile-edit-hidden" data-profile-edit="security">
                    <form class="portal-form profile-form" method="post" action="<?php echo $profile_action; ?>" data-profile-form="security" aria-label="Change password">
                        <?php echo $csrf; ?>
                        <input type="hidden" name="profile_section" value="security">
                        <div class="portal-form-group">
                            <label for="profile-current-password">Current password</label>
                            <input type="password" id="profile-current-password" class="portal-input" name="current_password" autocomplete="current-password" required>
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-new-password">New password</label>
                            <input type="password" id="profile-new-password" class="portal-input" name="new_password" autocomplete="new-password" required minlength="8">
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-confirm-password">Confirm new password</label>
                            <input type="password" id="profile-confirm-password" class="portal-input" name="confirm_password" autocomplete="new-password" required>
                        </div>
                        <p class="profile-field-hint portal-text-muted">Password must be at least 8 characters and include uppercase, lowercase, number and symbol.</p>
                        <div class="profile-form-actions">
                            <button type="submit" class="portal-btn portal-btn-primary profile-save">Update password</button>
                            <button type="button" class="portal-btn portal-btn-secondary profile-cancel" data-profile-cancel="security">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Two-factor authentication -->
        <section class="profile-section-card portal-card" id="two-factor-section" aria-labelledby="2fa-heading">
            <div class="profile-card-header">
                <h2 id="2fa-heading" class="profile-card-title">
                    <i class="fas fa-mobile-alt" aria-hidden="true"></i>
                    Two-factor authentication
                </h2>
            </div>
            <div class="profile-card-body">
                <?php if ($two_factor_enabled): ?>
                <p class="profile-2fa-status portal-text-muted">Two-factor authentication is enabled. You will be asked for a code from your authenticator app when signing in.</p>
                <form method="post" action="<?php echo $profile_action; ?>" class="profile-2fa-disable-form" onsubmit="return confirm('Disable two-factor authentication? You will only need your password to sign in.');">
                    <?php echo $csrf; ?>
                    <input type="hidden" name="profile_section" value="2fa_disable">
                    <div class="portal-form-group" style="max-width: 280px;">
                        <label for="profile-2fa-disable-password">Enter your password to disable</label>
                        <input type="password" id="profile-2fa-disable-password" class="portal-input" name="password" autocomplete="current-password" required>
                    </div>
                    <button type="submit" class="portal-btn portal-btn-secondary">Disable two-factor authentication</button>
                </form>
                <?php else: ?>
                <p class="profile-2fa-status portal-text-muted">Add an extra layer of security by enabling two-factor authentication. You will need an authenticator app (e.g. Google Authenticator).</p>
                <form method="post" action="" id="profile-2fa-enable-form">
                    <?php echo $csrf; ?>
                    <input type="hidden" name="profile_section" value="2fa_enable">
                    <button type="submit" class="portal-btn portal-btn-primary" id="profile-2fa-enable-btn"><i class="fas fa-lock" aria-hidden="true"></i> Enable two-factor authentication</button>
                </form>
                <?php endif; ?>
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
