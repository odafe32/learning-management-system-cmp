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
                    <i class="ph ph-house text-lg"></i>
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
                    <i class="ph ph-plus text-lg"></i>
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
                            <i class="ph ph-magnifying-glass text-lg"></i>
                            Filter
                        </button>
                        <a href="{{ route('instructor.assignments.manage') }}" class="btn btn-outline-gray radius-8 px-20 py-11">
                            <i class="ph ph-arrow-clockwise text-lg"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                                                    <button type="button" class="btn-link p-0 text-start hover-text-primary view-assignment"
                                                            data-assignment-id="{{ $assignment->id }}"
                                                            data-assignment-title="{{ $assignment->title }}"
                                                            data-assignment-description="{{ $assignment->description }}"
                                                            data-assignment-deadline="{{ $assignment->getFormattedDeadline() }}"
                                                            data-assignment-status="{{ $assignment->status_label }}"
                                                            data-assignment-course="{{ $assignment->course->code }} - {{ $assignment->course->title }}"
                                                            data-assignment-submissions="{{ $assignment->getSubmissionsCount() }}"
                                                            data-assignment-pending="{{ $assignment->getPendingSubmissionsCount() }}"
                                                            data-assignment-graded="{{ $assignment->getGradedSubmissionsCount() }}"
                                                            data-assignment-created="{{ $assignment->created_at->format('M d, Y \a\t g:i A') }}"
                                                            data-assignment-code-sample="{{ $assignment->code_sample }}"
                                                            style="border: none; background: none; text-decoration: none;">
                                                        {{ $assignment->title }}
                                                    </button>
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
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <!-- View Button (Modal) -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center view-assignment"
                                                    data-assignment-id="{{ $assignment->id }}"
                                                    data-assignment-title="{{ $assignment->title }}"
                                                    data-assignment-description="{{ $assignment->description }}"
                                                    data-assignment-deadline="{{ $assignment->getFormattedDeadline() }}"
                                                    data-assignment-status="{{ $assignment->status_label }}"
                                                    data-assignment-course="{{ $assignment->course->code }} - {{ $assignment->course->title }}"
                                                    data-assignment-submissions="{{ $assignment->getSubmissionsCount() }}"
                                                    data-assignment-pending="{{ $assignment->getPendingSubmissionsCount() }}"
                                                    data-assignment-graded="{{ $assignment->getGradedSubmissionsCount() }}"
                                                    data-assignment-created="{{ $assignment->created_at->format('M d, Y \a\t g:i A') }}"
                                                    data-assignment-code-sample="{{ $assignment->code_sample }}"
                                                    title="View Assignment Details"
                                                    style="width: 32px; height: 32px;">
                                                <i class="ph ph-eye text-lg"></i>
                                            </button>
                                            
                                            <!-- Edit Button (Navigate to page) -->
                                            <a href="{{ route('instructor.assignments.edit', $assignment) }}" 
                                               class="btn btn-sm btn-success d-inline-flex align-items-center justify-content-center"
                                               title="Edit Assignment"
                                               style="width: 32px; height: 32px;">
                                                <i class="ph ph-pencil-simple text-lg"></i>
                                            </a>
                                            
                                            <!-- Delete Button -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center delete-assignment"
                                                    data-assignment-id="{{ $assignment->id }}"
                                                    data-assignment-title="{{ $assignment->title }}"
                                                    title="Delete Assignment"
                                                    style="width: 32px; height: 32px;">
                                                <i class="ph ph-trash text-lg"></i>
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
                    <i class="ph ph-file-text text-6xl text-secondary-light mb-3"></i>
                    <h6 class="text-lg fw-semibold mb-2">No Assignments Found</h6>
                    <p class="text-secondary-light mb-4">
                        @if(request()->hasAny(['search', 'course_id', 'status', 'deadline_status']))
                            No assignments match your current filters. Try adjusting your search criteria.
                        @else
                            You haven't created any assignments yet. Create your first assignment to get started.
                        @endif
                    </p>
                    <a href="{{ route('instructor.assignments.create') }}" class="btn btn-primary-600 radius-8 px-20 py-11">
                        <i class="ph ph-plus text-lg"></i>
                        Create Assignment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Assignment Modal -->
