<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\Grade;
use App\Services\GradeCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradesController extends Controller
{
    protected $gradeCalculator;
    
    public function __construct(GradeCalculator $gradeCalculator)
    {
        $this->gradeCalculator = $gradeCalculator;
        $this->middleware('auth');
    }
    
    /**
     * Display a list of courses for grade management
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('teacher')) {
            // Teachers see courses they teach
            $courses = Course::where('teacher_id', $user->id)->get();
            return view('grades.index', compact('courses'));
        } elseif ($user->hasRole('student')) {
            // Students see courses they're enrolled in
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return redirect()->back()->with('error', 'Student profile not found');
            }
            $courses = $student->courses;
            return view('grades.student-courses', compact('courses'));
        } elseif ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            // Admins see all courses
            $courses = Course::all();
            return view('grades.index', compact('courses'));
        } else {
            return redirect()->back()->with('error', 'You don\'t have permission to view grades');
        }
    }
    
    /**
     * Display assignments for a specific course
     */
    public function courseAssignments(Course $course)
    {
        $assignments = $course->assignments;
        
        return view('grades.assignments', compact('course', 'assignments'));
    }
    
    /**
     * Show grade entry form for an assignment
     */
    public function assignmentGradeForm(Assignment $assignment)
    {
        $course = $assignment->course;
        $students = $course->students;
        $existingGrades = Grade::where('assignment_id', $assignment->id)->get()->keyBy('student_id');
        
        return view('grades.enter', compact('assignment', 'course', 'students', 'existingGrades'));
    }
    
    /**
     * Save grades for an assignment
     */
    public function storeGrades(Request $request, Assignment $assignment)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.points_earned' => 'required|numeric|min:0|max:' . $assignment->max_points,
            'grades.*.feedback' => 'nullable|string',
        ]);
        
        foreach ($request->grades as $gradeData) {
            Grade::updateOrCreate(
                [
                    'assignment_id' => $assignment->id,
                    'student_id' => $gradeData['student_id'],
                ],
                [
                    'points_earned' => $gradeData['points_earned'],
                    'feedback' => $gradeData['feedback'] ?? null,
                ]
            );
        }
        
        return redirect()->route('grades.assignments', $assignment->course)
                         ->with('success', 'Grades have been saved successfully');
    }
    
    /**
     * View course report with summary statistics
     */
    public function courseReport(Course $course)
    {
        $summary = $this->gradeCalculator->calculateCourseSummary($course);
        
        return view('grades.course-report', compact('course', 'summary'));
    }
    
    /**
     * View student report card for a specific course
     */
    public function studentCourseReport(Course $course, Student $student)
    {
        $gradeReport = $this->gradeCalculator->calculateCourseGrade($student, $course);
        
        return view('grades.student-report', compact('gradeReport'));
    }
    
    /**
     * View student overall grades across all courses
     */
    public function studentOverallReport(Student $student)
    {
        $courses = $student->courses;
        $courseGrades = [];
        
        foreach ($courses as $course) {
            $courseGrades[$course->id] = $this->gradeCalculator->calculateCourseGrade($student, $course);
        }
        
        return view('grades.student-overall', compact('student', 'courses', 'courseGrades'));
    }
}
