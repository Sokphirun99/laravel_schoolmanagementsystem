<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Ensure Log facade is imported
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
        try {
            $count = Teacher::count();
            $string = $count == 1 ? 'Teacher' : 'Teachers';

            return view('voyager::dimmer', array_merge($this->config, [
                'icon'   => 'voyager-person',
                'title'  => "{$count} {$string}",
                'text'   => __('You have '.$count.' '.$string.' in your database. Click on button below to view all teachers.'),
                'button' => [
                    'text' => __('View all teachers'),
                    'link' => route('voyager.teachers.index'),
                ],
                'image' => voyager_asset('images/widget-backgrounds/03.jpg'),
            ]));
        } catch (\Exception $e) {
            Log::error('TeacherDimmer error: ' . $e->getMessage());

            return view('voyager::dimmer', array_merge($this->config, [
                'icon'   => 'voyager-person',
                'title'  => 'Teachers',
                'text'   => __('Unable to load teacher data. Please check BREAD configuration.'),
                'button' => [
                    'text' => __('Check Teachers'),
                    'link' => route('voyager.dashboard'),
                ],
                'image' => voyager_asset('images/widget-backgrounds/03.jpg'),
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
        // Temporarily return true for debugging
        return true;

        // Uncomment this when ready:
        // Make sure the Teacher model exists first
        // return Auth::check() &&
        //        class_exists('App\Models\Teacher') &&
        //        Auth::user()->can('browse', app(Teacher::class));
    }
}
