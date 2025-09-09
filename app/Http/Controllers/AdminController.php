<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\Message;

class AdminController extends Controller
{
    public function Dashboard()
    {
        $user = Auth::user();

        $viewData = [
           'meta_title'=> 'Dashboard | LMS Dashboard',
           'meta_desc'=> 'Learning management system',
           'meta_image'=> url('pwa_assets/android-chrome-256x256.png'),
           'user' => $user,
        ];

        return view('admin.dashboard', $viewData);
    }

    public function Showusers(Request $request)
    {
        $user = Auth::user();
        
        // Get search and filter parameters
        $search = $request->get('search');
        $roleFilter = $request->get('role');
        $departmentFilter = $request->get('department');
        $facultyFilter = $request->get('faculty');
        $genderFilter = $request->get('gender');
        $levelFilter = $request->get('level');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $perPage = $request->get('per_page', 15);

        // Validate sort parameters
        $allowedSortFields = ['name', 'email', 'role', 'department', 'faculty', 'created_at', 'matric_or_staff_id'];
        $allowedSortOrders = ['asc', 'desc'];
        $allowedPerPage = [10, 15, 25, 50, 100];
        
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        if (!in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'desc';
        }

        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        // Build the query
        $query = User::query();

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matric_or_staff_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('faculty', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($roleFilter && in_array($roleFilter, [User::ROLE_STUDENT, User::ROLE_INSTRUCTOR, User::ROLE_LECTURER, User::ROLE_ADMIN])) {
            $query->where('role', $roleFilter);
        }

        // Apply department filter
        if ($departmentFilter) {
            $query->where('department', $departmentFilter);
        }

        // Apply faculty filter
        if ($facultyFilter) {
            $query->where('faculty', $facultyFilter);
        }

        // Apply gender filter
        if ($genderFilter && in_array($genderFilter, ['male', 'female', 'other'])) {
            $query->where('gender', $genderFilter);
        }

        // Apply level filter (for students)
        if ($levelFilter && in_array($levelFilter, ['100', '200', '300', '400'])) {
            $query->where('level', $levelFilter);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);

        // Get paginated results
        $users = $query->paginate($perPage)->withQueryString();

        // Get user statistics (with current filters applied)
        $filteredQuery = User::query();
        
        // Apply same filters for statistics
        if ($search) {
            $filteredQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matric_or_staff_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('faculty', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $filteredQuery->where('role', $roleFilter);
        }

        if ($departmentFilter) {
            $filteredQuery->where('department', $departmentFilter);
        }

        if ($facultyFilter) {
            $filteredQuery->where('faculty', $facultyFilter);
        }

        if ($genderFilter) {
            $filteredQuery->where('gender', $genderFilter);
        }

        if ($levelFilter) {
            $filteredQuery->where('level', $levelFilter);
        }

        $userStats = [
            'total' => $filteredQuery->count(),
            'students' => (clone $filteredQuery)->where('role', User::ROLE_STUDENT)->count(),
            'instructors' => (clone $filteredQuery)->whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_LECTURER])->count(),
            'admins' => (clone $filteredQuery)->where('role', User::ROLE_ADMIN)->count(),
        ];

        // Get filter options for dropdowns
        $filterOptions = [
            'departments' => User::whereNotNull('department')
                                ->where('department', '!=', '')
                                ->distinct()
                                ->orderBy('department')
                                ->pluck('department'),
            'faculties' => User::whereNotNull('faculty')
                              ->where('faculty', '!=', '')
                              ->distinct()
                              ->orderBy('faculty')
                              ->pluck('faculty'),
            'roles' => User::getRoles(),
            'genders' => User::getGenders(),
            'levels' => User::getLevels(),
        ];

        // Current filters for the view
        $currentFilters = [
            'search' => $search,
            'role' => $roleFilter,
            'department' => $departmentFilter,
            'faculty' => $facultyFilter,
            'gender' => $genderFilter,
            'level' => $levelFilter,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'per_page' => $perPage,
        ];

        $viewData = [
           'metaTitle'=> 'Manage Users | LMS Dashboard',
           'metaDesc'=> 'Learning management system - User Management',
           'metaImage'=> url('pwa_assets/android-chrome-256x256.png'),
           'user' => $user,
           'users' => $users,
           'userStats' => $userStats,
           'filterOptions' => $filterOptions,
           'currentFilters' => $currentFilters,
           'allowedSortFields' => $allowedSortFields,
           'allowedPerPage' => $allowedPerPage,
        ];

        return view('admin.users', $viewData);
    }

    /**
     * Export users based on current filters
     */
    public function exportUsers(Request $request)
    {
        // Get the same filters as Showusers method
        $search = $request->get('search');
        $roleFilter = $request->get('role');
        $departmentFilter = $request->get('department');
        $facultyFilter = $request->get('faculty');
        $genderFilter = $request->get('gender');
        $levelFilter = $request->get('level');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Build the same query
        $query = User::query();

        // Apply the same filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matric_or_staff_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('faculty', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $query->where('role', $roleFilter);
        }

        if ($departmentFilter) {
            $query->where('department', $departmentFilter);
        }

        if ($facultyFilter) {
            $query->where('faculty', $facultyFilter);
        }

        if ($genderFilter) {
            $query->where('gender', $genderFilter);
        }

        if ($levelFilter) {
            $query->where('level', $levelFilter);
        }

        // Get all results (no pagination for export)
        $users = $query->orderBy($sortBy, $sortOrder)->get();

