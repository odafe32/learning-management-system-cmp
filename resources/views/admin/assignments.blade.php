<x-admin-layout :metaTitle="$metaTitle" :metaDesc="$metaDesc" :metaImage="$metaImage">
    <div class="dashboard-body__content">
        <div class="row gy-4">
            <div class="col-lg-12">
                <!-- Page Header -->
                <div class="card border-0 bg-transparent">
                    <div class="card-body p-0">
                        <div class="flex-between flex-wrap gap-16 mb-24">
                            <div>
                                <h4 class="mb-8 text-xl fw-semibold">Manage Assignments</h4>
                                <p class="text-gray-600 text-15">View, manage, and delete assignments from all instructors.</p>
                            </div>
                            <div class="flex-align gap-8">
                                <a href="{{ route('admin.assignments.export', request()->query()) }}" class="btn btn-success radius-8 px-20 py-11">
                                    <i class="ph ph-download me-8"></i>
                                    Export Assignments
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Form -->
                <div class="card border-0 mb-24">
                    <div class="card-header bg-gray-50 border-bottom border-gray-100 py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0 text-gray-900">
                            <i class="ph ph-funnel me-8"></i>
                            Search & Filter Assignments
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <form method="GET" action="{{ route('admin.assignments.index') }}" id="filterForm">
                            <div class="row gy-16">
                                <!-- Search Input -->
                                <div class="col-md-4">
                                    <label for="search" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Search Assignments
                                    </label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               class="form-control radius-8 ps-40" 
                                               id="search" 
                                               name="search" 
                                               value="{{ $currentFilters['search'] }}" 
                                               placeholder="Search by title, course, instructor...">
                                        <i class="ph ph-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-12 text-gray-400"></i>
                                    </div>
                                </div>

                                <!-- Status Filter -->
                                <div class="col-md-2">
                                    <label for="status" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Status
                                    </label>
                                    <select class="form-control radius-8" id="status" name="status">
                                        <option value="">All Statuses</option>
                                        @foreach($filterOptions['statuses'] as $statusValue => $statusLabel)
                                            <option value="{{ $statusValue }}" {{ $currentFilters['status'] == $statusValue ? 'selected' : '' }}>
                                                {{ $statusLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Course Filter -->
                                <div class="col-md-3">
                                    <label for="course" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Course
                                    </label>
                                    <select class="form-control radius-8" id="course" name="course">
                                        <option value="">All Courses</option>
                                        @foreach($filterOptions['courses'] as $course)
                                            <option value="{{ $course->id }}" {{ $currentFilters['course'] == $course->id ? 'selected' : '' }}>
                                                {{ $course->code }} - {{ $course->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Instructor Filter -->
                                <div class="col-md-3">
                                    <label for="instructor" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Instructor
                                    </label>
                                    <select class="form-control radius-8" id="instructor" name="instructor">
                                        <option value="">All Instructors</option>
                                        @foreach($filterOptions['instructors'] as $instructor)
                                            <option value="{{ $instructor->id }}" {{ $currentFilters['instructor'] == $instructor->id ? 'selected' : '' }}>
                                                {{ $instructor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Department Filter -->
                                <div class="col-md-2">
                                    <label for="department" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Department
                                    </label>
                                    <select class="form-control radius-8" id="department" name="department">
                                        <option value="">All Departments</option>
                                        @foreach($filterOptions['departments'] as $dept)
                                            <option value="{{ $dept }}" {{ $currentFilters['department'] == $dept ? 'selected' : '' }}>
                                                {{ $dept }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Faculty Filter -->
                                <div class="col-md-2">
                                    <label for="faculty" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Faculty
                                    </label>
                                    <select class="form-control radius-8" id="faculty" name="faculty">
                                        <option value="">All Faculties</option>
                                        @foreach($filterOptions['faculties'] as $fac)
                                            <option value="{{ $fac }}" {{ $currentFilters['faculty'] == $fac ? 'selected' : '' }}>
                                                {{ $fac }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Deadline Filter -->
                                <div class="col-md-2">
                                    <label for="deadline" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Deadline
                                    </label>
                                    <select class="form-control radius-8" id="deadline" name="deadline">
                                        <option value="">All Deadlines</option>
                                        @foreach($filterOptions['deadlineFilters'] as $deadlineValue => $deadlineLabel)
                                            <option value="{{ $deadlineValue }}" {{ $currentFilters['deadline'] == $deadlineValue ? 'selected' : '' }}>
                                                {{ $deadlineLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sort By -->
                                <div class="col-md-2">
                                    <label for="sort_by" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Sort By
                                    </label>
                                    <select class="form-control radius-8" id="sort_by" name="sort_by">
                                        @foreach($allowedSortFields as $field)
                                            <option value="{{ $field }}" {{ $currentFilters['sort_by'] == $field ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $field)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sort Order -->
                                <div class="col-md-2">
                                    <label for="sort_order" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Order
                                    </label>
                                    <select class="form-control radius-8" id="sort_order" name="sort_order">
                                        <option value="asc" {{ $currentFilters['sort_order'] == 'asc' ? 'selected' : '' }}>Ascending</option>
                                        <option value="desc" {{ $currentFilters['sort_order'] == 'desc' ? 'selected' : '' }}>Descending</option>
                                    </select>
                                </div>

                                <!-- Per Page -->
                                <div class="col-md-2">
                                    <label for="per_page" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Per Page
                                    </label>
                                    <select class="form-control radius-8" id="per_page" name="per_page">
                                        @foreach($allowedPerPage as $count)
                                            <option value="{{ $count }}" {{ $currentFilters['per_page'] == $count ? 'selected' : '' }}>
                                                {{ $count }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Actions -->
                                <div class="col-12">
                                    <div class="flex-align gap-12">
                                        <button type="submit" class="btn btn-main-600 radius-8 px-20 py-11">
                                            <i class="ph ph-funnel me-8"></i>
                                            Apply Filters
                                        </button>
                                        <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-gray-600 radius-8 px-20 py-11">
                                            <i class="ph ph-x me-8"></i>
                                            Clear Filters
                                        </a>
                                        <div class="ms-auto">
                                            <small class="text-gray-500">
                                                @if(array_filter($currentFilters))
                                                    <i class="ph ph-info me-4"></i>
                                                    Filters applied - showing filtered results
                                                @else
                                                    <i class="ph ph-info me-4"></i>
                                                    No filters applied - showing all assignments
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Assignment Statistics -->
                <div class="row gy-4 mb-24">
                    <div class="col-xxl-2 col-md-4 col-sm-6">
                        <div class="card border-0 h-100">
                            <div class="card-body p-20">
                                <div class="flex-between gap-8 mb-16">
                                    <div class="flex-align gap-16">
                                        <div class="w-44 h-44 bg-main-50 text-main-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-file-text"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">
                                                {{ array_filter($currentFilters) ? 'Filtered' : 'Total' }} Assignments
                                            </span>
                                            <h4 class="mb-0 text-main-600">{{ number_format($assignmentStats['total']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-md-4 col-sm-6">
                        <div class="card border-0 h-100">
                            <div class="card-body p-20">
                                <div class="flex-between gap-8 mb-16">
                                    <div class="flex-align gap-16">
                                        <div class="w-44 h-44 bg-success-50 text-success-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-check-circle"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">Active</span>
                                            <h4 class="mb-0 text-success-600">{{ number_format($assignmentStats['active']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-md-4 col-sm-6">
                        <div class="card border-0 h-100">
                            <div class="card-body p-20">
                                <div class="flex-between gap-8 mb-16">
                                    <div class="flex-align gap-16">
                                        <div class="w-44 h-44 bg-warning-50 text-warning-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-file-dashed"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">Draft</span>
                                            <h4 class="mb-0 text-warning-600">{{ number_format($assignmentStats['draft']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-md-4 col-sm-6">
                        <div class="card border-0 h-100">
                            <div class="card-body p-20">
                                <div class="flex-between gap-8 mb-16">
                                    <div class="flex-align gap-16">
                                        <div class="w-44 h-44 bg-secondary-50 text-secondary-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-archive"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">Archived</span>
                                            <h4 class="mb-0 text-secondary-600">{{ number_format($assignmentStats['archived']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-md-4 col-sm-6">
                        <div class="card border-0 h-100">
                            <div class="card-body p-20">
                                <div class="flex-between gap-8 mb-16">
                                    <div class="flex-align gap-16">
                                        <div class="w-44 h-44 bg-danger-50 text-danger-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-clock"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">Overdue</span>
                                            <h4 class="mb-0 text-danger-600">{{ number_format($assignmentStats['overdue']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignments Table -->
                <div class="card border-0 overflow-hidden">
                    <div class="card-header bg-main-50 border-bottom border-gray-100 py-16 px-24">
                        <div class="flex-between flex-wrap gap-16">
                            <h6 class="text-lg fw-semibold mb-0 text-main-600">
                                <i class="ph ph-list me-8"></i>
                                Assignments List
                                @if(array_filter($currentFilters))
                                    <span class="badge bg-primary-50 text-primary-600 text-xs px-8 py-4 rounded-4 ms-8">
                                        Filtered
                                    </span>
                                @endif
                            </h6>
                            <div class="flex-align gap-16">
                                <small class="text-gray-600">
                                    Showing {{ $assignments->firstItem() ?? 0 }} to {{ $assignments->lastItem() ?? 0 }} of {{ $assignments->total() }} assignments
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="assignmentsTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="ps-24 py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'title', 'sort_order' => $currentFilters['sort_by'] == 'title' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Assignment
                                                @if($currentFilters['sort_by'] == 'title')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-16 text-gray-900 fw-semibold">Course & Instructor</th>
                                        <th class="py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => $currentFilters['sort_by'] == 'status' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Status
                                                @if($currentFilters['sort_by'] == 'status')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'deadline', 'sort_order' => $currentFilters['sort_by'] == 'deadline' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Deadline
                                                @if($currentFilters['sort_by'] == 'deadline')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-16 text-gray-900 fw-semibold">Submissions</th>
                                        <th class="py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => $currentFilters['sort_by'] == 'created_at' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Created
                                                @if($currentFilters['sort_by'] == 'created_at')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="pe-24 py-16 text-gray-900 fw-semibold text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assignments as $assignment)
                                        <tr>
                                            <td class="ps-24 py-16">
                                                <div>
                                                    <h6 class="text-sm fw-semibold mb-4">{{ $assignment->title }}</h6>
                                                    @if($assignment->description)
                                                        <p class="text-xs text-gray-500 mb-0">{{ Str::limit($assignment->description, 80) }}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                <div>
                                                    <span class="text-sm fw-medium text-gray-900">{{ $assignment->course->code }} - {{ $assignment->course->title }}</span>
                                                    <br><small class="text-gray-500">{{ $assignment->instructor->name }}</small>
                                                    @if($assignment->instructor->department)
                                                        <br><small class="text-gray-400">{{ $assignment->instructor->department }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                {!! $assignment->status_badge !!}
                                                @if($assignment->isOverdue())
                                                    <br><small class="text-danger fw-medium">
                                                        <i class="ph ph-warning-circle me-4"></i>Overdue
                                                    </small>
                                                @endif
                                            </td>
                                            <td class="py-16">
                                                <div>
                                                    <span class="text-sm fw-medium text-gray-900">{{ $assignment->getFormattedDeadline() }}</span>
                                                    <br>
                                                    @php
                                                        $daysUntil = $assignment->getDaysUntilDeadline();
                                                    @endphp
                                                    @if($daysUntil < 0)
                                                        <small class="text-danger">{{ abs($daysUntil) }} days overdue</small>
                                                    @elseif($daysUntil == 0)
                                                        <small class="text-warning fw-medium">Due today</small>
                                                    @elseif($daysUntil <= 7)
                                                        <small class="text-warning">{{ $daysUntil }} days left</small>
                                                    @else
                                                        <small class="text-gray-500">{{ $daysUntil }} days left</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                <div class="d-flex flex-column gap-4">
                                                    <small class="text-gray-600">
                                                        <i class="ph ph-files me-4"></i>{{ $assignment->submissions_count }} Total
                                                    </small>
                                                    @php
                                                        $stats = $assignment->getSubmissionStats();
                                                    @endphp
                                                    <small class="text-success-600">
                                                        <i class="ph ph-check me-4"></i>{{ $stats['graded'] }} Graded
                                                    </small>
                                                    <small class="text-warning-600">
                                                        <i class="ph ph-clock me-4"></i>{{ $stats['pending'] }} Pending
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                <span class="text-sm text-gray-600">{{ $assignment->created_at->format('M d, Y') }}</span>
                                                <br><small class="text-gray-400">{{ $assignment->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="pe-24 py-16 text-center">
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger radius-4 px-12 py-6 delete-assignment-btn"
                                                        data-assignment-id="{{ $assignment->id }}"
                                                        data-assignment-title="{{ $assignment->title }}"
                                                        data-course-name="{{ $assignment->course->title }}"
                                                        data-instructor-name="{{ $assignment->instructor->name }}"
                                                        data-submissions-count="{{ $assignment->submissions_count }}"
                                                        title="Delete Assignment">
                                                    <i class="ph ph-trash text-sm"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-32">
                                                <div class="text-gray-400">
                                                    <i class="ph ph-file-text text-4xl mb-16"></i>
                                                    <p class="mb-0">
                                                        @if(array_filter($currentFilters))
                                                            No assignments found matching your filters
                                                        @else
                                                            No assignments found
                                                        @endif
                                                    </p>
                                                    @if(array_filter($currentFilters))
                                                        <a href="{{ route('admin.assignments.index') }}" class="btn btn-sm btn-outline-primary-600 mt-12">
                                                            Clear Filters
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($assignments->hasPages())
                            <div class="border-top border-gray-100 px-24 py-16">
                                <div class="flex-between flex-wrap gap-16">
                                    <div class="text-sm text-gray-600">
                                        Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} assignments
                                    </div>
                                    {{ $assignments->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteAssignmentModal" tabindex="-1" aria-labelledby="deleteAssignmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 radius-12">
                <div class="modal-header border-bottom border-gray-100 py-16 px-24">
                    <h5 class="modal-title text-danger-600" id="deleteAssignmentModalLabel">
                        <i class="ph ph-warning-circle me-8"></i>
                        Confirm Assignment Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-24 py-20">
                    <div class="text-center mb-20">
                        <div class="w-64 h-64 bg-danger-50 text-danger-600 rounded-circle flex-center text-2xl mx-auto mb-16">
                            <i class="ph ph-trash"></i>
                        </div>
                        <h6 class="text-lg fw-semibold mb-8">Delete Assignment</h6>
                        <p class="text-gray-600 mb-0">
                            Are you sure you want to delete <strong id="deleteAssignmentTitle"></strong> 
                            from course <strong id="deleteCourseName"></strong> by <span id="deleteInstructorName"></span>?
                        </p>
                    </div>
                    <div class="alert alert-danger-50 border border-danger-200 radius-8 p-16">
                        <div class="flex-align gap-8">
                            <i class="ph ph-warning text-danger-600"></i>
                            <div>
                                <h6 class="text-sm fw-semibold text-danger-600 mb-4">Warning</h6>
                                <p class="text-xs text-danger-600 mb-0">
                                    This will permanently delete the assignment and all its submissions. This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div id="submissionsInfo" class="alert alert-info-50 border border-info-200 radius-8 p-16 d-none">
                        <div class="flex-align gap-8">
                            <i class="ph ph-info text-info-600"></i>
                            <div>
                                <h6 class="text-sm fw-semibold text-info-600 mb-4">Submissions Found</h6>
                                <p class="text-xs text-info-600 mb-0">
                                    This assignment has <span id="submissionsCountInfo"></span> submission(s) that will also be deleted.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-gray-100 px-24 py-16">
                    <button type="button" class="btn btn-gray radius-8 px-20 py-11" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger radius-8 px-20 py-11" id="confirmDeleteBtn">
                        <i class="ph ph-trash me-8"></i>
                        <span class="delete-text">Delete Assignment</span>
                        <span class="delete-loading d-none">
                            <span class="spinner-border spinner-border-sm me-8" role="status"></span>
                            Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteAssignmentModal'));
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            let currentAssignmentId = null;

            // Auto-submit form on filter change
            const filterForm = document.getElementById('filterForm');
            const autoSubmitElements = ['status', 'course', 'instructor', 'department', 'faculty', 'deadline', 'sort_by', 'sort_order', 'per_page'];
            
            autoSubmitElements.forEach(elementId => {
                const element = document.getElementById(elementId);
                if (element) {
                    element.addEventListener('change', function() {
                        filterForm.submit();
                    });
                }
            });

            // Search with debounce
            const searchInput = document.getElementById('search');
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filterForm.submit();
                }, 500);
            });

            // Delete assignment functionality
            document.querySelectorAll('.delete-assignment-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    currentAssignmentId = this.dataset.assignmentId;
                    const assignmentTitle = this.dataset.assignmentTitle;
                    const courseName = this.dataset.courseName;
                    const instructorName = this.dataset.instructorName;
                    const submissionsCount = parseInt(this.dataset.submissionsCount);

                    document.getElementById('deleteAssignmentTitle').textContent = assignmentTitle;
                    document.getElementById('deleteCourseName').textContent = courseName;
                    document.getElementById('deleteInstructorName').textContent = instructorName;

                    // Show submissions info if submissions exist
                    const submissionsInfo = document.getElementById('submissionsInfo');
                    if (submissionsCount > 0) {
                        document.getElementById('submissionsCountInfo').textContent = submissionsCount;
                        submissionsInfo.classList.remove('d-none');
                    } else {
                        submissionsInfo.classList.add('d-none');
                    }

                    deleteModal.show();
                });
            });

            // Confirm delete
            confirmDeleteBtn.addEventListener('click', function() {
                if (!currentAssignmentId) return;

                const deleteText = document.querySelector('.delete-text');
                const deleteLoading = document.querySelector('.delete-loading');

                // Show loading state
                this.disabled = true;
                deleteText.classList.add('d-none');
                deleteLoading.classList.remove('d-none');

                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Make delete request
                fetch(`/admin/assignments/${currentAssignmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide modal
                        deleteModal.hide();
                        
                        // Show success message
                        showAlert('success', data.message);
                        
                        // Remove assignment row from table
                        const assignmentRow = document.querySelector(`[data-assignment-id="${currentAssignmentId}"]`).closest('tr');
                        if (assignmentRow) {
                            assignmentRow.remove();
                        }
                        
                        // Reload page to update statistics
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    showAlert('error', 'Failed to delete assignment. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    this.disabled = false;
                    deleteText.classList.remove('d-none');
                    deleteLoading.classList.add('d-none');
                    currentAssignmentId = null;
                });
            });

            // Show alert function
            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const iconClass = type === 'success' ? 'ph-check-circle' : 'ph-warning-circle';
                const titleText = type === 'success' ? 'Success!' : 'Error!';
                
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show mb-24" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="${iconClass} text-${type === 'success' ? 'success' : 'danger'} me-12 text-xl"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-4 text-${type === 'success' ? 'success' : 'danger'} fw-semibold">${titleText}</h6>
                                <p class="mb-0 text-${type === 'success' ? 'success' : 'danger'}-emphasis">${message}</p>
                            </div>
                            <button type="button" class="btn-close ms-12" data-bs-dismiss="alert" aria-label="Close">
                                <i class="ph ph-x text-${type === 'success' ? 'success' : 'danger'}"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                // Insert alert at the top of dashboard body
                const dashboardBody = document.querySelector('.dashboard-body__content');
                dashboardBody.insertAdjacentHTML('afterbegin', alertHtml);
                
                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    const alert = dashboardBody.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            }
        });
    </script>
</x-admin-layout>