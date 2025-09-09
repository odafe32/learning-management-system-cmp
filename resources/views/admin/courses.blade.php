<x-admin-layout :metaTitle="$metaTitle" :metaDesc="$metaDesc" :metaImage="$metaImage">
    <div class="dashboard-body__content">
        <div class="row gy-4">
            <div class="col-lg-12">
                <!-- Page Header -->
                <div class="card border-0 bg-transparent">
                    <div class="card-body p-0">
                        <div class="flex-between flex-wrap gap-16 mb-24">
                            <div>
                                <h4 class="mb-8 text-xl fw-semibold">Manage Courses</h4>
                                <p class="text-gray-600 text-15">View, manage, and delete courses from all instructors.</p>
                            </div>
                            <div class="flex-align gap-8">
                                <a href="{{ route('admin.courses.export', request()->query()) }}" class="btn btn-success radius-8 px-20 py-11">
                                    <i class="ph ph-download me-8"></i>
                                    Export Courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Form -->
                <div class="card border-0 mb-24">
                    <div class="card-header bg-gray-50 border-bottom border-gray-100 py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0 text-gray-900">
                            <i class="ph ph-funnel me-8"></i>
                            Search & Filter Courses
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <form method="GET" action="{{ route('admin.courses.index') }}" id="filterForm">
                            <div class="row gy-16">
                                <!-- Search Input -->
                                <div class="col-md-4">
                                    <label for="search" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Search Courses
                                    </label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               class="form-control radius-8 ps-40" 
                                               id="search" 
                                               name="search" 
                                               value="{{ $currentFilters['search'] }}" 
                                               placeholder="Search by title, code, instructor...">
                                        <i class="ph ph-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-12 text-gray-400"></i>
                                    </div>
                                </div>

                                <!-- Status Filter -->
                                <div class="col-md-2">
                                    <label for="status" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Status
                                    </label>
                                    <select class="form-control radius-8" id="status" name="status">
                                        <option value="">All Statuses</option>
                                        @foreach($filterOptions['statuses'] as $statusValue => $statusLabel)
                                            <option value="{{ $statusValue }}" {{ $currentFilters['status'] == $statusValue ? 'selected' : '' }}>
                                                {{ $statusLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Level Filter -->
                                <div class="col-md-2">
                                    <label for="level" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Level
                                    </label>
                                    <select class="form-control radius-8" id="level" name="level">
                                        <option value="">All Levels</option>
                                        @foreach($filterOptions['levels'] as $levelValue => $levelLabel)
                                            <option value="{{ $levelValue }}" {{ $currentFilters['level'] == $levelValue ? 'selected' : '' }}>
                                                {{ $levelLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Semester Filter -->
                                <div class="col-md-2">
                                    <label for="semester" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Semester
                                    </label>
                                    <select class="form-control radius-8" id="semester" name="semester">
                                        <option value="">All Semesters</option>
                                        @foreach($filterOptions['semesters'] as $semesterValue => $semesterLabel)
                                            <option value="{{ $semesterValue }}" {{ $currentFilters['semester'] == $semesterValue ? 'selected' : '' }}>
                                                {{ $semesterLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Instructor Filter -->
                                <div class="col-md-2">
                                    <label for="instructor" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Instructor
                                    </label>
                                    <select class="form-control radius-8" id="instructor" name="instructor">
                                        <option value="">All Instructors</option>
                                        @foreach($filterOptions['instructors'] as $instructor)
                                            <option value="{{ $instructor->id }}" {{ $currentFilters['instructor'] == $instructor->id ? 'selected' : '' }}>
                                                {{ $instructor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Department Filter -->
                                <div class="col-md-2">
                                    <label for="department" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Department
                                    </label>
                                    <select class="form-control radius-8" id="department" name="department">
                                        <option value="">All Departments</option>
                                        @foreach($filterOptions['departments'] as $dept)
                                            <option value="{{ $dept }}" {{ $currentFilters['department'] == $dept ? 'selected' : '' }}>
                                                {{ $dept }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Faculty Filter -->
                                <div class="col-md-2">
                                    <label for="faculty" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Faculty
                                    </label>
                                    <select class="form-control radius-8" id="faculty" name="faculty">
                                        <option value="">All Faculties</option>
                                        @foreach($filterOptions['faculties'] as $fac)
                                            <option value="{{ $fac }}" {{ $currentFilters['faculty'] == $fac ? 'selected' : '' }}>
                                                {{ $fac }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sort By -->
                                <div class="col-md-2">
                                    <label for="sort_by" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Sort By
                                    </label>
                                    <select class="form-control radius-8" id="sort_by" name="sort_by">
                                        @foreach($allowedSortFields as $field)
                                            <option value="{{ $field }}" {{ $currentFilters['sort_by'] == $field ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $field)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sort Order -->
                                <div class="col-md-2">
                                    <label for="sort_order" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Order
                                    </label>
                                    <select class="form-control radius-8" id="sort_order" name="sort_order">
                                        <option value="asc" {{ $currentFilters['sort_order'] == 'asc' ? 'selected' : '' }}>Ascending</option>
                                        <option value="desc" {{ $currentFilters['sort_order'] == 'desc' ? 'selected' : '' }}>Descending</option>
                                    </select>
                                </div>

                                <!-- Per Page -->
                                <div class="col-md-2">
                                    <label for="per_page" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Per Page
                                    </label>
                                    <select class="form-control radius-8" id="per_page" name="per_page">
                                        @foreach($allowedPerPage as $count)
                                            <option value="{{ $count }}" {{ $currentFilters['per_page'] == $count ? 'selected' : '' }}>
                                                {{ $count }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Actions -->
                                <div class="col-12">
                                    <div class="flex-align gap-12">
                                        <button type="submit" class="btn btn-main-600 radius-8 px-20 py-11">
                                            <i class="ph ph-funnel me-8"></i>
                                            Apply Filters
                                        </button>
                                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-gray-600 radius-8 px-20 py-11">
                                            <i class="ph ph-x me-8"></i>
                                            Clear Filters
                                        </a>
                                        <div class="ms-auto">
                                            <small class="text-gray-500">
                                                @if(array_filter($currentFilters))
                                                    <i class="ph ph-info me-4"></i>
                                                    Filters applied - showing filtered results
                                                @else
                                                    <i class="ph ph-info me-4"></i>
                                                    No filters applied - showing all courses
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Course Statistics -->
                <div class="row gy-4 mb-24">
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 h-100">
                            <div class="card-body p-20">
                                <div class="flex-between gap-8 mb-16">
                                    <div class="flex-align gap-16">
                                        <div class="w-44 h-44 bg-main-50 text-main-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-books"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">
                                                {{ array_filter($currentFilters) ? 'Filtered' : 'Total' }} Courses
                                            </span>
                                            <h4 class="mb-0 text-main-600">{{ number_format($courseStats['total']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 h-100">
                            <div class="card-body p-20">
                                <div class="flex-between gap-8 mb-16">
                                    <div class="flex-align gap-16">
                                        <div class="w-44 h-44 bg-success-50 text-success-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-check-circle"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">Active Courses</span>
                                            <h4 class="mb-0 text-success-600">{{ number_format($courseStats['active']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 h-100">
                            <div class="card-body p-20">
                                <div class="flex-between gap-8 mb-16">
                                    <div class="flex-align gap-16">
                                        <div class="w-44 h-44 bg-warning-50 text-warning-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-file-dashed"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">Draft Courses</span>
                                            <h4 class="mb-0 text-warning-600">{{ number_format($courseStats['draft']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 h-100">
                            <div class="card-body p-20">
                                <div class="flex-between gap-8 mb-16">
                                    <div class="flex-align gap-16">
                                        <div class="w-44 h-44 bg-danger-50 text-danger-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-x-circle"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">Inactive Courses</span>
                                            <h4 class="mb-0 text-danger-600">{{ number_format($courseStats['inactive']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Courses Table -->
                <div class="card border-0 overflow-hidden">
                    <div class="card-header bg-main-50 border-bottom border-gray-100 py-16 px-24">
                        <div class="flex-between flex-wrap gap-16">
                            <h6 class="text-lg fw-semibold mb-0 text-main-600">
                                <i class="ph ph-list me-8"></i>
                                Courses List
                                @if(array_filter($currentFilters))
                                    <span class="badge bg-primary-50 text-primary-600 text-xs px-8 py-4 rounded-4 ms-8">
                                        Filtered
                                    </span>
                                @endif
                            </h6>
                            <div class="flex-align gap-16">
                                <small class="text-gray-600">
                                    Showing {{ $courses->firstItem() ?? 0 }} to {{ $courses->lastItem() ?? 0 }} of {{ $courses->total() }} courses
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="coursesTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="ps-24 py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'title', 'sort_order' => $currentFilters['sort_by'] == 'title' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Course
                                                @if($currentFilters['sort_by'] == 'title')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-16 text-gray-900 fw-semibold">Instructor</th>
                                        <th class="py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'level', 'sort_order' => $currentFilters['sort_by'] == 'level' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Level/Semester
                                                @if($currentFilters['sort_by'] == 'level')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => $currentFilters['sort_by'] == 'status' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Status
                                                @if($currentFilters['sort_by'] == 'status')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-16 text-gray-900 fw-semibold">Statistics</th>
                                        <th class="py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => $currentFilters['sort_by'] == 'created_at' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Created
                                                @if($currentFilters['sort_by'] == 'created_at')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="pe-24 py-16 text-gray-900 fw-semibold text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($courses as $course)
                                        <tr>
                                            <td class="ps-24 py-16">
                                                <div class="flex-align gap-12">
                                                    <div class="w-50 h-50 rounded-8 overflow-hidden flex-shrink-0">
                                                        <img src="{{ $course->image_url }}" 
                                                             alt="{{ $course->title }}" 
                                                             class="w-100 h-100 object-fit-cover">
                                                    </div>
                                                    <div>
                                                        <h6 class="text-sm fw-semibold mb-4">{{ $course->title }}</h6>
                                                        <span class="text-xs text-gray-500">{{ $course->code }}</span>
                                                        @if($course->credit_units)
                                                            <br><small class="text-gray-400">{{ $course->credit_units }} Credit Units</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                <div>
                                                    <span class="text-sm fw-medium text-gray-900">{{ $course->instructor->name }}</span>
                                                    @if($course->instructor->department)
                                                        <br><small class="text-gray-500">{{ $course->instructor->department }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                <div>
                                                    <span class="text-sm fw-medium text-gray-900">{{ $course->level_display }}</span>
                                                    <br><small class="text-gray-500">{{ $course->semester_display }}</small>
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                {!! $course->status_badge !!}
                                            </td>
                                            <td class="py-16">
                                                <div class="d-flex flex-column gap-4">
                                                    <small class="text-gray-600">
                                                        <i class="ph ph-users me-4"></i>{{ $course->students_count }} Students
                                                    </small>
                                                    <small class="text-gray-600">
                                                        <i class="ph ph-file-text me-4"></i>{{ $course->assignments_count }} Assignments
                                                    </small>
                                                    <small class="text-gray-600">
                                                        <i class="ph ph-folder me-4"></i>{{ $course->materials_count }} Materials
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                <span class="text-sm text-gray-600">{{ $course->created_at->format('M d, Y') }}</span>
                                                <br><small class="text-gray-400">{{ $course->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="pe-24 py-16 text-center">
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger radius-4 px-12 py-6 delete-course-btn"
                                                        data-course-id="{{ $course->id }}"
                                                        data-course-title="{{ $course->title }}"
                                                        data-course-code="{{ $course->code }}"
                                                        data-instructor-name="{{ $course->instructor->name }}"
                                                        data-students-count="{{ $course->students_count }}"
                                                        title="Delete Course">
                                                    <i class="ph ph-trash text-sm"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-32">
                                                <div class="text-gray-400">
                                                    <i class="ph ph-books text-4xl mb-16"></i>
                                                    <p class="mb-0">
                                                        @if(array_filter($currentFilters))
                                                            No courses found matching your filters
                                                        @else
                                                            No courses found
                                                        @endif
                                                    </p>
                                                    @if(array_filter($currentFilters))
                                                        <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-primary-600 mt-12">
                                                            Clear Filters
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($courses->hasPages())
                            <div class="border-top border-gray-100 px-24 py-16">
                                <div class="flex-between flex-wrap gap-16">
                                    <div class="text-sm text-gray-600">
                                        Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} courses
                                    </div>
                                    {{ $courses->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- First Delete Confirmation Modal -->
    <div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 radius-12">
                <div class="modal-header border-bottom border-gray-100 py-16 px-24">
                    <h5 class="modal-title text-danger-600" id="deleteCourseModalLabel">
                        <i class="ph ph-warning-circle me-8"></i>
                        Confirm Course Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-24 py-20">
                    <div class="text-center mb-20">
                        <div class="w-64 h-64 bg-danger-50 text-danger-600 rounded-circle flex-center text-2xl mx-auto mb-16">
                            <i class="ph ph-trash"></i>
                        </div>
                        <h6 class="text-lg fw-semibold mb-8">Delete Course</h6>
                        <p class="text-gray-600 mb-0">
                            Are you sure you want to delete <strong id="deleteCourseTitle"></strong> 
                            (<span id="deleteCourseCode"></span>) by <span id="deleteInstructorName"></span>?
                        </p>
                    </div>
                    <div class="alert alert-danger-50 border border-danger-200 radius-8 p-16">
                        <div class="flex-align gap-8">
                            <i class="ph ph-warning text-danger-600"></i>
                            <div>
                                <h6 class="text-sm fw-semibold text-danger-600 mb-4">Warning</h6>
                                <p class="text-xs text-danger-600 mb-0">
                                    This will permanently delete the course and all its assignments, materials, and enrollments. This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div id="enrollmentInfo" class="alert alert-info-50 border border-info-200 radius-8 p-16 d-none">
                        <div class="flex-align gap-8">
                            <i class="ph ph-info text-info-600"></i>
                            <div>
                                <h6 class="text-sm fw-semibold text-info-600 mb-4">Students Enrolled</h6>
                                <p class="text-xs text-info-600 mb-0">
                                    This course has <span id="studentsCountInfo"></span> enrolled student(s). They will be automatically unenrolled.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-gray-100 px-24 py-16">
                    <button type="button" class="btn btn-gray radius-8 px-20 py-11" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger radius-8 px-20 py-11" id="proceedDeleteBtn">
                        <i class="ph ph-trash me-8"></i>
                        Delete Course
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Confirmation Modal (for courses with students) -->
    <div class="modal fade" id="finalDeleteModal" tabindex="-1" aria-labelledby="finalDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 radius-12">
                <div class="modal-header border-bottom border-gray-100 py-16 px-24">
                    <h5 class="modal-title text-danger-600" id="finalDeleteModalLabel">
                        <i class="ph ph-warning-octagon me-8"></i>
                        Final Confirmation Required
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-24 py-20">
                    <div class="text-center mb-20">
                        <div class="w-64 h-64 bg-warning-50 text-warning-600 rounded-circle flex-center text-2xl mx-auto mb-16">
                            <i class="ph ph-warning-octagon"></i>
                        </div>
                        <h6 class="text-lg fw-semibold mb-8 text-warning-600">Critical Action Warning</h6>
                        <p class="text-gray-600 mb-0">
                            You are about to delete <strong id="finalDeleteCourseTitle"></strong> 
                            which has <strong id="finalStudentsCount"></strong> enrolled student(s).
                        </p>
                    </div>
                    
                    <div class="alert alert-warning-50 border border-warning-200 radius-8 p-16 mb-16">
                        <div class="flex-align gap-8">
                            <i class="ph ph-warning text-warning-600"></i>
                            <div>
                                <h6 class="text-sm fw-semibold text-warning-600 mb-8">This action will:</h6>
                                <ul class="text-xs text-warning-600 mb-0 ps-16">
                                    <li>Permanently delete the course</li>
                                    <li>Remove all <span id="finalStudentsCountText"></span> student enrollments</li>
                                    <li>Delete all assignments and submissions</li>
                                    <li>Delete all course materials</li>
                                    <li>Remove all course-related data</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-danger-50 border border-danger-200 radius-8 p-16">
                        <div class="flex-align gap-8">
                            <i class="ph ph-x-circle text-danger-600"></i>
                            <div>
                                <h6 class="text-sm fw-semibold text-danger-600 mb-4">This action cannot be undone!</h6>
                                <p class="text-xs text-danger-600 mb-0">
                                    Please ensure you have backed up any important data before proceeding.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mt-16">
                        <input class="form-check-input" type="checkbox" id="confirmUnderstand">
                        <label class="form-check-label text-sm fw-medium" for="confirmUnderstand">
                            I understand the consequences and want to proceed with deletion
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top border-gray-100 px-24 py-16">
                    <button type="button" class="btn btn-gray radius-8 px-20 py-11" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger radius-8 px-20 py-11" id="confirmFinalDeleteBtn" disabled>
                        <i class="ph ph-trash me-8"></i>
                        <span class="delete-text">Yes, Delete Everything</span>
                        <span class="delete-loading d-none">
                            <span class="spinner-border spinner-border-sm me-8" role="status"></span>
                            Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteCourseModal'));
            const finalDeleteModal = new bootstrap.Modal(document.getElementById('finalDeleteModal'));
            const proceedDeleteBtn = document.getElementById('proceedDeleteBtn');
            const confirmFinalDeleteBtn = document.getElementById('confirmFinalDeleteBtn');
            const confirmCheckbox = document.getElementById('confirmUnderstand');
            let currentCourseId = null;
            let currentStudentsCount = 0;

            // Auto-submit form on filter change
            const filterForm = document.getElementById('filterForm');
            const autoSubmitElements = ['status', 'level', 'semester', 'instructor', 'department', 'faculty', 'sort_by', 'sort_order', 'per_page'];
            
            autoSubmitElements.forEach(elementId => {
                const element = document.getElementById(elementId);
                if (element) {
                    element.addEventListener('change', function() {
                        filterForm.submit();
                    });
                }
            });

            // Search with debounce
            const searchInput = document.getElementById('search');
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filterForm.submit();
                }, 500);
            });

            // Delete course functionality - First modal
            document.querySelectorAll('.delete-course-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    currentCourseId = this.dataset.courseId;
                    const courseTitle = this.dataset.courseTitle;
                    const courseCode = this.dataset.courseCode;
                    const instructorName = this.dataset.instructorName;
                    currentStudentsCount = parseInt(this.dataset.studentsCount);

                    document.getElementById('deleteCourseTitle').textContent = courseTitle;
                    document.getElementById('deleteCourseCode').textContent = courseCode;
                    document.getElementById('deleteInstructorName').textContent = instructorName;

                    // Show enrollment info if students are enrolled
                    const enrollmentInfo = document.getElementById('enrollmentInfo');
                    if (currentStudentsCount > 0) {
                        document.getElementById('studentsCountInfo').textContent = currentStudentsCount;
                        enrollmentInfo.classList.remove('d-none');
                    } else {
                        enrollmentInfo.classList.add('d-none');
                    }

                    deleteModal.show();
                });
            });

            // Proceed to second confirmation or direct delete
            proceedDeleteBtn.addEventListener('click', function() {
                if (currentStudentsCount > 0) {
                    // Show second confirmation modal for courses with students
                    deleteModal.hide();
                    
                    // Populate second modal
                    document.getElementById('finalDeleteCourseTitle').textContent = document.getElementById('deleteCourseTitle').textContent;
                    document.getElementById('finalStudentsCount').textContent = currentStudentsCount;
                    document.getElementById('finalStudentsCountText').textContent = currentStudentsCount;
                    
                    // Reset checkbox
                    confirmCheckbox.checked = false;
                    confirmFinalDeleteBtn.disabled = true;
                    
                    finalDeleteModal.show();
                } else {
                    // Direct delete for courses without students
                    performDelete();
                }
            });

            // Enable/disable final delete button based on checkbox
            confirmCheckbox.addEventListener('change', function() {
                confirmFinalDeleteBtn.disabled = !this.checked;
            });

            // Final delete confirmation
            confirmFinalDeleteBtn.addEventListener('click', function() {
                if (!confirmCheckbox.checked) return;
                performDelete();
            });

            // Perform the actual delete
            function performDelete() {
                if (!currentCourseId) return;

                const deleteText = document.querySelector('.delete-text');
                const deleteLoading = document.querySelector('.delete-loading');

                // Show loading state
                confirmFinalDeleteBtn.disabled = true;
                if (deleteText && deleteLoading) {
                    deleteText.classList.add('d-none');
                    deleteLoading.classList.remove('d-none');
                }

                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Make delete request
                fetch(`/admin/courses/${currentCourseId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide modals
                        deleteModal.hide();
                        finalDeleteModal.hide();
                        
                        // Show success message
                        showAlert('success', data.message);
                        
                        // Remove course row from table
                        const courseRow = document.querySelector(`[data-course-id="${currentCourseId}"]`).closest('tr');
                        if (courseRow) {
                            courseRow.remove();
                        }
                        
                        // Reload page to update statistics
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    showAlert('error', 'Failed to delete course. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    confirmFinalDeleteBtn.disabled = false;
                    if (deleteText && deleteLoading) {
                        deleteText.classList.remove('d-none');
                        deleteLoading.classList.add('d-none');
                    }
                    currentCourseId = null;
                    currentStudentsCount = 0;
                });
            }

            // Show alert function
            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const iconClass = type === 'success' ? 'ph-check-circle' : 'ph-warning-circle';
                const titleText = type === 'success' ? 'Success!' : 'Error!';
                
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show mb-24" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="${iconClass} text-${type === 'success' ? 'success' : 'danger'} me-12 text-xl"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-4 text-${type === 'success' ? 'success' : 'danger'} fw-semibold">${titleText}</h6>
                                <p class="mb-0 text-${type === 'success' ? 'success' : 'danger'}-emphasis">${message}</p>
                            </div>
                            <button type="button" class="btn-close ms-12" data-bs-dismiss="alert" aria-label="Close">
                                <i class="ph ph-x text-${type === 'success' ? 'success' : 'danger'}"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                // Insert alert at the top of dashboard body
                const dashboardBody = document.querySelector('.dashboard-body__content');
                dashboardBody.insertAdjacentHTML('afterbegin', alertHtml);
                
                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    const alert = dashboardBody.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            }
        });
    </script>
</x-admin-layout>