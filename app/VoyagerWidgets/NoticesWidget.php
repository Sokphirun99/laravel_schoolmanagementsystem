<?php

namespace App\VoyagerWidgets;

use TCG\Voyager\Widgets\BaseDimmer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class NoticesWidget extends BaseDimmer
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
        $notices = \App\Models\Notice::orderBy('created_at', 'desc')->limit(5)->get();

        return View::make('vendor.voyager.widgets.notices', [
            'notices' => $notices
        ]);
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', \App\Models\Notice::class);
    }
}
