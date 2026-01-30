/**
 * Golden Z-5 HR System – Forgot password page
 * Form validation and submit state • forgot_password.js
 * assets/js/forgot_password.js
 */

(function () {
  'use strict';

  const form = document.getElementById('forgotPasswordForm');
  const usernameInput = document.getElementById('forgot_username');
  const usernameError = document.getElementById('forgotUsernameError');
  const submitBtn = document.getElementById('forgotSubmitBtn');

  if (!form) return;

  function showError(el, message) {
    if (!el) return;
    el.textContent = message;
    el.style.display = message ? 'block' : 'none';
  }

  function setInvalid(input, invalid) {
    if (!input) return;
    if (invalid) {
      input.classList.add('forgot-input--touched');
      input.setAttribute('aria-invalid', 'true');
    } else {
      input.classList.remove('forgot-input--touched');
      input.setAttribute('aria-invalid', 'false');
    }
  }

  function validateUsername() {
    const value = (usernameInput && usernameInput.value) ? usernameInput.value.trim() : '';
    if (value.length === 0) {
      showError(usernameError, 'Please enter your username.');
      setInvalid(usernameInput, true);
      return false;
    }
    showError(usernameError, '');
    setInvalid(usernameInput, false);
    return true;
  }

  if (usernameInput) {
    usernameInput.addEventListener('blur', function () {
      if (usernameInput.value.trim().length > 0) {
        validateUsername();
      } else {
        showError(usernameError, '');
        setInvalid(usernameInput, false);
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
      if (usernameInput) {
        usernameInput.focus();
      }
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
})();
