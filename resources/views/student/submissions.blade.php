<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <!-- Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">My Submissions</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Submissions</li>
        </ul>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-24">
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-2">{{ $stats['total'] ?? 0 }}</h4>
                            <span class="text-gray-600">Total Submissions</span>
                        </div>
                        <div class="w-44 h-44 bg-primary-50 text-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:document-text-outline" class="text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-2">{{ $stats['pending'] ?? 0 }}</h4>
                            <span class="text-gray-600">Pending Review</span>
                        </div>
                        <div class="w-44 h-44 bg-warning-50 text-warning-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:clock-circle-outline" class="text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-2">{{ $stats['graded'] ?? 0 }}</h4>
                            <span class="text-gray-600">Graded</span>
                        </div>
                        <div class="w-44 h-44 bg-success-50 text-success-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:check-circle-outline" class="text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-2">{{ isset($stats['average_grade']) && $stats['average_grade'] ? number_format($stats['average_grade'], 1) . '%' : 'N/A' }}</h4>
                            <span class="text-gray-600">Average Grade</span>
                        </div>
                        <div class="w-44 h-44 bg-info-50 text-info-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:star-outline" class="text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-24">
        <div class="card-header">
            <h5 class="card-title mb-0">Filter Submissions</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('student.submissions.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Course</label>
                    <select name="course" class="form-select">
                        <option value="">All Courses</option>
                        @if(isset($enrolledCourses))
                            @foreach($enrolledCourses as $course)
                                <option value="{{ $course->id }}" {{ ($currentFilters['course'] ?? '') == $course->id ? 'selected' : '' }}>
                                    {{ $course->code }} - {{ $course->title }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="submitted" {{ ($currentFilters['status'] ?? '') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="pending" {{ ($currentFilters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="graded" {{ ($currentFilters['status'] ?? '') == 'graded' ? 'selected' : '' }}>Graded</option>
                        <option value="returned" {{ ($currentFilters['status'] ?? '') == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search assignments..." value="{{ $currentFilters['search'] ?? '' }}">
                        <button type="submit" class="btn btn-primary">
                            <iconify-icon icon="solar:magnifer-outline"></iconify-icon>
                        </button>
                        <a href="{{ route('student.submissions.index') }}" class="btn btn-secondary">
                            <iconify-icon icon="solar:refresh-outline"></iconify-icon>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Your Submissions</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('student.assignments.index') }}" class="btn btn-primary btn-sm">
                        <iconify-icon icon="solar:document-add-outline" class="icon"></iconify-icon>
                        View Assignments
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if(isset($submissions) && $submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-dark">Assignment</th>
                                <th class="text-dark">Course</th>
                                <th class="text-dark">Submitted</th>
                                <th class="text-dark">Status</th>
                                <th class="text-dark">Grade</th>
                                <th class="text-dark">Submission Type</th>
                                <th class="text-dark">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-1 text-dark">{{ $submission->assignment->title ?? 'N/A' }}</h6>
                                            <small class="text-muted">
                                                Deadline: {{ $submission->assignment && $submission->assignment->deadline ? $submission->assignment->deadline->format('M d, Y h:i A') : 'No deadline' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($submission->assignment && $submission->assignment->course)
                                            <span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded-4">
                                                {{ $submission->assignment->course->code }}
                                            </span>
                                            <div class="text-sm text-muted mt-1">{{ $submission->assignment->course->title }}</div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <span class="text-dark">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y') : 'N/A' }}</span>
                                            @if($submission->submitted_at)
                                                <small class="d-block text-muted">{{ $submission->submitted_at->format('h:i A') }}</small>
                                            @endif
                                        </div>
                                        @if($submission->isLate())
                                            <small class="text-danger">
                                                <iconify-icon icon="solar:clock-circle-outline" class="icon"></iconify-icon>
                                                Late Submission
                                            </small>
                                        @else
                                            <small class="text-success">
                                                <iconify-icon icon="solar:check-circle-outline" class="icon"></iconify-icon>
                                                On Time
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $submission->status_badge !!}
                                    </td>
                                    <td>
                                        @if($submission->isGraded())
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-{{ $submission->grade_badge_color }}-50 text-{{ $submission->grade_badge_color }}-600 px-8 py-4 rounded-4">
                                                    {{ $submission->formatted_grade }} ({{ $submission->grade_letter }})
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-muted">Not graded</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @if($submission->hasCodeSubmission())
                                                <span class="badge bg-info-50 text-info-600 px-6 py-2 rounded-3 text-xs">Code</span>
                                            @endif
                                            @if($submission->hasFile())
                                                <span class="badge bg-success-50 text-success-600 px-6 py-2 rounded-3 text-xs">File</span>
                                            @endif
                                            @if(!$submission->hasCodeSubmission() && !$submission->hasFile())
                                                <span class="badge bg-secondary-50 text-secondary-600 px-6 py-2 rounded-3 text-xs">Text</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('student.submissions.show', $submission) }}" 
                                               class="btn btn-primary btn-sm" 
                                               title="View Details">
                                                <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                            </a>
                                            @if($submission->hasFile())
                                                <button type="button" 
                                                        class="btn btn-success btn-sm" 
                                                        onclick="downloadSubmissionFile('{{ $submission->id }}')"
                                                        title="Download File">
                                                    <iconify-icon icon="solar:download-outline"></iconify-icon>
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
                @if(method_exists($submissions, 'hasPages') && $submissions->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            {{ $submissions->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <iconify-icon icon="solar:document-outline" class="text-6xl text-muted"></iconify-icon>
                    </div>
                    <h5 class="mb-2">No Submissions Found</h5>
                    <p class="text-muted mb-4">You haven't submitted any assignments yet.</p>
                    <a href="{{ route('student.assignments.index') }}" class="btn btn-primary">
                        <iconify-icon icon="solar:document-add-outline" class="icon"></iconify-icon>
                        View Available Assignments
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Include Iconify -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>

<script>
function downloadSubmissionFile(submissionId) {
    // Create a temporary form to download the file
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = `/student/submissions/${submissionId}/download`;
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Handle form submissions with loading states
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                submitBtn.disabled = true;
                
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            }
        });
    });
});
</script>

<style>
/* Ensure proper text visibility */
.table th {
    color: #374151 !important;
    font-weight: 600;
}

.table td {
    color: #374151;
}

.table-striped > tbody > tr:nth-of-type(odd) > td {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Loading animation */
.spinner-border {
    animation: spinner-border 0.75s linear infinite;
}

@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .btn-sm {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
</x-student-layout>