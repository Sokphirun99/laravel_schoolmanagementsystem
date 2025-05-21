<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use TCG\Voyager\Widgets\BaseDimmer;
use App\Models\ClassRoom;

class ClassDimmer extends BaseDimmer
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
            $count = ClassRoom::count();
            $string = $count == 1 ? 'Class' : 'Classes';

            return view('voyager::dimmer', array_merge($this->config, [
                'icon'   => 'voyager-book',
                'title'  => "{$count} {$string}",
                'text'   => __('You have '.$count.' '.$string.' in your database. Click on button below to view all classes.'),
                'button' => [
                    'text' => __('View all classes'),
                    'link' => route('voyager.classes.index'),
                ],
                'image' => voyager_asset('images/widget-backgrounds/02.jpg'),
            ]));
        } catch (\Exception $e) {
            Log::error('ClassDimmer error: ' . $e->getMessage());

            return view('voyager::dimmer', array_merge($this->config, [
                'icon'   => 'voyager-book',
                'title'  => 'Classes',
                'text'   => __('Unable to load class data. Please check BREAD configuration.'),
                'button' => [
                    'text' => __('Check Classes'),
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
        // Temporarily return true for debugging
        return true;

        // Uncomment this when ready:
        // return Auth::check() &&
        //        class_exists('App\Models\ClassRoom') &&
        //        Gate::allows('browse', app(ClassRoom::class));
    }
}
