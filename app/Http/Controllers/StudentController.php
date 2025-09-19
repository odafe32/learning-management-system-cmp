<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\MaterialRequest;
use Illuminate\Support\Facades\DB; 
use App\Models\Course;
use App\Models\Material;
use App\Models\Submission;
use App\Models\CourseStudent;
use App\Models\User; 
use App\Models\Message;
use App\Http\Requests\AssignmentRequest;
use App\Models\Assignment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function Dashboard()
    {
        try {
            $user = Auth::user();
            
            // Get student statistics
            $enrolledCoursesCount = $user->enrolledCourses()->count();
            $pendingAssignments = $this->getPendingAssignmentsCount();
            $recentGrades = $this->getRecentGrades();
            $upcomingDeadlines = $this->getUpcomingDeadlines();
            
            $viewData = [
                'meta_title' => 'Dashboard | Student Portal',
                'meta_desc' => 'Student Learning Management System Dashboard',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'enrolledCoursesCount' => $enrolledCoursesCount,
                'pendingAssignments' => $pendingAssignments,
                'recentGrades' => $recentGrades,
                'upcomingDeadlines' => $upcomingDeadlines,
                'user' => $user
            ];

            return view('student.dashboard', $viewData);
        } catch (\Exception $e) {
            Log::error('Student Dashboard Error: ' . $e->getMessage());
            return view('student.dashboard', [
                'meta_title' => 'Dashboard | Student Portal',
                'meta_desc' => 'Student Learning Management System Dashboard',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            ]);
        }
    }

    // Profile Management
    public function profile()
    {
        $viewData = [
            'meta_title' => 'Profile | Student Portal',
            'meta_desc'  => 'Manage your profile information',
            'meta_image' => url('assets/images/logo/logo.png'),
            'user' => Auth::user(),
        ];

        return view('student.profile', $viewData);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = Auth::user();
            $data = $request->validated();

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                // Store new avatar
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            // Update user profile
            $user->update($data);

            return redirect()->route('student.profile')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('student.profile')
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = Auth::user();

            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->route('student.profile')
                    ->withErrors(['current_password' => 'Current password is incorrect.'])
                    ->withInput();
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('student.profile')
                ->with('success', 'Password updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('student.profile')
                ->with('error', 'Failed to update password. Please try again.');
        }
    }
  /**
     * Show available courses for enrollment based on student's level and semester
     */
    public function EnrollCourse(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get current semester (you might want to make this dynamic)
            $currentSemester = $request->get('semester', 'first'); // Default to first semester
            
            // Get available courses based on student's level and semester
            $availableCourses = Course::active()
                ->where('level', $user->level)
                ->where('semester', $currentSemester)
                ->whereNotIn('id', function($query) use ($user) {
                    $query->select('course_id')
                          ->from('course_student')
                          ->where('user_id', $user->id)
                          ->where('status', 'active');
                })
                ->with(['instructor', 'materials', 'assignments'])
                ->withCount(['students', 'assignments', 'materials'])
                ->orderBy('code')
                ->paginate(12);

            // Get enrolled course IDs for comparison
            $enrolledCourseIds = $user->enrolledCourses()->pluck('course_id')->toArray();

            $viewData = [
                'meta_title' => 'Enroll in Courses | Student Portal',
                'meta_desc' => 'Browse and enroll in available courses for your level',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'availableCourses' => $availableCourses,
                'enrolledCourseIds' => $enrolledCourseIds,
                'currentSemester' => $currentSemester,
                'userLevel' => $user->level,
                'semesters' => Course::getSemesters(),
                'user' => $user
            ];

            return view('student.enroll-courses', $viewData);
        } catch (\Exception $e) {
            Log::error('Enroll Course Page Error: ' . $e->getMessage());
            return redirect()->route('student.dashboard')
                ->with('error', 'Unable to load available courses. Please try again.');
        }
    }

    /**
     * Enroll student in a course (AJAX endpoint)
     */
    public function enrollInCourse(Request $request, Course $course)
    {
        try {
            $user = Auth::user();

            // Validate that the course is available for this student
            if ($course->level !== $user->level) {
                return response()->json([
                    'success' => false,
                    'message' => 'This course is not available for your level.'
                ], 400);
            }

            if ($course->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'This course is not currently active.'
                ], 400);
            }

            // Check if already enrolled
            if ($user->isEnrolledIn($course->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already enrolled in this course.'
                ], 400);
            }

            // Enroll the student
            $enrolled = $user->enrollInCourse($course->id);

            if ($enrolled) {
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully enrolled in ' . $course->title
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to enroll in course. Please try again.'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Course Enrollment Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while enrolling. Please try again.'
            ], 500);
        }
    }

    /**
     * Show enrolled courses
     */
    public function ShowsCourses(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get enrolled courses with related data
            $enrolledCourses = $user->enrolledCourses()
                ->with([
                    'instructor',
                    'assignments' => function($query) {
                        $query->where('status', 'active')
                              ->orderBy('deadline', 'asc')
                              ->limit(3);
                    },
                    'materials' => function($query) {
                        $query->orderBy('uploaded_at', 'desc')
                              ->limit(3);
                    }
                ])
                ->withCount(['assignments', 'materials'])
                ->orderBy('code')
                ->paginate(9);

            // Get recent activities for each course
            foreach ($enrolledCourses as $course) {
                $course->recentActivities = $this->getCourseRecentActivities($course->id);
                $course->upcomingAssignments = $course->assignments()
                    ->where('status', 'active')
                    ->where('deadline', '>', now())
                    ->orderBy('deadline', 'asc')
                    ->limit(3)
                    ->get();
            }

            $viewData = [
                'meta_title' => 'My Courses | Student Portal',
                'meta_desc' => 'View your enrolled courses and access materials',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'enrolledCourses' => $enrolledCourses,
                'totalEnrolled' => $user->enrolledCourses()->count(),
                'user' => $user
            ];

            return view('student.courses', $viewData);
        } catch (\Exception $e) {
            Log::error('Student Courses Error: ' . $e->getMessage());
            return view('student.courses', [
                'meta_title' => 'My Courses | Student Portal',
                'meta_desc' => 'View your enrolled courses',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'enrolledCourses' => collect(),
                'totalEnrolled' => 0
            ]);
        }
    }

 
