<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RoleManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:admin,staff']);
    }

    /**
     * Display a listing of users with their roles
     */
    public function index(Request $request)
    {
        $query = User::with('roles');
        
        // Filter by role if specified
        if ($request->filled('role')) {
            $query->byRole($request->role);
        }
        
        // Filter by status if specified
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }
        
        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->paginate(15);
        $roles = User::availableRoles();
        
        return view('admin.role-management.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = User::availableRoles();
        return view('admin.role-management.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(array_keys(User::availableRoles()))],
            'phone' => 'nullable|string|max:20',
            'status' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'status' => $request->filled('status'),
        ]);

        return redirect()->route('admin.role-management.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load('student', 'teacher', 'parent');
        return view('admin.role-management.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Ensure only higher-level users can edit lower-level users
        if (!Auth::user()->canManage($user)) {
            abort(403, 'You cannot manage this user.');
        }
        
        $roles = User::availableRoles();
        return view('admin.role-management.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Ensure only higher-level users can edit lower-level users
        if (!Auth::user()->canManage($user)) {
            abort(403, 'You cannot manage this user.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => ['required', Rule::in(array_keys(User::availableRoles()))],
            'phone' => 'nullable|string|max:20',
            'status' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'status' => $request->filled('status'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.role-management.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        // Prevent deletion of own account
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // Ensure only higher-level users can delete lower-level users
        if (!Auth::user()->canManage($user)) {
            abort(403, 'You cannot delete this user.');
        }

        $user->delete();

        return redirect()->route('admin.role-management.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        // Ensure only higher-level users can modify lower-level users
        if (!Auth::user()->canManage($user)) {
            abort(403, 'You cannot modify this user.');
        }

        $user->update(['status' => !$user->status]);

        $status = $user->status ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$status} successfully!");
    }

    /**
     * Bulk actions on users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $request->user_ids;
        $users = User::whereIn('id', $userIds)->get();

        // Check permissions for all users
        foreach ($users as $user) {
            if (!Auth::user()->canManage($user)) {
                return redirect()->back()->with('error', 'You do not have permission to manage some of the selected users.');
            }
        }

        switch ($request->action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['status' => true]);
                $message = 'Selected users activated successfully!';
                break;
            case 'deactivate':
                User::whereIn('id', $userIds)->update(['status' => false]);
                $message = 'Selected users deactivated successfully!';
                break;
            case 'delete':
                // Prevent deletion of own account
                if (in_array(Auth::id(), $userIds)) {
                    return redirect()->back()->with('error', 'You cannot delete your own account.');
                }
                User::whereIn('id', $userIds)->delete();
                $message = 'Selected users deleted successfully!';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('role')) {
            $query->byRole($request->role);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }
        
        $users = $query->get();
        
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Status', 'Phone', 'Created At', 'Last Login']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->roleText(),
                    $user->status ? 'Active' : 'Inactive',
                    $user->phone ?? 'N/A',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
