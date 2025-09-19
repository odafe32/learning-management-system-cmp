<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">My Assignments</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <i class="ph ph-house text-lg"></i>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Assignments</li>
        </ul>
    </div>

    <!-- Assignment Statistics -->
    @if(isset($categorizedAssignments))
    <div class="row gy-4 mb-24">
        <div class="col-xxl-3 col-sm-6">
            <div class="card h-100 radius-12 text-center">
                <div class="card-body p-24">
                    <div class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-primary-50 text-primary-600 mb-16 radius-12">
                        <i class="ph ph-clock-countdown text-2xl"></i>
                    </div>
                    <h6 class="mb-2 fw-semibold text-lg">{{ $categorizedAssignments['pending']->count() }}</h6>
                    <span class="text-sm text-secondary-light fw-medium">Pending</span>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card h-100 radius-12 text-center">
                <div class="card-body p-24">
                    <div class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-success-50 text-success-600 mb-16 radius-12">
                        <i class="ph ph-check-circle text-2xl"></i>
                    </div>
                    <h6 class="mb-2 fw-semibold text-lg">{{ $categorizedAssignments['submitted']->count() }}</h6>
                    <span class="text-sm text-secondary-light fw-medium">Submitted</span>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card h-100 radius-12 text-center">
                <div class="card-body p-24">
                    <div class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-danger-50 text-danger-600 mb-16 radius-12">
                        <i class="ph ph-warning-circle text-2xl"></i>
                    </div>
                    <h6 class="mb-2 fw-semibold text-lg">{{ $categorizedAssignments['overdue']->count() }}</h6>
                    <span class="text-sm text-secondary-light fw-medium">Overdue</span>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card h-100 radius-12 text-center">
                <div class="card-body p-24">
                    <div class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-info-50 text-info-600 mb-16 radius-12">
                        <i class="ph ph-star text-2xl"></i>
                    </div>
                    <h6 class="mb-2 fw-semibold text-lg">{{ $categorizedAssignments['graded']->count() }}</h6>
                    <span class="text-sm text-secondary-light fw-medium">Graded</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter and Search Section -->
    <div class="card basic-data-table radius-12 overflow-hidden mb-24">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h6 class="text-lg fw-semibold mb-0">Filter Assignments</h6>
                <div class="d-flex align-items-center gap-3">
                    <!-- Status Filter -->
                    <select class="form-select form-select-sm w-auto" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending" {{ (isset($currentFilters['status']) && $currentFilters['status'] == 'pending') ? 'selected' : '' }}>Pending</option>
                        <option value="submitted" {{ (isset($currentFilters['status']) && $currentFilters['status'] == 'submitted') ? 'selected' : '' }}>Submitted</option>
                        <option value="graded" {{ (isset($currentFilters['status']) && $currentFilters['status'] == 'graded') ? 'selected' : '' }}>Graded</option>
                        <option value="overdue" {{ (isset($currentFilters['status']) && $currentFilters['status'] == 'overdue') ? 'selected' : '' }}>Overdue</option>
                    </select>
                    
                    <!-- Course Filter -->
                    <select class="form-select form-select-sm w-auto" id="courseFilter">
                        <option value="">All Courses</option>
                        @if(isset($courses) && $courses->count() > 0)
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ (isset($currentFilters['course']) && $currentFilters['course'] == $course->id) ? 'selected' : '' }}>
                                    {{ $course->code }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    
                    <!-- Search -->
                    <div class="position-relative">
                        <input type="text" class="form-control form-control-sm ps-40" placeholder="Search assignments..." id="searchInput" value="{{ $currentFilters['search'] ?? '' }}">
                        <i class="ph ph-magnifying-glass position-absolute start-0 top-50 translate-middle-y ms-12 text-secondary-light"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments Grid -->
    <div class="row gy-4" id="assignmentsGrid">
        @if(isset($assignments) && $assignments->count() > 0)
            @foreach($assignments as $assignment)
                @php
                    // Get submission for this assignment
                    $submission = $assignment->submissions()->where('student_id', auth()->id())->first();
                    $isSubmitted = $submission !== null;
                    $isOverdue = $assignment->deadline && $assignment->deadline->isPast() && !$isSubmitted;
                    $isUpcoming = $assignment->deadline && $assignment->deadline->isFuture() && $assignment->deadline->diffInDays() <= 3;
                @endphp
                
                <div class="col-xxl-4 col-lg-6 assignment-card" 
                     data-course="{{ $assignment->course->id }}" 
                     data-status="{{ $isSubmitted ? ($submission->status ?? 'submitted') : ($isOverdue ? 'overdue' : 'pending') }}"
                     data-title="{{ strtolower($assignment->title) }}">
                    <div class="card h-100 radius-12 overflow-hidden">
                        <!-- Card Header with Status -->
                        <div class="card-header border-bottom-0 pb-0">
                            <div class="d-flex align-items-center justify-content-between mb-12">
                                <span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded-4 text-xs">
                                    {{ $assignment->course->code }}
                                </span>
                                @if($isSubmitted)
                                    @if($submission->status === 'graded')
                                        <span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4 text-xs">
                                            <i class="ph ph-star me-1"></i>
                                            Graded
                                        </span>
                                    @elseif($submission->status === 'pending')
                                        <span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4 text-xs">
                                            <i class="ph ph-clock me-1"></i>
                                            Under Review
                                        </span>
                                    @else
                                        <span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4 text-xs">
                                            <i class="ph ph-check-circle me-1"></i>
                                            Submitted
                                        </span>
                                    @endif
                                @elseif($isOverdue)
                                    <span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4 text-xs">
                                        <i class="ph ph-warning me-1"></i>
                                        Overdue
                                    </span>
                                @elseif($isUpcoming)
                                    <span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4 text-xs">
                                        <i class="ph ph-timer me-1"></i>
                                        Due Soon
                                    </span>
                                @else
                                    <span class="badge bg-gray-50 text-gray-600 px-8 py-4 rounded-4 text-xs">
                                        <i class="ph ph-circle me-1"></i>
                                        Pending
                                    </span>
                                @endif
                            </div>
                            
                            <h6 class="text-lg fw-semibold mb-8 line-clamp-2">
                                <a href="{{ route('student.assignments.show', $assignment) }}" class="text-primary-light hover-text-primary">
                                    {{ $assignment->title }}
                                </a>
                            </h6>
                            
                            <p class="text-secondary-light text-sm mb-16 line-clamp-3">
                                {{ Str::limit($assignment->description, 120) }}
                            </p>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body pt-0">
                            <!-- Assignment Info -->
                            <div class="mb-16">
                                <div class="d-flex align-items-center gap-2 mb-8">
                                    <i class="ph ph-book text-secondary-light"></i>
                                    <span class="text-sm text-secondary-light">{{ $assignment->course->title }}</span>
                                </div>
                                
                                <div class="d-flex align-items-center gap-2 mb-8">
                                    <i class="ph ph-user text-secondary-light"></i>
                                    <span class="text-sm text-secondary-light">{{ $assignment->course->instructor->name }}</span>
                                </div>
                                
                                <!-- Deadline with null check -->
                                @if($assignment->deadline)
                                    <div class="d-flex align-items-center gap-2 mb-8">
                                        <i class="ph ph-calendar text-secondary-light"></i>
                                        <span class="text-sm {{ $isOverdue ? 'text-danger' : ($isUpcoming ? 'text-warning' : 'text-secondary-light') }}">
                                            Due: {{ $assignment->deadline->format('M d, Y \a\t g:i A') }}
                                        </span>
                                    </div>
                                    
                                    <!-- Time remaining -->
                                    @if(!$isSubmitted && $assignment->deadline->isFuture())
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ph ph-timer text-secondary-light"></i>
                                            <span class="text-sm text-secondary-light">
                                                {{ $assignment->deadline->diffForHumans() }}
                                            </span>
                                        </div>
                                    @endif
                                @else
                                    <div class="d-flex align-items-center gap-2 mb-8">
                                        <i class="ph ph-calendar text-secondary-light"></i>
                                        <span class="text-sm text-secondary-light">No deadline set</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Submission Status -->
                            @if($isSubmitted)
                                <div class="mb-16 p-12 bg-success-50 rounded-8">
                                    <div class="d-flex align-items-center gap-2 mb-4">
                                        <i class="ph ph-check-circle text-success"></i>
                                        <span class="text-sm fw-medium text-success">
                                            Submitted {{ $submission->submitted_at ? $submission->submitted_at->diffForHumans() : 'recently' }}
                                        </span>
                                    </div>
                                    @if($submission->status === 'graded' && $submission->grade !== null)
                                        <div class="text-sm text-success">
                                            <strong>Grade:</strong> {{ $submission->grade }}/{{ $assignment->max_grade ?? 100 }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Progress Bar for Deadline -->
                            @if($assignment->deadline && !$isSubmitted)
                                @php
                                    $totalTime = $assignment->created_at->diffInHours($assignment->deadline);
                                    $remainingTime = now()->diffInHours($assignment->deadline, false);
                                    $progress = $totalTime > 0 ? max(0, min(100, (($totalTime - $remainingTime) / $totalTime) * 100)) : 100;
                                @endphp
                                <div class="mb-16">
                                    <div class="d-flex justify-content-between mb-4">
                                        <span class="text-xs text-secondary-light">Time Progress</span>
                                        <span class="text-xs text-secondary-light">{{ number_format($progress, 0) }}%</span>
                                    </div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar {{ $progress > 80 ? 'bg-danger' : ($progress > 60 ? 'bg-warning' : 'bg-primary') }}" 
                                             style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('student.assignments.show', $assignment) }}" 
                                   class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="ph ph-eye me-2"></i>
                                    View Details
                                </a>
                                
                                @if($isSubmitted)
                                    <a href="{{ route('student.submissions.show', $submission) }}" 
                                       class="btn btn-success btn-sm">
                                        <i class="ph ph-file-text me-2"></i>
                                        View Submission
                                    </a>
                                @else
                                    @if($assignment->deadline && $assignment->deadline->isPast())
                                        <button class="btn btn-danger btn-sm" disabled>
                                            <i class="ph ph-lock me-2"></i>
                                            Overdue
                                        </button>
                                    @else
                                        <a href="{{ route('student.assignments.submit', $assignment) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="ph ph-paper-plane-tilt me-2"></i>
                                            Submit
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="col-12">
                <div class="card radius-12">
                    <div class="card-body text-center py-5">
                        <div class="mb-24">
                            <i class="ph ph-clipboard-text text-6xl text-secondary-light"></i>
                        </div>
                        <h5 class="fw-semibold mb-12">No Assignments Found</h5>
                        <p class="text-secondary-light mb-24">
                            You don't have any assignments yet. Check back later or contact your instructor.
                        </p>
                        <a href="{{ route('student.courses.index') }}" class="btn btn-primary">
                            <i class="ph ph-book me-2"></i>
                            View My Courses
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($assignments) && $assignments->hasPages())
        <div class="d-flex justify-content-center mt-24">
            {{ $assignments->links() }}
        </div>
    @endif
