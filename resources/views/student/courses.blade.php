<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <!-- Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">My Courses</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">My Courses</li>
        </ul>
    </div>

    <!-- Course Statistics -->
    <div class="row gy-4 mb-24">
        <div class="col-xxl-3 col-sm-6">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon-container w-56-px h-56-px bg-primary-50 text-primary-600 rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                            <iconify-icon icon="solar:book-outline" class="icon text-2xl"></iconify-icon>
                        </div>
                        <div class="flex-grow-1">
                            <span class="d-block fw-medium text-secondary-light text-sm mb-1">Total Enrolled</span>
                            <h4 class="fw-bold text-primary-light mb-0">{{ $totalEnrolled ?? 0 }}</h4>
                            <span class="text-xs text-success-main">
                                <iconify-icon icon="solar:check-circle-outline" class="icon"></iconify-icon>
                                Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon-container w-56-px h-56-px bg-success-50 text-success-600 rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                            <iconify-icon icon="solar:document-text-outline" class="icon text-2xl"></iconify-icon>
                        </div>
                        <div class="flex-grow-1">
                            <span class="d-block fw-medium text-secondary-light text-sm mb-1">Active Assignments</span>
                            <h4 class="fw-bold text-primary-light mb-0">{{ $enrolledCourses->sum('assignments_count') ?? 0 }}</h4>
                            <span class="text-xs text-warning-main">
                                <iconify-icon icon="solar:clock-circle-outline" class="icon"></iconify-icon>
                                Pending
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon-container w-56-px h-56-px bg-info-50 text-info-600 rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                            <iconify-icon icon="solar:folder-outline" class="icon text-2xl"></iconify-icon>
                        </div>
                        <div class="flex-grow-1">
                            <span class="d-block fw-medium text-secondary-light text-sm mb-1">Course Materials</span>
                            <h4 class="fw-bold text-primary-light mb-0">{{ $enrolledCourses->sum('materials_count') ?? 0 }}</h4>
                            <span class="text-xs text-info-main">
                                <iconify-icon icon="solar:download-outline" class="icon"></iconify-icon>
                                Available
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon-container w-56-px h-56-px bg-warning-50 text-warning-600 rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                            <iconify-icon icon="solar:user-outline" class="icon text-2xl"></iconify-icon>
                        </div>
                        <div class="flex-grow-1">
                            <span class="d-block fw-medium text-secondary-light text-sm mb-1">Current Level</span>
                            <h4 class="fw-bold text-primary-light mb-0">{{ $user->level ?? 'N/A' }}</h4>
                            <span class="text-xs text-secondary-light">
                                <iconify-icon icon="solar:graduation-cap-outline" class="icon"></iconify-icon>
                                Level
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex flex-wrap align-items-center gap-3 mb-24">
        <a href="{{ route('student.courses.enroll-courses') }}" class="btn btn-primary action-btn">
            <iconify-icon icon="solar:user-plus-outline" class="icon"></iconify-icon>
            Enroll in More Courses
        </a>
        <a href="{{ route('student.assignments.index') }}" class="btn btn-primary action-btn">
            <iconify-icon icon="solar:document-text-outline" class="icon"></iconify-icon>
            View All Assignments
        </a>
        <a href="{{ route('student.materials.index') }}" class="btn btn-info action-btn">
            <iconify-icon icon="solar:folder-outline" class="icon"></iconify-icon>
            Browse Materials
        </a>
    </div>

    <!-- Enrolled Courses -->
    <div class="row" id="coursesGrid">
        @forelse($enrolledCourses ?? [] as $course)
            <div class="col-xxl-4 col-lg-6 mb-24 course-item">
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
                        <!-- Quick Actions Overlay -->
                        <div class="course-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <div class="d-flex gap-2">
                               
                                <div class="dropdown">
                                    
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('student.materials.index') }}?course={{ $course->id }}">
                                                <iconify-icon icon="solar:folder-outline" class="icon"></iconify-icon>
                                                Materials ({{ $course->materials_count ?? 0 }})
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('student.assignments.index') }}?course={{ $course->id }}">
                                                <iconify-icon icon="solar:document-text-outline" class="icon"></iconify-icon>
                                                Assignments ({{ $course->assignments_count ?? 0 }})
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('student.submissions.index') }}?course={{ $course->id }}">
                                                <iconify-icon icon="solar:file-check-outline" class="icon"></iconify-icon>
                                                My Submissions
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('student.grades.index') }}?course={{ $course->id }}">
                                                <iconify-icon icon="solar:medal-star-outline" class="icon"></iconify-icon>
                                                Grades
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
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
                            <div class="info-card enrollment-info">
                                <div class="info-icon">
                                    <iconify-icon icon="solar:check-circle-outline" class="icon text-primary-600"></iconify-icon>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Status</span>
                                    <span class="info-value">Enrolled</span>
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

                        <!-- Recent Activities -->
                        @if(isset($course->recentActivities) && $course->recentActivities->count() > 0)
                            <div class="recent-activities mb-3">
                                <h6 class="text-sm fw-semibold mb-2 text-success-600">
                                    <iconify-icon icon="solar:clock-circle-outline" class="icon"></iconify-icon>
                                    Recent Activities
                                </h6>
                                <div class="activities-list">
                                    @foreach($course->recentActivities->take(2) as $activity)
                                        <div class="activity-item d-flex align-items-center gap-2 mb-1">
                                            <iconify-icon icon="{{ $activity['type'] == 'material' ? 'solar:folder-outline' : 'solar:document-text-outline' }}" 
                                                          class="icon text-xs text-secondary-light flex-shrink-0"></iconify-icon>
                                            <span class="text-xs text-secondary-light text-break">{{ Str::limit($activity['title'], 25) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Upcoming Assignments -->
                        @if(isset($course->upcomingAssignments) && $course->upcomingAssignments->count() > 0)
                            <div class="upcoming-assignments mb-3">
                                <h6 class="text-sm fw-semibold mb-2 text-warning-600">
                                    <iconify-icon icon="solar:alarm-outline" class="icon"></iconify-icon>
                                    Upcoming Deadlines
                                </h6>
                                <div class="assignments-list">
                                    @foreach($course->upcomingAssignments->take(2) as $assignment)
                                        <div class="assignment-item d-flex align-items-center justify-content-between mb-1">
                                            <span class="text-xs text-break flex-grow-1">{{ Str::limit($assignment->title, 20) }}</span>
                                            <span class="text-xs text-warning-600 fw-medium flex-shrink-0 ms-2">
                                                {{ $assignment->deadline->format('M d') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="course-actions d-flex gap-2 mt-auto">
                            <a href="{{ route('student.courses.show', $course->slug) }}" 
                               class="btn btn-primary flex-grow-1 course-action-btn">
                                <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                View {{ $course->level }} Level Course
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-primary course-action-btn dropdown-toggle" 
                                        type="button" 
                                        data-bs-toggle="dropdown">
                                    <iconify-icon icon="solar:menu-dots-outline" class="icon"></iconify-icon>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('student.materials.index') }}?course={{ $course->id }}">
                                            <iconify-icon icon="solar:folder-outline" class="icon"></iconify-icon>
                                            Materials ({{ $course->materials_count ?? 0 }})
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('student.assignments.index') }}?course={{ $course->id }}">
                                            <iconify-icon icon="solar:document-text-outline" class="icon"></iconify-icon>
                                            Assignments ({{ $course->assignments_count ?? 0 }})
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('student.submissions.index') }}?course={{ $course->id }}">
                                            <iconify-icon icon="solar:file-check-outline" class="icon"></iconify-icon>
                                            My Submissions
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('student.grades.index') }}?course={{ $course->id }}">
                                            <iconify-icon icon="solar:medal-star-outline" class="icon"></iconify-icon>
                                            Grades
                                        </a>
                                    </li>
                                </ul>
                            </div>
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
                        <h5 class="mb-2">No Enrolled Courses</h5>
                        <p class="text-secondary-light mb-4 max-w-400 mx-auto">
                            You haven't enrolled in any courses yet. Browse available courses and start your learning journey today.
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('student.courses.enroll-courses') }}" class="btn btn-primary">
                                <iconify-icon icon="solar:user-plus-outline" class="icon"></iconify-icon>
                                Browse Available Courses
                            </a>
                            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                                <iconify-icon icon="solar:home-outline" class="icon"></iconify-icon>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($enrolledCourses) && method_exists($enrolledCourses, 'hasPages') && $enrolledCourses->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $enrolledCourses->links() }}
        </div>
    @endif
