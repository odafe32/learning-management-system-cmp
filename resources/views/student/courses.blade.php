<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
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
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="mb-0 w-48-px h-48-px bg-primary-50 text-primary-600 rounded-circle d-flex justify-content-center align-items-center text-xl">
                            <iconify-icon icon="solar:book-outline"></iconify-icon>
                        </span>
                        <div>
                            <span class="mb-2 fw-medium text-secondary-light text-sm">Total Enrolled</span>
                            <h6 class="fw-semibold my-1">{{ $totalEnrolled ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="mb-0 w-48-px h-48-px bg-success text-success-600 rounded-circle d-flex justify-content-center align-items-center text-xl">
                            <iconify-icon icon="solar:document-text-outline"></iconify-icon>
                        </span>
                        <div>
                            <span class="mb-2 fw-medium text-secondary-light text-sm">Active Assignments</span>
                            <h6 class="fw-semibold my-1">{{ $enrolledCourses->sum('assignments_count') ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="mb-0 w-48-px h-48-px bg-info text-info-600 rounded-circle d-flex justify-content-center align-items-center text-xl">
                            <iconify-icon icon="solar:folder-outline"></iconify-icon>
                        </span>
                        <div>
                            <span class="mb-2 fw-medium text-secondary-light text-sm">Course Materials</span>
                            <h6 class="fw-semibold my-1">{{ $enrolledCourses->sum('materials_count') ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="mb-0 w-48-px h-48-px bg-warning text-warning rounded-circle d-flex justify-content-center align-items-center text-xl">
                            <iconify-icon icon="solar:user-outline"></iconify-icon>
                        </span>
                        <div>
                            <span class="mb-2 fw-medium text-secondary-light text-sm">Level</span>
                            <h6 class="fw-semibold my-1">{{ $user->level ?? 'N/A' }} Level</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex flex-wrap align-items-center gap-3 mb-24">
        <a href="{{ route('student.courses.enroll-courses') }}" class="btn btn-primary">
            <iconify-icon icon="solar:user-plus-outline" class="icon"></iconify-icon>
            Enroll in More Courses
        </a>
        <a href="{{ route('student.assignments.index') }}" class="btn btn-primary">
            <iconify-icon icon="solar:document-text-outline" class="icon"></iconify-icon>
            View All Assignments
        </a>
        <a href="{{ route('student.materials.index') }}" class="btn btn-info">
            <iconify-icon icon="solar:folder-outline" class="icon"></iconify-icon>
            Browse Materials
        </a>
    </div>

    <!-- Enrolled Courses -->
    <div class="row" id="coursesGrid">
        @forelse($enrolledCourses ?? [] as $course)
            <div class="col-xxl-4 col-md-6 mb-24">
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
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('student.courses.show', $course->slug) }}">
                                            <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                            View Course
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('student.materials.index') }}?course={{ $course->id }}">
                                            <iconify-icon icon="solar:folder-outline" class="icon"></iconify-icon>
                                            Course Materials
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('student.assignments.index') }}?course={{ $course->id }}">
                                            <iconify-icon icon="solar:document-text-outline" class="icon"></iconify-icon>
                                            Assignments
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

                        <!-- Instructor Info -->
                        <div class="d-flex align-items-center gap-2 mb-16">
                            <img src="{{ $course->instructor->profile_image_url }}" alt="{{ $course->instructor->name }}" class="w-32-px h-32-px rounded-circle object-fit-cover">
                            <div>
                                <h6 class="text-md mb-0">{{ $course->instructor->name }}</h6>
                                <span class="text-sm text-secondary-light">{{ $course->instructor->department }}</span>
                            </div>
                        </div>

                        <!-- Course Stats -->
                        <div class="row g-2 mb-16">
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:document-text-outline" class="icon text-lg text-secondary-light"></iconify-icon>
                                    <span class="text-secondary-light text-sm">{{ $course->assignments_count ?? 0 }} Assignments</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:folder-outline" class="icon text-lg text-secondary-light"></iconify-icon>
                                    <span class="text-secondary-light text-sm">{{ $course->materials_count ?? 0 }} Materials</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:star-outline" class="icon text-lg text-secondary-light"></iconify-icon>
                                    <span class="text-secondary-light text-sm">{{ $course->credit_units }} Credits</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:calendar-outline" class="icon text-lg text-secondary-light"></iconify-icon>
                                    <span class="text-secondary-light text-sm">{{ $course->semester_display }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activities -->
                        @if(isset($course->recentActivities) && $course->recentActivities->count() > 0)
                            <div class="mb-16">
                                <h6 class="text-sm fw-semibold mb-2">Recent Activities</h6>
                                <div class="recent-activities">
                                    @foreach($course->recentActivities->take(3) as $activity)
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <iconify-icon icon="{{ $activity['type'] == 'material' ? 'solar:folder-outline' : 'solar:document-text-outline' }}" class="icon text-sm text-secondary-light"></iconify-icon>
                                            <span class="text-xs text-secondary-light">{{ Str::limit($activity['title'], 30) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Upcoming Assignments -->
                        @if(isset($course->upcomingAssignments) && $course->upcomingAssignments->count() > 0)
                            <div class="mb-16">
                                <h6 class="text-sm fw-semibold mb-2 text-warning-600">Upcoming Deadlines</h6>
                                @foreach($course->upcomingAssignments->take(2) as $assignment)
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="text-xs">{{ Str::limit($assignment->title, 25) }}</span>
                                        <span class="text-xs text-warning-600">{{ $assignment->deadline->format('M d') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('student.courses.show', $course->slug) }}" class="btn btn-primary flex-fill">
                                <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                View Course
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <iconify-icon icon="solar:menu-dots-outline" class="icon"></iconify-icon>
                                </button>
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
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <iconify-icon icon="solar:book-outline" class="icon text-6xl text-secondary-light mb-3"></iconify-icon>
                        <h5 class="mb-2">No Enrolled Courses</h5>
                        <p class="text-secondary-light mb-4">
                            You haven't enrolled in any courses yet. Browse available courses and start your learning journey.
                        </p>
                        <a href="{{ route('student.courses.enroll-courses') }}" class="btn btn-primary">
                            <iconify-icon icon="solar:user-plus-outline" class="icon"></iconify-icon>
                            Browse Available Courses
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($enrolledCourses) && $enrolledCourses->hasPages())
        <div class="d-flex justify-content-center mt-24">
            {{ $enrolledCourses->links() }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add any additional JavaScript functionality here
        console.log('Student courses page loaded');
    });
</script>
</x-student-layout>