        // Generate CSV
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Name',
                'Email',
                'Role',
                'Department',
                'Faculty',
                'Phone',
                'Gender',
                'Level',
                'Matric/Staff ID',
                'Address',
                'Birth Date',
                'Created At'
            ]);

            // CSV data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->getRoleDisplayName(),
                    $user->department,
                    $user->faculty,
                    $user->phone,
                    $user->gender ? ucfirst($user->gender) : '',
                    $user->level,
                    $user->matric_or_staff_id,
                    $user->address,
                    $user->birth_date ? $user->birth_date->format('Y-m-d') : '',
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get users data for AJAX requests (for real-time filtering)
     */
    public function getUsersAjax(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        // Use the same logic as Showusers but return JSON
        $search = $request->get('search');
        $roleFilter = $request->get('role');
        $departmentFilter = $request->get('department');
        $facultyFilter = $request->get('faculty');
        $genderFilter = $request->get('gender');
        $levelFilter = $request->get('level');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $perPage = $request->get('per_page', 15);

        $query = User::query();

        // Apply filters (same as Showusers)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matric_or_staff_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('faculty', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $query->where('role', $roleFilter);
        }

        if ($departmentFilter) {
            $query->where('department', $departmentFilter);
        }

        if ($facultyFilter) {
            $query->where('faculty', $facultyFilter);
        }

        if ($genderFilter) {
            $query->where('gender', $genderFilter);
        }

        if ($levelFilter) {
            $query->where('level', $levelFilter);
        }

        $users = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return response()->json([
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ]
        ]);
    }
    
    public function Createusers()
    {
        $user = Auth::user();

        $viewData = [
           'metaTitle'=> 'Create User | LMS Dashboard',
           'metaDesc'=> 'Learning management system - Create New User',
           'metaImage'=> url('pwa_assets/android-chrome-256x256.png'),
           'user' => $user,
           'roles' => User::getRoles(),
           'genders' => User::getGenders(),
           'levels' => User::getLevels(),
        ];

        return view('admin.create-users', $viewData);
    }

    public function storeUser(Request $request)
    {
        try {
            // Base validation rules
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'role' => ['required', 'string', Rule::in([User::ROLE_STUDENT, User::ROLE_INSTRUCTOR, User::ROLE_LECTURER, User::ROLE_ADMIN])],
                'phone' => ['nullable', 'string', 'max:20'],
                'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
                'department' => ['required', 'string', 'max:255'],
                'faculty' => ['required', 'string', 'max:255'],
                'birth_date' => ['nullable', 'date', 'before:today'],
                'address' => ['nullable', 'string', 'max:500'],
            ];

            // Add role-specific validation
            if ($request->role === User::ROLE_STUDENT) {
                $rules['level'] = ['required', 'string', Rule::in(['100', '200', '300', '400'])];
                $rules['matric_or_staff_id'] = ['required', 'string', 'max:50', 'unique:users'];
            } else {
                $rules['matric_or_staff_id'] = ['nullable', 'string', 'max:50', 'unique:users'];
            }

            // Custom messages
            $messages = [
                'name.required' => 'Full name is required.',
                'email.required' => 'Email address is required.',
                'email.unique' => 'This email address is already registered.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'role.required' => 'User role is required.',
                'role.in' => 'Invalid user role selected.',
                'department.required' => 'Department is required.',
                'faculty.required' => 'Faculty is required.',
                'level.required' => 'Level is required for students.',
                'matric_or_staff_id.required' => 'Matric number is required for students.',
                'matric_or_staff_id.unique' => 'This matric/staff ID is already registered.',
            ];

            $validatedData = $request->validate($rules, $messages);

            // Create the user
            $userData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
                'phone' => $validatedData['phone'] ?? null,
                'gender' => $validatedData['gender'] ?? null,
                'department' => $validatedData['department'],
                'faculty' => $validatedData['faculty'],
                'level' => $validatedData['level'] ?? null,
                'matric_or_staff_id' => $validatedData['matric_or_staff_id'] ?? null,
                'birth_date' => $validatedData['birth_date'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'email_verified_at' => now(), // Auto-verify admin created users
            ];

            $newUser = User::create($userData);

            Log::info('User created by admin', [
                'admin_id' => Auth::id(),
                'new_user_id' => $newUser->id,
                'new_user_role' => $newUser->role,
            ]);

            return redirect()->route('admin.users.create')
                           ->with('success', "User '{$newUser->name}' has been created successfully as {$newUser->getRoleDisplayName()}.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to create user', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            return redirect()->back()
                           ->with('error', 'Failed to create user. Please try again.')
                           ->withInput();
        }
    }

    public function deleteUser(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $user = User::findOrFail($userId);

            // Prevent admin from deleting themselves
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account.'
                ], 400);
            }

            // Prevent deletion of the last admin
            if ($user->isAdmin() && User::where('role', User::ROLE_ADMIN)->count() <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the last admin user.'
                ], 400);
            }

            $userName = $user->name;
            $userRole = $user->getRoleDisplayName();

            // Delete the user
            $user->delete();

            Log::info('User deleted by admin', [
                'admin_id' => Auth::id(),
                'deleted_user_id' => $userId,
                'deleted_user_name' => $userName,
                'deleted_user_role' => $userRole,
            ]);

            return response()->json([
                'success' => true,
                'message' => "User '{$userName}' ({$userRole}) has been deleted successfully."
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete user', [
                'admin_id' => Auth::id(),
                'user_id' => $userId ?? null,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user. Please try again.'
            ], 500);
        }
    }
/**
 * Show all courses with search and filter functionality
 */
