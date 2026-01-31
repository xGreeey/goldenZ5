/**
 * My Profile — toast from server flash, edit/cancel toggles, copy 2FA secret.
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
        if (card) card = card.closest('.profile-section-card');
        if (card) {
            card.classList.add('profile-section-edit');
            var editEl = card.querySelector('[data-profile-edit="' + sectionId + '"]');
            if (editEl) editEl.classList.remove('profile-edit-hidden');
        }
    }

    function exitEdit(sectionId) {
        var card = page.querySelector('[data-profile-section-content="' + sectionId + '"]');
        if (card) card = card.closest('.profile-section-card');
        if (card) {
            card.classList.remove('profile-section-edit');
            var editEl = card.querySelector('[data-profile-edit="' + sectionId + '"]');
            if (editEl) editEl.classList.add('profile-edit-hidden');
        }
    }

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

    page.querySelectorAll('.profile-edit-trigger').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var section = this.getAttribute('data-profile-section');
            if (section) enterEdit(section);
        });
    });

    page.querySelectorAll('.profile-cancel').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var section = this.getAttribute('data-profile-cancel');
            if (section) exitEdit(section);
        });
    });

    // 2FA Disable: button opens modal; password entered in modal; Confirm submits form
    var disableBtn = document.getElementById('profile-2fa-disable-btn');
    var disableForm = document.getElementById('profile-2fa-disable-form');
    var disableModal = document.getElementById('profile2faDisableModal');
    var disablePasswordInput = document.getElementById('profile-2fa-disable-password');
    var disablePasswordError = document.getElementById('profile2faDisablePasswordError');
    var disableModalCancel = document.getElementById('profile2faDisableModalCancel');
    var disableModalClose = document.getElementById('profile2faDisableModalClose');
    var disableModalBackdrop = document.getElementById('profile2faDisableModalBackdrop');

    function open2faDisableModal() {
        if (disablePasswordInput) {
            disablePasswordInput.value = '';
            disablePasswordInput.focus();
        }
        if (disablePasswordError) {
            disablePasswordError.textContent = '';
            disablePasswordError.style.display = 'none';
        }
        if (disableModal) disableModal.classList.remove('profile-modal-hidden');
    }
    function close2faDisableModal() {
        if (disableModal) disableModal.classList.add('profile-modal-hidden');
    }

    if (disableBtn && disableModal) {
        disableBtn.addEventListener('click', open2faDisableModal);
    }
    if (disableForm && disableModal) {
        disableForm.addEventListener('submit', function (e) {
            var password = disablePasswordInput ? disablePasswordInput.value.trim() : '';
            if (disablePasswordError) {
                disablePasswordError.textContent = '';
                disablePasswordError.style.display = 'none';
            }
            if (!password) {
                e.preventDefault();
                if (disablePasswordError) {
                    disablePasswordError.textContent = 'Enter your password to disable two-factor authentication.';
                    disablePasswordError.style.display = 'block';
                }
                if (disablePasswordInput) disablePasswordInput.focus();
            }
        });
        if (disableModalCancel) disableModalCancel.addEventListener('click', close2faDisableModal);
        if (disableModalClose) disableModalClose.addEventListener('click', close2faDisableModal);
        if (disableModalBackdrop) disableModalBackdrop.addEventListener('click', close2faDisableModal);
        document.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape' && disableModal && !disableModal.classList.contains('profile-modal-hidden')) {
                close2faDisableModal();
            }
        });
    }

    page.querySelectorAll('.profile-copy-secret').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.getAttribute('data-copy-target');
            var el = id ? document.getElementById(id) : null;
            if (el) {
                var text = (el.textContent || '').trim();
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
