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
use App\Models\Course;
use App\Models\Material;
use Illuminate\Support\Str;

class InstructorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $coursesCount = Course::byInstructor($user->id)->count();
        $activeCourses = Course::byInstructor($user->id)->active()->count();
        $recentCourses = Course::byInstructor($user->id)->latest()->take(5)->get();
        $materialsCount = Material::byInstructor($user->id)->count();

        $viewData = [
            'meta_title' => 'Instructor Dashboard | LMS',
            'meta_desc'  => 'Manage your courses, assignments, and students',
            'meta_image' => url('assets/images/logo/logo.png'),
            'coursesCount' => $coursesCount,
            'activeCourses' => $activeCourses,
            'recentCourses' => $recentCourses,
            'materialsCount' => $materialsCount,
        ];

        return view('instructor.dashboard', $viewData);
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
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            // Generate slug
            $data['slug'] = Str::slug($data['title']);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('courses', 'public');
                $data['image'] = $imagePath;
            }

            $course = Course::create($data);

            return redirect()->route('instructor.courses.manage')
                ->with('success', 'Course created successfully!');

        } catch (\Exception $e) {
            return redirect()->route('instructor.courses.create')
                ->with('error', 'Failed to create course. Please try again.')
                ->withInput();
        }
    }

    public function manageCourses(Request $request)
    {
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

        try {
            $data = $request->validated();
            
            // Generate slug if title changed
            if ($data['title'] !== $course->title) {
                $data['slug'] = Str::slug($data['title']);
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($course->image && Storage::disk('public')->exists($course->image)) {
                    Storage::disk('public')->delete($course->image);
                }
                
                $imagePath = $request->file('image')->store('courses', 'public');
                $data['image'] = $imagePath;
            }

            $course->update($data);

            return redirect()->route('instructor.courses.manage')
                ->with('success', 'Course updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('instructor.courses.edit', $course)
                ->with('error', 'Failed to update course. Please try again.')
                ->withInput();
        }
    }

    public function deleteCourse(Course $course)
    {
        // Ensure the course belongs to the authenticated instructor
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course.');
        }

        try {
            // Delete course image if exists
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }

            $course->delete();

            return redirect()->route('instructor.courses.manage')
                ->with('success', 'Course deleted successfully!');

        } catch (\Exception $e) {
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
        // Ensure the material belongs to the authenticated instructor
        if ($material->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this material.');
        }

        if (!$material->file_path || !Storage::disk('public')->exists($material->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($material->file_path, $material->title . '.' . $material->file_type);
    }

    // Assignments
    public function createAssignment()
    {
        $viewData = [
            'meta_title' => 'Create Assignment | LMS',
            'meta_desc'  => 'Create a new assignment',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.create-assignments', $viewData);
    }

    public function manageAssignments()
    {
        $viewData = [
            'meta_title' => 'Manage Assignments | LMS',
            'meta_desc'  => 'Manage your assignments',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.assignments', $viewData);
    }

    // Submissions
    public function viewSubmissions()
    {
        $viewData = [
            'meta_title' => 'View Submissions | LMS',
            'meta_desc'  => 'View student submissions',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.submissions', $viewData);
    }

    public function gradeAssignments()
    {
        $viewData = [
            'meta_title' => 'Grade Assignments | LMS',
            'meta_desc'  => 'Grade student assignments',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.grade-assignments', $viewData);
    }

    // Students
    public function viewEnrolledStudents()
    {
        $viewData = [
            'meta_title' => 'Enrolled Students | LMS',
            'meta_desc'  => 'View enrolled students',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.students', $viewData);
    }

    // Messages
    public function messages()
    {
        $viewData = [
            'meta_title' => 'Messages | LMS',
            'meta_desc'  => 'View and send messages',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.messages', $viewData);
    }
}