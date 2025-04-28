<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Widgets\BaseDimmer;
use App\Models\Parents;
use Illuminate\Support\Facades\Gate;

class ParentsDimmer extends BaseDimmer
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
        $count = Parents::count();
        $string = trans_choice('parent|parents', $count);

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-people',
            'title'  => "{$count} {$string}",
            'text'   => __('You have '.$count.' parents in your database. Click on button below to view all parents.'),
            'button' => [
                'text' => __('View all parents'),
                'link' => route('voyager.parents.index'),
            ],
            'image' => asset('images/widget-backgrounds/parents.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
{
    return Gate::allows('browse', app(Parents::class));
}
}
