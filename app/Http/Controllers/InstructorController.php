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

class InstructorController extends Controller
{
public function dashboard()
{
    $user = Auth::user();
    
    try {
        // Basic course statistics
        $coursesCount = Course::byInstructor($user->id)->count();
        $activeCourses = Course::byInstructor($user->id)->active()->count();
        $recentCourses = Course::byInstructor($user->id)->latest()->take(5)->get();
        $materialsCount = Material::byInstructor($user->id)->count();

        // Assignment statistics
        $assignmentsCount = Assignment::byInstructor($user->id)->count();
        $pendingGrading = Submission::whereHas('assignment', function($q) use ($user) {
            $q->where('assignments.user_id', $user->id);
        })->whereIn('status', ['submitted', 'pending'])->count();
        
        $gradedAssignments = Submission::whereHas('assignment', function($q) use ($user) {
            $q->where('assignments.user_id', $user->id);
        })->where('status', 'graded')->count();
        
        $overdueAssignments = Assignment::byInstructor($user->id)
            ->where('deadline', '<', now())
            ->whereHas('submissions', function($q) {
                $q->whereIn('status', ['submitted', 'pending']);
            })->count();

        // Student statistics - Fixed the ambiguous column issue
        $studentsCount = DB::table('users')
            ->join('course_student', 'users.id', '=', 'course_student.user_id')
            ->join('courses', 'course_student.course_id', '=', 'courses.id')
            ->where('courses.user_id', $user->id)
            ->where('users.role', 'student')
            ->distinct('users.id')
            ->count('users.id');
        
        $activeStudents = DB::table('users')
            ->join('course_student', 'users.id', '=', 'course_student.user_id')
            ->join('courses', 'course_student.course_id', '=', 'courses.id')
            ->where('courses.user_id', $user->id)
            ->where('users.role', 'student')
            ->where('course_student.status', 'active')
            ->distinct('users.id')
            ->count('users.id');

        // Message statistics
        $unreadMessages = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Upcoming deadlines
        $upcomingDeadlines = Assignment::byInstructor($user->id)
            ->where('deadline', '>', now())
            ->where('deadline', '<=', now()->addDays(7))
            ->with('course')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        // Real enrollment data for chart
        $enrollmentData = DB::table('course_student')
            ->join('courses', 'course_student.course_id', '=', 'courses.id')
            ->where('courses.user_id', $user->id)
            ->selectRaw('MONTH(course_student.enrolled_at) as month, COUNT(*) as count')
            ->whereYear('course_student.enrolled_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Fill missing months with 0
        $monthlyEnrollments = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyEnrollments[] = $enrollmentData->get($i, 0);
        }

        // Recent activities - Get real data
        $recentActivities = collect();
        
        // Get recent submissions
        $recentSubmissions = Submission::whereHas('assignment', function($q) use ($user) {
            $q->where('assignments.user_id', $user->id);
        })
        ->with(['assignment', 'student'])
        ->latest()
        ->take(3)
        ->get();

        foreach ($recentSubmissions as $submission) {
            $recentActivities->push([
                'message' => "New submission from {$submission->student->name} for {$submission->assignment->title}",
                'time' => $submission->created_at->diffForHumans(),
                'icon' => 'file-text',
                'color' => 'success'
            ]);
        }

        // Get recent course updates
        $recentCourseUpdates = Course::byInstructor($user->id)
            ->latest('updated_at')
            ->take(2)
            ->get();

        foreach ($recentCourseUpdates as $course) {
            if ($course->updated_at->gt($course->created_at)) {
                $recentActivities->push([
                    'message' => "Course \"{$course->title}\" was updated",
                    'time' => $course->updated_at->diffForHumans(),
                    'icon' => 'book-open',
                    'color' => 'primary'
                ]);
            }
        }

        // Get recent material uploads
        $recentMaterials = Material::byInstructor($user->id)
            ->with('course')
            ->latest()
            ->take(2)
            ->get();

        foreach ($recentMaterials as $material) {
            $recentActivities->push([
                'message' => "Material \"{$material->title}\" uploaded to {$material->course->title}",
                'time' => $material->created_at->diffForHumans(),
                'icon' => 'upload',
                'color' => 'warning'
            ]);
        }

        // Sort activities by time and take only the most recent 5
        $recentActivities = $recentActivities->sortByDesc(function($activity) {
            return strtotime($activity['time']);
        })->take(5)->values();

        // If no real activities, show placeholder
        if ($recentActivities->isEmpty()) {
            $recentActivities = collect([
                [
                    'message' => 'Welcome to your dashboard! Start by creating a course.',
                    'time' => 'Just now',
                    'icon' => 'info',
                    'color' => 'info'
                ]
            ]);
        }

        $viewData = [
            'meta_title' => 'Instructor Dashboard | LMS',
            'meta_desc'  => 'Manage your courses, assignments, and students',
            'meta_image' => url('assets/images/logo/logo.png'),
            
            // Course data
            'coursesCount' => $coursesCount,
            'activeCourses' => $activeCourses,
            'recentCourses' => $recentCourses,
            'materialsCount' => $materialsCount,
            
            // Assignment data
            'assignmentsCount' => $assignmentsCount,
            'pendingGrading' => $pendingGrading,
            'gradedAssignments' => $gradedAssignments,
            'overdueAssignments' => $overdueAssignments,
            
            // Student data
            'studentsCount' => $studentsCount,
            'activeStudents' => $activeStudents,
            
            // Message data
            'unreadMessages' => $unreadMessages,
            
            // Activity data
            'upcomingDeadlines' => $upcomingDeadlines,
            'recentActivities' => $recentActivities,
            
            // Chart data
            'monthlyEnrollments' => $monthlyEnrollments,
        ];

        return view('instructor.dashboard', $viewData);

    } catch (\Exception $e) {
        Log::error('Dashboard loading error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => $user->id
        ]);

        // Return dashboard with default values on error
        $viewData = [
            'meta_title' => 'Instructor Dashboard | LMS',
            'meta_desc'  => 'Manage your courses, assignments, and students',
            'meta_image' => url('assets/images/logo/logo.png'),
            
            // Default values
            'coursesCount' => 0,
            'activeCourses' => 0,
            'recentCourses' => collect(),
            'materialsCount' => 0,
            'assignmentsCount' => 0,
            'pendingGrading' => 0,
            'gradedAssignments' => 0,
            'overdueAssignments' => 0,
            'studentsCount' => 0,
            'activeStudents' => 0,
            'unreadMessages' => 0,
            'upcomingDeadlines' => collect(),
            'recentActivities' => collect([
                [
                    'message' => 'Dashboard is loading. Please refresh the page.',
                    'time' => 'Just now',
                    'icon' => 'warning',
                    'color' => 'warning'
                ]
            ]),
            'monthlyEnrollments' => [0,0,0,0,0,0,0,0,0,0,0,0],
        ];

        return view('instructor.dashboard', $viewData);
    }
}
    // Profile Management
    public function profile()
    {
        $viewData = [
            'meta_title' => 'Profile | LMS',
            'meta_desc'  => 'Manage your profile information',
            'meta_image' => url('assets/images/logo/logo.png'),
            'user' => Auth::user(),
        ];

        return view('instructor.profile', $viewData);
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

            return redirect()->route('instructor.profile')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('instructor.profile')
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = Auth::user();

            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->route('instructor.profile')
                    ->withErrors(['current_password' => 'Current password is incorrect.'])
                    ->withInput();
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('instructor.profile')
                ->with('success', 'Password updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('instructor.profile')
                ->with('error', 'Failed to update password. Please try again.');
        }
    }

       // Course Management
    public function createCourse()
    {
        $viewData = [
            'meta_title' => 'Create Course | LMS',
            'meta_desc'  => 'Create a new course',
            'meta_image' => url('assets/images/logo/logo.png'),
            'levels' => Course::getLevels(),
            'semesters' => Course::getSemesters(),
            'statuses' => Course::getStatuses(),
        ];

        return view('instructor.create-courses', $viewData);
    }

    public function storeCourse(CourseRequest $request)
    {
        // Start database transaction
        DB::beginTransaction();
        
        try {
            // Log the incoming request data for debugging
            Log::info('Course creation attempt', [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['image']), // Exclude file from log
                'has_image' => $request->hasFile('image')
            ]);

            // Validate that user is authenticated
            if (!Auth::check()) {
                throw new \Exception('User not authenticated');
            }

            // Get validated data
            $data = $request->validated();
            
            // Add user ID
            $data['user_id'] = Auth::id();
            
            // Generate unique slug
            $baseSlug = Str::slug($data['title']);
            $slug = $baseSlug;
            $counter = 1;
            
            while (Course::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
            
            // Handle image upload with better error handling
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Validate image
                if (!$image->isValid()) {
                    throw new \Exception('Invalid image file uploaded');
                }
                
                // Check file size (max 5MB)
                if ($image->getSize() > 5 * 1024 * 1024) {
                    throw new \Exception('Image file too large. Maximum size is 5MB');
                }
                
                // Check file type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!in_array($image->getMimeType(), $allowedTypes)) {
                    throw new \Exception('Invalid image type. Only JPEG, PNG, and GIF are allowed');
                }
                
                try {
                    // Create directory if it doesn't exist
                    $directory = 'courses';
                    if (!Storage::disk('public')->exists($directory)) {
                        Storage::disk('public')->makeDirectory($directory);
                    }
                    
                    // Generate unique filename
                    $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs($directory, $filename, 'public');
                    
                    if (!$imagePath) {
                        throw new \Exception('Failed to store image file');
                    }
                    
                    $data['image'] = $imagePath;
                    
                    Log::info('Image uploaded successfully', ['path' => $imagePath]);
                    
                } catch (\Exception $e) {
                    Log::error('Image upload failed', ['error' => $e->getMessage()]);
                    throw new \Exception('Failed to upload image: ' . $e->getMessage());
                }
            }

            // Validate required fields are present
            $requiredFields = ['title', 'code', 'level', 'semester', 'credit_units', 'status'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new \Exception("Required field '{$field}' is missing or empty");
                }
            }

            // Check for duplicate course code for this instructor
            $existingCourse = Course::where('code', $data['code'])
                                  ->where('user_id', Auth::id())
                                  ->first();
            
            if ($existingCourse) {
                throw new \Exception('A course with this code already exists in your courses');
            }

            // Create the course
            $course = Course::create($data);
            
            if (!$course) {
                throw new \Exception('Failed to create course record in database');
            }

            // Commit the transaction
            DB::commit();
            
            Log::info('Course created successfully', [
                'course_id' => $course->id,
                'title' => $course->title,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('instructor.courses.manage')
                ->with('success', 'Course created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            Log::error('Course creation validation failed', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('instructor.courses.create')
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Log the detailed error
            Log::error('Course creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['image'])
            ]);
            
            // Return more specific error message in development
            $errorMessage = config('app.debug') 
                ? 'Failed to create course: ' . $e->getMessage()
                : 'Failed to create course. Please try again.';
            
            return redirect()->route('instructor.courses.create')
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    public function manageCourses(Request $request)
    {
        try {
            $query = Course::byInstructor(Auth::id())->with('instructor');

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by level
            if ($request->filled('level')) {
                $query->where('level', $request->level);
            }

            // Filter by semester
            if ($request->filled('semester')) {
                $query->where('semester', $request->semester);
            }

            $courses = $query->latest()->paginate(10)->withQueryString();

            $viewData = [
                'meta_title' => 'Manage Courses | LMS',
                'meta_desc'  => 'Manage your courses',
                'meta_image' => url('assets/images/logo/logo.png'),
                'courses' => $courses,
                'levels' => Course::getLevels(),
                'semesters' => Course::getSemesters(),
                'statuses' => Course::getStatuses(),
                'filters' => $request->only(['search', 'status', 'level', 'semester']),
            ];

            return view('instructor.courses', $viewData);
            
        } catch (\Exception $e) {
            Log::error('Failed to load courses page', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Failed to load courses. Please try again.');
        }
    }

    public function editCourse(Course $course)
    {
        // Ensure the course belongs to the authenticated instructor
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course.');
        }

        $viewData = [
            'meta_title' => 'Edit Course | LMS',
            'meta_desc'  => 'Edit course information',
            'meta_image' => url('assets/images/logo/logo.png'),
            'course' => $course,
            'levels' => Course::getLevels(),
            'semesters' => Course::getSemesters(),
            'statuses' => Course::getStatuses(),
        ];

        return view('instructor.edit-course', $viewData);
    }

    public function updateCourse(CourseRequest $request, Course $course)
    {
        // Ensure the course belongs to the authenticated instructor
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course.');
        }

        DB::beginTransaction();
        
        try {
            $data = $request->validated();
            
            // Generate slug if title changed
            if ($data['title'] !== $course->title) {
                $baseSlug = Str::slug($data['title']);
                $slug = $baseSlug;
                $counter = 1;
                
                while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $data['slug'] = $slug;
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Validate image
                if (!$image->isValid()) {
                    throw new \Exception('Invalid image file uploaded');
                }
                
                try {
                    // Delete old image if exists
                    if ($course->image && Storage::disk('public')->exists($course->image)) {
                        Storage::disk('public')->delete($course->image);
                    }
                    
                    $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('courses', $filename, 'public');
                    
                    if (!$imagePath) {
                        throw new \Exception('Failed to store image file');
                    }
                    
                    $data['image'] = $imagePath;
                    
                } catch (\Exception $e) {
                    throw new \Exception('Failed to upload image: ' . $e->getMessage());
                }
            }

            $course->update($data);
            
            DB::commit();
            
            Log::info('Course updated successfully', [
                'course_id' => $course->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('instructor.courses.manage')
                ->with('success', 'Course updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Course update failed', [
                'error' => $e->getMessage(),
                'course_id' => $course->id,
                'user_id' => Auth::id()
            ]);
            
            $errorMessage = config('app.debug') 
                ? 'Failed to update course: ' . $e->getMessage()
                : 'Failed to update course. Please try again.';
            
            return redirect()->route('instructor.courses.edit', $course)
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    public function deleteCourse(Course $course)
    {
        // Ensure the course belongs to the authenticated instructor
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course.');
        }

        DB::beginTransaction();
        
        try {
            // Delete course image if exists
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }

            $courseTitle = $course->title;
            $course->delete();
            
            DB::commit();
            
            Log::info('Course deleted successfully', [
                'course_title' => $courseTitle,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('instructor.courses.manage')
                ->with('success', 'Course deleted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Course deletion failed', [
                'error' => $e->getMessage(),
                'course_id' => $course->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('instructor.courses.manage')
                ->with('error', 'Failed to delete course. Please try again.');
        }
    }
    // Material Management
    public function uploadMaterial()
    {
        $courses = Course::byInstructor(Auth::id())->active()->get();
        
        $viewData = [
            'meta_title' => 'Upload Material | LMS',
            'meta_desc'  => 'Upload lecture materials',
            'meta_image' => url('assets/images/logo/logo.png'),
            'courses' => $courses,
            'visibilityOptions' => Material::getVisibilityOptions(),
            'allowedTypes' => Material::getAllowedFileTypes(),
            'maxFileSize' => Material::getMaxFileSize(),
        ];

        return view('instructor.materials-uploads', $viewData);
    }

    public function storeMaterial(MaterialRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                // Generate unique filename
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('materials', $filename, 'public');
                
                $data['file_path'] = $filePath;
                $data['file_type'] = $file->getClientOriginalExtension();
                $data['file_size'] = round($file->getSize() / 1024); // Convert to KB
            }

            Material::create($data);

            return redirect()->route('instructor.materials.index')
                ->with('success', 'Material uploaded successfully!');

        } catch (\Exception $e) {
            return redirect()->route('instructor.materials.upload')
                ->with('error', 'Failed to upload material. Please try again.')
                ->withInput();
        }
    }

 public function viewMaterials(Request $request)
    {
        try {
            $query = Material::byInstructor(Auth::id())->with(['course']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('course', function($courseQuery) use ($search) {
                          $courseQuery->where('title', 'like', "%{$search}%")
                                      ->orWhere('code', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by course
            if ($request->filled('course_id')) {
                $query->where('course_id', $request->course_id);
            }

            // Filter by visibility
            if ($request->filled('visibility')) {
                $query->where('visibility', $request->visibility);
            }

            // Filter by file type
            if ($request->filled('file_type')) {
                $query->where('file_type', $request->file_type);
            }

            $materials = $query->latest()->paginate(12)->withQueryString();
            $courses = Course::byInstructor(Auth::id())->get();

            // Debug: Log materials data
            Log::info('Materials loaded for instructor', [
                'instructor_id' => Auth::id(),
                'materials_count' => $materials->count(),
                'total_materials' => $materials->total(),
                'filters' => $request->only(['search', 'course_id', 'visibility', 'file_type'])
            ]);

            // Debug: Check file accessibility for each material
            foreach ($materials as $material) {
                $fileExists = $material->file_path ? Storage::disk('public')->exists($material->file_path) : false;
                $fileUrl = $material->file_url;
                
                Log::info('Material file check', [
                    'material_id' => $material->id,
                    'title' => $material->title,
                    'file_path' => $material->file_path,
                    'file_exists' => $fileExists,
                    'file_url' => $fileUrl,
                    'file_type' => $material->file_type
                ]);
            }

            $viewData = [
                'meta_title' => 'View Materials | LMS',
                'meta_desc'  => 'View and manage lecture materials',
                'meta_image' => url('assets/images/logo/logo.png'),
                'materials' => $materials,
                'courses' => $courses,
                'visibilityOptions' => Material::getVisibilityOptions(),
                'filters' => $request->only(['search', 'course_id', 'visibility', 'file_type']),
            ];

            return view('instructor.materials', $viewData);

        } catch (\Exception $e) {
            Log::error('Error loading materials', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'instructor_id' => Auth::id()
            ]);

            return redirect()->route('instructor.dashboard')
                ->with('error', 'Failed to load materials. Please try again.');
        }
    }

    public function editMaterial(Material $material)
    {
        // Ensure the material belongs to the authenticated instructor
        if ($material->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this material.');
        }

        $courses = Course::byInstructor(Auth::id())->get();

        $viewData = [
            'meta_title' => 'Edit Material | LMS',
            'meta_desc'  => 'Edit material information',
            'meta_image' => url('assets/images/logo/logo.png'),
            'material' => $material,
            'courses' => $courses,
            'visibilityOptions' => Material::getVisibilityOptions(),
        ];

        return view('instructor.materials-edit', $viewData);
    }

    public function updateMaterial(MaterialRequest $request, Material $material)
    {
        // Ensure the material belongs to the authenticated instructor
        if ($material->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this material.');
        }

        try {
            $data = $request->validated();

            // Handle file upload if new file is provided
            if ($request->hasFile('file')) {
                // Delete old file
                if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                    Storage::disk('public')->delete($material->file_path);
                }

                $file = $request->file('file');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('materials', $filename, 'public');
                
                $data['file_path'] = $filePath;
                $data['file_type'] = $file->getClientOriginalExtension();
                $data['file_size'] = round($file->getSize() / 1024);
            }

            $material->update($data);

            return redirect()->route('instructor.materials.index')
                ->with('success', 'Material updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('instructor.materials.edit', $material)
                ->with('error', 'Failed to update material. Please try again.')
                ->withInput();
        }
    }

    public function deleteMaterial(Material $material)
    {
        // Ensure the material belongs to the authenticated instructor
        if ($material->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this material.');
        }

        try {
            $material->delete(); // File deletion is handled in the model's deleting event

            return redirect()->route('instructor.materials.index')
                ->with('success', 'Material deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('instructor.materials.index')
                ->with('error', 'Failed to delete material. Please try again.');
        }
    }

    public function downloadMaterial(Material $material)
    {
        try {
            // Check authorization
            if ($material->user_id !== Auth::id() && $material->visibility !== 'public') {
                abort(403, 'Unauthorized access to this material.');
            }

            if (!$material->file_path || !Storage::disk('public')->exists($material->file_path)) {
                abort(404, 'File not found.');
            }

            $filePath = Storage::disk('public')->path($material->file_path);
            $fileName = $material->title . '.' . $material->file_type;

            Log::info('Material download', [
                'material_id' => $material->id,
                'user_id' => Auth::id(),
                'file_name' => $fileName
            ]);

            return response()->download($filePath, $fileName);

        } catch (\Exception $e) {
            Log::error('Error downloading material', [
                'material_id' => $material->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to download file.');
        }
    }

   public function serveMaterial(Material $material)
    {
        try {
            Log::info('Serving material request', [
                'material_id' => $material->id,
                'title' => $material->title,
                'file_path' => $material->file_path,
                'user_id' => Auth::id(),
                'material_owner' => $material->user_id,
                'visibility' => $material->visibility
            ]);

            // Check authorization
            if ($material->user_id !== Auth::id() && $material->visibility !== 'public') {
                Log::warning('Unauthorized material access attempt', [
                    'material_id' => $material->id,
                    'user_id' => Auth::id(),
                    'material_owner' => $material->user_id
                ]);
                abort(403, 'Unauthorized access to this material.');
            }

            // Check if file exists
            if (!$material->file_path) {
                Log::error('Material has no file path', ['material_id' => $material->id]);
                abort(404, 'File path not found.');
            }

            if (!Storage::disk('public')->exists($material->file_path)) {
                Log::error('Material file does not exist', [
                    'material_id' => $material->id,
                    'file_path' => $material->file_path,
                    'full_path' => Storage::disk('public')->path($material->file_path)
                ]);
                abort(404, 'File not found on server.');
            }

            $filePath = Storage::disk('public')->path($material->file_path);
            $mimeType = Storage::disk('public')->mimeType($material->file_path);
            $fileSize = Storage::disk('public')->size($material->file_path);
            
            Log::info('File details', [
                'material_id' => $material->id,
                'file_path' => $filePath,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'file_exists_on_disk' => file_exists($filePath)
            ]);

            // Set appropriate headers for file serving
            $headers = [
                'Content-Type' => $mimeType,
                'Content-Length' => $fileSize,
                'Content-Disposition' => 'inline; filename="' . $material->title . '.' . $material->file_type . '"',
                'Cache-Control' => 'public, max-age=3600',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'SAMEORIGIN'
            ];

            return response()->file($filePath, $headers);

        } catch (\Exception $e) {
            Log::error('Error serving material', [
                'material_id' => $material->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(500, 'Error serving file: ' . $e->getMessage());
        }
    }

   public function streamMaterial(Material $material)
    {
        try {
            // Check authorization
            if ($material->user_id !== Auth::id() && $material->visibility !== 'public') {
                abort(403, 'Unauthorized access to this material.');
            }

            if (!$material->file_path || !Storage::disk('public')->exists($material->file_path)) {
                abort(404, 'File not found.');
            }

            $filePath = Storage::disk('public')->path($material->file_path);
            $mimeType = Storage::disk('public')->mimeType($material->file_path);
            $fileSize = Storage::disk('public')->size($material->file_path);
            
            // Handle range requests for video/audio streaming
            $headers = [
                'Content-Type' => $mimeType,
                'Accept-Ranges' => 'bytes',
                'Content-Length' => $fileSize,
                'Cache-Control' => 'public, max-age=3600',
                'Access-Control-Allow-Origin' => '*',
            ];

            if (request()->hasHeader('Range')) {
                return $this->handleRangeRequest($filePath, $fileSize, $mimeType);
            }

            return response()->file($filePath, $headers);

        } catch (\Exception $e) {
            Log::error('Error streaming material', [
                'material_id' => $material->id,
                'error' => $e->getMessage()
            ]);

            abort(500, 'Error streaming file.');
        }
    }


    private function handleRangeRequest($filePath, $fileSize, $mimeType)
    {
        $range = request()->header('Range');
        $ranges = explode('=', $range)[1];
        $parts = explode('-', $ranges);
        
        $start = intval($parts[0]);
        $end = isset($parts[1]) && $parts[1] !== '' ? intval($parts[1]) : $fileSize - 1;
        
        $length = $end - $start + 1;
        
        $headers = [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
            'Content-Length' => $length,
            'Content-Range' => "bytes $start-$end/$fileSize",
            'Cache-Control' => 'public, max-age=3600',
            'Access-Control-Allow-Origin' => '*',
        ];
        
        $stream = fopen($filePath, 'rb');
        fseek($stream, $start);
        $data = fread($stream, $length);
        fclose($stream);
        
        return response($data, 206, $headers);
    }
  // Debug endpoint to check material details
    public function debugMaterial(Material $material)
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $fileExists = $material->file_path ? Storage::disk('public')->exists($material->file_path) : false;
        $filePath = $material->file_path ? Storage::disk('public')->path($material->file_path) : null;
        $fileUrl = $material->file_url;

        $debug = [
            'material' => [
                'id' => $material->id,
                'title' => $material->title,
                'file_path' => $material->file_path,
                'file_type' => $material->file_type,
                'file_size' => $material->file_size,
                'visibility' => $material->visibility,
                'user_id' => $material->user_id,
                'course_id' => $material->course_id,
            ],
            'file_info' => [
                'file_exists_in_storage' => $fileExists,
                'file_exists_on_disk' => $filePath ? file_exists($filePath) : false,
                'full_file_path' => $filePath,
                'file_url' => $fileUrl,
                'storage_disk_path' => Storage::disk('public')->path(''),
                'public_path' => public_path('storage'),
                'storage_link_exists' => is_link(public_path('storage')),
            ],
            'auth' => [
                'user_id' => Auth::id(),
                'can_access' => $material->user_id === Auth::id() || $material->visibility === 'public',
            ],
            'routes' => [
                'serve_url' => route('instructor.materials.serve', $material),
                'download_url' => route('instructor.materials.download', $material),
            ]
        ];

        if ($fileExists && $filePath) {
            try {
                $debug['file_details'] = [
                    'mime_type' => Storage::disk('public')->mimeType($material->file_path),
                    'size_bytes' => Storage::disk('public')->size($material->file_path),
                    'last_modified' => Storage::disk('public')->lastModified($material->file_path),
                ];
            } catch (\Exception $e) {
                $debug['file_details_error'] = $e->getMessage();
            }
        }

        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
    }

public function createAssignment()
{
    $courses = Course::byInstructor(Auth::id())->active()->get();
    
    $viewData = [
        'meta_title' => 'Create Assignment | LMS',
        'meta_desc'  => 'Create a new assignment',
        'meta_image' => url('assets/images/logo/logo.png'),
        'courses' => $courses,
        'statuses' => Assignment::getStatuses(),
    ];

    return view('instructor.create-assignments', $viewData);
}

public function storeAssignment(AssignmentRequest $request)
{
    try {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        
        // Generate slug
        $data['slug'] = Str::slug($data['title']);

        $assignment = Assignment::create($data);

        return redirect()->route('instructor.assignments.manage')
            ->with('success', 'Assignment created successfully!');

    } catch (\Exception $e) {
        return redirect()->route('instructor.assignments.create')
            ->with('error', 'Failed to create assignment. Please try again.')
            ->withInput();
    }
}

public function manageAssignments(Request $request)
{
    $query = Assignment::byInstructor(Auth::id())->with(['course']);

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('course', function($courseQuery) use ($search) {
                  $courseQuery->where('title', 'like', "%{$search}%")
                              ->orWhere('code', 'like', "%{$search}%");
              });
        });
    }

    // Filter by course
    if ($request->filled('course_id')) {
        $query->where('course_id', $request->course_id);
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by deadline status
    if ($request->filled('deadline_status')) {
        if ($request->deadline_status === 'upcoming') {
            $query->upcoming();
        } elseif ($request->deadline_status === 'overdue') {
            $query->overdue();
        }
    }

    $assignments = $query->latest()->paginate(10)->withQueryString();
    $courses = Course::byInstructor(Auth::id())->get();

    $viewData = [
        'meta_title' => 'Manage Assignments | LMS',
        'meta_desc'  => 'Manage your assignments',
        'meta_image' => url('assets/images/logo/logo.png'),
        'assignments' => $assignments,
        'courses' => $courses,
        'statuses' => Assignment::getStatuses(),
        'filters' => $request->only(['search', 'course_id', 'status', 'deadline_status']),
    ];

    return view('instructor.assignments', $viewData);
}

public function editAssignment(Assignment $assignment)
{
    // Ensure the assignment belongs to the authenticated instructor
    if ($assignment->user_id !== Auth::id()) {
        abort(403, 'Unauthorized access to this assignment.');
    }

    $courses = Course::byInstructor(Auth::id())->get();

    $viewData = [
        'meta_title' => 'Edit Assignment | LMS',
        'meta_desc'  => 'Edit assignment information',
        'meta_image' => url('assets/images/logo/logo.png'),
        'assignment' => $assignment,
        'courses' => $courses,
        'statuses' => Assignment::getStatuses(),
    ];

    return view('instructor.edit-assignment', $viewData);
}

public function updateAssignment(AssignmentRequest $request, Assignment $assignment)
{
    // Ensure the assignment belongs to the authenticated instructor
    if ($assignment->user_id !== Auth::id()) {
        abort(403, 'Unauthorized access to this assignment.');
    }

    try {
        $data = $request->validated();
        
        // Generate slug if title changed
        if ($data['title'] !== $assignment->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        $assignment->update($data);

        return redirect()->route('instructor.assignments.manage')
            ->with('success', 'Assignment updated successfully!');

    } catch (\Exception $e) {
        return redirect()->route('instructor.assignments.edit', $assignment)
            ->with('error', 'Failed to update assignment. Please try again.')
            ->withInput();
    }
}

public function deleteAssignment(Assignment $assignment)
{
    // Ensure the assignment belongs to the authenticated instructor
    if ($assignment->user_id !== Auth::id()) {
        abort(403, 'Unauthorized access to this assignment.');
    }

    try {
        $assignment->delete();

        return redirect()->route('instructor.assignments.manage')
            ->with('success', 'Assignment deleted successfully!');

    } catch (\Exception $e) {
        return redirect()->route('instructor.assignments.manage')
            ->with('error', 'Failed to delete assignment. Please try again.');
    }
}

public function viewAssignment(Assignment $assignment)
{
    // Ensure the assignment belongs to the authenticated instructor
    if ($assignment->user_id !== Auth::id()) {
        abort(403, 'Unauthorized access to this assignment.');
    }

    $assignment->load(['course', 'submissions.student']);

    $viewData = [
        'meta_title' => $assignment->title . ' | LMS',
        'meta_desc'  => 'View assignment details and submissions',
        'meta_image' => url('assets/images/logo/logo.png'),
        'assignment' => $assignment,
    ];

    return view('instructor.view-assignment', $viewData);
}
// Add these methods to your InstructorController.php

// Submissions
public function viewSubmissions(Request $request)
{
    $query = Submission::with(['assignment.course', 'student'])
        ->whereHas('assignment', function($q) {
            $q->where('user_id', Auth::id());
        });

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('student', function($studentQuery) use ($search) {
                $studentQuery->where('name', 'like', "%{$search}%")
                           ->orWhere('matric_or_staff_id', 'like', "%{$search}%");
            })->orWhereHas('assignment', function($assignmentQuery) use ($search) {
                $assignmentQuery->where('title', 'like', "%{$search}%");
            });
        });
    }

    // Filter by assignment
    if ($request->filled('assignment_id')) {
        $query->where('assignment_id', $request->assignment_id);
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by course
    if ($request->filled('course_id')) {
        $query->whereHas('assignment', function($q) use ($request) {
            $q->where('course_id', $request->course_id);
        });
    }

    // Filter by submission date
    if ($request->filled('submission_date')) {
        $date = $request->submission_date;
        $query->whereDate('submitted_at', $date);
    }

    $submissions = $query->latest('submitted_at')->paginate(15)->withQueryString();

    // Get filter options
    $assignments = Assignment::where('user_id', Auth::id())
        ->with('course')
        ->orderBy('title')
        ->get();

    $courses = Course::where('user_id', Auth::id())
        ->orderBy('title')
        ->get();

    $viewData = [
        'meta_title' => 'View Submissions | LMS',
        'meta_desc'  => 'View student submissions',
        'meta_image' => url('assets/images/logo/logo.png'),
        'submissions' => $submissions,
        'assignments' => $assignments,
        'courses' => $courses,
        'statuses' => Submission::getStatuses(),
        'filters' => $request->only(['search', 'assignment_id', 'status', 'course_id', 'submission_date']),
    ];

    return view('instructor.submissions', $viewData);
}

public function gradeAssignments(Request $request)
{
    $query = Submission::with(['assignment.course', 'student'])
        ->whereHas('assignment', function($q) {
            $q->where('user_id', Auth::id());
        })
        ->whereIn('status', ['submitted', 'pending']);

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('student', function($studentQuery) use ($search) {
                $studentQuery->where('name', 'like', "%{$search}%")
                           ->orWhere('matric_or_staff_id', 'like', "%{$search}%");
            })->orWhereHas('assignment', function($assignmentQuery) use ($search) {
                $assignmentQuery->where('title', 'like', "%{$search}%");
            });
        });
    }

    // Filter by assignment
    if ($request->filled('assignment_id')) {
        $query->where('assignment_id', $request->assignment_id);
    }

    // Filter by course
    if ($request->filled('course_id')) {
        $query->whereHas('assignment', function($q) use ($request) {
            $q->where('course_id', $request->course_id);
        });
    }

    // Sort by priority (overdue first, then by submission date)
    $query->join('assignments', 'submissions.assignment_id', '=', 'assignments.id')
          ->orderByRaw("
              CASE 
                  WHEN assignments.deadline < NOW() THEN 1 
                  ELSE 2 
              END,
              submissions.submitted_at ASC
          ")
          ->select('submissions.*');

    $submissions = $query->paginate(15)->withQueryString();

    // Get filter options
    $assignments = Assignment::where('user_id', Auth::id())
        ->with('course')
        ->whereHas('submissions', function($q) {
            $q->whereIn('status', ['submitted', 'pending']);
        })
        ->orderBy('title')
        ->get();

    $courses = Course::where('user_id', Auth::id())
        ->orderBy('title')
        ->get();

    $viewData = [
        'meta_title' => 'Grade Assignments | LMS',
        'meta_desc'  => 'Grade student assignments',
        'meta_image' => url('assets/images/logo/logo.png'),
        'submissions' => $submissions,
        'assignments' => $assignments,
        'courses' => $courses,
        'filters' => $request->only(['search', 'assignment_id', 'course_id']),
    ];

    return view('instructor.grade-assignments', $viewData);
}

public function gradeSubmission(Request $request, Submission $submission)
{
    // Ensure the submission belongs to the instructor's assignment
    if ($submission->assignment->user_id !== Auth::id()) {
        abort(403, 'Unauthorized access to this submission.');
    }

    $request->validate([
        'grade' => 'required|numeric|min:0|max:100',
        'feedback' => 'nullable|string|max:2000',
    ]);

    $submission->update([
        'grade' => $request->grade,
        'feedback' => $request->feedback,
        'status' => 'graded',
        'graded_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Submission graded successfully!');
}

// Add this method to fix the storeGrade error
public function storeGrade(Request $request, Submission $submission)
{
    return $this->gradeSubmission($request, $submission);
}

public function viewSubmissionDetail(Submission $submission)
{
    // Ensure the submission belongs to the instructor's assignment
    if ($submission->assignment->user_id !== Auth::id()) {
        abort(403, 'Unauthorized access to this submission.');
    }

    $submission->load(['assignment.course', 'student']);

    $viewData = [
        'meta_title' => 'Submission Details | LMS',
        'meta_desc'  => 'View submission details',
        'meta_image' => url('assets/images/logo/logo.png'),
        'submission' => $submission,
    ];

    return view('instructor.submission-detail', $viewData);
}

    // Students
  public function viewEnrolledStudents(Request $request)
{
    $instructor = Auth::user();
    
    // Get instructor's courses
    $instructorCourses = Course::where('user_id', $instructor->id)->get();
    
    if ($instructorCourses->isEmpty()) {
        $viewData = [
            'meta_title' => 'Enrolled Students | LMS',
            'meta_desc' => 'View enrolled students',
            'meta_image' => url('assets/images/logo/logo.png'),
            'courses' => collect(),
            'students' => collect(),
            'selectedCourseId' => 'all',
            'searchTerm' => '',
            'statusFilter' => 'all',
            'sortBy' => 'name',
            'sortOrder' => 'asc',
            'stats' => [
                'total_enrollments' => 0,
                'active_enrollments' => 0,
                'inactive_enrollments' => 0,
                'completed_enrollments' => 0,
                'dropped_enrollments' => 0,
                'recent_enrollments' => 0,
            ],
            'availableStatuses' => CourseStudent::getStatuses(),
        ];

        return view('instructor.students', $viewData);
    }

    // Get filter parameters
    $selectedCourseId = $request->get('course_id', 'all');
    $searchTerm = $request->get('search', '');
    $statusFilter = $request->get('status', 'all');
    $sortBy = $request->get('sort_by', 'name');
    $sortOrder = $request->get('sort_order', 'asc');

    // Build the query
    $studentsQuery = User::query()
        ->select([
            'users.*',
            'course_student.enrolled_at',
            'course_student.status as enrollment_status',
            'courses.title as course_title',
            'courses.code as course_code',
            'courses.id as course_id'
        ])
        ->join('course_student', 'users.id', '=', 'course_student.user_id')
        ->join('courses', 'course_student.course_id', '=', 'courses.id')
        ->where('courses.user_id', $instructor->id)
        ->where('users.role', User::ROLE_STUDENT);

    // Apply filters
    if ($selectedCourseId !== 'all') {
        $studentsQuery->where('courses.id', $selectedCourseId);
    }

    if ($statusFilter !== 'all') {
        $studentsQuery->where('course_student.status', $statusFilter);
    }

    if (!empty($searchTerm)) {
        $studentsQuery->where(function($q) use ($searchTerm) {
            $q->where('users.name', 'like', "%{$searchTerm}%")
              ->orWhere('users.email', 'like', "%{$searchTerm}%")
              ->orWhere('users.matric_or_staff_id', 'like', "%{$searchTerm}%");
        });
    }

// Check for success parameter and set flash message
    if ($request->has('success')) {
        session()->flash('success', 'Student has been successfully removed from the course.');
    }


    // Apply sorting
    switch ($sortBy) {
        case 'name':
            $studentsQuery->orderBy('users.name', $sortOrder);
            break;
        case 'course':
            $studentsQuery->orderBy('courses.title', $sortOrder);
            break;
        case 'enrolled_at':
            $studentsQuery->orderBy('course_student.enrolled_at', $sortOrder);
            break;
        case 'status':
            $studentsQuery->orderBy('course_student.status', $sortOrder);
            break;
        default:
            $studentsQuery->orderBy('users.name', 'asc');
    }

    // Get results and fix dates
    $students = $studentsQuery->paginate(15)->withQueryString();
    
    // Convert enrolled_at strings to Carbon instances
    $students->getCollection()->transform(function ($student) {
        if ($student->enrolled_at) {
            $student->enrolled_at = \Carbon\Carbon::parse($student->enrolled_at);
        }
        return $student;
    });

    // Get statistics
    $stats = $this->getEnrollmentStats($instructor->id, $selectedCourseId);

    // Get selected course
    $selectedCourse = null;
    if ($selectedCourseId !== 'all') {
        $selectedCourse = $instructorCourses->find($selectedCourseId);
    }

    $viewData = [
        'meta_title' => 'Enrolled Students | LMS',
        'meta_desc' => 'View and manage enrolled students',
        'meta_image' => url('assets/images/logo/logo.png'),
        'courses' => $instructorCourses,
        'students' => $students,
        'selectedCourse' => $selectedCourse,
        'selectedCourseId' => $selectedCourseId,
        'searchTerm' => $searchTerm,
        'statusFilter' => $statusFilter,
        'sortBy' => $sortBy,
        'sortOrder' => $sortOrder,
        'stats' => $stats,
        'availableStatuses' => CourseStudent::getStatuses(),
    ];

    return view('instructor.students', $viewData);
}
/**
 * Get enrollment statistics for instructor
 */
private function getEnrollmentStats($instructorId, $courseId = 'all')
{
    $baseQuery = DB::table('course_student')
        ->join('courses', 'course_student.course_id', '=', 'courses.id')
        ->where('courses.user_id', $instructorId);

    if ($courseId !== 'all') {
        $baseQuery->where('courses.id', $courseId);
    }

    return [
        'total_enrollments' => $baseQuery->count(),
        'active_enrollments' => (clone $baseQuery)->where('course_student.status', 'active')->count(),
        'inactive_enrollments' => (clone $baseQuery)->where('course_student.status', 'inactive')->count(),
        'completed_enrollments' => (clone $baseQuery)->where('course_student.status', 'completed')->count(),
        'dropped_enrollments' => (clone $baseQuery)->where('course_student.status', 'dropped')->count(),
        'recent_enrollments' => (clone $baseQuery)
            ->where('course_student.enrolled_at', '>=', now()->subDays(30))
            ->count(),
    ];
}
    /**
     * Update student enrollment status
     */
    public function updateStudentStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|in:active,inactive,completed,dropped',
        ]);

        $instructor = Auth::user();
        
        // Verify the course belongs to the instructor
        $course = Course::where('id', $request->course_id)
                       ->where('user_id', $instructor->id)
                       ->first();

        if (!$course) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Update enrollment status
        $updated = CourseStudent::where('user_id', $request->user_id)
                                ->where('course_id', $request->course_id)
                                ->update([
                                    'status' => $request->status,
                                    'updated_at' => now(),
                                ]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Student enrollment status updated successfully.',
            ]);
        }

        return response()->json(['error' => 'Failed to update status'], 500);
    }

   /**
 * Remove student from course
 */
public function removeStudentFromCourse(Request $request)
{
    try {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id'
        ]);

        $instructor = Auth::user();
        $userId = $request->user_id;
        $courseId = $request->course_id;

        // Verify the course belongs to the instructor
        $course = Course::where('id', $courseId)
            ->where('user_id', $instructor->id)
            ->first();

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found or you do not have permission to manage this course.'
            ], 403);
        }

        // Get student details for the success message
        $student = User::find($userId);
        
        // Remove the enrollment
        $deleted = CourseStudent::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => "Student {$student->name} has been successfully removed from {$course->code}.",
                'redirect' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Student enrollment not found.'
            ], 404);
        }

    } catch (\Exception $e) {
        Log::error('Error removing student from course', [
            'error' => $e->getMessage(),
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
            'instructor_id' => Auth::id()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while removing the student.'
        ], 500);
    }
}

    /**
     * Export enrolled students to CSV
     */
    public function exportEnrolledStudents(Request $request)
    {
        $instructor = Auth::user();
        $courseId = $request->get('course_id', 'all');

        // Build query similar to viewEnrolledStudents
        $studentsQuery = User::query()
            ->select([
                'users.name',
                'users.email',
                'users.matric_or_staff_id',
                'users.department',
                'users.faculty',
                'users.level',
                'course_student.enrolled_at',
                'course_student.status as enrollment_status',
                'courses.title as course_title',
                'courses.code as course_code'
            ])
            ->join('course_student', 'users.id', '=', 'course_student.user_id')
            ->join('courses', 'course_student.course_id', '=', 'courses.id')
            ->where('courses.user_id', $instructor->id)
            ->where('users.role', User::ROLE_STUDENT);

        if ($courseId !== 'all') {
            $studentsQuery->where('courses.id', $courseId);
        }

        $students = $studentsQuery->orderBy('users.name')->get();

        $filename = 'enrolled_students_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Name',
                'Email',
                'Matric/Staff ID',
                'Department',
                'Faculty',
                'Level',
                'Course Code',
                'Course Title',
                'Enrollment Status',
                'Enrolled At'
            ]);

            // Add data rows
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->name,
                    $student->email,
                    $student->matric_or_staff_id,
                    $student->department,
                    $student->faculty,
                    $student->level,
                    $student->course_code,
                    $student->course_title,
                    ucfirst($student->enrollment_status),
                    $student->enrolled_at ? $student->enrolled_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
 /**
 * Display messages page
 */
