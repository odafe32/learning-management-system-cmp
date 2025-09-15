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

    public function ShowAssignments(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get assignments from enrolled courses
            $assignments = Assignment::whereHas('course', function($query) use ($user) {
                $query->whereIn('id', $user->enrolledCourses()->pluck('course_id'));
            })
            ->with([
                'course',
                'submissions' => function($query) use ($user) {
                    $query->where('student_id', $user->id);
                }
            ])
            ->where('status', 'active')
            ->orderBy('deadline', 'asc')
            ->paginate(10);

            // Categorize assignments
            $upcomingAssignments = $assignments->filter(function($assignment) {
                return $assignment->deadline > now() && $assignment->submissions->isEmpty();
            });

            $overdueAssignments = $assignments->filter(function($assignment) {
                return $assignment->deadline < now() && $assignment->submissions->isEmpty();
            });

            $submittedAssignments = $assignments->filter(function($assignment) {
                return $assignment->submissions->isNotEmpty();
            });

            $viewData = [
                'meta_title' => 'Assignments | Student Portal',
                'meta_desc' => 'View and manage your course assignments',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'assignments' => $assignments,
                'upcomingAssignments' => $upcomingAssignments,
                'overdueAssignments' => $overdueAssignments,
                'submittedAssignments' => $submittedAssignments,
                'user' => $user
            ];

            return view('student.assignments', $viewData);
        } catch (\Exception $e) {
            Log::error('Student Assignments Error: ' . $e->getMessage());
            return view('student.assignments', [
                'meta_title' => 'Assignments | Student Portal',
                'meta_desc' => 'View your course assignments',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            ]);
        }
    }

    public function SubmitAssignments()
    {
        try {
            $user = Auth::user();
            
            // Get pending assignments (not submitted and not overdue)
            $pendingAssignments = Assignment::whereHas('course', function($query) use ($user) {
                $query->whereIn('id', $user->enrolledCourses()->pluck('course_id'));
            })
            ->whereDoesntHave('submissions', function($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->where('status', 'active')
            ->where('deadline', '>', now())
            ->with('course')
            ->orderBy('deadline', 'asc')
            ->get();

            $viewData = [
                'meta_title' => 'Submit Assignments | Student Portal',
                'meta_desc' => 'Submit your pending course assignments',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'pendingAssignments' => $pendingAssignments,
                'user' => $user
            ];

            return view('student.submit-assignments', $viewData);
        } catch (\Exception $e) {
            Log::error('Submit Assignments Error: ' . $e->getMessage());
            return view('student.submit-assignments', [
                'meta_title' => 'Submit Assignments | Student Portal',
                'meta_desc' => 'Submit your course assignments',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            ]);
        }
    }

    // ==================== MATERIAL METHODS ====================

    public function viewMaterials(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get materials from enrolled courses
            $materials = Material::whereHas('course', function($query) use ($user) {
                $query->whereIn('id', $user->enrolledCourses()->pluck('course_id'));
            })
            ->with('course')
            ->orderBy('uploaded_at', 'desc')
            ->paginate(12);

            // Group materials by course
            $materialsByCourse = $materials->groupBy('course.title');

            $viewData = [
                'meta_title' => 'Course Materials | Student Portal',
                'meta_desc' => 'Access your course materials and resources',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'materials' => $materials,
                'materialsByCourse' => $materialsByCourse,
                'user' => $user
            ];

            return view('student.materials', $viewData);
        } catch (\Exception $e) {
            Log::error('Student Materials Error: ' . $e->getMessage());
            return view('student.materials', [
                'meta_title' => 'Course Materials | Student Portal',
                'meta_desc' => 'Access your course materials',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            ]);
        }
    }

    // ==================== SUBMISSION METHODS ====================

    public function viewSubmissions()
    {
        try {
            $user = Auth::user();
            
            $submissions = $user->submissions()
                ->with(['assignment.course'])
                ->orderBy('submitted_at', 'desc')
                ->paginate(10);

            $viewData = [
                'meta_title' => 'My Submissions | Student Portal',
                'meta_desc' => 'View your assignment submissions history',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'submissions' => $submissions,
                'user' => $user
            ];

            return view('student.submissions', $viewData);
        } catch (\Exception $e) {
            Log::error('Student Submissions Error: ' . $e->getMessage());
            return view('student.submissions', [
                'meta_title' => 'My Submissions | Student Portal',
                'meta_desc' => 'View your assignment submissions',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            ]);
        }
    }

    // ==================== GRADE METHODS ====================

    public function viewGrades()
    {
        try {
            $user = Auth::user();
            
            $gradedSubmissions = $user->submissions()
                ->where('status', 'graded')
                ->whereNotNull('grade')
                ->with(['assignment.course'])
                ->orderBy('graded_at', 'desc')
                ->paginate(10);

            // Calculate statistics
            $averageGrade = $gradedSubmissions->avg('grade');
            $totalGraded = $gradedSubmissions->count();
            $highestGrade = $gradedSubmissions->max('grade');

            $viewData = [
                'meta_title' => 'My Grades | Student Portal',
                'meta_desc' => 'View your assignment and course grades',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'gradedSubmissions' => $gradedSubmissions,
                'averageGrade' => round($averageGrade, 2),
                'totalGraded' => $totalGraded,
                'highestGrade' => $highestGrade,
                'user' => $user
            ];

            return view('student.grades', $viewData);
        } catch (\Exception $e) {
            Log::error('Student Grades Error: ' . $e->getMessage());
            return view('student.grades', [
                'meta_title' => 'My Grades | Student Portal',
                'meta_desc' => 'View your grades',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            ]);
        }
    }

    // ==================== FEEDBACK METHODS ====================

    public function viewFeedbacks()
    {
        try {
            $user = Auth::user();
            
            $feedbacks = $user->submissions()
                ->whereNotNull('feedback')
                ->where('feedback', '!=', '')
                ->with(['assignment.course'])
                ->orderBy('graded_at', 'desc')
                ->paginate(10);

            $viewData = [
                'meta_title' => 'Feedbacks | Student Portal',
                'meta_desc' => 'View feedback on your assignments and performance',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
                'feedbacks' => $feedbacks,
                'user' => $user
            ];

            return view('student.feedbacks', $viewData);
        } catch (\Exception $e) {
            Log::error('Student Feedbacks Error: ' . $e->getMessage());
            return view('student.feedbacks', [
                'meta_title' => 'Feedbacks | Student Portal',
                'meta_desc' => 'View feedback on your assignments',
                'meta_image' => url('pwa_assets/android-chrome-256x256.png'),
            ]);
        }
    }

    // ==================== HELPER METHODS ====================

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
            return $user->submissions()
                ->where('status', 'graded')
                ->whereNotNull('grade')
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
            ->where('status', 'active')
            ->where('deadline', '>', now())
            ->where('deadline', '<=', now()->addDays(7))
            ->with('course')
            ->orderBy('deadline', 'asc')
            ->limit(5)
            ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

}