/**
 * Theme init: apply saved theme before first paint (no flash).
 * Load this in <head> with no defer/async. All other JS in portal.js.
 */
(function () {
    'use strict';
    try {
        if (localStorage.getItem('portal-theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    } catch (_) {}
})();
