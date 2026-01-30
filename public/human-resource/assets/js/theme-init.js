/**
 * Theme init: apply saved theme before first paint (no flash).
 * Load this in <head> with no defer/async. All other JS in hr-admin.js.
 */
(function () {
    'use strict';
    try {
        if (localStorage.getItem('hr-admin-theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    } catch (_) {}
})();
