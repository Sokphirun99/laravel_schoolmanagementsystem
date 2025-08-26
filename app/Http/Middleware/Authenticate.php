<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;
use function url;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Send portal requests to portal login when using the portal guard
            if ($request->is('portal/*') || ($request->route() && $request->route()->getName() && str_starts_with($request->route()->getName(), 'portal.'))) {
                return Route::has('portal.login') ? route('portal.login') : url('/');
            }
            // Fallback to default login if available, else home
            return Route::has('login') ? route('login') : url('/');
        }
    }
}
