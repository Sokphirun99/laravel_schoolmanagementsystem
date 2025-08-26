<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\TimetableController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Authentication Routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Authentication Routes
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-all', [AuthController::class, 'logoutAll']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    
    // Legacy user route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Student Routes
    Route::apiResource('students', StudentController::class);
    Route::get('/students/{student}/grades', [StudentController::class, 'grades']);
    Route::get('/students/{student}/attendance', [StudentController::class, 'attendance']);
    
    // Teacher Routes
    Route::apiResource('teachers', TeacherController::class);
    Route::get('/teachers/{teacher}/courses', [TeacherController::class, 'courses']);
    Route::get('/teachers/{teacher}/students', [TeacherController::class, 'students']);
    
    // Course Routes
    Route::apiResource('courses', CourseController::class);
    Route::get('/courses/{course}/assignments', [CourseController::class, 'assignments']);
    Route::get('/courses/{course}/students', [CourseController::class, 'students']);
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enrollStudent']);
    
    // Assignment Routes
    Route::apiResource('assignments', AssignmentController::class);
    Route::get('/assignments/{assignment}/grades', [AssignmentController::class, 'grades']);
    Route::post('/assignments/{assignment}/grade', [AssignmentController::class, 'submitGrade']);
    
    // Attendance Routes
    Route::apiResource('attendance', AttendanceController::class);
    Route::get('/attendance/student/{studentId}/summary', [AttendanceController::class, 'studentSummary']);
    Route::post('/attendance/bulk', [AttendanceController::class, 'bulkStore']);
    
    // Announcement Routes
    Route::apiResource('announcements', AnnouncementController::class);
    Route::get('/announcements-active', [AnnouncementController::class, 'getActive']);
    
    // Fee Routes
    Route::apiResource('fees', FeeController::class);
    Route::post('/fees/{fee}/mark-paid', [FeeController::class, 'markPaid']);
    Route::get('/fees/student/{studentId}/summary', [FeeController::class, 'studentSummary']);
    
    // Timetable Routes
    Route::apiResource('timetables', TimetableController::class);
    Route::get('/timetables/teacher/{teacherId}', [TimetableController::class, 'teacherTimetable']);
    Route::get('/timetables/class', [TimetableController::class, 'classTimetable']);
    
    // Additional utility routes
    Route::get('/dashboard/stats', function () {
        $stats = [
            'total_students' => \App\Models\Student::count(),
            'total_teachers' => \App\Models\Teacher::count(),
            'total_courses' => \App\Models\Course::count(),
            'total_assignments' => \App\Models\Assignment::count(),
            'pending_fees' => \App\Models\Fee::where('status', 'pending')->sum('amount'),
            'recent_announcements' => \App\Models\Announcement::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ];
        
        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    });
});
