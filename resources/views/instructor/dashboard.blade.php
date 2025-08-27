<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Instructor Dashboard</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ph ph-house text-lg"></i>
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
                            </div>
                            <div class="text-white">
                                <i class="ph ph-graduation-cap text-6xl opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row gy-4 mb-24">
            <div class="col-xxl-3 col-sm-6">
                <div class="card radius-8 border-0 overflow-hidden">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center gap-16">
                            <div class="w-64-px h-64-px bg-primary-50 text-primary rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                <i class="ph ph-book-open text-2xl"></i>
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
                <div class="card radius-8 border-0 overflow-hidden">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center gap-16">
                            <div class="w-64-px h-64-px bg-success-50 text-success rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                <i class="ph ph-file-text text-2xl"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Materials</span>
                                <h4 class="fw-bold text-primary-light mb-0">{{ number_format($materialsCount) }}</h4>
                                <div class="d-flex align-items-center gap-1 mt-1">
                                    <i class="ph ph-trend-up text-success-main text-sm"></i>
                                    <span class="text-xs text-secondary-light">Uploaded</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-sm-6">
                <div class="card radius-8 border-0 overflow-hidden">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center gap-16">
                            <div class="w-64-px h-64-px bg-warning-50 text-warning rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                <i class="ph ph-clipboard-text text-2xl"></i>
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
                <div class="card radius-8 border-0 overflow-hidden">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center gap-16">
                            <div class="w-64-px h-64-px bg-info-50 text-info rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                <i class="ph ph-users text-2xl"></i>
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
                                <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary w-100 d-flex flex-column align-items-center gap-2 py-3">
                                    <i class="ph ph-plus-circle text-2xl"></i>
                                    <span class="text-sm">Create Course</span>
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <a href="{{ route('instructor.materials.upload') }}" class="btn btn-success w-100 d-flex flex-column align-items-center gap-2 py-3">
                                    <i class="ph ph-upload text-2xl"></i>
                                    <span class="text-sm">Upload Material</span>
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <a href="{{ route('instructor.assignments.create') }}" class="btn btn-warning w-100 d-flex flex-column align-items-center gap-2 py-3">
                                    <i class="ph ph-clipboard text-2xl"></i>
                                    <span class="text-sm">New Assignment</span>
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <a href="{{ route('instructor.submissions.grade') }}" class="btn btn-info w-100 d-flex flex-column align-items-center gap-2 py-3">
                                    <i class="ph ph-check-circle text-2xl"></i>
                                    <span class="text-sm">Grade Work</span>
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <a href="{{ route('instructor.students.index') }}" class="btn btn-secondary w-100 d-flex flex-column align-items-center gap-2 py-3">
                                    <i class="ph ph-users-three text-2xl"></i>
                                    <span class="text-sm">View Students</span>
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <a href="{{ route('instructor.messages.index') }}" class="btn btn-danger w-100 d-flex flex-column align-items-center gap-2 py-3">
                                    <i class="ph ph-envelope text-2xl"></i>
                                    <span class="text-sm">Messages</span>
                                    @if(($unreadMessages ?? 0) > 0)
                                        <span class="badge bg-white text-danger rounded-pill position-absolute top-0 end-0 translate-middle">{{ $unreadMessages }}</span>
                                    @endif
                                </a>
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
                    <div class="card-header border-bottom border-gray-100 flex-between flex-wrap gap-8">
                        <h6 class="mb-0 fw-semibold">Recent Courses</h6>
                        <a href="{{ route('instructor.courses.manage') }}" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            View All
                            <i class="ph ph-arrow-right text-sm"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @forelse($recentCourses as $course)
                            <div class="p-20 border-bottom border-gray-100 hover-bg-gray-50">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-12">
                                        <div class="w-44-px h-44-px bg-primary-50 rounded-8 d-flex justify-content-center align-items-center flex-shrink-0">
                                            @if($course->image)
                                                <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}" class="w-44-px h-44-px rounded-8 object-fit-cover">
                                            @else
                                                <i class="ph ph-book-open text-primary text-xl"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="text-md mb-0 fw-medium">{{ $course->title }}</h6>
                                            <span class="text-sm text-secondary-light">{{ $course->code }} • {{ ucfirst($course->level) }} Level</span>
                                            <div class="d-flex align-items-center gap-8 mt-4">
                                                <span class="badge bg-{{ $course->status === 'active' ? 'success' : ($course->status === 'draft' ? 'warning' : 'secondary') }}-50 text-{{ $course->status === 'active' ? 'success' : ($course->status === 'draft' ? 'warning' : 'secondary') }} px-8 py-2 rounded-pill text-xs">
                                                    {{ ucfirst($course->status) }}
                                                </span>
                                                <span class="text-xs text-secondary-light">{{ $course->credit_units }} Credits</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-8">
                                        <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-primary btn-sm">
                                            <i class="ph ph-pencil"></i>
                                        </a>
                                        <a href="{{ route('instructor.courses.manage') }}" class="btn btn-primary btn-sm">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="ph ph-book-open text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-600 mb-3">No courses created yet</p>
                                <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">Create Your First Course</a>
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
                                        <h6 class="text-sm mb-4 fw-medium">{{ $deadline->title }}</h6>
                                        <p class="text-xs text-secondary-light mb-4">{{ $deadline->course->code }}</p>
                                        <span class="text-xs text-warning-main">Due {{ $deadline->deadline->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="ph ph-calendar-check text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">No upcoming deadlines</p>
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
                                        <i class="ph ph-{{ $activity['icon'] ?? 'bell' }} text-{{ $activity['color'] ?? 'primary' }} text-sm"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-sm mb-4">{{ $activity['message'] }}</p>
                                        <span class="text-xs text-secondary-light">{{ $activity['time'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="ph ph-clock text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">No recent activity</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Debug Info (remove after testing) -->
{{-- <div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info">
            <strong>Debug Info:</strong>
            Graded: {{ $gradedAssignments ?? 'NOT SET' }} | 
            Pending: {{ $pendingGrading ?? 'NOT SET' }} | 
            Overdue: {{ $overdueAssignments ?? 'NOT SET' }} | 
            Monthly: {{ isset($monthlyEnrollments) ? 'SET' : 'NOT SET' }}
        </div>
    </div>
</div> --}}
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
        console.log('Enrollment chart created');
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
        console.log('Assignment chart created');
    }
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75);
}

.hover-bg-gray-50:hover {
    background-color: #f9fafb !important;
    transition: background-color 0.2s ease;
}

/* Chart container styling */
canvas {
    max-height: 300px !important;
}

/* Quick action buttons hover effects */
.btn:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Badge positioning for message notification */
.position-relative .badge {
    font-size: 0.7rem;
    min-width: 1.5rem;
    height: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Card animations */
.card {
    opacity: 0.8;
    transform: translateY(10px);
    transition: all 0.3s ease;
}

/* Statistics card hover effects */
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* Loading states */
.chart-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 200px;
    color: #6b7280;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn {
        font-size: 0.8rem;
        padding: 0.5rem;
    }
    
    .text-2xl {
        font-size: 1.5rem;
    }
}
</style>

</x-instructor-layout>