public function courses(Request $request)
{
    $user = Auth::user();
    
    // Get search and filter parameters
    $search = $request->get('search');
    $statusFilter = $request->get('status');
    $levelFilter = $request->get('level');
    $semesterFilter = $request->get('semester');
    $instructorFilter = $request->get('instructor');
    $departmentFilter = $request->get('department');
    $facultyFilter = $request->get('faculty');
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');
    $perPage = $request->get('per_page', 15);

    // Validate sort parameters
    $allowedSortFields = ['title', 'code', 'level', 'semester', 'status', 'created_at', 'credit_units'];
    $allowedSortOrders = ['asc', 'desc'];
    $allowedPerPage = [10, 15, 25, 50, 100];
    
    if (!in_array($sortBy, $allowedSortFields)) {
        $sortBy = 'created_at';
    }
    
    if (!in_array($sortOrder, $allowedSortOrders)) {
        $sortOrder = 'desc';
    }

    if (!in_array($perPage, $allowedPerPage)) {
        $perPage = 15;
    }

    // Build the query
    $query = Course::with(['instructor', 'assignments', 'materials'])
                   ->withCount(['assignments', 'materials', 'students']);

    // Apply search filter
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('instructor', function($instructorQuery) use ($search) {
                  $instructorQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Apply status filter
    if ($statusFilter && in_array($statusFilter, ['active', 'inactive', 'draft'])) {
        $query->where('status', $statusFilter);
    }

    // Apply level filter
    if ($levelFilter && in_array($levelFilter, ['100', '200', '300', '400'])) {
        $query->where('level', $levelFilter);
    }

    // Apply semester filter
    if ($semesterFilter && in_array($semesterFilter, ['first', 'second'])) {
        $query->where('semester', $semesterFilter);
    }

    // Apply instructor filter
    if ($instructorFilter) {
        $query->where('user_id', $instructorFilter);
    }

    // Apply department filter
    if ($departmentFilter) {
        $query->whereHas('instructor', function($q) use ($departmentFilter) {
            $q->where('department', $departmentFilter);
        });
    }

    // Apply faculty filter
    if ($facultyFilter) {
        $query->whereHas('instructor', function($q) use ($facultyFilter) {
            $q->where('faculty', $facultyFilter);
        });
    }

    // Apply sorting
    if ($sortBy === 'instructor_name') {
        $query->join('users', 'courses.user_id', '=', 'users.id')
              ->orderBy('users.name', $sortOrder)
              ->select('courses.*');
    } else {
        $query->orderBy($sortBy, $sortOrder);
    }

    // Get paginated results
    $courses = $query->paginate($perPage)->withQueryString();

    // Get course statistics (with current filters applied)
    $filteredQuery = Course::query();
    
    // Apply same filters for statistics
    if ($search) {
        $filteredQuery->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('instructor', function($instructorQuery) use ($search) {
                  $instructorQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    if ($statusFilter) {
        $filteredQuery->where('status', $statusFilter);
    }

    if ($levelFilter) {
        $filteredQuery->where('level', $levelFilter);
    }

    if ($semesterFilter) {
        $filteredQuery->where('semester', $semesterFilter);
    }

    if ($instructorFilter) {
        $filteredQuery->where('user_id', $instructorFilter);
    }

    if ($departmentFilter) {
        $filteredQuery->whereHas('instructor', function($q) use ($departmentFilter) {
            $q->where('department', $departmentFilter);
        });
    }

    if ($facultyFilter) {
        $filteredQuery->whereHas('instructor', function($q) use ($facultyFilter) {
            $q->where('faculty', $facultyFilter);
        });
    }

    $courseStats = [
        'total' => $filteredQuery->count(),
        'active' => (clone $filteredQuery)->where('status', 'active')->count(),
        'inactive' => (clone $filteredQuery)->where('status', 'inactive')->count(),
        'draft' => (clone $filteredQuery)->where('status', 'draft')->count(),
    ];

    // Get filter options for dropdowns
    $filterOptions = [
        'instructors' => User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_LECTURER])
                            ->orderBy('name')
                            ->get(['id', 'name', 'department']),
        'departments' => User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_LECTURER])
                             ->whereNotNull('department')
                             ->where('department', '!=', '')
                             ->distinct()
                             ->orderBy('department')
                             ->pluck('department'),
        'faculties' => User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_LECTURER])
                          ->whereNotNull('faculty')
                          ->where('faculty', '!=', '')
                          ->distinct()
                          ->orderBy('faculty')
                          ->pluck('faculty'),
        'levels' => Course::getLevels(),
        'semesters' => Course::getSemesters(),
        'statuses' => Course::getStatuses(),
    ];

    // Current filters for the view
    $currentFilters = [
        'search' => $search,
        'status' => $statusFilter,
        'level' => $levelFilter,
        'semester' => $semesterFilter,
        'instructor' => $instructorFilter,
        'department' => $departmentFilter,
        'faculty' => $facultyFilter,
        'sort_by' => $sortBy,
        'sort_order' => $sortOrder,
        'per_page' => $perPage,
    ];

    $viewData = [
        'metaTitle' => 'Manage Courses | LMS Dashboard',
        'metaDesc' => 'Learning management system - Course Management',
        'metaImage' => url('pwa_assets/android-chrome-256x256.png'),
        'user' => $user,
        'courses' => $courses,
        'courseStats' => $courseStats,
        'filterOptions' => $filterOptions,
        'currentFilters' => $currentFilters,
        'allowedSortFields' => $allowedSortFields,
        'allowedPerPage' => $allowedPerPage,
    ];

    return view('admin.courses', $viewData);
}

/**
 * Delete a course (updated to allow deletion with enrolled students)
 */
public function deleteCourse(Request $request, Course $course)
{
    try {
        $courseTitle = $course->title;
        $courseCode = $course->code;
        $instructorName = $course->instructor->name;
        $enrolledStudents = $course->getEnrolledStudentsCount();

        // Delete related data (including enrolled students)
        $course->assignments()->delete();
        $course->materials()->delete();
        $course->enrollments()->delete(); // This will unenroll all students
        
        // Also detach from pivot table if using belongsToMany relationship
        $course->students()->detach();

        // Delete course image if exists
        if ($course->image && Storage::exists('public/' . $course->image)) {
            Storage::delete('public/' . $course->image);
        }

        // Delete the course
        $course->delete();

        Log::info('Course deleted by admin', [
            'admin_id' => Auth::id(),
            'course_id' => $course->id,
            'course_title' => $courseTitle,
            'course_code' => $courseCode,
            'instructor_name' => $instructorName,
            'enrolled_students_count' => $enrolledStudents,
        ]);

        $message = "Course '{$courseTitle}' ({$courseCode}) by {$instructorName} has been deleted successfully.";
        if ($enrolledStudents > 0) {
            $message .= " {$enrolledStudents} student(s) have been automatically unenrolled.";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to delete course', [
            'admin_id' => Auth::id(),
            'course_id' => $course->id ?? null,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete course. Please try again.'
        ], 500);
    }
}
/**
 * View course details
 */
public function viewCourse(Course $course)
{
    $user = Auth::user();
    
    // Load relationships
    $course->load([
        'instructor',
        'assignments' => function($query) {
            $query->withCount('submissions')->orderBy('created_at', 'desc');
        },
        'materials' => function($query) {
            $query->orderBy('uploaded_at', 'desc');
        },
        'students' => function($query) {
            $query->orderBy('name');
        }
    ]);

    $viewData = [
        'metaTitle' => "Course: {$course->title} | LMS Dashboard",
        'metaDesc' => 'Learning management system - Course Details',
        'metaImage' => url('pwa_assets/android-chrome-256x256.png'),
        'user' => $user,
        'course' => $course,
    ];

    return view('admin.course-details', $viewData);
}

/**
 * Export courses based on current filters
 */
