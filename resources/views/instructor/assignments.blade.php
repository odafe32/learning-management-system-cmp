<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Manage Assignments</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Assignments</li>
        </ul>
    </div>

    <!-- Filters and Search -->
    <div class="card h-100 p-0 radius-12 mb-24">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h6 class="text-lg fw-semibold mb-0">Filter Assignments</h6>
                <a href="{{ route('instructor.assignments.create') }}" class="btn btn-primary radius-8 px-20 py-11">
                    <iconify-icon icon="solar:add-circle-outline" class="icon text-lg"></iconify-icon>
                    Create Assignment
                </a>
            </div>
        </div>
        <div class="card-body p-24">
            <form method="GET" action="{{ route('instructor.assignments.manage') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Search</label>
                    <input type="text" 
                           class="form-control radius-8" 
                           name="search" 
                           value="{{ $filters['search'] ?? '' }}" 
                           placeholder="Search assignments...">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Course</label>
                    <select class="form-select radius-8" name="course_id">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ ($filters['course_id'] ?? '') == $course->id ? 'selected' : '' }}>
                                {{ $course->code }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Status</label>
                    <select class="form-select radius-8" name="status">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $status)
                            <option value="{{ $key }}" {{ ($filters['status'] ?? '') == $key ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Deadline</label>
                    <select class="form-select radius-8" name="deadline_status">
                        <option value="">All Deadlines</option>
                        <option value="upcoming" {{ ($filters['deadline_status'] ?? '') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="overdue" {{ ($filters['deadline_status'] ?? '') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary radius-8 px-20 py-11">
                            <iconify-icon icon="solar:magnifer-outline" class="icon text-lg"></iconify-icon>
                            Filter
                        </button>
                        <a href="{{ route('instructor.assignments.manage') }}" class="btn btn-outline-gray radius-8 px-20 py-11">
                            <iconify-icon icon="solar:refresh-outline" class="icon text-lg"></iconify-icon>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-24" role="alert">
            <iconify-icon icon="solar:check-circle-outline" class="icon text-lg"></iconify-icon>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-24" role="alert">
            <iconify-icon icon="solar:danger-circle-outline" class="icon text-lg"></iconify-icon>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Assignments List -->
    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h6 class="text-lg fw-semibold mb-0">
                    Assignments ({{ $assignments->total() }})
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-sm text-secondary-light">Showing {{ $assignments->firstItem() ?? 0 }} to {{ $assignments->lastItem() ?? 0 }} of {{ $assignments->total() }} results</span>
                </div>
            </div>
        </div>
        <div class="card-body p-24">
            @if($assignments->count() > 0)
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Assignment</th>
                                <th scope="col">Course</th>
                                <th scope="col">Deadline</th>
                                <th scope="col">Status</th>
                                <th scope="col">Submissions</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md fw-semibold mb-0">
                                                    <a href="{{ route('instructor.assignments.view', $assignment) }}" class="hover-text-primary">
                                                        {{ $assignment->title }}
                                                    </a>
                                                </h6>
                                                @if($assignment->description)
                                                    <p class="text-sm text-secondary-light mb-0 mt-1">
                                                        {{ Str::limit($assignment->description, 80) }}
                                                    </p>
                                                @endif
                                                <small class="text-xs text-secondary-light">
                                                    Created {{ $assignment->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-sm fw-medium">{{ $assignment->course->code }}</span>
                                            <span class="text-xs text-secondary-light">{{ $assignment->course->title }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm fw-medium">{{ $assignment->deadline->format('M d, Y') }}</span>
                                            <span class="text-xs text-secondary-light">{{ $assignment->deadline->format('g:i A') }}</span>
                                            @if($assignment->isOverdue())
                                                <span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4 mt-1">
                                                    Overdue
                                                </span>
                                            @elseif($assignment->getDaysUntilDeadline() <= 3)
                                                <span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4 mt-1">
                                                    Due Soon
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {!! $assignment->status_badge !!}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm fw-medium">{{ $assignment->getSubmissionsCount() }} Total</span>
                                            <span class="text-xs text-secondary-light">{{ $assignment->getPendingSubmissionsCount() }} Pending</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <a href="{{ route('instructor.assignments.view', $assignment) }}" 
                                               class="w-32-px h-32-px bg-primary-50 text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center"
                                               title="View Assignment">
                                                <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                            </a>
                                            <a href="{{ route('instructor.assignments.edit', $assignment) }}" 
                                               class="w-32-px h-32-px bg-success-50 text-success-600 rounded-circle d-inline-flex align-items-center justify-content-center"
                                               title="Edit Assignment">
                                                <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                            </a>
                                            <button type="button" 
                                                    class="w-32-px h-32-px bg-danger-50 text-danger-600 rounded-circle d-inline-flex align-items-center justify-content-center delete-assignment"
                                                    data-assignment-id="{{ $assignment->id }}"
                                                    data-assignment-title="{{ $assignment->title }}"
                                                    title="Delete Assignment">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-outline"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($assignments->hasPages())
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                        <span class="text-sm text-secondary-light">
                            Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} results
                        </span>
                        {{ $assignments->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <iconify-icon icon="solar:document-outline" class="icon text-6xl text-secondary-light mb-3"></iconify-icon>
                    <h6 class="text-lg fw-semibold mb-2">No Assignments Found</h6>
                    <p class="text-secondary-light mb-4">
                        @if(request()->hasAny(['search', 'course_id', 'status', 'deadline_status']))
                            No assignments match your current filters. Try adjusting your search criteria.
                        @else
                            You haven't created any assignments yet. Create your first assignment to get started.
                        @endif
                    </p>
                    <a href="{{ route('instructor.assignments.create') }}" class="btn btn-primary-600 radius-8 px-20 py-11">
                        <iconify-icon icon="solar:add-circle-outline" class="icon text-lg"></iconify-icon>
                        Create Assignment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAssignmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <iconify-icon icon="solar:danger-triangle-outline" class="icon text-6xl text-danger-600 mb-3"></iconify-icon>
                    <h6 class="text-lg fw-semibold mb-2">Are you sure?</h6>
                    <p class="text-secondary-light mb-0">
                        This will permanently delete the assignment "<span id="assignmentTitle"></span>" and all associated submissions. This action cannot be undone.
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-gray radius-8 px-20 py-11" data-bs-dismiss="modal">
                    Cancel
                </button>
                <form id="deleteAssignmentForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger-600 radius-8 px-20 py-11">
                        <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="icon text-lg"></iconify-icon>
                        Delete Assignment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete assignment functionality
    const deleteButtons = document.querySelectorAll('.delete-assignment');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteAssignmentModal'));
    const deleteForm = document.getElementById('deleteAssignmentForm');
    const assignmentTitleSpan = document.getElementById('assignmentTitle');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const assignmentId = this.getAttribute('data-assignment-id');
            const assignmentTitle = this.getAttribute('data-assignment-title');
            
            // Update modal content
            assignmentTitleSpan.textContent = assignmentTitle;
            deleteForm.action = `{{ route('instructor.assignments.manage') }}/${assignmentId}`;
            
            // Show modal
            deleteModal.show();
        });
    });

    // Auto-submit filter form on select change
    const filterSelects = document.querySelectorAll('select[name="course_id"], select[name="status"], select[name="deadline_status"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Search input with debounce
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.closest('form').submit();
            }, 500); // 500ms debounce
        });
    }

    // Tooltip initialization for action buttons
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Status badge click to filter
    const statusBadges = document.querySelectorAll('[data-status-filter]');
    statusBadges.forEach(badge => {
        badge.style.cursor = 'pointer';
        badge.addEventListener('click', function() {
            const status = this.getAttribute('data-status-filter');
            const form = document.querySelector('form[method="GET"]');
            const statusSelect = form.querySelector('select[name="status"]');
            statusSelect.value = status;
            form.submit();
        });
    });

    // Deadline status indicators
    const deadlineElements = document.querySelectorAll('[data-deadline]');
    deadlineElements.forEach(element => {
        const deadline = new Date(element.getAttribute('data-deadline'));
        const now = new Date();
        const diffTime = deadline - now;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays < 0) {
            element.classList.add('text-danger');
        } else if (diffDays <= 3) {
            element.classList.add('text-warning');
        }
    });

    // Bulk actions (if needed in future)
    const selectAllCheckbox = document.getElementById('selectAll');
    const assignmentCheckboxes = document.querySelectorAll('.assignment-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            assignmentCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    assignmentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.assignment-checkbox:checked');
        const bulkActionsDiv = document.getElementById('bulkActions');
        
        if (bulkActionsDiv) {
            if (checkedBoxes.length > 0) {
                bulkActionsDiv.style.display = 'block';
                bulkActionsDiv.querySelector('.selected-count').textContent = checkedBoxes.length;
            } else {
                bulkActionsDiv.style.display = 'none';
            }
        }
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput?.focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            searchInput.closest('form').submit();
        }
    });

    // Auto-refresh assignments every 5 minutes (optional)
    // setInterval(() => {
    //     if (!document.hidden) {
    //         window.location.reload();
    //     }
    // }, 300000); // 5 minutes
});
</script>
</x-instructor-layout>