<div class="modal fade" id="viewAssignmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ph ph-eye me-2 text-primary"></i>
                    <span id="modalAssignmentTitle">Assignment Details</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Assignment Info -->
                    <div class="col-md-8 p-24">
                        <div class="mb-20">
                            <h6 class="text-md fw-semibold mb-8">Description</h6>
                            <div id="modalAssignmentDescription" class="text-secondary-light">
                                <!-- Description will be populated here -->
                            </div>
                        </div>

                        <!-- Code Sample -->
                        <div class="mb-20" id="codeSampleSection" style="display: none;">
                            <h6 class="text-md fw-semibold mb-8">Code Sample/Template</h6>
                            <div class="position-relative">
                                <pre id="modalCodeSample" class="bg-gray-50 p-16 rounded-8 overflow-auto" style="max-height: 300px;"><code></code></pre>
                                <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" onclick="copyCodeSample()">
                                    <i class="ph ph-copy me-1"></i>
                                    Copy
                                </button>
                            </div>
                        </div>

                        <!-- Submission Statistics -->
                        <div class="mb-20">
                            <h6 class="text-md fw-semibold mb-12">Submission Statistics</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="text-center p-12 bg-primary-50 rounded-8">
                                        <div class="text-2xl fw-bold text-primary" id="modalTotalSubmissions">0</div>
                                        <div class="text-xs text-secondary-light">Total</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-12 bg-warning-50 rounded-8">
                                        <div class="text-2xl fw-bold text-warning" id="modalPendingSubmissions">0</div>
                                        <div class="text-xs text-secondary-light">Pending</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-12 bg-success-50 rounded-8">
                                        <div class="text-2xl fw-bold text-success" id="modalGradedSubmissions">0</div>
                                        <div class="text-xs text-secondary-light">Graded</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-12 bg-info-50 rounded-8">
                                        <div class="text-2xl fw-bold text-info" id="modalCompletionRate">0%</div>
                                        <div class="text-xs text-secondary-light">Completion</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Details Sidebar -->
                    <div class="col-md-4 bg-gray-50 p-24">
                        <div class="mb-20">
                            <h6 class="text-md fw-semibold mb-12">Assignment Details</h6>
                            
                            <div class="mb-12">
                                <label class="text-sm fw-medium text-secondary-light">Course</label>
                                <div id="modalAssignmentCourse" class="text-sm fw-semibold"></div>
                            </div>

                            <div class="mb-12">
                                <label class="text-sm fw-medium text-secondary-light">Status</label>
                                <div id="modalAssignmentStatus"></div>
                            </div>

                            <div class="mb-12">
                                <label class="text-sm fw-medium text-secondary-light">Deadline</label>
                                <div id="modalAssignmentDeadline" class="text-sm fw-semibold"></div>
                            </div>

                            <div class="mb-12">
                                <label class="text-sm fw-medium text-secondary-light">Created</label>
                                <div id="modalAssignmentCreated" class="text-sm"></div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mb-20">
                            <h6 class="text-md fw-semibold mb-12">Quick Actions</h6>
                            <div class="d-grid gap-2">
                                <a href="#" id="modalEditLink" class="btn btn-primary btn-sm">
                                    <i class="ph ph-pencil-simple me-2"></i>
                                    Edit Assignment
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="viewSubmissions()">
                                    <i class="ph ph-list me-2"></i>
                                    View Submissions
                                </button>
                               
                            </div>
                        </div>

                        <!-- Assignment Timeline -->
                        <div>
                            <h6 class="text-md fw-semibold mb-12">Timeline</h6>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <div class="text-sm fw-medium">Created</div>
                                        <div class="text-xs text-secondary-light" id="timelineCreated"></div>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <div class="text-sm fw-medium">Deadline</div>
                                        <div class="text-xs text-secondary-light" id="timelineDeadline"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-gray radius-8" data-bs-dismiss="modal">
                    <i class="ph ph-x me-2"></i>
                    Close
                </button>
                <a href="#" id="modalEditButton" class="btn btn-primary radius-8">
                    <i class="ph ph-pencil-simple me-2"></i>
                    Edit Assignment
                </a>
            </div>
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
                    <i class="ph ph-warning-circle text-6xl text-danger-600 mb-3"></i>
                    <h6 class="text-lg fw-semibold mb-2">Are you sure?</h6>
                    <p class="text-secondary-light mb-0">
                        This will permanently delete the assignment "<span id="assignmentTitle"></span>" and all associated submissions. This action cannot be undone.
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-gray radius-8 px-20 py-11" data-bs-dismiss="modal">
                    Cancel
                </button>
                <form id="deleteAssignmentForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger radius-8 px-20 py-11">
                        <i class="ph ph-trash text-lg"></i>
                        Delete Assignment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 16px;
}

