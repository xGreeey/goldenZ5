/**
 * My Profile — Edit/view toggles and save/cancel feedback (UI only).
 * No backend calls; visual feedback only.
 */
(function () {
    'use strict';

    var page = document.querySelector('[data-profile-page]');
    if (!page) return;

    var toast = document.getElementById('profileToast');
    var toastMessage = document.getElementById('profileToastMessage');

    function showToast(message) {
        if (!toast || !toastMessage) return;
        toastMessage.textContent = message;
        toast.classList.remove('profile-toast-hidden');
        toast.classList.add('profile-toast-visible');
        setTimeout(function () {
            toast.classList.remove('profile-toast-visible');
            toast.classList.add('profile-toast-hidden');
        }, 2800);
    }

    function enterEdit(sectionId) {
        var card = page.querySelector('[data-profile-section-content="' + sectionId + '"]')?.closest('.profile-section-card');
        if (card) {
            card.classList.add('profile-section-edit');
            card.querySelector('[data-profile-edit="' + sectionId + '"]')?.classList.remove('profile-edit-hidden');
        }
    }

    function exitEdit(sectionId) {
        var card = page.querySelector('[data-profile-section-content="' + sectionId + '"]')?.closest('.profile-section-card');
        if (card) {
            card.classList.remove('profile-section-edit');
            card.querySelector('[data-profile-edit="' + sectionId + '"]')?.classList.add('profile-edit-hidden');
        }
    }

    // Edit trigger buttons
    page.querySelectorAll('.profile-edit-trigger').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var section = this.getAttribute('data-profile-section');
            if (section) enterEdit(section);
        });
    });

    // Cancel buttons
    page.querySelectorAll('.profile-cancel').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var section = this.getAttribute('data-profile-cancel');
            if (section) exitEdit(section);
        });
    });

    // Form submit (prevent default, show toast, exit edit)
    page.querySelectorAll('.profile-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var section = this.getAttribute('data-profile-form');
            showToast('Changes saved');
            if (section) exitEdit(section);
        });
    });
})();
