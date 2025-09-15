<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Profile Settings</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li class="fw-medium">
                    <span class="text-gray-300">/</span>
                </li>
                <li class="fw-medium text-primary-600">Profile</li>
            </ul>
        </div>

        
        <div class="row gy-4">
            <!-- Profile Information Card -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-gray-100 flex-align gap-8">
                        <h5 class="mb-0">Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row gy-4">
                                <!-- Avatar Upload -->
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-16">
                                        <div class="position-relative">
                                            <img src="{{ $user->avatar ? Storage::url($user->avatar) : url('assets/images/thumbs/user-img.png') }}" 
                                                 alt="Profile Picture" 
                                                 class="w-80 h-80 rounded-circle object-fit-cover border border-gray-200"
                                                 id="avatarPreview">
                                           <label for="avatar" class="position-absolute bottom-0 end-0 bg-primary-600 text-white w-32 h-32 rounded-circle flex-center cursor-pointer hover-bg-primary-700 transition-2">
    <i class="ph ph-camera"></i>
</label>
                                            </label>
                                        </div>
                                        <div>
                                            <h6 class="mb-8">Profile Picture</h6>
                                            <p class="text-gray-600 text-sm mb-0">Upload a new profile picture. JPG, PNG, GIF up to 2MB</p>
                                            <input type="file" id="avatar" name="avatar" accept="image/*" class="d-none" onchange="previewAvatar(this)">
                                            @error('avatar')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold text-primary-light">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control radius-8 @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold text-primary-light">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control radius-8 @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold text-primary-light">Phone Number</label>
                                    <input type="tel" class="form-control radius-8 @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="gender" class="form-label fw-semibold text-primary-light">Gender</label>
                                    <select class="form-select radius-8 @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="matric_or_staff_id" class="form-label fw-semibold text-primary-light">Staff ID</label>
                                    <input type="text" class="form-control radius-8 @error('matric_or_staff_id') is-invalid @enderror" 
                                           id="matric_or_staff_id" name="matric_or_staff_id" value="{{ old('matric_or_staff_id', $user->matric_or_staff_id) }}">
                                    @error('matric_or_staff_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label fw-semibold text-primary-light">Birth Date</label>
                                    <input type="date" class="form-control radius-8 @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="department" class="form-label fw-semibold text-primary-light">Department</label>
                                    <input type="text" class="form-control radius-8 @error('department') is-invalid @enderror" 
                                           id="department" name="department" value="{{ old('department', $user->department) }}">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="faculty" class="form-label fw-semibold text-primary-light">Faculty</label>
                                    <input type="text" class="form-control radius-8 @error('faculty') is-invalid @enderror" 
                                           id="faculty" name="faculty" value="{{ old('faculty', $user->faculty) }}">
                                    @error('faculty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label fw-semibold text-primary-light">Address</label>
                                    <textarea class="form-control radius-8 @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="flex-align justify-content-end gap-8">
                                        <button type="reset" class="btn btn-outline-gray-400 radius-8">Reset</button>
                                       <button type="submit" class="btn btn-primary radius-8">
                                            <i class="ph ph-check-circle"></i>
                                            Update Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-gray-100 flex-align gap-8">
                        <h5 class="mb-0">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student.profile.password') }}" method="POST" id="passwordForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row gy-4">
                                <!-- Current Password -->
                                <div class="col-12">
                                    <label for="current_password" class="form-label fw-semibold text-primary-light">Current Password <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control radius-8 pe-50 @error('current_password') is-invalid @enderror" 
                                               id="current_password" name="current_password" required placeholder="Enter your current password">
                                       <button type="button" class="position-absolute top-50 translate-middle-y end-0 me-12 p-0 bg-transparent border-0 text-gray-400 hover-text-primary-600 transition-2" 
                                                onclick="togglePasswordVisibility('current_password')" 
                                                title="Toggle password visibility">
                                            <i class="ph ph-eye" id="current_password_icon"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold text-primary-light">New Password <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control radius-8 pe-50 @error('password') is-invalid @enderror" 
                                               id="password" name="password" required placeholder="Enter new password">
                                       <button type="button" class="position-absolute top-50 translate-middle-y end-0 me-12 p-0 bg-transparent border-0 text-gray-400 hover-text-primary-600 transition-2" 
                                            onclick="togglePasswordVisibility('password')" 
                                            title="Toggle password visibility">
                                        <i class="ph ph-eye" id="password_icon"></i>
                                    </button>
                                    </div>
                                 <!-- Password Info Text -->
                                    <small class="text-gray-600 mt-1 d-block">
                                        <i class="ph ph-info me-1"></i>
                                        Password must be at least 8 characters with uppercase, lowercase, numbers, and symbols.
                                    </small>

                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold text-primary-light">Confirm New Password <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control radius-8 pe-50 @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" name="password_confirmation" required placeholder="Confirm new password">
                                        <button type="button" class="position-absolute top-50 translate-middle-y end-0 me-12 p-0 bg-transparent border-0 text-gray-400 hover-text-primary-600 transition-2" 
                                                onclick="togglePasswordVisibility('password_confirmation')" 
                                                title="Toggle password visibility">
                                            <i class="ph ph-eye" id="password_confirmation_icon"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password Strength Indicator -->
                                <div class="col-12">
                                    <div class="password-strength-container d-none" id="passwordStrengthContainer">
                                        <label class="form-label fw-semibold text-primary-light mb-2">Password Strength</label>
                                        <div class="password-strength-bar mb-2">
                                            <div class="password-strength-fill" id="passwordStrengthFill"></div>
                                        </div>
                                        <div class="password-strength-text text-sm" id="passwordStrengthText">Enter a password to see strength</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="flex-align justify-content-end gap-8">
                                        <button type="reset" class="btn btn-outline-gray-400 radius-8">Reset</button>
                                        <button type="submit" class="btn btn-primary radius-8">
                                            <iconify-icon icon="solar:shield-check-outline" class="text-xl"></iconify-icon>
                                            Update Password
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Password toggle button styles */
    .position-relative .btn:hover {
        background-color: transparent !important;
    }
    
    /* Password strength indicator styles */
    .password-strength-bar {
        width: 100%;
        height: 6px;
        background-color: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
    }
    
    .password-strength-fill {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 3px;
    }
    
    .strength-weak .password-strength-fill {
        background-color: #ef4444;
        width: 25%;
    }
    
    .strength-fair .password-strength-fill {
        background-color: #f59e0b;
        width: 50%;
    }
    
    .strength-good .password-strength-fill {
        background-color: #3b82f6;
        width: 75%;
    }
    
    .strength-strong .password-strength-fill {
        background-color: #10b981;
        width: 100%;
    }
    
    /* Avatar upload hover effect */
    .position-relative label:hover {
        transform: scale(1.05);
    }
    
    /* Form input focus styles */
    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-600);
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }
    </style>

    <script>
    // Avatar preview function
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                input.value = '';
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, JPG, or GIF)');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Enhanced password visibility toggle
 function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (!field || !icon) {
        console.error('Password field or icon not found:', fieldId);
        return;
    }
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'ph ph-eye-slash';
        icon.parentElement.setAttribute('title', 'Hide password');
    } else {
        field.type = 'password';
        icon.className = 'ph ph-eye';
        icon.parentElement.setAttribute('title', 'Show password');
    }
}


    // Password strength checker
    function checkPasswordStrength(password) {
        const strengthContainer = document.getElementById('passwordStrengthContainer');
        const strengthFill = document.getElementById('passwordStrengthFill');
        const strengthText = document.getElementById('passwordStrengthText');
        
        if (!password) {
            strengthContainer.classList.add('d-none');
            return;
        }
        
        strengthContainer.classList.remove('d-none');
        
        let score = 0;
        let feedback = [];
        
        // Length check
        if (password.length >= 8) score++;
        else feedback.push('at least 8 characters');
        
        // Uppercase check
        if (/[A-Z]/.test(password)) score++;
        else feedback.push('uppercase letter');
        
        // Lowercase check
        if (/[a-z]/.test(password)) score++;
        else feedback.push('lowercase letter');
        
        // Number check
        if (/\d/.test(password)) score++;
        else feedback.push('number');
        
        // Symbol check
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score++;
        else feedback.push('special character');
        
        // Remove all strength classes
        strengthContainer.className = strengthContainer.className.replace(/strength-\w+/g, '');
        
        // Apply strength class and text
        switch (score) {
            case 0:
            case 1:
                strengthContainer.classList.add('strength-weak');
                strengthText.textContent = 'Weak - Missing: ' + feedback.join(', ');
                strengthText.className = 'password-strength-text text-sm text-danger';
                break;
            case 2:
            case 3:
                strengthContainer.classList.add('strength-fair');
                strengthText.textContent = 'Fair - Missing: ' + feedback.join(', ');
                strengthText.className = 'password-strength-text text-sm text-warning';
                break;
            case 4:
                strengthContainer.classList.add('strength-good');
                strengthText.textContent = 'Good - Almost there!';
                strengthText.className = 'password-strength-text text-sm text-info';
                break;
            case 5:
                strengthContainer.classList.add('strength-strong');
                strengthText.textContent = 'Strong - Great password!';
                strengthText.className = 'password-strength-text text-sm text-success';
                break;
        }
    }

    // Form validation and submission handling
    document.addEventListener('DOMContentLoaded', function() {
        const profileForm = document.getElementById('profileForm');
        const passwordForm = document.getElementById('passwordForm');
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');

        // Password strength checking
        if (passwordField) {
            passwordField.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });
        }

        // Real-time password confirmation validation
        if (confirmPasswordField) {
            confirmPasswordField.addEventListener('input', function() {
                const password = passwordField.value;
                const confirmPassword = this.value;
                
                if (confirmPassword && password !== confirmPassword) {
                    this.setCustomValidity('Passwords do not match');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            });
        }

        // Profile form submission
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating Profile...';
                }
            });
        }

        // Password form submission
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                const password = passwordField.value;
                const confirmPassword = confirmPasswordField.value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    confirmPasswordField.focus();
                    confirmPasswordField.setCustomValidity('Passwords do not match');
                    confirmPasswordField.classList.add('is-invalid');
                    return false;
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating Password...';
                }
            });
        }

        // Reset form functionality
   document.querySelectorAll('button[type="reset"]').forEach(button => {
    button.addEventListener('click', function() {
        const form = this.closest('form');
        if (form) {
            // Reset validation states
            form.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            
            // Hide password strength indicator
            const strengthContainer = document.getElementById('passwordStrengthContainer');
            if (strengthContainer) {
                strengthContainer.classList.add('d-none');
            }
            
            // Reset password visibility icons
            document.querySelectorAll('[id$="_icon"]').forEach(icon => {
                if (icon.id.includes('password')) {
                    icon.className = 'ph ph-eye';
                    const field = document.getElementById(icon.id.replace('_icon', ''));
                    if (field) {
                        field.type = 'password';
                    }
                }
            });
        }
    });
});
    });
    </script>
</x-student-layout>