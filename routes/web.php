<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use TCG\Voyager\Facades\Voyager;

// Controllers
use App\Http\Controllers\GradesController;
use App\Http\Controllers\Auth\PortalLoginController;
use App\Http\Controllers\Portal\{
    DashboardController,
    GradeController,
    AttendanceController,
    FeeController,
    AnnouncementController,
    CommunicationController
};

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Lightweight health check (useful for uptime monitors and local smoke tests)
Route::get('/health', function () {
    $status = [
        'app' => 'ok',
        'php' => PHP_VERSION,
        'laravel' => app()->version(),
    ];

    // Optional DB check
    try {
        DB::select('select 1');
        $status['db'] = 'ok';
    } catch (\Throwable $e) {
        $status['db'] = 'error';
        $status['db_error'] = $e->getMessage();
    }

    // Optional cache check
    try {
        Cache::put('health_check', 'ok', 5);
        $status['cache'] = Cache::get('health_check') === 'ok' ? 'ok' : 'error';
    } catch (\Throwable $e) {
        $status['cache'] = 'error';
        $status['cache_error'] = $e->getMessage();
    }

    return response()->json($status);
})->name('health');

// Admin Routes
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    
    // Gradebook Routes
    Route::group(['middleware' => 'auth'], function() {
        // Announcements
        Route::resource('announcements', 'App\Http\Controllers\Admin\AnnouncementController');
        
        // Grade management routes
        Route::get('/grades', [GradesController::class, 'index'])->name('grades.index');
        Route::get('/grades/course/{course}/assignments', [GradesController::class, 'courseAssignments'])->name('grades.assignments');
        Route::get('/grades/assignment/{assignment}/form', [GradesController::class, 'assignmentGradeForm'])->name('grades.assignment.form');
        Route::post('/grades/assignment/{assignment}', [GradesController::class, 'storeGrades'])->name('grades.store');
        
        // Reporting routes
        Route::get('/grades/course/{course}/report', [GradesController::class, 'courseReport'])->name('grades.course.report');
        Route::get('/grades/course/{course}/student/{student}', [GradesController::class, 'studentCourseReport'])->name('grades.student.course.report');
        Route::get('/grades/student/{student}/report', [GradesController::class, 'studentOverallReport'])->name('grades.student.overall.report');
    });
});

// Portal Routes
Route::prefix('portal')->name('portal.')->group(function () {
    // Authentication
    Route::get('login', [PortalLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [PortalLoginController::class, 'login']);
    Route::post('logout', [PortalLoginController::class, 'logout'])->name('logout');
    
    // Protected routes
    Route::middleware('auth:portal')->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('students', [DashboardController::class, 'students'])->name('students');
        
        // Grades
        Route::get('grades/report/{student?}', [GradeController::class, 'report'])->name('grades.report');
        Route::get('grades/assignment/{assignment}', [GradeController::class, 'assignmentGrades'])->name('grades.assignment');
        
        // Attendance
        Route::get('attendance/history/{student?}', [AttendanceController::class, 'history'])->name('attendance.history');
        Route::get('attendance/summary/{student?}', [AttendanceController::class, 'summary'])->name('attendance.summary');
        
        // Communication
        Route::get('communication', [CommunicationController::class, 'index'])->name('communication.index');
        Route::get('communication/conversation/{conversationId}', [CommunicationController::class, 'showConversation'])->name('communication.conversation');
        Route::post('communication/send/{conversationId?}', [CommunicationController::class, 'sendMessage'])->name('communication.send');
        Route::get('communication/teachers', [CommunicationController::class, 'listTeachers'])->name('communication.teachers');
        
        // Announcements
        Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
        
        // Fees
        Route::get('fees', [FeeController::class, 'index'])->name('fees.index');
        Route::get('fees/{fee}/pay', [FeeController::class, 'showPaymentForm'])->name('fees.pay');
        Route::post('fees/{fee}/process', [FeeController::class, 'processPayment'])->name('fees.process');
        
        // Profile routes
        Route::get('profile', function() {
            return view('portal.profile');
        })->name('profile');
        
        Route::get('change-password', function() {
            return view('portal.change-password');
        })->name('change-password');
        
        // Placeholder routes 
        Route::get('timetable', function() { 
            return view('portal.placeholder', ['title' => 'Timetable', 'message' => 'Timetable feature coming soon']);
        })->name('timetable');
        
        Route::get('events', function() { 
            return view('portal.placeholder', ['title' => 'School Events', 'message' => 'Events calendar feature coming soon']);
        })->name('events');
    });
});

// Role-based example routes for documentation
Route::prefix('role-demo')->name('role-demo.')->middleware(['auth'])->group(function() {
    // Accessible to all authenticated users
    Route::get('/', function() {
        return view('welcome', ['title' => 'Role Demo']);
    })->name('index');
    
    // Admin-only routes
    Route::middleware(['check.role:admin'])->group(function() {
        Route::get('/admin-only', function() {
            return view('welcome', ['title' => 'Admin Only Area']);
        })->name('admin');
    });
    
    // Teacher-only routes
    Route::middleware(['check.role:teacher'])->group(function() {
        Route::get('/teacher-only', function() {
            return view('welcome', ['title' => 'Teacher Only Area']);
        })->name('teacher');
    });
    
    // Student-only routes
    Route::middleware(['check.role:student'])->group(function() {
        Route::get('/student-only', function() {
            return view('welcome', ['title' => 'Student Only Area']);
        })->name('student');
    });
    
    // Parent-only routes
    Route::middleware(['check.role:parent'])->group(function() {
        Route::get('/parent-only', function() {
            return view('welcome', ['title' => 'Parent Only Area']);
        })->name('parent');
    });
    
    // Routes for teachers OR admins
    Route::middleware(['check.role:teacher,admin'])->group(function() {
        Route::get('/teacher-admin', function() {
            return view('welcome', ['title' => 'Teacher or Admin Area']);
        })->name('teacher-admin');
    });
    
    // Routes for students OR teachers
    Route::middleware(['check.role:student,teacher'])->group(function() {
        Route::get('/student-teacher', function() {
            return view('welcome', ['title' => 'Student or Teacher Area']);
        })->name('student-teacher');
    });
});
