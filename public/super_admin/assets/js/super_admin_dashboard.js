/**
 * =============================================================================
 * SUPER ADMIN DASHBOARD — Permission engine & UI gating
 * =============================================================================
 * - hasPermission('x'), hasAny(['a','b']), hasAll(['a','b'])
 * - Mock payload: window.sadashConfig.permissions / currentUser (from server later)
 * - Hides/shows sections, disables buttons, shows "No access" for restricted panels.
 * =============================================================================
 */

(function () {
    'use strict';

    // -------------------------------------------------------------------------
    // Permission payload (from server session / API later)
    // -------------------------------------------------------------------------
    var config = window.sadashConfig || {};
    var currentUser = config.currentUser || { role: 'super_admin', permissions: [] };
    var permissions = Array.isArray(config.permissions) ? config.permissions : [];

    /**
     * Check if user has a single permission.
     * @param {string} perm - Permission string (e.g. 'users.manage')
     * @returns {boolean}
     */
    function hasPermission(perm) {
        if (!perm) return false;
        return permissions.indexOf(perm) !== -1;
    }

    /**
     * Check if user has any of the given permissions.
     * @param {string[]} perms - Array of permission strings
     * @returns {boolean}
     */
    function hasAny(perms) {
        if (!Array.isArray(perms) || perms.length === 0) return false;
        return perms.some(function (p) { return hasPermission(p); });
    }

    /**
     * Check if user has all of the given permissions.
     * @param {string[]} perms - Array of permission strings
     * @returns {boolean}
     */
    function hasAll(perms) {
        if (!Array.isArray(perms) || perms.length === 0) return false;
        return perms.every(function (p) { return hasPermission(p); });
    }

    /**
     * Parse data-permission or data-permission-any from element.
     * data-permission: single permission required
     * data-permission-any: pipe-separated list, any one required
     */
    function getRequiredPermission(el) {
        var single = el.getAttribute('data-permission');
        if (single) return { type: 'single', value: single };
        var any = el.getAttribute('data-permission-any');
        if (any) return { type: 'any', value: any.split('|').map(function (s) { return s.trim(); }) };
        return null;
    }

    function canShowElement(el) {
        var req = getRequiredPermission(el);
        if (!req) return true;
        if (req.type === 'single') return hasPermission(req.value);
        if (req.type === 'any') return hasAny(req.value);
        return false;
    }

    function canEnableAction(el) {
        var perm = el.getAttribute('data-permission');
        if (!perm) return true;
        return hasPermission(perm);
    }

    /**
     * Apply permission gating: sections, nav, buttons.
     * For content panels: show "No access" message inside panel when no permission (keep layout).
     */
    function applyGating() {
        // Sections with data-permission or data-permission-any (content panels)
        var sections = document.querySelectorAll('[data-permission], [data-permission-any]');
        sections.forEach(function (section) {
            var id = section.id;
            var noAccessEl = id ? document.getElementById(id.replace(/-panel$/, '-no-access')) : null;
            if (!noAccessEl && id === 'sadash-access-panel') noAccessEl = document.getElementById('sadash-access-no-access');
            if (!noAccessEl && id === 'sadash-audit-panel') noAccessEl = document.getElementById('sadash-audit-no-access');
            if (!noAccessEl && id === 'sadash-reports-panel') noAccessEl = document.getElementById('sadash-reports-no-access');
            if (!noAccessEl && id === 'sadash-search-panel') noAccessEl = document.getElementById('sadash-search-no-access');

            var hasAccess = canShowElement(section);
            if (hasAccess) {
                section.classList.remove('sadash-hidden-by-permission');
                if (noAccessEl) noAccessEl.hidden = true;
                section.querySelectorAll('.sadash-panel-content').forEach(function (el) { el.hidden = false; });
            } else {
                /* Keep section visible; hide content and show "No access" message */
                section.classList.remove('sadash-hidden-by-permission');
                if (noAccessEl) noAccessEl.hidden = false;
                section.querySelectorAll('.sadash-panel-content').forEach(function (el) { el.hidden = true; });
            }
        });

        // Buttons/links with data-permission (disable or hide if no permission)
        var actions = document.querySelectorAll('.sadash-action, .sadash-search-btn');
        actions.forEach(function (btn) {
            var perm = btn.getAttribute('data-permission');
            if (!perm) return;
            if (hasPermission(perm)) {
                btn.classList.remove('sadash-disabled-by-permission');
                btn.removeAttribute('aria-disabled');
                if (btn.tagName === 'A') btn.removeAttribute('tabindex');
            } else {
                btn.classList.add('sadash-disabled-by-permission');
                btn.setAttribute('aria-disabled', 'true');
                if (btn.tagName === 'A') {
                    btn.setAttribute('tabindex', '-1');
                    btn.addEventListener('click', function (e) { e.preventDefault(); });
                }
            }
        });

        // Sidebar nav: items with data-sadash-permission or data-sadash-permission-any
        var navLinks = document.querySelectorAll('[data-sadash-permission], [data-sadash-permission-any]');
        navLinks.forEach(function (link) {
            var perm = link.getAttribute('data-sadash-permission');
            var any = link.getAttribute('data-sadash-permission-any');
            var show = false;
            if (any) {
                var list = any.split('|').map(function (s) { return s.trim(); });
                show = hasAny(list);
            } else if (perm) {
                show = hasPermission(perm);
            }
            var item = link.closest('.portal-sidebar-item');
            if (item) {
                if (show) item.classList.remove('sadash-hidden-by-permission');
                else item.classList.add('sadash-hidden-by-permission');
            }
        });
    }

    /**
     * Render mock data into placeholders (stub for future backend).
     */
    function renderMockData() {
        // Placeholder: when backend is ready, replace with API response and update
        // elements with data-sadash-mock or IDs. No-op for now beyond what PHP already outputs.
    }

    // Expose helpers globally for use elsewhere (e.g. other SA pages)
    window.sadashPermissions = {
        hasPermission: hasPermission,
        hasAny: hasAny,
        hasAll: hasAll,
        permissions: permissions,
        currentUser: currentUser
    };

    // Run on DOM ready
    function init() {
        applyGating();
        renderMockData();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
