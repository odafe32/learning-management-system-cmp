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
            return redirect()->route('student.courses')
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
        return redirect()->route('student.courses')
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
                        'url' => route('student.materials')
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
                        'url' => route('student.assignments')
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
            return redirect()->route('student.materials')
                ->with('error', 'You are not enrolled in this course.');
        }
        
        // Check visibility
        if ($material->visibility === 'private') {
            return redirect()->route('student.materials')
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
        return redirect()->route('student.materials')
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
            return redirect()->route('student.assignments')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Check if assignment is active
        if ($assignment->status !== 'active') {
            return redirect()->route('student.assignments')
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
        return redirect()->route('student.assignments')
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


/**
 * View student's grades with filtering and statistics
 */
public function viewGrades(Request $request)
{
    try {
        $user = Auth::user();
        
        // Get filter parameters
        $courseId = $request->get('course');
        $search = $request->get('search');
        
        // Base query for graded submissions
        $gradesQuery = Submission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->whereNotNull('grade')
            ->with(['assignment.course.instructor']);

        // Apply course filter
        if ($courseId) {
            $gradesQuery->whereHas('assignment', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            });
        }

        // Apply search filter
        if ($search) {
            $gradesQuery->whereHas('assignment', function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            });
        }

        $grades = $gradesQuery->orderBy('graded_at', 'desc')->get();

        // Group grades by course
        $gradesByCourse = $grades->groupBy('assignment.course_id');

        // Get enrolled courses for filter
        $enrolledCourses = $user->enrolledCourses()->get();

        // Calculate statistics
        $stats = [
            'total_graded' => $grades->count(),
            'overall_average' => $grades->avg('grade'),
            'highest_grade' => $grades->max('grade'),
            'lowest_grade' => $grades->min('grade'),
            'pending_grades' => $user->submissions()
                ->whereIn('status', ['submitted', 'pending'])
                ->count()
        ];

        // Calculate grade distribution
        $gradeDistribution = $this->calculateGradeDistribution($grades);

        $viewData = [
            'meta_title' => 'My Grades | Student Portal',
            'meta_desc' => 'View your assignment grades and academic performance',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'grades' => $grades,
            'gradesByCourse' => $gradesByCourse,
            'enrolledCourses' => $enrolledCourses,
            'stats' => $stats,
            'gradeDistribution' => $gradeDistribution,
            'currentFilters' => [
                'course' => $courseId,
                'search' => $search
            ],
            'user' => $user
        ];

        return view('student.grades', $viewData);
    } catch (\Exception $e) {
        Log::error('Student Grades Error: ' . $e->getMessage());
        return view('student.grades', [
            'meta_title' => 'My Grades | Student Portal',
            'meta_desc' => 'View your assignment grades',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'grades' => collect(),
            'gradesByCourse' => collect(),
            'enrolledCourses' => collect(),
            'stats' => ['total_graded' => 0, 'overall_average' => 0, 'highest_grade' => 0, 'lowest_grade' => 0, 'pending_grades' => 0],
            'gradeDistribution' => [],
            'currentFilters' => ['course' => '', 'search' => ''],
            'user' => Auth::user()
        ]);
    }
}

/**
 * View grades by specific course
 */