public function exportCourses(Request $request)
{
    // Get the same filters as courses method
    $search = $request->get('search');
    $statusFilter = $request->get('status');
    $levelFilter = $request->get('level');
    $semesterFilter = $request->get('semester');
    $instructorFilter = $request->get('instructor');
    $departmentFilter = $request->get('department');
    $facultyFilter = $request->get('faculty');
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');

    // Build the same query
    $query = Course::with(['instructor'])->withCount(['assignments', 'materials', 'students']);

    // Apply the same filters
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('instructor', function($instructorQuery) use ($search) {
                  $instructorQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    if ($statusFilter) {
        $query->where('status', $statusFilter);
    }

    if ($levelFilter) {
        $query->where('level', $levelFilter);
    }

    if ($semesterFilter) {
        $query->where('semester', $semesterFilter);
    }

    if ($instructorFilter) {
        $query->where('user_id', $instructorFilter);
    }

    if ($departmentFilter) {
        $query->whereHas('instructor', function($q) use ($departmentFilter) {
            $q->where('department', $departmentFilter);
        });
    }

    if ($facultyFilter) {
        $query->whereHas('instructor', function($q) use ($facultyFilter) {
            $q->where('faculty', $facultyFilter);
        });
    }

    // Get all results (no pagination for export)
    $courses = $query->orderBy($sortBy, $sortOrder)->get();

    // Generate CSV
    $filename = 'courses_export_' . date('Y-m-d_H-i-s') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ];

    $callback = function() use ($courses) {
        $file = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($file, [
            'Course Code',
            'Course Title',
            'Level',
            'Semester',
            'Credit Units',
            'Status',
            'Instructor',
            'Department',
            'Faculty',
            'Enrolled Students',
            'Assignments',
            'Materials',
            'Created At'
        ]);

        // CSV data
        foreach ($courses as $course) {
            fputcsv($file, [
                $course->code,
                $course->title,
                $course->level_display,
                $course->semester_display,
                $course->credit_units,
                ucfirst($course->status),
                $course->instructor->name,
                $course->instructor->department,
                $course->instructor->faculty,
                $course->students_count,
                $course->assignments_count,
                $course->materials_count,
                $course->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}/**
 * Show all assignments with search and filter functionality
 */
public function assignments(Request $request)
{
    $user = Auth::user();
    
    // Get search and filter parameters
    $search = $request->get('search');
    $statusFilter = $request->get('status');
    $courseFilter = $request->get('course');
    $instructorFilter = $request->get('instructor');
    $departmentFilter = $request->get('department');
    $facultyFilter = $request->get('faculty');
    $deadlineFilter = $request->get('deadline');
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');
    $perPage = $request->get('per_page', 15);

    // Validate sort parameters
    $allowedSortFields = ['title', 'deadline', 'status', 'created_at'];
    $allowedSortOrders = ['asc', 'desc'];
    $allowedPerPage = [10, 15, 25, 50, 100];
    
    if (!in_array($sortBy, $allowedSortFields)) {
        $sortBy = 'created_at';
    }
    
    if (!in_array($sortOrder, $allowedSortOrders)) {
        $sortOrder = 'desc';
    }

    if (!in_array($perPage, $allowedPerPage)) {
        $perPage = 15;
    }

    // Build the query
    $query = Assignment::with(['instructor', 'course', 'submissions'])
                      ->withCount(['submissions']);

    // Apply search filter
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('instructor', function($instructorQuery) use ($search) {
                  $instructorQuery->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('course', function($courseQuery) use ($search) {
                  $courseQuery->where('title', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
              });
        });
    }

    // Apply status filter
    if ($statusFilter && in_array($statusFilter, ['active', 'draft', 'archived'])) {
        $query->where('status', $statusFilter);
    }

    // Apply course filter
    if ($courseFilter) {
        $query->where('course_id', $courseFilter);
    }

    // Apply instructor filter
    if ($instructorFilter) {
        $query->where('user_id', $instructorFilter);
    }

    // Apply department filter
    if ($departmentFilter) {
        $query->whereHas('instructor', function($q) use ($departmentFilter) {
            $q->where('department', $departmentFilter);
        });
    }

    // Apply faculty filter
    if ($facultyFilter) {
        $query->whereHas('instructor', function($q) use ($facultyFilter) {
            $q->where('faculty', $facultyFilter);
        });
    }

    // Apply deadline filter
    if ($deadlineFilter) {
        switch ($deadlineFilter) {
            case 'overdue':
                $query->where('deadline', '<', now())->where('status', 'active');
                break;
            case 'due_today':
                $query->whereDate('deadline', today());
                break;
            case 'due_this_week':
                $query->whereBetween('deadline', [now(), now()->addWeek()]);
                break;
            case 'due_this_month':
                $query->whereBetween('deadline', [now(), now()->addMonth()]);
                break;
            case 'upcoming':
                $query->where('deadline', '>', now());
                break;
        }
    }

    // Apply sorting
    $query->orderBy($sortBy, $sortOrder);

    // Get paginated results
    $assignments = $query->paginate($perPage)->withQueryString();

    // Get assignment statistics (with current filters applied)
    $filteredQuery = Assignment::query();
    
    // Apply same filters for statistics
    if ($search) {
        $filteredQuery->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('instructor', function($instructorQuery) use ($search) {
                  $instructorQuery->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('course', function($courseQuery) use ($search) {
                  $courseQuery->where('title', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
              });
        });
    }

    if ($statusFilter) {
        $filteredQuery->where('status', $statusFilter);
    }

    if ($courseFilter) {
        $filteredQuery->where('course_id', $courseFilter);
    }

    if ($instructorFilter) {
        $filteredQuery->where('user_id', $instructorFilter);
    }

    if ($departmentFilter) {
        $filteredQuery->whereHas('instructor', function($q) use ($departmentFilter) {
            $q->where('department', $departmentFilter);
        });
    }

    if ($facultyFilter) {
        $filteredQuery->whereHas('instructor', function($q) use ($facultyFilter) {
            $q->where('faculty', $facultyFilter);
        });
    }

    if ($deadlineFilter) {
        switch ($deadlineFilter) {
            case 'overdue':
                $filteredQuery->where('deadline', '<', now())->where('status', 'active');
                break;
            case 'due_today':
                $filteredQuery->whereDate('deadline', today());
                break;
            case 'due_this_week':
                $filteredQuery->whereBetween('deadline', [now(), now()->addWeek()]);
                break;
            case 'due_this_month':
                $filteredQuery->whereBetween('deadline', [now(), now()->addMonth()]);
                break;
            case 'upcoming':
                $filteredQuery->where('deadline', '>', now());
                break;
        }
    }

    $assignmentStats = [
        'total' => $filteredQuery->count(),
        'active' => (clone $filteredQuery)->where('status', 'active')->count(),
        'draft' => (clone $filteredQuery)->where('status', 'draft')->count(),
        'archived' => (clone $filteredQuery)->where('status', 'archived')->count(),
        'overdue' => (clone $filteredQuery)->where('deadline', '<', now())->where('status', 'active')->count(),
    ];

    // Get filter options for dropdowns
    $filterOptions = [
        'instructors' => User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_LECTURER])
                            ->orderBy('name')
                            ->get(['id', 'name', 'department']),
        'courses' => Course::with('instructor')
                          ->orderBy('title')
                          ->get(['id', 'title', 'code', 'user_id']),
        'departments' => User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_LECTURER])
                             ->whereNotNull('department')
                             ->where('department', '!=', '')
                             ->distinct()
                             ->orderBy('department')
                             ->pluck('department'),
        'faculties' => User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_LECTURER])
                          ->whereNotNull('faculty')
                          ->where('faculty', '!=', '')
                          ->distinct()
                          ->orderBy('faculty')
                          ->pluck('faculty'),
        'statuses' => Assignment::getStatuses(),
        'deadlineFilters' => [
            'overdue' => 'Overdue',
            'due_today' => 'Due Today',
            'due_this_week' => 'Due This Week',
            'due_this_month' => 'Due This Month',
            'upcoming' => 'Upcoming',
        ],
    ];

    // Current filters for the view
    $currentFilters = [
        'search' => $search,
        'status' => $statusFilter,
        'course' => $courseFilter,
        'instructor' => $instructorFilter,
        'department' => $departmentFilter,
        'faculty' => $facultyFilter,
        'deadline' => $deadlineFilter,
        'sort_by' => $sortBy,
        'sort_order' => $sortOrder,
        'per_page' => $perPage,
    ];

    $viewData = [
        'metaTitle' => 'Manage Assignments | LMS Dashboard',
        'metaDesc' => 'Learning management system - Assignment Management',
        'metaImage' => url('pwa_assets/android-chrome-256x256.png'),
        'user' => $user,
        'assignments' => $assignments,
        'assignmentStats' => $assignmentStats,
        'filterOptions' => $filterOptions,
        'currentFilters' => $currentFilters,
        'allowedSortFields' => $allowedSortFields,
        'allowedPerPage' => $allowedPerPage,
    ];

    return view('admin.assignments', $viewData);
}

