<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">View Submissions</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <i class="ph ph-house text-lg"></i>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Submissions</li>
        </ul>
    </div>

    <!-- Filters and Search -->
    <div class="card h-100 p-0 radius-12 mb-24">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h6 class="text-lg fw-semibold mb-0">Filter Submissions</h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-info-50 text-info-600 px-12 py-6 rounded-4">
                        {{ $submissions->total() }} Total Submissions
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-24">
            <form method="GET" action="{{ route('instructor.submissions.view') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Search</label>
                    <input type="text" 
                           class="form-control radius-8" 
                           name="search" 
                           value="{{ $filters['search'] ?? '' }}" 
                           placeholder="Search by student name or assignment...">
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
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Assignment</label>
                    <select class="form-select radius-8" name="assignment_id">
                        <option value="">All Assignments</option>
                        @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}" {{ ($filters['assignment_id'] ?? '') == $assignment->id ? 'selected' : '' }}>
                                {{ Str::limit($assignment->title, 30) }}
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
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Date</label>
                    <input type="date" 
                           class="form-control radius-8" 
                           name="submission_date" 
                           value="{{ $filters['submission_date'] ?? '' }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary radius-8 px-20 py-11">
                            <i class="ph ph-magnifying-glass text-lg"></i>
                        </button>
                        <a href="{{ route('instructor.submissions.view') }}" class="btn btn-outline-gray radius-8 px-20 py-11">
                            <i class="ph ph-arrow-clockwise text-lg"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h6 class="text-lg fw-semibold mb-0">
                    Student Submissions ({{ $submissions->total() }})
                </h6>
            </div>
        </div>
        <div class="card-body p-24">
            @if($submissions->count() > 0)
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Student</th>
                                <th scope="col">Assignment</th>
                                <th scope="col">Course</th>
                                <th scope="col">Submission Type</th>
                                <th scope="col">Submitted</th>
                                <th scope="col">Status</th>
                                <th scope="col">Grade</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                                <tr>
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
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-50 text-info-600 px-8 py-4 rounded-4">
                                            {{ $submission->assignment->course->code }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @if($submission->hasCodeSubmission())
                                                <span class="badge bg-success-50 text-success-600 px-6 py-2 rounded-4" style="font-size: 10px;">
                                                    <i class="ph ph-code me-1"></i>Code
                                                </span>
                                            @endif
                                            @if($submission->hasFile())
                                                <span class="badge bg-primary-50 text-primary-600 px-6 py-2 rounded-4" style="font-size: 10px;">
                                                    <i class="ph ph-file me-1"></i>File
                                                </span>
                                            @endif
                                            @if(!$submission->hasCodeSubmission() && !$submission->hasFile())
                                                <span class="text-muted small">No content</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($submission->submitted_at)
                                            <div class="d-flex flex-column">
                                                <span class="text-sm fw-medium">{{ $submission->submitted_at->format('M d, Y') }}</span>
                                                <span class="text-xs text-secondary-light">{{ $submission->submitted_at->format('g:i A') }}</span>
                                                @if($submission->submitted_at > $submission->assignment->deadline)
                                                    <span class="badge bg-danger-50 text-danger-600 px-6 py-2 rounded-4 mt-1" style="font-size: 10px;">
                                                        Late
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">Not submitted</span>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $submission->status_badge !!}
                                    </td>
                                    <td>
                                        @if($submission->grade)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-lg fw-bold {{ $submission->grade_color }}">
                                                    {{ number_format($submission->grade, 1) }}%
                                                </span>
                                                <span class="badge bg-{{ $submission->grade_badge_color }}-50 text-{{ $submission->grade_badge_color }}-600 px-6 py-2 rounded-4">
                                                    {{ $submission->grade_letter }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-muted">Not graded</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <!-- View Button -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center view-submission"
                                                    data-submission-id="{{ $submission->id }}"
                                                    data-student-name="{{ $submission->student->name }}"
                                                    data-assignment-title="{{ $submission->assignment->title }}"
                                                    data-course-code="{{ $submission->assignment->course->code }}"
                                                    data-code-content="{{ $submission->code_content }}"
                                                    data-file-path="{{ $submission->file_path }}"
                                                    data-file-name="{{ $submission->file_name }}"
                                                    data-feedback="{{ $submission->feedback }}"
                                                    data-grade="{{ $submission->grade }}"
                                                    data-submitted-at="{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y g:i A') : 'Not submitted' }}"
                                                    data-deadline="{{ $submission->assignment->deadline->format('M d, Y g:i A') }}"
                                                    title="View Submission"
                                                    style="width: 32px; height: 32px;">
                                                <i class="ph ph-eye text-lg"></i>
                                            </button>
                                            
                                            @if(in_array($submission->status, ['submitted', 'pending', 'graded']))
                                                <!-- Grade/Edit Grade Button -->
                                                <button type="button" 
                                                        class="btn btn-sm {{ $submission->status === 'graded' ? 'btn-warning' : 'btn-success' }} d-inline-flex align-items-center justify-content-center grade-submission"
                                                        data-submission-id="{{ $submission->id }}"
                                                        data-student-name="{{ $submission->student->name }}"
                                                        data-assignment-title="{{ $submission->assignment->title }}"
                                                        data-current-grade="{{ $submission->grade }}"
                                                        data-current-feedback="{{ $submission->feedback }}"
                                                        title="{{ $submission->status === 'graded' ? 'Edit Grade' : 'Grade Submission' }}"
                                                        style="width: 32px; height: 32px;">
                                                    @if($submission->status === 'graded')
                                                        <i class="ph ph-pencil text-lg"></i>
                                                    @else
                                                        <i class="ph ph-check-circle text-lg"></i>
                                                    @endif
                                                </button>
                                            @endif
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
                    <i class="ph ph-file-text text-6xl text-secondary-light mb-3"></i>
                    <h6 class="text-lg fw-semibold mb-2">No Submissions Found</h6>
                    <p class="text-secondary-light mb-4">
                        @if(request()->hasAny(['search', 'course_id', 'assignment_id', 'status', 'submission_date']))
                            No submissions match your current filters. Try adjusting your search criteria.
                        @else
                            No submissions have been made to your assignments yet.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Submission Modal -->
<div class="modal fade" id="viewSubmissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ph ph-eye me-2 text-primary"></i>
                    <span id="modalSubmissionTitle">Submission Details</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Content Area -->
                    <div class="col-md-8 p-24">
                        <!-- Assignment Title Header -->
                        <div class="mb-20">
                            <div class="d-flex align-items-center justify-content-between mb-12">
                                <h5 class="text-lg fw-bold mb-0" id="modalAssignmentHeader">Assignment Title</h5>
                                <span class="badge bg-info-50 text-info-600 px-12 py-6 rounded-4" id="modalCourseCode">Course</span>
                            </div>
                            <div class="text-sm text-secondary-light mb-8">
                                <i class="ph ph-clock me-1"></i>
                                Deadline: <span id="modalDeadline"></span>
                            </div>
                            <hr>
                        </div>

                        <!-- Submission Content Tabs -->
                        <div class="mb-20">
                            <ul class="nav nav-tabs" id="submissionTabs" role="tablist">
                                <li class="nav-item" role="presentation" id="codeTabItem" style="display: none;">
                                    <button class="nav-link active" id="code-tab" data-bs-toggle="tab" data-bs-target="#code-content" type="button" role="tab">
                                        <i class="ph ph-code me-2"></i>Code Submission
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation" id="fileTabItem" style="display: none;">
                                    <button class="nav-link" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-content" type="button" role="tab">
                                        <i class="ph ph-file me-2"></i>File Submission
                                    </button>
                                </li>
                            </ul>
                            
                            <div class="tab-content mt-16" id="submissionTabContent">
                                <!-- Code Content Tab -->
                                <div class="tab-pane fade show active" id="code-content" role="tabpanel">
                                    <div class="position-relative">
                                        <pre id="modalCodeContent" class="bg-gray-50 p-16 rounded-8 overflow-auto" style="max-height: 400px;"><code></code></pre>
                                        <button type="button" class="btn btn-sm btn-secondary position-absolute top-0 end-0 m-2" onclick="copySubmissionCode()">
                                            <i class="ph ph-copy me-1"></i>
                                            Copy
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- File Content Tab -->
                                <div class="tab-pane fade" id="file-content" role="tabpanel">
                                    <div class="bg-gray-50 p-24 rounded-8 text-center">
                                        <div id="filePreview">
                                            <i class="ph ph-file text-6xl text-secondary-light mb-12"></i>
                                            <h6 class="text-md fw-semibold mb-8" id="modalFileName">No file uploaded</h6>
                                            <p class="text-sm text-secondary-light mb-16" id="modalFileSize"></p>
                                            <button type="button" class="btn btn-primary" id="downloadFileBtn" style="display: none;">
                                                <i class="ph ph-download me-2"></i>
                                                Download File
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Feedback Section -->
                        <div class="mb-20">
                            <h6 class="text-md fw-semibold mb-8">Instructor Feedback</h6>
                            <div id="modalFeedback" class="bg-info-50 p-16 rounded-8 min-height-100">
                                <em class="text-muted">No feedback provided yet</em>
                            </div>
                        </div>
                    </div>

                    <!-- Submission Details Sidebar -->
                    <div class="col-md-4 bg-gray-50 p-24">
                        <div class="mb-20">
                            <h6 class="text-md fw-semibold mb-12">Submission Details</h6>
                            
                            <div class="mb-12">
                                <label class="text-sm fw-medium text-secondary-light">Student</label>
                                <div id="modalStudentName" class="text-sm fw-semibold"></div>
                            </div>

                            <div class="mb-12">
                                <label class="text-sm fw-medium text-secondary-light">Submitted At</label>
                                <div id="modalSubmittedAt" class="text-sm"></div>
                            </div>

                            <div class="mb-12">
                                <label class="text-sm fw-medium text-secondary-light">Submission Types</label>
                                <div id="modalSubmissionTypes" class="text-sm"></div>
                            </div>

                            <div class="mb-12">
                                <label class="text-sm fw-medium text-secondary-light">Current Grade</label>
                                <div id="modalCurrentGrade" class="text-sm fw-semibold"></div>
                            </div>
                        </div>

                        <!-- Grading Scale Reference -->
                        <div class="mb-20">
                            <h6 class="text-md fw-semibold mb-12">Grading Scale</h6>
                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge bg-success-50 text-success-600 px-6 py-2 rounded-4">A</span>
                                    <span>70 - 100</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge bg-info-50 text-info-600 px-6 py-2 rounded-4">B</span>
                                    <span>60 - 69</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge bg-primary-50 text-primary-600 px-6 py-2 rounded-4">C</span>
                                    <span>50 - 59</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge bg-warning-50 text-warning-600 px-6 py-2 rounded-4">D</span>
                                    <span>46 - 49</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge bg-orange-50 text-orange-600 px-6 py-2 rounded-4">E</span>
                                    <span>41 - 45</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-danger-50 text-danger-600 px-6 py-2 rounded-4">F</span>
                                    <span>0 - 40</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mb-20">
                            <h6 class="text-md fw-semibold mb-12">Download Options</h6>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-info btn-sm" id="downloadCodeBtn" style="display: none;">
                                    <i class="ph ph-download me-2"></i>
                                    Download Code
                                </button>
                                <button type="button" class="btn btn-info btn-sm" id="downloadFileBtnSidebar" style="display: none;">
                                    <i class="ph ph-download me-2"></i>
                                    Download File
                                </button>
                                <button type="button" class="btn btn-success btn-sm" id="gradeFromModal">
                                    <i class="ph ph-check-circle me-2"></i>
                                    <span id="gradeButtonText">Grade This Submission</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-gray radius-8" data-bs-dismiss="modal">
                    <i class="ph ph-x me-2"></i>
                    Close
                </button>
                <button type="button" class="btn btn-success radius-8" id="gradeFromModalFooter">
                    <i class="ph ph-check-circle me-2"></i>
                    <span id="gradeFooterButtonText">Grade Submission</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Grade Submission Modal -->
<div class="modal fade" id="gradeSubmissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradeModalTitle">Grade Submission</h5>
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
                        <div class="mt-8">
                            <small class="text-muted">Grade Scale: A (70-100), B (60-69), C (50-59), D (46-49), E (41-45), F (0-40)</small>
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
                        <div class="mt-8">
                            <small class="text-muted">Constructive feedback helps students improve their work.</small>
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
                    <button type="submit" class="btn btn-success radius-8" id="submitGradeButton">
                        <i class="ph ph-check-circle me-2"></i>
                        <span id="submitGradeButtonText">Submit Grade</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.min-height-100 {
    min-height: 100px;
}

#modalCodeContent {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 14px;
    line-height: 1.5;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.grade-input:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.submission-late {
    border-left: 4px solid #dc3545;
}

.submission-ontime {
    border-left: 4px solid #28a745;
}

/* Orange color classes for E grade */
.bg-orange-50 {
    background-color: #fff7ed;
}

.text-orange-600 {
    color: #ea580c;
}

.text-orange {
    color: #ea580c;
}

.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}

.nav-tabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
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
    // View submission modal functionality
    const viewButtons = document.querySelectorAll('.view-submission');
    const viewModal = new bootstrap.Modal(document.getElementById('viewSubmissionModal'));
    const gradeModal = new bootstrap.Modal(document.getElementById('gradeSubmissionModal'));
    
    let currentSubmissionId = null;
    let currentSubmissionStatus = null;
    let currentFilePath = null;
    let currentFileName = null;
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get submission data from button attributes
            currentSubmissionId = this.getAttribute('data-submission-id');
            const studentName = this.getAttribute('data-student-name');
            const assignmentTitle = this.getAttribute('data-assignment-title');
            const courseCode = this.getAttribute('data-course-code');
            const codeContent = this.getAttribute('data-code-content');
            const filePath = this.getAttribute('data-file-path');
            const fileName = this.getAttribute('data-file-name');
            const feedback = this.getAttribute('data-feedback');
            const grade = this.getAttribute('data-grade');
            const submittedAt = this.getAttribute('data-submitted-at');
            const deadline = this.getAttribute('data-deadline');
            
            // Store current file info
            currentFilePath = filePath;
            currentFileName = fileName;
            
            // Determine if this is already graded
            const isGraded = grade && grade !== 'null';
            currentSubmissionStatus = isGraded ? 'graded' : 'pending';
            
            // Populate modal content
            document.getElementById('modalSubmissionTitle').textContent = `${studentName}'s Submission`;
            document.getElementById('modalAssignmentHeader').textContent = assignmentTitle;
            document.getElementById('modalCourseCode').textContent = courseCode;
            document.getElementById('modalStudentName').textContent = studentName;
            document.getElementById('modalSubmittedAt').textContent = submittedAt;
            document.getElementById('modalDeadline').textContent = deadline;
            
            // Handle submission types and tabs
            const hasCode = codeContent && codeContent.trim() !== '';
            const hasFile = filePath && filePath.trim() !== '';
            
            // Show/hide tabs based on content
            const codeTabItem = document.getElementById('codeTabItem');
            const fileTabItem = document.getElementById('fileTabItem');
            const codeTab = document.getElementById('code-tab');
            const fileTab = document.getElementById('file-tab');
            
            if (hasCode) {
                codeTabItem.style.display = 'block';
                document.getElementById('modalCodeContent').querySelector('code').textContent = codeContent;
                codeTab.classList.add('active');
                document.getElementById('code-content').classList.add('show', 'active');
                document.getElementById('downloadCodeBtn').style.display = 'block';
            } else {
                codeTabItem.style.display = 'none';
                codeTab.classList.remove('active');
                document.getElementById('code-content').classList.remove('show', 'active');
                document.getElementById('downloadCodeBtn').style.display = 'none';
            }
            
            if (hasFile) {
                fileTabItem.style.display = 'block';
                document.getElementById('modalFileName').textContent = fileName || 'Uploaded File';
                document.getElementById('downloadFileBtn').style.display = 'block';
                document.getElementById('downloadFileBtnSidebar').style.display = 'block';
                
                if (!hasCode) {
                    fileTab.classList.add('active');
                    document.getElementById('file-content').classList.add('show', 'active');
                }
            } else {
                fileTabItem.style.display = 'none';
                fileTab.classList.remove('active');
                document.getElementById('file-content').classList.remove('show', 'active');
                document.getElementById('downloadFileBtn').style.display = 'none';
                document.getElementById('downloadFileBtnSidebar').style.display = 'none';
            }
            
            // Update submission types display
            let submissionTypes = [];
            if (hasCode) submissionTypes.push('<span class="badge bg-success-50 text-success-600 px-6 py-2 rounded-4 me-1"><i class="ph ph-code me-1"></i>Code</span>');
            if (hasFile) submissionTypes.push('<span class="badge bg-primary-50 text-primary-600 px-6 py-2 rounded-4"><i class="ph ph-file me-1"></i>File</span>');
            
            document.getElementById('modalSubmissionTypes').innerHTML = submissionTypes.length > 0 ? submissionTypes.join(' ') : '<span class="text-muted">No content</span>';
            
            // Handle feedback
            const feedbackDiv = document.getElementById('modalFeedback');
            if (feedback && feedback.trim() !== '') {
                feedbackDiv.innerHTML = feedback;
                feedbackDiv.className = 'bg-info-50 p-16 rounded-8 min-height-100';
            } else {
                feedbackDiv.innerHTML = '<em class="text-muted">No feedback provided yet</em>';
                feedbackDiv.className = 'bg-gray-50 p-16 rounded-8 min-height-100';
            }
            
            // Handle grade display
            const gradeDiv = document.getElementById('modalCurrentGrade');
            if (grade && grade !== 'null') {
                const gradeValue = parseFloat(grade);
                const letterGrade = getLetterGrade(gradeValue);
                gradeDiv.innerHTML = `<span class="text-lg fw-bold ${getGradeColor(gradeValue)}">${gradeValue}%</span> <span class="badge bg-${getGradeBadgeColor(gradeValue)}-50 text-${getGradeBadgeColor(gradeValue)}-600 px-6 py-2 rounded-4">${letterGrade}</span>`;
            } else {
                gradeDiv.innerHTML = '<span class="text-muted">Not graded yet</span>';
            }
            
            // Update button text based on grading status
            updateGradeButtons(isGraded);
            
            // Show modal
            viewModal.show();
        });
    });
    
    function updateGradeButtons(isGraded) {
        const gradeButtonText = document.getElementById('gradeButtonText');
        const gradeFooterButtonText = document.getElementById('gradeFooterButtonText');
        const gradeFromModalBtn = document.getElementById('gradeFromModal');
        const gradeFromModalFooterBtn = document.getElementById('gradeFromModalFooter');
        
        if (isGraded) {
            gradeButtonText.textContent = 'Edit Grade';
            gradeFooterButtonText.textContent = 'Edit Grade';
            gradeFromModalBtn.className = 'btn btn-warning btn-sm';
            gradeFromModalFooterBtn.className = 'btn btn-warning radius-8';
            gradeFromModalBtn.innerHTML = '<i class="ph ph-pencil me-2"></i><span id="gradeButtonText">Edit Grade</span>';
            gradeFromModalFooterBtn.innerHTML = '<i class="ph ph-pencil me-2"></i><span id="gradeFooterButtonText">Edit Grade</span>';
        } else {
            gradeButtonText.textContent = 'Grade This Submission';
            gradeFooterButtonText.textContent = 'Grade Submission';
            gradeFromModalBtn.className = 'btn btn-success btn-sm';
            gradeFromModalFooterBtn.className = 'btn btn-success radius-8';
            gradeFromModalBtn.innerHTML = '<i class="ph ph-check-circle me-2"></i><span id="gradeButtonText">Grade This Submission</span>';
            gradeFromModalFooterBtn.innerHTML = '<i class="ph ph-check-circle me-2"></i><span id="gradeFooterButtonText">Grade Submission</span>';
        }
    }
    
    // Download code functionality
    document.getElementById('downloadCodeBtn').addEventListener('click', function() {
        downloadSubmissionCode();
    });
    
    // Download file functionality
    document.getElementById('downloadFileBtn').addEventListener('click', function() {
        downloadSubmissionFile();
    });
    
    document.getElementById('downloadFileBtnSidebar').addEventListener('click', function() {
        downloadSubmissionFile();
    });
    
    function downloadSubmissionFile() {
        if (currentFilePath && currentSubmissionId) {
            // Create a download link
            const downloadUrl = `/instructor/submissions/${currentSubmissionId}/download-file`;
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = currentFileName || 'submission_file';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
    
    // Grade submission modal functionality
    const gradeButtons = document.querySelectorAll('.grade-submission');
    
    gradeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openGradeModal(this);
        });
    });// Grade from view modal
