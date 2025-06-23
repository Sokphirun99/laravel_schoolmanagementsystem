<?php

namespace App\VoyagerWidgets;

use TCG\Voyager\Widgets\BaseDimmer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class StudentsWidget extends BaseDimmer
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
        $totalCount = \App\Models\Student::count();
        $activeCount = \App\Models\Student::where('status', 1)->count();
        
        // Get new students this month
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        $newThisMonth = \App\Models\Student::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        return View::make('vendor.voyager.widgets.students', [
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'newThisMonth' => $newThisMonth,
        ]);
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', \App\Models\Student::class);
    }
}
