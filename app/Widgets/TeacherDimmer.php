<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Widgets\BaseDimmer;
use App\Models\Teacher;

class TeacherDimmer extends BaseDimmer
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
        $count = Teacher::count();
        $string = 'Teachers';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-study',
            'title'  => "{$count} {$string}",
            'text'   => __('You have '.$count.' teachers in your database. Click on button below to view all teachers.'),
            'button' => [
                'text' => __('View all teachers'),
                'link' => route('voyager.teachers.index'),
            ],
            'image' => asset('images/widget-backgrounds/teachers.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::check() && Auth::user()->can('browse', app(Teacher::class));
    }
}
