<?php

namespace App\VoyagerWidgets;

use TCG\Voyager\Widgets\BaseDimmer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class TeachersWidget extends BaseDimmer
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = \App\Models\Teacher::count();
        $activeCount = \App\Models\Teacher::where('status', 1)->count();
        $subjectCount = \App\Models\Teacher::select('subject_id')->distinct()->count();
        $classTeacherCount = \App\Models\Teacher::where('is_class_teacher', 1)->count();

        // Use the fully qualified function name to avoid namespace issues
        return \Illuminate\Support\Facades\View::make('vendor.voyager.widgets.teachers', [
            'totalCount' => $count,
            'subjectCount' => $subjectCount,
            'classTeacherCount' => $classTeacherCount
        ]);
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', \App\Models\Teacher::class);
    }
}
