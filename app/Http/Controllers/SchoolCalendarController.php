<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Exam;
use TCG\Voyager\Facades\Voyager;
use Carbon\Carbon;

class SchoolCalendarController extends Controller
{
    public function index()
    {
        // Get current academic year
        $currentAcademicYear = AcademicYear::where('is_current', true)->first();

        if (!$currentAcademicYear) {
            // If no current academic year, get the latest one
            $currentAcademicYear = AcademicYear::latest()->first();
        }

        // Get academic calendar events (exams for now)
        $events = [];

        if ($currentAcademicYear) {
            // Add academic year start and end
            $events[] = [
                'title' => 'Academic Year Start',
                'start' => $currentAcademicYear->start_date->format('Y-m-d'),
                'color' => '#28a745', // green
            ];

            $events[] = [
                'title' => 'Academic Year End',
                'start' => $currentAcademicYear->end_date->format('Y-m-d'),
                'color' => '#dc3545', // red
            ];

            // Add exams
            $exams = Exam::where('academic_year_id', $currentAcademicYear->id)->get();

            foreach ($exams as $exam) {
                $events[] = [
                    'title' => $exam->name,
                    'start' => $exam->start_date->format('Y-m-d'),
                    'end' => $exam->end_date->addDay()->format('Y-m-d'), // Add a day for proper display
                    'color' => '#007bff', // blue
                ];
            }
        }

        return Voyager::view('vendor.voyager.calendar', compact('events', 'currentAcademicYear'));
    }
}