/**
 * Show detailed course view with materials, assignments, and announcements
 */
public function viewCourse(Course $course)
{
    try {
        $user = Auth::user();

        // Check if student is enrolled in this course
        if (!$user->isEnrolledIn($course->id)) {
            return redirect()->route('student.courses.index')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Get course materials
        $materials = $course->materials()
            ->orderBy('uploaded_at', 'desc')
            ->paginate(10, ['*'], 'materials_page');

        // Get course assignments
        $assignments = $course->assignments()
            ->where('status', 'active')
            ->with(['submissions' => function($query) use ($user) {
                $query->where('student_id', $user->id);
            }])
            ->orderBy('deadline', 'asc')
            ->paginate(10, ['*'], 'assignments_page');

        // Get recent announcements (placeholder)
        $announcements = collect();

        // Get student's submissions for this course
        $submissions = Submission::whereHas('assignment', function($query) use ($course) {
            $query->where('course_id', $course->id);
        })
        ->where('student_id', $user->id)
        ->with('assignment')
        ->orderBy('submitted_at', 'desc')
        ->limit(5)
        ->get();

        // Get total enrolled courses count for the student
        $totalEnrolled = $user->enrolledCourses()->count();

        // Get enrolled courses for statistics
        $enrolledCourses = $user->enrolledCourses()
            ->withCount(['assignments', 'materials'])
            ->get();

        $viewData = [
            'meta_title' => $course->title . ' | Student Portal',
            'meta_desc' => 'Course materials, assignments and announcements for ' . $course->title,
            'meta_image' => $course->image_url,
            'course' => $course,
            'materials' => $materials,
            'assignments' => $assignments,
            'announcements' => $announcements,
            'submissions' => $submissions,
            'totalEnrolled' => $totalEnrolled,
            'enrolledCourses' => $enrolledCourses,
            'user' => $user
        ];

        return view('student.course-detail', $viewData);
    } catch (\Exception $e) {
        Log::error('Course Detail Error: ' . $e->getMessage());
        return redirect()->route('student.courses.index')
            ->with('error', 'Unable to load course details.');
    }
}

    // Helper method for course activities
    private function getCourseRecentActivities($courseId)
    {
        try {
            // Get recent materials and assignments
            $recentMaterials = Material::where('course_id', $courseId)
                ->orderBy('uploaded_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function($material) {
                    return [
                        'type' => 'material',
                        'title' => $material->title,
                        'date' => $material->uploaded_at,
                        'url' => route('student.materials.index')
                    ];
                });

            $recentAssignments = Assignment::where('course_id', $courseId)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function($assignment) {
                    return [
                        'type' => 'assignment',
                        'title' => $assignment->title,
                        'date' => $assignment->created_at,
                        'deadline' => $assignment->deadline,
                        'url' => route('student.assignments.index')
                    ];
                });

            return $recentMaterials->merge($recentAssignments)
                ->sortByDesc('date')
                ->take(5);
        } catch (\Exception $e) {
            return collect();
        }
    }
    // ==================== ASSIGNMENT METHODS ====================




