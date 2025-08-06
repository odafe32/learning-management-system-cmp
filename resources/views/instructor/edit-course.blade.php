<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Edit Course</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ph ph-house text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li class="fw-medium">
                    <span class="text-gray-300">/</span>
                </li>
                <li class="fw-medium">
                    <a href="{{ route('instructor.courses.manage') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        Courses
                    </a>
                </li>
                <li class="fw-medium">
                    <span class="text-gray-300">/</span>
                </li>
                <li class="fw-medium text-primary-600">Edit Course</li>
            </ul>
        </div>

        <!-- Success Message -->
        <x-success-message />

        <div class="row gy-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-gray-100 flex-between flex-wrap gap-8">
                        <h5 class="mb-0">Edit Course Information</h5>
                        <a href="{{ route('instructor.courses.manage') }}" class="btn btn-outline-primary-600 radius-8 px-20 py-11">
                            <i class="ph ph-arrow-left me-8"></i>
                            Back to Courses
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row gy-4">
                                <!-- Course Image -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-primary-light">Course Image</label>
                                    <div class="d-flex align-items-center gap-16">
                                        <div class="position-relative">
                                            <img src="{{ $course->image_url }}" 
                                                 alt="Course Image" 
                                                 class="w-120 h-120 rounded-12 object-fit-cover border border-gray-200"
                                                 id="courseImagePreview">
                                            <label for="image" class="position-absolute bottom-0 end-0 bg-primary-600 text-white w-32 h-32 rounded-circle flex-center cursor-pointer hover-bg-primary-700 transition-2">
                                                <i class="ph ph-camera"></i>
                                            </label>
                                        </div>
                                        <div>
                                            <h6 class="mb-8">Update Course Image</h6>
                                            <p class="text-gray-600 text-sm mb-0">Upload a new course thumbnail. JPG, PNG, GIF up to 2MB</p>
                                            <input type="file" id="image" name="image" accept="image/*" class="d-none" onchange="previewCourseImage(this)">
                                            @error('image')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Course Title -->
                                <div class="col-md-6">
                                    <label for="title" class="form-label fw-semibold text-primary-light">Course Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control radius-8 @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $course->title) }}" required 
                                           placeholder="e.g., Introduction to Computer Science">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Course Code -->
                                <div class="col-md-6">
                                    <label for="code" class="form-label fw-semibold text-primary-light">Course Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control radius-8 @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $course->code) }}" required 
                                           placeholder="e.g., CSC102" style="text-transform: uppercase;">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Level -->
                                <div class="col-md-4">
                                    <label for="level" class="form-label fw-semibold text-primary-light">Level</label>
                                    <select class="form-select radius-8 @error('level') is-invalid @enderror" id="level" name="level">
                                        <option value="">Select Level</option>
                                        @foreach($levels as $key => $value)
                                            <option value="{{ $key }}" {{ old('level', $course->level) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Semester -->
                                <div class="col-md-4">
                                    <label for="semester" class="form-label fw-semibold text-primary-light">Semester</label>
                                    <select class="form-select radius-8 @error('semester') is-invalid @enderror" id="semester" name="semester">
                                        <option value="">Select Semester</option>
                                        @foreach($semesters as $key => $value)
                                            <option value="{{ $key }}" {{ old('semester', $course->semester) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('semester')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Credit Units -->
                                <div class="col-md-4">
                                    <label for="credit_units" class="form-label fw-semibold text-primary-light">Credit Units <span class="text-danger">*</span></label>
                                    <select class="form-select radius-8 @error('credit_units') is-invalid @enderror" id="credit_units" name="credit_units" required>
                                        <option value="">Select Units</option>
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ old('credit_units', $course->credit_units) == $i ? 'selected' : '' }}>{{ $i }} Unit{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                    @error('credit_units')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-semibold text-primary-light">Status <span class="text-danger">*</span></label>
                                    <select class="form-select radius-8 @error('status') is-invalid @enderror" id="status" name="status" required>
                                        @foreach($statuses as $key => $value)
                                            <option value="{{ $key }}" {{ old('status', $course->status) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Course Info Display -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light">Course Information</label>
                                    <div class="bg-gray-50 p-12 rounded-8">
                                        <div class="d-flex align-items-center gap-8 mb-8">
                                            <span class="text-sm text-gray-600">Created:</span>
                                            <span class="text-sm fw-medium">{{ $course->created_at->format('M d, Y \a\t g:i A') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-8 mb-8">
                                            <span class="text-sm text-gray-600">Last Updated:</span>
                                            <span class="text-sm fw-medium">{{ $course->updated_at->format('M d, Y \a\t g:i A') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-8">
                                            <span class="text-sm text-gray-600">Course Slug:</span>
                                            <span class="text-sm fw-medium text-primary-600">{{ $course->slug }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold text-primary-light">Course Description</label>
                                    <textarea class="form-control radius-8 @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Provide a detailed description of the course content, objectives, and learning outcomes...">{{ old('description', $course->description) }}</textarea>
                                    <small class="text-gray-600">Maximum 1000 characters</small>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Form Actions -->
                                <div class="col-12">
                                    <div class="flex-align justify-content-between">
                                        <div class="d-flex align-items-center gap-8">
                                            <a href="{{ route('instructor.courses.manage') }}" class="btn btn-outline-gray-400 radius-8 px-20 py-11">Cancel</a>
                                            <button type="reset" class="btn btn-outline-warning-600 radius-8 px-20 py-11">
                                                <i class="ph ph-arrow-counter-clockwise me-8"></i>
                                                Reset Changes
                                            </button>
                                        </div>
                                        <div class="d-flex align-items-center gap-8">
                                            <button type="button" class="btn btn-outline-danger-600 radius-8 px-20 py-11" onclick="deleteCourse({{ $course->id }}, '{{ $course->title }}')">
                                                <i class="ph ph-trash me-8"></i>
                                                Delete Course
                                            </button>
                                            <button type="submit" class="btn btn-primary-600 radius-8 px-20 py-11">
                                                <i class="ph ph-check-circle me-8"></i>
                                                Update Course
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-20">
                        <i class="ph ph-warning-circle text-danger text-64 mb-16"></i>
                        <h6 class="mb-16">Delete Course</h6>
                        <p>Are you sure you want to delete the course "<span id="courseTitle" class="fw-medium"></span>"?</p>
                        <p class="text-danger text-sm mb-0">This action cannot be undone and will permanently remove all course data.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray-400" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger-600">
                            <i class="ph ph-trash me-8"></i>
                            Delete Course
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Course image preview function
    function previewCourseImage(input) {
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
                document.getElementById('courseImagePreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Delete course function
    function deleteCourse(courseId, courseTitle) {
        document.getElementById('courseTitle').textContent = courseTitle;
        document.getElementById('deleteForm').action = `/instructor/courses/${courseId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    // Form validation and submission
    document.addEventListener('DOMContentLoaded', function() {
        const courseForm = document.getElementById('courseForm');
        const codeInput = document.getElementById('code');
        
        // Auto uppercase course code
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Form submission
        courseForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating Course...';
            }
        });

        // Reset form functionality
        const resetBtn = courseForm.querySelector('button[type="reset"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Confirm reset
                if (confirm('Are you sure you want to reset all changes? This will restore the original values.')) {
                    // Reset form to original values
                    courseForm.reset();
                    
                    // Reset image preview to original
                    document.getElementById('courseImagePreview').src = '{{ $course->image_url }}';
                    
                    // Remove validation classes
                    courseForm.querySelectorAll('.is-invalid').forEach(field => {
                        field.classList.remove('is-invalid');
                    });
                    
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-info alert-dismissible fade show mb-3';
                    alertDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="ph ph-info me-2"></i>
                            <div class="flex-grow-1">
                                <strong>Reset Complete!</strong> All changes have been reverted to original values.
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    
                    const cardBody = document.querySelector('.card-body');
                    cardBody.insertBefore(alertDiv, courseForm);
                    
                    // Auto dismiss after 3 seconds
                    setTimeout(() => {
                        if (alertDiv.parentNode) {
                            const bsAlert = new bootstrap.Alert(alertDiv);
                            bsAlert.close();
                        }
                    }, 3000);
                }
            });
        }

        // Character count for description
        const descriptionTextarea = document.getElementById('description');
        if (descriptionTextarea) {
            descriptionTextarea.addEventListener('input', function() {
                const maxLength = 1000;
                const currentLength = this.value.length;
                const remaining = maxLength - currentLength;
                
                let countElement = this.parentNode.querySelector('.char-count');
                if (!countElement) {
                    countElement = document.createElement('small');
                    countElement.className = 'char-count text-gray-600';
                    this.parentNode.appendChild(countElement);
                }
                
                countElement.textContent = `${remaining} characters remaining`;
                
                if (remaining < 0) {
                    countElement.className = 'char-count text-danger';
                    this.classList.add('is-invalid');
                } else {
                    countElement.className = 'char-count text-gray-600';
                    this.classList.remove('is-invalid');
                }
            });
            
            // Trigger initial count
            descriptionTextarea.dispatchEvent(new Event('input'));
        }

        // Show unsaved changes warning
        let formChanged = false;
        const formInputs = courseForm.querySelectorAll('input, select, textarea');
        
        formInputs.forEach(input => {
            input.addEventListener('change', function() {
                formChanged = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                return e.returnValue;
            }
        });

        // Remove warning when form is submitted
        courseForm.addEventListener('submit', function() {
            formChanged = false;
        });
    });
    </script>
</x-instructor-layout>