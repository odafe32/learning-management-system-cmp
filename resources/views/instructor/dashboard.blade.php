<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <!-- Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Instructor Dashboard</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
        </ul>
    </div>

    <!-- Success Message -->
    <x-success-message />

    <!-- Welcome Section -->
    <div class="row mb-24">
        <div class="col-12">
            <div class="card radius-8 border-0 overflow-hidden bg-gradient-primary">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-white">
                            <h4 class="text-white mb-8">Welcome back, {{ Auth::user()->name }}!</h4>
                            <p class="text-white-75 mb-0">Here's what's happening with your courses today.</p>
                            <div class="d-flex align-items-center gap-3 mt-3">
                                <span class="badge bg-white bg-opacity-20 text-black px-12 py-6 rounded-4">
                                    {{ Auth::user()->department ?? 'N/A' }}
                                </span>
                                <span class="text-white-75 text-sm">
                                    <iconify-icon icon="solar:calendar-outline" class="icon"></iconify-icon>
                                    {{ now()->format('l, F j, Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-white d-none d-md-block">
                            <iconify-icon icon="solar:graduation-cap-outline" class="icon text-6xl opacity-50"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row gy-4 mb-24">
        <div class="col-xxl-3 col-sm-6">
            <div class="card radius-8 border-0 overflow-hidden h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center gap-16">
                        <div class="w-64-px h-64-px bg-primary-50 text-primary rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                            <iconify-icon icon="solar:book-open-outline" class="icon text-2xl"></iconify-icon>
                        </div>
                        <div class="flex-grow-1">
                            <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Total Courses</span>
                            <h4 class="fw-bold text-primary-light mb-0">{{ number_format($coursesCount) }}</h4>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <span class="text-sm text-success-main">{{ $activeCourses }}</span>
                                <span class="text-xs text-secondary-light">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xxl-3 col-sm-6">
            <div class="card radius-8 border-0 overflow-hidden h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center gap-16">
                        <div class="w-64-px h-64-px bg-success-50 text-success rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                            <iconify-icon icon="solar:folder-outline" class="icon text-2xl"></iconify-icon>
                        </div>
                        <div class="flex-grow-1">
                            <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Materials</span>
                            <h4 class="fw-bold text-primary-light mb-0">{{ number_format($materialsCount) }}</h4>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <iconify-icon icon="solar:trend-up-outline" class="icon text-success-main text-sm"></iconify-icon>
                                <span class="text-xs text-secondary-light">Uploaded</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card radius-8 border-0 overflow-hidden h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center gap-16">
                        <div class="w-64-px h-64-px bg-warning-50 text-warning rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                            <iconify-icon icon="solar:clipboard-text-outline" class="icon text-2xl"></iconify-icon>
                        </div>
                        <div class="flex-grow-1">
                            <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Assignments</span>
                            <h4 class="fw-bold text-primary-light mb-0">{{ number_format($assignmentsCount ?? 0) }}</h4>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <span class="text-sm text-warning-main">{{ $pendingGrading ?? 0 }}</span>
                                <span class="text-xs text-secondary-light">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card radius-8 border-0 overflow-hidden h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center gap-16">
                        <div class="w-64-px h-64-px bg-info-50 text-info rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                            <iconify-icon icon="solar:users-group-rounded-outline" class="icon text-2xl"></iconify-icon>
                        </div>
                        <div class="flex-grow-1">
                            <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Students</span>
                            <h4 class="fw-bold text-primary-light mb-0">{{ number_format($studentsCount ?? 0) }}</h4>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <span class="text-sm text-info-main">{{ $activeStudents ?? 0 }}</span>
                                <span class="text-xs text-secondary-light">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row gy-4 mb-24">
        <div class="col-12">
            <div class="card radius-8 border-0">
                <div class="card-header border-bottom border-gray-100">
                    <h6 class="mb-0 fw-semibold">Quick Actions</h6>
                </div>
                <div class="card-body p-20">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary w-100 d-flex flex-column align-items-center gap-2 py-3 quick-action-btn">
                                <iconify-icon icon="solar:add-circle-outline" class="icon text-2xl"></iconify-icon>
                                <span class="text-sm">Create Course</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('instructor.materials.upload') }}" class="btn btn-success w-100 d-flex flex-column align-items-center gap-2 py-3 quick-action-btn">
                                <iconify-icon icon="solar:upload-outline" class="icon text-2xl"></iconify-icon>
                                <span class="text-sm">Upload Material</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('instructor.assignments.create') }}" class="btn btn-warning w-100 d-flex flex-column align-items-center gap-2 py-3 quick-action-btn">
                                <iconify-icon icon="solar:clipboard-outline" class="icon text-2xl"></iconify-icon>
                                <span class="text-sm">New Assignment</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('instructor.submissions.grade') }}" class="btn btn-info w-100 d-flex flex-column align-items-center gap-2 py-3 quick-action-btn">
                                <iconify-icon icon="solar:check-circle-outline" class="icon text-2xl"></iconify-icon>
                                <span class="text-sm">Grade Work</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('instructor.students.index') }}" class="btn btn-secondary w-100 d-flex flex-column align-items-center gap-2 py-3 quick-action-btn">
                                <iconify-icon icon="solar:users-group-rounded-outline" class="icon text-2xl"></iconify-icon>
                                <span class="text-sm">View Students</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="position-relative">
                                <a href="{{ route('instructor.messages.index') }}" class="btn btn-danger w-100 d-flex flex-column align-items-center gap-2 py-3 quick-action-btn">
                                    <iconify-icon icon="solar:letter-outline" class="icon text-2xl"></iconify-icon>
                                    <span class="text-sm">Messages</span>
                                </a>
                                @if(($unreadMessages ?? 0) > 0)
                                    <span class="badge bg-white text-danger rounded-pill position-absolute top-0 end-0 translate-middle">{{ $unreadMessages }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row gy-4">
        <!-- Recent Courses -->
        <div class="col-lg-8">
            <div class="card radius-8 border-0">
                <div class="card-header border-bottom border-gray-100 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="mb-0 fw-semibold">Recent Courses</h6>
                    <a href="{{ route('instructor.courses.manage') }}" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                        View All
                        <iconify-icon icon="solar:arrow-right-outline" class="icon text-sm"></iconify-icon>
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($recentCourses as $course)
                        <div class="p-20 border-bottom border-gray-100 hover-bg-gray-50 course-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-16">
                                    <!-- Fixed Course Image -->
                                    <div class="flex-shrink-0">
                                        @if($course->image)
                                            <img 
                                                src="{{ Storage::url($course->image) }}" 
                                                alt="{{ $course->title }}" 
                                                class="course-thumbnail rounded-8 object-fit-cover"
                                                style="width: 60px; height: 60px; min-width: 60px;"
                                                loading="lazy"
                                                onerror="this.src='{{ asset('assets/images/thumbs/course-default.png') }}'"
                                            >
                                        @else
                                            <div class="course-thumbnail-placeholder bg-primary-50 rounded-8 d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; min-width: 60px;">
                                                <iconify-icon icon="solar:book-open-outline" class="icon text-primary text-xl"></iconify-icon>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-1 fw-medium text-break">{{ $course->title }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $course->code }} • {{ ucfirst($course->level) }} Level</span>
                                        <div class="d-flex align-items-center gap-8 mt-2">
                                            <span class="badge bg-{{ $course->status === 'active' ? 'success' : ($course->status === 'draft' ? 'warning' : 'secondary') }}-50 text-{{ $course->status === 'active' ? 'success' : ($course->status === 'draft' ? 'warning' : 'secondary') }}-600 px-8 py-2 rounded-pill text-xs">
                                                {{ ucfirst($course->status) }}
                                            </span>
                                            <span class="text-xs text-secondary-light">{{ $course->credit_units }} Credit{{ $course->credit_units > 1 ? 's' : '' }}</span>
                                            <span class="text-xs text-secondary-light">
                                                <iconify-icon icon="solar:users-group-rounded-outline" class="icon"></iconify-icon>
                                                {{ $course->students_count ?? 0 }} Students
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('instructor.courses.show', $course) }}">
                                                    <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                                    View Course
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('instructor.courses.edit', $course) }}">
                                                    <iconify-icon icon="solar:pen-outline" class="icon"></iconify-icon>
                                                    Edit Course
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('instructor.assignments.index', ['course' => $course->id]) }}">
                                                    <iconify-icon icon="solar:clipboard-text-outline" class="icon"></iconify-icon>
                                                    Assignments
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('instructor.materials.index', ['course' => $course->id]) }}">
                                                    <iconify-icon icon="solar:folder-outline" class="icon"></iconify-icon>
                                                    Materials
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <iconify-icon icon="solar:book-open-outline" class="icon text-6xl text-secondary-light mb-3"></iconify-icon>
                            <h6 class="mb-2">No courses created yet</h6>
                            <p class="text-secondary-light mb-3">Start by creating your first course to manage students and assignments.</p>
                            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
                                <iconify-icon icon="solar:add-circle-outline" class="icon"></iconify-icon>
                                Create Your First Course
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Upcoming Deadlines -->
            <div class="card radius-8 border-0 mb-24">
                <div class="card-header border-bottom border-gray-100">
                    <h6 class="mb-0 fw-semibold">Upcoming Deadlines</h6>
                </div>
                <div class="card-body p-0">
                    @forelse($upcomingDeadlines ?? [] as $deadline)
                        <div class="p-16 border-bottom border-gray-100">
                            <div class="d-flex align-items-start gap-12">
                                <div class="w-8-px h-8-px bg-warning rounded-circle mt-8 flex-shrink-0"></div>
                                <div class="flex-grow-1">
                                    <h6 class="text-sm mb-1 fw-medium text-break">{{ $deadline->title }}</h6>
                                    <p class="text-xs text-secondary-light mb-2">{{ $deadline->course->code }}</p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-xs text-warning-main">
                                            <iconify-icon icon="solar:calendar-outline" class="icon"></iconify-icon>
                                            Due {{ $deadline->deadline->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <iconify-icon icon="solar:calendar-check-outline" class="icon text-4xl text-secondary-light mb-2"></iconify-icon>
                            <p class="text-sm text-secondary-light">No upcoming deadlines</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card radius-8 border-0">
                <div class="card-header border-bottom border-gray-100">
                    <h6 class="mb-0 fw-semibold">Recent Activity</h6>
                </div>
                <div class="card-body p-0">
                    @forelse($recentActivities ?? [] as $activity)
                        <div class="p-16 border-bottom border-gray-100">
                            <div class="d-flex align-items-start gap-12">
                                <div class="w-32-px h-32-px bg-{{ $activity['color'] ?? 'primary' }}-50 rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                    <iconify-icon icon="solar:{{ $activity['icon'] ?? 'bell' }}-outline" class="icon text-{{ $activity['color'] ?? 'primary' }} text-sm"></iconify-icon>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-sm mb-1">{{ $activity['message'] }}</p>
                                    <span class="text-xs text-secondary-light">{{ $activity['time'] }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <iconify-icon icon="solar:clock-circle-outline" class="icon text-4xl text-secondary-light mb-2"></iconify-icon>
                            <p class="text-sm text-secondary-light">No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row gy-4 mt-24">
        <div class="col-lg-8">
            <div class="card radius-8 border-0">
                <div class="card-header border-bottom border-gray-100">
                    <h6 class="mb-0 fw-semibold">Course Enrollment Trends</h6>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="enrollmentChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card radius-8 border-0">
                <div class="card-header border-bottom border-gray-100">
                    <h6 class="mb-0 fw-semibold">Assignment Status</h6>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="assignmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple test data
    const enrollmentData = [5, 10, 8, 15, 12, 20, 18, 25, 22, 30, 28, 35];
    const assignmentData = {
        graded: {{ $gradedAssignments ?? 10 }},
        pending: {{ $pendingGrading ?? 5 }},
        overdue: {{ $overdueAssignments ?? 2 }}
    };

    // Enrollment Chart
    const enrollmentCtx = document.getElementById('enrollmentChart');
    if (enrollmentCtx) {
        new Chart(enrollmentCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Student Enrollments',
                    data: enrollmentData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Assignment Chart
    const assignmentCtx = document.getElementById('assignmentChart');
    if (assignmentCtx) {
        new Chart(assignmentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Graded', 'Pending', 'Overdue'],
                datasets: [{
                    data: [assignmentData.graded, assignmentData.pending, assignmentData.overdue],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>

<!-- Custom Styles -->
<style>
/* Gradient Background */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75);
}

/* Fixed Image Dimensions */
.course-thumbnail {
    transition: transform 0.2s ease;
}

.course-thumbnail:hover {
    transform: scale(1.05);
}

.course-thumbnail-placeholder {
    transition: all 0.2s ease;
}

.course-thumbnail-placeholder:hover {
    transform: scale(1.05);
    background-color: var(--bs-primary-100) !important;
}

/* Course Item Hover Effects */
.course-item {
    transition: all 0.2s ease;
}

.course-item:hover {
    background-color: #f9fafb !important;
    transform: translateX(4px);
}

/* Quick Action Buttons */
.quick-action-btn {
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.quick-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Statistics Cards */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* Badge Positioning */
.position-relative .badge {
    font-size: 0.7rem;
    min-width: 1.5rem;
    height: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Chart Container */
canvas {
    max-height: 300px !important;
}

/* Text Break for Long Titles */
.text-break {
    word-break: break-word;
    overflow-wrap: break-word;
}

/* Responsive Design */
@media (max-width: 768px) {
    .course-thumbnail,
    .course-thumbnail-placeholder {
        width: 50px !important;
        height: 50px !important;
        min-width: 50px !important;
    }
    
    .quick-action-btn {
        font-size: 0.8rem;
        padding: 0.75rem 0.5rem;
    }
    
    .quick-action-btn .icon {
        font-size: 1.5rem !important;
    }
    
    .w-64-px {
        width: 48px !important;
        height: 48px !important;
    }
    
    .course-item {
        padding: 16px !important;
    }
    
    .course-item .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px;
    }
    
    .course-item .d-flex > .d-flex {
        flex-direction: row;
        align-items: center !important;
        width: 100%;
    }
}

@media (max-width: 576px) {
    .course-thumbnail,
    .course-thumbnail-placeholder {
        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;
    }
    
    .bg-gradient-primary .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        text-align: left;
    }
    
    .bg-gradient-primary .text-white:last-child {
        display: none !important;
    }
}

/* Loading States */
.chart-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 200px;
    color: #6b7280;
}

/* Dropdown Menu Improvements */
.dropdown-menu {
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.dropdown-item {
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
}

/* Empty State Improvements */
.text-center .icon {
    opacity: 0.5;
}

/* Animation for Cards */
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

.card {
    animation: fadeInUp 0.5s ease-out;
}

/* Improved Badge Styles */
.badge {
    font-weight: 500;
    letter-spacing: 0.025em;
}

/* Better Focus States */
.btn:focus,
.dropdown-toggle:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}
</style>
</x-instructor-layout>