/**
 * Show materials with search and filter functionality
 */
public function viewMaterials(Request $request)
{
    try {
        $user = Auth::user();
        
        // Get search and filter parameters
        $search = $request->get('search');
        $courseId = $request->get('course');
        $fileType = $request->get('file_type');
        $sortBy = $request->get('sort_by', 'uploaded_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Get materials from enrolled courses
        $materialsQuery = Material::whereHas('course', function($query) use ($user) {
            $query->whereIn('id', $user->enrolledCourses()->pluck('course_id'));
        })
        ->with(['course', 'instructor'])
        ->where('visibility', '!=', 'private');
        
        // Apply search filter
        if ($search) {
            $materialsQuery->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply course filter
        if ($courseId) {
            $materialsQuery->where('course_id', $courseId);
        }
        
        // Apply file type filter
        if ($fileType) {
            $materialsQuery->where('file_type', $fileType);
        }
        
        // Apply sorting
        $materialsQuery->orderBy($sortBy, $sortOrder);
        
        $materials = $materialsQuery->paginate(12);
        
        // Get enrolled courses for filter dropdown
        $enrolledCourses = $user->enrolledCourses()
            ->withCount('materials')
            ->get();
        
        // Get available file types for filter
        $availableFileTypes = Material::whereHas('course', function($query) use ($user) {
            $query->whereIn('id', $user->enrolledCourses()->pluck('course_id'));
        })
        ->distinct()
        ->pluck('file_type')
        ->filter()
        ->sort();
        
        $viewData = [
            'meta_title' => 'Course Materials | Student Portal',
            'meta_desc' => 'Access your course materials and resources',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'materials' => $materials,
            'enrolledCourses' => $enrolledCourses,
            'availableFileTypes' => $availableFileTypes,
            'currentFilters' => [
                'search' => $search,
                'course' => $courseId,
                'file_type' => $fileType,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'user' => $user
        ];

        return view('student.materials', $viewData);
        
    } catch (\Exception $e) {
        Log::error('Student Materials Error: ' . $e->getMessage());
        
        // Return safe fallback data
        $viewData = [
            'meta_title' => 'Course Materials | Student Portal',
            'meta_desc' => 'Access your course materials',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'materials' => collect(),
            'enrolledCourses' => collect(),
            'availableFileTypes' => collect(),
            'currentFilters' => [
                'search' => '',
                'course' => '',
                'file_type' => '',
                'sort_by' => 'uploaded_at',
                'sort_order' => 'desc',
            ],
            'user' => Auth::user()
        ];
        
        return view('student.materials', $viewData)->with('error', 'Unable to load materials. Please try again.');
    }
}

/**
 * Show single material details
 */
public function showMaterial(Material $material)
{
    try {
        $user = Auth::user();
        
        // Check if student is enrolled in the course
        if (!$user->isEnrolledIn($material->course_id)) {
            return redirect()->route('student.materials.index')
                ->with('error', 'You are not enrolled in this course.');
        }
        
        // Check visibility
        if ($material->visibility === 'private') {
            return redirect()->route('student.materials.index')
                ->with('error', 'This material is not available.');
        }
        
        $viewData = [
            'meta_title' => $material->title . ' | Course Materials',
            'meta_desc' => 'View course material: ' . $material->title,
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'material' => $material,
            'user' => $user
        ];

        return view('student.material-detail', $viewData);
    } catch (\Exception $e) {
        Log::error('Material Detail Error: ' . $e->getMessage());
        return redirect()->route('student.materials.index')
            ->with('error', 'Unable to load material details.');
    }
}

/**
 * Download material file
 */
public function downloadMaterial(Material $material)
{
    try {
        $user = Auth::user();
        
        // Check if student is enrolled in the course
        if (!$user->isEnrolledIn($material->course_id)) {
            abort(403, 'You are not enrolled in this course.');
        }
        
        // Check visibility
        if ($material->visibility === 'private') {
            abort(403, 'This material is not available.');
        }
        
        // Check if file exists
        if (!$material->file_exists) {
            abort(404, 'File not found.');
        }
        
        $filePath = storage_path('app/public/' . $material->file_path);
        $fileName = $material->title . '.' . $material->file_type;
        
        return response()->download($filePath, $fileName);
        
    } catch (\Exception $e) {
        Log::error('Material Download Error: ' . $e->getMessage());
        abort(500, 'Unable to download file.');
    }
}

/**
 * Stream material file (for viewing in browser)
 */
public function streamMaterial(Material $material)
{
    try {
        $user = Auth::user();
        
        // Check if student is enrolled in the course
        if (!$user->isEnrolledIn($material->course_id)) {
            abort(403, 'You are not enrolled in this course.');
        }
        
        // Check visibility
        if ($material->visibility === 'private') {
            abort(403, 'This material is not available.');
        }
        
        // Check if file exists
        if (!$material->file_exists) {
            abort(404, 'File not found.');
        }
        
        $filePath = storage_path('app/public/' . $material->file_path);
        
        // Get file info
        $mimeType = Storage::disk('public')->mimeType($material->file_path);
        $fileSize = Storage::disk('public')->size($material->file_path);
        
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Content-Disposition' => 'inline; filename="' . $material->title . '.' . $material->file_type . '"'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Material Stream Error: ' . $e->getMessage());
        abort(500, 'Unable to stream file.');
    }
}

/**
 * AJAX search materials
 */
public function searchMaterials(Request $request)
{
    try {
        $user = Auth::user();
        $search = $request->get('q');
        $courseId = $request->get('course_id');
        
        $materialsQuery = Material::whereHas('course', function($query) use ($user) {
            $query->whereIn('id', $user->enrolledCourses()->pluck('course_id'));
        })
        ->with(['course', 'instructor'])
        ->where('visibility', '!=', 'private');
        
        if ($search) {
            $materialsQuery->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($courseId) {
            $materialsQuery->where('course_id', $courseId);
        }
        
        $materials = $materialsQuery->limit(10)->get();
        
        return response()->json([
            'success' => true,
            'materials' => $materials->map(function($material) {
                return [
                    'id' => $material->id,
                    'title' => $material->title,
                    'description' => Str::limit($material->description, 100),
                    'course_name' => $material->course->title,
                    'file_type' => $material->file_type,
                    'file_size' => $material->file_size_formatted,
                    'uploaded_at' => $material->uploaded_at ? $material->uploaded_at->format('M d, Y') : 'N/A',
                    'view_url' => route('student.materials.show', $material->id),
                    'download_url' => route('student.materials.download', $material->id),
                    'file_icon' => $material->file_icon,
                ];
            })
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Search failed. Please try again.'
        ], 500);
    }
}

/**
 * Show assignments from enrolled courses with filtering and status
 */
public function ShowAssignments(Request $request)
{
    try {
        $user = Auth::user();
        
        // Get filter parameters
        $courseId = $request->get('course');
        $status = $request->get('status', 'all'); // all, pending, submitted, overdue
        $search = $request->get('search');
        
        // Base query for assignments from enrolled courses
        $assignmentsQuery = Assignment::whereHas('course', function($query) use ($user) {
            $query->whereIn('id', $user->enrolledCourses()->pluck('course_id'));
        })
        ->with([
            'course',
            'submissions' => function($query) use ($user) {
                $query->where('student_id', $user->id);
            }
        ])
        ->where('status', 'active');

        // Apply course filter
        if ($courseId) {
            $assignmentsQuery->where('course_id', $courseId);
        }

        // Apply search filter
        if ($search) {
            $assignmentsQuery->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $assignments = $assignmentsQuery->orderBy('deadline', 'asc')->paginate(12);

        // Categorize assignments based on status
        $categorizedAssignments = [
            'pending' => collect(),
            'submitted' => collect(),
            'overdue' => collect(),
            'graded' => collect()
        ];

        foreach ($assignments as $assignment) {
            $submission = $assignment->submissions->first();
            
            if ($submission) {
                if ($submission->status === 'graded') {
                    $categorizedAssignments['graded']->push($assignment);
                } else {
                    $categorizedAssignments['submitted']->push($assignment);
                }
            } elseif ($assignment->deadline && $assignment->deadline < now()) {
                $categorizedAssignments['overdue']->push($assignment);
            } else {
                $categorizedAssignments['pending']->push($assignment);
            }
        }

        // Filter by status if specified
        if ($status !== 'all') {
            $assignments = $assignments->filter(function($assignment) use ($status) {
                $submission = $assignment->submissions->first();
                
                return match($status) {
                    'pending' => !$submission && (!$assignment->deadline || $assignment->deadline >= now()),
                    'submitted' => $submission && $submission->status !== 'graded',
                    'overdue' => !$submission && $assignment->deadline && $assignment->deadline < now(),
                    'graded' => $submission && $submission->status === 'graded',
                    default => true
                };
            });
        }

        // Get enrolled courses for filter
        $enrolledCourses = $user->enrolledCourses()->get();

        $viewData = [
            'meta_title' => 'My Assignments | Student Portal',
            'meta_desc' => 'View and manage your course assignments',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'assignments' => $assignments,
            'categorizedAssignments' => $categorizedAssignments,
            'courses' => $enrolledCourses, // Fix: Pass as 'courses' instead of 'enrolledCourses'
            'enrolledCourses' => $enrolledCourses, // Keep both for compatibility
            'currentFilters' => [
                'course' => $courseId,
                'status' => $status,
                'search' => $search
            ],
            'user' => $user
        ];

        return view('student.assignments', $viewData);
    } catch (\Exception $e) {
        Log::error('Student Assignments Error: ' . $e->getMessage());
        return view('student.assignments', [
            'meta_title' => 'My Assignments | Student Portal',
            'meta_desc' => 'View your course assignments',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'assignments' => collect(),
            'categorizedAssignments' => ['pending' => collect(), 'submitted' => collect(), 'overdue' => collect(), 'graded' => collect()],
            'courses' => collect(), // Fix: Add empty courses collection
            'enrolledCourses' => collect(),
            'currentFilters' => ['course' => '', 'status' => 'all', 'search' => ''],
            'user' => Auth::user()
        ]);
    }
}

/**
 * Show detailed assignment view for student
 */
/**
 * Show detailed assignment view for student
 */
public function viewAssignment(Assignment $assignment)
{
    try {
        $user = Auth::user();

        // Check if student is enrolled in the course
        if (!$user->isEnrolledIn($assignment->course_id)) {
            return redirect()->route('student.assignments.index')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Check if assignment is active
        if ($assignment->status !== 'active') {
            return redirect()->route('student.assignments.index')
                ->with('error', 'This assignment is not available.');
        }

        // Get student's submission for this assignment
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->first();

        // Determine assignment status
        $canSubmit = !$submission && (!$assignment->deadline || $assignment->deadline > now());
        $isOverdue = $assignment->deadline && $assignment->deadline < now();

        // Get assignment statistics (optional - for student to see class progress)
        $totalSubmissions = $assignment->submissions()->count();
        $totalEnrolled = $assignment->course->students()->count();

        $viewData = [
            'meta_title' => $assignment->title . ' | Assignment Details',
            'meta_desc' => 'View assignment details and submit your work',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'assignment' => $assignment,
            'submission' => $submission,
            'canSubmit' => $canSubmit,
            'isOverdue' => $isOverdue,
            'totalSubmissions' => $totalSubmissions,
            'totalEnrolled' => $totalEnrolled,
            'user' => $user
        ];

        return view('student.assignment-detail', $viewData);
    } catch (\Exception $e) {
        Log::error('Assignment Detail Error: ' . $e->getMessage());
        return redirect()->route('student.assignments.index')
            ->with('error', 'Unable to load assignment details.');
    }
}



/**
 * Show assignment submission form
 */
public function showSubmissionForm(Assignment $assignment)
{
    try {
        $user = Auth::user();

        // Check if student is enrolled in the course
        if (!$user->isEnrolledIn($assignment->course_id)) {
            return redirect()->route('student.assignments.index')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Check if assignment is active
        if ($assignment->status !== 'active') {
            return redirect()->route('student.assignments.index')
                ->with('error', 'This assignment is not available.');
        }

        // Check if already submitted
        $existingSubmission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('student.assignments.show', $assignment)
                ->with('info', 'You have already submitted this assignment.');
        }

        // Check if deadline has passed
        if ($assignment->deadline < now()) {
            return redirect()->route('student.assignments.show', $assignment)
                ->with('error', 'The deadline for this assignment has passed.');
        }

        $viewData = [
            'meta_title' => 'Submit Assignment: ' . $assignment->title,
            'meta_desc' => 'Submit your assignment work',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'assignment' => $assignment,
            'user' => $user
        ];

        return view('student.submit-assignment', $viewData);
    } catch (\Exception $e) {
        Log::error('Assignment Submission Form Error: ' . $e->getMessage());
        return redirect()->route('student.assignments.index')
            ->with('error', 'Unable to load submission form.');
    }
}
/**
 * Show assignment submission form
 */
public function SubmitAssignments(Assignment $assignment = null)
{
    try {
        $user = Auth::user();

        // If no specific assignment is provided, show assignment selection
        if (!$assignment) {
            // Get all pending assignments for the student
            $pendingAssignments = Assignment::whereHas('course', function($query) use ($user) {
                $query->whereIn('id', $user->enrolledCourses()->pluck('course_id'));
            })
            ->whereDoesntHave('submissions', function($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->where('status', 'active')
            ->where(function($query) {
                $query->whereNull('deadline')
                      ->orWhere('deadline', '>', now());
            })
            ->with('course')
            ->orderBy('deadline', 'asc')
            ->get();

            $viewData = [
                'meta_title' => 'Submit Assignments | Student Portal',
                'meta_desc' => 'Choose an assignment to submit',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'assignments' => $pendingAssignments,
                'user' => $user
            ];

            // Use a different view for assignment selection
            return view('student.submit-assignments-list', $viewData);
        }

        // If specific assignment is provided, show submission form
        // Check if student is enrolled in the course
        if (!$user->isEnrolledIn($assignment->course_id)) {
            return redirect()->route('student.assignments.index')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Check if assignment is active
        if ($assignment->status !== 'active') {
            return redirect()->route('student.assignments.index')
                ->with('error', 'This assignment is not available.');
        }

        // Check if already submitted
        $existingSubmission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('student.assignments.show', $assignment)
                ->with('info', 'You have already submitted this assignment.');
        }

        // Check if deadline has passed
        if ($assignment->deadline && $assignment->deadline < now()) {
            return redirect()->route('student.assignments.show', $assignment)
                ->with('error', 'The deadline for this assignment has passed.');
        }

        $viewData = [
            'meta_title' => 'Submit Assignment: ' . $assignment->title,
            'meta_desc' => 'Submit your assignment work',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'assignment' => $assignment,
            'user' => $user
        ];

        // Use the existing view for single assignment submission
        return view('student.submit-assignments', $viewData);

    } catch (\Exception $e) {
        Log::error('Assignment Submission Form Error: ' . $e->getMessage());
        return redirect()->route('student.assignments.index')
            ->with('error', 'Unable to load submission form.');
    }
}

/**
 * Handle assignment submission
 */
/**
 * Handle assignment submission
 */
public function submitAssignment(Request $request, Assignment $assignment)
{
    try {
        $user = Auth::user();

        // Validate access
        if (!$user->isEnrolledIn($assignment->course_id)) {
            return redirect()->route('student.assignments.index')
                ->with('error', 'You are not enrolled in this course.');
        }

        if ($assignment->status !== 'active') {
            return redirect()->route('student.assignments.index')
                ->with('error', 'This assignment is not available.');
        }

        // Check if already submitted
        $existingSubmission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('student.assignments.show', $assignment)
                ->with('info', 'You have already submitted this assignment.');
        }

        // Check deadline
        if ($assignment->deadline && $assignment->deadline < now()) {
            return redirect()->route('student.assignments.show', $assignment)
                ->with('error', 'The deadline for this assignment has passed.');
        }

        // Validate request - Fix field name here
        $validator = Validator::make($request->all(), [
            'code_submission' => 'nullable|string|max:50000',  // ✅ Correct field name
            'submission_files.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,zip,rar,jpg,jpeg,png',
            'submission_text' => 'nullable|string|max:10000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Fix the validation check - use correct field names
        $hasCode = !empty(trim($request->code_submission ?? ''));  // ✅ Fixed
        $hasFiles = $request->hasFile('submission_files');         // ✅ Fixed
        $hasText = !empty(trim($request->submission_text ?? ''));  // ✅ Added text support

        // Debug log
        Log::info('Submission validation:', [
            'hasCode' => $hasCode,
            'hasFiles' => $hasFiles,
            'hasText' => $hasText,
            'code_length' => strlen($request->code_submission ?? ''),
            'files_count' => $hasFiles ? count($request->file('submission_files')) : 0,
            'text_length' => strlen($request->submission_text ?? ''),
        ]);

        if (!$hasCode && !$hasFiles && !$hasText) {
            return redirect()->back()
                ->with('error', 'Please provide at least one form of submission (code, file, or text).')
                ->withInput();
        }

        DB::beginTransaction();

        // Handle file uploads (multiple files)
        $filePaths = [];
        if ($hasFiles) {
            foreach ($request->file('submission_files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('submissions/' . $assignment->id, $fileName, 'public');
                $filePaths[] = $path;
            }
        }

        // Create submission using existing DB structure
        $submission = Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $user->id,
            'code_content' => $request->code_submission,  // ✅ Map code_submission to code_content (DB field)
            'file_path' => !empty($filePaths) ? json_encode($filePaths) : null,
            'submission_text' => $request->submission_text, // Add if this field exists in DB
            'submitted_at' => now(),
            'status' => 'submitted'  // Changed from 'pending' to 'submitted'
        ]);

        // Debug: Verify what was saved
        Log::info('Submission Created Successfully', [
            'submission_id' => $submission->id,
            'code_saved' => !empty($submission->code_content),
            'code_length_saved' => strlen($submission->code_content ?? ''),
            'files_saved' => !empty($submission->file_path),
            'text_saved' => !empty($submission->submission_text ?? ''),
        ]);

        DB::commit();

        return redirect()->route('student.assignments.show', $assignment)
            ->with('success', 'Assignment submitted successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Assignment Submission Error: ' . $e->getMessage(), [
            'assignment_id' => $assignment->id ?? null,
            'student_id' => $user->id ?? null,
            'request_data' => $request->all(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'Failed to submit assignment. Please try again.')
            ->withInput();
    }
}

/**
 * View student's submissions with filtering
 */
public function viewSubmissions(Request $request)
{
    try {
        $user = Auth::user();
        
        // Get filter parameters
        $courseId = $request->get('course');
        $status = $request->get('status');
        $search = $request->get('search');
        
        // Base query for student's submissions
        $submissionsQuery = Submission::where('student_id', $user->id)
            ->with(['assignment.course']);

        // Apply course filter
        if ($courseId) {
            $submissionsQuery->whereHas('assignment', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            });
        }

        // Apply status filter
        if ($status) {
            $submissionsQuery->where('status', $status);
        }

        // Apply search filter
        if ($search) {
            $submissionsQuery->whereHas('assignment', function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            });
        }

        $submissions = $submissionsQuery->orderBy('submitted_at', 'desc')->paginate(10);

        // Get enrolled courses for filter
        $enrolledCourses = $user->enrolledCourses()->get();

        // Get submission statistics
        $stats = [
            'total' => $user->submissions()->count(),
            'pending' => $user->submissions()->where('status', 'pending')->count(),
            'graded' => $user->submissions()->where('status', 'graded')->count(),
            'average_grade' => $user->submissions()->where('status', 'graded')->avg('grade')
        ];

        $viewData = [
            'meta_title' => 'My Submissions | Student Portal',
            'meta_desc' => 'View your assignment submissions and grades',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'submissions' => $submissions,
            'enrolledCourses' => $enrolledCourses,
            'stats' => $stats,
            'currentFilters' => [
                'course' => $courseId,
                'status' => $status,
                'search' => $search
            ],
            'user' => $user
        ];

        return view('student.submissions', $viewData);
    } catch (\Exception $e) {
        Log::error('Student Submissions Error: ' . $e->getMessage());
        return view('student.submissions', [
            'meta_title' => 'My Submissions | Student Portal',
            'meta_desc' => 'View your assignment submissions',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'submissions' => collect(),
            'enrolledCourses' => collect(),
            'stats' => ['total' => 0, 'pending' => 0, 'graded' => 0, 'average_grade' => 0],
            'currentFilters' => ['course' => '', 'status' => '', 'search' => ''],
            'user' => Auth::user()
        ]);
    }
}

/**
 * View specific submission details
 */
public function viewSubmission(Submission $submission)
{
    try {
        $user = Auth::user();

        // Check if submission belongs to the student
        if ($submission->student_id !== $user->id) {
            return redirect()->route('student.submissions.index')
                ->with('error', 'You can only view your own submissions.');
        }

        $viewData = [
            'meta_title' => 'Submission Details | Student Portal',
            'meta_desc' => 'View your submission details and feedback',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'submission' => $submission,
            'user' => $user
        ];

        return view('student.submission-detail', $viewData);
    } catch (\Exception $e) {
        Log::error('Submission Detail Error: ' . $e->getMessage());
        return redirect()->route('student.submissions.index')
            ->with('error', 'Unable to load submission details.');
    }
}

/**
 * Download submission file
 */
public function downloadSubmissionFile(Submission $submission)
{
    try {
        $user = Auth::user();

        // Check if submission belongs to the student
        if ($submission->student_id !== $user->id) {
            abort(403, 'You can only download your own submission files.');
        }

        // Check if file exists
        if (!$submission->file_path || !Storage::disk('public')->exists($submission->file_path)) {
            abort(404, 'File not found.');
        }

        $filePath = storage_path('app/public/' . $submission->file_path);
        
        return response()->download($filePath, $submission->file_name);
        
    } catch (\Exception $e) {
        Log::error('Submission File Download Error: ' . $e->getMessage());
        abort(500, 'Unable to download file.');
    }
}

// Helper methods for dashboard and other views
private function getPendingAssignmentsCount()
{
    try {
        $user = Auth::user();
        return Assignment::whereHas('course', function($query) use ($user) {
            $query->whereIn('id', $user->enrolledCourses()->pluck('course_id'));
        })
        ->whereDoesntHave('submissions', function($query) use ($user) {
            $query->where('student_id', $user->id);
        })
        ->where('status', 'active')
        ->where('deadline', '>', now())
        ->count();
    } catch (\Exception $e) {
        return 0;
    }
}

private function getRecentGrades()
{
    try {
        $user = Auth::user();
        return Submission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->with('assignment.course')
            ->orderBy('graded_at', 'desc')
            ->limit(5)
            ->get();
    } catch (\Exception $e) {
        return collect();
    }
}

private function getUpcomingDeadlines()
{
    try {
        $user = Auth::user();
        return Assignment::whereHas('course', function($query) use ($user) {
            $query->whereIn('id', $user->enrolledCourses()->pluck('course_id'));
        })
        ->whereDoesntHave('submissions', function($query) use ($user) {
            $query->where('student_id', $user->id);
        })
        ->where('status', 'active')
        ->where('deadline', '>', now())
        ->where('deadline', '<=', now()->addDays(7))
        ->orderBy('deadline', 'asc')
        ->limit(5)
        ->get();
    } catch (\Exception $e) {
        return collect();
    }
}

}