public function viewGradesByCourse(Request $request, Course $course)
{
    try {
        $user = Auth::user();

        // Check if student is enrolled in this course
        if (!$user->isEnrolledIn($course->id)) {
            return redirect()->route('student.grades.index')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Get graded submissions for this course
        $grades = Submission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->whereNotNull('grade')
            ->whereHas('assignment', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->with(['assignment'])
            ->orderBy('graded_at', 'desc')
            ->get();

        // Calculate course statistics
        $courseStats = [
            'total_assignments' => $course->assignments()->where('status', 'active')->count(),
            'graded_assignments' => $grades->count(),
            'course_average' => $grades->avg('grade'),
            'highest_grade' => $grades->max('grade'),
            'lowest_grade' => $grades->min('grade'),
        ];

        $viewData = [
            'meta_title' => $course->title . ' Grades | Student Portal',
            'meta_desc' => 'View your grades for ' . $course->title,
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'course' => $course,
            'grades' => $grades,
            'courseStats' => $courseStats,
            'user' => $user
        ];

        return view('student.course-grades', $viewData);
    } catch (\Exception $e) {
        Log::error('Course Grades Error: ' . $e->getMessage());
        return redirect()->route('student.grades.index')
            ->with('error', 'Unable to load course grades.');
    }
}

/**
 * View grades for specific assignment
 */
public function viewGradesByAssignment(Request $request, Assignment $assignment)
{
    try {
        $user = Auth::user();

        // Check if student is enrolled in the course
        if (!$user->isEnrolledIn($assignment->course_id)) {
            return redirect()->route('student.grades.index')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Get student's submission for this assignment
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->first();

        if (!$submission) {
            return redirect()->route('student.assignments.show', $assignment)
                ->with('error', 'You have not submitted this assignment yet.');
        }

        return redirect()->route('student.submissions.show', $submission);
    } catch (\Exception $e) {
        Log::error('Assignment Grades Error: ' . $e->getMessage());
        return redirect()->route('student.grades.index')
            ->with('error', 'Unable to load assignment grade.');
    }
}


/**
 * Calculate grade distribution for charts
 */
private function calculateGradeDistribution($grades)
{
    $distribution = [
        'A' => ['count' => 0, 'percentage' => 0, 'color' => 'success'],
        'B' => ['count' => 0, 'percentage' => 0, 'color' => 'info'],
        'C' => ['count' => 0, 'percentage' => 0, 'color' => 'primary'],
        'D' => ['count' => 0, 'percentage' => 0, 'color' => 'warning'],
        'E' => ['count' => 0, 'percentage' => 0, 'color' => 'orange'],
        'F' => ['count' => 0, 'percentage' => 0, 'color' => 'danger'],
    ];

    $total = $grades->count();

    if ($total === 0) {
        return $distribution;
    }

    foreach ($grades as $grade) {
        $letter = $grade->grade_letter;
        if (isset($distribution[$letter])) {
            $distribution[$letter]['count']++;
        }
    }

    // Calculate percentages
    foreach ($distribution as $letter => &$data) {
        $data['percentage'] = round(($data['count'] / $total) * 100, 1);
    }

    return $distribution;
}

/**
 * Export grades to PDF (placeholder - implement with your preferred PDF library)
 */


/**
 * Export grades to Excel (placeholder - implement with Laravel Excel)
 */

/**
 * Add missing route for downloading submission files
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

/**
 * Export grades to PDF or Excel
 */
public function exportGrades(Request $request)
{
    try {
        $user = Auth::user();
        $format = $request->get('format', 'pdf');

        // Get all graded submissions
        $grades = Submission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->whereNotNull('grade')
            ->with(['assignment.course.instructor'])
            ->orderBy('graded_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_graded' => $grades->count(),
            'overall_average' => $grades->avg('grade'),
            'highest_grade' => $grades->max('grade'),
            'lowest_grade' => $grades->min('grade'),
        ];

        if ($format === 'pdf') {
            return $this->exportGradesToPDF($grades, $stats, $user);
        } else {
            return $this->exportGradesToExcel($grades, $stats, $user);
        }
    } catch (\Exception $e) {
        Log::error('Grades Export Error: ' . $e->getMessage());
        return redirect()->route('student.grades.index')
            ->with('error', 'Unable to export grades. Please try again.');
    }
}

/**
 * Export grades to PDF using HTML/CSS
 */
private function exportGradesToPDF($grades, $stats, $user)
{
    // Group grades by course
    $gradesByCourse = $grades->groupBy('assignment.course_id');
    
    // Generate HTML content
    $html = $this->generateGradesPDFHTML($grades, $gradesByCourse, $stats, $user);
    
    // Create filename
    $filename = 'grades_report_' . $user->student_id . '_' . date('Y-m-d') . '.pdf';
    
    // Use DOMPDF if available, otherwise create HTML response
    if (class_exists('\Dompdf\Dompdf')) {
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    } else {
        // Fallback: Return HTML that can be printed as PDF
        return response($html, 200)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="grades_report.html"');
    }
}

/**
 * Export grades to Excel/CSV
 */
private function exportGradesToExcel($grades, $stats, $user)
{
    $filename = 'grades_report_' . $user->student_id . '_' . date('Y-m-d') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function() use ($grades, $stats, $user) {
        $file = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header information
        fputcsv($file, ['GRADE REPORT']);
        fputcsv($file, ['Student Name:', $user->name]);
        fputcsv($file, ['Student ID:', $user->student_id ?? 'N/A']);
        fputcsv($file, ['Generated:', date('Y-m-d H:i:s')]);
        fputcsv($file, []);
        
        // Statistics
        fputcsv($file, ['STATISTICS']);
        fputcsv($file, ['Total Graded Assignments:', $stats['total_graded']]);
        fputcsv($file, ['Overall Average:', $stats['overall_average'] ? number_format($stats['overall_average'], 2) . '%' : 'N/A']);
        fputcsv($file, ['Highest Grade:', $stats['highest_grade'] ? number_format($stats['highest_grade'], 2) . '%' : 'N/A']);
        fputcsv($file, ['Lowest Grade:', $stats['lowest_grade'] ? number_format($stats['lowest_grade'], 2) . '%' : 'N/A']);
        fputcsv($file, []);
        
        // Column headers
        fputcsv($file, [
            'Course Code',
            'Course Title',
            'Assignment Title',
            'Max Points',
            'Grade (%)',
            'Letter Grade',
            'Submitted Date',
            'Graded Date',
            'Status',
            'Feedback'
        ]);
        
        // Data rows
        foreach ($grades as $submission) {
            fputcsv($file, [
                $submission->assignment->course->code,
                $submission->assignment->course->title,
                $submission->assignment->title,
                $submission->assignment->max_points ?? 100,
                number_format($submission->grade, 2),
                $submission->grade_letter,
                $submission->submitted_at->format('Y-m-d H:i:s'),
                $submission->graded_at ? $submission->graded_at->format('Y-m-d H:i:s') : 'N/A',
                $submission->isLate() ? 'Late' : 'On Time',
                $submission->feedback ?? 'No feedback'
            ]);
        }
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

/**
 * Generate HTML content for PDF export
 */
private function generateGradesPDFHTML($grades, $gradesByCourse, $stats, $user)
{
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Grade Report - ' . $user->name . '</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                color: #333;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #007bff;
                padding-bottom: 20px;
            }
            .header h1 {
                color: #007bff;
                margin: 0;
            }
            .student-info {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
            }
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                margin-bottom: 30px;
            }
            .stat-card {
                background: #fff;
                border: 1px solid #dee2e6;
                border-radius: 5px;
                padding: 15px;
                text-align: center;
            }
            .stat-value {
                font-size: 24px;
                font-weight: bold;
                color: #007bff;
            }
            .stat-label {
                color: #6c757d;
                font-size: 14px;
            }
            .course-section {
                margin-bottom: 30px;
                break-inside: avoid;
            }
            .course-header {
                background: #007bff;
                color: white;
                padding: 10px 15px;
                border-radius: 5px 5px 0 0;
                margin: 0;
            }
            .course-average {
                float: right;
                font-weight: bold;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td {
                border: 1px solid #dee2e6;
                padding: 8px;
                text-align: left;
            }
            th {
                background: #f8f9fa;
                font-weight: bold;
            }
            .grade-a { color: #28a745; }
            .grade-b { color: #17a2b8; }
            .grade-c { color: #007bff; }
            .grade-d { color: #ffc107; }
            .grade-e { color: #fd7e14; }
            .grade-f { color: #dc3545; }
            .late { color: #dc3545; font-size: 12px; }
            .on-time { color: #28a745; font-size: 12px; }
            .footer {
                margin-top: 30px;
                text-align: center;
                color: #6c757d;
                font-size: 12px;
                border-top: 1px solid #dee2e6;
                padding-top: 15px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Academic Grade Report</h1>
            <p>Student Performance Summary</p>
        </div>
        
        <div class="student-info">
            <h3>Student Information</h3>
            <p><strong>Name:</strong> ' . $user->name . '</p>
            <p><strong>Student ID:</strong> ' . ($user->student_id ?? 'N/A') . '</p>
            <p><strong>Email:</strong> ' . $user->email . '</p>
            <p><strong>Level:</strong> ' . ($user->level ?? 'N/A') . '</p>
            <p><strong>Report Generated:</strong> ' . date('F j, Y \a\t g:i A') . '</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">' . $stats['total_graded'] . '</div>
                <div class="stat-label">Graded Assignments</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">' . ($stats['overall_average'] ? number_format($stats['overall_average'], 1) . '%' : 'N/A') . '</div>
                <div class="stat-label">Overall Average</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">' . ($stats['highest_grade'] ? number_format($stats['highest_grade'], 1) . '%' : 'N/A') . '</div>
                <div class="stat-label">Highest Grade</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">' . ($stats['lowest_grade'] ? number_format($stats['lowest_grade'], 1) . '%' : 'N/A') . '</div>
                <div class="stat-label">Lowest Grade</div>
            </div>
        </div>';

    // Add courses and grades
    foreach ($gradesByCourse as $courseId => $courseGrades) {
        $course = $courseGrades->first()->assignment->course;
        $courseAverage = $courseGrades->avg('grade');
        
        $html .= '
        <div class="course-section">
            <h3 class="course-header">
                ' . $course->code . ' - ' . $course->title . '
                <span class="course-average">Average: ' . number_format($courseAverage, 1) . '%</span>
            </h3>
            <table>
                <thead>
                    <tr>
                        <th>Assignment</th>
                        <th>Max Points</th>
                        <th>Grade</th>
                        <th>Letter</th>
                        <th>Submitted</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($courseGrades as $submission) {
            $gradeClass = 'grade-' . strtolower($submission->grade_letter);
            $statusClass = $submission->isLate() ? 'late' : 'on-time';
            $statusText = $submission->isLate() ? 'Late' : 'On Time';
            
            $html .= '
                    <tr>
                        <td>' . $submission->assignment->title . '</td>
                        <td>' . ($submission->assignment->max_points ?? 100) . '</td>
                        <td class="' . $gradeClass . '">' . number_format($submission->grade, 1) . '%</td>
                        <td class="' . $gradeClass . '">' . $submission->grade_letter . '</td>
                        <td>' . $submission->submitted_at->format('M j, Y') . '</td>
                        <td class="' . $statusClass . '">' . $statusText . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
        </div>';
    }

    $html .= '
        <div class="footer">
            <p>This report was generated automatically by the Student Learning Management System.</p>
            <p>For questions about your grades, please contact your instructor or academic advisor.</p>
        </div>
    </body>
    </html>';

    return $html;
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
            ->with(['assignment.course.instructor']);

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
            'pending' => $user->submissions()->whereIn('status', ['submitted', 'pending'])->count(),
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
        
        // Return safe fallback data
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
 * Mark message as read
 */
public function markAsRead(Request $request, $messageId)
{
    try {
        $user = Auth::user();
        $message = Message::where('id', $messageId)
            ->where('receiver_id', $user->id)
            ->firstOrFail();

        $message->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read'
        ]);

    } catch (\Exception $e) {
        Log::error('Error marking student message as read', [
            'error' => $e->getMessage(),
            'message_id' => $messageId,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to mark message as read'
        ], 500);
    }
}






/**
 * Check if user can message another user
 * Updated with proper restrictions for students
 */
public function canMessageUser(User $user): bool
{
    // Admin can message anyone
    if ($this->isAdmin()) {
        return true;
    }

    // Instructors/Lecturers can message:
    // - Students enrolled in their courses
    // - Admins
    // - Other instructors/lecturers
    if ($this->isInstructor()) {
        // Can message admins
        if ($user->isAdmin()) {
            return true;
        }

        // Can message other instructors/lecturers
        if ($user->isInstructor()) {
            return true;
        }

        // Can message students enrolled in their courses
        if ($user->isStudent()) {
            $studentCourseIds = CourseStudent::where('user_id', $user->id)
                ->where('status', 'active')
                ->pluck('course_id');
            $instructorCourseIds = $this->taughtCourses()->pluck('id');
            
            return $studentCourseIds->intersect($instructorCourseIds)->isNotEmpty();
        }
    }

    // Students can message:
    // 1. Lecturers who teach courses for their level
    // 2. Fellow students in the same level
    // 3. Admins
    if ($this->isStudent()) {
        // Can message admins
        if ($user->isAdmin()) {
            return true;
        }

        // Can message lecturers who teach courses for their level
        if ($user->isInstructor()) {
            return $user->taughtCourses()
                ->where('level', $this->level)
                ->where('status', 'active')
                ->exists();
        }

        // Can message fellow students in the same level
        if ($user->isStudent()) {
            return $this->level === $user->level && $this->id !== $user->id;
        }
    }

    return false;
}


/**
 * View feedbacks with filtering
 */
public function viewFeedbacks(Request $request)
{
    try {
        $user = Auth::user();

        // Get filter parameters
        $courseId = $request->get('course');
        $search = $request->get('search');

        // Base query for graded submissions with feedback
        $feedbacksQuery = Submission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->whereNotNull('feedback')
            ->where('feedback', '!=', '')
            ->with(['assignment.course.instructor']);

        // Apply course filter
        if ($courseId) {
            $feedbacksQuery->whereHas('assignment', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            });
        }

        // Apply search filter
        if ($search) {
            $feedbacksQuery->whereHas('assignment', function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            });
        }

        $feedbacks = $feedbacksQuery->orderBy('graded_at', 'desc')->paginate(10);

        // Get enrolled courses for filter
        $enrolledCourses = $user->enrolledCourses()->get();

        // Calculate statistics
        $stats = [
            'total_feedbacks' => Submission::where('student_id', $user->id)
                ->where('status', 'graded')
                ->whereNotNull('feedback')
                ->where('feedback', '!=', '')
                ->count(),
            'average_grade' => Submission::where('student_id', $user->id)
                ->where('status', 'graded')
                ->whereNotNull('feedback')
                ->where('feedback', '!=', '')
                ->avg('grade'),
            'recent_feedbacks' => Submission::where('student_id', $user->id)
                ->where('status', 'graded')
                ->whereNotNull('feedback')
                ->where('feedback', '!=', '')
                ->where('graded_at', '>=', now()->subDays(7))
                ->count(),
        ];

        $viewData = [
            'meta_title' => 'My Feedbacks | Student Portal',
            'meta_desc' => 'View feedback from your instructors on assignments',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'feedbacks' => $feedbacks,
            'enrolledCourses' => $enrolledCourses,
            'stats' => $stats,
            'currentFilters' => [
                'course' => $courseId,
                'search' => $search
            ],
            'user' => $user
        ];

        return view('student.feedbacks', $viewData);

    } catch (\Exception $e) {
        Log::error('Student Feedbacks Error: ' . $e->getMessage());
        
        return view('student.feedbacks', [
            'meta_title' => 'My Feedbacks | Student Portal',
            'meta_desc' => 'View feedback from instructors',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'feedbacks' => collect(),
            'enrolledCourses' => collect(),
            'stats' => ['total_feedbacks' => 0, 'average_grade' => 0, 'recent_feedbacks' => 0],
            'currentFilters' => ['course' => '', 'search' => ''],
            'user' => Auth::user()
        ])->with('error', 'Unable to load feedbacks. Please try again.');
    }
}

/**
 * View specific feedback details
 */
public function viewFeedback(Submission $submission)
{
    try {
        $user = Auth::user();

        // Check if submission belongs to the student
        if ($submission->student_id !== $user->id) {
            return redirect()->route('student.feedbacks.index')
                ->with('error', 'You can only view your own feedback.');
        }

        // Check if submission has feedback
        if (!$submission->isGraded() || empty($submission->feedback)) {
            return redirect()->route('student.feedbacks.index')
                ->with('error', 'This submission does not have feedback yet.');
        }

        // Get related submissions for comparison
        $relatedSubmissions = Submission::where('assignment_id', $submission->assignment_id)
            ->where('student_id', $user->id)
            ->where('id', '!=', $submission->id)
            ->orderBy('submitted_at', 'desc')
            ->limit(3)
            ->get();

        // Get assignment statistics
        $assignmentStats = [
            'total_submissions' => Submission::where('assignment_id', $submission->assignment_id)->count(),
            'average_grade' => Submission::where('assignment_id', $submission->assignment_id)
                ->where('status', 'graded')
                ->avg('grade'),
            'highest_grade' => Submission::where('assignment_id', $submission->assignment_id)
                ->where('status', 'graded')
                ->max('grade'),
            'lowest_grade' => Submission::where('assignment_id', $submission->assignment_id)
                ->where('status', 'graded')
                ->min('grade'),
        ];

        $viewData = [
            'meta_title' => 'Feedback Details | Student Portal',
            'meta_desc' => 'View detailed feedback for your submission',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'submission' => $submission,
            'relatedSubmissions' => $relatedSubmissions,
            'assignmentStats' => $assignmentStats,
            'user' => $user
        ];

        return view('student.feedback-detail', $viewData);

    } catch (\Exception $e) {
        Log::error('Feedback Detail Error: ' . $e->getMessage());
        return redirect()->route('student.feedbacks.index')
            ->with('error', 'Unable to load feedback details.');
    }
}

/**
 * View feedbacks by specific course
 */
public function viewFeedbacksByCourse(Request $request, Course $course)
{
    try {
        $user = Auth::user();

        // Check if student is enrolled in this course
        if (!$user->isEnrolledIn($course->id)) {
            return redirect()->route('student.feedbacks')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Get feedbacks for this course
        $feedbacks = Submission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->whereNotNull('feedback')
            ->where('feedback', '!=', '')
            ->whereHas('assignment', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->with(['assignment'])
            ->orderBy('graded_at', 'desc')
            ->paginate(10);

        // Calculate course-specific feedback statistics
        $courseStats = [
            'total_assignments' => $course->assignments()->where('status', 'active')->count(),
            'graded_assignments' => Submission::where('student_id', $user->id)
                ->whereHas('assignment', function($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->where('status', 'graded')
                ->count(),
            'feedbacks_received' => $feedbacks->total(),
            'course_average' => Submission::where('student_id', $user->id)
                ->whereHas('assignment', function($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->where('status', 'graded')
                ->avg('grade'),
        ];

        $viewData = [
            'meta_title' => $course->title . ' Feedbacks | Student Portal',
            'meta_desc' => 'View feedback for ' . $course->title,
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'course' => $course,
            'feedbacks' => $feedbacks,
            'courseStats' => $courseStats,
            'user' => $user
        ];

        return view('student.course-feedbacks', $viewData);

    } catch (\Exception $e) {
        Log::error('Course Feedbacks Error: ' . $e->getMessage());
        return redirect()->route('student.feedbacks')
            ->with('error', 'Unable to load course feedbacks.');
    }
}

/**
 * AJAX search feedbacks
 */
public function searchFeedbacks(Request $request)
{
    try {
        $user = Auth::user();
        $search = $request->get('q');
        $courseId = $request->get('course_id');

        $feedbacksQuery = Submission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->whereNotNull('feedback')
            ->where('feedback', '!=', '')
            ->with(['assignment.course.instructor']);

        if ($search) {
            $feedbacksQuery->where(function($query) use ($search) {
                $query->where('feedback', 'like', "%{$search}%")
                      ->orWhereHas('assignment', function($q) use ($search) {
                          $q->where('title', 'like', "%{$search}%");
                      });
            });
        }

        if ($courseId) {
            $feedbacksQuery->whereHas('assignment', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            });
        }

        $feedbacks = $feedbacksQuery->limit(10)->get();

        return response()->json([
            'success' => true,
            'feedbacks' => $feedbacks->map(function($submission) {
                return [
                    'id' => $submission->id,
                    'assignment_title' => $submission->assignment->title,
                    'course_name' => $submission->assignment->course->title,
                    'grade' => $submission->grade,
                    'grade_letter' => $submission->grade_letter,
                    'feedback' => Str::limit($submission->feedback, 150),
                    'graded_at' => $submission->graded_at ? $submission->graded_at->format('M d, Y') : 'N/A',
                    'instructor_name' => $submission->assignment->course->instructor->name ?? 'N/A',
                    'view_url' => route('student.feedbacks.show', $submission->id),
                    'is_positive' => $submission->grade >= 70,
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
 * Export feedbacks to PDF or Excel
 */
public function exportFeedbacks(Request $request)
{
    try {
        $user = Auth::user();
        $format = $request->get('format', 'pdf');

        // Get all feedbacks
        $feedbacks = Submission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->whereNotNull('feedback')
            ->where('feedback', '!=', '')
            ->with(['assignment.course.instructor'])
            ->orderBy('graded_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_feedbacks' => $feedbacks->count(),
            'positive_feedbacks' => $feedbacks->where('grade', '>=', 70)->count(),
            'average_grade' => $feedbacks->avg('grade'),
            'highest_grade' => $feedbacks->max('grade'),
            'lowest_grade' => $feedbacks->min('grade'),
        ];

        if ($format === 'pdf') {
            return $this->exportFeedbacksToPDF($feedbacks, $stats, $user);
        } else {
            return $this->exportFeedbacksToExcel($feedbacks, $stats, $user);
        }

    } catch (\Exception $e) {
        Log::error('Feedbacks Export Error: ' . $e->getMessage());
        return redirect()->route('student.feedbacks')
            ->with('error', 'Unable to export feedbacks. Please try again.');
    }
}

/**
 * Export feedbacks to PDF using HTML/CSS
 */
private function exportFeedbacksToPDF($feedbacks, $stats, $user)
{
    // Group feedbacks by course
    $feedbacksByCourse = $feedbacks->groupBy('assignment.course_id');

    // Generate HTML content
    $html = $this->generateFeedbacksPDFHTML($feedbacks, $feedbacksByCourse, $stats, $user);

    // Create filename
    $filename = 'feedbacks_report_' . $user->matric_or_staff_id . '_' . date('Y-m-d') . '.pdf';

    // Use DOMPDF if available, otherwise create HTML response
    if (class_exists('\\Dompdf\\Dompdf')) {
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    } else {
        // Fallback: Return HTML that can be printed as PDF
        return response($html, 200)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="feedbacks_report.html"');
    }
}

/**
 * Export feedbacks to Excel/CSV
 */
private function exportFeedbacksToExcel($feedbacks, $stats, $user)
{
    $filename = 'feedbacks_report_' . $user->matric_or_staff_id . '_' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function() use ($feedbacks, $stats, $user) {
        $file = fopen('php://output', 'w');

        // Add BOM for UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header information
        fputcsv($file, ['FEEDBACKS REPORT']);
        fputcsv($file, ['Student Name:', $user->name]);
        fputcsv($file, ['Student ID:', $user->matric_or_staff_id ?? 'N/A']);
        fputcsv($file, ['Generated:', date('Y-m-d H:i:s')]);
        fputcsv($file, []);

        // Statistics
        fputcsv($file, ['STATISTICS']);
        fputcsv($file, ['Total Feedbacks:', $stats['total_feedbacks']]);
        fputcsv($file, ['Positive Feedbacks:', $stats['positive_feedbacks']]);
        fputcsv($file, ['Average Grade:', $stats['average_grade'] ? number_format($stats['average_grade'], 2) . '%' : 'N/A']);
        fputcsv($file, ['Highest Grade:', $stats['highest_grade'] ? number_format($stats['highest_grade'], 2) . '%' : 'N/A']);
        fputcsv($file, ['Lowest Grade:', $stats['lowest_grade'] ? number_format($stats['lowest_grade'], 2) . '%' : 'N/A']);
        fputcsv($file, []);

        // Column headers
        fputcsv($file, [
            'Course Code',
            'Course Title',
            'Assignment Title',
            'Grade (%)',
            'Letter Grade',
            'Instructor',
            'Graded Date',
            'Feedback'
        ]);

        // Data rows
        foreach ($feedbacks as $submission) {
            fputcsv($file, [
                $submission->assignment->course->code,
                $submission->assignment->course->title,
                $submission->assignment->title,
                number_format($submission->grade, 2),
                $submission->grade_letter,
                $submission->assignment->course->instructor->name ?? 'N/A',
                $submission->graded_at ? $submission->graded_at->format('Y-m-d H:i:s') : 'N/A',
                $submission->feedback
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

/**
 * Generate HTML content for PDF export
 */
private function generateFeedbacksPDFHTML($feedbacks, $feedbacksByCourse, $stats, $user)
{
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Feedbacks Report - ' . $user->name . '</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                color: #333;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #007bff;
                padding-bottom: 20px;
            }
            .header h1 {
                color: #007bff;
                margin: 0;
            }
            .student-info {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
            }
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                margin-bottom: 30px;
            }
            .stat-card {
                background: #fff;
                border: 1px solid #dee2e6;
                border-radius: 5px;
                padding: 15px;
                text-align: center;
            }
            .stat-value {
                font-size: 24px;
                font-weight: bold;
                color: #007bff;
            }
            .stat-label {
                color: #6c757d;
                font-size: 14px;
            }
            .course-section {
                margin-bottom: 30px;
                break-inside: avoid;
            }
            .course-header {
                background: #007bff;
                color: white;
                padding: 10px 15px;
                border-radius: 5px 5px 0 0;
                margin: 0;
            }
            .feedback-item {
                border: 1px solid #dee2e6;
                border-top: none;
                padding: 15px;
                background: #fff;
            }
            .feedback-item:last-child {
                border-radius: 0 0 5px 5px;
            }
            .assignment-title {
                font-weight: bold;
                color: #495057;
                margin-bottom: 5px;
            }
            .grade-info {
                margin-bottom: 10px;
            }
            .grade-badge {
                padding: 4px 8px;
                border-radius: 4px;
                font-weight: bold;
                font-size: 12px;
            }
            .grade-a { background: #d4edda; color: #155724; }
            .grade-b { background: #d1ecf1; color: #0c5460; }
            .grade-c { background: #cce5ff; color: #004085; }
            .grade-d { background: #fff3cd; color: #856404; }
            .grade-e { background: #f8d7da; color: #721c24; }
            .grade-f { background: #f8d7da; color: #721c24; }
            .feedback-text {
                background: #f8f9fa;
                padding: 10px;
                border-left: 4px solid #007bff;
                margin-top: 10px;
                font-style: italic;
            }
            .footer {
                margin-top: 30px;
                text-align: center;
                color: #6c757d;
                font-size: 12px;
                border-top: 1px solid #dee2e6;
                padding-top: 15px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Assignment Feedbacks Report</h1>
            <p>Instructor Feedback Summary</p>
        </div>

        <div class="student-info">
            <h3>Student Information</h3>
            <p><strong>Name:</strong> ' . $user->name . '</p>
            <p><strong>Student ID:</strong> ' . ($user->matric_or_staff_id ?? 'N/A') . '</p>
            <p><strong>Email:</strong> ' . $user->email . '</p>
            <p><strong>Level:</strong> ' . ($user->level ?? 'N/A') . '</p>
            <p><strong>Report Generated:</strong> ' . date('F j, Y \\a\\t g:i A') . '</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">' . $stats['total_feedbacks'] . '</div>
                <div class="stat-label">Total Feedbacks</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">' . $stats['positive_feedbacks'] . '</div>
                <div class="stat-label">Positive Feedbacks</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">' . ($stats['average_grade'] ? number_format($stats['average_grade'], 1) . '%' : 'N/A') . '</div>
                <div class="stat-label">Average Grade</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">' . ($stats['highest_grade'] ? number_format($stats['highest_grade'], 1) . '%' : 'N/A') . '</div>
                <div class="stat-label">Highest Grade</div>
            </div>
        </div>';

    // Add courses and feedbacks
    foreach ($feedbacksByCourse as $courseId => $courseFeedbacks) {
        $course = $courseFeedbacks->first()->assignment->course;
        $courseAverage = $courseFeedbacks->avg('grade');

        $html .= '
        <div class="course-section">
            <h3 class="course-header">
                ' . $course->code . ' - ' . $course->title . '
                <span style="float: right;">Average: ' . number_format($courseAverage, 1) . '%</span>
            </h3>';

        foreach ($courseFeedbacks as $submission) {
            $gradeClass = 'grade-' . strtolower($submission->grade_letter);

            $html .= '
            <div class="feedback-item">
                <div class="assignment-title">' . $submission->assignment->title . '</div>
                <div class="grade-info">
                    <span class="grade-badge ' . $gradeClass . '">' . number_format($submission->grade, 1) . '% (' . $submission->grade_letter . ')</span>
                    <span style="margin-left: 15px; color: #6c757d; font-size: 14px;">
                        Graded: ' . ($submission->graded_at ? $submission->graded_at->format('M j, Y') : 'N/A') . '
                    </span>
                </div>
                <div class="feedback-text">' . nl2br(htmlspecialchars($submission->feedback)) . '</div>
            </div>';
        }

        $html .= '</div>';
    }

    $html .= '
        <div class="footer">
            <p>This report was generated automatically by the Student Learning Management System.</p>
            <p>For questions about your feedback, please contact your instructor.</p>
        </div>
    </body>
    </html>';

    return $html;
}

/**
 * View messages/conversations
 */

public function viewMessages(Request $request)
{
    try {
        $user = Auth::user();
        
        // Get filter parameters
        $filter = $request->get('filter', 'all');
        $search = $request->get('search', '');
        $conversationWith = $request->get('conversation');

        // Get all conversations for the student
        $conversations = Message::where(function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy(function($message) use ($user) {
            // Group by the other participant in the conversation
            return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
        })
        ->map(function($messages) {
            return $messages->first(); // Get the latest message for each conversation
        });

        $messages = collect();
        $conversationPartner = null;
        
        if ($conversationWith) {
            // Verify student can message this user
            $partner = User::find($conversationWith);
            if (!$partner || !$user->canMessageUser($partner)) {
                return redirect()->route('student.messages.index')
                    ->with('error', 'You cannot message this user.');
            }
            
            // Get messages between users
            $messages = Message::where(function($query) use ($user, $conversationWith) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $conversationWith);
            })
            ->orWhere(function($query) use ($user, $conversationWith) {
                $query->where('sender_id', $conversationWith)
                      ->where('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
            
            $conversationPartner = $partner;
            
            // Mark messages as read
            Message::where('receiver_id', $user->id)
                ->where('sender_id', $conversationWith)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Apply filters to conversations
        if ($filter === 'unread') {
            $conversations = $conversations->filter(function($conversation) use ($user) {
                return !$conversation->is_read && $conversation->receiver_id === $user->id;
            });
        } elseif ($filter === 'sent') {
            $conversations = $conversations->filter(function($conversation) use ($user) {
                return $conversation->sender_id === $user->id;
            });
        }

        // Get search query if provided
        if ($search) {
            $conversations = $conversations->filter(function($message) use ($search, $user) {
                $otherUser = $message->sender_id == $user->id ? $message->receiver : $message->sender;
                return stripos($otherUser->name, $search) !== false || 
                       stripos($otherUser->email, $search) !== false;
            });
        }

        // Get users that can be messaged
        $availableUsers = $this->getAvailableUsersForStudentMessaging($user);

        // Calculate message statistics
        $stats = [
            'total_messages' => Message::where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })->count(),
            'unread_count' => Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count(),
            'sent_count' => Message::where('sender_id', $user->id)->count(),
            'received_count' => Message::where('receiver_id', $user->id)->count(),
        ];

        $viewData = [
            'meta_title' => 'Messages | Student Portal',
            'meta_desc' => 'View and manage your messages',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'conversations' => $conversations,
            'messages' => $messages,
            'conversationPartner' => $conversationPartner,
            'availableUsers' => $availableUsers,
            'stats' => $stats, // IMPORTANT: Include stats
            'filter' => $filter,
            'search' => $search,
            'conversationWith' => $conversationWith,
            'user' => $user
        ];

        return view('student.messages', $viewData);

    } catch (\Exception $e) {
        Log::error('Student Messages Error: ' . $e->getMessage());
        
        // Return safe fallback with default stats
        $user = Auth::user();
        $viewData = [
            'meta_title' => 'Messages | Student Portal',
            'meta_desc' => 'View and manage your messages',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'conversations' => collect(),
            'messages' => collect(),
            'conversationPartner' => null,
            'availableUsers' => collect(),
            'stats' => [
                'total_messages' => 0,
                'unread_count' => 0,
                'sent_count' => 0,
                'received_count' => 0,
            ],
            'filter' => 'all',
            'search' => '',
            'conversationWith' => null,
            'user' => $user
        ];
        
        return view('student.messages', $viewData)
            ->with('error', 'Unable to load messages. Please try again.');
    }
}
/**
 * View specific conversation
 */
public function viewConversation(Request $request, User $user)
{
    try {
        $currentUser = Auth::user();
        
        // Get messages between current user and the specified user
        $messages = Message::where(function($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                  ->where('receiver_id', $user->id);
        })
        ->orWhere(function($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $currentUser->id);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->paginate(50);

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $viewData = [
            'meta_title' => 'Conversation with ' . $user->name . ' | Student Portal',
            'meta_desc' => 'View conversation with ' . $user->name,
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'messages' => $messages,
            'otherUser' => $user,
            'currentUser' => $currentUser
        ];

        return view('student.messages.conversation', $viewData);

    } catch (\Exception $e) {
        Log::error('Student Conversation Error: ' . $e->getMessage());
        return redirect()->route('student.messages')
            ->with('error', 'Unable to load conversation. Please try again.');
    }
}


/**
 * Search users for messaging
 */
public function searchUsers(Request $request)
{
    try {
        $query = $request->get('q', '');
        $currentUser = Auth::user();
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'users' => []
            ]);
        }

        // Search for instructors and other students
        $users = User::where('id', '!=', $currentUser->id)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('matric_or_staff_id', 'like', "%{$query}%");
            })
            ->whereIn('role', ['instructor', 'student'])
            ->limit(10)
            ->get(['id', 'name', 'email', 'role', 'avatar']);

        $formattedUsers = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => ucfirst($user->role),
                'avatar' => $user->avatar ? Storage::url($user->avatar) : url('assets/images/thumbs/user-img.png'),
                'conversation_url' => route('student.messages.conversation', $user->id)
            ];
        });

        return response()->json([
            'success' => true,
            'users' => $formattedUsers
        ]);

    } catch (\Exception $e) {
        Log::error('Search Users Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Search failed. Please try again.'
        ], 500);
    }
}

/**
 * Mark message as read
 */
public function markMessageAsRead(Message $message)
{
    try {
        $user = Auth::user();
        
        // Check if user is the receiver of the message
        if ($message->receiver_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $message->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read'
        ]);

    } catch (\Exception $e) {
        Log::error('Mark Message Read Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to mark message as read'
        ], 500);
    }
}

/**
 * Delete message
 */
public function deleteMessage(Message $message)
{
    try {
        $user = Auth::user();
        
        // Check if user is sender or receiver
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete attachment if exists
        if ($message->attachment_path && Storage::disk('public')->exists($message->attachment_path)) {
            Storage::disk('public')->delete($message->attachment_path);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Delete Message Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete message'
        ], 500);
    }
}

/**
 * Download message attachment
 */
public function downloadAttachment(Message $message)
{
    try {
        $user = Auth::user();
        
        // Check if user is sender or receiver
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if (!$message->attachment_path || !Storage::disk('public')->exists($message->attachment_path)) {
            abort(404, 'Attachment not found');
        }

        return Storage::disk('public')->download(
            $message->attachment_path,
            $message->attachment_name ?? basename($message->attachment_path)
        );

    } catch (\Exception $e) {
        Log::error('Download Attachment Error: ' . $e->getMessage());
        abort(500, 'Failed to download attachment');
    }
}



/**
 * Show messages interface for students (Fixed version with debugging)
 */
public function messages(Request $request)
{
    try {
        $user = Auth::user();
        
        // Get filter parameters
        $filter = $request->get('filter', 'all');
        $search = $request->get('search', '');
        $conversationWith = $request->get('conversation');

        // Get conversations list
        $conversations = Message::where(function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy(function($message) use ($user) {
            return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
        })
        ->map(function($messages) {
            return $messages->first();
        });
        
        $messages = collect();
        $conversationPartner = null;
        
        if ($conversationWith) {
            // Verify student can message this user
            $partner = User::find($conversationWith);
            if (!$partner || !$user->canMessageUser($partner)) {
                return redirect()->route('student.messages.index')
                    ->with('error', 'You cannot message this user.');
            }
            
            // Get messages between users
            $messages = Message::where(function($query) use ($user, $conversationWith) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $conversationWith);
            })
            ->orWhere(function($query) use ($user, $conversationWith) {
                $query->where('sender_id', $conversationWith)
                      ->where('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
            
            $conversationPartner = $partner;
            
            // Mark messages as read
            Message::where('receiver_id', $user->id)
                ->where('sender_id', $conversationWith)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Apply filters
        if ($filter === 'unread') {
            $conversations = $conversations->filter(function($conversation) use ($user) {
                return !$conversation->is_read && $conversation->receiver_id === $user->id;
            });
        } elseif ($filter === 'sent') {
            $conversations = $conversations->filter(function($conversation) use ($user) {
                return $conversation->sender_id === $user->id;
            });
        }

        // Get users that can be messaged
        $availableUsers = $this->getAvailableUsersForStudentMessaging($user);

        // Get message statistics
        $stats = [
            'total_messages' => Message::where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })->count(),
            'unread_count' => Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count(),
            'sent_count' => Message::where('sender_id', $user->id)->count(),
            'received_count' => Message::where('receiver_id', $user->id)->count(),
        ];

        $viewData = [
            'meta_title' => 'Messages | Student Portal',
            'meta_desc' => 'View and send messages to lecturers and classmates',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'messages' => $messages,
            'conversations' => $conversations,
            'availableUsers' => $availableUsers,
            'stats' => $stats,
            'filter' => $filter,
            'search' => $search,
            'conversationWith' => $conversationWith,
            'conversationPartner' => $conversationPartner,
            'user' => $user
        ];

        return view('student.messages', $viewData);

    } catch (\Exception $e) {
        Log::error('Student Messages Error: ' . $e->getMessage(), [
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // Return safe fallback
        $user = Auth::user();
        $viewData = [
            'meta_title' => 'Messages | Student Portal',
            'meta_desc' => 'View and send messages',
            'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            'conversations' => collect(),
            'messages' => collect(),
            'conversationPartner' => null,
            'availableUsers' => collect(),
            'stats' => [
                'total_messages' => 0,
                'unread_count' => 0,
                'sent_count' => 0,
                'received_count' => 0,
            ],
            'filter' => 'all',
            'search' => '',
            'conversationWith' => null,
            'user' => $user
        ];
        
        return view('student.messages', $viewData)
            ->with('error', 'Unable to load messages. Please try again.');
    }
}

/**
 * Send a new message (Student version with enhanced debugging)
 */
public function sendMessage(Request $request)
{
    Log::info('Student send message request received', [
        'user_id' => Auth::id(),
        'request_data' => $request->all(),
        'files' => $request->hasFile('attachment') ? 'Has file' : 'No file'
    ]);

    try {
        // Custom validation
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|string|exists:users,id',
            'content' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,mp3,mp4,wav,avi,mov,zip,rar'
        ], [
            'receiver_id.required' => 'Please select a recipient.',
            'receiver_id.exists' => 'The selected recipient does not exist.',
            'content.required' => 'Please enter a message.',
            'content.max' => 'Message cannot exceed 5000 characters.',
            'attachment.file' => 'The attachment must be a valid file.',
            'attachment.max' => 'The attachment cannot exceed 10MB.',
            'attachment.mimes' => 'Invalid file type. Supported: PDF, DOC, DOCX, TXT, Images, Audio, Video, ZIP, RAR.'
        ]);

        if ($validator->fails()) {
            Log::warning('Student message validation failed', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $receiver = User::find($request->receiver_id);

        if (!$receiver) {
            Log::error('Receiver not found', [
                'user_id' => Auth::id(),
                'receiver_id' => $request->receiver_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Recipient not found.'
            ], 404);
        }

        // Check if student can message the receiver
        if (!$user->canMessageUser($receiver)) {
            Log::warning('Student cannot message receiver', [
                'sender_id' => $user->id,
                'receiver_id' => $receiver->id,
                'sender_role' => $user->role,
                'receiver_role' => $receiver->role,
                'sender_level' => $user->level,
                'receiver_level' => $receiver->level ?? 'N/A'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'You can only message lecturers assigned to your level and fellow students in your level.'
            ], 403);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            try {
                $file = $request->file('attachment');
                Log::info('Processing file attachment', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]);

                // Store the file
                $attachmentPath = $file->store('message-attachments', 'public');
                
                Log::info('File stored successfully', [
                    'path' => $attachmentPath
                ]);
            } catch (\Exception $e) {
                Log::error('File upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload attachment: ' . $e->getMessage()
                ], 500);
            }
        }

        // Create the message
        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'receiver_role' => $receiver->role,
            'content' => $request->content,
            'attachment' => $attachmentPath,
            'is_read' => false,
        ]);

        Log::info('Student message created successfully', [
            'message_id' => $message->id,
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'has_attachment' => !empty($attachmentPath),
            'content_length' => strlen($request->content)
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!',
                'data' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'created_at' => $message->created_at->format('M d, Y \a\t g:i A'),
                    'time_ago' => $message->created_at->diffForHumans(),
                    'has_attachment' => $message->hasAttachment(),
                    'attachment_name' => $message->getAttachmentName(),
                    'sender' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->profile_image_url ?? '/assets/images/default-avatar.png'
                    ]
                ]
            ]);
        }

        return redirect()->route('student.messages.index', ['conversation' => $request->receiver_id])
            ->with('success', 'Message sent successfully!');

    } catch (\Exception $e) {
        Log::error('Error sending student message', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
            'receiver_id' => $request->receiver_id ?? null,
            'request_data' => $request->all()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again. Error: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Failed to send message. Please try again.');
    }
}

/**
 * Get available users for student messaging
 * Students can message:
 * 1. Lecturers who teach courses for their level
 * 2. Fellow students in the same level
 */
private function getAvailableUsersForStudentMessaging(User $user)
{
    try {
        if (!$user->isStudent()) {
            return collect();
        }

        Log::info('Getting available users for student messaging', [
            'student_id' => $user->id,
            'student_level' => $user->level
        ]);

        $availableUsers = collect();

        // Get lecturers who teach courses for this student's level
        $lecturers = User::whereIn('role', ['instructor', 'lecturer'])
            ->whereHas('taughtCourses', function($query) use ($user) {
                $query->where('level', $user->level)
                      ->where('status', 'active');
            })
            ->select('id', 'name', 'email', 'role', 'avatar', 'matric_or_staff_id', 'level')
            ->get();

        Log::info('Found lecturers for student level', [
            'student_level' => $user->level,
            'lecturers_count' => $lecturers->count(),
            'lecturer_ids' => $lecturers->pluck('id')->toArray()
        ]);

        // Get fellow students in the same level
        $fellowStudents = User::where('role', 'student')
            ->where('level', $user->level)
            ->where('id', '!=', $user->id)
            ->select('id', 'name', 'email', 'role', 'avatar', 'matric_or_staff_id', 'level')
            ->get();

        Log::info('Found fellow students', [
            'student_level' => $user->level,
            'fellow_students_count' => $fellowStudents->count(),
            'fellow_student_ids' => $fellowStudents->pluck('id')->toArray()
        ]);

        // Combine and sort
        $availableUsers = $lecturers->concat($fellowStudents)
            ->sortBy(function($availableUser) {
                // Sort lecturers first, then students
                return ($availableUser->role === 'student' ? 'z' : 'a') . $availableUser->name;
            });

        Log::info('Total available users for messaging', [
            'total_count' => $availableUsers->count(),
            'breakdown' => [
                'lecturers' => $lecturers->count(),
                'students' => $fellowStudents->count()
            ]
        ]);

        return $availableUsers;
    } catch (\Exception $e) {
        Log::error('Error getting available users for student messaging', [
            'error' => $e->getMessage(),
            'user_id' => $user->id,
            'user_level' => $user->level,
            'trace' => $e->getTraceAsString()
        ]);

        return collect();
    }
}

/**
 * Mark all messages as read
 */
public function markAllAsRead(Request $request)
{
    try {
        $user = Auth::user();
        $conversationWith = $request->get('conversation_with');
        
        Log::info('Mark all as read request', [
            'user_id' => $user->id,
            'conversation_with' => $conversationWith
        ]);
        
        if ($conversationWith) {
            // Mark messages from specific user as read
            $count = Message::where('receiver_id', $user->id)
                ->where('sender_id', $conversationWith)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            // Mark all messages as read
            $count = Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        Log::info('Messages marked as read', [
            'user_id' => $user->id,
            'count' => $count
        ]);

        return response()->json([
            'success' => true,
            'message' => "Marked {$count} messages as read",
            'count' => $count
        ]);

    } catch (\Exception $e) {
        Log::error('Error marking student messages as read', [
            'error' => $e->getMessage(),
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to mark messages as read'
        ], 500);
    }
}

/**
 * Get conversation messages (AJAX)
 */
public function getConversation(Request $request, $userId)
{
    try {
        $user = Auth::user();
        $partner = User::find($userId);
        
        if (!$partner || !$user->canMessageUser($partner)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot access this conversation'
            ], 403);
        }
        
        $messages = Message::where(function($query) use ($user, $userId) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $userId);
        })
        ->orWhere(function($query) use ($user, $userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $user->id);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();
        
        // Mark messages as read
        Message::where('receiver_id', $user->id)
            ->where('sender_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function($message) use ($user) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'attachment' => $message->attachment_url,
                    'attachment_name' => $message->getAttachmentName(),
                    'is_sender' => $message->sender_id === $user->id,
                    'created_at' => $message->created_at->format('M d, Y \a\t g:i A'),
                    'time_ago' => $message->created_at->diffForHumans(),
                    'is_read' => $message->is_read,
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                        'avatar' => $message->sender->profile_image_url ?? '/assets/images/default-avatar.png'
                    ]
                ];
            }),
            'partner' => [
                'id' => $partner->id,
                'name' => $partner->name,
                'avatar' => $partner->profile_image_url ?? '/assets/images/default-avatar.png',
                'role' => ucfirst($partner->role),
                'level' => $partner->level ?? 'N/A'
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error loading student conversation', [
            'error' => $e->getMessage(),
            'user_id' => Auth::id(),
            'conversation_with' => $userId,
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to load conversation'
        ], 500);
    }
}

public function getNotifications(Request $request)
{
    try {
        $user = Auth::user();
        
        // For now, we'll create sample notifications since there's no Notification model
        // You can replace this with actual database queries when you have a notifications table
        $notifications = $this->getSampleNotifications($user);
        
        $unreadCount = collect($notifications)->where('is_read', false)->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);

    } catch (\Exception $e) {
        Log::error('Get Notifications Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to load notifications'
        ], 500);
    }
}

/**
 * Get notification count for the authenticated student
 */
public function getNotificationCount(Request $request)
{
    try {
        $user = Auth::user();
        
        // Sample unread count - replace with actual database query
        $unreadCount = $this->getUnreadNotificationCount($user);

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);

    } catch (\Exception $e) {
        Log::error('Get Notification Count Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to get notification count'
        ], 500);
    }
}

/**
 * Mark notification as read
 */
public function markNotificationAsRead(Request $request)
{
    try {
        $request->validate([
            'notification_id' => 'required|string'
        ]);

        $user = Auth::user();
        $notificationId = $request->notification_id;
        
        // For now, we'll just simulate marking as read
        // Replace this with actual database update when you have a notifications table
        Log::info('Marking notification as read', [
            'user_id' => $user->id,
            'notification_id' => $notificationId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);

    } catch (\Exception $e) {
        Log::error('Mark Notification Read Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to mark notification as read'
        ], 500);
    }
}

/**
 * Delete notification
 */
public function deleteNotification(Request $request, $notificationId)
{
    try {
        $user = Auth::user();
        
        // For now, we'll just simulate deletion
        // Replace this with actual database deletion when you have a notifications table
        Log::info('Deleting notification', [
            'user_id' => $user->id,
            'notification_id' => $notificationId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Delete Notification Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete notification'
        ], 500);
    }
}

/**
 * Clear all notifications
 */
public function clearAllNotifications(Request $request)
{
    try {
        $user = Auth::user();
        
        // For now, we'll just simulate clearing all
        // Replace this with actual database operations when you have a notifications table
        Log::info('Clearing all notifications for user', [
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications cleared successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Clear All Notifications Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to clear notifications'
        ], 500);
    }
}

/**
 * Get sample notifications for testing (replace with actual database queries)
 */
private function getSampleNotifications($user)
{
    // Sample notifications - replace this with actual database queries
    return [
        [
            'id' => 'notif_1',
            'type' => 'assignment_created',
            'title' => 'New Assignment Available',
            'message' => 'A new assignment "PHP Basics" has been posted in Web Development course.',
            'is_read' => false,
            'created_at' => now()->subMinutes(30)->toISOString(),
            'data' => [
                'assignment_id' => 1,
                'course_id' => 1
            ]
        ],
        [
            'id' => 'notif_2',
            'type' => 'material_uploaded',
            'title' => 'New Course Material',
            'message' => 'New lecture notes have been uploaded for Database Systems.',
            'is_read' => false,
            'created_at' => now()->subHours(2)->toISOString(),
            'data' => [
                'material_id' => 1,
                'course_id' => 2
            ]
        ],
        [
            'id' => 'notif_3',
            'type' => 'assignment_submitted',
            'title' => 'Assignment Graded',
            'message' => 'Your assignment "JavaScript Functions" has been graded. Grade: 85%',
            'is_read' => true,
            'created_at' => now()->subDays(1)->toISOString(),
            'data' => [
                'assignment_id' => 2,
                'grade' => 85
            ]
        ],
        [
            'id' => 'notif_4',
            'type' => 'course_created',
            'title' => 'Course Enrollment Reminder',
            'message' => 'Don\'t forget to enroll in your remaining courses for this semester.',
            'is_read' => false,
            'created_at' => now()->subDays(2)->toISOString(),
            'data' => []
        ],
        [
            'id' => 'notif_5',
            'type' => 'warning',
            'title' => 'Assignment Deadline Approaching',
            'message' => 'Assignment "Data Structures" is due in 2 days. Don\'t forget to submit!',
            'is_read' => true,
            'created_at' => now()->subDays(3)->toISOString(),
            'data' => [
                'assignment_id' => 3,
                'deadline' => now()->addDays(2)->toISOString()
            ]
        ]
    ];
}

/**
 * Get unread notification count (replace with actual database query)
 */
private function getUnreadNotificationCount($user)
{
    // Sample count - replace with actual database query
    $notifications = $this->getSampleNotifications($user);
    return collect($notifications)->where('is_read', false)->count();
}
}