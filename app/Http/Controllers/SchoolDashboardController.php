<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Parents;
use App\Models\ClassRoom;
use App\Models\Section;
use App\Models\AcademicYear;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Contracts\View\View; // Import View contract
use Illuminate\Database\Eloquent\Collection; // Import Collection

class SchoolDashboardController extends Controller
{
    /**
     * Display the school dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     *
     * @property int $studentCount
     * @property int $teacherCount
     * @property int $parentCount  // Note: parentCount is passed but not used in the current blade view
     * @property int $classCount
     * @property int $sectionCount
     * @property \App\Models\AcademicYear|null $currentAcademicYear
     * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Student> $recentStudents
     * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Teacher> $recentTeachers
     * @property \Illuminate\Support\Collection $widgets Widgets collection from Voyager
     */
    public function index(): View
    {
        // Get counts for dashboard
        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $parentCount = Parents::count(); // This is passed but not used in the blade view provided
        $classCount = ClassRoom::count();
        $sectionCount = Section::count();

        // Get current academic year
        $currentAcademicYear = AcademicYear::where('is_current', true)->first();

        // Recent activity (latest 5 students and teachers)
        $recentStudents = Student::with(['section.class']) // Eager load nested relationship
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentTeachers = Teacher::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch widgets registered in Voyager
        $widgets = Voyager::dimmers();

        return Voyager::view('voyager::dashboard', compact(
            'studentCount',
            'teacherCount',
            // 'parentCount', // Consider removing if not used in the view
            'classCount',
            'sectionCount',
            'currentAcademicYear',
            'recentStudents',
            'recentTeachers',
            'widgets' // Add widgets to compact
        ));
    }
}
