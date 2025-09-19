<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Assignment Details</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <i class="ph ph-house text-lg"></i>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('student.assignments.index') }}" class="hover-text-primary">
                    Assignments
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">{{ $assignment->title }}</li>
        </ul>
    </div>

    <div class="row gy-4">
        <!-- Assignment Details -->
        <div class="col-lg-8">
            <div class="card h-100 p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <h6 class="text-lg fw-semibold mb-0">{{ $assignment->title }}</h6>
                        @if($submission)
                            @if($submission->status === 'graded')
                                <span class="badge bg-success-50 text-success-600 px-12 py-6 rounded-4">
                                    <i class="ph ph-star me-1"></i>
                                    Graded: {{ $submission->grade ?? 'N/A' }}/{{ $assignment->max_grade ?? 100 }}
                                </span>
                            @else
                                <span class="badge bg-info-50 text-info-600 px-12 py-6 rounded-4">
                                    <i class="ph ph-check-circle me-1"></i>
                                    Submitted
                                </span>
                            @endif
                        @elseif($isOverdue)
                            <span class="badge bg-danger-50 text-danger-600 px-12 py-6 rounded-4">
                                <i class="ph ph-warning-circle me-1"></i>
                                Overdue
                            </span>
                        @else
                            <span class="badge bg-warning-50 text-warning-600 px-12 py-6 rounded-4">
                                <i class="ph ph-clock me-1"></i>
                                Pending
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-24">
                    <!-- Course Information -->
                    <div class="mb-24">
                        <div class="d-flex align-items-center gap-3 mb-16">
                            <div class="w-48-px h-48-px d-inline-flex align-items-center justify-content-center bg-primary-50 text-primary-600 radius-8">
                                <i class="ph ph-book text-xl"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">{{ $assignment->course->title }}</h6>
                                <span class="text-sm text-secondary-light">{{ $assignment->course->code }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Description -->
                    @if($assignment->description)
                        <div class="mb-24">
                            <h6 class="text-md fw-semibold mb-12">Description</h6>
                            <div class="text-secondary-light">
                                {!! nl2br(e($assignment->description)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Code Sample/Template -->
                    @if($assignment->code_sample)
                        <div class="mb-24">
                            <h6 class="text-md fw-semibold mb-12">Code Sample/Template</h6>
                            <div class="position-relative">
                                <pre class="bg-gray-50 p-16 rounded-8 overflow-auto" style="max-height: 400px;"><code>{{ $assignment->code_sample }}</code></pre>
                                <button type="button" class="btn btn-sm btn-secondary position-absolute top-0 end-0 m-2" onclick="copyCodeSample()">
                                    <i class="ph ph-copy me-1"></i>
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Submission Status -->
                    @if($submission)
                        <div class="mb-24">
                            <h6 class="text-md fw-semibold mb-12">Your Submission</h6>
                            <div class="bg-success-50 p-16 rounded-8">
                                <div class="d-flex align-items-center gap-2 mb-8">
                                    <i class="ph ph-check-circle text-success"></i>
                                    <span class="text-success fw-medium">Submitted on {{ $submission->submitted_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                                
                                @if($submission->submission_text)
                                    <div class="mb-12">
                                        <strong class="text-sm">Text Submission:</strong>
                                        <p class="text-sm mt-4 mb-0">{{ Str::limit($submission->submission_text, 200) }}</p>
                                    </div>
                                @endif

                                @if($submission->file_path)
                                    <div class="mb-12">
                                        <strong class="text-sm">File Submission:</strong>
                                        <div class="d-flex align-items-center gap-2 mt-4">
                                            <i class="ph ph-file text-primary"></i>
                                            <span class="text-sm">{{ $submission->file_name }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if($submission->code_submission)
                                    <div class="mb-12">
                                        <strong class="text-sm">Code Submission:</strong>
                                        <pre class="bg-white p-12 rounded-4 mt-4 text-sm" style="max-height: 200px; overflow-y: auto;"><code>{{ Str::limit($submission->code_submission, 500) }}</code></pre>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Grade and Feedback -->
                        @if($submission->status === 'graded')
                            <div class="mb-24">
                                <h6 class="text-md fw-semibold mb-12">Grade & Feedback</h6>
                                <div class="bg-info-50 p-16 rounded-8">
                                    <div class="d-flex align-items-center justify-content-between mb-12">
                                        <span class="text-info fw-medium">Grade:</span>
                                        <span class="text-xl fw-bold text-info">{{ $submission->grade ?? 'N/A' }}/{{ $assignment->max_grade ?? 100 }}</span>
                                    </div>
                                    
                                    @if($submission->feedback)
                                        <div class="border-top border-info-200 pt-12">
                                            <strong class="text-sm text-info">Instructor Feedback:</strong>
                                            <p class="text-sm mt-4 mb-0">{!! nl2br(e($submission->feedback)) !!}</p>
                                        </div>
                                    @endif

                                    @if($submission->graded_at)
                                        <div class="text-xs text-info mt-8">
                                            Graded on {{ $submission->graded_at->format('M d, Y \a\t g:i A') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3">
                        @if($canSubmit)
                            <a href="{{ route('student.assignments.submit', $assignment) }}" class="btn btn-primary radius-8 px-20 py-11">
                                <i class="ph ph-upload me-2"></i>
                                Submit Assignment
                            </a>
                        @endif
                        
                        <a href="{{ route('student.assignments.index') }}" class="btn btn-gray radius-8 px-20 py-11">
                            <i class="ph ph-arrow-left me-2"></i>
                            Back to Assignments
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment Info Sidebar -->
        <div class="col-lg-4">
            <div class="card h-100 p-0 radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">Assignment Info</h6>
                </div>
                <div class="card-body p-24">
                    <!-- Deadline Information -->
                    <div class="mb-20">
                        <h6 class="text-md fw-semibold mb-12">Deadline</h6>
                        @if($assignment->deadline)
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <i class="ph ph-calendar text-primary"></i>
                                <span class="text-sm fw-medium {{ $isOverdue ? 'text-danger' : '' }}">
                                    {{ $assignment->deadline->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <i class="ph ph-clock text-primary"></i>
                                <span class="text-sm fw-medium {{ $isOverdue ? 'text-danger' : '' }}">
                                    {{ $assignment->deadline->format('g:i A') }}
                                </span>
                            </div>
                            
                            @if(!$submission)
                                @if($isOverdue)
                                    <div class="bg-danger-50 p-12 rounded-8">
                                        <div class="d-flex align-items-center">
                                            <i class="ph ph-warning-circle text-danger me-2"></i>
                                            <span class="text-sm text-danger fw-semibold">
                                                Overdue by {{ $assignment->deadline->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $hoursRemaining = $assignment->deadline->diffInHours();
                                    @endphp
                                    <div class="bg-{{ $hoursRemaining <= 24 ? 'warning' : 'info' }}-50 p-12 rounded-8">
                                        <div class="d-flex align-items-center">
                                            <i class="ph ph-timer text-{{ $hoursRemaining <= 24 ? 'warning' : 'info' }} me-2"></i>
                                            <span class="text-sm text-{{ $hoursRemaining <= 24 ? 'warning' : 'info' }} fw-semibold">
                                                Due {{ $assignment->deadline->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <i class="ph ph-calendar text-primary"></i>
                                <span class="text-sm text-secondary-light">No deadline set</span>
                            </div>
                        @endif
                    </div>

                    <!-- Assignment Details -->
                    <div class="mb-20">
                        <h6 class="text-md fw-semibold mb-12">Details</h6>
                        
                        <div class="mb-12">
                            <label class="text-sm fw-medium text-secondary-light">Course</label>
                            <div class="text-sm fw-semibold">{{ $assignment->course->code }} - {{ $assignment->course->title }}</div>
                        </div>

                        <div class="mb-12">
                            <label class="text-sm fw-medium text-secondary-light">Instructor</label>
                            <div class="text-sm fw-semibold">{{ $assignment->course->instructor->name }}</div>
                        </div>

                        <div class="mb-12">
                            <label class="text-sm fw-medium text-secondary-light">Created</label>
                            <div class="text-sm">{{ $assignment->created_at->format('M d, Y \a\t g:i A') }}</div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <h6 class="text-md fw-semibold mb-12">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            @if($canSubmit)
                                <a href="{{ route('student.assignments.submit', $assignment) }}" class="btn btn-primary btn-sm">
                                    <i class="ph ph-upload me-2"></i>
                                    Submit Assignment
                                </a>
                            @endif
                            
                            <a href="{{ route('student.courses.show', $assignment->course) }}" class="btn btn-primary btn-sm">
                                <i class="ph ph-book me-2"></i>
                                View Course
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Copy code sample function
function copyCodeSample() {
    const codeElement = document.querySelector('pre code');
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
</script>

</x-student-layout>