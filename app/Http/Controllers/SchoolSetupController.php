<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class SchoolSetupController extends Controller
{
    /**
     * Initialize a new school with basic data
     */
    public function initializeSchool(Request $request)
    {
        // Validate request
        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_code' => 'required|string|max:50|unique:schools,code',
            'principal_name' => 'required|string|max:255',
            'academic_year_name' => 'required|string|max:255',
            'academic_year_start' => 'required|date',
            'academic_year_end' => 'required|date|after:academic_year_start',
        ]);

        try {
            DB::beginTransaction();

            // Create school
            $school = School::create([
                'name' => $request->school_name,
                'code' => $request->school_code,
                'principal_name' => $request->principal_name,
                'established_date' => Carbon::now(),
                'status' => 'active',
            ]);

            // Create academic year
            $academicYear = AcademicYear::create([
                'name' => $request->academic_year_name,
                'start_date' => $request->academic_year_start,
                'end_date' => $request->academic_year_end,
                'is_current' => true,
                'school_id' => $school->id,
            ]);

            // Create default classes (1-12)
            for ($i = 1; $i <= 12; $i++) {
                $class = ClassRoom::create([
                    'name' => 'Grade ' . $i,
                    'numeric_value' => $i,
                    'description' => 'Grade ' . $i,
                    'school_id' => $school->id,
                ]);

                // Create default sections (A, B, C) for each class
                foreach (['A', 'B', 'C'] as $sectionName) {
                    Section::create([
                        'name' => $sectionName,
                        'class_id' => $class->id,
                        'capacity' => 40,
                        'academic_year_id' => $academicYear->id,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('voyager.dashboard')->with([
                'message' => 'School setup completed successfully!',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'message' => 'School setup failed: ' . $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * Display the initial setup form
     */
    public function showSetupForm()
    {
        // Check if setup has already been done
        if (School::count() > 0) {
            return redirect()->route('voyager.dashboard')->with([
                'message' => 'School is already set up!',
                'alert-type' => 'info',
            ]);
        }

        return view('vendor.voyager.setup');
    }

    /**
     * Run all migrations
     */
    public function runMigrations()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            return redirect()->route('school.setup')->with([
                'message' => 'Database migrations completed successfully!',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'message' => 'Database migrations failed: ' . $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }
}
