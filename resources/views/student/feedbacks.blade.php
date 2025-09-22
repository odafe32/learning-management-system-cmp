<x-student-layout
    :metaTitle="$meta_title ?? 'Course Materials'"
    :metaDesc="$meta_desc ?? 'Access your course materials'"
    :metaImage="$meta_image ?? ''"
>
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">My Feedbacks</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('student.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Feedbacks</li>
        </ul>
    </div>

    <!-- Feedback Stats -->
    <div class="row gy-4 mb-24">
        <div class="col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-64 h-64 radius-12 d-flex justify-content-center align-items-center bg-primary-50">
                            <iconify-icon icon="material-symbols:feedback" class="text-primary-600 text-xxl"></iconify-icon>
                        </div>
                        <div>
                            <h6 class="mb-2 fw-semibold">{{ $stats['total_feedbacks'] }}</h6>
                            <p class="text-sm text-secondary-light fw-medium">Total Feedbacks</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-64 h-64 radius-12 d-flex justify-content-center align-items-center bg-success-50">
                            <iconify-icon icon="material-symbols:grade" class="text-success-600 text-xxl"></iconify-icon>
                        </div>
                        <div>
                            <h6 class="mb-2 fw-semibold">{{ $stats['average_grade'] ? number_format($stats['average_grade'], 1) . '%' : 'N/A' }}</h6>
                            <p class="text-sm text-secondary-light fw-medium">Average Grade</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-64 h-64 radius-12 d-flex justify-content-center align-items-center bg-info-50">
                            <iconify-icon icon="material-symbols:schedule" class="text-info-600 text-xxl"></iconify-icon>
                        </div>
                        <div>
                            <h6 class="mb-2 fw-semibold">{{ $stats['recent_feedbacks'] }}</h6>
                            <p class="text-sm text-secondary-light fw-medium">Recent (7 days)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-24">
        <div class="card-body">
            <form method="GET" action="{{ route('student.feedbacks.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search assignments..." value="{{ $currentFilters['search'] }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="material-symbols:search" class="me-1"></iconify-icon>
                                Filter
                            </button>
                            <a href="{{ route('student.feedbacks.index') }}" class="btn btn-secondary">
                                <iconify-icon icon="material-symbols:clear" class="me-1"></iconify-icon>
                                Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Feedbacks List -->
    <div class="card">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h6 class="text-lg fw-semibold mb-0">Assignment Feedbacks</h6>
        </div>
        <div class="card-body p-24">
            @forelse($feedbacks as $feedback)
                <div class="border border-neutral-200 rounded-12 p-20 mb-20">
                    <div class="row align-items-start">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <div class="w-48 h-48 bg-primary-50 rounded-12 d-flex justify-content-center align-items-center">
                                        <iconify-icon icon="material-symbols:assignment" class="text-primary-600 text-xl"></iconify-icon>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-lg fw-semibold mb-1">{{ $feedback->assignment->title }}</h6>
                                    <p class="text-sm text-secondary-light mb-2">
                                        {{ $feedback->assignment->course->code }} - {{ $feedback->assignment->course->title }}
                                    </p>
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <span class="text-sm">
                                            <iconify-icon icon="material-symbols:person" class="me-1"></iconify-icon>
                                            {{ $feedback->assignment->course->instructor->name }}
                                        </span>
                                        <span class="text-sm">
                                            <iconify-icon icon="material-symbols:schedule" class="me-1"></iconify-icon>
                                            {{ $feedback->formatted_graded_date }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-neutral-600 mb-0">
                                        {{ Str::limit($feedback->feedback, 100) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="text-end">
                                <div class="mb-2">
                                    <span class="badge bg-{{ $feedback->grade_badge_color }}-50 text-{{ $feedback->grade_badge_color }}-600 px-12 py-6 rounded-6 fw-semibold">
                                        {{ $feedback->formatted_grade }} ({{ $feedback->grade_letter }})
                                    </span>
                                </div>
                                <div class="mb-3">
                                    {!! $feedback->status_badge !!}
                                </div>
                                <a href="{{ route('student.feedbacks.show', $feedback->id) }}" class="btn btn-primary btn-sm">
                                    <iconify-icon icon="material-symbols:visibility" class="me-1"></iconify-icon>
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <iconify-icon icon="material-symbols:feedback-outline" class="text-secondary-light text-6xl mb-3"></iconify-icon>
                    <h5 class="text-secondary-light mb-2">No Feedbacks Yet</h5>
                    <p class="text-secondary-light">
                        @if($currentFilters['course'] || $currentFilters['search'])
                            No feedbacks found matching your filters. Try adjusting your search criteria.
                        @else
                            You haven't received any feedback on your assignments yet. Keep submitting your work!
                        @endif
                    </p>
                    @if($currentFilters['course'] || $currentFilters['search'])
                        <a href="{{ route('student.feedbacks.index') }}" class="btn btn-primary mt-3">
                            <iconify-icon icon="material-symbols:clear" class="me-1"></iconify-icon>
                            Clear Filters
                        </a>
                    @endif
                </div>
            @endforelse

            <!-- Pagination -->
            @if($feedbacks->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $feedbacks->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-submit form when course filter changes
    $('select[name="course"]').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>

</x-student-layout>