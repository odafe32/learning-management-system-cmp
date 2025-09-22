<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <!-- Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Submission Details</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('student.submissions.index') }}" class="hover-text-primary">Submissions</a>
            </li>
            <li>-</li>
            <li class="fw-medium">Details</li>
        </ul>
    </div>

    <div class="row">
        <!-- Submission Details -->
        <div class="col-lg-8">
            <!-- Assignment Information -->
            <div class="card mb-24">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <iconify-icon icon="solar:document-text-outline" class="icon me-2"></iconify-icon>
                        Assignment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3 text-dark">{{ $submission->assignment->title ?? 'N/A' }}</h6>
                            <div class="mb-3">
                                @if($submission->assignment && $submission->assignment->course)
                                    <span class="badge bg-primary-50 text-primary-600 px-12 py-6 rounded-4">
                                        {{ $submission->assignment->course->code }} - {{ $submission->assignment->course->title }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-muted mb-0">{{ $submission->assignment->description ?? 'No description available.' }}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="submission-info">
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">Deadline:</label>
                                    <div class="text-dark">
                                        {{ $submission->assignment && $submission->assignment->deadline ? $submission->assignment->deadline->format('M d, Y h:i A') : 'No deadline' }}
                                    </div>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">Max Points:</label>
                                    <div class="text-dark">{{ $submission->assignment->max_points ?? 100 }} points</div>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">Instructor:</label>
                                    <div class="text-dark">{{ $submission->assignment->course->instructor->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submission Content -->
            <div class="card mb-24">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <iconify-icon icon="solar:code-outline" class="icon me-2"></iconify-icon>
                        Your Submission
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Code Submission -->
                    @if($submission->hasCodeSubmission())
                        <div class="mb-4">
                            <h6 class="mb-3 text-dark">Code Submission</h6>
                            <div class="code-container">
                                <pre class="bg-light p-3 rounded border"><code class="text-dark">{{ $submission->code_content }}</code></pre>
                            </div>
                        </div>
                    @endif

                    <!-- File Submission -->
                    @if($submission->hasFile())
                        <div class="mb-4">
                            <h6 class="mb-3 text-dark">File Submission</h6>
                            <div class="file-info bg-light p-3 rounded border">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <iconify-icon icon="solar:document-outline" class="text-2xl text-primary"></iconify-icon>
                                        <div>
                                            <div class="fw-medium text-dark">{{ $submission->file_name ?? 'Submission File' }}</div>
                                            <small class="text-muted">{{ $submission->formatted_file_size ?? 'Unknown size' }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.submissions.download', $submission) }}" 
                                       class="btn btn-primary btn-sm">
                                        <iconify-icon icon="solar:download-outline" class="icon"></iconify-icon>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Text Submission (if exists) -->
                    @if(!empty($submission->submission_text))
                        <div class="mb-4">
                            <h6 class="mb-3 text-dark">Text Submission</h6>
                            <div class="bg-light p-3 rounded border">
                                <p class="mb-0 text-dark">{{ $submission->submission_text }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- No Content Message -->
                    @if(!$submission->hasCodeSubmission() && !$submission->hasFile() && empty($submission->submission_text))
                        <div class="text-center py-4">
                            <iconify-icon icon="solar:document-outline" class="text-4xl text-muted mb-2"></iconify-icon>
                            <p class="text-muted">No submission content available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Feedback Section -->
            @if($submission->feedback)
                <div class="card mb-24">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <iconify-icon icon="solar:chat-round-dots-outline" class="icon me-2"></iconify-icon>
                            Instructor Feedback
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="feedback-content bg-info-50 p-3 rounded border-start border-info border-4">
                            <p class="mb-0 text-dark">{{ $submission->feedback }}</p>
                        </div>
                        @if($submission->graded_at)
                            <small class="text-muted mt-2 d-block">
                                Feedback provided on {{ $submission->graded_at->format('M d, Y h:i A') }}
                            </small>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Submission Status -->
            <div class="card mb-24">
                <div class="card-header">
                    <h5 class="card-title mb-0">Submission Status</h5>
                </div>
                <div class="card-body">
                    <div class="status-info">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status:</label>
                            <div>{!! $submission->status_badge !!}</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Submitted:</label>
                            <div class="text-dark">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'Not submitted' }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Submission Time:</label>
                            <div>
                                @if($submission->isLate())
                                    <span class="text-danger">
                                        <iconify-icon icon="solar:clock-circle-outline" class="icon"></iconify-icon>
                                        Late Submission
                                    </span>
                                @else
                                    <span class="text-success">
                                        <iconify-icon icon="solar:check-circle-outline" class="icon"></iconify-icon>
                                        On Time
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($submission->isGraded())
                            <div class="mb-3">
                                <label class="form-label text-muted">Graded:</label>
                                <div class="text-dark">{{ $submission->graded_at->format('M d, Y h:i A') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Grade Information -->
            @if($submission->isGraded())
                <div class="card mb-24">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Grade Information</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="grade-display mb-3">
                            @php
                                $gradeColor = $submission->grade >= 70 ? '10b981' : ($submission->grade >= 60 ? '3b82f6' : ($submission->grade >= 50 ? '8b5cf6' : ($submission->grade >= 46 ? 'f59e0b' : 'ef4444')));
                                $gradeDarkColor = $submission->grade >= 70 ? '059669' : ($submission->grade >= 60 ? '2563eb' : ($submission->grade >= 50 ? '7c3aed' : ($submission->grade >= 46 ? 'd97706' : 'dc2626')));
                            @endphp
                            <div class="grade-circle mx-auto mb-3" style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #{{ $gradeColor }} 0%, #{{ $gradeDarkColor }} 100%); display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);">
                                <div>
                                    <div class="h3 mb-0">{{ $submission->grade_letter }}</div>
                                    <small>{{ $submission->formatted_grade }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grade-details">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h6 class="mb-1 text-dark">{{ number_format($submission->grade, 1) }}</h6>
                                        <small class="text-muted">Score</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-1 text-dark">{{ $submission->assignment->max_points ?? 100 }}</h6>
                                    <small class="text-muted">Max Points</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card mb-24">
                    <div class="card-body text-center">
                        <iconify-icon icon="solar:clock-circle-outline" class="text-4xl text-warning mb-3"></iconify-icon>
                        <h6 class="mb-2 text-dark">Awaiting Grade</h6>
                        <p class="text-muted mb-0">Your submission is being reviewed by the instructor.</p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.submissions.index') }}" class="btn btn-primary">
                            <iconify-icon icon="solar:arrow-left-outline" class="icon"></iconify-icon>
                            Back to Submissions
                        </a>
                        
                        @if($submission->assignment)
                            <a href="{{ route('student.assignments.show', $submission->assignment) }}" class="btn btn-outline-secondary">
                                <iconify-icon icon="solar:document-text-outline" class="icon"></iconify-icon>
                                View Assignment
                            </a>
                        @endif

                        @if($submission->hasFile())
                            <a href="{{ route('student.submissions.download', $submission) }}" class="btn btn-outline-success">
                                <iconify-icon icon="solar:download-outline" class="icon"></iconify-icon>
                                Download File
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Iconify -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>

<style>
.code-container pre {
    max-height: 400px;
    overflow-y: auto;
    background-color: #f8f9fa !important;
    border: 1px solid #dee2e6;
}

.code-container code {
    color: #212529 !important;
    font-family: 'Courier New', Courier, monospace;
    font-size: 0.875rem;
    line-height: 1.5;
}

.grade-circle {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.status-info .form-label {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.file-info {
    border: 1px solid #e5e7eb;
}

.feedback-content {
    border-left: 4px solid #3b82f6;
}

.info-item {
    padding-bottom: 0.5rem;
}

/* Ensure proper text visibility */
.text-dark {
    color: #212529 !important;
}

.card-title {
    color: #212529 !important;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .grade-circle {
        width: 80px !important;
        height: 80px !important;
    }
    
    .grade-circle .h3 {
        font-size: 1.5rem;
    }
    
    .code-container pre {
        font-size: 0.75rem;
        max-height: 300px;
    }
    
    .card-body {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .grade-circle {
        width: 70px !important;
        height: 70px !important;
    }
    
    .grade-circle .h3 {
        font-size: 1.25rem;
    }
    
    .d-grid gap-2 {
        gap: 0.5rem !important;
    }
}
</style>
</x-student-layout>