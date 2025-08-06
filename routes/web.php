<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// Default dashboard route - redirects to role-specific dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user) {
        return redirect()->route($user->getDashboardRoute());
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    // Add more student-specific routes here
    // Route::get('/courses', [StudentController::class, 'courses'])->name('courses');
    // Route::get('/assignments', [StudentController::class, 'assignments'])->name('assignments');
});

// Instructor/Lecturer Routes
Route::middleware(['auth'])->prefix('instructor')->name('instructor.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [InstructorController::class, 'profile'])->name('profile');
    Route::put('/profile', [InstructorController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [InstructorController::class, 'updatePassword'])->name('profile.password');
    
    // Courses - Complete CRUD
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/create', [InstructorController::class, 'createCourse'])->name('create');
        Route::post('/create', [InstructorController::class, 'storeCourse'])->name('store');
        Route::get('/manage', [InstructorController::class, 'manageCourses'])->name('manage');
        Route::get('/{course}/edit', [InstructorController::class, 'editCourse'])->name('edit');
        Route::put('/{course}', [InstructorController::class, 'updateCourse'])->name('update');
        Route::delete('/{course}', [InstructorController::class, 'deleteCourse'])->name('delete');
    });
    
    // Materials
    Route::prefix('materials')->name('materials.')->group(function () {
        Route::get('/upload', [InstructorController::class, 'uploadMaterial'])->name('upload');
        Route::get('/', [InstructorController::class, 'viewMaterials'])->name('index');
    });
    
    // Assignments
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/create', [InstructorController::class, 'createAssignment'])->name('create');
        Route::get('/manage', [InstructorController::class, 'manageAssignments'])->name('manage');
    });
    
    // Submissions
    Route::prefix('submissions')->name('submissions.')->group(function () {
        Route::get('/', [InstructorController::class, 'viewSubmissions'])->name('index');
        Route::get('/grade', [InstructorController::class, 'gradeAssignments'])->name('grade');
    });
    
    // Students
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [InstructorController::class, 'viewEnrolledStudents'])->name('index');
    });
    
    // Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [InstructorController::class, 'messages'])->name('index');
    });
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'Dashboard'])->name('dashboard');
    // Add more admin-specific routes here
    // Route::get('/users', [AdminController::class, 'users'])->name('users');
    // Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
    // Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});

require __DIR__.'/auth.php';