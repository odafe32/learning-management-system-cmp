<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <section class="auth d-flex">
        <div class="auth-left bg-main-50 flex-center p-24">
            <img src="assets/images/thumbs/auth-img3.png" alt="">
        </div>
        <div class="auth-right py-40 px-24 flex-center flex-column">
            <div class="auth-right__inner mx-auto w-100">
                <a href="{{ route('dashboard') }}" class="auth-right__logo">
                    <img src="assets/images/logo/logo.png" alt="">
                </a>
                <h2 class="mb-8">{{ __('Forgot Password?') }}</h2>
                <p class="text-gray-600 text-15 mb-32">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>

                <form method="POST" action="{{ route('password.email') }}">
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
                                   placeholder="Type your email address"
                                   required 
                                   autofocus>
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex">
                                <i class="ph ph-envelope"></i>
                            </span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-main rounded-pill w-100">
                        {{ __('Email Password Reset Link') }}
                    </button>

                    <!-- Back to Login -->
                    <a href="{{ route('login') }}" 
                       class="my-32 text-main-600 flex-align gap-8 justify-content-center text-decoration-none"> 
                        <i class="ph ph-arrow-left d-flex"></i> 
                        {{ __('Back To Login') }}
                    </a>

                 
                </form>
            </div>
        </div>
    </section>
</x-guest-layout>