</div>

<!-- Custom Styles -->
<style>
/* Stats Cards */
.stats-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #3b82f6;
}

.stats-icon-container {
    transition: all 0.2s ease;
}

.stats-card:hover .stats-icon-container {
    transform: scale(1.1);
}

/* Action Buttons */
.action-btn {
    transition: all 0.2s ease;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

.overlay-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.overlay-btn:hover {
    transform: scale(1.1);
    background: #3b82f6;
    color: white;
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

/* Activities and Assignments */
.recent-activities,
.upcoming-assignments {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
}

.activity-item,
.assignment-item {
    padding: 2px 0;
}

/* Course Actions */
.course-action-btn {
    transition: all 0.2s ease;
}

.course-action-btn:hover {
    transform: translateY(-1px);
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

/* Dropdown Improvements */
.dropdown-menu {
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    padding: 8px 0;
}

.dropdown-item {
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f3f4f6;
    transform: translateX(2px);
}

.dropdown-item .icon {
    font-size: 16px;
    width: 16px;
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
    
    .stats-icon-container {
        width: 48px !important;
        height: 48px !important;
    }
    
    .course-info-grid {
        grid-template-columns: 1fr;
        gap: 6px;
    }
    
    .course-actions {
        flex-direction: column;
    }
    
    .course-actions .btn {
        width: 100%;
    }
    
    .overlay-btn {
        width: 36px;
        height: 36px;
    }
    
    .level-badge,
    .semester-badge {
        font-size: 0.7rem;
        padding: 4px 6px;
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
    
    .stats-icon-container {
        width: 40px !important;
        height: 40px !important;
    }
    
    .course-card {
        margin-bottom: 16px;
    }
    
    .card-body {
        padding: 16px;
    }
    
    .action-btn {
        font-size: 0.875rem;
        padding: 8px 16px;
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
.btn:focus,
.dropdown-toggle:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}
</style>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

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

    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Course card hover effects
    const courseCards = document.querySelectorAll('.course-card');
    courseCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });

    // Smooth scroll for pagination
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            setTimeout(() => {
                document.querySelector('#coursesGrid').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
        });
    });

    console.log('Student courses page loaded with enhanced level visibility');
});
</script>
</x-student-layout>