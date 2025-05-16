<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;

class RoleDemoController extends Controller
{
    /**
     * Show the role management demo page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('role_demo.index');
    }

    /**
     * Show detailed information about assigned roles
     *
     * @return \Illuminate\View\View
     */
    public function roleDetails()
    {
        $roles = Role::all();
        $userRoles = UserRole::where('user_id', Auth::id())->with('role')->get();

        return view('role_demo.details', compact('roles', 'userRoles'));
    }
}
