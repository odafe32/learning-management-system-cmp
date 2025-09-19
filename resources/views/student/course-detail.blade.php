<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <!-- Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">{{ $course->title }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('student.courses.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    My Courses
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">{{ $course->code }}</li>
        </ul>
    </div>

    <!-- Course Header -->
    <div class="card mb-24">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <!-- Fixed Course Image -->
                        <div class="flex-shrink-0">
                            <img 
                                src="{{ $course->image_url }}" 
                                alt="{{ $course->title }}" 
                                class="course-image rounded-8 object-fit-cover"
                                style="width: 100px; height: 100px; min-width: 100px;"
                                loading="lazy"
                                onerror="this.src='{{ asset('assets/images/thumbs/course-default.png') }}'"
                            >
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="badge bg-primary text-white px-12 py-6 rounded-4 fw-medium">
                                    {{ $course->code }}
                                </span>
                                {!! $course->status_badge !!}
                                <span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4">
                                    {{ $course->level_display }} - {{ $course->semester_display }}
                                </span>
                            </div>
                            <h4 class="mb-2 text-break">{{ $course->title }}</h4>
                            <p class="text-secondary-light mb-0 line-clamp-2">{{ $course->description }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="text-lg-end mt-3 mt-lg-0">
                        <div class="d-flex align-items-center gap-2 mb-3 justify-content-lg-end">
                            <!-- Fixed Instructor Image -->
                            <div class="flex-shrink-0">
                                <img 
                                    src="{{ $course->instructor->profile_image_url }}" 
                                    alt="{{ $course->instructor->name }}" 
                                    class="instructor-avatar rounded-circle object-fit-cover"
                                    style="width: 48px; height: 48px; min-width: 48px;"
                                    loading="lazy"
                                    onerror="this.src='{{ asset('assets/images/avatar/default-avatar.png') }}'"
                                >
                            </div>
                            <div class="text-start text-lg-end">
                                <h6 class="text-md mb-0 text-break">{{ $course->instructor->name }}</h6>
                                <span class="text-sm text-secondary-light">
                                    {{ $course->instructor->department ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                            <span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">
                                {{ $course->credit_units }} Credit{{ $course->credit_units > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Statistics -->
    <div class="row gy-4 mb-24">
        <div class="col-xxl-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <span class="w-48-px h-48-px bg-primary-50 text-primary rounded-circle d-flex justify-content-center align-items-center text-xl">
                                <iconify-icon icon="solar:document-text-outline"></iconify-icon>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <span class="fw-medium text-secondary-light text-sm d-block">Assignments</span>
                            <h6 class="fw-semibold my-1 mb-0">{{ $assignments->total() ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <span class="w-48-px h-48-px bg-success-50 text-success rounded-circle d-flex justify-content-center align-items-center text-xl">
                                <iconify-icon icon="solar:folder-outline"></iconify-icon>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <span class="fw-medium text-secondary-light text-sm d-block">Materials</span>
                            <h6 class="fw-semibold my-1 mb-0">{{ $materials->total() ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <span class="w-48-px h-48-px bg-info-50 text-info rounded-circle d-flex justify-content-center align-items-center text-xl">
                                <iconify-icon icon="solar:file-check-outline"></iconify-icon>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <span class="fw-medium text-secondary-light text-sm d-block">My Submissions</span>
                            <h6 class="fw-semibold my-1 mb-0">{{ $submissions->count() ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <span class="w-48-px h-48-px bg-warning-50 text-warning rounded-circle d-flex justify-content-center align-items-center text-xl">
                                <iconify-icon icon="solar:star-outline"></iconify-icon>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <span class="fw-medium text-secondary-light text-sm d-block">Credit Units</span>
                            <h6 class="fw-semibold my-1 mb-0">{{ $course->credit_units ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="card mb-24">
        <div class="card-body">
            <ul class="nav nav-pills nav-pills-warning flex-wrap" id="courseTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
                        <iconify-icon icon="solar:home-outline" class="icon"></iconify-icon>
                        <span class="d-none d-sm-inline">Overview</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="assignments-tab" data-bs-toggle="pill" data-bs-target="#assignments" type="button" role="tab" aria-controls="assignments" aria-selected="false">
                        <iconify-icon icon="solar:document-text-outline" class="icon"></iconify-icon>
                        <span class="d-none d-sm-inline">Assignments</span>
                        <span class="badge bg-primary text-white ms-1">{{ $assignments->total() ?? 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="materials-tab" data-bs-toggle="pill" data-bs-target="#materials" type="button" role="tab" aria-controls="materials" aria-selected="false">
                        <iconify-icon icon="solar:folder-outline" class="icon"></iconify-icon>
                        <span class="d-none d-sm-inline">Materials</span>
                        <span class="badge bg-success text-white ms-1">{{ $materials->total() ?? 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="submissions-tab" data-bs-toggle="pill" data-bs-target="#submissions" type="button" role="tab" aria-controls="submissions" aria-selected="false">
                        <iconify-icon icon="solar:file-check-outline" class="icon"></iconify-icon>
                        <span class="d-none d-sm-inline">Submissions</span>
                        <span class="badge bg-info text-white ms-1">{{ $submissions->count() ?? 0 }}</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="courseTabContent">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
            <div class="row">
                <!-- Recent Activities -->
                <div class="col-lg-8">
                    <div class="card mb-24">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Recent Activities</h5>
                        </div>
                        <div class="card-body">
                            @if(($assignments && $assignments->count() > 0) || ($materials && $materials->count() > 0))
                                <div class="activity-timeline">
                                    @php
                                        $recentItems = collect();
                                        
                                        // Add recent assignments
                                        if($assignments) {
                                            foreach($assignments->take(3) as $assignment) {
                                                $recentItems->push([
                                                    'type' => 'assignment',
                                                    'item' => $assignment,
                                                    'date' => $assignment->created_at
                                                ]);
                                            }
                                        }
                                        
                                        // Add recent materials
                                        if($materials) {
                                            foreach($materials->take(3) as $material) {
                                                $recentItems->push([
                                                    'type' => 'material',
                                                    'item' => $material,
                                                    'date' => $material->uploaded_at ?? $material->created_at
                                                ]);
                                            }
                                        }
                                        
                                        // Sort by date (most recent first)
                                        $recentItems = $recentItems->sortByDesc('date')->take(5);
                                    @endphp

                                    @foreach($recentItems as $recentItem)
                                        @if($recentItem['type'] === 'assignment')
                                            @php $assignment = $recentItem['item']; @endphp
                                            <div class="d-flex align-items-start gap-3 mb-4 pb-3 border-bottom">
                                                <div class="flex-shrink-0">
                                                    <span class="w-40-px h-40-px bg-primary-50 text-primary rounded-circle d-flex justify-content-center align-items-center">
                                                        <iconify-icon icon="solar:document-text-outline"></iconify-icon>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 text-break">{{ $assignment->title }}</h6>
                                                    <p class="text-secondary-light text-sm mb-2 line-clamp-2">{{ Str::limit($assignment->description, 100) }}</p>
                                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                                        <span class="text-xs text-secondary-light">
                                                            <iconify-icon icon="solar:calendar-outline" class="icon"></iconify-icon>
                                                            Due: {{ $assignment->deadline ? $assignment->deadline->format('M d, Y h:i A') : 'No deadline' }}
                                                        </span>
                                                        @php
                                                            $userSubmission = $assignment->submissions->where('user_id', auth()->id())->first();
                                                        @endphp
                                                        @if(!$userSubmission)
                                                            <span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">Not Submitted</span>
                                                        @else
                                                            <span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Submitted</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @php $material = $recentItem['item']; @endphp
                                            <div class="d-flex align-items-start gap-3 mb-4 pb-3 border-bottom">
                                                <div class="flex-shrink-0">
                                                    <span class="w-40-px h-40-px bg-success-50 text-success rounded-circle d-flex justify-content-center align-items-center">
                                                        <iconify-icon icon="solar:folder-outline"></iconify-icon>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 text-break">{{ $material->title }}</h6>
                                                    <p class="text-secondary-light text-sm mb-2 line-clamp-2">{{ Str::limit($material->description ?? 'No description available', 100) }}</p>
                                                    <span class="text-xs text-secondary-light">
                                                        <iconify-icon icon="solar:calendar-outline" class="icon"></iconify-icon>
                                                        Uploaded: {{ $material->uploaded_at ? $material->uploaded_at->format('M d, Y') : ($material->created_at ? $material->created_at->format('M d, Y') : 'N/A') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <iconify-icon icon="solar:inbox-outline" class="icon text-6xl text-secondary-light mb-3"></iconify-icon>
                                    <h6 class="mb-2">No Recent Activities</h6>
                                    <p class="text-secondary-light">Check back later for course updates.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Upcoming Deadlines -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card mb-24">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('student.assignments.index') }}?course={{ $course->id }}" class="btn btn-primary">
                                    <iconify-icon icon="solar:document-text-outline" class="icon"></iconify-icon>
                                    View All Assignments
                                </a>
                                <a href="{{ route('student.materials.index') }}?course={{ $course->id }}" class="btn btn-success">
                                    <iconify-icon icon="solar:folder-outline" class="icon"></iconify-icon>
                                    Browse Materials
                                </a>
                                <a href="{{ route('student.submissions.index') }}?course={{ $course->id }}" class="btn btn-info">
                                    <iconify-icon icon="solar:file-check-outline" class="icon"></iconify-icon>
                                    My Submissions
                                </a>
                                <a href="{{ route('student.grades.index') }}?course={{ $course->id }}" class="btn btn-warning">
                                    <iconify-icon icon="solar:medal-star-outline" class="icon"></iconify-icon>
                                    View Grades
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Deadlines -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Upcoming Deadlines</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $upcomingAssignments = $assignments ? $assignments->filter(function($assignment) {
                                    $userSubmission = $assignment->submissions->where('user_id', auth()->id())->first();
                                    return $assignment->deadline && $assignment->deadline > now() && !$userSubmission;
                                })->sortBy('deadline')->take(5) : collect();
                            @endphp

                            @if($upcomingAssignments->count() > 0)
                                @foreach($upcomingAssignments as $assignment)
                                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                        <div class="flex-grow-1 me-2">
                                            <h6 class="text-sm mb-1 text-break">{{ Str::limit($assignment->title, 30) }}</h6>
                                            <span class="text-xs text-secondary-light">
                                                {{ $assignment->deadline->format('M d, Y h:i A') }}
                                            </span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @php
                                                $daysLeft = now()->diffInDays($assignment->deadline, false);
                                                $badgeClass = $daysLeft <= 1 ? 'bg-danger-50 text-danger-600' : ($daysLeft <= 3 ? 'bg-warning-50 text-warning-600' : 'bg-info-50 text-info-600');
                                            @endphp
                                            <span class="badge {{ $badgeClass }} px-8 py-4 rounded-4 text-xs">
                                                {{ $assignment->deadline->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <iconify-icon icon="solar:check-circle-outline" class="icon text-4xl text-success mb-2"></iconify-icon>
                                    <p class="text-sm text-secondary-light mb-0">No upcoming deadlines</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments Tab -->
        <div class="tab-pane fade" id="assignments" role="tabpanel" aria-labelledby="assignments-tab">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h5 class="card-title mb-0">Course Assignments</h5>
                    <a href="{{ route('student.assignments.index') }}?course={{ $course->id }}" class="btn btn-primary btn-sm">
                        <iconify-icon icon="solar:external-link-outline" class="icon"></iconify-icon>
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($assignments && $assignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Assignment</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Grade</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignments as $assignment)
                                        @php
                                            $userSubmission = $assignment->submissions->where('user_id', auth()->id())->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                <h6 class="mb-1 text-break">{{ $assignment->title }}</h6>
                                                <p class="text-sm text-black mb-0 line-clamp-2">{{ Str::limit($assignment->description, 80) }}</p>
                                            </td>
                                            <td>
                                                @if($assignment->deadline)
                                                    <span class="text-sm">{{ $assignment->deadline->format('M d, Y') }}</span>
                                                    <br>
                                                    <span class="text-xs text-black">{{ $assignment->deadline->format('h:i A') }}</span>
                                                @else
                                                    <span class="text-sm text-black">No deadline</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($userSubmission)
                                                    @if($userSubmission->status === 'graded')
                                                        <span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Graded</span>
                                                    @else
                                                        <span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4">Submitted</span>
                                                    @endif
                                                @elseif($assignment->deadline && $assignment->deadline < now())
                                                    <span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4">Overdue</span>
                                                @else
                                                    <span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($userSubmission && $userSubmission->status === 'graded' && $userSubmission->grade)
                                                    <span class="fw-medium">{{ $userSubmission->grade }}%</span>
                                                @else
                                                    <span class="text-black">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('student.assignments.show', $assignment->id) }}">
                                                                <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                                                View Details
                                                            </a>
                                                        </li>
                                                        @if(!$userSubmission && (!$assignment->deadline || $assignment->deadline > now()))
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('student.assignments.submit', $assignment->id) }}">
                                                                    <iconify-icon icon="solar:upload-outline" class="icon"></iconify-icon>
                                                                    Submit Assignment
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if($userSubmission)
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('student.submissions.show', $userSubmission->id) }}">
                                                                    <iconify-icon icon="solar:file-check-outline" class="icon"></iconify-icon>
                                                                    View Submission
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($assignments->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $assignments->appends(['materials_page' => request('materials_page')])->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <iconify-icon icon="solar:document-text-outline" class="icon text-6xl text-secondary-light mb-3"></iconify-icon>
                            <h6 class="mb-2">No Assignments Yet</h6>
                            <p class="text-secondary-light">Assignments will appear here when your instructor creates them.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Materials Tab -->
        <div class="tab-pane fade" id="materials" role="tabpanel" aria-labelledby="materials-tab">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h5 class="card-title mb-0">Course Materials</h5>
                    <a href="{{ route('student.materials.index') }}?course={{ $course->id }}" class="btn btn-primary btn-sm">
                        <iconify-icon icon="solar:external-link-outline" class="icon"></iconify-icon>
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($materials && $materials->count() > 0)
                        <div class="row">
                            @foreach($materials as $material)
                                <div class="col-lg-6 col-xl-4 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="flex-shrink-0">
                                                    <span class="w-48-px h-48-px bg-success-50 text-success rounded-circle d-flex justify-content-center align-items-center">
                                                        <iconify-icon icon="solar:folder-outline"></iconify-icon>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 text-break">{{ $material->title }}</h6>
                                                    <p class="text-sm text-secondary-light mb-2 line-clamp-2">{{ Str::limit($material->description ?? 'No description available', 80) }}</p>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span class="text-xs text-secondary-light">
                                                            {{ $material->uploaded_at ? $material->uploaded_at->format('M d, Y') : ($material->created_at ? $material->created_at->format('M d, Y') : 'N/A') }}
                                                        </span>
                                                        <div class="d-flex gap-1">
                                                            <a href="{{ route('student.materials.show', $material->id) }}" class="btn btn-sm btn-primary" title="View"> View
                                                                <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                                            </a>
                                                            <a href="{{ route('student.materials.download', $material->id) }}" class="btn btn-sm btn-success" title="Download"> Download
                                                                <iconify-icon icon="solar:download-outline" class="icon"></iconify-icon>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($materials->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $materials->appends(['assignments_page' => request('assignments_page')])->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <iconify-icon icon="solar:folder-outline" class="icon text-6xl text-secondary-light mb-3"></iconify-icon>
                            <h6 class="mb-2">No Materials Yet</h6>
                            <p class="text-secondary-light">Course materials will appear here when your instructor uploads them.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Submissions Tab -->
        <div class="tab-pane fade" id="submissions" role="tabpanel" aria-labelledby="submissions-tab">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h5 class="card-title mb-0">My Submissions</h5>
                    <a href="{{ route('student.submissions.index') }}?course={{ $course->id }}" class="btn btn-primary btn-sm">
                        <iconify-icon icon="solar:external-link-outline" class="icon"></iconify-icon>
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($submissions && $submissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Assignment</th>
                                        <th>Submitted At</th>
                                        <th>Status</th>
                                        <th>Grade</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $submission)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-break">{{ $submission->assignment->title }}</h6>
                                            </td>
                                            <td>
                                                @if($submission->submitted_at)
                                                    <span class="text-sm">{{ $submission->submitted_at->format('M d, Y') }}</span>
                                                    <br>
                                                    <span class="text-xs text-secondary-light">{{ $submission->submitted_at->format('h:i A') }}</span>
                                                @else
                                                    <span class="text-sm text-secondary-light">Not submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($submission->status === 'graded')
                                                    <span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">Graded</span>
                                                @elseif($submission->status === 'pending')
                                                    <span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">Pending Review</span>
                                                @else
                                                    <span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4">{{ ucfirst($submission->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($submission->status === 'graded' && $submission->grade)
                                                    <span class="fw-medium">{{ $submission->grade }}%</span>
                                                @else
                                                    <span class="text-secondary-light">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('student.submissions.show', $submission->id) }}" class="btn btn-sm btn-primary">
                                                    <iconify-icon icon="solar:eye-outline" class="icon"></iconify-icon>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <iconify-icon icon="solar:file-check-outline" class="icon text-6xl text-secondary-light mb-3"></iconify-icon>
                            <h6 class="mb-2">No Submissions Yet</h6>
                            <p class="text-secondary-light">Your assignment submissions will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .course-image {
        transition: transform 0.2s ease;
    }
    
    .course-image:hover {
        transform: scale(1.05);
    }
    
    .instructor-avatar {
        transition: transform 0.2s ease;
    }
    
    .instructor-avatar:hover {
        transform: scale(1.1);
    }
    
    .activity-timeline .border-bottom:last-child {
        border-bottom: none !important;
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }
    
    @media (max-width: 576px) {
        .nav-pills .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .course-image {
            width: 80px !important;
            height: 80px !important;
            min-width: 80px !important;
        }
        
        .instructor-avatar {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Handle tab switching with URL hash
        const hash = window.location.hash;
        if (hash) {
            const tabTrigger = document.querySelector(`[data-bs-target="${hash}"]`);
            if (tabTrigger) {
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        }

        // Update URL hash when tab changes
        const tabTriggers = document.querySelectorAll('[data-bs-toggle="pill"]');
        tabTriggers.forEach(trigger => {
            trigger.addEventListener('shown.bs.tab', function(e) {
                const target = e.target.getAttribute('data-bs-target');
                history.replaceState(null, null, target);
            });
        });

        // Add loading states for images
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(img => {
            img.addEventListener('load', function() {
                this.style.opacity = '1';
            });
            
            img.addEventListener('error', function() {
                this.style.opacity = '0.7';
            });
        });
    });
</script>
</x-student-layout>