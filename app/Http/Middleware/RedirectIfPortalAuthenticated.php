<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfPortalAuthenticated
{
    public function handle($request, Closure $next, $guard = 'portal')
    {
        if (Auth::guard($guard)->check()) {
            return redirect()->route('portal.dashboard');
        }

        return $next($request);
    }
}
