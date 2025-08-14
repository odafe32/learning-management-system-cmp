<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Grade Assignments</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <i class="ph ph-house text-lg"></i>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('instructor.submissions.view') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    Submissions
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Grade Assignments</li>
        </ul>
    </div>

    <!-- Priority Alert -->
    @php
        $overdueCount = $submissions->where('assignment.deadline', '<', now())->count();
        $pendingCount = $submissions->whereIn('status', ['submitted', 'pending'])->count();
    @endphp
    
    @if($overdueCount > 0)
        <div class="alert alert-warning alert-dismissible fade show mb-24" role="alert">
            <div class="d-flex align-items-center">
                <i class="ph ph-warning-circle text-lg me-2"></i>
                <div>
                    <strong>Attention Required:</strong> You have {{ $overdueCount }} overdue submission(s) that need grading.
                    <div class="mt-1">
                        <small>Overdue submissions are prioritized at the top of the list.</small>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters and Search -->
    <div class="card h-100 p-0 radius-12 mb-24">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h6 class="text-lg fw-semibold mb-0">Filter Submissions to Grade</h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning-50 text-warning-600 px-12 py-6 rounded-4">
                        {{ $pendingCount }} Pending Grades
                    </span>
                    @if($overdueCount > 0)
                        <span class="badge bg-danger-50 text-danger-600 px-12 py-6 rounded-4">
                            {{ $overdueCount }} Overdue
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body p-24">
            <form method="GET" action="{{ route('instructor.assignments.grade') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Search</label>
                    <input type="text" 
                           class="form-control radius-8" 
                           name="search" 
                           value="{{ $filters['search'] ?? '' }}" 
                           placeholder="Search by student name or assignment...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Course</label>
                    <select class="form-select radius-8" name="course_id">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ ($filters['course_id'] ?? '') == $course->id ? 'selected' : '' }}>
                                {{ $course->code }} - {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Assignment</label>
                    <select class="form-select radius-8" name="assignment_id">
                        <option value="">All Assignments</option>
                        @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}" {{ ($filters['assignment_id'] ?? '') == $assignment->id ? 'selected' : '' }}>
                                {{ Str::limit($assignment->title, 40) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary radius-8 px-20 py-11">
                            <i class="ph ph-magnifying-glass text-lg"></i>
                        </button>
                        <a href="{{ route('instructor.assignments.grade') }}" class="btn btn-outline-gray radius-8 px-20 py-11">
                            <i class="ph ph-arrow-clockwise text-lg"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Grading Queue -->
    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h6 class="text-lg fw-semibold mb-0">
                    Grading Queue ({{ $submissions->total() }})
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-success radius-8 px-20 py-11" id="bulkGradeBtn" style="display: none;">
                        <i class="ph ph-check-circle text-lg"></i>
                        Grade Selected
                    </button>
                    <a href="{{ route('instructor.submissions.view') }}" class="btn btn-outline-primary radius-8 px-20 py-11">
                        <i class="ph ph-eye text-lg"></i>
                        View All Submissions
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-24">
            @if($submissions->count() > 0)
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col" width="40">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th scope="col">Priority</th>
                                <th scope="col">Student</th>
                                <th scope="col">Assignment</th>
                                <th scope="col">Course</th>
                                <th scope="col">Submitted</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                                @php
                                    $isOverdue = $submission->assignment->deadline < now();
                                    $isLateSubmission = $submission->submitted_at && $submission->submitted_at > $submission->assignment->deadline;
                                @endphp
                                <tr class="{{ $isOverdue ? 'table-warning' : '' }}">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input submission-checkbox" type="checkbox" value="{{ $submission->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($isOverdue)
                                                <span class="badge bg-danger-50 text-danger-600 px-8 py-4 rounded-4">
                                                    <i class="ph ph-warning-circle me-1"></i>
                                                    Overdue
                                                </span>
                                            @elseif($isLateSubmission)
                                                <span class="badge bg-warning-50 text-warning-600 px-8 py-4 rounded-4">
                                                    <i class="ph ph-clock me-1"></i>
                                                    Late
                                                </span>
                                            @else
                                                <span class="badge bg-success-50 text-success-600 px-8 py-4 rounded-4">
                                                    <i class="ph ph-check me-1"></i>
                                                    On Time
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="w-40 h-40 rounded-circle bg-primary-50 d-flex align-items-center justify-content-center">
                                                <span class="text-primary fw-semibold">
                                                    {{ strtoupper(substr($submission->student->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-0">{{ $submission->student->name }}</h6>
                                                <span class="text-sm text-secondary-light">{{ $submission->student->matric_or_staff_id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-0">{{ Str::limit($submission->assignment->title, 40) }}</h6>
                                            <span class="text-xs text-secondary-light">
                                                Deadline: {{ $submission->assignment->deadline->format('M d, Y g:i A') }}
                                                @if($isOverdue)
                                                    <span class="text-danger">({{ $submission->assignment->deadline->diffForHumans() }})</span>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4">
                                            {{ $submission->assignment->course->code }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($submission->submitted_at)
                                            <div class="d-flex flex-column">
                                                <span class="text-sm fw-medium">{{ $submission->submitted_at->format('M d, Y') }}</span>
                                                <span class="text-xs text-secondary-light">{{ $submission->submitted_at->format('g:i A') }}</span>
                                                @if($isLateSubmission)
                                                    <span class="text-xs text-danger">
                                                        ({{ $submission->submitted_at->diffForHumans($submission->assignment->deadline) }} late)
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">Not submitted</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <!-- Quick Grade Button -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-success d-inline-flex align-items-center justify-content-center grade-submission"
                                                    data-submission-id="{{ $submission->id }}"
                                                    data-student-name="{{ $submission->student->name }}"
                                                    data-assignment-title="{{ $submission->assignment->title }}"
                                                    data-current-grade="{{ $submission->grade }}"
                                                    data-current-feedback="{{ $submission->feedback }}"
                                                    title="Grade Submission"
                                                    style="width: 32px; height: 32px;">
                                                <i class="ph ph-check-circle text-lg"></i>
                                            </button>
                                            
                                            <!-- View Code Button -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center view-code"
                                                    data-submission-id="{{ $submission->id }}"
                                                    data-student-name="{{ $submission->student->name }}"
                                                    data-assignment-title="{{ $submission->assignment->title }}"
                                                    data-code-content="{{ $submission->code_content }}"
                                                    title="View Code"
                                                    style="width: 32px; height: 32px;">
                                                <i class="ph ph-code text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($submissions->hasPages())
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                        <span class="text-sm text-secondary-light">
                            Showing {{ $submissions->firstItem() }} to {{ $submissions->lastItem() }} of {{ $submissions->total() }} results
                        </span>
                        {{ $submissions->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="ph ph-check-circle text-6xl text-success-600 mb-3"></i>
                    <h6 class="text-lg fw-semibold mb-2">All Caught Up!</h6>
                    <p class="text-secondary-light mb-4">
                        @if(request()->hasAny(['search', 'course_id', 'assignment_id']))
                            No submissions match your current filters that need grading.
                        @else
                            There are no submissions that need grading at the moment.
                        @endif
                    </p>
                    <a href="{{ route('instructor.submissions.view') }}" class="btn btn-primary-600 radius-8 px-20 py-11">
                        <i class="ph ph-eye text-lg"></i>
                        View All Submissions
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Grade Submission Modal -->
<div class="modal fade" id="gradeSubmissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Grade Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="gradeSubmissionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-20">
                        <h6 class="text-md fw-semibold mb-8">Student Information</h6>
                        <div class="bg-gray-50 p-12 rounded-8">
                            <div class="d-flex justify-content-between mb-8">
                                <span class="text-sm text-secondary-light">Student:</span>
                                <span class="text-sm fw-semibold" id="gradeStudentName"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-secondary-light">Assignment:</span>
                                <span class="text-sm fw-semibold" id="gradeAssignmentTitle"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-20">
                        <label for="grade" class="form-label fw-semibold text-primary-light text-sm mb-8">
                            Grade (0-100) <span class="text-danger-600">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control radius-8" 
                                   id="grade" 
                                   name="grade" 
                                   min="0" 
                                   max="100" 
                                   step="0.1"
                                   placeholder="Enter grade"
                                   required>
                            <span class="input-group-text">%</span>
                        </div>
                        
                        <!-- Quick Grade Buttons -->
                        <div class="mt-12">
                            <small class="text-muted d-block mb-8">Quick Grade:</small>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-outline-success quick-grade" data-grade="95">A (95%)</button>
                                <button type="button" class="btn btn-sm btn-outline-info quick-grade" data-grade="85">B (85%)</button>
                                <button type="button" class="btn btn-sm btn-outline-warning quick-grade" data-grade="75">C (75%)</button>
                                <button type="button" class="btn btn-sm btn-outline-danger quick-grade" data-grade="65">D (65%)</button>
                                <button type="button" class="btn btn-sm btn-outline-dark quick-grade" data-grade="45">F (45%)</button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-20">
                        <label for="feedback" class="form-label fw-semibold text-primary-light text-sm mb-8">
                            Feedback
                        </label>
                        <textarea class="form-control radius-8" 
                                  id="feedback" 
                                  name="feedback" 
                                  rows="4" 
                                  placeholder="Provide feedback to the student (optional)"></textarea>
                        
                        <!-- Quick Feedback Templates -->
                        <div class="mt-12">
                            <small class="text-muted d-block mb-8">Quick Feedback:</small>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-feedback" data-feedback="Excellent work! Well structured and efficient code.">Excellent</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-feedback" data-feedback="Good job! Minor improvements needed in code organization.">Good</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-feedback" data-feedback="Needs improvement. Please review the requirements and resubmit.">Needs Work</button>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Preview -->
                    <div class="mb-20" id="gradePreview" style="display: none;">
                        <h6 class="text-md fw-semibold mb-8">Grade Preview</h6>
                        <div class="bg-success-50 p-12 rounded-8 text-center">
                            <div class="text-2xl fw-bold text-success mb-4" id="previewGrade">0%</div>
                            <div class="badge bg-success-100 text-success-600 px-12 py-6 rounded-4" id="previewLetter">F</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray radius-8" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success radius-8">
                        <i class="ph ph-check-circle me-2"></i>
                        Submit Grade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Code Modal -->
<div class="modal fade" id="viewCodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ph ph-code me-2 text-primary"></i>
                    <span id="codeModalTitle">View Code</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-24">
                <div class="position-relative">
                    <pre id="codeContent" class="bg-gray-50 p-16 rounded-8 overflow-auto" style="max-height: 400px;"><code></code></pre>
                    <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" onclick="copyCode()">
                        <i class="ph ph-copy me-1"></i>
                        Copy
                    </button>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-gray radius-8" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-success radius-8" id="gradeFromCodeModal">
                    <i class="ph ph-check-circle me-2"></i>
                    Grade This Submission
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

#codeContent {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 14px;
    line-height: 1.5;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.quick-grade:hover, .quick-feedback:hover {
    transform: translateY(-1px);
}

.submission-checkbox:checked {
    background-color: #28a745;
    border-color: #28a745;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeModal = new bootstrap.Modal(document.getElementById('gradeSubmissionModal'));
    const codeModal = new bootstrap.Modal(document.getElementById('viewCodeModal'));
    
    let currentSubmissionId = null;
    
    // Grade submission functionality
    const gradeButtons = document.querySelectorAll('.grade-submission');
    gradeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openGradeModal(this);
        });
    });
    
    // View code functionality
    const viewCodeButtons = document.querySelectorAll('.view-code');
    viewCodeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            currentSubmissionId = this.getAttribute('data-submission-id');
            const studentName = this.getAttribute('data-student-name');
            const assignmentTitle = this.getAttribute('data-assignment-title');
            const codeContent = this.getAttribute('data-code-content');
            
            document.getElementById('codeModalTitle').textContent = `${studentName}'s Code`;
            document.getElementById('codeContent').querySelector('code').textContent = codeContent || 'No code submitted';
            
            codeModal.show();
        });
    });
    
    // Grade from code modal
    document.getElementById('gradeFromCodeModal').addEventListener('click', function() {
        codeModal.hide();
        const gradeButton = document.querySelector(`[data-submission-id="${currentSubmissionId}"].grade-submission`);
        if (gradeButton) {
            openGradeModal(gradeButton);
        }
    });
    
    function openGradeModal(button) {
        currentSubmissionId = button.getAttribute('data-submission-id');
        const studentName = button.getAttribute('data-student-name');
        const assignmentTitle = button.getAttribute('data-assignment-title');
        const currentGrade = button.getAttribute('data-current-grade');
        const currentFeedback = button.getAttribute('data-current-feedback');
        
        document.getElementById('gradeStudentName').textContent = studentName;
        document.getElementById('gradeAssignmentTitle').textContent = assignmentTitle;
        document.getElementById('grade').value = currentGrade || '';
        document.getElementById('feedback').value = currentFeedback || '';
        
        document.getElementById('gradeSubmissionForm').action = `/instructor/submissions/${currentSubmissionId}/grade`;
        
        if (currentGrade) {
            updateGradePreview(currentGrade);
        }
        
        gradeModal.show();
    }
    
    // Quick grade buttons
    document.querySelectorAll('.quick-grade').forEach(button => {
        button.addEventListener('click', function() {
            const grade = this.getAttribute('data-grade');
            document.getElementById('grade').value = grade;
            updateGradePreview(grade);
        });
    });
    
    // Quick feedback buttons
    document.querySelectorAll('.quick-feedback').forEach(button => {
        button.addEventListener('click', function() {
            const feedback = this.getAttribute('data-feedback');
            const currentFeedback = document.getElementById('feedback').value;
            document.getElementById('feedback').value = currentFeedback ? currentFeedback + '\n\n' + feedback : feedback;
        });
    });
    
    // Grade input change handler
    document.getElementById('grade').addEventListener('input', function() {
        const grade = parseFloat(this.value);
        if (!isNaN(grade) && grade >= 0 && grade <= 100) {
            updateGradePreview(grade);
        } else {
            document.getElementById('gradePreview').style.display = 'none';
        }
    });
    
    function updateGradePreview(grade) {
        const gradeValue = parseFloat(grade);
        const letterGrade = getLetterGrade(gradeValue);
        const color = getGradeBadgeColor(gradeValue);
        
        document.getElementById('previewGrade').textContent = gradeValue + '%';
        document.getElementById('previewGrade').className = `text-2xl fw-bold text-${color} mb-4`;
        document.getElementById('previewLetter').textContent = letterGrade;
        document.getElementById('previewLetter').className = `badge bg-${color}-100 text-${color}-600 px-12 py-6 rounded-4`;
        document.getElementById('gradePreview').style.display = 'block';
    }
    
    function getLetterGrade(grade) {
        if (grade >= 90) return 'A';
        if (grade >= 80) return 'B';
        if (grade >= 70) return 'C';
        if (grade >= 60) return 'D';
        return 'F';
    }
    
    function getGradeBadgeColor(grade) {
        if (grade >= 70) return 'success';
        if (grade >= 50) return 'warning';
        return 'danger';
    }
    
    // Bulk selection functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const submissionCheckboxes = document.querySelectorAll('.submission-checkbox');
    const bulkGradeBtn = document.getElementById('bulkGradeBtn');
    
    selectAllCheckbox?.addEventListener('change', function() {
        submissionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });
    
    submissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
    
    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.submission-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkGradeBtn.style.display = 'inline-block';
        } else {
            bulkGradeBtn.style.display = 'none';
        }
    }
    
    // Form submission handling
    document.getElementById('gradeSubmissionForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
    });
    
    // Auto-submit filter form
    const filterSelects = document.querySelectorAll('select[name="course_id"], select[name="assignment_id"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    
    // Search with debounce
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
});

// Copy code function
function copyCode() {
    const codeElement = document.getElementById('codeContent').querySelector('code');
    const text = codeElement.textContent;
    
    navigator.clipboard.writeText(text).then(function() {
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
</script>
</x-instructor-layout>