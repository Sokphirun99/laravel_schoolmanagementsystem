<?php

namespace App\VoyagerWidgets;

use App\Services\DashboardService;
use TCG\Voyager\Widgets\BaseDimmer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
    public function run(DashboardService $dashboardService)
    {
        return View::make('vendor.voyager.widgets.teachers', $dashboardService->getTeacherWidgetData());
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
