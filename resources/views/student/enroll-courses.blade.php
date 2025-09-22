<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <!-- Breadcrumb -->
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
    <div class="card filter-card mb-24">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Available Courses for {{ $userLevel }} Level</h5>
                <span class="badge bg-primary-50 text-primary-600 px-12 py-6 rounded-4">
                    {{ $availableCourses->count() }} Course{{ $availableCourses->count() !== 1 ? 's' : '' }} Available
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-medium">Filter by Semester</label>
                    <select class="form-select" id="semesterFilter" onchange="filterBySemester()">
                        @foreach($semesters as $key => $semester)
                            <option value="{{ $key }}" {{ $currentSemester == $key ? 'selected' : '' }}>
                                {{ $semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-medium">Search Courses</label>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-40" id="courseSearch" placeholder="Search by course title or code...">
                        <iconify-icon icon="solar:magnifer-outline" class="position-absolute top-50 start-0 translate-middle-y ms-12 text-secondary-light"></iconify-icon>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-primary" onclick="searchCourses()">
                            <iconify-icon icon="solar:magnifer-outline" class="icon"></iconify-icon>
                            Search
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="clearSearch()">
                            <iconify-icon icon="solar:refresh-outline" class="icon"></iconify-icon>
                            Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="row" id="coursesGrid">
        @forelse($availableCourses as $course)
            <div class="col-xxl-4 col-lg-6 mb-24 course-item" 
                 data-course-code="{{ strtolower($course->code) }}" 
                 data-course-title="{{ strtolower($course->title) }}" 
                 data-course-id="{{ $course->id }}">
                <div class="card course-card h-100">
                    <!-- Course Image Container -->
                    <div class="course-image-container position-relative">
                        <img 
                            src="{{ $course->image_url }}" 
                            alt="{{ $course->title }}" 
                            class="course-image"
                            loading="lazy"
                            onerror="this.src='{{ asset('assets/images/thumbs/course-default.png') }}'"
                        >
                        <!-- Course Code and Level Badges -->
                        <div class="position-absolute top-12 start-12">
                            <div class="d-flex flex-column gap-2">
                                <span class="badge bg-primary-600 text-white px-8 py-4 rounded-4 fw-medium">
                                    {{ $course->code }}
                                </span>
                                <span class="badge bg-warning-600 text-white px-8 py-4 rounded-4 fw-medium level-badge">
                                    <iconify-icon icon="solar:graduation-cap-outline" class="icon me-1"></iconify-icon>
                                    {{ $course->level }} Level
                                </span>
                            </div>
                        </div>
                        <!-- Course Status -->
                        <div class="position-absolute top-12 end-12">
                            {!! $course->status_badge !!}
                        </div>
                        <!-- Semester Badge -->
                        <div class="position-absolute bottom-12 end-12">
                            <span class="badge bg-info-600 text-white px-8 py-4 rounded-4 fw-medium semester-badge">
                                <iconify-icon icon="solar:calendar-outline" class="icon me-1"></iconify-icon>
                                {{ ucfirst($course->semester) }} Sem
                            </span>
                        </div>
                        <!-- Quick Preview Overlay -->
                        <div class="course-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <button type="button" class="preview-btn rounded-circle" onclick="previewCourse('{{ $course->id }}', '{{ $course->title }}', '{{ $course->code }}', '{{ $course->level }}', '{{ ucfirst($course->semester) }}', '{{ $course->credit_units }}', '{{ $course->instructor->name ?? 'Unknown Instructor' }}', '{{ $course->instructor->department ?? 'N/A' }}', '{{ $course->description ? addslashes($course->description) : 'No description available.' }}', '{{ $course->students_count ?? 0 }}', '{{ $course->assignments_count ?? 0 }}', '{{ $course->materials_count ?? 0 }}')">
                                <iconify-icon icon="solar:eye-outline" class="icon text-lg"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <!-- Course Header with Level -->
                        <div class="course-header mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="course-title mb-0 text-break flex-grow-1">{{ $course->title }}</h6>
                                <div class="course-level-indicator ms-2">
                                    <span class="badge bg-gradient-warning text-white px-10 py-6 rounded-pill fw-bold">
                                        {{ $course->level }}L
                                    </span>
                                </div>
                            </div>
                            <p class="text-secondary-light text-sm mb-0 line-clamp-2">
                                {{ $course->description ? Str::limit($course->description, 100) : 'No description available.' }}
                            </p>
                        </div>

                        <!-- Course Info Grid -->
                        <div class="course-info-grid mb-3">
                            <div class="info-card level-info">
                                <div class="info-icon">
                                    <iconify-icon icon="solar:graduation-cap-outline" class="icon text-warning-600"></iconify-icon>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Level</span>
                                    <span class="info-value">{{ $course->level }}</span>
                                </div>
                            </div>
                            <div class="info-card semester-info">
                                <div class="info-icon">
                                    <iconify-icon icon="solar:calendar-outline" class="icon text-info-600"></iconify-icon>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Semester</span>
                                    <span class="info-value">{{ ucfirst($course->semester) }}</span>
                                </div>
                            </div>
                            <div class="info-card credits-info">
                                <div class="info-icon">
                                    <iconify-icon icon="solar:star-outline" class="icon text-success-600"></iconify-icon>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Credits</span>
                                    <span class="info-value">{{ $course->credit_units }}</span>
                                </div>
                            </div>
                            <div class="info-card students-info">
                                <div class="info-icon">
                                    <iconify-icon icon="solar:users-group-rounded-outline" class="icon text-primary-600"></iconify-icon>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Students</span>
                                    <span class="info-value">{{ $course->students_count ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Course Stats -->
                        <div class="course-stats mb-3">
                            <div class="stats-row">
                                <div class="stat-item">
                                    <iconify-icon icon="solar:document-text-outline" class="icon text-secondary-light"></iconify-icon>
                                    <span class="text-xs text-secondary-light">{{ $course->assignments_count ?? 0 }} Assignments</span>
                                </div>
                                <div class="stat-item">
                                    <iconify-icon icon="solar:folder-outline" class="icon text-secondary-light"></iconify-icon>
                                    <span class="text-xs text-secondary-light">{{ $course->materials_count ?? 0 }} Materials</span>
                                </div>
                            </div>
                        </div>

                        <!-- Instructor Info -->
                        <div class="d-flex align-items-center gap-3 mb-3 instructor-info">
                            <div class="flex-shrink-0">
                                <img 
                                    src="{{ $course->instructor->profile_image_url ?? asset('assets/images/default-avatar.png') }}" 
                                    alt="{{ $course->instructor->name ?? 'Instructor' }}" 
                                    class="instructor-avatar"
                                    loading="lazy"
                                    onerror="this.src='{{ asset('assets/images/default-avatar.png') }}'"
                                >
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <h6 class="instructor-name mb-0 text-break">{{ $course->instructor->name ?? 'Unknown Instructor' }}</h6>
                                <span class="text-xs text-secondary-light">{{ $course->instructor->department ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="course-actions mt-auto">
                            <div class="d-flex gap-2 mb-2">
                                <button type="button" 
                                        class="btn btn-primary flex-fill view-details-btn" 
                                        onclick="previewCourse('{{ $course->id }}', '{{ $course->title }}', '{{ $course->code }}', '{{ $course->level }}', '{{ ucfirst($course->semester) }}', '{{ $course->credit_units }}', '{{ $course->instructor->name ?? 'Unknown Instructor' }}', '{{ $course->instructor->department ?? 'N/A' }}', '{{ $course->description ? addslashes($course->description) : 'No description available.' }}', '{{ $course->students_count ?? 0 }}', '{{ $course->assignments_count ?? 0 }}', '{{ $course->materials_count ?? 0 }}')">
                                    <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                    View Details
                                </button>
                            </div>
                            <button type="button" 
                                    class="btn btn-primary w-100 enroll-btn" 
                                    onclick="enrollInCourse('{{ $course->id }}', '{{ $course->title }}')"
                                    data-course-id="{{ $course->id }}">
                                <iconify-icon icon="solar:user-plus-outline" class="icon"></iconify-icon>
                                Enroll in {{ $course->level }} Level Course
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card empty-state-card">
                    <div class="card-body text-center py-5">
                        <div class="empty-state-icon mb-3">
                            <iconify-icon icon="solar:book-outline" class="icon text-6xl text-secondary-light"></iconify-icon>
                        </div>
                        <h5 class="mb-2">No Available Courses</h5>
                        <p class="text-secondary-light mb-4 max-w-400 mx-auto">
                            There are no courses available for {{ $userLevel }} level in {{ $semesters[$currentSemester] ?? 'the selected semester' }}.
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                                <iconify-icon icon="solar:home-outline" class="icon"></iconify-icon>
                                Back to Dashboard
                            </a>
                            <button type="button" class="btn btn-secondary" onclick="location.reload()">
                                <iconify-icon icon="solar:refresh-outline" class="icon"></iconify-icon>
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($availableCourses) && method_exists($availableCourses, 'hasPages') && $availableCourses->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $availableCourses->links() }}
        </div>
    @endif
</div>

<!-- Enrollment Confirmation Modal -->
<div class="modal fade" id="enrollmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <iconify-icon icon="solar:user-plus-outline" class="icon me-2"></iconify-icon>
                    Confirm Enrollment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="enrollment-icon mb-3">
                        <iconify-icon icon="solar:book-open-outline" class="icon text-4xl text-primary-600"></iconify-icon>
                    </div>
                    <p class="mb-2">Are you sure you want to enroll in</p>
                    <h6 class="text-primary-600" id="courseTitle"></h6>
                </div>
                <div class="alert alert-info">
                    <iconify-icon icon="solar:info-circle-outline" class="icon me-2"></iconify-icon>
                    Once enrolled, you will have access to all course materials, assignments, and announcements.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <iconify-icon icon="solar:close-circle-outline" class="icon"></iconify-icon>
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmEnrollBtn">
                    <iconify-icon icon="solar:user-plus-outline" class="icon"></iconify-icon>
                    Confirm Enrollment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Course Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title">
                    <iconify-icon icon="solar:eye-outline" class="icon me-2"></iconify-icon>
                    Course Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="previewContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading course details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <iconify-icon icon="solar:close-circle-outline" class="icon"></iconify-icon>
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="enrollFromPreview" style="display: none;">
                    <iconify-icon icon="solar:user-plus-outline" class="icon"></iconify-icon>
                    Enroll Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Container -->
<div id="alertContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

<!-- Include Iconify -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>

<!-- Custom Styles -->
<style>
/* Filter Card */
.filter-card {
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Course Cards */
.course-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    border-color: #3b82f6;
}

/* Course Image Container */
.course-image-container {
    height: 220px;
    overflow: hidden;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
}

.course-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.course-card:hover .course-image {
    transform: scale(1.05);
}

/* Level and Semester Badges */
.level-badge {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    font-size: 0.75rem;
    font-weight: 600;
}

.semester-badge {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
    box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
    font-size: 0.75rem;
    font-weight: 600;
}

.course-level-indicator .badge {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    font-size: 0.7rem;
    min-width: 32px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Course Info Grid */
.course-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
}

.info-card {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.info-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.info-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-icon .icon {
    font-size: 16px;
}

.info-content {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.info-label {
    font-size: 0.7rem;
    color: #6b7280;
    font-weight: 500;
    line-height: 1;
}

.info-value {
    font-size: 0.8rem;
    font-weight: 600;
    color: #374151;
    line-height: 1.2;
}

/* Level Info Specific Styling */
.level-info {
    border-color: #f59e0b;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
}

.level-info .info-value {
    color: #d97706;
    font-weight: 700;
}

/* Course Stats */
.course-stats {
    background: #f1f5f9;
    border-radius: 6px;
    padding: 8px;
}

.stats-row {
    display: flex;
    justify-content: space-between;
    gap: 12px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 4px;
    flex: 1;
}

.stat-item .icon {
    font-size: 12px;
    flex-shrink: 0;
}

/* Course Overlay */
.course-overlay {
    background: rgba(0, 0, 0, 0.7);
    opacity: 0;
    transition: opacity 0.3s ease;
    backdrop-filter: blur(2px);
}

.course-card:hover .course-overlay {
    opacity: 1;
}

.preview-btn {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.preview-btn:hover {
    transform: scale(1.1);
    background: #3b82f6;
    color: white;
}

/* View Details Button */
.view-details-btn {
    transition: all 0.2s ease;
    font-weight: 500;
}

.view-details-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

/* Instructor Avatar */
.instructor-avatar {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
    transition: all 0.2s ease;
}

.instructor-info:hover .instructor-avatar {
    transform: scale(1.1);
    border-color: #3b82f6;
}

.instructor-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

/* Enroll Button */
.enroll-btn {
    transition: all 0.2s ease;
    font-weight: 600;
}

.enroll-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* Empty State */
.empty-state-card {
    border: 2px dashed #d1d5db;
    background: #f9fafb;
}

.empty-state-icon {
    opacity: 0.5;
}

.max-w-400 {
    max-width: 400px;
}

/* Line Clamp */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Text Break */
.text-break {
    word-break: break-word;
    overflow-wrap: break-word;
}

.min-w-0 {
    min-width: 0;
}

/* Course Item Animation */
.course-item {
    opacity: 0;
    animation: fadeInUp 0.5s ease-out forwards;
}

.course-item:nth-child(1) { animation-delay: 0.1s; }
.course-item:nth-child(2) { animation-delay: 0.2s; }
.course-item:nth-child(3) { animation-delay: 0.3s; }
.course-item:nth-child(4) { animation-delay: 0.4s; }
.course-item:nth-child(5) { animation-delay: 0.5s; }
.course-item:nth-child(6) { animation-delay: 0.6s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modal Enhancements */
.enrollment-icon {
    opacity: 0.8;
}

/* Course Preview Modal Styles */
.course-preview-header {
    background: skyblue;
    color: white;
    padding: 2rem;
    margin: -1px -1px 0 -1px;
}

.course-preview-body {
    padding: 2rem;
}

.preview-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.preview-info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
    border: 1px solid #e9ecef;
}

.preview-info-card .icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.preview-info-card h6 {
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.preview-info-card p {
    margin: 0;
    color: #6c757d;
    font-size: 0.875rem;
}

/* Search Input Enhancement */
.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

/* Button Hover Effects */
.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Badge Improvements */
.badge {
    font-weight: 500;
    letter-spacing: 0.025em;
}

/* Gradient Backgrounds */
.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .course-image-container {
        height: 200px;
    }
}

@media (max-width: 768px) {
    .course-image-container {
        height: 180px;
    }
    
    .instructor-avatar {
        width: 32px;
        height: 32px;
        min-width: 32px;
    }
    
    .course-info-grid {
        grid-template-columns: 1fr;
        gap: 6px;
    }
    
    .preview-btn {
        width: 40px;
        height: 40px;
    }
    
    .card-body {
        padding: 16px;
    }
    
    .level-badge,
    .semester-badge {
        font-size: 0.7rem;
        padding: 4px 6px;
    }
    
    .preview-info-grid {
        grid-template-columns: 1fr;
    }
    
    .course-preview-body {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .course-image-container {
        height: 160px;
    }
    
    .instructor-avatar {
        width: 28px;
        height: 28px;
        min-width: 28px;
    }
    
    .course-card {
        margin-bottom: 16px;
    }
    
    .course-level-indicator .badge {
        min-width: 28px;
        height: 20px;
        font-size: 0.65rem;
    }
}

/* Loading States */
.course-image[src=""] {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Focus States */
.btn:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}
</style>

<!-- Latest version -->
<script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

<!-- Or use the web component version -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<!-- Enhanced JavaScript -->
<script>
let selectedCourseId = null;
let currentPreviewCourse = null;
const enrollmentBaseUrl = "{{ url('student/courses/enroll') }}";

// Filter by semester
function filterBySemester() {
    const semester = document.getElementById('semesterFilter').value;
    window.location.href = `{{ route('student.courses.enroll-courses') }}?semester=${semester}`;
}

// Search courses
function searchCourses() {
    const searchTerm = document.getElementById('courseSearch').value.toLowerCase();
    const courseCards = document.querySelectorAll('.course-item');

    courseCards.forEach(card => {
        const courseCode = card.getAttribute('data-course-code');
        const courseTitle = card.getAttribute('data-course-title');
        
        if (courseCode.includes(searchTerm) || courseTitle.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    // Update results count
    const visibleCards = document.querySelectorAll('.course-item[style="display: block"], .course-item:not([style*="display: none"])').length;
    console.log(`Found ${visibleCards} courses matching "${searchTerm}"`);
}

// Clear search
function clearSearch() {
    document.getElementById('courseSearch').value = '';
    const courseCards = document.querySelectorAll('.course-item');
    courseCards.forEach(card => {
        card.style.display = 'block';
    });
}

// Preview course with detailed information
function previewCourse(courseId, title, code, level, semester, credits, instructor, department, description, studentsCount, assignmentsCount, materialsCount) {
    currentPreviewCourse = {
        id: courseId,
        title: title,
        code: code,
        level: level,
        semester: semester,
        credits: credits,
        instructor: instructor,
        department: department,
        description: description,
        studentsCount: studentsCount,
        assignmentsCount: assignmentsCount,
        materialsCount: materialsCount
    };

    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
    
    // Show loading initially
    document.getElementById('previewContent').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading course details...</p>
        </div>
    `;
    
    // Simulate loading and then show course details
    setTimeout(() => {
        document.getElementById('previewContent').innerHTML = `
            <div class="course-preview-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold">${code}</span>
                            <span class="badge bg-warning-600 text-white px-3 py-2 rounded-pill">Level ${level}</span>
                        </div>
                        <h4 class="mb-2">${title}</h4>
                        <p class="mb-0 opacity-90">${semester} Semester • ${credits} Credit Units</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex flex-column align-items-md-end gap-2">
                            <div class="d-flex align-items-center gap-2 text-white">
                                <iconify-icon icon="solar:users-group-rounded-outline" class="icon"></iconify-icon>
                                <span>${studentsCount} Students Enrolled</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="course-preview-body">
                <!-- Course Info Grid -->
                <div class="preview-info-grid">
                    <div class="preview-info-card">
                        <iconify-icon icon="solar:graduation-cap-outline" class="icon text-warning-600"></iconify-icon>
                        <h6>Level</h6>
                        <p>${level}</p>
                    </div>
                    <div class="preview-info-card">
                        <iconify-icon icon="solar:calendar-outline" class="icon text-info-600"></iconify-icon>
                        <h6>Semester</h6>
                        <p>${semester}</p>
                    </div>
                    <div class="preview-info-card">
                        <iconify-icon icon="solar:star-outline" class="icon text-success-600"></iconify-icon>
                        <h6>Credits</h6>
                        <p>${credits} Units</p>
                    </div>
                    <div class="preview-info-card">
                        <iconify-icon icon="solar:document-text-outline" class="icon text-primary-600"></iconify-icon>
                        <h6>Assignments</h6>
                        <p>${assignmentsCount} Available</p>
                    </div>
                    <div class="preview-info-card">
                        <iconify-icon icon="solar:folder-outline" class="icon text-secondary-600"></iconify-icon>
                        <h6>Materials</h6>
                        <p>${materialsCount} Resources</p>
                    </div>
                    <div class="preview-info-card">
                        <iconify-icon icon="solar:users-group-rounded-outline" class="icon text-purple-600"></iconify-icon>
                        <h6>Enrolled</h6>
                        <p>${studentsCount} Students</p>
                    </div>
                </div>
                
                <!-- Course Description -->
                <div class="mb-4">
                    <h5 class="mb-3">
                        <iconify-icon icon="solar:document-text-outline" class="icon me-2"></iconify-icon>
                        Course Description
                    </h5>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">${description}</p>
                    </div>
                </div>
                
                <!-- Instructor Information -->
                <div class="mb-4">
                    <h5 class="mb-3">
                        <iconify-icon icon="solar:user-outline" class="icon me-2"></iconify-icon>
                        Instructor Information
                    </h5>
                    <div class="bg-light p-3 rounded">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-1">${instructor}</h6>
                                <p class="text-muted mb-0">${department}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">Course Instructor</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Course Features -->
                <div class="mb-4">
                    <h5 class="mb-3">
                        <iconify-icon icon="solar:star-outline" class="icon me-2"></iconify-icon>
                        What You'll Get
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <iconify-icon icon="solar:check-circle-outline" class="icon text-success me-2"></iconify-icon>
                                    Access to all course materials
                                </li>
                                <li class="mb-2">
                                    <iconify-icon icon="solar:check-circle-outline" class="icon text-success me-2"></iconify-icon>
                                    ${assignmentsCount} assignments and quizzes
                                </li>
                                <li class="mb-2">
                                    <iconify-icon icon="solar:check-circle-outline" class="icon text-success me-2"></iconify-icon>
                                    Direct instructor communication
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <iconify-icon icon="solar:check-circle-outline" class="icon text-success me-2"></iconify-icon>
                                    ${materialsCount} learning resources
                                </li>
                                <li class="mb-2">
                                    <iconify-icon icon="solar:check-circle-outline" class="icon text-success me-2"></iconify-icon>
                                    Progress tracking
                                </li>
                                <li class="mb-2">
                                    <iconify-icon icon="solar:check-circle-outline" class="icon text-success me-2"></iconify-icon>
                                    Certificate upon completion
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Show enroll button in modal footer
        document.getElementById('enrollFromPreview').style.display = 'inline-block';
    }, 800);
}

// Enroll in course
function enrollInCourse(courseId, courseTitle) {
    selectedCourseId = courseId;
    document.getElementById('courseTitle').textContent = courseTitle;
    
    const modal = new bootstrap.Modal(document.getElementById('enrollmentModal'));
    modal.show();
}

// Enroll from preview modal
document.getElementById('enrollFromPreview').addEventListener('click', function() {
    if (currentPreviewCourse) {
        // Close preview modal
        bootstrap.Modal.getInstance(document.getElementById('previewModal')).hide();
        
        // Open enrollment modal
        setTimeout(() => {
            enrollInCourse(currentPreviewCourse.id, currentPreviewCourse.title);
        }, 300);
    }
});

// Confirm enrollment
document.getElementById('confirmEnrollBtn').addEventListener('click', function() {
    if (!selectedCourseId) return;

    const btn = this;
    const originalText = btn.innerHTML;
    
    // Show loading state
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enrolling...';
    btn.disabled = true;

    // Make AJAX request
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
            
            // Hide the course card with animation
            const courseCard = document.querySelector(`[data-course-id="${selectedCourseId}"]`);
            if (courseCard) {
                courseCard.style.transition = 'all 0.3s ease';
                courseCard.style.transform = 'scale(0.8)';
                courseCard.style.opacity = '0';
                setTimeout(() => {
                    courseCard.style.display = 'none';
                }, 300);
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

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Image loading error handling
    const images = document.querySelectorAll('.course-image, .instructor-avatar');
    images.forEach(img => {
        img.addEventListener('error', function() {
            if (this.classList.contains('course-image')) {
                this.src = '{{ asset("assets/images/thumbs/course-default.png") }}';
            } else if (this.classList.contains('instructor-avatar')) {
                this.src = '{{ asset("assets/images/default-avatar.png") }}';
            }
        });
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    console.log('Enroll courses page loaded with enhanced course preview functionality');
});
</script>
</x-student-layout>