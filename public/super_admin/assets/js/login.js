/**
 * Golden Z-5 HR System – Login page scripts
 * public/assets/js/login.js
 */
(function () {
  'use strict';

  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
  }

  function onDOMReady(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  onDOMReady(function () {
    // Fill password from remembered password (server-set data attribute)
    var passwordField = document.getElementById('password');
    if (passwordField && passwordField.dataset.rememberedPassword) {
      passwordField.value = passwordField.dataset.rememberedPassword;
      setTimeout(function () {
        passwordField.removeAttribute('data-remembered-password');
      }, 100);
    }

    // Prevent zoom (keyboard, wheel, pinch)
    document.addEventListener('keydown', function (e) {
      if ((e.ctrlKey || e.metaKey) && (e.key === '+' || e.key === '-' || e.key === '=' || e.key === '0' || e.keyCode === 187 || e.keyCode === 189 || e.keyCode === 48)) {
        e.preventDefault();
        return false;
      }
    });

    document.addEventListener('wheel', function (e) {
      if (e.ctrlKey || e.metaKey) {
        e.preventDefault();
        return false;
      }
    }, { passive: false });

    var lastTouchEnd = 0;
    document.addEventListener('touchend', function (e) {
      var now = Date.now();
      if (now - lastTouchEnd <= 300) e.preventDefault();
      lastTouchEnd = now;
    }, { passive: false });

    document.addEventListener('scroll', function () {
      window.scrollTo(0, 0);
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    }, { passive: false });

    function lockScroll() {
      window.scrollTo(0, 0);
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    }
    setInterval(lockScroll, 10);

    window.addEventListener('resize', lockScroll);

    // System info modal
    var seeMoreBtn = document.getElementById('seeMoreBtn');
    var systemInfoModal = document.getElementById('systemInfoModal');
    var systemInfoOverlay = document.getElementById('systemInfoOverlay');
    var closeModalBtn = document.getElementById('closeModalBtn');

    function openSystemModal() {
      if (seeMoreBtn) {
        seeMoreBtn.classList.add('animating');
        setTimeout(function () { seeMoreBtn.classList.remove('animating'); }, 800);
      }
      setTimeout(function () {
        if (systemInfoModal) {
          systemInfoModal.classList.add('active');
          document.body.style.overflow = 'hidden';
          if (closeModalBtn) closeModalBtn.focus();
        }
      }, 200);
    }

    function closeSystemModal() {
      if (systemInfoModal) systemInfoModal.classList.remove('active');
      setTimeout(function () { document.body.style.overflow = ''; }, 400);
    }

    if (seeMoreBtn) {
      seeMoreBtn.addEventListener('click', openSystemModal);
      seeMoreBtn.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); seeMoreBtn.click(); }
      });
    }
    if (closeModalBtn) {
      closeModalBtn.addEventListener('click', closeSystemModal);
      closeModalBtn.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); closeSystemModal(); }
      });
    }
    if (systemInfoOverlay) systemInfoOverlay.addEventListener('click', closeSystemModal);

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && systemInfoModal && systemInfoModal.classList.contains('active')) closeSystemModal();
    });

    if (systemInfoModal) {
      var focusable = systemInfoModal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
      var firstF = focusable[0], lastF = focusable[focusable.length - 1];
      systemInfoModal.addEventListener('keydown', function (e) {
        if (e.key !== 'Tab') return;
        if (e.shiftKey) {
          if (document.activeElement === firstF) { e.preventDefault(); lastF.focus(); }
        } else {
          if (document.activeElement === lastF) { e.preventDefault(); firstF.focus(); }
        }
      });
    }

    // Password visibility toggles
    function bindPasswordToggle(btnId, inputId, iconId) {
      var btn = document.getElementById(btnId);
      var input = document.getElementById(inputId);
      var icon = document.getElementById(iconId);
      if (!btn || !input) return;
      btn.addEventListener('click', function () {
        var type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        if (icon) {
          icon.classList.toggle('fa-eye');
          icon.classList.toggle('fa-eye-slash');
        }
      });
    }
    bindPasswordToggle('togglePassword', 'password', 'togglePasswordIcon');
    bindPasswordToggle('toggleNewPassword', 'new_password', 'toggleNewPasswordIcon');
    bindPasswordToggle('toggleConfirmPassword', 'confirm_password', 'toggleConfirmPasswordIcon');

    // Password change form validation
    var passwordChangeForm = document.getElementById('passwordChangeForm');
    var newPasswordInput = document.getElementById('new_password');
    var confirmPasswordInput = document.getElementById('confirm_password');
    if (passwordChangeForm && newPasswordInput && confirmPasswordInput) {
      passwordChangeForm.addEventListener('submit', function (e) {
        var newP = newPasswordInput.value, confirmP = confirmPasswordInput.value;
        if (newP.length < 8) {
          e.preventDefault();
          alert('Password must be at least 8 characters long.');
          return false;
        }
        if (newP !== confirmP) {
          e.preventDefault();
          alert('Passwords do not match. Please try again.');
          return false;
        }
        var changePasswordBtn = document.getElementById('changePasswordBtn');
        if (changePasswordBtn) {
          changePasswordBtn.disabled = true;
          changePasswordBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating Password...';
        }
      });
    }

    // Close validation alert
    var closeAlertBtn = document.getElementById('closeAlert');
    if (closeAlertBtn) {
      closeAlertBtn.addEventListener('click', function () {
        var validationAlert = document.getElementById('validationAlert');
        if (validationAlert) validationAlert.classList.add('d-none');
      });
    }

    // Reset password link loading state
    var resetPasswordLink = document.getElementById('resetPasswordLink');
    if (resetPasswordLink) {
      resetPasswordLink.addEventListener('click', function () {
        this.classList.add('loading');
        var linkText = this.querySelector('.link-text');
        if (linkText) {
          linkText.setAttribute('data-original', linkText.textContent);
          linkText.textContent = 'Redirecting...';
        }
      });
    }

    // Login form submit (AJAX)
    var loginForm = document.getElementById('loginForm');
    if (!loginForm) return;

    loginForm.addEventListener('submit', function (e) {
      var usernameEl = document.getElementById('username');
      var passwordEl = document.getElementById('password');
      var username = usernameEl ? usernameEl.value.trim() : '';
      var password = passwordEl ? passwordEl.value : '';

      e.preventDefault();

      var validationAlert = document.getElementById('validationAlert');
      var alertTitle = document.getElementById('alertTitle');
      var alertMessage = document.getElementById('alertMessage');

      if (!username || !password) {
        if (validationAlert && alertTitle && alertMessage) {
          alertTitle.textContent = 'Required Fields Missing';
          alertMessage.textContent = 'Please enter both username and password to continue.';
          validationAlert.classList.remove('d-none');
          setTimeout(function () { validationAlert.classList.add('d-none'); }, 5000);
        }
        if (!username && usernameEl) usernameEl.focus();
        else if (passwordEl) passwordEl.focus();
        return false;
      }

      var submitBtn = document.getElementById('submitBtn');
      var btnText = submitBtn ? submitBtn.querySelector('.btn-text') : null;
      var spinner = document.getElementById('submitSpinner');
      var authCard = document.querySelector('.auth-form-card');
      var errorAlert = document.getElementById('loginErrorAlert');
      var errorMessage = document.getElementById('loginErrorMessage');

      if (submitBtn) {
        submitBtn.disabled = true;
        if (btnText) btnText.textContent = 'Signing in...';
        if (spinner) spinner.classList.remove('d-none');
      }
      if (errorAlert) errorAlert.classList.add('d-none');

      document.body.classList.add('login-transition-active');

      var spinnerOverlay = document.querySelector('.login-spinner-overlay');
      if (!spinnerOverlay) {
        spinnerOverlay = document.createElement('div');
        spinnerOverlay.className = 'login-spinner-overlay';
        spinnerOverlay.innerHTML = '<div class="login-spinner-container"><div class="login-spinner"></div><p class="login-spinner-text">Logging in…</p></div>';
        document.body.appendChild(spinnerOverlay);
        void spinnerOverlay.offsetHeight;
        requestAnimationFrame(function () {
          requestAnimationFrame(function () { spinnerOverlay.classList.add('active'); });
        });
      } else {
        spinnerOverlay.classList.remove('fade-out', 'active');
        void spinnerOverlay.offsetHeight;
        requestAnimationFrame(function () {
          requestAnimationFrame(function () { spinnerOverlay.classList.add('active'); });
        });
      }

      function resetLoadingState() {
        if (submitBtn) {
          submitBtn.disabled = false;
          if (btnText) btnText.textContent = 'Sign In';
          if (spinner) spinner.classList.add('d-none');
        }
        document.body.classList.remove('login-transition-active');
        var overlay = document.querySelector('.login-spinner-overlay');
        if (overlay) {
          overlay.classList.add('fade-out');
          setTimeout(function () { overlay.remove(); }, 250);
        }
      }

      function shakeLoginCard() {
        if (!authCard) return;
        authCard.classList.remove('shake');
        void authCard.offsetWidth;
        authCard.classList.add('shake');
        setTimeout(function () { authCard.classList.remove('shake'); }, 650);
      }

      var formData = new FormData(loginForm);
      fetch(loginForm.getAttribute('action') || window.location.href, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        credentials: 'same-origin'
      })
        .then(function (res) { return res.json().catch(function () { return null; }); })
        .then(function (data) {
          if (!data) throw new Error('Invalid JSON response');

          if (data.success && data.redirect) {
            setTimeout(function () { window.location.href = data.redirect; }, 850);
            return;
          }

          resetLoadingState();
          shakeLoginCard();

          if (data.error === 'status' && data.status && data.message) {
            var modal = document.getElementById('statusErrorModal');
            var messageEl = document.getElementById('statusErrorMessage');
            var titleEl = document.getElementById('statusErrorTitle');
            var iconEl = document.getElementById('statusErrorIcon');
            var infoBoxEl = document.getElementById('statusErrorInfoBox');
            var infoTextEl = document.getElementById('statusErrorInfoText');

            if (modal && messageEl && titleEl && iconEl && infoBoxEl && infoTextEl && window.bootstrap) {
              messageEl.textContent = data.message;
              if (data.status === 'inactive') {
                titleEl.textContent = 'Account Inactive';
                iconEl.className = 'fas fa-pause-circle futuristic-status-icon';
                iconEl.style.color = '#94a3b8';
                infoBoxEl.style.borderColor = 'rgba(148, 163, 184, 0.3)';
                infoBoxEl.style.background = 'rgba(148, 163, 184, 0.1)';
                infoTextEl.textContent = 'Your account has been deactivated. Contact your administrator to reactivate it.';
              } else if (data.status === 'suspended') {
                titleEl.textContent = 'Account Suspended';
                iconEl.className = 'fas fa-ban futuristic-status-icon';
                iconEl.style.color = '#ef4444';
                infoBoxEl.style.borderColor = 'rgba(239, 68, 68, 0.3)';
                infoBoxEl.style.background = 'rgba(239, 68, 68, 0.1)';
                infoTextEl.textContent = 'Your account has been suspended. This action is due to policy or security.';
              }
              var modalInstance = new bootstrap.Modal(modal, { backdrop: 'static', keyboard: false, focus: true });
              modalInstance.show();
              return;
            }
          }

          if (errorMessage) errorMessage.textContent = (data.message) ? data.message : 'Login failed. Please try again.';
          if (errorAlert) errorAlert.classList.remove('d-none');
        })
        .catch(function (err) {
          console.error('Login error', err);
          resetLoadingState();
          shakeLoginCard();
          if (errorMessage) errorMessage.textContent = 'Login failed. Please try again.';
          if (errorAlert) errorAlert.classList.remove('d-none');
        });
    });

    // AI Help widget
    (function initAiHelp() {
      var toggleBtn = document.getElementById('aiHelpToggleBtn');
      var panel = document.getElementById('aiHelpPanel');
      var closeBtn = document.getElementById('aiHelpCloseBtn');
      var clearBtn = document.getElementById('aiHelpClearBtn');
      var form = document.getElementById('aiHelpForm');
      var input = document.getElementById('aiHelpInput');
      var messagesEl = document.getElementById('aiHelpMessages');
      var sendBtn = document.getElementById('aiHelpSendBtn');

      if (!toggleBtn || !panel || !form || !input || !messagesEl || !sendBtn) return;

      var state = { open: false, history: [], thinkingId: null };

      function scrollMessages() {
        try { messagesEl.scrollTop = messagesEl.scrollHeight; } catch (e) {}
      }

      function createMessageEl(role, text) {
        var wrapper = document.createElement('div');
        wrapper.className = 'ai-help-message ' + (role === 'user' ? 'ai-user' : 'ai-assistant');
        var bubble = document.createElement('div');
        bubble.className = 'ai-help-bubble ' + (role === 'user' ? 'ai-user' : 'ai-assistant');
        bubble.textContent = text;
        wrapper.appendChild(bubble);
        return wrapper;
      }

      function addMessage(role, text, options) {
        options = options || {};
        var el = createMessageEl(role, text);
        if (options.replaceId && state.thinkingId === options.replaceId) {
          var existing = document.getElementById(state.thinkingId);
          if (existing && existing.parentNode) existing.parentNode.replaceChild(el, existing);
          else messagesEl.appendChild(el);
          state.thinkingId = null;
        } else {
          messagesEl.appendChild(el);
        }
        if (!options.skipHistory && role !== 'system') {
          state.history.push({ role: role === 'user' ? 'user' : 'assistant', text: text });
          if (state.history.length > 12) state.history = state.history.slice(-12);
          try { sessionStorage.setItem('ai_help_history', JSON.stringify(state.history)); } catch (e) {}
        }
        scrollMessages();
        return el;
      }

      try {
        var raw = sessionStorage.getItem('ai_help_history');
        if (raw) {
          var parsed = JSON.parse(raw);
          if (Array.isArray(parsed)) {
            state.history = [];
            parsed.slice(-12).forEach(function (msg) {
              if (msg && typeof msg.text === 'string') {
                var role = msg.role === 'assistant' ? 'assistant' : 'user';
                addMessage(role, msg.text, { skipHistory: true });
                state.history.push({ role: role, text: msg.text });
              }
            });
            scrollMessages();
          }
        }
      } catch (e) {}

      function openPanel() {
        if (state.open) return;
        state.open = true;
        panel.classList.add('open');
        toggleBtn.setAttribute('aria-expanded', 'true');
        setTimeout(function () { input.focus(); }, 30);
      }

      function closePanel() {
        if (!state.open) return;
        state.open = false;
        panel.classList.remove('open');
        toggleBtn.setAttribute('aria-expanded', 'false');
        toggleBtn.focus();
      }

      toggleBtn.addEventListener('click', function () {
        if (state.open) closePanel();
        else openPanel();
      });
      if (closeBtn) closeBtn.addEventListener('click', closePanel);

      if (clearBtn) {
        clearBtn.addEventListener('click', function () {
          while (messagesEl.firstChild) messagesEl.removeChild(messagesEl.firstChild);
          state.history = [];
          try { sessionStorage.removeItem('ai_help_history'); } catch (e) {}
          var welcome = document.createElement('div');
          welcome.className = 'ai-help-message ai-assistant';
          var bubble = document.createElement('div');
          bubble.className = 'ai-help-bubble ai-assistant';
          bubble.innerHTML = '<strong>Welcome.</strong><br>You can ask how to log in, reset your password, or who to contact for help.';
          welcome.appendChild(bubble);
          messagesEl.appendChild(welcome);
          scrollMessages();
        });
      }

      document.addEventListener('keydown', function (e) {
        if (!state.open) return;
        if (e.key === 'Escape') { e.preventDefault(); closePanel(); }
      });

      function setThinking(on) {
        if (on) {
          sendBtn.disabled = true;
          input.setAttribute('aria-busy', 'true');
          var tempId = 'ai-help-thinking';
          var el = document.getElementById(tempId);
          if (!el) {
            el = document.createElement('div');
            el.id = tempId;
            el.className = 'ai-help-message ai-assistant';
            var b = document.createElement('div');
            b.className = 'ai-help-bubble ai-assistant';
            b.textContent = 'Thinking…';
            el.appendChild(b);
            messagesEl.appendChild(el);
          }
          state.thinkingId = tempId;
          scrollMessages();
        } else {
          sendBtn.disabled = false;
          input.removeAttribute('aria-busy');
        }
      }

      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var text = (input.value || '').trim();
        if (!text) { input.focus(); return; }

        addMessage('user', text);
        input.value = '';
        setThinking(true);

        var payload = state.history.slice(-6).map(function (item) { return { role: item.role, text: item.text }; });
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
        var headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
        if (csrfToken) { headers['X-CSRF-Token'] = csrfToken; }

        fetch('/api/ai_help.php', {
          method: 'POST',
          headers: headers,
          credentials: 'same-origin',
          body: JSON.stringify({ message: text, history: payload })
        })
          .then(function (res) {
            return res.json().then(function (json) { return { ok: res.ok, data: json }; }).catch(function () { return { ok: false, data: null }; });
          })
          .then(function (_) {
            var res = _.ok ? _ : { ok: false };
            var data = _.data;
            setThinking(false);
            var reply = (data && typeof data.reply === 'string') ? data.reply : (!res.ok ? 'AI help service is unavailable. Please try again later.' : 'I could not generate an answer. Please try again.');
            addMessage('assistant', reply, { replaceId: state.thinkingId || 'ai-help-thinking' });
          })
          .catch(function () {
            setThinking(false);
            addMessage('assistant', 'Service unavailable. Try again later.', { replaceId: state.thinkingId || 'ai-help-thinking' });
          });
      });
    })();
  });
})();
