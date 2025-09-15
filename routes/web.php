<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

// Default dashboard route - redirects to role-specific dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user) {
        return redirect()->route($user->getDashboardRoute());
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Common authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // AJAX Logout route
    Route::post('/logout-ajax', [AuthenticatedSessionController::class, 'logoutAjax'])->name('logout.ajax');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'Dashboard'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::put('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [StudentController::class, 'updatePassword'])->name('profile.password');
    
    // Courses Management - Enhanced with enrollment functionality
    Route::prefix('courses')->name('courses.')->group(function () {
        // Main courses listing (enrolled courses)
        Route::get('/', [StudentController::class, 'ShowsCourses'])->name('index');
        
        // Course enrollment
        Route::get('/enroll-courses', [StudentController::class, 'EnrollCourse'])->name('enroll-courses');
        Route::post('/enroll/{course}', [StudentController::class, 'enrollInCourse'])->name('enroll');
        Route::delete('/unenroll/{course}', [StudentController::class, 'unenrollFromCourse'])->name('unenroll');
        
        // Course detail view (with materials, assignments, announcements)
        Route::get('/{course:slug}', [StudentController::class, 'viewCourse'])->name('show');
        
        // AJAX routes for course operations
        Route::post('/ajax/enroll/{course}', [StudentController::class, 'enrollInCourse'])->name('ajax.enroll');
        Route::get('/ajax/details/{course}', [StudentController::class, 'getCourseDetails'])->name('ajax.details');
    });    

    // Assignments - Enhanced with course filtering
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/', [StudentController::class, 'ShowAssignments'])->name('index');
        Route::get('/submit-assignments', [StudentController::class, 'SubmitAssignments'])->name('submit-assignments');
        Route::get('/{assignment}', [StudentController::class, 'viewAssignment'])->name('show');
        Route::post('/{assignment}/submit', [StudentController::class, 'submitAssignment'])->name('submit');
        Route::get('/course/{course}', [StudentController::class, 'ShowAssignments'])->name('by-course');
    });

    // Material Routes
    Route::prefix('materials')->name('materials.')->group(function () {
        Route::get('/', [StudentController::class, 'viewMaterials'])->name('index');
        Route::get('/search', [StudentController::class, 'searchMaterials'])->name('search');
        Route::get('/{material}', [StudentController::class, 'showMaterial'])->name('show');
        Route::get('/{material}/download', [StudentController::class, 'downloadMaterial'])->name('download');
        Route::get('/{material}/stream', [StudentController::class, 'streamMaterial'])->name('stream');
    });

    // Submission Management - Enhanced with filtering
    Route::prefix('submissions')->name('submissions.')->group(function () {
        Route::get('/', [StudentController::class, 'viewSubmissions'])->name('index');
        Route::get('/{submission}', [StudentController::class, 'viewSubmission'])->name('show');
        Route::get('/assignment/{assignment}', [StudentController::class, 'viewSubmissionsByAssignment'])->name('by-assignment');
        Route::get('/course/{course}', [StudentController::class, 'viewSubmissions'])->name('by-course');
    });

    // Grades Management - Enhanced with filtering
    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/', [StudentController::class, 'viewGrades'])->name('index');
        Route::get('/course/{course}', [StudentController::class, 'viewGrades'])->name('by-course');
        Route::get('/assignment/{assignment}', [StudentController::class, 'viewGradesByAssignment'])->name('by-assignment');
        Route::get('/export', [StudentController::class, 'exportGrades'])->name('export');
    });

    // Feedbacks Management - Enhanced with filtering
    Route::prefix('feedbacks')->name('feedbacks.')->group(function () {
        Route::get('/', [StudentController::class, 'viewFeedbacks'])->name('index');
        Route::get('/{submission}', [StudentController::class, 'viewFeedback'])->name('show');
        Route::get('/course/{course}', [StudentController::class, 'viewFeedbacks'])->name('by-course');
    });

    // Messages/Communication
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [StudentController::class, 'messages'])->name('index');
        Route::post('/send', [StudentController::class, 'sendMessage'])->name('send');
        Route::get('/conversation/{user}', [StudentController::class, 'getConversation'])->name('conversation');
        Route::post('/{message}/read', [StudentController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [StudentController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{message}', [StudentController::class, 'deleteMessage'])->name('delete');
    });

    // Announcements (if you plan to implement this)
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [StudentController::class, 'viewAnnouncements'])->name('index');
        Route::get('/{announcement}', [StudentController::class, 'viewAnnouncement'])->name('show');
        Route::get('/course/{course}', [StudentController::class, 'viewAnnouncements'])->name('by-course');
    });

    // Calendar/Schedule (if you plan to implement this)
    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/', [StudentController::class, 'viewSchedule'])->name('index');
        Route::get('/calendar', [StudentController::class, 'viewCalendar'])->name('calendar');
    });
});

