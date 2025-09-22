<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<style>
    /* Simple Dashboard Styles */
    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #f1f5f9;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }
    
    .stat-card {
        padding: 1.5rem;
        text-align: center;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
    }
    
    .stat-primary { background: #eff6ff; color: #2563eb; }
    .stat-success { background: #f0fdf4; color: #16a34a; }
    .stat-warning { background: #fffbeb; color: #d97706; }
    .stat-danger { background: #fef2f2; color: #dc2626; }
    .stat-info { background: #f0f9ff; color: #0284c7; }
    
    .quick-action-card {
        padding: 1.25rem;
        text-align: center;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .quick-action-card:hover {
        color: inherit;
        text-decoration: none;
    }
    
    .quick-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.25rem;
        background: #f8fafc;
        color: #475569;
    }
    
    .quick-action-title {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .quick-action-desc {
        font-size: 0.75rem;
        color: #64748b;
        margin: 0;
    }
    
    .activity-item {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: start;
        gap: 0.75rem;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        flex-shrink: 0;
    }
    
    .activity-content h6 {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #1e293b;
    }
    
    .activity-content p {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0.25rem;
    }
    
    .activity-time {
        font-size: 0.75rem;
        color: #94a3b8;
    }
    
    .progress-bar-custom {
        height: 8px;
        border-radius: 4px;
        background: #f1f5f9;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    .welcome-section {
        background: #003c7c;
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .welcome-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .welcome-subtitle {
        opacity: 0.9;
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: #6366f1;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #64748b;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    .badge-custom {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-weight: 500;
    }
    
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-completed { background: #d1fae5; color: #065f46; }
    .badge-overdue { background: #fee2e2; color: #991b1b; }
    
    @media (max-width: 768px) {
        .stat-number { font-size: 2rem; }
        .welcome-title { font-size: 1.5rem; }
        .section-title { font-size: 1.125rem; }
    }
</style>

<div class="dashboard-main-body">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="welcome-title text-white">Welcome back, {{ $user->name }}! 👋</h1>
                <p class="welcome-subtitle mb-0">
                    Here's what's happening with your studies today. You have 
                    <strong>{{ $pendingAssignments }}</strong> pending assignments and 
                    <strong>{{ $enrolledCoursesCount }}</strong> active courses.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="d-flex flex-column align-items-md-end">
                    <small class="opacity-75">{{ now()->format('l, F j, Y') }}</small>
                    <small class="opacity-75">{{ now()->format('g:i A') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card stat-card">
                <div class="stat-icon stat-primary">
                    <i class="ph ph-book-open"></i>
                </div>
                <span class="stat-number text-primary">{{ $enrolledCoursesCount }}</span>
                <div class="stat-label">Enrolled Courses</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card stat-card">
                <div class="stat-icon stat-warning">
                    <i class="ph ph-clock"></i>
                </div>
                <span class="stat-number text-warning">{{ $pendingAssignments }}</span>
                <div class="stat-label">Pending Assignments</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card stat-card">
                <div class="stat-icon stat-success">
                    <i class="ph ph-trophy"></i>
                </div>
                <span class="stat-number text-success">{{ $recentGrades->count() }}</span>
                <div class="stat-label">Recent Grades</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card stat-card">
                <div class="stat-icon stat-info">
                    <i class="ph ph-calendar-check"></i>
                </div>
                <span class="stat-number text-info">{{ $upcomingDeadlines->count() }}</span>
                <div class="stat-label">Upcoming Deadlines</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <h2 class="section-title">
                <i class="ph ph-lightning"></i>
                Quick Actions
            </h2>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6">
            <a href="{{ route('student.courses.index') }}" class="dashboard-card quick-action-card">
                <div class="quick-action-icon">
                    <i class="ph ph-books"></i>
                </div>
                <div class="quick-action-title">My Courses</div>
                <p class="quick-action-desc">View enrolled courses</p>
            </a>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6">
            <a href="{{ route('student.assignments.index') }}" class="dashboard-card quick-action-card">
                <div class="quick-action-icon">
                    <i class="ph ph-clipboard-text"></i>
                </div>
                <div class="quick-action-title">Assignments</div>
                <p class="quick-action-desc">View & submit work</p>
            </a>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6">
            <a href="{{ route('student.materials.index') }}" class="dashboard-card quick-action-card">
                <div class="quick-action-icon">
                    <i class="ph ph-file-text"></i>
                </div>
                <div class="quick-action-title">Materials</div>
                <p class="quick-action-desc">Course resources</p>
            </a>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6">
            <a href="{{ route('student.grades.index') }}" class="dashboard-card quick-action-card">
                <div class="quick-action-icon">
                    <i class="ph ph-chart-line"></i>
                </div>
                <div class="quick-action-title">Grades</div>
                <p class="quick-action-desc">View performance</p>
            </a>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6">
            <a href="{{ route('student.messages.index') }}" class="dashboard-card quick-action-card">
                <div class="quick-action-icon">
                    <i class="ph ph-chat-circle"></i>
                </div>
                <div class="quick-action-title">Messages</div>
                <p class="quick-action-desc">Chat with lecturers</p>
            </a>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6">
            <a href="{{ route('student.courses.enroll-courses') }}" class="dashboard-card quick-action-card">
                <div class="quick-action-icon">
                    <i class="ph ph-plus-circle"></i>
                </div>
                <div class="quick-action-title">Enroll</div>
                <p class="quick-action-desc">Join new courses</p>
            </a>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Upcoming Deadlines -->
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="p-3 border-bottom">
                    <h3 class="section-title mb-0">
                        <i class="ph ph-alarm"></i>
                        Upcoming Deadlines
                    </h3>
                </div>
                <div class="p-0">
                    @forelse($upcomingDeadlines as $assignment)
                        <div class="activity-item">
                            <div class="activity-icon stat-warning">
                                <i class="ph ph-clock"></i>
                            </div>
                            <div class="activity-content flex-grow-1">
                                <h6>{{ $assignment->title }}</h6>
                                <p>{{ $assignment->course->title }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="activity-time">
                                        Due: {{ $assignment->deadline->format('M j, Y g:i A') }}
                                    </span>
                                    <span class="badge-custom badge-pending">
                                        {{ $assignment->deadline->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="ph ph-calendar-check"></i>
                            <p class="mb-0">No upcoming deadlines</p>
                            <small>You're all caught up!</small>
                        </div>
                    @endforelse
                </div>
                @if($upcomingDeadlines->count() > 0)
                    <div class="p-3 border-top text-center">
                        <a href="{{ route('student.assignments.index') }}" class="btn btn-sm btn-outline-primary">
                            View All Assignments
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Grades -->
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="p-3 border-bottom">
                    <h3 class="section-title mb-0">
                        <i class="ph ph-trophy"></i>
                        Recent Grades
                    </h3>
                </div>
                <div class="p-0">
                    @forelse($recentGrades as $submission)
                        <div class="activity-item">
                            <div class="activity-icon stat-success">
                                <i class="ph ph-check-circle"></i>
                            </div>
                            <div class="activity-content flex-grow-1">
                                <h6>{{ $submission->assignment->title }}</h6>
                                <p>{{ $submission->assignment->course->title }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="activity-time">
                                        Graded: {{ $submission->graded_at->format('M j, Y') }}
                                    </span>
                                    <span class="badge-custom badge-completed">
                                        {{ number_format($submission->grade, 1) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="ph ph-chart-line"></i>
                            <p class="mb-0">No recent grades</p>
                            <small>Complete assignments to see grades here</small>
                        </div>
                    @endforelse
                </div>
                @if($recentGrades->count() > 0)
                    <div class="p-3 border-top text-center">
                        <a href="{{ route('student.grades.index') }}" class="btn btn-sm btn-outline-success">
                            View All Grades
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Course Progress -->
        <div class="col-lg-8">
            <div class="dashboard-card">
                <div class="p-3 border-bottom">
                    <h3 class="section-title mb-0">
                        <i class="ph ph-chart-pie"></i>
                        Course Progress
                    </h3>
                </div>
                <div class="p-3">
                    @forelse($user->enrolledCourses()->withCount(['assignments', 'materials'])->get() as $course)
                        @php
                            $totalAssignments = $course->assignments_count;
                            $completedAssignments = $course->assignments()
                                ->whereHas('submissions', function($q) use ($user) {
                                    $q->where('student_id', $user->id);
                                })->count();
                            $progress = $totalAssignments > 0 ? ($completedAssignments / $totalAssignments) * 100 : 0;
                        @endphp
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-1">{{ $course->title }}</h6>
                                    <small class="text-muted">
                                        {{ $completedAssignments }}/{{ $totalAssignments }} assignments completed
                                    </small>
                                </div>
                                <span class="badge-custom {{ $progress >= 80 ? 'badge-completed' : ($progress >= 50 ? 'badge-pending' : 'badge-overdue') }}">
                                    {{ number_format($progress, 0) }}%
                                </span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill bg-{{ $progress >= 80 ? 'success' : ($progress >= 50 ? 'warning' : 'danger') }}" 
                                     style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="ph ph-book-open"></i>
                            <p class="mb-2">No enrolled courses</p>
                            <a href="{{ route('student.courses.enroll-courses') }}" class="btn btn-sm btn-primary">
                                Enroll in Courses
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="dashboard-card">
                <div class="p-3 border-bottom">
                    <h3 class="section-title mb-0">
                        <i class="ph ph-info"></i>
                        Quick Stats
                    </h3>
                </div>
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Current Level</span>
                        <strong>Level {{ $user->level ?? 'N/A' }}</strong>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Department</span>
                        <strong>{{ $user->department ?? 'N/A' }}</strong>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Student ID</span>
                        <strong>{{ $user->matric_or_staff_id ?? 'N/A' }}</strong>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total Submissions</span>
                        <strong>{{ $user->submissions()->count() }}</strong>
                    </div>
                    
                    @if($user->submissions()->where('status', 'graded')->count() > 0)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Average Grade</span>
                            <strong class="text-success">
                                {{ number_format($user->submissions()->where('status', 'graded')->avg('grade'), 1) }}%
                            </strong>
                        </div>
                    @endif
                </div>
                
                <div class="p-3 border-top">
                    <a href="{{ route('student.profile') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="ph ph-user me-1"></i>
                        Update Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple animations for cards
    const cards = document.querySelectorAll('.dashboard-card');
    
    // Animate cards on load
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Update time every minute
    function updateTime() {
        const now = new Date();
        const timeElements = document.querySelectorAll('.current-time');
        timeElements.forEach(el => {
            el.textContent = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        });
    }
    
    setInterval(updateTime, 60000);
    
    // Simple success message handling
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const closeBtn = alert.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }
        
        // Auto dismiss success alerts
        if (alert.classList.contains('alert-success')) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        }
    });
    
    console.log('Simple dashboard loaded successfully');
});
</script>


</x-student-layout>