<?php
/**
 * My Profile — shared content for all roles.
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

$profile_action = htmlspecialchars($base_url ?? '') . '?page=profile';
$profile_post_url = ($base_url ?? '') . '/profile-post';
$csrf = function_exists('csrf_field') ? csrf_field() : '';
?>
<div class="portal-page portal-page-profile profile-page" data-profile-page>
    <?php if (!empty($profile_success) || !empty($profile_error)): ?>
    <div id="profile-flash" class="profile-flash-data" data-success="<?php echo htmlspecialchars($profile_success ?? ''); ?>" data-error="<?php echo htmlspecialchars($profile_error ?? ''); ?>"></div>
    <?php endif; ?>

    <header class="portal-page-header profile-page-header">
        <nav class="portal-breadcrumb" aria-label="Breadcrumb">
            <ol class="portal-breadcrumb-list">
                <li class="portal-breadcrumb-item"><a href="<?php echo htmlspecialchars($base_url ?? ''); ?>?page=dashboard">Dashboard</a></li>
                <li class="portal-breadcrumb-item portal-breadcrumb-current" aria-current="page">My Profile</li>
            </ol>
        </nav>
    </header>

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

    <?php if (!empty($profile_2fa_setup) && !empty($profile_2fa_secret)): ?>
    <?php
    $twofa_issuer = 'GoldenZ5';
    $twofa_label = ($current_user['email'] ?? $current_user['username'] ?? 'User');
    $twofa_otpauth = 'otpauth://totp/' . rawurlencode($twofa_issuer) . ':' . rawurlencode($twofa_label) . '?secret=' . rawurlencode($profile_2fa_secret) . '&issuer=' . rawurlencode($twofa_issuer);
    $twofa_qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . rawurlencode($twofa_otpauth);
    ?>
    <div class="profile-2fa-setup portal-alert portal-alert-success portal-mb-24" role="alert">
        <div class="portal-alert-icon"><i class="fas fa-check" aria-hidden="true"></i></div>
        <div class="portal-alert-body">
            <p class="portal-alert-message"><strong>Two-factor authentication is now enabled.</strong> Scan the QR code with your authenticator app, or enter the secret key manually. Save your recovery codes — they will not be shown again.</p>
            <div class="profile-2fa-setup-content">
                <div class="profile-2fa-qr-box">
                    <label class="profile-2fa-secret-label">Scan with your app</label>
                    <img src="<?php echo htmlspecialchars($twofa_qr_url); ?>" width="200" height="200" alt="QR code for authenticator app" class="profile-2fa-qr-img">
                </div>
                <div class="profile-2fa-secret-box">
                    <label class="profile-2fa-secret-label">Or enter this secret manually</label>
                    <code class="profile-2fa-secret-value" id="profile2faSecret"><?php echo htmlspecialchars($profile_2fa_secret); ?></code>
                    <button type="button" class="portal-btn portal-btn-sm portal-btn-ghost profile-copy-secret" data-copy-target="profile2faSecret">Copy</button>
                </div>
                <div class="profile-2fa-recovery-box">
                    <label class="profile-2fa-secret-label">Recovery codes (save these)</label>
                    <ul class="profile-2fa-recovery-list">
                        <?php foreach ($profile_2fa_recovery_codes ?? [] as $code): ?>
                        <li><code><?php echo htmlspecialchars($code); ?></code></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="profile-grid">
        <section class="profile-section-card portal-card" id="personal-section" aria-labelledby="personal-heading">
            <div class="profile-card-header">
                <h2 id="personal-heading" class="profile-card-title">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    Personal Information
                </h2>
                <button type="button" class="portal-btn portal-btn-sm portal-btn-ghost profile-edit-trigger" data-profile-section="personal">Edit</button>
            </div>
            <div class="profile-card-body" data-profile-section-content="personal">
                <dl class="profile-dl">
                    <dt>Full name</dt>
                    <dd><?php echo htmlspecialchars($display_name); ?></dd>
                    <dt>Email</dt>
                    <dd><?php echo htmlspecialchars($email); ?></dd>
                    <dt>Phone</dt>
                    <dd><?php echo htmlspecialchars($phone ?: '—'); ?></dd>
                </dl>
                <div class="profile-edit-hidden" data-profile-edit="personal">
                    <form method="post" action="<?php echo $profile_post_url; ?>" class="profile-edit-form">
                        <?php echo $csrf; ?>
                        <input type="hidden" name="profile_section" value="personal">
                        <div class="portal-form-group">
                            <label for="profile-name">Full name</label>
                            <input type="text" id="profile-name" name="name" class="portal-input" value="<?php echo htmlspecialchars($display_name); ?>" required>
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-email">Email</label>
                            <input type="email" id="profile-email" name="email" class="portal-input" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-phone">Phone</label>
                            <input type="text" id="profile-phone" name="phone" class="portal-input" value="<?php echo htmlspecialchars($phone); ?>">
                        </div>
                        <div class="profile-form-actions">
                            <button type="submit" class="portal-btn portal-btn-primary">Save</button>
                            <button type="button" class="portal-btn portal-btn-secondary profile-cancel" data-profile-cancel="personal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="profile-section-card portal-card" id="account-section" aria-labelledby="account-heading">
            <div class="profile-card-header">
                <h2 id="account-heading" class="profile-card-title">
                    <i class="fas fa-id-card" aria-hidden="true"></i>
                    Account Information
                </h2>
                <button type="button" class="portal-btn portal-btn-sm portal-btn-ghost profile-edit-trigger" data-profile-section="account">Edit</button>
            </div>
            <div class="profile-card-body" data-profile-section-content="account">
                <dl class="profile-dl">
                    <dt>Username</dt>
                    <dd><?php echo htmlspecialchars($current_user['username'] ?? ''); ?></dd>
                    <dt>Created</dt>
                    <dd><?php echo htmlspecialchars($created_at_formatted); ?></dd>
                    <dt>Last login</dt>
                    <dd><?php echo htmlspecialchars($last_login_formatted); ?></dd>
                </dl>
                <div class="profile-edit-hidden" data-profile-edit="account">
                    <form method="post" action="<?php echo $profile_post_url; ?>" class="profile-edit-form">
                        <?php echo $csrf; ?>
                        <input type="hidden" name="profile_section" value="account">
                        <div class="portal-form-group">
                            <label for="profile-username">Username</label>
                            <input type="text" id="profile-username" name="username" class="portal-input" value="<?php echo htmlspecialchars($current_user['username'] ?? ''); ?>" required>
                        </div>
                        <div class="profile-form-actions">
                            <button type="submit" class="portal-btn portal-btn-primary">Save</button>
                            <button type="button" class="portal-btn portal-btn-secondary profile-cancel" data-profile-cancel="account">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="profile-section-card portal-card" id="security-section" aria-labelledby="security-heading">
            <div class="profile-card-header">
                <h2 id="security-heading" class="profile-card-title">
                    <i class="fas fa-lock" aria-hidden="true"></i>
                    Security
                </h2>
                <button type="button" class="portal-btn portal-btn-sm portal-btn-ghost profile-edit-trigger" data-profile-section="security">Change password</button>
            </div>
            <div class="profile-card-body" data-profile-section-content="security">
                <p class="portal-text-muted">Update your password. Use a strong password with uppercase, lowercase, number and symbol.</p>
                <p class="profile-2fa-status-line <?php echo $two_factor_enabled ? 'profile-2fa-status-enabled' : 'profile-2fa-status-disabled'; ?>" role="status">
                    <i class="fas <?php echo $two_factor_enabled ? 'fa-check-circle' : 'fa-times-circle'; ?>" aria-hidden="true"></i>
                    <span><?php echo $two_factor_enabled ? 'Two-factor Authentication Enabled' : 'Two-factor Authentication Disabled'; ?></span>
                </p>
                <div class="profile-edit-hidden" data-profile-edit="security">
                    <form method="post" action="<?php echo $profile_post_url; ?>" class="profile-edit-form">
                        <?php echo $csrf; ?>
                        <input type="hidden" name="profile_section" value="security">
                        <div class="portal-form-group">
                            <label for="profile-current-password">Current password</label>
                            <input type="password" id="profile-current-password" name="current_password" class="portal-input" autocomplete="current-password" required>
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-new-password">New password</label>
                            <input type="password" id="profile-new-password" name="new_password" class="portal-input" autocomplete="new-password" required minlength="8">
                        </div>
                        <div class="portal-form-group">
                            <label for="profile-confirm-password">Confirm new password</label>
                            <input type="password" id="profile-confirm-password" name="confirm_password" class="portal-input" autocomplete="new-password" required minlength="8">
                        </div>
                        <div class="profile-form-actions">
                            <button type="submit" class="portal-btn portal-btn-primary">Update password</button>
                            <button type="button" class="portal-btn portal-btn-secondary profile-cancel" data-profile-cancel="security">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="profile-section-card portal-card" id="two-factor-section" aria-labelledby="2fa-heading">
            <div class="profile-card-header">
                <h2 id="2fa-heading" class="profile-card-title">
                    <i class="fas fa-mobile-alt" aria-hidden="true"></i>
                    Two-factor authentication
                </h2>
            </div>
            <div class="profile-card-body">
                <?php if ($two_factor_enabled): ?>
                <p class="profile-2fa-status portal-text-muted">Two-factor authentication is enabled. You will be asked for a code when signing in.</p>
                <button type="button" class="portal-btn portal-btn-secondary" id="profile-2fa-disable-btn">Disable two-factor authentication</button>
                <?php else: ?>
                <p class="profile-2fa-status portal-text-muted">Add an extra layer of security by enabling two-factor authentication. You will need an authenticator app (e.g. Google Authenticator).</p>
                <form method="post" action="<?php echo $profile_post_url; ?>">
                    <?php echo $csrf; ?>
                    <input type="hidden" name="profile_section" value="2fa_enable">
                    <button type="submit" class="portal-btn portal-btn-primary"><i class="fas fa-lock" aria-hidden="true"></i> Enable two-factor authentication</button>
                </form>
                <?php endif; ?>
            </div>
        </section>

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

    <div class="profile-toast profile-toast-hidden" id="profileToast" role="status" aria-live="polite" aria-atomic="true">
        <i class="fas fa-check-circle profile-toast-icon" aria-hidden="true"></i>
        <span class="profile-toast-message" id="profileToastMessage">Saved</span>
    </div>

    <!-- 2FA Disable: modal with password form (password entered in popup only) -->
    <div class="profile-modal-overlay profile-modal-hidden" id="profile2faDisableModal" role="dialog" aria-modal="true" aria-labelledby="profile2faDisableModalTitle" aria-describedby="profile2faDisableModalDesc">
        <div class="profile-modal-backdrop" id="profile2faDisableModalBackdrop"></div>
        <div class="profile-modal-dialog">
            <form method="post" action="<?php echo htmlspecialchars($profile_post_url); ?>" id="profile-2fa-disable-form" class="profile-2fa-disable-form" data-profile-action="<?php echo htmlspecialchars($profile_post_url); ?>">
                <?php echo $csrf; ?>
                <input type="hidden" name="profile_section" value="2fa_disable">
                <input type="hidden" name="_2fa_disable" value="1">
                <div class="profile-modal-header">
                    <h3 id="profile2faDisableModalTitle" class="profile-modal-title">Disable two-factor authentication?</h3>
                    <button type="button" class="profile-modal-close" id="profile2faDisableModalClose" aria-label="Close">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="profile-modal-body">
                    <p id="profile2faDisableModalDesc" class="profile-modal-message">Enter your password to confirm. You will only need your password to sign in after this.</p>
                    <div class="portal-form-group profile-modal-password-group">
                        <label for="profile-2fa-disable-password">Password</label>
                        <input type="password" id="profile-2fa-disable-password" class="portal-input" name="password" autocomplete="current-password" required placeholder="Enter your password">
                        <span class="profile-2fa-password-error" id="profile2faDisablePasswordError" role="alert" style="display:none; font-size: 0.875rem; color: var(--hr-danger); margin-top: 0.25rem;"></span>
                    </div>
                </div>
                <div class="profile-modal-footer">
                    <button type="button" class="portal-btn portal-btn-ghost" id="profile2faDisableModalCancel">Cancel</button>
                    <button type="submit" class="portal-btn portal-btn-secondary" id="profile2faDisableModalConfirm">Disable two-factor authentication</button>
                </div>
            </form>
        </div>
    </div>
</div>
