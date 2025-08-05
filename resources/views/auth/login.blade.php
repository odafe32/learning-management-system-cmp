<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <section class="auth d-flex">
        <div class="auth-left bg-main-50 flex-center p-24">
            <img src="assets/images/thumbs/auth-img1.png" alt="">
        </div>
        <div class="auth-right py-40 px-24 flex-center flex-column">
            <div class="auth-right__inner mx-auto w-100">
                <a href="{{ route('dashboard') }}" class="auth-right__logo">
                    <img src="assets/images/logo/logo.png" alt="">
                </a>
                <h2 class="mb-8">Welcome Back! 👋</h2>
                <p class="text-gray-600 text-15 mb-32">Please sign in to your account and start the adventure</p>

                <!-- Success Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" id="successAlert">
                        <i class="ph ph-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="ph ph-x"></i>
                        </button>
                    </div>
                @endif

                <!-- Display CSRF Error if it occurs -->
                @if ($errors->has('csrf'))
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <i class="ph ph-warning-circle me-2"></i>
                        Session expired. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="ph ph-x"></i>
                        </button>
                    </div>
                @endif

                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <i class="ph ph-warning-circle me-2"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="ph ph-x"></i>
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <!-- Email Address -->
                    <div class="mb-24">
                        <label for="email" class="form-label mb-8 h6">{{ __('Email') }}</label>
                        <div class="position-relative">
                            <input type="email" 
                                   class="form-control py-11 ps-40 @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="Type your email"
                                   required
                                   autofocus 
                                   autocomplete="username">
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex">
                                <i class="ph ph-user"></i>
                            </span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-24">
                        <label for="password" class="form-label mb-8 h6">{{ __('Password') }}</label>
                        <div class="position-relative">
                            <input type="password" 
                                   class="form-control py-11 ps-40 @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password"
                                   placeholder="Enter your password" 
                                   required
                                   autocomplete="current-password">
                            <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash" 
                                  onclick="togglePassword('password')"></span>
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex">
                                <i class="ph ph-lock"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="mb-32 flex-between flex-wrap gap-8">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input flex-shrink-0 rounded-4" 
                                   type="checkbox" 
                                   id="remember_me" 
                                   name="remember">
                            <label class="form-check-label text-15 flex-grow-1" for="remember_me">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                               class="text-main-600 hover-text-decoration-underline text-15 fw-medium">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-main rounded-pill w-100" id="loginBtn">
                        <span class="btn-text">{{ __('Log in') }}</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            {{ __('Signing in...') }}
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <style>
        .btn-loading .spinner-border {
            width: 1rem;
            height: 1rem;
        }
        
        .btn:disabled {
            opacity: 0.8;
            cursor: not-allowed;
        }

        /* Alert Styles */
        .alert {
            padding: 0.875rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.5rem;
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #a3cfbb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
            flex: 1;
        }

        .alert li {
            margin-bottom: 0.25rem;
        }

        .alert li:last-child {
            margin-bottom: 0;
        }

        .alert .btn-close {
            background: none;
            border: none;
            font-size: 1rem;
            opacity: 0.7;
            cursor: pointer;
            padding: 0;
            margin-left: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            transition: opacity 0.15s ease-in-out, background-color 0.15s ease-in-out;
        }

        .alert .btn-close:hover {
            opacity: 1;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .alert-success .btn-close:hover {
            background-color: rgba(15, 81, 50, 0.1);
        }

        .alert-danger .btn-close:hover {
            background-color: rgba(114, 28, 36, 0.1);
        }

        /* Ensure loading state is visible */
        .btn-loading {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-loading.d-none {
            display: none !important;
        }

        .btn-text.d-none {
            display: none !important;
        }

        /* Alert Animation */
        .alert.fade {
            transition: opacity 0.15s linear;
        }

        .alert.fade:not(.show) {
            opacity: 0;
        }

        .alert.show {
            opacity: 1;
        }
    </style>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.querySelector(`[onclick="togglePassword('${inputId}')"]`);
            
            if (passwordInput && toggleIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('ph-eye-slash');
                    toggleIcon.classList.add('ph-eye');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('ph-eye');
                    toggleIcon.classList.add('ph-eye-slash');
                }
            }
        }

        // Alert dismissal functionality
        function dismissAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => {
                    alert.remove();
                }, 150);
            }
        }

        // Main loading functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing login form...');
            
            // Setup alert dismissal
            document.querySelectorAll('.alert .btn-close').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const alert = this.closest('.alert');
                    if (alert) {
                        alert.classList.remove('show');
                        setTimeout(() => {
                            alert.remove();
                        }, 150);
                    }
                });
            });

            // Auto dismiss success alerts after 5 seconds
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(() => {
                    if (successAlert && successAlert.parentNode) {
                        successAlert.classList.remove('show');
                        setTimeout(() => {
                            if (successAlert.parentNode) {
                                successAlert.remove();
                            }
                        }, 150);
                    }
                }, 5000);
            }
            
            // Setup CSRF token for fetch requests
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.Laravel = {
                    csrfToken: token.getAttribute('content')
                };
                console.log('CSRF token set:', token.getAttribute('content').substring(0, 10) + '...');
            }

            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const btnText = loginBtn?.querySelector('.btn-text');
            const btnLoading = loginBtn?.querySelector('.btn-loading');

            if (!loginForm || !loginBtn || !btnText || !btnLoading) {
                console.error('Required elements not found:', {
                    loginForm: !!loginForm,
                    loginBtn: !!loginBtn,
                    btnText: !!btnText,
                    btnLoading: !!btnLoading
                });
                return;
            }

            console.log('All elements found, setting up event listeners...');

            // Reset loading state function
            function resetLoadingState() {
                console.log('Resetting loading state...');
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                loginBtn.disabled = false;
            }

            // Show loading state function
            function showLoadingState() {
                console.log('Showing loading state...');
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
                loginBtn.disabled = true;
            }

            // Form submit event
            loginForm.addEventListener('submit', function(e) {
                console.log('Form submit triggered...');
                
                // Prevent double submission
                if (loginBtn.disabled) {
                    console.log('Button already disabled, preventing submission');
                    e.preventDefault();
                    return false;
                }

                // Validate form before showing loading
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();

                if (!email || !password) {
                    console.log('Form validation failed - missing email or password');
                    return false; // Let browser handle validation
                }

                console.log('Form validation passed, showing loading state...');
                showLoadingState();

                // Set a timeout to reset loading state if form doesn't submit
                // This handles cases where validation fails on the client side
                setTimeout(function() {
                    if (loginBtn.disabled && window.location.href.includes('/login')) {
                        console.log('Form still on login page after 5 seconds, resetting loading state');
                        resetLoadingState();
                    }
                }, 5000);
            });

            // Reset loading state on page focus (in case user navigates back)
            window.addEventListener('pageshow', function(event) {
                console.log('Page show event triggered, persisted:', event.persisted);
                resetLoadingState();
            });

            // Reset loading state on page load if there are errors
            if (document.querySelector('.alert-danger')) {
                console.log('Errors detected on page load, resetting loading state');
                resetLoadingState();
            }

            // Handle browser back button
            window.addEventListener('popstate', function() {
                console.log('Popstate event triggered, resetting loading state');
                resetLoadingState();
            });

            // Reset on beforeunload
            window.addEventListener('beforeunload', function() {
                resetLoadingState();
            });

            console.log('Login form initialization complete');
        });

        // Debug function - you can call this in browser console to test loading state
        window.testLoadingState = function() {
            const btnText = document.querySelector('.btn-text');
            const btnLoading = document.querySelector('.btn-loading');
            const loginBtn = document.getElementById('loginBtn');
            
            console.log('Testing loading state...');
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            loginBtn.disabled = true;
            
            setTimeout(() => {
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                loginBtn.disabled = false;
                console.log('Loading state test complete');
            }, 3000);
        };
    </script>
</x-guest-layout>