<?php
/**
 * Roles & Permissions — Role list (left), permission matrix (right), preview of visible tabs.
 * Permission-based visibility; toggles save via roles-api.php.
 */

$page_title = 'Roles & Permissions';

// Path resolution: roles.php is at public/super_admin/pages/ — app root is one level above public
$possibleRoots = [
    dirname(__DIR__, 3),  // pages -> super_admin -> public -> project root
    dirname(__DIR__, 2),  // pages -> super_admin -> public (if docroot is project root)
];
$appRoot = null;
foreach ($possibleRoots as $root) {
    if (is_file($root . '/config/database.php')) {
        $appRoot = $root;
        break;
    }
}
if (!$appRoot) {
    $appRoot = dirname(__DIR__, 3);
}
require_once $appRoot . '/config/database.php';
require_once $appRoot . '/includes/permissions.php';

try {
    $roles = permissions_available_roles();
    $permissions_grouped = permissions_get_all_grouped();
} catch (Throwable $e) {
    $roles = [];
    $permissions_grouped = [];
}

$role_permissions = [];
foreach ($roles as $role) {
    try {
        $codes = permissions_get_for_role($role);
    } catch (Throwable $e) {
        $codes = [];
    }
    $ids = [];
    foreach ($permissions_grouped as $perms) {
        foreach ($perms as $p) {
            if (in_array($p['code'], $codes, true)) {
                $ids[] = $p['id'];
            }
        }
    }
    $role_permissions[$role] = $ids;
}

$modules_order = (require $appRoot . '/config/permissions.php')['modules_order'];
$csrf_token = csrf_token();
$base_url = '/super_admin';
$permissions_migration_missing = empty($permissions_grouped);
?>

<div class="portal-page portal-page-roles rp-panel" id="rolesPermissionsPanel">
    <div class="portal-dashboard-header rp-header">
        <div class="portal-dashboard-welcome">
            <h1 class="portal-dashboard-user-name">Roles & Permissions</h1>
            <span class="portal-dashboard-greeting">Assign granular permissions to control dashboard tab and module visibility</span>
        </div>
    </div>

    <?php if ($permissions_migration_missing): ?>
    <div class="rp-alert rp-alert-warning" role="alert">
        <i class="fas fa-database" aria-hidden="true"></i>
        <div>
            <strong>Permissions tables not found.</strong> Run the migration <code>storage/migrations/001_permissions_and_role_permissions.sql</code> to create <code>permissions</code> and <code>role_permissions</code>, then refresh this page.
        </div>
    </div>
    <?php endif; ?>

    <div class="rp-layout">
        <!-- Left: Role list -->
        <aside class="rp-roles-col">
            <div class="rp-card rp-roles-card">
                <h2 class="rp-card-title">
                    <i class="fas fa-user-shield" aria-hidden="true"></i>
                    Roles
                </h2>
                <ul class="rp-role-list" id="rpRoleList" role="tablist">
                    <?php foreach ($roles as $role): ?>
                    <li class="rp-role-item" role="presentation">
                        <button type="button"
                                class="rp-role-btn <?php echo $role === 'super_admin' ? 'rp-role-btn-active' : ''; ?>"
                                data-role="<?php echo htmlspecialchars($role); ?>"
                                role="tab"
                                aria-selected="<?php echo $role === 'super_admin' ? 'true' : 'false'; ?>"
                                id="rp-tab-<?php echo htmlspecialchars($role); ?>">
                            <span class="rp-role-badge"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $role))); ?></span>
                            <span class="rp-role-count" data-role-count="<?php echo htmlspecialchars($role); ?>">
                                <?php echo count($role_permissions[$role] ?? []); ?> permissions
                            </span>
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>

        <!-- Right: Permission matrix + preview -->
        <div class="rp-permissions-col">
            <div class="rp-card rp-matrix-card">
                <div class="rp-matrix-header">
                    <h2 class="rp-card-title">
                        <i class="fas fa-key" aria-hidden="true"></i>
                        Permissions for <span id="rpSelectedRoleLabel">Super Admin</span>
                    </h2>
                    <button type="button" class="portal-btn portal-btn-primary rp-save-btn" id="rpSaveBtn" disabled>
                        <i class="fas fa-save" aria-hidden="true"></i>
                        Save changes
                    </button>
                </div>

                <div class="rp-matrix-content" id="rpMatrixContent">
                    <?php foreach ($modules_order as $module): ?>
                        <?php if (empty($permissions_grouped[$module])) continue; ?>
                    <section class="rp-module-group" data-module="<?php echo htmlspecialchars($module); ?>">
                        <h3 class="rp-module-title">
                            <i class="fas fa-folder" aria-hidden="true"></i>
                            <?php echo htmlspecialchars($module); ?>
                        </h3>
                        <ul class="rp-permission-list">
                            <?php foreach ($permissions_grouped[$module] as $perm): ?>
                            <li class="rp-permission-item">
                                <label class="rp-toggle-label">
                                    <input type="checkbox"
                                           class="rp-toggle-input"
                                           name="permission_ids[]"
                                           value="<?php echo (int) $perm['id']; ?>"
                                           data-code="<?php echo htmlspecialchars($perm['code']); ?>"
                                           data-label="<?php echo htmlspecialchars($perm['label']); ?>"
                                           <?php echo in_array($perm['id'], $role_permissions['super_admin'] ?? [], true) ? 'checked' : ''; ?>>
                                    <span class="rp-toggle-switch" aria-hidden="true"></span>
                                    <span class="rp-toggle-text">
                                        <strong><?php echo htmlspecialchars($perm['label']); ?></strong>
                                        <?php if (!empty($perm['description'])): ?>
                                        <span class="rp-toggle-desc"><?php echo htmlspecialchars($perm['description']); ?></span>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Preview: visible tabs for selected role -->
            <div class="rp-card rp-preview-card">
                <h3 class="rp-preview-title">
                    <i class="fas fa-eye" aria-hidden="true"></i>
                    Dashboard tabs visible for this role
                </h3>
                <p class="rp-preview-hint">Toggles above control which sidebar tabs and modules users with this role will see.</p>
                <div class="rp-preview-tabs" id="rpPreviewTabs">
                    <span class="rp-preview-empty" id="rpPreviewEmpty">No permissions selected — users will see an empty dashboard state.</span>
                    <ul class="rp-preview-list" id="rpPreviewList" aria-live="polite"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="rp-toast rp-toast-success" id="rpToastSuccess" role="status" aria-live="polite" hidden>
        <i class="fas fa-check-circle" aria-hidden="true"></i>
        <span>Permissions saved successfully.</span>
    </div>
    <div class="rp-toast rp-toast-error" id="rpToastError" role="alert" hidden>
        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
        <span id="rpToastErrorText">Failed to save.</span>
    </div>
