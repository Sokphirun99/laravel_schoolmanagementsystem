<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Widgets\BaseDimmer;
use App\Models\Student;

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
        try {
            $count = Student::count();
            $string = $count == 1 ? 'Student' : 'Students';

            return view('voyager::dimmer', array_merge($this->config, [
                'icon'   => 'voyager-person',
                'title'  => "{$count} {$string}",
                'text'   => __('You have '.$count.' students in your database.'),
                'button' => [
                    'text' => __('View all students'),
                    'link' => route('voyager.students.index'),
                ],
                'image' => voyager_asset('images/widget-backgrounds/02.jpg'),
            ]));
        } catch (\Exception $e) {
            // Return a basic widget if there's an error
            return view('voyager::dimmer', array_merge($this->config, [
                'icon'   => 'voyager-study',
                'title'  => 'Students',
                'text'   => __('Error loading student count'),
                'button' => [
                    'text' => __('Fix Students'),
                    'link' => route('voyager.dashboard'),
                ],
                'image' => voyager_asset('images/widget-backgrounds/02.jpg'),
            ]));
        }
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        // Always show this widget for now (for debugging)
        return true;

        // Uncomment this when everything works:
        // return Auth::user()->can('browse', app(Student::class));
    }
}