/**
 * Delete an assignment
 */
public function deleteAssignment(Request $request, Assignment $assignment)
{
    try {
        $assignmentTitle = $assignment->title;
        $courseName = $assignment->course->title;
        $instructorName = $assignment->instructor->name;
        $submissionsCount = $assignment->getSubmissionsCount();

        // Delete all submissions for this assignment
        $assignment->submissions()->delete();

        // Delete the assignment
        $assignment->delete();

        Log::info('Assignment deleted by admin', [
            'admin_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'assignment_title' => $assignmentTitle,
            'course_name' => $courseName,
            'instructor_name' => $instructorName,
            'submissions_count' => $submissionsCount,
        ]);

        $message = "Assignment '{$assignmentTitle}' from course '{$courseName}' by {$instructorName} has been deleted successfully.";
        if ($submissionsCount > 0) {
            $message .= " {$submissionsCount} submission(s) were also deleted.";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to delete assignment', [
            'admin_id' => Auth::id(),
            'assignment_id' => $assignment->id ?? null,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete assignment. Please try again.'
        ], 500);
    }
}

/**
 * Export assignments based on current filters
 */
public function exportAssignments(Request $request)
{
    // Get the same filters as assignments method
    $search = $request->get('search');
    $statusFilter = $request->get('status');
    $courseFilter = $request->get('course');
    $instructorFilter = $request->get('instructor');
    $departmentFilter = $request->get('department');
    $facultyFilter = $request->get('faculty');
    $deadlineFilter = $request->get('deadline');
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');

    // Build the same query
    $query = Assignment::with(['instructor', 'course'])->withCount(['submissions']);

    // Apply the same filters
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('instructor', function($instructorQuery) use ($search) {
                  $instructorQuery->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('course', function($courseQuery) use ($search) {
                  $courseQuery->where('title', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
              });
        });
    }

    if ($statusFilter) {
        $query->where('status', $statusFilter);
    }

    if ($courseFilter) {
        $query->where('course_id', $courseFilter);
    }

    if ($instructorFilter) {
        $query->where('user_id', $instructorFilter);
    }

    if ($departmentFilter) {
        $query->whereHas('instructor', function($q) use ($departmentFilter) {
            $q->where('department', $departmentFilter);
        });
    }

    if ($facultyFilter) {
        $query->whereHas('instructor', function($q) use ($facultyFilter) {
            $q->where('faculty', $facultyFilter);
        });
    }

    if ($deadlineFilter) {
        switch ($deadlineFilter) {
            case 'overdue':
                $query->where('deadline', '<', now())->where('status', 'active');
                break;
            case 'due_today':
                $query->whereDate('deadline', today());
                break;
            case 'due_this_week':
                $query->whereBetween('deadline', [now(), now()->addWeek()]);
                break;
            case 'due_this_month':
                $query->whereBetween('deadline', [now(), now()->addMonth()]);
                break;
            case 'upcoming':
                $query->where('deadline', '>', now());
                break;
        }
    }

    // Get all results (no pagination for export)
    $assignments = $query->orderBy($sortBy, $sortOrder)->get();

    // Generate CSV
    $filename = 'assignments_export_' . date('Y-m-d_H-i-s') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ];

    $callback = function() use ($assignments) {
        $file = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($file, [
            'Assignment Title',
            'Course',
            'Course Code',
            'Instructor',
            'Department',
            'Faculty',
            'Status',
            'Deadline',
            'Days Until Deadline',
            'Submissions Count',
            'Created At'
        ]);

        // CSV data
        foreach ($assignments as $assignment) {
            fputcsv($file, [
                $assignment->title,
                $assignment->course->title,
                $assignment->course->code,
                $assignment->instructor->name,
                $assignment->instructor->department,
                $assignment->instructor->faculty,
                ucfirst($assignment->status),
                $assignment->deadline->format('Y-m-d H:i:s'),
                $assignment->getDaysUntilDeadline(),
                $assignment->submissions_count,
                $assignment->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

/**
 * Show all materials with search and filter functionality
 */
public function materials(Request $request)
{
    $user = Auth::user();
    
    // Get search and filter parameters
    $search = $request->get('search');
    $visibilityFilter = $request->get('visibility');
    $fileTypeFilter = $request->get('file_type');
    $courseFilter = $request->get('course');
    $instructorFilter = $request->get('instructor');
    $departmentFilter = $request->get('department');
    $facultyFilter = $request->get('faculty');
    $sortBy = $request->get('sort_by', 'uploaded_at');
    $sortOrder = $request->get('sort_order', 'desc');
    $perPage = $request->get('per_page', 15);

    // Validate sort parameters
    $allowedSortFields = ['title', 'file_type', 'file_size', 'visibility', 'uploaded_at', 'created_at'];
    $allowedSortOrders = ['asc', 'desc'];
    $allowedPerPage = [10, 15, 25, 50, 100];
    
    if (!in_array($sortBy, $allowedSortFields)) {
        $sortBy = 'uploaded_at';
    }
    
    if (!in_array($sortOrder, $allowedSortOrders)) {
        $sortOrder = 'desc';
    }

    if (!in_array($perPage, $allowedPerPage)) {
        $perPage = 15;
    }

    // Build the query
    $query = Material::with(['instructor', 'course']);

    // Apply search filter
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('file_type', 'like', "%{$search}%")
              ->orWhereHas('instructor', function($instructorQuery) use ($search) {
                  $instructorQuery->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('course', function($courseQuery) use ($search) {
                  $courseQuery->where('title', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
              });
        });
    }

    // Apply visibility filter
    if ($visibilityFilter && in_array($visibilityFilter, ['public', 'enrolled', 'private'])) {
        $query->where('visibility', $visibilityFilter);
    }

    // Apply file type filter
    if ($fileTypeFilter) {
        $query->where('file_type', $fileTypeFilter);
    }

    // Apply course filter
    if ($courseFilter) {
        $query->where('course_id', $courseFilter);
    }

    // Apply instructor filter
    if ($instructorFilter) {
        $query->where('user_id', $instructorFilter);
    }

    // Apply department filter
    if ($departmentFilter) {
        $query->whereHas('instructor', function($q) use ($departmentFilter) {
            $q->where('department', $departmentFilter);
        });
    }

    // Apply faculty filter
    if ($facultyFilter) {
        $query->whereHas('instructor', function($q) use ($facultyFilter) {
            $q->where('faculty', $facultyFilter);
        });
    }

    // Apply sorting
    $query->orderBy($sortBy, $sortOrder);

    // Get paginated results
    $materials = $query->paginate($perPage)->withQueryString();

    // Get material statistics (with current filters applied)
    $filteredQuery = Material::query();
    
    // Apply same filters for statistics
    if ($search) {
        $filteredQuery->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('file_type', 'like', "%{$search}%")
              ->orWhereHas('instructor', function($instructorQuery) use ($search) {
                  $instructorQuery->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('course', function($courseQuery) use ($search) {
                  $courseQuery->where('title', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
              });
        });
    }

    if ($visibilityFilter) {
        $filteredQuery->where('visibility', $visibilityFilter);
    }

    if ($fileTypeFilter) {
        $filteredQuery->where('file_type', $fileTypeFilter);
    }

    if ($courseFilter) {
        $filteredQuery->where('course_id', $courseFilter);
    }

    if ($instructorFilter) {
        $filteredQuery->where('user_id', $instructorFilter);
    }

    if ($departmentFilter) {
        $filteredQuery->whereHas('instructor', function($q) use ($departmentFilter) {
            $q->where('department', $departmentFilter);
        });
    }

    if ($facultyFilter) {
        $filteredQuery->whereHas('instructor', function($q) use ($facultyFilter) {
            $q->where('faculty', $facultyFilter);
        });
    }

    $materialStats = [
        'total' => $filteredQuery->count(),
        'public' => (clone $filteredQuery)->where('visibility', 'public')->count(),
        'enrolled' => (clone $filteredQuery)->where('visibility', 'enrolled')->count(),
        'private' => (clone $filteredQuery)->where('visibility', 'private')->count(),
        'total_size' => $filteredQuery->sum('file_size'), // in KB
    ];

    // Get filter options for dropdowns
    $filterOptions = [
        'instructors' => User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_LECTURER])
                            ->orderBy('name')
                            ->get(['id', 'name', 'department']),
        'courses' => Course::with('instructor')
                          ->orderBy('title')
                          ->get(['id', 'title', 'code', 'user_id']),
        'departments' => User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_LECTURER])
                             ->whereNotNull('department')
                             ->where('department', '!=', '')
                             ->distinct()
                             ->orderBy('department')
                             ->pluck('department'),
        'faculties' => User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_LECTURER])
                          ->whereNotNull('faculty')
                          ->where('faculty', '!=', '')
                          ->distinct()
                          ->orderBy('faculty')
                          ->pluck('faculty'),
        'visibilities' => Material::getVisibilityOptions(),
        'fileTypes' => Material::whereNotNull('file_type')
                              ->where('file_type', '!=', '')
                              ->distinct()
                              ->orderBy('file_type')
                              ->pluck('file_type'),
    ];

    // Current filters for the view
    $currentFilters = [
        'search' => $search,
        'visibility' => $visibilityFilter,
        'file_type' => $fileTypeFilter,
        'course' => $courseFilter,
        'instructor' => $instructorFilter,
        'department' => $departmentFilter,
        'faculty' => $facultyFilter,
        'sort_by' => $sortBy,
        'sort_order' => $sortOrder,
        'per_page' => $perPage,
    ];

    $viewData = [
        'metaTitle' => 'Manage Materials | LMS Dashboard',
        'metaDesc' => 'Learning management system - Materials Management',
        'metaImage' => url('pwa_assets/android-chrome-256x256.png'),
        'user' => $user,
        'materials' => $materials,
        'materialStats' => $materialStats,
        'filterOptions' => $filterOptions,
        'currentFilters' => $currentFilters,
        'allowedSortFields' => $allowedSortFields,
        'allowedPerPage' => $allowedPerPage,
    ];

    return view('admin.materials', $viewData);
}

