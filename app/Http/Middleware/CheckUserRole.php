<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request and check if the user has the required role.
     * This middleware supports both the legacy role_id system and the new user_roles relationship.
     *
     * Usage:
     * Route::middleware(['check.role:student'])->group(function() { ... })
     * Route::middleware(['check.role:student,teacher'])->group(function() { ... })
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user();

        // If no roles are specified, just check if the user is authenticated
        if (empty($roles)) {
            return $next($request);
        }

        // Convert role names to lowercase for comparison
        $roles = array_map('strtolower', $roles);

        foreach ($roles as $role) {
            // Check using the hasRoleName method (preferred method)
            if ($user->hasRoleName($role)) {
                return $next($request);
            }

            // Check using the corresponding method if available (secondary check)
            $methodName = 'is' . ucfirst($role);
            if (method_exists($user, $methodName) && $user->$methodName()) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized. You do not have the required role.'], 403);
        }

        return redirect()->back()->with('error', 'You do not have permission to access this page.');
    }
}