public function messages(Request $request)
{
    $user = Auth::user();
    
    // Get filter parameters
    $filter = $request->get('filter', 'all');
    $search = $request->get('search', '');
    $conversationWith = $request->get('conversation');

    // Get conversations list
    $conversations = Message::getConversationsForUser($user->id, 20);
    
    $messages = collect();
    $conversationPartner = null;
    
    if ($conversationWith) {
        // Show specific conversation
        $messages = Message::conversation($user->id, $conversationWith)
            ->with(['sender', 'receiver'])
            ->get();
        $conversationPartner = User::find($conversationWith);
        
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
    $availableUsers = $this->getAvailableUsersForMessaging($user);

    // Get message statistics
    $stats = [
        'total_messages' => Message::forUser($user->id)->count(),
        'unread_count' => Message::getUnreadCountForUser($user->id),
        'sent_count' => Message::where('sender_id', $user->id)->count(),
        'received_count' => Message::where('receiver_id', $user->id)->count(),
    ];

    $viewData = [
        'meta_title' => 'Messages | LMS',
        'meta_desc' => 'View and send messages',
        'meta_image' => url('assets/images/logo/logo.png'),
        'messages' => $messages,
        'conversations' => $conversations,
        'availableUsers' => $availableUsers,
        'stats' => $stats,
        'filter' => $filter,
        'search' => $search,
        'conversationWith' => $conversationWith,
        'conversationPartner' => $conversationPartner,
    ];

    return view('instructor.messages', $viewData);
}

/**
 * Send a new message
 */
public function sendMessage(Request $request)
{
    // Add debugging
    Log::info('Send message request received', [
        'user_id' => Auth::id(),
        'request_data' => $request->all()
    ]);

    try {
        // Custom validation with detailed error messages
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
            'attachment.mimes' => 'The attachment must be a file of type: pdf, doc, docx, txt, jpg, jpeg, png, gif, mp3, mp4, wav, avi, mov, zip, rar.'
        ]);

        if ($validator->fails()) {
            Log::warning('Message validation failed', [
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

        // Check if user can message the receiver
        if (!$this->canMessageUser($user, $receiver)) {
            Log::warning('User cannot message receiver', [
                'sender_id' => $user->id,
                'receiver_id' => $receiver->id,
                'sender_role' => $user->role,
                'receiver_role' => $receiver->role
            ]);

            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to message this user.'
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

        Log::info('Message created successfully', [
            'message_id' => $message->id,
            'has_attachment' => !empty($attachmentPath)
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!',
                'data' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'created_at' => $message->formatted_date,
                    'time_ago' => $message->time_ago,
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

        return redirect()->route('instructor.messages.index', ['conversation' => $request->receiver_id])
            ->with('success', 'Message sent successfully!');

    } catch (\Exception $e) {
        Log::error('Error sending message', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
            'receiver_id' => $request->receiver_id ?? null
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
        Log::error('Error marking message as read', [
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
 * Mark all messages as read
 */
public function markAllAsRead(Request $request)
{
    try {
        $user = Auth::user();
        $conversationWith = $request->get('conversation_with');
        
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

        return response()->json([
            'success' => true,
            'message' => "Marked {$count} messages as read",
            'count' => $count
        ]);

    } catch (\Exception $e) {
        Log::error('Error marking messages as read', [
            'error' => $e->getMessage(),
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to mark messages as read'
        ], 500);
    }
}

/**
 * Delete a message
 */
public function deleteMessage(Request $request, $messageId)
{
    try {
        $user = Auth::user();
        $message = Message::where('id', $messageId)
            ->where(function($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
            })
            ->firstOrFail();

        // Delete attachment if exists
        if ($message->hasAttachment()) {
            Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully'
            ]);
        }

        return redirect()->route('instructor.messages.index')
            ->with('success', 'Message deleted successfully');

    } catch (\Exception $e) {
        Log::error('Error deleting message', [
            'error' => $e->getMessage(),
            'message_id' => $messageId,
            'user_id' => Auth::id()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete message'
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Failed to delete message');
    }
}

/**
 * Get conversation messages (AJAX)
 */
public function getConversation(Request $request, $userId)
{
    try {
        $user = Auth::user();
        $messages = Message::conversation($user->id, $userId)
            ->with(['sender', 'receiver'])
            ->get();
        
        // Mark messages as read
        Message::where('receiver_id', $user->id)
            ->where('sender_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversationPartner = User::find($userId);

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function($message) use ($user) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'attachment' => $message->attachment_url,
                    'attachment_name' => $message->getAttachmentName(),
                    'is_sender' => $message->sender_id === $user->id,
                    'created_at' => $message->formatted_date,
                    'time_ago' => $message->time_ago,
                    'is_read' => $message->is_read,
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                        'avatar' => $message->sender->profile_image_url ?? '/assets/images/default-avatar.png'
                    ]
                ];
            }),
            'partner' => [
                'id' => $conversationPartner->id,
                'name' => $conversationPartner->name,
                'avatar' => $conversationPartner->profile_image_url ?? '/assets/images/default-avatar.png',
                'role' => ucfirst($conversationPartner->role)
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error loading conversation', [
            'error' => $e->getMessage(),
            'user_id' => Auth::id(),
            'conversation_with' => $userId
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to load conversation'
        ], 500);
    }
}

/**
 * Search users for messaging (AJAX)
 */
public function searchUsers(Request $request)
{
    try {
        $search = $request->get('search', '');
        $user = Auth::user();

        if (empty($search)) {
            return response()->json([
                'success' => true,
                'users' => []
            ]);
        }

        $availableUsers = $this->getAvailableUsersForMessaging($user);
        
        $filteredUsers = $availableUsers->filter(function($availableUser) use ($search) {
            return stripos($availableUser->name, $search) !== false ||
                   stripos($availableUser->email, $search) !== false ||
                   stripos($availableUser->matric_or_staff_id ?? '', $search) !== false;
        })->take(10);

        return response()->json([
            'success' => true,
            'users' => $filteredUsers->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => ucfirst($user->role),
                    'avatar' => $user->profile_image_url ?? '/assets/images/default-avatar.png',
                    'display_name' => $user->name . ' (' . ucfirst($user->role) . ')'
                ];
            })->values()
        ]);
    } catch (\Exception $e) {
        Log::error('Error searching users', [
            'error' => $e->getMessage(),
            'search' => $search
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to search users'
        ], 500);
    }
}
/**
 * Get available users for messaging
 */
private function getAvailableUsersForMessaging(User $user)
{
    try {
        $query = User::where('id', '!=', $user->id);

        if ($user->role === 'instructor') {
            // Instructors can message:
            // 1. Students enrolled in their courses
            // 2. Admins
            $studentIds = CourseStudent::whereIn('course_id', 
                $user->taughtCourses()->pluck('id')
            )->pluck('user_id');

            $query->where(function($q) use ($studentIds) {
                $q->whereIn('id', $studentIds)
                  ->orWhere('role', 'admin');
            });
        }

        return $query->select('id', 'name', 'email', 'role', 'avatar', 'matric_or_staff_id')
                    ->orderBy('role')
                    ->orderBy('name')
                    ->get();
    } catch (\Exception $e) {
        Log::error('Error getting available users', [
            'error' => $e->getMessage(),
            'user_id' => $user->id
        ]);

        return collect(); // Return empty collection on error
    }
}

/**
 * Check if user can message another user
 */
private function canMessageUser(User $sender, User $receiver): bool
{
    try {
        if ($sender->role === 'admin') {
            return true; // Admins can message anyone
        }

        if ($sender->role === 'instructor') {
            // Instructors can message admins
            if ($receiver->role === 'admin') {
                return true;
            }

            // Instructors can message students in their courses
            if ($receiver->role === 'student') {
                $studentCourseIds = CourseStudent::where('user_id', $receiver->id)->pluck('course_id');
                $instructorCourseIds = $sender->taughtCourses()->pluck('id');
                
                return $studentCourseIds->intersect($instructorCourseIds)->isNotEmpty();
            }
        }

        return false;
    } catch (\Exception $e) {
        Log::error('Error checking message permissions', [
            'error' => $e->getMessage(),
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id
        ]);

        return false; // Deny access on error
    }
}
  
}