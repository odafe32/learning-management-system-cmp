<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<style>
    .feedback-content {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin: 1rem 0;
    }
    
    .grade-display {
        font-size: 2rem;
        font-weight: bold;
    }
    
    .file-preview {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        margin: 0.5rem 0;
        background-color: #f9fafb;
    }
    
    .code-preview {
        background-color: #1e293b;
        color: #e2e8f0;
        padding: 1rem;
        border-radius: 0.5rem;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        line-height: 1.5;
        overflow-x: auto;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 1rem;
        padding: 1.5rem;
    }
    
    .comparison-chart {
        height: 200px;
    }
</style>
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Feedback Details</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('student.feedbacks.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    Feedbacks
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Details</li>
        </ul>
    </div>

    <div class="row gy-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Assignment Info -->
            <div class="card mb-24">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="text-lg fw-semibold mb-0">{{ $submission->assignment->title }}</h6>
                        {!! $submission->status_badge !!}
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <iconify-icon icon="material-symbols:book" class="text-primary-600"></iconify-icon>
                                <span class="fw-medium">Course:</span>
                                <span>{{ $submission->assignment->course->code }} - {{ $submission->assignment->course->title }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <iconify-icon icon="material-symbols:person" class="text-primary-600"></iconify-icon>
                                <span class="fw-medium">Instructor:</span>
                                <span>{{ $submission->assignment->course->instructor->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <iconify-icon icon="material-symbols:schedule" class="text-primary-600"></iconify-icon>
                                <span class="fw-medium">Submitted:</span>
                                <span>{{ $submission->formatted_submitted_date }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <iconify-icon icon="material-symbols:grade" class="text-primary-600"></iconify-icon>
                                <span class="fw-medium">Graded:</span>
                                <span>{{ $submission->formatted_graded_date }}</span>
                            </div>
                        </div>
                    </div>

                    @if($submission->assignment->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">Assignment Description</h6>
                            <p class="text-secondary-light">{{ $submission->assignment->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Grade and Feedback -->
            <div class="card mb-24">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">Grade & Feedback</h6>
                </div>
                <div class="card-body p-24">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-6">
                            <div class="text-center">
                                <div class="grade-display text-{{ $submission->grade_color }}">
                                    {{ $submission->formatted_grade }}
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-{{ $submission->grade_badge_color }}-50 text-{{ $submission->grade_badge_color }}-600 px-16 py-8 rounded-6 fw-semibold text-lg">
                                        Grade {{ $submission->grade_letter }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <iconify-icon icon="material-symbols:timer" class="text-secondary-light"></iconify-icon>
                                <span class="fw-medium">Status:</span>
                                <span class="{{ $submission->isLate() ? 'text-danger' : 'text-success' }}">
                                    {{ $submission->submission_time_status }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <iconify-icon icon="material-symbols:trending-up" class="text-secondary-light"></iconify-icon>
                                <span class="fw-medium">Performance:</span>
                                <span class="text-{{ $submission->grade >= ($assignmentStats['average_grade'] ?? 0) ? 'success' : 'warning' }}">
                                    {{ $submission->grade >= ($assignmentStats['average_grade'] ?? 0) ? 'Above Average' : 'Below Average' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="feedback-content">
                        <h6 class="fw-semibold mb-3">
                            <iconify-icon icon="material-symbols:feedback" class="me-2"></iconify-icon>
                            Instructor Feedback
                        </h6>
                        <p class="mb-0">{{ $submission->feedback }}</p>
                    </div>
                </div>
            </div>

            <!-- Submission Content -->
            <div class="card mb-24">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">Your Submission</h6>
                </div>
                <div class="card-body p-24">
                    @if($submission->hasCode())
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">Code Submission</h6>
                            <div class="code-preview">
                                <pre><code>{{ $submission->code_content }}</code></pre>
                            </div>
                        </div>
                    @endif

                    @if($submission->hasFiles())
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">File Submissions</h6>
                            @foreach($submission->getFiles() as $file)
                                <div class="file-preview">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <iconify-icon icon="material-symbols:{{ $file['icon'] }}" class="text-primary-600 text-xl"></iconify-icon>
                                            <div>
                                                <div class="fw-medium">{{ $file['name'] }}</div>
                                                <small class="text-secondary-light">{{ $file['type'] }} • {{ $file['size_formatted'] }}</small>
                                            </div>
                                        </div>
                                        <div>
                                            @if($file['exists'])
                                                <a href="{{ $file['download_url'] }}" class="btn btn-primary btn-sm">
                                                    <iconify-icon icon="material-symbols:download" class="me-1"></iconify-icon>
                                                    Download
                                                </a>
                                            @else
                                                <span class="text-danger">File not found</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($submission->hasText())
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">Text Submission</h6>
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-0">{{ $submission->submission_text }}</p>
                            </div>
                        </div>
                    @endif

                    @if(!$submission->hasContent())
                        <div class="text-center py-4">
                            <iconify-icon icon="material-symbols:description" class="text-secondary-light text-4xl mb-2"></iconify-icon>
                            <p class="text-secondary-light mb-0">No submission content available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Assignment Statistics -->
            <div class="card mb-24">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">Assignment Statistics</h6>
                </div>
                <div class="card-body p-24">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-2xl fw-bold text-primary">{{ $assignmentStats['total_submissions'] }}</div>
                                <small class="text-secondary-light">Total Submissions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-2xl fw-bold text-info">{{ $assignmentStats['average_grade'] ? number_format($assignmentStats['average_grade'], 1) . '%' : 'N/A' }}</div>
                                <small class="text-secondary-light">Class Average</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-2xl fw-bold text-success">{{ $assignmentStats['highest_grade'] ? number_format($assignmentStats['highest_grade'], 1) . '%' : 'N/A' }}</div>
                                <small class="text-secondary-light">Highest Grade</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-2xl fw-bold text-warning">{{ $assignmentStats['lowest_grade'] ? number_format($assignmentStats['lowest_grade'], 1) . '%' : 'N/A' }}</div>
                                <small class="text-secondary-light">Lowest Grade</small>
                            </div>
                        </div>
                    </div>

                    @if($assignmentStats['average_grade'])
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-sm">Your Performance</span>
                                <span class="text-sm fw-medium">{{ number_format($submission->grade, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $submission->grade_badge_color }}" 
                                     style="width: {{ min(($submission->grade / 100) * 100, 100) }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-secondary-light">0%</small>
                                <small class="text-secondary-light">Class Avg: {{ number_format($assignmentStats['average_grade'], 1) }}%</small>
                                <small class="text-secondary-light">100%</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Submissions -->
            @if($relatedSubmissions->count() > 0)
                <div class="card mb-24">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">Your Other Submissions</h6>
                    </div>
                    <div class="card-body p-24">
                        @foreach($relatedSubmissions as $related)
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                                <div>
                                    <div class="fw-medium">{{ $related->assignment->title }}</div>
                                    <small class="text-secondary-light">{{ $related->time_ago }}</small>
                                </div>
                                <div class="text-end">
                                    @if($related->isGraded())
                                        <div class="text-{{ $related->grade_color }} fw-medium">{{ $related->formatted_grade }}</div>
                                        <small class="text-secondary-light">{{ $related->grade_letter }}</small>
                                    @else
                                        {!! $related->status_badge !!}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="card">
                <div class="card-body p-24">
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.feedbacks.index') }}" class="btn btn-primary">
                            <iconify-icon icon="material-symbols:arrow-back" class="me-1"></iconify-icon>
                            Back to Feedbacks
                        </a>
                        <a href="{{ route('student.assignments.show', $submission->assignment->id) }}" class="btn btn-secondary">
                            <iconify-icon icon="material-symbols:assignment" class="me-1"></iconify-icon>
                            View Assignment
                        </a>
                        @if($submission->hasFiles())
                            <a href="{{ route('student.submissions.download', $submission->id) }}" class="btn btn-info">
                                <iconify-icon icon="material-symbols:download" class="me-1"></iconify-icon>
                                Download Files
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Add syntax highlighting if available
    if (typeof hljs !== 'undefined') {
        hljs.highlightAll();
    }
});
</script>

</x-student-layout>