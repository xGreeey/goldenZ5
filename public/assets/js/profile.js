/**
 * My Profile — Edit/view toggles, flash toast from server, copy 2FA secret.
 * Forms submit via POST; server redirects with success/error in URL; we show toast from flash data.
 */
(function () {
    'use strict';

    var page = document.querySelector('[data-profile-page]');
    if (!page) return;

    var toast = document.getElementById('profileToast');
    var toastMessage = document.getElementById('profileToastMessage');

    function showToast(message, isError) {
        if (!toast || !toastMessage) return;
        toastMessage.textContent = message;
        toast.classList.remove('profile-toast-hidden');
        toast.classList.add('profile-toast-visible');
        if (isError) {
            toast.classList.add('profile-toast-error');
        } else {
            toast.classList.remove('profile-toast-error');
        }
        setTimeout(function () {
            toast.classList.remove('profile-toast-visible');
            toast.classList.add('profile-toast-hidden');
        }, 3200);
    }

    function enterEdit(sectionId) {
        var card = page.querySelector('[data-profile-section-content="' + sectionId + '"]');
        if (card) {
            card = card.closest('.profile-section-card');
        }
        if (card) {
            card.classList.add('profile-section-edit');
            var editEl = card.querySelector('[data-profile-edit="' + sectionId + '"]');
            if (editEl) editEl.classList.remove('profile-edit-hidden');
        }
    }

    function exitEdit(sectionId) {
        var card = page.querySelector('[data-profile-section-content="' + sectionId + '"]');
        if (card) {
            card = card.closest('.profile-section-card');
        }
        if (card) {
            card.classList.remove('profile-section-edit');
            var editEl = card.querySelector('[data-profile-edit="' + sectionId + '"]');
            if (editEl) editEl.classList.add('profile-edit-hidden');
        }
    }

    // Flash from server (success/error in data attributes after redirect)
    var flash = document.getElementById('profile-flash');
    if (flash) {
        var success = flash.getAttribute('data-success');
        var err = flash.getAttribute('data-error');
        if (err) {
            showToast(err, true);
        } else if (success) {
            var labels = {
                personal: 'Personal information saved.',
                account: 'Account information saved.',
                security: 'Password updated.',
                '2fa_disable': 'Two-factor authentication disabled.'
            };
            showToast(labels[success] || 'Saved.', false);
        }
    }

    // Edit trigger buttons
    page.querySelectorAll('.profile-edit-trigger').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var section = this.getAttribute('data-profile-section');
            if (section) enterEdit(section);
        });
    });

    // Cancel buttons (no form submit; just exit edit mode)
    page.querySelectorAll('.profile-cancel').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var section = this.getAttribute('data-profile-cancel');
            if (section) exitEdit(section);
        });
    });

    // Forms submit normally (POST); no preventDefault
    // Optional: show toast on submit in case of slow redirect (optional enhancement)

    // Copy 2FA secret button
    page.querySelectorAll('.profile-copy-secret').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.getAttribute('data-copy-target');
            var el = id ? document.getElementById(id) : null;
            if (el) {
                var text = el.textContent || '';
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(function () {
                        showToast('Secret key copied to clipboard.', false);
                    });
                } else {
                    var ta = document.createElement('textarea');
                    ta.value = text;
                    ta.setAttribute('readonly', '');
                    ta.style.position = 'absolute';
                    ta.style.left = '-9999px';
                    document.body.appendChild(ta);
                    ta.select();
                    try {
                        document.execCommand('copy');
                        showToast('Secret key copied to clipboard.', false);
                    } catch (e) {}
                    document.body.removeChild(ta);
                }
            }
        });
    });
})();