document.getElementById('gradeFromModal').addEventListener('click', function() {
    viewModal.hide();
    // Find the corresponding grade button
    const gradeButton = document.querySelector(`[data-submission-id="${currentSubmissionId}"].grade-submission`);
    if (gradeButton) {
        openGradeModal(gradeButton);
    }
});

document.getElementById('gradeFromModalFooter').addEventListener('click', function() {
    document.getElementById('gradeFromModal').click();
});

function openGradeModal(button) {
    currentSubmissionId = button.getAttribute('data-submission-id');
    const studentName = button.getAttribute('data-student-name');
    const assignmentTitle = button.getAttribute('data-assignment-title');
    const currentGrade = button.getAttribute('data-current-grade');
    const currentFeedback = button.getAttribute('data-current-feedback');
    
    // Determine if this is an edit or new grade
    const isEdit = currentGrade && currentGrade !== 'null';
    
    // Update modal title and button text
    const modalTitle = document.getElementById('gradeModalTitle');
    const submitButtonText = document.getElementById('submitGradeButtonText');
    
    if (isEdit) {
        modalTitle.textContent = 'Edit Grade';
        submitButtonText.textContent = 'Update Grade';
    } else {
        modalTitle.textContent = 'Grade Submission';
        submitButtonText.textContent = 'Submit Grade';
    }
    
    // Populate grade modal
    document.getElementById('gradeStudentName').textContent = studentName;
    document.getElementById('gradeAssignmentTitle').textContent = assignmentTitle;
    document.getElementById('grade').value = currentGrade || '';
    document.getElementById('feedback').value = currentFeedback || '';
    
    // Set form action
    document.getElementById('gradeSubmissionForm').action = `/instructor/submissions/${currentSubmissionId}/grade`;
    
    // Update grade preview if there's a current grade
    if (currentGrade) {
        updateGradePreview(currentGrade);
    }
    
    gradeModal.show();
}

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
    // Updated grading scale
    if (grade >= 70) return 'A';
    if (grade >= 60) return 'B';
    if (grade >= 50) return 'C';
    if (grade >= 46) return 'D';
    if (grade >= 41) return 'E';
    return 'F';
}