/**
 * Delete a material
 */
public function deleteMaterial(Request $request, Material $material)
{
    try {
        $materialTitle = $material->title;
        $courseName = $material->course->title;
        $instructorName = $material->instructor->name;
        $fileSize = $material->file_size_formatted;

        // Delete the material (file will be deleted automatically via model event)
        $material->delete();

        Log::info('Material deleted by admin', [
            'admin_id' => Auth::id(),
            'material_id' => $material->id,
            'material_title' => $materialTitle,
            'course_name' => $courseName,
            'instructor_name' => $instructorName,
            'file_size' => $fileSize,
        ]);

        $message = "Material '{$materialTitle}' from course '{$courseName}' by {$instructorName} has been deleted successfully.";

        return response()->json([
            'success' => true,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to delete material', [
            'admin_id' => Auth::id(),
            'material_id' => $material->id ?? null,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete material. Please try again.'
        ], 500);
    }
}

/**
 * View material details
 */
public function viewMaterial(Material $material)
{
    $user = Auth::user();
    
    // Load relationships
    $material->load(['instructor', 'course']);

    $viewData = [
        'metaTitle' => "Material: {$material->title} | LMS Dashboard",
        'metaDesc' => 'Learning management system - Material Details',
        'metaImage' => url('pwa_assets/android-chrome-256x256.png'),
        'user' => $user,
        'material' => $material,
    ];

    return view('admin.material-details', $viewData);
}

/**
 * Export materials based on current filters
 */
