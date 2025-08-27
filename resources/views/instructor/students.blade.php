<x-instructor-layout
    :metaTitle="$meta_title"
    :metaDesc="$meta_desc"
    :metaImage="$meta_image"
>
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Enrolled Students</h6>
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
                <li class="fw-medium text-primary">Enrolled Students</li>
            </ul>
        </div>

     
        @if($courses->isEmpty())
            <!-- No Courses State -->
            <div class="card radius-8">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="ph ph-users-three text-6xl text-gray-400"></i>
                    </div>
                    <h5 class="mb-2 fw-semibold">No Courses Found</h5>
                    <p class="text-gray mb-4">You haven't created any courses yet. Create a course first to view enrolled students.</p>
                    <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary radius-8 px-20 py-11">
                        <i class="ph ph-plus-circle me-8"></i>
                        Create Course
                    </a>
                </div>
            </div>
        @else
            <!-- Statistics Cards -->
            <div class="row gy-4 mb-24">
                <div class="col-xxl-3 col-sm-6">
                    <div class="card radius-8 border-0 overflow-hidden">
                        <div class="card-body p-20">
                            <div class="d-flex align-items-center gap-16">
                                <div class="w-64-px h-64-px bg-primary-50 text-primary rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                    <i class="ph ph-users-three text-2xl"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Total Enrollments</span>
                                    <h4 class="fw-bold text-primary-light mb-0">{{ number_format($stats['total_enrollments']) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card radius-8 border-0 overflow-hidden">
                        <div class="card-body p-20">
                            <div class="d-flex align-items-center gap-16">
                                <div class="w-64-px h-64-px bg-success-50 text-success rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                    <i class="ph ph-check-circle text-2xl"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Active Students</span>
                                    <h4 class="fw-bold text-primary-light mb-0">{{ number_format($stats['active_enrollments']) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card radius-8 border-0 overflow-hidden">
                        <div class="card-body p-20">
                            <div class="d-flex align-items-center gap-16">
                                <div class="w-64-px h-64-px bg-info-50 text-info rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                    <i class="ph ph-graduation-cap text-2xl"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Completed</span>
                                    <h4 class="fw-bold text-primary-light mb-0">{{ number_format($stats['completed_enrollments']) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card radius-8 border-0 overflow-hidden">
                        <div class="card-body p-20">
                            <div class="d-flex align-items-center gap-16">
                                <div class="w-64-px h-64-px bg-warning-50 text-warning rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                    <i class="ph ph-calendar-plus text-2xl"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="mb-2 fw-medium text-secondary-light text-sm d-block">Recent (30 days)</span>
                                    <h4 class="fw-bold text-primary-light mb-0">{{ number_format($stats['recent_enrollments']) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card radius-8 border-0 overflow-hidden mb-24">
                <div class="card-header border-bottom border-gray-100 flex-between flex-wrap gap-8">
                    <h6 class="mb-0 fw-semibold">Filter Students</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('instructor.students.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
                           class="btn btn-primary radius-8 px-20 py-11">
                            <i class="ph ph-download me-8"></i>
                            Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body p-24">
                    <form method="GET" action="{{ route('instructor.students.enrolled') }}" class="row gy-4">
                        <div class="col-md-3">
                            <label for="course_id" class="form-label fw-semibold text-primary-light">Filter by Course</label>
                            <select name="course_id" id="course_id" class="form-select radius-8">
                                <option value="all" {{ $selectedCourseId == 'all' ? 'selected' : '' }}>All Courses</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ $selectedCourseId == $course->id ? 'selected' : '' }}>
                                        {{ $course->code }} - {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label fw-semibold text-primary-light">Status</label>
                            <select name="status" id="status" class="form-select radius-8">
                                <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All Status</option>
                                @foreach($availableStatuses as $key => $label)
                                    <option value="{{ $key }}" {{ $statusFilter == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label fw-semibold text-primary-light">Search Students</label>
                            <div class="position-relative">
                                <input type="text" name="search" id="search" class="form-control radius-8 ps-44" 
                                       placeholder="Name, email, or ID..." value="{{ $searchTerm }}">
                                <span class="position-absolute top-50 translate-middle-y ms-16 text-gray">
                                    <i class="ph ph-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="sort_by" class="form-label fw-semibold text-primary-light">Sort By</label>
                            <select name="sort_by" id="sort_by" class="form-select radius-8">
                                <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="course" {{ $sortBy == 'course' ? 'selected' : '' }}>Course</option>
                                <option value="enrolled_at" {{ $sortBy == 'enrolled_at' ? 'selected' : '' }}>Enrollment Date</option>
                                <option value="status" {{ $sortBy == 'status' ? 'selected' : '' }}>Status</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-primary-light">&nbsp;</label>
                            <div class="d-flex gap-8">
                                <button type="submit" class="btn btn-primary radius-8 px-20 py-11 flex-fill">
                                    <i class="ph ph-funnel me-8"></i>
                                    Filter
                                </button>
                                <a href="{{ route('instructor.students.enrolled') }}" class="btn btn-gray radius-8 px-20 py-11">
                                    <i class="ph ph-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Students Table -->
            <div class="card radius-8 border-0 overflow-hidden">
                <div class="card-header border-bottom border-gray-100 flex-between flex-wrap gap-8">
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            Enrolled Students
                            @if($selectedCourse)
                                <span class="text-sm text-secondary-light fw-medium">in {{ $selectedCourse->code }} - {{ $selectedCourse->title }}</span>
                            @endif
                        </h5>
                        <p class="text-gray text-sm mb-0 mt-4">Manage and view all enrolled students</p>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="ps-24">
                                            <div class="d-flex align-items-center gap-10">
                                                <div class="form-check style-check d-flex align-items-center">
                                                    <input class="form-check-input radius-4 border input-form-dark" type="checkbox" name="checkbox" id="selectAll">
                                                </div>
                                                <span class="fw-semibold text-primary-light">Student</span>
                                            </div>
                                        </th>
                                        <th scope="col" class="fw-semibold text-primary-light">Course</th>
                                        <th scope="col" class="fw-semibold text-primary-light">Department</th>
                                        <th scope="col" class="fw-semibold text-primary-light">Level</th>
                                        <th scope="col" class="fw-semibold text-primary-light">Status</th>
                                        <th scope="col" class="fw-semibold text-primary-light">Enrolled</th>
                                        <th scope="col" class="fw-semibold text-primary-light pe-24">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr class="border-bottom border-gray-100">
                                            <td class="ps-24">
                                                <div class="d-flex align-items-center gap-10">
                                                    <div class="form-check style-check d-flex align-items-center">
                                                        <input class="form-check-input radius-4 border input-form-dark" type="checkbox" name="student_ids[]" value="{{ $student->id }}">
                                                    </div>
                                                    <div class="d-flex align-items-center gap-12">
                                                        <div class="position-relative">
                                                            <img src="{{ $student->profile_image_url }}" alt="{{ $student->name }}" class=" rounded-circle object-fit-cover border border-gray-200" style="width: 44px; height:44px;">
                                                            <span class="position-absolute bottom-0 end-0 w-14-px h-14-px bg-success rounded-circle border-2 border-white"></span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="text-md mb-0 fw-semibold text-primary-light">{{ $student->name }}</h6>
                                                            <span class="text-sm text-secondary-light fw-medium">{{ $student->email }}</span>
                                                            @if($student->matric_or_staff_id)
                                                                <div class="text-xs text-secondary-light mt-2">ID: {{ $student->matric_or_staff_id }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="fw-semibold text-primary">{{ $student->course_code }}</span>
                                                    <div class="text-sm text-secondary-light">{{ $student->course_title }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-sm fw-medium">{{ $student->department ?? 'N/A' }}</span>
                                                @if($student->faculty)
                                                    <div class="text-xs text-secondary-light">{{ $student->faculty }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($student->level)
                                                    <span class="badge bg-info-50 text-info px-12 py-6 rounded-pill fw-medium">{{ $student->level }} Level</span>
                                                @else
                                                    <span class="text-secondary-light">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm radius-8 status-select" 
                                                        data-user-id="{{ $student->id }}" 
                                                        data-course-id="{{ $student->course_id }}">
                                                    @foreach($availableStatuses as $key => $label)
                                                        <option value="{{ $key }}" {{ $student->enrollment_status == $key ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                @if($student->enrolled_at)
                                                    <span class="text-sm fw-medium">{{ $student->enrolled_at->format('M d, Y') }}</span>
                                                    <div class="text-xs text-secondary-light">{{ $student->enrolled_at->diffForHumans() }}</div>
                                                @else
                                                    <span class="text-sm text-secondary-light">N/A</span>
                                                @endif
                                            </td>
                                            <td class="pe-24">
                                                <div class="d-flex align-items-center gap-8">
                                                    <button type="button" class="btn btn-success radius-8 px-16 py-8 d-flex align-items-center gap-8" 
                                                            data-bs-toggle="modal" data-bs-target="#studentModal{{ $student->id }}" title="View Details">
                                                        <i class="ph ph-eye"></i>
                                                        <span class="d-none d-sm-inline">View</span>
                                                    </button>
                                                    <button type="button" class="btn btn-danger radius-8 px-16 py-8 d-flex align-items-center gap-8 remove-student" 
                                                            data-user-id="{{ $student->id }}" 
                                                            data-course-id="{{ $student->course_id }}" 
                                                            data-student-name="{{ $student->name }}" 
                                                            data-course-name="{{ $student->course_code }}" 
                                                            title="Remove from Course">
                                                        <i class="ph ph-trash"></i>
                                                        <span class="d-none d-sm-inline">Remove</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Student Details Modal -->
                                        <div class="modal fade" id="studentModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content radius-16 border-0">
                                                    <div class="modal-header border-bottom border-gray-100 pb-20 mb-20">
                                                        <h5 class="modal-title fw-semibold">Student Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body flex justify-center items-center flex-col">
                                                        <div class="text-center mb-24">
                                                            <img src="{{ $student->profile_image_url }}" alt="{{ $student->name }}" class=" rounded-circle object-fit-cover border border-gray-200 mb-12" style="width: 60px ; height:60px'">
                                                            <h5 class="mb-4 fw-semibold">{{ $student->name }}</h5>
                                                            <span class="text-secondary-light">{{ $student->email }}</span>
                                                        </div>
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="p-16 bg-gray-50 radius-8">
                                                                    <span class="fw-semibold text-primary-light d-block mb-4">Matric/Staff ID</span>
                                                                    <span class="text-secondary-light">{{ $student->matric_or_staff_id ?? 'N/A' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="p-16 bg-gray-50 radius-8">
                                                                    <span class="fw-semibold text-primary-light d-block mb-4">Level</span>
                                                                    <span class="text-secondary-light">{{ $student->level ? $student->level . ' Level' : 'N/A' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="p-16 bg-gray-50 radius-8">
                                                                    <span class="fw-semibold text-primary-light d-block mb-4">Department</span>
                                                                    <span class="text-secondary-light">{{ $student->department ?? 'N/A' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="p-16 bg-gray-50 radius-8">
                                                                    <span class="fw-semibold text-primary-light d-block mb-4">Faculty</span>
                                                                    <span class="text-secondary-light">{{ $student->faculty ?? 'N/A' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="p-16 bg-gray-50 radius-8">
                                                                    <span class="fw-semibold text-primary-light d-block mb-4">Course</span>
                                                                    <span class="text-secondary-light">{{ $student->course_code }} - {{ $student->course_title }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="p-16 bg-gray-50 radius-8">
                                                                    <span class="fw-semibold text-primary-light d-block mb-4">Enrollment Status</span>
                                                                    <span class="badge bg-{{ $student->enrollment_status == 'active' ? 'success' : ($student->enrollment_status == 'completed' ? 'info' : 'warning') }}-50 text-{{ $student->enrollment_status == 'active' ? 'success' : ($student->enrollment_status == 'completed' ? 'info' : 'warning') }} px-12 py-6 rounded-pill">
                                                                        {{ ucfirst($student->enrollment_status) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="p-16 bg-gray-50 radius-8">
                                                                    <span class="fw-semibold text-primary-light d-block mb-4">Enrolled At</span>
                                                                    <span class="text-secondary-light">{{ $student->enrolled_at ? $student->enrolled_at->format('F d, Y \a\t g:i A') : 'N/A' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top border-gray-100 pt-20 mt-20">
                                                        <button type="button" class="btn btn-gray-400 radius-8 px-20 py-11" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-24 border-top border-gray-100">
                            <span class="text-secondary-light fw-medium">
                                Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} results
                            </span>
                            {{ $students->links() }}
                        </div>
                    @else
                        <div class="text-center py-5 p-24">
                            <div class="mb-4">
                                <i class="ph ph-users-three text-6xl text-gray-400"></i>
                            </div>
                            <h5 class="mb-2 fw-semibold">No Students Found</h5>
                            <p class="text-gray mb-4">
                                @if($searchTerm || $statusFilter != 'all' || $selectedCourseId != 'all')
                                    No students match your current filters. Try adjusting your search criteria.
                                @else
                                    No students are enrolled in your courses yet.
                                @endif
                            </p>
                            @if($searchTerm || $statusFilter != 'all' || $selectedCourseId != 'all')
                                <a href="{{ route('instructor.students.enrolled') }}" class="btn btn-primary radius-8 px-20 py-11">
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 border-0">
                <div class="modal-header border-bottom border-gray-100 pb-20 mb-20">
                    <h5 class="modal-title fw-semibold text-danger">
                        <i class="ph ph-warning-circle me-8"></i>
                        Remove Student
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-24">
                        <div class="w-80 text-danger rounded-circle d-flex justify-content-center align-items-center mx-auto mb-16">
                            <i class="ph ph-trash text-3xl"></i>
                        </div>
                        <h6 class="mb-8 fw-semibold">Are you sure you want to remove this student?</h6>
                        <p class="text-gray mb-0">
                            You are about to remove <strong id="deleteStudentName"></strong> from 
                            <strong id="deleteCourseName"></strong>. This action cannot be undone.
                        </p>
                    </div>
                    <div class="bg-warning-50 p-16 radius-8 mb-20">
                        <div class="d-flex align-items-start gap-12">
                            <i class="ph ph-warning text-warning text-xl flex-shrink-0 mt-2"></i>
                            <div>
                                <h6 class="text-warning mb-4 fw-semibold">Warning</h6>
                                <p class="text-warning text-sm mb-0">
                                    The student will lose access to all course materials, assignments, and their enrollment history will be permanently deleted.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-gray-100 pt-20 mt-20 justify-content-center">
                    <button type="button" class="btn btn-gray radius-8 px-20 py-11" data-bs-dismiss="modal">
                        <i class="ph ph-x me-8"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger radius-8 px-20 py-11" id="confirmDeleteBtn">
                        <i class="ph ph-trash me-8"></i>
                        <span id="deleteButtonText">Remove Student</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

   <script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure jQuery is loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded');
        return;
    }

    let deleteUserId, deleteCourseId;

    // Handle status change
    $(document).on('change', '.status-select', function() {
        const userId = $(this).data('user-id');
        const courseId = $(this).data('course-id');
        const newStatus = $(this).val();
        const selectElement = $(this);
        const originalValue = selectElement.data('original-value');
        
        // Show loading state
        selectElement.prop('disabled', true);
        
        $.ajax({
            url: "{{ route('instructor.students.update-status') }}",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: userId,
                course_id: courseId,
                status: newStatus
            },
            success: function(response) {
                selectElement.prop('disabled', false);
                if (response.success) {
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert('Status updated successfully');
                    }
                    // Update original value
                    selectElement.data('original-value', newStatus);
                } else {
                    // Show error and revert
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Failed to update status');
                    } else {
                        alert('Failed to update status');
                    }
                    selectElement.val(originalValue);
                }
            },
            error: function(xhr, status, error) {
                selectElement.prop('disabled', false);
                console.error('AJAX Error:', error);
                
                // Show error message
                if (typeof toastr !== 'undefined') {
                    toastr.error('An error occurred while updating status');
                } else {
                    alert('An error occurred while updating status');
                }
                
                // Revert to original value
                selectElement.val(originalValue);
            }
        });
    });

    // Store original values for rollback on page load
    $('.status-select').each(function() {
        $(this).data('original-value', $(this).val());
    });

    // Handle student removal - Show modal
    $(document).on('click', '.remove-student', function() {
        deleteUserId = $(this).data('user-id');
        deleteCourseId = $(this).data('course-id');
        const studentName = $(this).data('student-name');
        const courseName = $(this).data('course-name');
        
        // Update modal content
        $('#deleteStudentName').text(studentName);
        $('#deleteCourseName').text(courseName);
        
        // Show the modal
        $('#deleteStudentModal').modal('show');
    });

    // Handle actual deletion when confirm button is clicked
    $(document).on('click', '#confirmDeleteBtn', function() {
        const button = $(this);
        const originalText = button.find('#deleteButtonText').text();
        const originalIcon = button.find('i').attr('class');
        
        // Show loading state
        button.prop('disabled', true);
        button.find('#deleteButtonText').text('Removing...');
        button.find('i').attr('class', 'ph ph-spinner-gap ph-spin me-8');
        
        $.ajax({
            url: "{{ route('instructor.students.remove') }}",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: deleteUserId,
                course_id: deleteCourseId
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteStudentModal').modal('hide');
                    
                    // Redirect to same page to show Laravel success message
                    window.location.href = "{{ route('instructor.students.enrolled') }}?success=1";
                } else {
                    // Show error and reset button
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Failed to remove student');
                    } else {
                        alert('Failed to remove student');
                    }
                    resetDeleteButton(button, originalText, originalIcon);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                
                // Show error message
                if (typeof toastr !== 'undefined') {
                    toastr.error('An error occurred while removing student');
                } else {
                    alert('An error occurred while removing student');
                }
                
                resetDeleteButton(button, originalText, originalIcon);
            }
        });
    });

    // Function to reset delete button state
    function resetDeleteButton(button, originalText, originalIcon) {
        button.prop('disabled', false);
        button.find('#deleteButtonText').text(originalText);
        button.find('i').attr('class', originalIcon);
    }

    // Reset modal state when closed
    $('#deleteStudentModal').on('hidden.bs.modal', function() {
        const button = $('#confirmDeleteBtn');
        resetDeleteButton(button, 'Remove Student', 'ph ph-trash me-8');
    });

    // Handle select all checkbox
    $(document).on('change', '#selectAll', function() {
        $('input[name="student_ids[]"]').prop('checked', this.checked);
    });

    // Auto-submit form on filter change
    $(document).on('change', '#course_id, #status', function() {
        $(this).closest('form').submit();
    });

    // Add CSS for spinner animation
    if (!document.getElementById('spinner-css')) {
        const style = document.createElement('style');
        style.id = 'spinner-css';
        style.textContent = `
            .ph-spin {
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
});
</script>
</x-instructor-layout>