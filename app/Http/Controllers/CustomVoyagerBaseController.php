<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadImagesDeleted;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomVoyagerBaseController extends VoyagerBaseController
{
    /**
     * Override the default authorization check to skip it for admin users
     *
     * @param string $ability
     * @param array|mixed $arguments
     * @return void
     */
    public function authorize($ability, $arguments = [])
    {
        // Skip authorization check if user is admin
        if (Auth::user() && Auth::user()->isAdmin()) {
            return;
        }

        // Otherwise, use the standard Laravel authorization
        parent::authorize($ability, $arguments);
    }
}
