<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:portal');
    }

    public function dashboard()
    {
        $user = Auth::guard('portal')->user();
        
        if ($user->user_type === 'parent') {
            $students = $user->parent->students()->with('schoolClass')->get();
            $recentAnnouncements = Announcement::latest()->take(3)->get();
            return view('portal.parent.dashboard', compact('students', 'recentAnnouncements'));
        }
        
        // Student dashboard
        $student = $user->student;
        $recentGrades = $student->grades()
            ->with('assignment')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $upcomingAssignments = Assignment::where('class_id', $student->class_id)
            ->where('due_date', '>', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();
            
        $events = Event::where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->take(5)
            ->get();
            
        $recentAnnouncements = Announcement::latest()->take(3)->get();
        return view('portal.student.dashboard', compact('student', 'recentGrades', 'upcomingAssignments', 'events', 'recentAnnouncements'));
    }
    
    public function students()
    {
        $user = Auth::guard('portal')->user();
        
        if ($user->user_type !== 'parent') {
            return redirect()->route('portal.dashboard');
        }
        
        $students = $user->parent->students()->with(['schoolClass', 'section'])->get();
        return view('portal.parent.students', compact('students'));
    }
}