// Instructor/Lecturer Routes
Route::middleware(['auth', 'role:instructor,lecturer'])->prefix('instructor')->name('instructor.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [InstructorController::class, 'profile'])->name('profile');
    Route::put('/profile', [InstructorController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [InstructorController::class, 'updatePassword'])->name('profile.password');
    
    // Notifications - New routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [InstructorController::class, 'getNotifications'])->name('index');
        Route::post('/mark-read', [InstructorController::class, 'markNotificationRead'])->name('mark-read');
        Route::post('/clear-all', [InstructorController::class, 'clearAllNotifications'])->name('clear-all');
    });
    
    // Add a direct route for AJAX calls
    Route::get('/notifications', [InstructorController::class, 'getNotifications'])->name('notifications');
    
    // Courses - Complete CRUD
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [InstructorController::class, 'manageCourses'])->name('index');
        Route::get('/manage', [InstructorController::class, 'manageCourses'])->name('manage');
        Route::get('/create', [InstructorController::class, 'createCourse'])->name('create');
        Route::post('/create', [InstructorController::class, 'storeCourse'])->name('store');
        Route::get('/{course}', [InstructorController::class, 'viewCourse'])->name('show');
        Route::get('/{course}/edit', [InstructorController::class, 'editCourse'])->name('edit');
        Route::put('/{course}', [InstructorController::class, 'updateCourse'])->name('update');
        Route::delete('/{course}', [InstructorController::class, 'deleteCourse'])->name('delete');
    });
    
    // Materials Management
    Route::prefix('materials')->name('materials.')->group(function () {
        Route::get('/', [InstructorController::class, 'viewMaterials'])->name('index');
        Route::get('/upload', [InstructorController::class, 'uploadMaterial'])->name('upload');
        Route::post('/', [InstructorController::class, 'storeMaterial'])->name('store');
        Route::get('/{material}', [InstructorController::class, 'viewMaterial'])->name('show');
        Route::get('/{material}/edit', [InstructorController::class, 'editMaterial'])->name('edit');
        Route::put('/{material}', [InstructorController::class, 'updateMaterial'])->name('update');
        Route::delete('/{material}', [InstructorController::class, 'deleteMaterial'])->name('destroy');
        
        // File serving routes
        Route::get('/{material}/serve', [InstructorController::class, 'serveMaterial'])->name('serve');
        Route::get('/{material}/stream', [InstructorController::class, 'streamMaterial'])->name('stream');
        Route::get('/{material}/download', [InstructorController::class, 'downloadMaterial'])->name('download');
        
        // Debug route (only in development)
        Route::get('/{material}/debug', [InstructorController::class, 'debugMaterial'])->name('debug');
    });

    // Assignments - Complete CRUD
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/', [InstructorController::class, 'manageAssignments'])->name('index');
        Route::get('/manage', [InstructorController::class, 'manageAssignments'])->name('manage');
        Route::get('/create', [InstructorController::class, 'createAssignment'])->name('create');
        Route::post('/create', [InstructorController::class, 'storeAssignment'])->name('store');
        Route::get('/{assignment}', [InstructorController::class, 'viewAssignment'])->name('show');
        Route::get('/{assignment}/view', [InstructorController::class, 'viewAssignment'])->name('view');
        Route::get('/{assignment}/edit', [InstructorController::class, 'editAssignment'])->name('edit');
        Route::put('/{assignment}', [InstructorController::class, 'updateAssignment'])->name('update');
        Route::delete('/{assignment}', [InstructorController::class, 'deleteAssignment'])->name('delete');
    });

    // Students Management - Enhanced
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [InstructorController::class, 'viewEnrolledStudents'])->name('index');
        Route::get('/enrolled', [InstructorController::class, 'viewEnrolledStudents'])->name('enrolled');
        Route::post('/update-status', [InstructorController::class, 'updateStudentStatus'])->name('update-status');
        Route::post('/remove', [InstructorController::class, 'removeStudentFromCourse'])->name('remove');
        Route::get('/export', [InstructorController::class, 'exportEnrolledStudents'])->name('export');
    });
    
    // Submissions Management - Fixed routes
    Route::prefix('submissions')->name('submissions.')->group(function () {
        // View all submissions
        Route::get('/', [InstructorController::class, 'viewSubmissions'])->name('index');
        Route::get('/view', [InstructorController::class, 'viewSubmissions'])->name('view');
        
        // Grading submissions
        Route::get('/grade', [InstructorController::class, 'gradeAssignments'])->name('grade');
        
        // Individual submission actions
        Route::get('/{submission}', [InstructorController::class, 'viewSubmissionDetail'])->name('show');
        Route::get('/{submission}/detail', [InstructorController::class, 'viewSubmissionDetail'])->name('detail');
        Route::post('/{submission}/grade', [InstructorController::class, 'gradeSubmission'])->name('store-grade');
        Route::put('/{submission}/grade', [InstructorController::class, 'gradeSubmission'])->name('update-grade');
    });
    
    // Messages/Communication - Complete messaging system
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [InstructorController::class, 'messages'])->name('index');
        Route::post('/send', [InstructorController::class, 'sendMessage'])->name('send');
        Route::post('/{message}/read', [InstructorController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [InstructorController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{message}', [InstructorController::class, 'deleteMessage'])->name('delete');
        Route::get('/conversation/{user}', [InstructorController::class, 'getConversation'])->name('conversation');
        Route::get('/search-users', [InstructorController::class, 'searchUsers'])->name('search-users');
    });
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'Dashboard'])->name('dashboard');
    
    // User Management - Complete CRUD
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'Showusers'])->name('index');
        Route::get('/create', [AdminController::class, 'Createusers'])->name('create');
        Route::post('/store', [AdminController::class, 'storeUser'])->name('store');
        Route::delete('/delete', [AdminController::class, 'deleteUser'])->name('delete');
        Route::get('/{user}/edit', [AdminController::class, 'editUser'])->name('edit');
        Route::put('/{user}', [AdminController::class, 'updateUser'])->name('update');
        Route::get('/export', [AdminController::class, 'exportUsers'])->name('export');
        Route::post('/bulk-action', [AdminController::class, 'bulkAction'])->name('bulk-action');
    });
    
    // Course Management
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [AdminController::class, 'courses'])->name('index');
        Route::get('/{course}', [AdminController::class, 'viewCourse'])->name('show');
        Route::delete('/{course}', [AdminController::class, 'deleteCourse'])->name('delete');
        Route::get('/export', [AdminController::class, 'exportCourses'])->name('export');
    });

    // Assignment Management Routes
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/', [AdminController::class, 'assignments'])->name('index');
        Route::delete('/{assignment}', [AdminController::class, 'deleteAssignment'])->name('delete');
        Route::get('/export', [AdminController::class, 'exportAssignments'])->name('export');
    });
        
    // Materials Management - Enhanced
    Route::prefix('materials')->name('materials.')->group(function () {
        Route::get('/', [AdminController::class, 'materials'])->name('index');
        Route::get('/{material}', [AdminController::class, 'viewMaterial'])->name('show');
        Route::delete('/{material}', [AdminController::class, 'deleteMaterial'])->name('delete');
        Route::get('/export', [AdminController::class, 'exportMaterials'])->name('export');
    });

    // Messages Management - Enhanced
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [AdminController::class, 'messages'])->name('index');
        Route::post('/send', [AdminController::class, 'sendMessage'])->name('send');
        Route::post('/mark-as-read', [AdminController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-read', [AdminController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/stats', [AdminController::class, 'getMessageStats'])->name('stats');
        Route::get('/{message}', [AdminController::class, 'viewMessage'])->name('show');
        Route::delete('/{message}', [AdminController::class, 'deleteMessage'])->name('delete');
    });

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminController::class, 'profile'])->name('index');
        Route::put('/update', [AdminController::class, 'updateProfile'])->name('update');
        Route::put('/password', [AdminController::class, 'updatePassword'])->name('password');
    });
    
    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminController::class, 'settings'])->name('index');
        Route::put('/update', [AdminController::class, 'updateSettings'])->name('update');
    });
    
    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminController::class, 'getNotifications'])->name('index');
        Route::get('/count', [AdminController::class, 'getNotificationCount'])->name('count');
        Route::post('/mark-read', [AdminController::class, 'markNotificationAsRead'])->name('mark-read');
        Route::post('/clear-all', [AdminController::class, 'clearAllNotifications'])->name('clear-all');
        Route::delete('/{notification}', [AdminController::class, 'deleteNotification'])->name('delete');
    });

    // Reports and Analytics
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminController::class, 'reports'])->name('index');
        Route::get('/users', [AdminController::class, 'userReports'])->name('users');
        Route::get('/courses', [AdminController::class, 'courseReports'])->name('courses');
        Route::get('/assignments', [AdminController::class, 'assignmentReports'])->name('assignments');
        Route::get('/export/{type}', [AdminController::class, 'exportReport'])->name('export');
    });

    // Course Enrollment Management (Admin can manage all enrollments)
    Route::prefix('enrollments')->name('enrollments.')->group(function () {
        Route::get('/', [AdminController::class, 'viewEnrollments'])->name('index');
        Route::post('/bulk-enroll', [AdminController::class, 'bulkEnrollStudents'])->name('bulk-enroll');
        Route::post('/bulk-unenroll', [AdminController::class, 'bulkUnenrollStudents'])->name('bulk-unenroll');
        Route::get('/export', [AdminController::class, 'exportEnrollments'])->name('export');
    });
});

require __DIR__.'/auth.php';