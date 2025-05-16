<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    /**
     * Show the reports dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // This route is protected by the check.role middleware
        // so we know the user has one of the allowed roles

        return view('shared.reports');
    }
}
