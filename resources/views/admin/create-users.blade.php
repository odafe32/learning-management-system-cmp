<x-admin-layout>
    <div class="dashboard-body__content">
        <div class="row gy-4">
            <div class="col-lg-12">
                <!-- Page Header -->
                <div class="card border-0 bg-transparent">
                    <div class="card-body p-0">
                        <div class="flex-between flex-wrap gap-16 mb-24">
                            <div>
                                <h4 class="mb-8 text-xl fw-semibold">Create New User</h4>
                                <p class="text-gray-600 text-15">Add a new user to the system with appropriate role and permissions.</p>
                            </div>
                            <div class="flex-align gap-8">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary radius-8 px-20 py-11">
                                    <i class="ph ph-arrow-left me-8"></i>
                                    Back to Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create User Form -->
                <div class="card border-0 overflow-hidden">
                    <div class="card-header bg-main-50 border-bottom border-gray-100 py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0 text-main-600">
                            <i class="ph ph-user-plus me-8"></i>
                            User Information
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm" novalidate>
                            @csrf
                            
                            <div class="row gy-20">
                                <!-- Basic Information Section -->
                                <div class="col-12">
                                    <h6 class="text-md fw-semibold mb-16 text-gray-900 border-bottom pb-8">
                                        <i class="ph ph-info me-8 text-primary-600"></i>
                                        Basic Information
                                    </h6>
                                </div>

                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Full Name <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control radius-8 @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Enter full name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Email Address <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control radius-8 @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter email address"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="col-md-6">
                                    <label for="role" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        User Role <span class="text-danger-600">*</span>
                                    </label>
                                    <select class="form-control radius-8 @error('role') is-invalid @enderror" 
                                            id="role" 
                                            name="role" 
                                            required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $roleValue => $roleLabel)
                                            <option value="{{ $roleValue }}" {{ old('role') == $roleValue ? 'selected' : '' }}>
                                                {{ $roleLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Phone Number
                                    </label>
                                    <input type="tel" 
                                           class="form-control radius-8 @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="Enter phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <label for="gender" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Gender
                                    </label>
                                    <select class="form-control radius-8 @error('gender') is-invalid @enderror" 
                                            id="gender" 
                                            name="gender">
                                        <option value="">Select Gender</option>
                                        @foreach($genders as $genderValue => $genderLabel)
                                            <option value="{{ $genderValue }}" {{ old('gender') == $genderValue ? 'selected' : '' }}>
                                                {{ $genderLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Birth Date -->
                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Date of Birth
                                    </label>
                                    <input type="date" 
                                           class="form-control radius-8 @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" 
                                           name="birth_date" 
                                           value="{{ old('birth_date') }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Academic Information Section -->
                                <div class="col-12 mt-32">
                                    <h6 class="text-md fw-semibold mb-16 text-gray-900 border-bottom pb-8">
                                        <i class="ph ph-graduation-cap me-8 text-primary-600"></i>
                                        Academic Information
                                    </h6>
                                </div>

                                <!-- Department -->
                                <div class="col-md-6">
                                    <label for="department" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Department <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control radius-8 @error('department') is-invalid @enderror" 
                                           id="department" 
                                           name="department" 
                                           value="{{ old('department') }}" 
                                           placeholder="Enter department"
                                           required>
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Faculty -->
                                <div class="col-md-6">
                                    <label for="faculty" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Faculty <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control radius-8 @error('faculty') is-invalid @enderror" 
                                           id="faculty" 
                                           name="faculty" 
                                           value="{{ old('faculty') }}" 
                                           placeholder="Enter faculty"
                                           required>
                                    @error('faculty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Student-specific fields -->
                                <div id="studentFields" class="col-12" style="display: none;">
                                    <div class="row gy-20">
                                        <!-- Level -->
                                        <div class="col-md-6">
                                            <label for="level" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Level <span class="text-danger-600">*</span>
                                            </label>
                                            <select class="form-control radius-8 @error('level') is-invalid @enderror" 
                                                    id="level" 
                                                    name="level">
                                                <option value="">Select Level</option>
                                                @foreach($levels as $levelValue => $levelLabel)
                                                    <option value="{{ $levelValue }}" {{ old('level') == $levelValue ? 'selected' : '' }}>
                                                        {{ $levelLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Matric Number -->
                                        <div class="col-md-6">
                                            <label for="matric_or_staff_id" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Matric Number <span class="text-danger-600">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control radius-8 @error('matric_or_staff_id') is-invalid @enderror" 
                                                   id="matric_or_staff_id" 
                                                   name="matric_or_staff_id" 
                                                   value="{{ old('matric_or_staff_id') }}" 
                                                   placeholder="Enter matric number">
                                            @error('matric_or_staff_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Staff-specific fields -->
                                <div id="staffFields" class="col-12" style="display: none;">
                                    <div class="row gy-20">
                                        <!-- Staff ID -->
                                        <div class="col-md-6">
                                            <label for="staff_id" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Staff ID
                                            </label>
                                            <input type="text" 
                                                   class="form-control radius-8" 
                                                   id="staff_id" 
                                                   name="matric_or_staff_id" 
                                                   value="{{ old('matric_or_staff_id') }}" 
                                                   placeholder="Enter staff ID">
                                        </div>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="col-12">
                                    <label for="address" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Address
                                    </label>
                                    <textarea class="form-control radius-8 @error('address') is-invalid @enderror" 
                                              id="address" 
                                              name="address" 
                                              rows="3" 
                                              placeholder="Enter address">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Security Section -->
                                <div class="col-12 mt-32">
                                    <h6 class="text-md fw-semibold mb-16 text-gray-900 border-bottom pb-8">
                                        <i class="ph ph-lock me-8 text-primary-600"></i>
                                        Security Information
                                    </h6>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Password <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               class="form-control radius-8 @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Enter password"
                                               required>
                                        <button type="button" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-12 border-0 bg-transparent" onclick="togglePassword('password')">
                                            <i class="ph ph-eye" id="passwordToggle"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-gray-500 mt-4">Minimum 8 characters required</small>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Confirm Password <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               class="form-control radius-8 @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Confirm password"
                                               required>
                                        <button type="button" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-12 border-0 bg-transparent" onclick="togglePassword('password_confirmation')">
                                            <i class="ph ph-eye" id="passwordConfirmationToggle"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Form Actions -->
                                <div class="col-12 mt-32">
                                    <div class="flex-align justify-content-end gap-16">
                                        <button type="reset" class="btn btn-gray radius-8 px-24 py-12">
                                            <i class="ph ph-arrow-clockwise me-8"></i>
                                            Reset Form
                                        </button>
                                        <button type="submit" class="btn btn-main radius-8 px-24 py-12" id="submitBtn">
                                            <i class="ph ph-user-plus me-8"></i>
                                            <span class="submit-text">Create User</span>
                                            <span class="submit-loading d-none">
                                                <span class="spinner-border spinner-border-sm me-8" role="status"></span>
                                                Creating...
                                            </span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const studentFields = document.getElementById('studentFields');
            const staffFields = document.getElementById('staffFields');
            const levelField = document.getElementById('level');
            const matricField = document.getElementById('matric_or_staff_id');
            const staffIdField = document.getElementById('staff_id');
            const form = document.getElementById('createUserForm');
            const submitBtn = document.getElementById('submitBtn');

            // Handle role change
            roleSelect.addEventListener('change', function() {
                const selectedRole = this.value;
                
                if (selectedRole === 'student') {
                    studentFields.style.display = 'block';
                    staffFields.style.display = 'none';
                    levelField.required = true;
                    matricField.required = true;
                    matricField.placeholder = 'Enter matric number';
                    document.querySelector('label[for="matric_or_staff_id"]').innerHTML = 'Matric Number <span class="text-danger-600">*</span>';
                } else if (selectedRole === 'instructor' || selectedRole === 'lecturer' || selectedRole === 'admin') {
                    studentFields.style.display = 'none';
                    staffFields.style.display = 'block';
                    levelField.required = false;
                    matricField.required = false;
                    staffIdField.placeholder = 'Enter staff ID';
                } else {
                    studentFields.style.display = 'none';
                    staffFields.style.display = 'none';
                    levelField.required = false;
                    matricField.required = false;
                }
            });

            // Trigger role change on page load if role is already selected
            if (roleSelect.value) {
                roleSelect.dispatchEvent(new Event('change'));
            }

            // Form submission handling
            form.addEventListener('submit', function(e) {
                const submitText = document.querySelector('.submit-text');
                const submitLoading = document.querySelector('.submit-loading');
                
                submitBtn.disabled = true;
                submitText.classList.add('d-none');
                submitLoading.classList.remove('d-none');
            });

            // Password toggle functionality
            window.togglePassword = function(fieldId) {
                const field = document.getElementById(fieldId);
                const toggle = document.getElementById(fieldId + 'Toggle');
                
                if (field.type === 'password') {
                    field.type = 'text';
                    toggle.className = 'ph ph-eye-slash';
                } else {
                    field.type = 'password';
                    toggle.className = 'ph ph-eye';
                }
            };

            // Real-time password confirmation validation
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('password_confirmation');
            
            confirmPasswordField.addEventListener('input', function() {
                if (this.value !== passwordField.value) {
                    this.setCustomValidity('Passwords do not match');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            });

            passwordField.addEventListener('input', function() {
                if (confirmPasswordField.value && confirmPasswordField.value !== this.value) {
                    confirmPasswordField.setCustomValidity('Passwords do not match');
                    confirmPasswordField.classList.add('is-invalid');
                } else if (confirmPasswordField.value === this.value) {
                    confirmPasswordField.setCustomValidity('');
                    confirmPasswordField.classList.remove('is-invalid');
                }
            });
        });
    </script>
</x-admin-layout>