<?php

namespace App\VoyagerWidgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Grade;
use App\Services\GradeCalculator;
use Illuminate\Support\Facades\View;

class GradeSummaryWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'title' => 'Grade Summary',
        'icon' => 'voyager-receipt',
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        // Get counts
        $courseCount = Course::count();
        $assignmentCount = Assignment::count();
        $gradeCount = Grade::count();
        
        // Get most recent graded assignments
        $recentGrades = Grade::with(['assignment', 'student'])
                          ->latest()
                          ->take(5)
                          ->get();

        // Get stats for courses
        $courseStats = [];
        $gradeCalculator = new GradeCalculator();
        
        $latestCourses = Course::latest()->take(3)->get();
        
        foreach ($latestCourses as $course) {
            $summary = $gradeCalculator->calculateCourseSummary($course);
            $courseStats[] = $summary;
        }

        return View::make('vendor.voyager.widgets.grade-summary', [
            'courseCount' => $courseCount,
            'assignmentCount' => $assignmentCount,
            'gradeCount' => $gradeCount,
            'recentGrades' => $recentGrades,
            'courseStats' => $courseStats,
            'icon' => $this->config['icon'],
            'title' => $this->config['title'],
        ]);
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return auth()->user()->can('browse', 'App\Models\Course') || 
               auth()->user()->can('browse', 'App\Models\Grade');
    }
}
