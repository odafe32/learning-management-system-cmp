<x-admin-layout :metaTitle="$metaTitle" :metaDesc="$metaDesc" :metaImage="$metaImage">
    <div class="dashboard-body__content">
        <div class="row gy-4">
            <div class="col-lg-12">
                <!-- Page Header -->
                <div class="card border-0 bg-transparent">
                    <div class="card-body p-0">
                        <div class="flex-between flex-wrap gap-16 mb-24">
                            <div>
                                <h4 class="mb-8 text-xl fw-semibold">Manage Users</h4>
                                <p class="text-gray-600 text-15">View, manage, and delete users in the system.</p>
                            </div>
                            <div class="flex-align gap-8">
                                <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-success radius-8 px-20 py-11">
                                    <i class="ph ph-download me-8"></i>
                                    Export Users
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-main radius-8 px-20 py-11">
                                    <i class="ph ph-plus me-8"></i>
                                    Create New User
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
                            Search & Filter Users
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
                            <div class="row gy-16">
                                <!-- Search Input -->
                                <div class="col-md-4">
                                    <label for="search" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Search Users
                                    </label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               class="form-control radius-8 ps-40" 
                                               id="search" 
                                               name="search" 
                                               value="{{ $currentFilters['search'] }}" 
                                               placeholder="Search by name, email, ID...">
                                        <i class="ph ph-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-12 text-gray-400"></i>
                                    </div>
                                </div>

                                <!-- Role Filter -->
                                <div class="col-md-2">
                                    <label for="role" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Role
                                    </label>
                                    <select class="form-control radius-8" id="role" name="role">
                                        <option value="">All Roles</option>
                                        @foreach($filterOptions['roles'] as $roleValue => $roleLabel)
                                            <option value="{{ $roleValue }}" {{ $currentFilters['role'] == $roleValue ? 'selected' : '' }}>
                                                {{ $roleLabel }}
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

                                <!-- Gender Filter -->
                                <div class="col-md-2">
                                    <label for="gender" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Gender
                                    </label>
                                    <select class="form-control radius-8" id="gender" name="gender">
                                        <option value="">All Genders</option>
                                        @foreach($filterOptions['genders'] as $genderValue => $genderLabel)
                                            <option value="{{ $genderValue }}" {{ $currentFilters['gender'] == $genderValue ? 'selected' : '' }}>
                                                {{ $genderLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Level Filter (for students) -->
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
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-gray-600 radius-8 px-20 py-11">
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
                                                    No filters applied - showing all users
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- User Statistics -->
                <div class="row gy-4 mb-24">
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 h-100">
                            <div class="card-body p-20">
                                <div class="flex-between gap-8 mb-16">
                                    <div class="flex-align gap-16">
                                        <div class="w-44 h-44 bg-main-50 text-main-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-users"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">
                                                {{ array_filter($currentFilters) ? 'Filtered' : 'Total' }} Users
                                            </span>
                                            <h4 class="mb-0 text-main-600">{{ number_format($userStats['total']) }}</h4>
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
                                            <i class="ph ph-graduation-cap"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">Students</span>
                                            <h4 class="mb-0 text-success-600">{{ number_format($userStats['students']) }}</h4>
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
                                        <div class="w-44 h-44 bg-info-50 text-info-600 rounded-circle flex-center text-xl">
                                            <i class="ph ph-chalkboard-teacher"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">Instructors</span>
                                            <h4 class="mb-0 text-info-600">{{ number_format($userStats['instructors']) }}</h4>
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
                                            <i class="ph ph-shield-check"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-sm fw-medium">Admins</span>
                                            <h4 class="mb-0 text-danger-600">{{ number_format($userStats['admins']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card border-0 overflow-hidden">
                    <div class="card-header bg-main-50 border-bottom border-gray-100 py-16 px-24">
                        <div class="flex-between flex-wrap gap-16">
                            <h6 class="text-lg fw-semibold mb-0 text-main-600">
                                <i class="ph ph-list me-8"></i>
                                Users List
                                @if(array_filter($currentFilters))
                                    <span class="badge bg-primary-50 text-primary-600 text-xs px-8 py-4 rounded-4 ms-8">
                                        Filtered
                                    </span>
                                @endif
                            </h6>
                            <div class="flex-align gap-16">
                                <small class="text-gray-600">
                                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="usersTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="ps-24 py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => $currentFilters['sort_by'] == 'name' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                User
                                                @if($currentFilters['sort_by'] == 'name')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'role', 'sort_order' => $currentFilters['sort_by'] == 'role' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Role
                                                @if($currentFilters['sort_by'] == 'role')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'department', 'sort_order' => $currentFilters['sort_by'] == 'department' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Department
                                                @if($currentFilters['sort_by'] == 'department')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-16 text-gray-900 fw-semibold">Contact</th>
                                        <th class="py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'matric_or_staff_id', 'sort_order' => $currentFilters['sort_by'] == 'matric_or_staff_id' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                ID Number
                                                @if($currentFilters['sort_by'] == 'matric_or_staff_id')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="py-16 text-gray-900 fw-semibold">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => $currentFilters['sort_by'] == 'created_at' && $currentFilters['sort_order'] == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-gray-900 d-flex align-items-center">
                                                Joined
                                                @if($currentFilters['sort_by'] == 'created_at')
                                                    <i class="ph ph-caret-{{ $currentFilters['sort_order'] == 'asc' ? 'up' : 'down' }} ms-4"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="pe-24 py-16 text-gray-900 fw-semibold text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr data-role="{{ $user->role }}">
                                            <td class="ps-24 py-16">
                                                <div class="flex-align gap-12">
                                                    <div class="w-40 h-40 rounded-circle bg-main-50 flex-center text-main-600 fw-semibold">
                                                        {{ $user->initials }}
                                                    </div>
                                                    <div>
                                                        <h6 class="text-sm fw-semibold mb-4">{{ $user->name }}</h6>
                                                        <span class="text-xs text-gray-500">{{ $user->email }}</span>
                                                        @if($user->gender)
                                                            <br><small class="text-gray-400">{{ ucfirst($user->gender) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                {!! $user->role_badge !!}
                                            </td>
                                            <td class="py-16">
                                                <div>
                                                    <span class="text-sm fw-medium text-gray-900">{{ $user->department ?? 'N/A' }}</span>
                                                    @if($user->faculty)
                                                        <br><small class="text-gray-500">{{ $user->faculty }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                <div>
                                                    @if($user->phone)
                                                        <span class="text-sm text-gray-900">{{ $user->phone }}</span>
                                                    @else
                                                        <span class="text-sm text-gray-400">No phone</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-16">
                                                @if($user->matric_or_staff_id)
                                                    <span class="text-sm fw-medium text-gray-900">{{ $user->matric_or_staff_id }}</span>
                                                    @if($user->level)
                                                        <br><small class="text-gray-500">Level {{ $user->level }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-sm text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                            <td class="py-16">
                                                <span class="text-sm text-gray-600">{{ $user->created_at->format('M d, Y') }}</span>
                                                <br><small class="text-gray-400">{{ $user->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="pe-24 py-16 text-center">
                                                <div class="flex-align justify-content-center gap-8">
                                                    @if($user->id !== Auth::id())
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger radius-4 px-12 py-6 delete-user-btn"
                                                                data-user-id="{{ $user->id }}"
                                                                data-user-name="{{ $user->name }}"
                                                                data-user-role="{{ $user->getRoleDisplayName() }}"
                                                                title="Delete User">
                                                            <i class="ph ph-trash text-sm"></i>
                                                        </button>
                                                    @else
                                                        <span class="badge bg-warning text-white px-8 py-4 rounded-4 text-xs">
                                                            Current User
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-32">
                                                <div class="text-gray-400">
                                                    <i class="ph ph-users text-4xl mb-16"></i>
                                                    <p class="mb-0">
                                                        @if(array_filter($currentFilters))
                                                            No users found matching your filters
                                                        @else
                                                            No users found
                                                        @endif
                                                    </p>
                                                    @if(array_filter($currentFilters))
                                                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary-600 mt-12">
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
                        
                        @if($users->hasPages())
                            <div class="border-top border-gray-100 px-24 py-16">
                                <div class="flex-between flex-wrap gap-16">
                                    <div class="text-sm text-gray-600">
                                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                                    </div>
                                    {{ $users->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 radius-12">
                <div class="modal-header border-bottom border-gray-100 py-16 px-24">
                    <h5 class="modal-title text-danger-600" id="deleteUserModalLabel">
                        <i class="ph ph-warning-circle me-8"></i>
                        Confirm User Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-24 py-20">
                    <div class="text-center mb-20">
                        <div class="w-64 h-64 bg-danger-50 text-danger-600 rounded-circle flex-center text-2xl mx-auto mb-16">
                            <i class="ph ph-trash"></i>
                        </div>
                        <h6 class="text-lg fw-semibold mb-8">Delete User Account</h6>
                        <p class="text-gray-600 mb-0">
                            Are you sure you want to delete <strong id="deleteUserName"></strong> 
                            (<span id="deleteUserRole"></span>)? This action cannot be undone.
                        </p>
                    </div>
                    <div class="alert alert-danger-50 border border-danger-200 radius-8 p-16">
                        <div class="flex-align gap-8">
                            <i class="ph ph-warning text-danger-600"></i>
                            <div>
                                <h6 class="text-sm fw-semibold text-danger-600 mb-4">Warning</h6>
                                <p class="text-xs text-danger-600 mb-0">
                                    Deleting this user will permanently remove all their data, including courses, assignments, and messages.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-gray-100 px-24 py-16">
                    <button type="button" class="btn btn-gray radius-8 px-20 py-11" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger radius-8 px-20 py-11" id="confirmDeleteBtn">
                        <i class="ph ph-trash me-8"></i>
                        <span class="delete-text">Delete User</span>
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
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            let currentUserId = null;

            // Auto-submit form on filter change (optional)
            const filterForm = document.getElementById('filterForm');
            const autoSubmitElements = ['role', 'department', 'faculty', 'gender', 'level', 'sort_by', 'sort_order', 'per_page'];
            
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
                }, 500); // 500ms debounce
            });

            // Delete user functionality
            document.querySelectorAll('.delete-user-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    currentUserId = this.dataset.userId;
                    const userName = this.dataset.userName;
                    const userRole = this.dataset.userRole;

                    document.getElementById('deleteUserName').textContent = userName;
                    document.getElementById('deleteUserRole').textContent = userRole;

                    deleteModal.show();
                });
            });

            // Confirm delete
            confirmDeleteBtn.addEventListener('click', function() {
                if (!currentUserId) return;

                const deleteText = document.querySelector('.delete-text');
                const deleteLoading = document.querySelector('.delete-loading');

                // Show loading state
                this.disabled = true;
                deleteText.classList.add('d-none');
                deleteLoading.classList.remove('d-none');

                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Make delete request
                fetch('{{ route("admin.users.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: currentUserId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide modal
                        deleteModal.hide();
                        
                        // Show success message
                        showAlert('success', data.message);
                        
                        // Remove user row from table
                        const userRow = document.querySelector(`[data-user-id="${currentUserId}"]`).closest('tr');
                        if (userRow) {
                            userRow.remove();
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
                    showAlert('error', 'Failed to delete user. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    this.disabled = false;
                    deleteText.classList.remove('d-none');
                    deleteLoading.classList.add('d-none');
                    currentUserId = null;
                });
            });

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