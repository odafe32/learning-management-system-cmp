<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Enroll in Courses</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Enroll Courses</li>
        </ul>
    </div>

    <!-- Filter Section -->
    <div class="card basic-data-table mb-24">
        <div class="card-header">
            <h5 class="card-title mb-0">Available Courses for {{ $userLevel }} Level</h5>
        </div>
        <div class="card-body">
            <div class="row mb-20">
                <div class="col-md-4">
                    <label class="form-label">Filter by Semester</label>
                    <select class="form-select" id="semesterFilter" onchange="filterBySemester()">
                        @foreach($semesters as $key => $semester)
                            <option value="{{ $key }}" {{ $currentSemester == $key ? 'selected' : '' }}>
                                {{ $semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search Courses</label>
                    <input type="text" class="form-control" id="courseSearch" placeholder="Search by course title or code...">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" class="btn btn-primary" onclick="searchCourses()">
                        <iconify-icon icon="solar:magnifer-outline" class="icon"></iconify-icon>
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="row" id="coursesGrid">
        @forelse($availableCourses as $course)
            <div class="col-xxl-4 col-md-6 course-card" data-course-code="{{ strtolower($course->code) }}" data-course-title="{{ strtolower($course->title) }}" data-course-id="{{ $course->id }}">
                <div class="card h-100">
                    <div class="card-body">
                        <!-- Course Header -->
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded-4">
                                    {{ $course->code }}
                                </span>
                                {!! $course->status_badge !!}
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="viewCourseDetails('{{ $course->id }}')">
                                            <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                            View Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="enrollInCourse('{{ $course->id }}', '{{ $course->title }}')">
                                            <iconify-icon icon="solar:user-plus-outline" class="icon"></iconify-icon>
                                            Enroll Now
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Course Image -->
                        <div class="mb-16">
                            <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-100 rounded-8" style="height: 200px; object-fit: cover;">
                        </div>

                        <!-- Course Info -->
                        <h6 class="card-title mb-8">{{ $course->title }}</h6>
                        <p class="text-secondary-light mb-16">{{ Str::limit($course->description, 100) }}</p>

                        <!-- Course Stats -->
                        <div class="row g-2 mb-16">
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:user-outline" class="icon text-lg text-secondary-light"></iconify-icon>
                                    <span class="text-secondary-light text-sm">{{ $course->students_count }} Students</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:document-text-outline" class="icon text-lg text-secondary-light"></iconify-icon>
                                    <span class="text-secondary-light text-sm">{{ $course->assignments_count }} Assignments</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:folder-outline" class="icon text-lg text-secondary-light"></iconify-icon>
                                    <span class="text-secondary-light text-sm">{{ $course->materials_count }} Materials</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:star-outline" class="icon text-lg text-secondary-light"></iconify-icon>
                                    <span class="text-secondary-light text-sm">{{ $course->credit_units }} Credits</span>
                                </div>
                            </div>
                        </div>

                        <!-- Instructor Info -->
                        <div class="d-flex align-items-center gap-2 mb-16">
                            <img src="{{ $course->instructor->profile_image_url }}" alt="{{ $course->instructor->name }}" class="w-32-px h-32-px rounded-circle object-fit-cover">
                            <div>
                                <h6 class="text-md mb-0">{{ $course->instructor->name }}</h6>
                                <span class="text-sm text-secondary-light">{{ $course->instructor->department }}</span>
                            </div>
                        </div>

                        <!-- Course Details -->
                        <div class="row g-2 mb-16">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-secondary-light">Level:</span>
                                    <span class="fw-medium">{{ $course->level_display }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-secondary-light">Semester:</span>
                                    <span class="fw-medium">{{ $course->semester_display }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Enroll Button -->
                        <button type="button" class="btn btn-primary w-100" onclick="enrollInCourse('{{ $course->id }}', '{{ $course->title }}')">
                            <iconify-icon icon="solar:user-plus-outline" class="icon"></iconify-icon>
                            Enroll in Course
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <iconify-icon icon="solar:book-outline" class="icon text-6xl text-secondary-light mb-3"></iconify-icon>
                        <h5 class="mb-2">No Available Courses</h5>
                        <p class="text-secondary-light mb-0">
                            There are no courses available for {{ $userLevel }} level in {{ $semesters[$currentSemester] ?? 'the selected semester' }}.
                        </p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($availableCourses->hasPages())
        <div class="d-flex justify-content-center mt-24">
            {{ $availableCourses->links() }}
        </div>
    @endif
</div>

<!-- Enrollment Confirmation Modal -->
<div class="modal fade" id="enrollmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Enrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to enroll in <strong id="courseTitle"></strong>?</p>
                <p class="text-secondary-light text-sm">Once enrolled, you will have access to all course materials, assignments, and announcements.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmEnrollBtn">
                    <iconify-icon icon="solar:user-plus-outline" class="icon"></iconify-icon>
                    Confirm Enrollment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Container -->
<div id="alertContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

<script>
    let selectedCourseId = null;
    // Create the base URL for enrollment
    const enrollmentBaseUrl = "{{ url('student/courses/enroll') }}";

    // Filter by semester
    function filterBySemester() {
        const semester = document.getElementById('semesterFilter').value;
        window.location.href = `{{ route('student.courses.enroll-courses') }}?semester=${semester}`;
    }

    // Search courses
    function searchCourses() {
        const searchTerm = document.getElementById('courseSearch').value.toLowerCase();
        const courseCards = document.querySelectorAll('.course-card');

        courseCards.forEach(card => {
            const courseCode = card.getAttribute('data-course-code');
            const courseTitle = card.getAttribute('data-course-title');
            
            if (courseCode.includes(searchTerm) || courseTitle.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Enroll in course
    function enrollInCourse(courseId, courseTitle) {
        selectedCourseId = courseId;
        document.getElementById('courseTitle').textContent = courseTitle;
        
        const modal = new bootstrap.Modal(document.getElementById('enrollmentModal'));
        modal.show();
    }

    // Confirm enrollment
    document.getElementById('confirmEnrollBtn').addEventListener('click', function() {
        if (!selectedCourseId) return;

        const btn = this;
        const originalText = btn.innerHTML;
        
        // Show loading state
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enrolling...';
        btn.disabled = true;

        // Make AJAX request using the base URL + course ID
        fetch(`${enrollmentBaseUrl}/${selectedCourseId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showAlert('success', data.message);
                
                // Hide the course card
                const courseCard = document.querySelector(`[data-course-id="${selectedCourseId}"]`);
                if (courseCard) {
                    courseCard.style.display = 'none';
                }
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('enrollmentModal')).hide();
                
                // Redirect to courses page after 2 seconds
                setTimeout(() => {
                    window.location.href = '{{ route("student.courses.index") }}';
                }, 2000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while enrolling. Please try again.');
        })
        .finally(() => {
            // Reset button state
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });

    // Show alert function
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alertContainer');
        const alertId = 'alert-' + Date.now();
        
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'solar:check-circle-outline' : 'solar:close-circle-outline';
        
        const alertHTML = `
            <div id="${alertId}" class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <iconify-icon icon="${iconClass}" class="icon me-2"></iconify-icon>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        alertContainer.insertAdjacentHTML('beforeend', alertHTML);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            const alertElement = document.getElementById(alertId);
            if (alertElement) {
                const bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }
        }, 5000);
    }

    // Real-time search
    document.getElementById('courseSearch').addEventListener('input', function() {
        searchCourses();
    });
</script>
</x-student-layout>