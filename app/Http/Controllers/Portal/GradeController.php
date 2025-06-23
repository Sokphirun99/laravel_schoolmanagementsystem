<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assignment;
use App\Services\GradeCalculator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:portal');
    }
    
    public function report($studentId = null)
    {
        $user = Auth::guard('portal')->user();
        
        // For parents, get specific student
        if ($user->user_type === 'parent' && $studentId) {
            $student = Student::findOrFail($studentId);
            // Verify parent has access to this student
            if (!$user->parent->students->contains($student)) {
                abort(403, 'Unauthorized access');
            }
        } else {
            $student = $user->student;
        }
        
        $courses = $student->courses;
        $reportData = [];
        $gradeCalculator = new GradeCalculator();
        
        foreach ($courses as $course) {
            $reportData[$course->id] = $gradeCalculator->calculateCourseGrade($student, $course);
        }
        
        return view('portal.grades.report', compact('student', 'courses', 'reportData'));
    }
    
    public function assignmentGrades($assignmentId)
    {
        $assignment = Assignment::with(['grades.student', 'course'])->findOrFail($assignmentId);
        
        // Verify user has access
        $user = Auth::guard('portal')->user();
        $studentIds = $user->user_type === 'parent' 
            ? $user->parent->students->pluck('id') 
            : [$user->student->id];
            
        if (!$assignment->grades->whereIn('student_id', $studentIds)->count()) {
            abort(403, 'Unauthorized access');
        }
        
        return view('portal.grades.assignment', compact('assignment'));
    }
}