</div>

<script>
(function() {
    'use strict';

    var baseUrl = <?php echo json_encode($base_url); ?>;
    var csrfToken = <?php echo json_encode($csrf_token); ?>;
    var roles = <?php echo json_encode($roles); ?>;
    var rolePermissions = <?php echo json_encode($role_permissions); ?>;
    var permissionsGrouped = <?php echo json_encode($permissions_grouped); ?>;

    var selectedRole = 'super_admin';
    var roleListEl = document.getElementById('rpRoleList');
    var matrixContent = document.getElementById('rpMatrixContent');
    var selectedRoleLabel = document.getElementById('rpSelectedRoleLabel');
    var saveBtn = document.getElementById('rpSaveBtn');
    var previewList = document.getElementById('rpPreviewList');
    var previewEmpty = document.getElementById('rpPreviewEmpty');
    var toastSuccess = document.getElementById('rpToastSuccess');
    var toastError = document.getElementById('rpToastError');
    var toastErrorText = document.getElementById('rpToastErrorText');
    var savingInProgress = false;

    // Ensure toasts are hidden on load (no notification until user saves)
    if (toastSuccess) toastSuccess.hidden = true;
    if (toastError) toastError.hidden = true;

    function getCheckedPermissionIds() {
        var checkboxes = matrixContent.querySelectorAll('.rp-toggle-input');
        var ids = [];
        checkboxes.forEach(function(cb) { if (cb.checked) ids.push(parseInt(cb.value, 10)); });
        return ids;
    }

    function getCheckedLabels() {
        var checkboxes = matrixContent.querySelectorAll('.rp-toggle-input:checked');
        var labels = [];
        checkboxes.forEach(function(cb) { labels.push(cb.getAttribute('data-label')); });
        return labels;
    }

    function updatePreview() {
        var labels = getCheckedLabels();
        previewList.innerHTML = '';
        if (labels.length === 0) {
            previewEmpty.hidden = false;
            previewList.hidden = true;
        } else {
            previewEmpty.hidden = true;
            previewList.hidden = false;
            labels.forEach(function(label) {
                var li = document.createElement('li');
                li.className = 'rp-preview-item';
                li.textContent = label;
                previewList.appendChild(li);
            });
        }
    }

    function updateRoleCount(role, count) {
        var el = document.querySelector('[data-role-count="' + role + '"]');
        if (el) el.textContent = count + ' permission' + (count !== 1 ? 's' : '');
    }

    function setRole(role) {
        selectedRole = role;
        selectedRoleLabel.textContent = role.replace(/_/g, ' ').replace(/\b\w/g, function(c) { return c.toUpperCase(); });

        roleListEl.querySelectorAll('.rp-role-btn').forEach(function(btn) {
            var isActive = btn.getAttribute('data-role') === role;
            btn.classList.toggle('rp-role-btn-active', isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        var ids = rolePermissions[role] || [];
        matrixContent.querySelectorAll('.rp-toggle-input').forEach(function(cb) {
            var id = parseInt(cb.value, 10);
            cb.checked = ids.indexOf(id) !== -1;
        });

        updatePreview();
        updateDirtyState();
    }

    function isDirty() {
        var current = getCheckedPermissionIds().sort(function(a,b) { return a - b; });
        var saved = (rolePermissions[selectedRole] || []).sort(function(a,b) { return a - b; });
        if (current.length !== saved.length) return true;
        for (var i = 0; i < current.length; i++) if (current[i] !== saved[i]) return true;
        return false;
    }

    function updateDirtyState() {
        saveBtn.disabled = !isDirty();
    }

    matrixContent.addEventListener('change', function() {
        updatePreview();
        updateDirtyState();
    });

    roleListEl.addEventListener('click', function(e) {
        var btn = e.target.closest('.rp-role-btn');
        if (!btn) return;
        e.preventDefault();
        setRole(btn.getAttribute('data-role'));
    });

    saveBtn.addEventListener('click', function() {
        if (saveBtn.disabled || savingInProgress) return;
        var ids = getCheckedPermissionIds();
        savingInProgress = true;
        saveBtn.disabled = true;
        // Hide any previous toasts so only one message shows for this save
        toastSuccess.hidden = true;
        toastError.hidden = true;

        var formData = new FormData();
        formData.append('csrf_token', csrfToken);
        formData.append('role', selectedRole);
        ids.forEach(function(id) { formData.append('permission_ids[]', id); });

        fetch(baseUrl + '/roles-api.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
        .then(function(result) {
            if (result.ok && result.data.success) {
                rolePermissions[selectedRole] = ids;
                updateRoleCount(selectedRole, ids.length);
                toastSuccess.hidden = false;
                toastError.hidden = true;
                setTimeout(function() { toastSuccess.hidden = true; }, 3000);
            } else {
                toastErrorText.textContent = result.data.error || 'Failed to save';
                toastError.hidden = false;
                toastSuccess.hidden = true;
            }
        })
        .catch(function() {
            toastErrorText.textContent = 'Network error';
            toastError.hidden = false;
            toastSuccess.hidden = true;
        })
        .finally(function() {
            savingInProgress = false;
            updateDirtyState();
        });
    });

    updatePreview();
    updateDirtyState();
})();
</script>

<style>
/* Roles & Permissions — clean panel, gold accent, smooth toggles */
.portal-page-roles { padding-bottom: 2rem; }
.rp-header { margin-bottom: 1.5rem; }
.rp-header .portal-dashboard-greeting { color: var(--hr-text-muted); font-size: 0.9rem; }

.rp-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.5rem;
    align-items: start;
}
@media (max-width: 900px) {
    .rp-layout { grid-template-columns: 1fr; }
}

.rp-card {
    background: var(--hr-bg-main);
    border: 1px solid var(--hr-border);
    border-radius: var(--hr-radius);
    box-shadow: var(--hr-shadow);
    overflow: hidden;
}
.rp-roles-card { padding: 1rem 0; }
.rp-card-title {
    margin: 0 0 1rem 0;
    padding: 0 1.25rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--hr-text);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.rp-card-title i { color: rgba(212, 175, 55, 0.9); }

.rp-role-list { list-style: none; padding: 0; margin: 0; }
.rp-role-item { margin: 0; }
.rp-role-btn {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    width: 100%;
    padding: 0.75rem 1.25rem;
    border: none;
    background: transparent;
    color: var(--hr-text);
    font-size: 0.9rem;
    text-align: left;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease;
    border-left: 3px solid transparent;
}
.rp-role-btn:hover { background: var(--hr-neutral-light); }
.rp-role-btn-active {
    background: rgba(212, 175, 55, 0.08);
    border-left-color: #b8941f;
    font-weight: 600;
}
.rp-role-badge { display: block; text-transform: capitalize; }
.rp-role-count { font-size: 0.75rem; color: var(--hr-text-muted); margin-top: 0.2rem; }

.rp-matrix-card { padding: 1.25rem; }
.rp-matrix-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.rp-matrix-content { display: flex; flex-direction: column; gap: 1.25rem; }
.rp-module-group { border: 1px solid var(--hr-border); border-radius: var(--hr-radius); overflow: hidden; }
.rp-module-title {
    margin: 0;
    padding: 0.6rem 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--hr-text-muted);
    background: var(--hr-neutral-light);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.rp-module-title i { opacity: 0.8; }
.rp-permission-list { list-style: none; padding: 0.5rem 0; margin: 0; }
.rp-permission-item { margin: 0; }
.rp-toggle-label {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.6rem 1rem;
    cursor: pointer;
    transition: background 0.15s ease;
}
.rp-toggle-label:hover { background: var(--hr-neutral-light); }
.rp-toggle-input { position: absolute; opacity: 0; width: 0; height: 0; }
.rp-toggle-switch {
    flex-shrink: 0;
    width: 2.75rem;
    height: 1.4rem;
    border-radius: 999px;
    background: var(--hr-border);
    position: relative;
    transition: background 0.25s ease, box-shadow 0.2s ease;
}
.rp-toggle-switch::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: calc(1.4rem - 4px);
    height: calc(1.4rem - 4px);
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    transition: transform 0.25s ease;
}
.rp-toggle-input:checked + .rp-toggle-switch {
    background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.25);
}
.rp-toggle-input:checked + .rp-toggle-switch::after { transform: translateX(1.35rem); }
.rp-toggle-input:focus-visible + .rp-toggle-switch { outline: 2px solid rgba(212, 175, 55, 0.5); outline-offset: 2px; }
.rp-toggle-text { display: flex; flex-direction: column; gap: 0.2rem; }
.rp-toggle-text strong { font-size: 0.9rem; color: var(--hr-text); }
.rp-toggle-desc { font-size: 0.8rem; color: var(--hr-text-muted); }

.rp-preview-card { padding: 1.25rem; margin-top: 1rem; }
.rp-preview-title {
    margin: 0 0 0.35rem 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--hr-text);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.rp-preview-title i { color: var(--hr-info); }
.rp-preview-hint { margin: 0 0 0.75rem 0; font-size: 0.8rem; color: var(--hr-text-muted); }
.rp-preview-tabs { min-height: 2.5rem; }
.rp-preview-empty {
    font-size: 0.85rem;
    color: var(--hr-text-muted);
    font-style: italic;
}
.rp-preview-list { list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 0.5rem; }
.rp-preview-item {
    display: inline-block;
    padding: 0.35rem 0.65rem;
    background: var(--hr-success-light);
    color: var(--hr-success-text);
    border-radius: var(--hr-radius);
    font-size: 0.8rem;
    font-weight: 500;
}

.rp-save-btn { min-width: 120px; }
.rp-toast {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: var(--hr-radius);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    font-weight: 500;
    box-shadow: var(--hr-shadow);
    animation: rp-toast-in 0.3s ease;
}
/* Ensure toasts are truly hidden when hidden attribute is set (overrides display: flex) */
.rp-toast[hidden] {
    display: none !important;
}
@keyframes rp-toast-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.rp-toast-success { background: var(--hr-success-light); color: var(--hr-success-text); }
.rp-toast-error { background: var(--hr-danger-light); color: var(--hr-danger-text); }

.rp-alert {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    border-radius: var(--hr-radius);
    border: 1px solid;
}
.rp-alert i { flex-shrink: 0; margin-top: 0.15rem; }
.rp-alert-warning {
    background: var(--hr-warning-light);
    border-color: var(--hr-warning);
    color: var(--hr-warning-text);
}
.rp-alert code { font-size: 0.85em; padding: 0.15em 0.4em; background: rgba(0,0,0,0.06); border-radius: 4px; }
</style>