function getGradeColor(grade) {
    // Updated color scheme based on new grading scale
    if (grade >= 70) return 'text-success';      // A - Green
    if (grade >= 60) return 'text-info';         // B - Blue  
    if (grade >= 50) return 'text-primary';      // C - Primary
    if (grade >= 46) return 'text-warning';      // D - Yellow
    if (grade >= 41) return 'text-orange';       // E - Orange
    return 'text-danger';                        // F - Red
}

function getGradeBadgeColor(grade) {
    // Updated badge colors based on new grading scale
    if (grade >= 70) return 'success';      // A - Green
    if (grade >= 60) return 'info';         // B - Blue
    if (grade >= 50) return 'primary';      // C - Primary  
    if (grade >= 46) return 'warning';      // D - Yellow
    if (grade >= 41) return 'orange';       // E - Orange
    return 'danger';                        // F - Red
}

// Form submission handling
document.getElementById('gradeSubmissionForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
});

// Auto-submit filter form on select change
const filterSelects = document.querySelectorAll('select[name="course_id"], select[name="assignment_id"], select[name="status"]');
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
});

// Copy submission code function
function copySubmissionCode() {
    const codeElement = document.getElementById('modalCodeContent').querySelector('code');
    const text = codeElement.textContent;
    
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="ph ph-check me-1"></i>Copied!';
        button.classList.add('btn-success');
        button.classList.remove('btn-secondary');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-secondary');
        }, 2000);
    });
}

// Download submission code function
function downloadSubmissionCode() {
    const codeContent = document.getElementById('modalCodeContent').querySelector('code').textContent;
    const studentName = document.getElementById('modalStudentName').textContent;
    const assignmentTitle = document.getElementById('modalAssignmentHeader').textContent;
    
    const blob = new Blob([codeContent], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `${studentName}_${assignmentTitle.replace(/[^a-z0-9]/gi, '_')}_code.txt`;
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}
</script>
</x-instructor-layout>