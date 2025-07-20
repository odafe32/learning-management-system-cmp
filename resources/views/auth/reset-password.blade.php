<x-guest-layout>
    <section class="auth d-flex">
        <div class="auth-left bg-main-50 flex-center p-24">
            <img src="assets/images/thumbs/auth-img3.png" alt="">
        </div>
        <div class="auth-right py-40 px-24 flex-center flex-column">
            <div class="auth-right__inner mx-auto w-100">
                <a href="index-2.html" class="auth-right__logo">
                    <img src="assets/images/logo/logo.png" alt="">
                </a>
                <h2 class="mb-8">Reset Password</h2>
                <p class="text-gray-600 text-15 mb-32">For <span class="fw-medium">{{ $request->email ?? 'your email' }}</span></p>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    
                    <!-- Email Address (Hidden) -->
                    <input type="hidden" name="email" value="{{ old('email', $request->email) }}">
                    
                    <!-- New Password -->
                    <div class="mb-24">
                        <label for="password" class="form-label mb-8 h6">New Password</label>
                        <div class="position-relative">
                            <input type="password" 
                                   class="form-control py-11 ps-40 @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password"
                                   placeholder="Enter New Password" 
                                   required 
                                   autocomplete="new-password">
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex">
                                <i class="ph ph-lock"></i>
                            </span>
                            <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash" 
                                  data-target="#password"></span>
                        </div>
                        @error('password')
                            <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="mb-24">
                        <label for="password_confirmation" class="form-label mb-8 h6">Confirm Password</label>
                        <div class="position-relative">
                            <input type="password" 
                                   class="form-control py-11 ps-40 @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation"
                                   placeholder="Enter Confirm Password" 
                                   required 
                                   autocomplete="new-password">
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex">
                                <i class="ph ph-lock"></i>
                            </span>
                            <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash" 
                                  data-target="#password_confirmation"></span>
                        </div>
                        @error('password_confirmation')
                            <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Email Validation Error (if any) -->
                    @error('email')
                        <div class="alert alert-danger mb-24">{{ $message }}</div>
                    @enderror
                    
                    <button type="submit" class="btn btn-main rounded-pill w-100">Set New Password</button>

                    <a href="{{ route('login') }}" class="mt-24 text-main-600 flex-align gap-8 justify-content-center">
                        <i class="ph ph-arrow-left d-flex"></i> Back To Login
                    </a>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Password toggle functionality
        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const targetSelector = this.getAttribute('data-target');
                const passwordInput = document.querySelector(targetSelector);
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('ph-eye-slash');
                    this.classList.add('ph-eye');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('ph-eye');
                    this.classList.add('ph-eye-slash');
                }
            });
        });
    </script>
</x-guest-layout>