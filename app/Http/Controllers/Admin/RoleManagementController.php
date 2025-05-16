<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;

class RoleManagementController extends Controller
{
    /**
     * Display a listing of users with their roles
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('browse_users');

        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('admin.manage_roles', compact('users', 'roles'));
    }

    /**
     * Get roles for a specific user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRoles(Request $request)
    {
        $this->authorize('edit', User::class);

        $userId = $request->input('user_id');
        $user = User::findOrFail($userId);

        $roleIds = $user->roles->pluck('id')->toArray();

        return response()->json([
            'role_ids' => $roleIds
        ]);
    }

    /**
     * Update roles for a specific user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserRoles(Request $request)
    {
        $this->authorize('edit', User::class);

        $userId = $request->input('user_id');
        $roleIds = $request->input('roles', []);

        $user = User::findOrFail($userId);

        // Clear existing roles
        UserRole::where('user_id', $userId)->delete();

        // Add new roles
        foreach ($roleIds as $roleId) {
            UserRole::create([
                'user_id' => $userId,
                'role_id' => $roleId
            ]);
        }

        // If no role is selected, use default role 2 (user)
        if (empty($roleIds)) {
            UserRole::create([
                'user_id' => $userId,
                'role_id' => 2 // Default user role
            ]);
        }

        // Update the legacy role_id column for backwards compatibility
        if (!empty($roleIds)) {
            $user->role_id = $roleIds[0]; // Use the first selected role as primary
        } else {
            $user->role_id = 2; // Default user role
        }
        $user->save();

        return redirect()->route('admin.manage-roles')
            ->with('success', 'User roles have been updated successfully.');
    }
}
