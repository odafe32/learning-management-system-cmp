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
    
    // Student-specific routes (uncomment and implement as needed)
    // Route::get('/courses', [StudentController::class, 'courses'])->name('courses');
    // Route::get('/assignments', [StudentController::class, 'assignments'])->name('assignments');
    // Route::get('/assignments/{assignment}', [StudentController::class, 'viewAssignment'])->name('assignments.view');
    // Route::post('/assignments/{assignment}/submit', [StudentController::class, 'submitAssignment'])->name('assignments.submit');
    // Route::get('/materials', [StudentController::class, 'materials'])->name('materials');
    // Route::get('/grades', [StudentController::class, 'grades'])->name('grades');
});

// Instructor/Lecturer Routes
Route::middleware(['auth', 'role:instructor,lecturer'])->prefix('instructor')->name('instructor.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [InstructorController::class, 'profile'])->name('profile');
    Route::put('/profile', [InstructorController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [InstructorController::class, 'updatePassword'])->name('profile.password');
    
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
    
    // Students Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [InstructorController::class, 'viewEnrolledStudents'])->name('index');
        Route::get('/enrolled', [InstructorController::class, 'viewEnrolledStudents'])->name('enrolled');
    });
    
    // Messages/Communication
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [InstructorController::class, 'messages'])->name('index');
    });
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'Dashboard'])->name('dashboard');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
    });
    
    // Course Management
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [AdminController::class, 'courses'])->name('index');
    });
    
    // System Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminController::class, 'reports'])->name('index');
    });
    
    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminController::class, 'settings'])->name('index');
    });
});

require __DIR__.'/auth.php';