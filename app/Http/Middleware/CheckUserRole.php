<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->guest(route('login'));
        }

        $user = Auth::user();
        
        // Check if user account is active
        if (!$user->status) {
            Auth::logout();
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Account is inactive.'], 403);
            }
            return redirect()->route('login')->withErrors(['email' => 'Your account has been deactivated.']);
        }
        
        // If no roles are required, allow access
        if (empty($roles)) {
            return $next($request);
        }
        
        // Handle special role checks
        foreach ($roles as $role) {
            // Check for role level requirements (e.g., 'level:80' means minimum level 80)
            if (strpos($role, 'level:') === 0) {
                $requiredLevel = (int) substr($role, 6);
                if ($user->hasRoleLevel($requiredLevel)) {
                    return $next($request);
                }
                continue;
            }
            
            // Check for permission requirements (e.g., 'permission:manage_users')
            if (strpos($role, 'permission:') === 0) {
                $permission = substr($role, 11);
                if ($user->hasPermission($permission)) {
                    return $next($request);
                }
                continue;
            }
            
            // Regular role check
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // If we're still here, the user doesn't have the required roles
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Insufficient permissions to access this resource.',
                'required_roles' => $roles,
                'user_role' => $user->role
            ], 403);
        }
        
        return abort(403, 'You do not have permission to access this page.');
    }
}
