<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:portal');
    }

    public function dashboard()
    {
        $user = Auth::guard('portal')->user();
        $dashboardData = $this->prepareDashboardData($user);
        
        $viewPath = $user->user_type === 'parent' ? 'portal.parent.dashboard' : 'portal.student.dashboard';
        
        return view($viewPath, $dashboardData);
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

    private function prepareDashboardData($user): array
    {
        $baseData = [
            'title' => ucfirst($user->user_type) . ' Dashboard',
            'icon' => 'voyager-dashboard'
        ];

        if ($user->user_type === 'parent') {
            return array_merge($baseData, $this->getParentDashboardData($user));
        }

        return array_merge($baseData, $this->getStudentDashboardData($user));
    }

    private function getParentDashboardData($user): array
    {
        return [
            'students' => $user->parent?->students()->with('schoolClass')->get() ?? collect(),
            'recentAnnouncements' => Announcement::latest()->take(3)->get()
        ];
    }

    private function getStudentDashboardData($user): array
    {
        $student = $user->student ?? $this->createFallbackStudent($user);
        
        if (!$user->student) {
            return [
                'student' => $student,
                'recentGrades' => collect(),
                'upcomingAssignments' => collect(),
                'events' => collect(),
                'recentAnnouncements' => collect()
            ];
        }

        return [
            'student' => $student,
            'recentGrades' => $this->getRecentGrades($student),
            'upcomingAssignments' => $this->getUpcomingAssignments(),
            'events' => $this->getUpcomingEvents(),
            'recentAnnouncements' => Announcement::latest()->take(3)->get()
        ];
    }

    private function createFallbackStudent($user): object
    {
        return (object) [
            'first_name' => $user->name,
            'last_name' => '',
            'photo' => null,
            'schoolClass' => null
        ];
    }

    private function getRecentGrades($student)
    {
        if (!method_exists($student, 'grades')) {
            return collect();
        }

        return $student->grades()
            ->with('assignment')
            ->latest()
            ->take(5)
            ->get();
    }

    private function getUpcomingAssignments()
    {
        return Assignment::where('due_date', '>', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();
    }

    private function getUpcomingEvents()
    {
        return Event::whereDate('date', '>=', now())
            ->orderBy('date')
            ->take(5)
            ->get();
    }
}