public function exportMaterials(Request $request)
{
    // Get the same filters as materials method
    $search = $request->get('search');
    $visibilityFilter = $request->get('visibility');
    $fileTypeFilter = $request->get('file_type');
    $courseFilter = $request->get('course');
    $instructorFilter = $request->get('instructor');
    $departmentFilter = $request->get('department');
    $facultyFilter = $request->get('faculty');
    $sortBy = $request->get('sort_by', 'uploaded_at');
    $sortOrder = $request->get('sort_order', 'desc');

    // Build the same query
    $query = Material::with(['instructor', 'course']);

    // Apply the same filters
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('file_type', 'like', "%{$search}%")
              ->orWhereHas('instructor', function($instructorQuery) use ($search) {
                  $instructorQuery->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('course', function($courseQuery) use ($search) {
                  $courseQuery->where('title', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
              });
        });
    }

    if ($visibilityFilter) {
        $query->where('visibility', $visibilityFilter);
    }

    if ($fileTypeFilter) {
        $query->where('file_type', $fileTypeFilter);
    }

    if ($courseFilter) {
        $query->where('course_id', $courseFilter);
    }

    if ($instructorFilter) {
        $query->where('user_id', $instructorFilter);
    }

    if ($departmentFilter) {
        $query->whereHas('instructor', function($q) use ($departmentFilter) {
            $q->where('department', $departmentFilter);
        });
    }

    if ($facultyFilter) {
        $query->whereHas('instructor', function($q) use ($facultyFilter) {
            $q->where('faculty', $facultyFilter);
        });
    }

    // Get all results (no pagination for export)
    $materials = $query->orderBy($sortBy, $sortOrder)->get();

    // Generate CSV
    $filename = 'materials_export_' . date('Y-m-d_H-i-s') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ];

    $callback = function() use ($materials) {
        $file = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($file, [
            'Material Title',
            'Course',
            'Course Code',
            'Instructor',
            'Department',
            'Faculty',
            'File Type',
            'File Size',
            'Visibility',
            'File Exists',
            'Uploaded At',
            'Created At'
        ]);

        // CSV data
        foreach ($materials as $material) {
            fputcsv($file, [
                $material->title,
                $material->course->title,
                $material->course->code,
                $material->instructor->name,
                $material->instructor->department,
                $material->instructor->faculty,
                strtoupper($material->file_type),
                $material->file_size_formatted,
                ucfirst($material->visibility),
                $material->file_exists ? 'Yes' : 'No',
                $material->uploaded_at->format('Y-m-d H:i:s'),
                $material->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
 /**
 * Show admin messages with conversations and filtering
 */
public function messages(Request $request)
{
    $user = Auth::user();
    
    // Get filter parameters
    $filter = $request->get('filter', 'all'); // all, unread, sent
    $search = $request->get('search');
    $conversationWith = $request->get('conversation');
    
    // Get message statistics
    $stats = [
        'total_messages' => Message::forUser($user->id)->count(),
        'unread_count' => Message::where('receiver_id', $user->id)->unread()->count(),
        'sent_count' => Message::where('sender_id', $user->id)->count(),
        'received_count' => Message::where('receiver_id', $user->id)->count(),
    ];
    
    // Build conversations query
    $conversationsQuery = Message::forUser($user->id)
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'desc');
    
    // Apply filters
    if ($filter === 'unread') {
        $conversationsQuery->where('receiver_id', $user->id)->unread();
    } elseif ($filter === 'sent') {
        $conversationsQuery->where('sender_id', $user->id);
    }
    
    // Apply search
    if ($search) {
        $conversationsQuery->where(function($q) use ($search, $user) {
            $q->where('content', 'like', "%{$search}%")
              ->orWhereHas('sender', function($sq) use ($search) {
                  $sq->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('receiver', function($sq) use ($search) {
                  $sq->where('name', 'like', "%{$search}%");
              });
        });
    }
    
    // Get conversations and group by conversation partner
    $allMessages = $conversationsQuery->get();
    $conversations = collect();
    $seenPartners = [];
    
    foreach ($allMessages as $message) {
        $partnerId = $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
        
        if (!in_array($partnerId, $seenPartners)) {
            $conversations->push($message);
            $seenPartners[] = $partnerId;
        }
    }
    
    // Get conversation partner and messages if viewing specific conversation
    $conversationPartner = null;
    $messages = collect();
    
    if ($conversationWith) {
        $conversationPartner = User::find($conversationWith);
        if ($conversationPartner) {
            $messages = Message::conversation($user->id, $conversationWith)
                ->with(['sender', 'receiver'])
                ->get();
            
            // Mark messages as read
            Message::where('sender_id', $conversationWith)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
    }
    
    // Get available users for new conversations (students, instructors, lecturers, other admins)
    $availableUsers = User::where('id', '!=', $user->id)
        ->whereIn('role', [User::ROLE_STUDENT, User::ROLE_INSTRUCTOR, User::ROLE_LECTURER, User::ROLE_ADMIN])
        ->orderBy('role')
        ->orderBy('name')
        ->get(['id', 'name', 'email', 'role', 'avatar']);
    
    $viewData = [
        'metaTitle' => 'Messages | LMS Dashboard',
        'metaDesc' => 'Learning management system - Admin Messages',
        'metaImage' => url('pwa_assets/android-chrome-256x256.png'),
        'user' => $user,
        'conversations' => $conversations,
        'messages' => $messages,
        'conversationPartner' => $conversationPartner,
        'conversationWith' => $conversationWith,
        'stats' => $stats,
        'filter' => $filter,
        'search' => $search,
        'availableUsers' => $availableUsers,
    ];
    
    return view('admin.messages', $viewData);
}

/**
 * Send a message
 */
public function sendMessage(Request $request)
{
    try {
        $user = Auth::user();
        
        // Validation rules
        $rules = [
            'receiver_id' => ['required', 'exists:users,id'],
            'content' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:10240'], // 10MB max
        ];
        
        $messages = [
            'receiver_id.required' => 'Please select a recipient.',
            'receiver_id.exists' => 'Selected recipient does not exist.',
            'content.required' => 'Message content is required.',
            'content.max' => 'Message content cannot exceed 5000 characters.',
            'attachment.file' => 'Attachment must be a valid file.',
            'attachment.max' => 'Attachment size cannot exceed 10MB.',
        ];
        
        $validatedData = $request->validate($rules, $messages);
        
        // Get receiver
        $receiver = User::findOrFail($validatedData['receiver_id']);
        
        // Prevent sending message to self
        if ($receiver->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot send a message to yourself.'
            ], 400);
        }
        
        // Handle file attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            
            // Validate file type
            $allowedMimes = [
                'pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png', 'gif', 
                'mp3', 'mp4', 'wav', 'avi', 'mov', 'zip', 'rar'
            ];
            
            $fileExtension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($fileExtension), $allowedMimes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file type. Allowed types: ' . implode(', ', $allowedMimes)
                ], 400);
            }
            
            // Store file
            $attachmentPath = $file->store('message_attachments', 'public');
        }
        
        // Create message
        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'receiver_role' => $receiver->role,
            'content' => $validatedData['content'],
            'attachment' => $attachmentPath,
            'is_read' => false,
        ]);
        
        // Load relationships for response
        $message->load(['sender', 'receiver']);
        
        Log::info('Message sent by admin', [
            'admin_id' => $user->id,
            'receiver_id' => $receiver->id,
            'receiver_role' => $receiver->role,
            'has_attachment' => !empty($attachmentPath),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'data' => [
                'id' => $message->id,
                'content' => $message->content,
                'time_ago' => $message->time_ago,
                'has_attachment' => $message->hasAttachment(),
                'attachment_name' => $message->getAttachmentName(),
            ]
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Failed to send message', [
            'admin_id' => Auth::id(),
            'error' => $e->getMessage(),
            'request_data' => $request->except(['attachment'])
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to send message. Please try again.'
        ], 500);
    }
}




