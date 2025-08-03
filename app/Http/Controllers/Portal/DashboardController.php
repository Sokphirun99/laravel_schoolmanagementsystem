<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
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
        $title = ucfirst($user->user_type) . ' Dashboard';
        $icon = 'voyager-dashboard';
        
        if ($user->user_type === 'parent') {
            $students = $user->parent ? $user->parent->students()->with('schoolClass')->get() : collect();
            $recentAnnouncements = Announcement::latest()->take(3)->get();
            return view('portal.parent.dashboard', compact('students', 'recentAnnouncements', 'title', 'icon'));
        }
        
        // Student dashboard
        $student = $user->student;
        
        // Handle case where student relationship doesn't exist
        if (!$student) {
            $student = (object) [
                'first_name' => $user->name,
                'last_name' => '',
                'photo' => null,
                'schoolClass' => null
            ];
            $recentGrades = collect();
            $upcomingAssignments = collect();
            $events = collect();
            $recentAnnouncements = collect();
        } else {
            // Get student data
            $recentGrades = collect(); // Initialize as empty collection for now
            if (method_exists($student, 'grades')) {
                $recentGrades = $student->grades()
                    ->with('assignment')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }
                
            // For now, get all upcoming assignments (we can filter by student's enrolled courses later)
            $upcomingAssignments = Assignment::where('due_date', '>', now())
                ->orderBy('due_date')
                ->take(5)
                ->get();
                
            $events = Event::where('date', '>=', now()->format('Y-m-d'))
                ->orderBy('date')
                ->take(5)
                ->get();
                
            $recentAnnouncements = Announcement::latest()->take(3)->get();
        }
        
        return view('portal.student.dashboard', compact('student', 'recentGrades', 'upcomingAssignments', 'events', 'recentAnnouncements', 'title', 'icon'));
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
