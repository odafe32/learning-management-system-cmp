<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Manage Courses</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('instructor.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ph ph-house text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li class="fw-medium">
                    <span class="text-gray-300">/</span>
                </li>
                <li class="fw-medium text-primary-600">Courses</li>
            </ul>
        </div>

    

        <div class="row gy-4">
            <!-- Filters and Search -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-gray-100">
                        <h6 class="mb-0">Search & Filter Courses</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('instructor.courses.manage') }}" class="row gy-3">
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control radius-8" 
                                       placeholder="Search by title, code, or description..." 
                                       value="{{ $filters['search'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select radius-8">
                                    <option value="">All Status</option>
                                    @foreach($statuses as $key => $value)
                                        <option value="{{ $key }}" {{ ($filters['status'] ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Level</label>
                                <select name="level" class="form-select radius-8">
                                    <option value="">All Levels</option>
                                    @foreach($levels as $key => $value)
                                        <option value="{{ $key }}" {{ ($filters['level'] ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Semester</label>
                                <select name="semester" class="form-select radius-8">
                                    <option value="">All Semesters</option>
                                    @foreach($semesters as $key => $value)
                                        <option value="{{ $key }}" {{ ($filters['semester'] ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary radius-8 flex-grow-1">
                                        <i class="ph ph-magnifying-glass"></i>
                                    </button>
                                    <a href="{{ route('instructor.courses.manage') }}" class="btn btn-outline-gray-400 radius-8">
                                        <i class="ph ph-x"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Courses List -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-gray-100 flex-between flex-wrap gap-8">
                        <h6 class="mb-0">Your Courses ({{ $courses->total() }})</h6>
                        <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary-600 radius-8 px-20 py-11">
                            <i class="ph ph-plus me-8"></i>
                            Create New Course
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if($courses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="h6 text-gray-300">#</th>
                                            <th class="h6 text-gray-300">Course</th>
                                            <th class="h6 text-gray-300">Code</th>
                                            <th class="h6 text-gray-300">Level</th>
                                            <th class="h6 text-gray-300">Semester</th>
                                            <th class="h6 text-gray-300">Units</th>
                                            <th class="h6 text-gray-300">Status</th>
                                            <th class="h6 text-gray-300">Created</th>
                                            <th class="h6 text-gray-300">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($courses as $index => $course)
                                            <tr>
                                                <td>
                                                    <span class="h6 mb-0">{{ $courses->firstItem() + $index }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-10">
                                                        <img src="{{ $course->image_url }}" 
                                                             alt="Course Image" 
                                                             class="w-40 h-40 rounded-8 object-fit-cover">
                                                        <div>
                                                            <h6 class="text-md mb-0">{{ $course->title }}</h6>
                                                            @if($course->description)
                                                                <p class="mb-0 text-gray-600 text-sm">{{ Str::limit($course->description, 50) }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded-4 fw-medium">{{ $course->code }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-600">{{ $course->level ? $levels[$course->level] : 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-600">{{ $course->semester ? $semesters[$course->semester] : 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-600">{{ $course->credit_units }} Unit{{ $course->credit_units > 1 ? 's' : '' }}</span>
                                                </td>
                                                <td>
                                                    {!! $course->status_badge !!}
                                                </td>
                                                <td>
                                                    <span class="text-gray-600 text-sm">{{ $course->created_at->format('M d, Y') }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-8">
                                                        <a href="{{ route('instructor.courses.edit', $course) }}" 
                                                           class="w-32 h-32 bg-success-focus text-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                           title="Edit Course">
                                                            <i class="ph ph-pencil-simple"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="w-32 h-32 btn-danger-focus text-danger rounded-circle d-inline-flex align-items-center justify-content-center border-0"
                                                                title="Delete Course" 
                                                                onclick="deleteCourse({{ $course->id }}, '{{ $course->title }}')">
                                                            <i class="ph ph-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($courses->hasPages())
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-16 px-20 border-top border-gray-100">
                                    <span class="text-gray-600">
                                        Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} results
                                    </span>
                                    {{ $courses->links() }}
                                </div>
                            @endif
                        @else
                            <div class="py-120 text-center flex justify-center items-center flex-col">
                                <img src="{{ url('assets/images/thumbs/empty.jpg') }}" width="200px" alt="No courses" class="mb-24">
                                <h6 class="mb-16">No courses found</h6>
                                <p class="text-gray-600 mb-24">
                                    @if(request()->hasAny(['search', 'status', 'level', 'semester']))
                                        No courses match your current filters. Try adjusting your search criteria.
                                    @else
                                        You haven't created any courses yet. Create your first course to get started.
                                    @endif
                                </p>
                                <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary-600 radius-8 px-20 py-11">
                                    <i class="ph ph-plus me-8"></i>
                                    Create Your First Course
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the course "<span id="courseTitle"></span>"?</p>
                    <p class="text-danger text-sm mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function deleteCourse(courseId, courseTitle) {
        document.getElementById('courseTitle').textContent = courseTitle;
        document.getElementById('deleteForm').action = `/instructor/courses/${courseId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    // Auto-submit form on filter change (optional)
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelects = document.querySelectorAll('select[name="status"], select[name="level"], select[name="semester"]');
        
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                // Uncomment the line below if you want auto-submit on filter change
                // this.form.submit();
            });
        });
    });
    </script>
</x-instructor-layout>