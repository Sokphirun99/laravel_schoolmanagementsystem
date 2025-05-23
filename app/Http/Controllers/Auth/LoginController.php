<?php

namespace App\Http\Controllers\Auth;

use TCG\Voyager\Http\Controllers\AuthController as VoyagerAuthController;

class LoginController extends VoyagerAuthController
{
    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        $user = auth()->user();
        $roleRedirects = config('voyager.user.role_redirects', []);
        
        // Get the user's first role
        $role = $user->role ? $user->role->name : ($user->roles->first() ? $user->roles->first()->name : null);
        
        // If user has a role and there's a redirect path defined for this role
        if ($role && isset($roleRedirects[$role])) {
            return $roleRedirects[$role];
        }
        
        // Default Voyager redirect
        return config('voyager.user.redirect', '/admin');
    }
}