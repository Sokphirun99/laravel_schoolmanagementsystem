<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\Notice;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the appropriate dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isTeacher()) {
            return $this->teacherDashboard();
        } elseif ($user->isStudent()) {
            return $this->studentDashboard();
        } elseif ($user->isParent()) {
            return $this->parentDashboard();
        } else {
            // Default dashboard
            return view('dashboard.default');
        }
    }
    
    /**
     * Show admin dashboard
     */
    protected function adminDashboard()
    {
        $data = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_parents' => ParentModel::count(),
            'total_classes' => SchoolClass::count(),
            'total_subjects' => Subject::count(),
            'recent_notices' => Notice::orderBy('created_at', 'desc')->take(5)->get(),
            'recent_users' => User::orderBy('created_at', 'desc')->take(5)->get(),
        ];
        
        return view('dashboard.admin', $data);
    }
    
    /**
     * Show teacher dashboard
     */
    protected function teacherDashboard()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'Teacher profile not found.');
        }
        
        $data = [
            'teacher' => $teacher,
            'subjects' => $teacher->subjects,
            'recent_notices' => Notice::forAudience('teacher')
                ->orWhere('created_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];
        
        return view('dashboard.teacher', $data);
    }
    
    /**
     * Show student dashboard
     */
    protected function studentDashboard()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student profile not found.');
        }
        
        $data = [
            'student' => $student,
            'class' => $student->schoolClass,
            'section' => $student->section,
            'subjects' => Subject::where('class_id', $student->class_id)->get(),
            'recent_notices' => Notice::forAudience('student')
                ->byClass($student->class_id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];
        
        return view('dashboard.student', $data);
    }
    
    /**
     * Show parent dashboard
     */
    protected function parentDashboard()
    {
        $user = Auth::user();
        $parent = $user->parent;
        
        if (!$parent) {
            return redirect()->route('dashboard')->with('error', 'Parent profile not found.');
        }
        
        $data = [
            'parent' => $parent,
            'children' => $parent->students,
            'recent_notices' => Notice::forAudience('parent')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];
        
        return view('dashboard.parent', $data);
    }
}
