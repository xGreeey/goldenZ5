/**
 * Forgot Password Form - JavaScript
 * Handles form validation and submission for password reset requests
 */
(function () {
    'use strict';
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initForgotPassword);
    } else {
        initForgotPassword();
    }
    
    function initForgotPassword() {
        const form = document.getElementById('forgotPasswordForm');
        if (!form) return;
        
        const usernameInput = document.getElementById('forgot_username');
        const usernameError = document.getElementById('forgotUsernameError');
        const submitBtn = document.getElementById('forgotSubmitBtn');
        
        function showError(el, message) {
            if (!el) return;
            el.textContent = message;
            if (message) {
                el.classList.add('show');
            } else {
                el.classList.remove('show');
            }
        }
        
        function validateUsername() {
            const value = usernameInput ? usernameInput.value.trim() : '';
            if (value.length === 0) {
                showError(usernameError, 'Please enter your username.');
                if (usernameInput) usernameInput.setAttribute('aria-invalid', 'true');
                return false;
            }
            showError(usernameError, '');
            if (usernameInput) usernameInput.setAttribute('aria-invalid', 'false');
            return true;
        }
        
        if (usernameInput) {
            usernameInput.addEventListener('blur', function () {
                if (usernameInput.value.trim().length > 0) {
                    validateUsername();
                }
            });
            
            usernameInput.addEventListener('input', function () {
                if (usernameError && usernameError.textContent) {
                    validateUsername();
                }
            });
        }
        
        form.addEventListener('submit', function (e) {
            if (!validateUsername()) {
                e.preventDefault();
                if (usernameInput) usernameInput.focus();
                return;
            }
            
            if (submitBtn) {
                const btnText = submitBtn.querySelector('.btn-text');
                const btnSpinner = submitBtn.querySelector('.btn-spinner');
                if (btnText) btnText.classList.add('d-none');
                if (btnSpinner) btnSpinner.classList.remove('d-none');
                submitBtn.disabled = true;
            }
        });
    }
})();
