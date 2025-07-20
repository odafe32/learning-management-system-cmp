<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\StudentController;
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
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'Dashboard'])->name('dashboard');
    // Add more student-specific routes here
    // Route::get('/courses', [StudentController::class, 'courses'])->name('courses');
    // Route::get('/assignments', [StudentController::class, 'assignments'])->name('assignments');
});

// Instructor/Lecturer Routes
Route::middleware(['auth', 'role:lecturer'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'Dashboard'])->name('dashboard');
    // Add more instructor-specific routes here
    // Route::get('/courses', [InstructorController::class, 'courses'])->name('courses');
    // Route::get('/students', [InstructorController::class, 'students'])->name('students');
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