<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GradesController;
use TCG\Voyager\Facades\Voyager;

Route::get('/', function () {
    return view('welcome');
});


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

use App\Http\Controllers\Auth\PortalLoginController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\GradeController;
use App\Http\Controllers\Portal\CommunicationController;
use App\Http\Controllers\Portal\AttendanceController;
use App\Http\Controllers\Portal\AnnouncementController;
use App\Http\Controllers\Portal\FeeController;

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
        
        // Profile routes (placeholders for future implementation)
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


// This is already defined above, removing duplicate route definition
