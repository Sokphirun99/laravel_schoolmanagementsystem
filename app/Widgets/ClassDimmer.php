<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        $count = ClassRoom::count();
        $string = 'Classes';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-book',
            'title'  => "{$count} {$string}",
            'text'   => __('You have '.$count.' classes in your database. Click on button below to view all classes.'),
            'button' => [
                'text' => __('View all classes'),
                // Make sure this matches your BREAD slug
                'link' => route('voyager.classes.index'),
            ],
            'image' => asset('images/widget-backgrounds/classes.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
{
    return Gate::allows('browse', app(ClassRoom::class));
}
}
