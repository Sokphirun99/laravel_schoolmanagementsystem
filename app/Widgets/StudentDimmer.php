<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Widgets\BaseDimmer;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;

class StudentDimmer extends BaseDimmer
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
        $count = Student::count();
        $string = 'Students';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-people',
            'title'  => "{$count} {$string}",
            'text'   => __('You have '.$count.' students in your database. Click on button below to view all students.'),
            'button' => [
                'text' => __('View all students'),
                'link' => route('voyager.students.index'),
            ],
            'image' => asset('images/widget-backgrounds/students.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::check() && Auth::user()->can('browse', app(Student::class));
    }
}