.timeline-marker {
    position: absolute;
    left: -16px;
    top: 4px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    padding-left: 8px;
}

.btn-link {
    color: inherit;
    text-decoration: none;
}

.btn-link:hover {
    color: var(--bs-primary);
    text-decoration: none;
}

#modalCodeSample {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 14px;
    line-height: 1.5;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.modal-xl {
    max-width: 1200px;
}

@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
        margin: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Assignments page loaded');
    
    // View assignment modal functionality
    const viewButtons = document.querySelectorAll('.view-assignment');
    const viewModal = new bootstrap.Modal(document.getElementById('viewAssignmentModal'));
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get assignment data from button attributes
            const assignmentId = this.getAttribute('data-assignment-id');
            const title = this.getAttribute('data-assignment-title');
            const description = this.getAttribute('data-assignment-description');
            const deadline = this.getAttribute('data-assignment-deadline');
            const status = this.getAttribute('data-assignment-status');
            const course = this.getAttribute('data-assignment-course');
            const totalSubmissions = this.getAttribute('data-assignment-submissions');
            const pendingSubmissions = this.getAttribute('data-assignment-pending');
            const gradedSubmissions = this.getAttribute('data-assignment-graded');
            const created = this.getAttribute('data-assignment-created');
            const codeSample = this.getAttribute('data-assignment-code-sample');
            
            // Populate modal content
            document.getElementById('modalAssignmentTitle').textContent = title;
            document.getElementById('modalAssignmentDescription').innerHTML = description || '<em class="text-muted">No description provided</em>';
            document.getElementById('modalAssignmentCourse').textContent = course;
            document.getElementById('modalAssignmentDeadline').textContent = deadline;
            document.getElementById('modalAssignmentCreated').textContent = created;
            document.getElementById('modalAssignmentStatus').innerHTML = `<span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded-4">${status}</span>`;
            
            // Populate submission statistics
            document.getElementById('modalTotalSubmissions').textContent = totalSubmissions;
            document.getElementById('modalPendingSubmissions').textContent = pendingSubmissions;
            document.getElementById('modalGradedSubmissions').textContent = gradedSubmissions;
            
            // Calculate completion rate
            const completionRate = totalSubmissions > 0 ? Math.round((gradedSubmissions / totalSubmissions) * 100) : 0;
            document.getElementById('modalCompletionRate').textContent = completionRate + '%';
            
            // Handle code sample
            const codeSampleSection = document.getElementById('codeSampleSection');
            const modalCodeSample = document.getElementById('modalCodeSample');
            
            if (codeSample && codeSample.trim() !== '') {
                modalCodeSample.querySelector('code').textContent = codeSample;
                codeSampleSection.style.display = 'block';
            } else {
                codeSampleSection.style.display = 'none';
            }
            
            // Set edit links
            const editUrl = `/instructor/assignments/${assignmentId}/edit`;
            document.getElementById('modalEditLink').href = editUrl;
            document.getElementById('modalEditButton').href = editUrl;
            
            // Set timeline
            document.getElementById('timelineCreated').textContent = created;
            document.getElementById('timelineDeadline').textContent = deadline;
            
            // Show modal
            viewModal.show();
        });
    });
    
    // Delete assignment functionality
    const deleteButtons = document.querySelectorAll('.delete-assignment');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteAssignmentModal'));
    const deleteForm = document.getElementById('deleteAssignmentForm');
    const assignmentTitleSpan = document.getElementById('assignmentTitle');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const assignmentId = this.getAttribute('data-assignment-id');
            const assignmentTitle = this.getAttribute('data-assignment-title');
            
            // Update modal content
            assignmentTitleSpan.textContent = assignmentTitle;
            deleteForm.action = `/instructor/assignments/${assignmentId}`;
            
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
            }, 500);
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Copy code sample function
function copyCodeSample() {
    const codeElement = document.getElementById('modalCodeSample').querySelector('code');
    const text = codeElement.textContent;
    
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="ph ph-check me-1"></i>Copied!';
        button.classList.add('btn-success');
        button.classList.remove('btn-outline-secondary');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    });
}

// Placeholder functions for quick actions
function viewSubmissions() {
    // Implement view submissions functionality
    console.log('View submissions clicked');
}

function downloadSubmissions() {
    // Implement download submissions functionality
    console.log('Download submissions clicked');
}
</script>
</x-instructor-layout>