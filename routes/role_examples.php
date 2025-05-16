<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolDashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ParentsController;

/*
|--------------------------------------------------------------------------
| Role-Based Access Control Example Routes
|--------------------------------------------------------------------------
|
| These routes demonstrate how to use the new CheckUserRole middleware
| for flexible role-based access control.
|
*/

// Group for routes that require authentication
Route::middleware(['auth'])->group(function () {

    // Dashboard accessible to anyone who is logged in
    Route::get('/dashboard', [SchoolDashboardController::class, 'dashboard'])->name('dashboard');

    // Routes accessible to administrators only
    Route::middleware(['check.role:admin'])->prefix('admin')->group(function () {
        Route::get('/system-settings', function () {
            return view('admin.system_settings');
        })->name('admin.system-settings');

        Route::get('/manage-roles', function () {
            return view('admin.manage_roles');
        })->name('admin.manage-roles');
    });

    // Routes accessible to teachers only
    Route::middleware(['check.role:teacher'])->prefix('teacher')->group(function () {
        Route::get('/my-classes', function () {
            return view('teacher.my_classes');
        })->name('teacher.my-classes');

        Route::get('/attendance', function () {
            return view('teacher.attendance');
        })->name('teacher.attendance');
    });

    // Routes accessible to students only
    Route::middleware(['check.role:student'])->prefix('student')->group(function () {
        Route::get('/my-courses', function () {
            return view('student.my_courses');
        })->name('student.my-courses');

        Route::get('/grades', function () {
            return view('student.grades');
        })->name('student.grades');
    });

    // Routes accessible to parents only
    Route::middleware(['check.role:parent'])->prefix('parent')->group(function () {
        Route::get('/my-children', function () {
            return view('parent.my_children');
        })->name('parent.my-children');

        Route::get('/progress-reports', function () {
            return view('parent.progress_reports');
        })->name('parent.progress-reports');
    });

    // Routes accessible to multiple roles (students and teachers)
    Route::middleware(['check.role:student,teacher'])->group(function () {
        Route::get('/library', function () {
            return view('shared.library');
        })->name('shared.library');
    });

    // Routes accessible to all authorized roles except students
    Route::middleware(['check.role:admin,teacher,parent'])->group(function () {
        Route::get('/reports', [App\Http\Controllers\Shared\ReportsController::class, 'index'])->name('shared.reports');
    });
});
