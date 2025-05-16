<?php

use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\SchoolDashboardController;
use App\Http\Controllers\SchoolSetupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// School setup routes
Route::get('/setup', [SchoolSetupController::class, 'showSetupForm'])->name('school.setup');
Route::post('/setup', [SchoolSetupController::class, 'initializeSchool'])->name('school.initialize');
Route::get('/setup/migrate', [SchoolSetupController::class, 'runMigrations'])->name('school.migrate');

// Custom setup routes
Route::get('/setup/add-role-management-menu', [App\Http\Controllers\CustomMenuController::class, 'addRoleManagementMenu'])->name('setup.add-role-menu');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [SchoolDashboardController::class, 'index'])->name('voyager.dashboard');

    // Add these BEFORE Voyager::routes()
    Route::get('students', [StudentController::class, 'index'])->name('voyager.students.index');
    Route::get('students/create', [StudentController::class, 'create'])->name('voyager.students.create'); // Add this
    Route::get('students/{id}', [StudentController::class, 'show'])->name('voyager.students.show');
    Route::post('students', [StudentController::class, 'store'])->name('voyager.students.store');

    Route::get('teachers/{id}', [TeacherController::class, 'show'])->name('voyager.teachers.show');
    Route::post('teachers', [TeacherController::class, 'store'])->name('voyager.teachers.store');

    Route::get('parents/{id}', [ParentsController::class, 'show'])->name('voyager.parents.show');
    Route::post('parents', [ParentsController::class, 'store'])->name('voyager.parents.store');

    // Role Management routes
    Route::get('manage-roles', [App\Http\Controllers\Admin\RoleManagementController::class, 'index'])->name('admin.manage-roles');
    Route::get('get-user-roles', [App\Http\Controllers\Admin\RoleManagementController::class, 'getUserRoles'])->name('admin.get-user-roles');

    // Role Demo routes
    Route::get('role-demo', [App\Http\Controllers\RoleDemoController::class, 'index'])->name('admin.role-demo');
    Route::get('role-demo/details', [App\Http\Controllers\RoleDemoController::class, 'roleDetails'])->name('admin.role-demo.details');

    // Protected role-specific routes (for testing middleware)
    Route::middleware(['check.role:admin'])->group(function() {
        Route::get('admin-only', function() {
            return view('role_demo.admin_only');
        })->name('admin.role-demo.admin-only');
    });

    Route::middleware(['check.role:teacher'])->group(function() {
        Route::get('teacher-only', function() {
            return view('role_demo.teacher_only');
        })->name('admin.role-demo.teacher-only');
    });
    Route::post('update-user-roles', [App\Http\Controllers\Admin\RoleManagementController::class, 'updateUserRoles'])->name('admin.update-user-roles');

    // Now the Voyager routes (should come AFTER your custom routes)
    Voyager::routes();
});
