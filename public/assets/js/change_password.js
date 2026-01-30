/**
 * Password Change Modal - JavaScript
 * Handles password strength validation, password matching, and modal display
 */
(function() {
    'use strict';
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPasswordChange);
    } else {
        initPasswordChange();
    }
    
    function initPasswordChange() {
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const strengthFill = document.getElementById('passwordStrengthFill');
        const strengthText = document.getElementById('passwordStrengthText');
        const changePasswordBtn = document.getElementById('changePasswordBtn');
        const passwordMatchIndicator = document.getElementById('passwordMatchIndicator');
        
        // Check if password change modal exists
        if (!newPasswordInput || !confirmPasswordInput || !strengthFill || !strengthText) {
            return; // Password change modal not present
        }
        
        const requirements = {
            length: document.getElementById('req-length'),
            uppercase: document.getElementById('req-uppercase'),
            lowercase: document.getElementById('req-lowercase'),
            number: document.getElementById('req-number'),
            symbol: document.getElementById('req-symbol')
        };
        
        function checkPasswordStrength(password) {
            if (!password || password.length === 0) {
                // Reset everything when password is empty
                strengthFill.style.width = '0%';
                strengthFill.style.backgroundColor = '#e5e7eb';
                strengthFill.style.background = '#e5e7eb';
                strengthText.textContent = 'Enter a password';
                strengthText.style.color = '#6b7280';
                requirements.length.classList.remove('valid');
                requirements.uppercase.classList.remove('valid');
                requirements.lowercase.classList.remove('valid');
                requirements.number.classList.remove('valid');
                requirements.symbol.classList.remove('valid');
                return false;
            }
            
            let strength = 0;
            let score = 0;
            
            // Length check
            if (password.length >= 8) {
                strength++;
                score += 20;
                requirements.length.classList.add('valid');
            } else {
                requirements.length.classList.remove('valid');
            }
            
            // Uppercase check
            if (/[A-Z]/.test(password)) {
                strength++;
                score += 20;
                requirements.uppercase.classList.add('valid');
            } else {
                requirements.uppercase.classList.remove('valid');
            }
            
            // Lowercase check
            if (/[a-z]/.test(password)) {
                strength++;
                score += 20;
                requirements.lowercase.classList.add('valid');
            } else {
                requirements.lowercase.classList.remove('valid');
            }
            
            // Number check
            if (/[0-9]/.test(password)) {
                strength++;
                score += 20;
                requirements.number.classList.add('valid');
            } else {
                requirements.number.classList.remove('valid');
            }
            
            // Symbol check
            if (/[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]/.test(password)) {
                strength++;
                score += 20;
                requirements.symbol.classList.add('valid');
            } else {
                requirements.symbol.classList.remove('valid');
            }
            
            // Bonus for longer passwords
            if (password.length >= 12) score += 10;
            if (password.length >= 16) score += 10;
            
            // Update strength bar automatically based on requirements met
            // Clear any existing classes first
            strengthFill.className = 'password-strength-fill';
            
            if (strength === 0) {
                strengthFill.style.width = '0%';
                strengthFill.style.backgroundColor = '#e5e7eb';
                strengthFill.style.background = '#e5e7eb';
                strengthText.textContent = 'Enter a password';
                strengthText.style.color = '#6b7280';
            } else if (strength === 1) {
                strengthFill.style.width = '20%';
                strengthFill.style.backgroundColor = '#ef4444';
                strengthFill.style.background = 'linear-gradient(90deg, #ef4444 0%, #dc2626 100%)';
                strengthText.textContent = 'Very weak';
                strengthText.style.color = '#ef4444';
            } else if (strength === 2) {
                strengthFill.style.width = '40%';
                strengthFill.style.backgroundColor = '#f59e0b';
                strengthFill.style.background = 'linear-gradient(90deg, #f59e0b 0%, #d97706 100%)';
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#f59e0b';
            } else if (strength === 3) {
                strengthFill.style.width = '60%';
                strengthFill.style.backgroundColor = '#f59e0b';
                strengthFill.style.background = 'linear-gradient(90deg, #f59e0b 0%, #d97706 100%)';
                strengthText.textContent = 'Fair';
                strengthText.style.color = '#f59e0b';
            } else if (strength === 4) {
                strengthFill.style.width = '80%';
                strengthFill.style.backgroundColor = '#22c55e';
                strengthFill.style.background = 'linear-gradient(90deg, #22c55e 0%, #16a34a 100%)';
                strengthText.textContent = 'Good';
                strengthText.style.color = '#22c55e';
            } else if (strength === 5) {
                strengthFill.style.width = '100%';
                strengthFill.style.backgroundColor = '#22c55e';
                strengthFill.style.background = 'linear-gradient(90deg, #22c55e 0%, #16a34a 100%)';
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#22c55e';
            }
            
            // Force a reflow to ensure animation triggers
            void strengthFill.offsetWidth;
            
            return strength === 5; // All requirements met
        }
        
        function checkPasswordMatch() {
            const newPassword = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            // Only show indicator if user has typed something in confirm password field
            if (confirmPassword.length === 0) {
                passwordMatchIndicator.style.display = 'none';
                return false;
            }
            
            // Only show match/mismatch if both fields have content
            if (newPassword.length === 0) {
                passwordMatchIndicator.style.display = 'none';
                return false;
            }
            
            if (newPassword === confirmPassword) {
                passwordMatchIndicator.style.display = 'flex';
                passwordMatchIndicator.className = 'password-match-indicator mt-2';
                passwordMatchIndicator.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i><span class="text-success">Passwords match</span>';
                return true;
            } else {
                passwordMatchIndicator.style.display = 'flex';
                passwordMatchIndicator.className = 'password-match-indicator mt-2';
                passwordMatchIndicator.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i><span class="text-danger">Passwords do not match</span>';
                return false;
            }
        }
        
        function validateForm() {
            const newPassword = newPasswordInput.value;
            const allRequirementsMet = checkPasswordStrength(newPassword);
            const passwordsMatch = checkPasswordMatch();
            
            if (allRequirementsMet && passwordsMatch && newPassword.length >= 8) {
                changePasswordBtn.disabled = false;
                changePasswordBtn.style.opacity = '1';
                changePasswordBtn.style.cursor = 'pointer';
            } else {
                changePasswordBtn.disabled = true;
                changePasswordBtn.style.opacity = '0.6';
                changePasswordBtn.style.cursor = 'not-allowed';
            }
        }
        
        // Update strength bar and requirements in real-time as user types
        newPasswordInput.addEventListener('input', function() {
            const password = newPasswordInput.value;
            checkPasswordStrength(password);
            validateForm();
            
            // Ensure strength bar is visible
            if (strengthFill) {
                strengthFill.style.display = 'block';
                strengthFill.style.visibility = 'visible';
                strengthFill.style.opacity = '1';
            }
        });
        
        // Initialize strength bar visibility on page load
        if (strengthFill) {
            strengthFill.style.display = 'block';
            strengthFill.style.visibility = 'visible';
            strengthFill.style.opacity = '1';
        }
        
        confirmPasswordInput.addEventListener('input', function() {
            checkPasswordMatch();
            validateForm();
        });
        
        // Ensure modal is visible and positioned correctly
        const modal = document.getElementById('passwordChangeModal');
        if (modal) {
            // Force modal to be visible with backdrop
            modal.style.display = 'block';
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.zIndex = '99999';
            modal.style.backgroundColor = 'rgba(0,0,0,0.7)';
            modal.classList.add('show', 'd-block');
            
            // Position the login-split-container inside modal to match login form position
            const modalSplitContainer = modal.querySelector('.login-split-container');
            if (modalSplitContainer) {
                modalSplitContainer.style.position = 'relative';
                modalSplitContainer.style.height = '100%';
                modalSplitContainer.style.display = 'flex';
            }
            
            // Prevent modal from being closed
            modal.addEventListener('hide.bs.modal', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            });
            
            // Prevent clicking outside modal (but allow clicking on the form)
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
            
            // Hide original login form when modal is shown (but keep logo visible in modal)
            const loginForm = document.getElementById('loginForm');
            const loginContainer = document.querySelector('.login-split-container:not(#passwordChangeModal .login-split-container)');
            if (loginForm) {
                loginForm.style.display = 'none';
            }
            if (loginContainer && !loginContainer.closest('#passwordChangeModal')) {
                loginContainer.style.opacity = '0.3';
                loginContainer.style.pointerEvents = 'none';
            }
            
            // Ensure the logo and branding in modal are fully visible
            const modalBrandedPanel = modal.querySelector('.login-branded-panel');
            if (modalBrandedPanel) {
                modalBrandedPanel.style.opacity = '1';
                modalBrandedPanel.style.pointerEvents = 'auto';
            }
        }
    }
})();
