<x-admin-layout :metaTitle="$metaTitle" :metaDesc="$metaDesc" :metaImage="$metaImage">
    <div class="dashboard-body__content">
        <!-- Welcome Section -->
        <div class="row mb-24">
            <div class="col-12">
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body p-24">
                        <div class="flex-between flex-wrap gap-16">
                            <div>
                                <h3 class="text-white mb-8">Welcome back, {{ $user->name }}!</h3>
                                <p class="text-white-75 mb-0">Here's what's happening in your LMS today</p>
                            </div>
                            <div class="text-end">
                                <div class="text-white-75 text-sm">{{ now()->format('l, F j, Y') }}</div>
                                <div class="text-white text-lg fw-semibold">{{ now()->format('g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Statistics Cards -->
        <div class="row gy-4 mb-24">
            <!-- Total Users -->
            <div class="col-xxl-3 col-md-6">
                <div class="card border-0 h-100">
                    <div class="card-body p-20">
                        <div class="flex-between gap-8 mb-16">
                            <div class="flex-align gap-16">
                                <div class="w-48 h-48 bg-primary-50 text-primary-600 rounded-circle flex-center text-xl">
                                    <i class="ph ph-users"></i>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-sm fw-medium">Total Users</span>
                                    <h4 class="mb-0 text-primary-600">{{ number_format(\App\Models\User::count()) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="flex-align gap-8">
                            <span class="text-success-600 text-sm fw-medium">
                                <i class="ph ph-trend-up me-4"></i>
                                +{{ \App\Models\User::whereDate('created_at', '>=', now()->subDays(7))->count() }}
                            </span>
                            <span class="text-gray-400 text-sm">this week</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Courses -->
            <div class="col-xxl-3 col-md-6">
                <div class="card border-0 h-100">
                    <div class="card-body p-20">
                        <div class="flex-between gap-8 mb-16">
                            <div class="flex-align gap-16">
                                <div class="w-48 h-48 bg-success-50 text-success-600 rounded-circle flex-center text-xl">
                                    <i class="ph ph-book-open"></i>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-sm fw-medium">Total Courses</span>
                                    <h4 class="mb-0 text-success-600">{{ number_format(\App\Models\Course::count()) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="flex-align gap-8">
                            <span class="text-success-600 text-sm fw-medium">
                                {{ \App\Models\Course::where('status', 'active')->count() }} Active
                            </span>
                            <span class="text-gray-400 text-sm">courses</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Assignments -->
            <div class="col-xxl-3 col-md-6">
                <div class="card border-0 h-100">
                    <div class="card-body p-20">
                        <div class="flex-between gap-8 mb-16">
                            <div class="flex-align gap-16">
                                <div class="w-48 h-48 bg-warning-50 text-warning-600 rounded-circle flex-center text-xl">
                                    <i class="ph ph-file-text"></i>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-sm fw-medium">Total Assignments</span>
                                    <h4 class="mb-0 text-warning-600">{{ number_format(\App\Models\Assignment::count()) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="flex-align gap-8">
                            <span class="text-danger-600 text-sm fw-medium">
                                {{ \App\Models\Assignment::where('deadline', '<', now())->where('status', 'active')->count() }} Overdue
                            </span>
                            <span class="text-gray-400 text-sm">assignments</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Materials -->
            <div class="col-xxl-3 col-md-6">
                <div class="card border-0 h-100">
                    <div class="card-body p-20">
                        <div class="flex-between gap-8 mb-16">
                            <div class="flex-align gap-16">
                                <div class="w-48 h-48 bg-info-50 text-info-600 rounded-circle flex-center text-xl">
                                    <i class="ph ph-files"></i>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-sm fw-medium">Total Materials</span>
                                    <h4 class="mb-0 text-info-600">{{ number_format(\App\Models\Material::count()) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="flex-align gap-8">
                            @php
                                $totalSize = \App\Models\Material::sum('file_size'); // in KB
                                $totalSizeMB = round($totalSize / 1024, 1);
                            @endphp
                            <span class="text-info-600 text-sm fw-medium">
                                {{ $totalSizeMB }} MB
                            </span>
                            <span class="text-gray-400 text-sm">total size</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Distribution & Course Statistics -->
        <div class="row gy-4 mb-24">
            <!-- User Distribution -->
            <div class="col-lg-6">
                <div class="card border-0 h-100">
                    <div class="card-header bg-gray-50 border-bottom border-gray-100 py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0 text-gray-900">
                            <i class="ph ph-users me-8"></i>
                            User Distribution
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        @php
                            $userStats = [
                                'students' => \App\Models\User::where('role', 'student')->count(),
                                'instructors' => \App\Models\User::whereIn('role', ['instructor', 'lecturer'])->count(),
                                'admins' => \App\Models\User::where('role', 'admin')->count(),
                            ];
                            $totalUsers = array_sum($userStats);
                        @endphp
                        
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="flex-between mb-8">
                                    <span class="text-gray-600">Students</span>
                                    <span class="text-gray-900 fw-semibold">{{ number_format($userStats['students']) }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $totalUsers > 0 ? ($userStats['students'] / $totalUsers) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="flex-between mb-8">
                                    <span class="text-gray-600">Instructors</span>
                                    <span class="text-gray-900 fw-semibold">{{ number_format($userStats['instructors']) }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $totalUsers > 0 ? ($userStats['instructors'] / $totalUsers) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="flex-between mb-8">
                                    <span class="text-gray-600">Admins</span>
                                    <span class="text-gray-900 fw-semibold">{{ number_format($userStats['admins']) }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $totalUsers > 0 ? ($userStats['admins'] / $totalUsers) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Statistics by Level -->
            <div class="col-lg-6">
                <div class="card border-0 h-100">
                    <div class="card-header bg-gray-50 border-bottom border-gray-100 py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0 text-gray-900">
                            <i class="ph ph-graduation-cap me-8"></i>
                            Courses by Level
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        @php
                            $coursesByLevel = [
                                '100' => \App\Models\Course::where('level', '100')->count(),
                                '200' => \App\Models\Course::where('level', '200')->count(),
                                '300' => \App\Models\Course::where('level', '300')->count(),
                                '400' => \App\Models\Course::where('level', '400')->count(),
                            ];
                            $totalCourses = array_sum($coursesByLevel);
                        @endphp
                        
                        <div class="row gy-3">
                            @foreach($coursesByLevel as $level => $count)
                                <div class="col-6">
                                    <div class="text-center p-16 bg-gray-50 rounded-8">
                                        <h4 class="mb-4 text-primary-600">{{ number_format($count) }}</h4>
                                        <span class="text-gray-600 text-sm">Level {{ $level }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="row gy-4 mb-24">
            <!-- Recent Users -->
            <div class="col-lg-8">
                <div class="card border-0">
                    <div class="card-header bg-gray-50 border-bottom border-gray-100 py-16 px-24">
                        <div class="flex-between">
                            <h6 class="text-lg fw-semibold mb-0 text-gray-900">
                                <i class="ph ph-clock me-8"></i>
                                Recent Users
                            </h6>
                            <a href="{{ route('admin.users.index') }}" class="text-primary-600 text-sm fw-medium">View All</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="ps-24 py-12 text-gray-600 text-sm fw-medium">User</th>
                                        <th class="py-12 text-gray-600 text-sm fw-medium">Role</th>
                                        <th class="py-12 text-gray-600 text-sm fw-medium">Department</th>
                                        <th class="pe-24 py-12 text-gray-600 text-sm fw-medium">Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\User::latest()->take(5)->get() as $recentUser)
                                        <tr>
                                            <td class="ps-24 py-12">
                                                <div class="flex-align gap-12">
                                                    <div class="w-32 h-32 bg-primary-50 text-primary-600 rounded-circle flex-center text-sm fw-semibold">
                                                        {{ substr($recentUser->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="text-sm fw-semibold mb-0">{{ $recentUser->name }}</h6>
                                                        <span class="text-xs text-gray-500">{{ $recentUser->email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-12">
                                                <span class="badge bg-{{ $recentUser->role == 'student' ? 'primary' : ($recentUser->role == 'admin' ? 'danger' : 'success') }}-50 text-{{ $recentUser->role == 'student' ? 'primary' : ($recentUser->role == 'admin' ? 'danger' : 'success') }}-600 px-8 py-4 rounded-4 text-xs">
                                                    {{ ucfirst($recentUser->role) }}
                                                </span>
                                            </td>
                                            <td class="py-12">
                                                <span class="text-sm text-gray-600">{{ $recentUser->department ?? 'N/A' }}</span>
                                            </td>
                                            <td class="pe-24 py-12">
                                                <span class="text-sm text-gray-500">{{ $recentUser->created_at->diffForHumans() }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="card border-0 h-100">
                    <div class="card-header bg-gray-50 border-bottom border-gray-100 py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0 text-gray-900">
                            <i class="ph ph-lightning me-8"></i>
                            Quick Actions
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="d-grid gap-12">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary radius-8 py-12">
                                <i class="ph ph-user-plus me-8"></i>
                                Create New User
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary radius-8 py-12">
                                <i class="ph ph-users me-8"></i>
                                Manage Users
                            </a>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-success radius-8 py-12">
                                <i class="ph ph-book-open me-8"></i>
                                View Courses
                            </a>
                            <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-warning radius-8 py-12">
                                <i class="ph ph-file-text me-8"></i>
                                View Assignments
                            </a>
                            <a href="{{ route('admin.materials.index') }}" class="btn btn-outline-info radius-8 py-12">
                                <i class="ph ph-files me-8"></i>
                                Manage Materials
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Overview -->
        <div class="row gy-4 mb-24">
            <!-- Course Categories -->
            <div class="col-lg-6">
                <div class="card border-0">
                    <div class="card-header bg-gray-50 border-bottom border-gray-100 py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0 text-gray-900">
                            <i class="ph ph-chart-pie me-8"></i>
                            Course Categories
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        @php
                            $cmpCourses = \App\Models\Course::where('code', 'like', 'CMP%')->count();
                            $staCourses = \App\Models\Course::where('code', 'like', 'STA%')->count();
                            $otherCourses = \App\Models\Course::where('code', 'not like', 'CMP%')->where('code', 'not like', 'STA%')->count();
                        @endphp
                        
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="flex-between mb-8">
                                    <div class="flex-align gap-8">
                                        <div class="w-12 h-12 bg-primary-600 rounded-circle"></div>
                                        <span class="text-gray-600">Computer Science (CMP)</span>
                                    </div>
                                    <span class="text-gray-900 fw-semibold">{{ number_format($cmpCourses) }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="flex-between mb-8">
                                    <div class="flex-align gap-8">
                                        <div class="w-12 h-12 bg-success-600 rounded-circle"></div>
                                        <span class="text-gray-600">Statistics (STA)</span>
                                    </div>
                                    <span class="text-gray-900 fw-semibold">{{ number_format($staCourses) }}</span>
                                </div>
                            </div>
                            @if($otherCourses > 0)
                                <div class="col-12">
                                    <div class="flex-between mb-8">
                                        <div class="flex-align gap-8">
                                            <div class="w-12 h-12 bg-warning-600 rounded-circle"></div>
                                            <span class="text-gray-600">Other Courses</span>
                                        </div>
                                        <span class="text-gray-900 fw-semibold">{{ number_format($otherCourses) }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="col-lg-6">
                <div class="card border-0">
                    <div class="card-header bg-gray-50 border-bottom border-gray-100 py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0 text-gray-900">
                            <i class="ph ph-gear me-8"></i>
                            System Status
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="flex-between p-12 bg-success-50 rounded-8">
                                    <div class="flex-align gap-8">
                                        <i class="ph ph-check-circle text-success-600"></i>
                                        <span class="text-success-600 fw-medium">Database</span>
                                    </div>
                                    <span class="text-success-600 text-sm">Online</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="flex-between p-12 bg-success-50 rounded-8">
                                    <div class="flex-align gap-8">
                                        <i class="ph ph-check-circle text-success-600"></i>
                                        <span class="text-success-600 fw-medium">File Storage</span>
                                    </div>
                                    <span class="text-success-600 text-sm">Available</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="flex-between p-12 bg-info-50 rounded-8">
                                    <div class="flex-align gap-8">
                                        <i class="ph ph-info text-info-600"></i>
                                        <span class="text-info-600 fw-medium">Laravel Version</span>
                                    </div>
                                    <span class="text-info-600 text-sm">{{ app()->version() }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="flex-between p-12 bg-primary-50 rounded-8">
                                    <div class="flex-align gap-8">
                                        <i class="ph ph-info text-primary-600"></i>
                                        <span class="text-primary-600 fw-medium">PHP Version</span>
                                    </div>
                                    <span class="text-primary-600 text-sm">{{ PHP_VERSION }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Courses -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-header bg-gray-50 border-bottom border-gray-100 py-16 px-24">
                        <div class="flex-between">
                            <h6 class="text-lg fw-semibold mb-0 text-gray-900">
                                <i class="ph ph-book-open me-8"></i>
                                Recent Courses
                            </h6>
                            <a href="{{ route('admin.courses.index') }}" class="text-primary-600 text-sm fw-medium">View All</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="ps-24 py-12 text-gray-600 text-sm fw-medium">Course</th>
                                        <th class="py-12 text-gray-600 text-sm fw-medium">Instructor</th>
                                        <th class="py-12 text-gray-600 text-sm fw-medium">Level</th>
                                        <th class="py-12 text-gray-600 text-sm fw-medium">Status</th>
                                        <th class="pe-24 py-12 text-gray-600 text-sm fw-medium">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Course::with('instructor')->latest()->take(5)->get() as $course)
                                        <tr>
                                            <td class="ps-24 py-12">
                                                <div>
                                                    <h6 class="text-sm fw-semibold mb-4">{{ $course->code }} - {{ $course->title }}</h6>
                                                    <span class="text-xs text-gray-500">{{ Str::limit($course->description, 50) }}</span>
                                                </div>
                                            </td>
                                            <td class="py-12">
                                                <span class="text-sm text-gray-900">{{ $course->instructor->name }}</span>
                                            </td>
                                            <td class="py-12">
                                                <span class="badge bg-primary-50 text-primary-600 px-8 py-4 rounded-4 text-xs">
                                                    Level {{ $course->level }}
                                                </span>
                                            </td>
                                            <td class="py-12">
                                                <span class="badge bg-{{ $course->status == 'active' ? 'success' : ($course->status == 'draft' ? 'warning' : 'secondary') }}-50 text-{{ $course->status == 'active' ? 'success' : ($course->status == 'draft' ? 'warning' : 'secondary') }}-600 px-8 py-4 rounded-4 text-xs">
                                                    {{ ucfirst($course->status) }}
                                                </span>
                                            </td>
                                            <td class="pe-24 py-12">
                                                <span class="text-sm text-gray-500">{{ $course->created_at->diffForHumans() }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>