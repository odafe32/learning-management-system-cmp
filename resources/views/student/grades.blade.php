<x-student-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
<div class="dashboard-main-body">
    <!-- Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">My Grades</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Grades</li>
        </ul>
    </div>

    <!-- Grade Statistics -->
    <div class="row mb-24">
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-2">{{ $stats['total_graded'] }}</h4>
                            <span class="text-gray-600">Graded Assignments</span>
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
                            <h4 class="mb-2">{{ $stats['overall_average'] ? number_format($stats['overall_average'], 1) . '%' : 'N/A' }}</h4>
                            <span class="text-gray-600">Overall Average</span>
                        </div>
                        <div class="w-44 h-44 bg-primary-50 text-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:star-outline" class="text-2xl"></iconify-icon>
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
                            <h4 class="mb-2">{{ $stats['highest_grade'] ? number_format($stats['highest_grade'], 1) . '%' : 'N/A' }}</h4>
                            <span class="text-gray-600">Highest Grade</span>
                        </div>
                        <div class="w-44 h-44 bg-warning-50 text-warning-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:crown-outline" class="text-2xl"></iconify-icon>
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
                            <h4 class="mb-2">{{ $stats['pending_grades'] }}</h4>
                            <span class="text-gray-600">Pending Grades</span>
                        </div>
                        <div class="w-44 h-44 bg-info-50 text-info-600 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:clock-circle-outline" class="text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Distribution Chart -->
    <div class="row mb-24">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Grade Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="grade-distribution">
                        @if(isset($gradeDistribution) && count($gradeDistribution) > 0)
                            @foreach($gradeDistribution as $letter => $data)
                                <div class="grade-bar mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-medium">Grade {{ $letter }}</span>
                                        <span class="text-muted">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $data['color'] }}" 
                                             style="width: {{ $data['percentage'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-3">
                                <iconify-icon icon="solar:chart-outline" class="text-4xl mb-2"></iconify-icon>
                                <p>No grade distribution data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Grade Scale</h5>
                </div>
                <div class="card-body">
                    <div class="grade-scale">
                        @foreach(\App\Models\Submission::getGradingScale() as $letter => $scale)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-{{ $scale['color'] }}-50 text-{{ $scale['color'] }}-600 px-8 py-4 rounded-4">
                                    {{ $letter }}
                                </span>
                                <span class="text-muted">{{ $scale['min'] }}% - {{ $scale['max'] }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-24">
        <div class="card-header">
            <h5 class="card-title mb-0">Filter Grades</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('student.grades.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Course</label>
                    <select name="course" class="form-select">
                        <option value="">All Courses</option>
                        @foreach($enrolledCourses as $course)
                            <option value="{{ $course->id }}" {{ $currentFilters['course'] == $course->id ? 'selected' : '' }}>
                                {{ $course->code }} - {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search assignments..." value="{{ $currentFilters['search'] }}">
                        <button type="submit" class="btn btn-primary">
                            <iconify-icon icon="solar:magnifer-outline"></iconify-icon>
                        </button>
                        <a href="{{ route('student.grades.index') }}" class="btn btn-secondary">
                            <iconify-icon icon="solar:refresh-outline"></iconify-icon>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Grades by Course -->
    @if(isset($gradesByCourse) && $gradesByCourse->count() > 0)
        @foreach($gradesByCourse as $courseId => $courseGrades)
            @php
                $course = $courseGrades->first()->assignment->course;
                $courseAverage = $courseGrades->avg('grade');
            @endphp
            <div class="card mb-24">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title mb-1">{{ $course->code }} - {{ $course->title }}</h5>
                            <small class="text-muted">{{ $course->instructor->name ?? 'N/A' }}</small>
                        </div>
                        <div class="text-end">
                            <div class="h5 mb-0 {{ $courseAverage >= 70 ? 'text-success' : ($courseAverage >= 60 ? 'text-info' : ($courseAverage >= 50 ? 'text-primary' : 'text-warning')) }}">
                                {{ number_format($courseAverage, 1) }}%
                            </div>
                            <small class="text-muted">Course Average</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-dark">Assignment</th>
                                    <th class="text-dark">Submitted</th>
                                    <th class="text-dark">Graded</th>
                                    <th class="text-dark">Grade</th>
                                    <th class="text-dark">Letter</th>
                                    <th class="text-dark">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courseGrades as $submission)
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 text-dark">{{ $submission->assignment->title }}</h6>
                                                <small class="text-muted">
                                                    Max: {{ $submission->assignment->max_points ?? 100 }} points
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="text-dark">{{ $submission->submitted_at->format('M d, Y') }}</span>
                                                @if($submission->isLate())
                                                    <small class="text-danger d-block">Late</small>
                                                @else
                                                    <small class="text-success d-block">On Time</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-dark">{{ $submission->graded_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold {{ $submission->grade_color }}">
                                                    {{ number_format($submission->grade, 1) }}%
                                                </span>
                                                <div class="progress" style="width: 60px; height: 6px;">
                                                    <div class="progress-bar bg-{{ $submission->grade_badge_color }}" 
                                                         style="width: {{ min($submission->grade, 100) }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $submission->grade_badge_color }}-50 text-{{ $submission->grade_badge_color }}-600 px-8 py-4 rounded-4">
                                                {{ $submission->grade_letter }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('student.submissions.show', $submission) }}" 
                                                   class="btn btn-primary btn-sm" 
                                                   title="View Submission">
                                                    <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                                </a>
                                                <a href="{{ route('student.assignments.show', $submission->assignment) }}" 
                                                   class="btn btn-secondary btn-sm" 
                                                   title="View Assignment">
                                                    <iconify-icon icon="solar:document-text-outline"></iconify-icon>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <iconify-icon icon="solar:star-outline" class="text-6xl text-muted"></iconify-icon>
                </div>
                <h5 class="mb-2">No Grades Available</h5>
                <p class="text-muted mb-4">You don't have any graded assignments yet.</p>
                <a href="{{ route('student.assignments.index') }}" class="btn btn-primary">
                    <iconify-icon icon="solar:document-add-outline" class="icon"></iconify-icon>
                    View Assignments
                </a>
            </div>
        </div>
    @endif

    <!-- Export Options -->
    @if(isset($gradesByCourse) && $gradesByCourse->count() > 0)
        <div class="card mt-24">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1">Export Grades</h6>
                        <small class="text-muted">Download your grade report in PDF or Excel format</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="exportGrades('pdf')">
                            <iconify-icon icon="solar:file-text-outline" class="icon"></iconify-icon>
                            Export PDF
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="exportGrades('excel')">
                            <iconify-icon icon="solar:document-outline" class="icon"></iconify-icon>
                            Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Loading Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="mb-2">Generating Report</h6>
                <p class="text-muted mb-0">Please wait while we prepare your grade report...</p>
            </div>
        </div>
    </div>
</div>

<!-- Include Iconify -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>

<script>
function exportGrades(format) {
    // Show loading modal
    const modal = new bootstrap.Modal(document.getElementById('exportModal'));
    modal.show();
    
    // Create a temporary form to submit the export request
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '{{ route("student.grades.export") }}';
    form.style.display = 'none';
    
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = format;
    form.appendChild(formatInput);
    
    document.body.appendChild(form);
    
    // Submit form
    form.submit();
    
    // Hide modal after a delay
    setTimeout(() => {
        modal.hide();
        document.body.removeChild(form);
    }, 3000);
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
.grade-distribution .progress {
    border-radius: 4px;
    background-color: #f1f5f9;
}

.grade-scale {
    max-height: 300px;
    overflow-y: auto;
}

.progress-bar {
    transition: width 0.3s ease;
}

.grade-bar:hover .progress-bar {
    opacity: 0.8;
}

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

/* Export button hover effects */
.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-outline-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: white;
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
}
</style>
</x-student-layout>