/**
 * Delete a message
 */
public function deleteMessage(Request $request, Message $message)
{
    try {
        $user = Auth::user();
        
        // Check if user owns the message (sender or receiver)
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this message.'
            ], 403);
        }
        
        // Delete attachment if exists
        if ($message->hasAttachment()) {
            $message->deleteAttachment();
        }
        
        $message->delete();
        
        Log::info('Message deleted by admin', [
            'admin_id' => $user->id,
            'message_id' => $message->id,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully.'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Failed to delete message', [
            'admin_id' => Auth::id(),
            'message_id' => $message->id ?? null,
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete message.'
        ], 500);
    }
}


/**
 * Show admin profile page
 */
public function profile()
{
    $user = Auth::user();
    
    $viewData = [
        'metaTitle' => 'Profile Settings | LMS Dashboard',
        'metaDesc' => 'Learning management system - Admin Profile',
        'metaImage' => url('pwa_assets/android-chrome-256x256.png'),
        'user' => $user,
    ];
    
    return view('admin.profile', $viewData);
}

/**
 * Update admin profile
 */
public function updateProfile(Request $request)
{
    try {
        $user = Auth::user();
        
        // Validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'matric_or_staff_id' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'department' => ['nullable', 'string', 'max:255'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB max
        ];

        $messages = [
            'name.required' => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email address is already taken.',
            'avatar.image' => 'Avatar must be an image file.',
            'avatar.mimes' => 'Avatar must be a JPEG, PNG, JPG, or GIF file.',
            'avatar.max' => 'Avatar file size must not exceed 2MB.',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            
            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validatedData['avatar'] = $avatarPath;
        }

        // Update user profile
        $user->update($validatedData);

        Log::info('Admin profile updated', [
            'admin_id' => $user->id,
            'updated_fields' => array_keys($validatedData)
        ]);

        return redirect()->route('admin.profile.index')
                       ->with('success', 'Profile updated successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
                       ->withErrors($e->errors())
                       ->withInput();
    } catch (\Exception $e) {
        Log::error('Failed to update admin profile', [
            'admin_id' => Auth::id(),
            'error' => $e->getMessage()
        ]);

        return redirect()->back()
                       ->with('error', 'Failed to update profile. Please try again.')
                       ->withInput();
    }
}

/**
 * Update admin password
 */
public function updatePassword(Request $request)
{
    try {
        $user = Auth::user();
        
        // Validation rules
        $rules = [
            'current_password' => ['required', 'string'],
            'password' => [
                'required', 
                'string', 
                'min:8', 
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
        ];

        $messages = [
            'current_password.required' => 'Current password is required.',
            'password.required' => 'New password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Check if current password is correct
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return redirect()->back()
                           ->withErrors(['current_password' => 'Current password is incorrect.'])
                           ->withInput();
        }

        // Check if new password is different from current
        if (Hash::check($validatedData['password'], $user->password)) {
            return redirect()->back()
                           ->withErrors(['password' => 'New password must be different from current password.'])
                           ->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($validatedData['password'])
        ]);

        Log::info('Admin password updated', [
            'admin_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('admin.profile.index')
                       ->with('success', 'Password updated successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
                       ->withErrors($e->errors())
                       ->withInput();
    } catch (\Exception $e) {
        Log::error('Failed to update admin password', [
            'admin_id' => Auth::id(),
            'error' => $e->getMessage()
        ]);

        return redirect()->back()
                       ->with('error', 'Failed to update password. Please try again.');
    }
}
/**
 * Mark messages as read
 */
public function markAsRead(Request $request)
{
    try {
        $user = Auth::user();
        $messageIds = $request->input('message_ids', []);
        
        if (empty($messageIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No messages specified.'
            ], 400);
        }
        
        $updated = Message::whereIn('id', $messageIds)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => "Marked {$updated} message(s) as read.",
            'updated_count' => $updated
        ]);
        
    } catch (\Exception $e) {
        Log::error('Failed to mark messages as read', [
            'admin_id' => Auth::id(),
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to mark messages as read.'
        ], 500);
    }
}

/**
 * Mark all messages as read
 */
public function markAllAsRead(Request $request)
{
    try {
        $user = Auth::user();
        $conversationWith = $request->input('conversation_with');
        
        $query = Message::where('receiver_id', $user->id)->where('is_read', false);
        
        if ($conversationWith) {
            $query->where('sender_id', $conversationWith);
        }
        
        $updated = $query->update(['is_read' => true]);
        
        $message = $conversationWith 
            ? "Marked all messages in conversation as read."
            : "Marked all messages as read.";
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'updated_count' => $updated
        ]);
        
    } catch (\Exception $e) {
        Log::error('Failed to mark all messages as read', [
            'admin_id' => Auth::id(),
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to mark messages as read.'
        ], 500);
    }
}

/**
 * Get message statistics for admin dashboard
 */
public function getMessageStats()
{
    $user = Auth::user();
    
    $stats = [
        'total_messages' => Message::forUser($user->id)->count(),
        'unread_count' => Message::where('receiver_id', $user->id)->unread()->count(),
        'sent_count' => Message::where('sender_id', $user->id)->count(),
        'received_count' => Message::where('receiver_id', $user->id)->count(),
        'conversations_count' => Message::getConversationsForUser($user->id)->count(),
    ];
    
    return response()->json([
        'success' => true,
        'data' => $stats
    ]);
}

/**
 * View a specific message
 */
public function viewMessage(Message $message)
{
    $user = Auth::user();
    
    // Check if user has permission to view this message
    if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
        abort(403, 'You do not have permission to view this message.');
    }
    
    // Mark as read if user is the receiver
    if ($message->receiver_id === $user->id && !$message->is_read) {
        $message->markAsRead();
    }
    
    // Load relationships
    $message->load(['sender', 'receiver']);
    
    $viewData = [
        'metaTitle' => 'Message Details | LMS Dashboard',
        'metaDesc' => 'Learning management system - Message Details',
        'metaImage' => url('pwa_assets/android-chrome-256x256.png'),
        'user' => $user,
        'message' => $message,
    ];
    
    return view('admin.message-details', $viewData);
}

}