</div>

<!-- Custom Styles -->
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.assignment-card {
    transition: transform 0.2s ease;
}

.assignment-card:hover {
    transform: translateY(-2px);
}

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.3s ease;
}

.badge {
    font-weight: 500;
}

.card {
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
</style>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize filters
    initializeFilters();
    
    // Initialize search
    initializeSearch();
    
    // Update assignment counts
    updateAssignmentCounts();
});

function initializeFilters() {
    const statusFilter = document.getElementById('statusFilter');
    const courseFilter = document.getElementById('courseFilter');
    
    if (statusFilter) statusFilter.addEventListener('change', filterAssignments);
    if (courseFilter) courseFilter.addEventListener('change', filterAssignments);
}

function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(filterAssignments, 300);
        });
    }
}

function filterAssignments() {
    const statusFilter = document.getElementById('statusFilter');
    const courseFilter = document.getElementById('courseFilter');
    const searchInput = document.getElementById('searchInput');
    
    const statusValue = statusFilter ? statusFilter.value : '';
    const courseValue = courseFilter ? courseFilter.value : '';
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    
    const assignmentCards = document.querySelectorAll('.assignment-card');
    let visibleCount = 0;
    
    assignmentCards.forEach(card => {
        const cardStatus = card.dataset.status;
        const cardCourse = card.dataset.course;
        const cardTitle = card.dataset.title;
        
        let showCard = true;
        
        // Status filter
        if (statusValue && cardStatus !== statusValue) {
            showCard = false;
        }
        
        // Course filter
        if (courseValue && cardCourse !== courseValue) {
            showCard = false;
        }
        
        // Search filter
        if (searchTerm && !cardTitle.includes(searchTerm)) {
            showCard = false;
        }
        
        if (showCard) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    toggleEmptyState(visibleCount === 0);
}

function toggleEmptyState(show) {
    let emptyState = document.getElementById('emptyState');
    
    if (show && !emptyState) {
        // Create empty state
        emptyState = document.createElement('div');
        emptyState.id = 'emptyState';
        emptyState.className = 'col-12';
        emptyState.innerHTML = `
            <div class="card radius-12">
                <div class="card-body text-center py-5">
                    <div class="mb-24">
                        <i class="ph ph-magnifying-glass text-6xl text-secondary-light"></i>
                    </div>
                    <h5 class="fw-semibold mb-12">No Assignments Found</h5>
                    <p class="text-secondary-light mb-24">
                        No assignments match your current filters. Try adjusting your search criteria.
                    </p>
                    <button class="btn btn-outline-primary" onclick="clearFilters()">
                        <i class="ph ph-x me-2"></i>
                        Clear Filters
                    </button>
                </div>
            </div>
        `;
        document.getElementById('assignmentsGrid').appendChild(emptyState);
    } else if (!show && emptyState) {
        emptyState.remove();
    }
}

function clearFilters() {
    const statusFilter = document.getElementById('statusFilter');
    const courseFilter = document.getElementById('courseFilter');
    const searchInput = document.getElementById('searchInput');
    
    if (statusFilter) statusFilter.value = '';
    if (courseFilter) courseFilter.value = '';
    if (searchInput) searchInput.value = '';
    
    filterAssignments();
}

function updateAssignmentCounts() {
    const assignmentCards = document.querySelectorAll('.assignment-card');
    const counts = {
        total: assignmentCards.length,
        pending: 0,
        submitted: 0,
        graded: 0,
        overdue: 0
    };
    
    assignmentCards.forEach(card => {
        const status = card.dataset.status;
        if (counts.hasOwnProperty(status)) {
            counts[status]++;
        }
    });
    
    // Update any count displays if they exist
    console.log('Assignment counts:', counts);
}

// Auto-refresh every 5 minutes to update time-sensitive information
setInterval(function() {
    location.reload();
}, 5 * 60 * 1000);
</script>

</x-student-layout>