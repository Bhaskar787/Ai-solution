<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | AI-Solutions - Secure Access Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/admin-login.css">
</head>
<body class="login-bg">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center px-6 py-12">
        <div class="login-card max-w-md w-full p-8 rounded-2xl shadow-2xl fade-in">
            <!-- Company Logo & Branding -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">AI-Solutions</h1>
                <p class="text-gray-600">Admin Portal</p>
                
                <div class="security-badge">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                    </svg>
                    Secure Access
                </div>
            </div>

            <!-- Error/Success Messages -->
            <div id="error-banner" class="alert-banner">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <div>
                        <p class="font-semibold" id="error-title">Authentication Failed</p>
                        <p class="text-sm opacity-90" id="error-message">Invalid username or password. Please try again.</p>
                    </div>
                </div>
            </div>

            <div id="success-banner" class="success-banner">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <div>
                        <p class="font-semibold">Login Successful</p>
                        <p class="text-sm opacity-90">Redirecting to admin dashboard...</p>
                    </div>
                </div>
            </div>

            <!-- Login Form -->
            <form id="login-form" class="space-y-6">
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required
                            autocomplete="username"
                            class="form-field w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none"
                            placeholder="Enter your username"
                        >
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="error-message" id="username-error"></div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            autocomplete="current-password"
                            class="form-field w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none"
                            placeholder="Enter your password"
                        >
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                            </svg>
                        </div>
                        <button type="button" class="password-toggle" id="toggle-password">
                            <svg class="w-5 h-5" id="eye-open" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                            <svg class="w-5 h-5 hidden" id="eye-closed" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="error-message" id="password-error"></div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" id="remember-me" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 transition-colors" id="forgot-password">
                        Forgot password?
                    </a>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    id="login-btn"
                    class="login-btn w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold text-lg hover:shadow-xl transition-all flex items-center justify-center"
                >
                    <span id="login-text">Sign In</span>
                    <div class="loading-spinner ml-3"></div>
                </button>

                <!-- Security Notice -->
                <div class="text-center text-sm text-gray-500 mt-6">
                    <p class="flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                        </svg>
                        Secure connection protected by SSL encryption
                    </p>
                </div>
            </form>

            <!-- Demo Credentials (Remove in production) -->
            <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-200">
                <h4 class="text-sm font-semibold text-blue-800 mb-2">Demo Credentials:</h4>
                <p class="text-sm text-blue-700">
                    <strong>Username:</strong> admin<br>
                    <strong>Password:</strong> admin123
                </p>
            </div>
        </div>
    </div>

    <script>
        // Form elements
        const loginForm = document.getElementById('login-form');
        const usernameField = document.getElementById('username');
        const passwordField = document.getElementById('password');
        const loginBtn = document.getElementById('login-btn');
        const loginText = document.getElementById('login-text');
        const loadingSpinner = document.querySelector('.loading-spinner');
        const errorBanner = document.getElementById('error-banner');
        const successBanner = document.getElementById('success-banner');
        const togglePassword = document.getElementById('toggle-password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        // Login attempt tracking
        let loginAttempts = 0;
        const maxAttempts = 5;
        let isLocked = false;
        let lockoutTime = null;

        // Password visibility toggle
        togglePassword.addEventListener('click', () => {
            const isPassword = passwordField.type === 'password';
            passwordField.type = isPassword ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', !isPassword);
            eyeClosed.classList.toggle('hidden', isPassword);
        });

        // Field validation
        function validateField(field, value, fieldName) {
            const errorElement = document.getElementById(`${fieldName}-error`);
            
            // Reset field state
            field.classList.remove('error', 'valid');
            errorElement.classList.remove('show');

            if (!value.trim()) {
                showFieldError(field, errorElement, `${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)} is required`);
                return false;
            }

            if (fieldName === 'username' && value.length < 3) {
                showFieldError(field, errorElement, 'Username must be at least 3 characters');
                return false;
            }

            if (fieldName === 'password' && value.length < 6) {
                showFieldError(field, errorElement, 'Password must be at least 6 characters');
                return false;
            }

            // Field is valid
            field.classList.add('valid');
            return true;
        }

        function showFieldError(field, errorElement, message) {
            field.classList.add('error');
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }

        // Real-time validation
        usernameField.addEventListener('blur', () => {
            validateField(usernameField, usernameField.value, 'username');
        });

        passwordField.addEventListener('blur', () => {
            validateField(passwordField, passwordField.value, 'password');
        });

        // Clear error states on input
        [usernameField, passwordField].forEach(field => {
            field.addEventListener('input', () => {
                if (field.classList.contains('error')) {
                    field.classList.remove('error');
                    const fieldName = field.name;
                    document.getElementById(`${fieldName}-error`).classList.remove('show');
                }
                hideMessages();
            });
        });

        // Show error message
        function showError(title, message) {
            document.getElementById('error-title').textContent = title;
            document.getElementById('error-message').textContent = message;
            errorBanner.classList.add('show');
            successBanner.classList.remove('show');
        }

        // Show success message
        function showSuccess() {
            successBanner.classList.add('show');
            errorBanner.classList.remove('show');
        }

        // Hide all messages
        function hideMessages() {
            errorBanner.classList.remove('show');
            successBanner.classList.remove('show');
        }

        // Check if account is locked
        function checkLockout() {
            if (isLocked && lockoutTime) {
                const timeRemaining = lockoutTime - Date.now();
                if (timeRemaining > 0) {
                    const minutes = Math.ceil(timeRemaining / 60000);
                    showError('Account Temporarily Locked', `Too many failed attempts. Try again in ${minutes} minute(s).`);
                    return true;
                } else {
                    // Unlock account
                    isLocked = false;
                    lockoutTime = null;
                    loginAttempts = 0;
                }
            }
            return false;
        }

        // Form submission
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Check if account is locked
            if (checkLockout()) {
                return;
            }

            const username = usernameField.value.trim();
            const password = passwordField.value.trim();

            // Validate fields
            const isUsernameValid = validateField(usernameField, username, 'username');
            const isPasswordValid = validateField(passwordField, password, 'password');

            if (!isUsernameValid || !isPasswordValid) {
                return;
            }

            // Show loading state
            loginBtn.disabled = true;
            loginText.textContent = 'Signing In...';
            loadingSpinner.style.display = 'block';
            hideMessages();

            try {
                const response = await fetch('./login-handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        username: username,
                        password: password
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Success - show success message and redirect
                    showSuccess();

                    // Store session (in production, use secure session management)
                    sessionStorage.setItem('adminSession', JSON.stringify({
                        user: { username: username, role: 'admin' },
                        loginTime: Date.now(),
                        expires: Date.now() + (24 * 60 * 60 * 1000) // 24 hours
                    }));

                    // Reset login attempts
                    loginAttempts = 0;

                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = '../admin-dashboard.php';
                    }, 2000);

                } else {
                    // Failed login
                    loginAttempts++;

                    if (loginAttempts >= maxAttempts) {
                        // Lock account for 15 minutes
                        isLocked = true;
                        lockoutTime = Date.now() + (15 * 60 * 1000);
                        showError('Account Locked', `Too many failed attempts. Account locked for 15 minutes.`);
                    } else {
                        const remainingAttempts = maxAttempts - loginAttempts;
                        showError('Authentication Failed', 
                            `Invalid username or password. ${remainingAttempts} attempt(s) remaining.`);
                    }
                }

            } catch (error) {
                console.error('Login error:', error);
                showError('Connection Error', 'Unable to connect to authentication server. Please try again.');
            } finally {
                // Reset button state
                loginBtn.disabled = false;
                loginText.textContent = 'Sign In';
                loadingSpinner.style.display = 'none';
            }
        });

        // Forgot password handler
        document.getElementById('forgot-password').addEventListener('click', (e) => {
            e.preventDefault();
            
            // Create custom modal for password reset
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white p-8 rounded-2xl max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Password Reset</h3>
                    <p class="text-gray-600 mb-6">
                        Please contact your system administrator to reset your password.
                    </p>
                    <div class="bg-blue-50 p-4 rounded-xl mb-6">
                        <p class="text-sm text-blue-800">
                            <strong>Contact:</strong> admin@ai-solutions.co.uk<br>
                            <strong>Phone:</strong> +44 191 XXX XXXX
                        </p>
                    </div>
                    <button class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors" onclick="this.closest('.fixed').remove()">
                        Close
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
        });

        // Check for existing session on page load
        // This will make a request to check if user is logged in
        window.addEventListener('load', () => {
            fetch('./check_session.php')
                .then(response => response.json())
                .then(data => {
                    if (data.logged_in) {
                        // Valid session exists, redirect to dashboard
                        window.location.href = '../admin-dashboard.php';
                    }
                })
                .catch(error => {
                    // If there's an error, it likely means user is not logged in
                    // which is fine, we just continue showing the login form
                    console.log('Not logged in, showing login form');
                });
        });

        // Auto-focus username field
        usernameField.focus();
    </script>
</body>
</html>
