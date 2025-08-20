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
        $users = $this->buildUserQuery($request)->paginate(15);
        $roles = User::availableRoles();
        
        return view('admin.role-management.index', compact('users', 'roles'));
    }

    /**
     * Build user query with filters
     */
    private function buildUserQuery(Request $request)
    {
        $query = User::with('roles');
        
        if ($request->filled('role')) {
            $query->byRole($request->role);
        }
        
        if ($request->filled('status')) {
            $query = $request->status === 'active' 
                ? $query->active() 
                : $query->inactive();
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        return $query;
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
        $validated = $this->validateUserData($request);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'status' => $request->filled('status'),
        ]);

        return redirect()->route('admin.role-management.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Validate user data for create/update
     */
    private function validateUserData(Request $request, ?User $user = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role' => ['required', Rule::in(array_keys(User::availableRoles()))],
            'phone' => 'nullable|string|max:20',
            'status' => 'boolean',
        ];

        if ($user) {
            $rules['email'] = "required|string|email|max:255|unique:users,email,{$user->id}";
            $rules['password'] = 'nullable|string|min:8|confirmed';
        } else {
            $rules['email'] = 'required|string|email|max:255|unique:users';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $request->validate($rules);
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
        $this->ensureCanManage($user);
        
        $validated = $this->validateUserData($request, $user);
        
        $updateData = $this->prepareUpdateData($validated, $request);
        
        $user->update($updateData);

        return redirect()->route('admin.role-management.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Ensure current user can manage the target user
     */
    private function ensureCanManage(User $user): void
    {
        if (!Auth::user()->canManage($user)) {
            abort(403, 'You cannot manage this user.');
        }
    }

    /**
     * Prepare update data array
     */
    private function prepareUpdateData(array $validated, Request $request): array
    {
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'status' => $request->filled('status'),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        return $updateData;
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $this->ensureCanManage($user);

        $user->delete();

        return redirect()->route('admin.role-management.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        $this->ensureCanManage($user);

        $user->update(['status' => !$user->status]);

        $status = $user->status ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$status} successfully!");
    }

    /**
     * Bulk actions on users
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $validated['user_ids'])->get();
        
        $this->validateBulkPermissions($users, $validated);
        
        $message = $this->executeBulkAction($validated['action'], $validated['user_ids']);

        return redirect()->back()->with('success', $message);
    }

    /**
     * Validate permissions for bulk actions
     */
    private function validateBulkPermissions($users, array $validated): void
    {
        foreach ($users as $user) {
            if (!Auth::user()->canManage($user)) {
                throw new \Exception('You do not have permission to manage some of the selected users.');
            }
        }

        if ($validated['action'] === 'delete' && in_array(Auth::id(), $validated['user_ids'])) {
            throw new \Exception('You cannot delete your own account.');
        }
    }

    /**
     * Execute bulk action and return success message
     */
    private function executeBulkAction(string $action, array $userIds): string
    {
        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['status' => true]);
                return 'Selected users activated successfully!';
            case 'deactivate':
                User::whereIn('id', $userIds)->update(['status' => false]);
                return 'Selected users deactivated successfully!';
            case 'delete':
                User::whereIn('id', $userIds)->delete();
                return 'Selected users deleted successfully!';
            default:
                throw new \Exception('Invalid action');
        }
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        $users = $this->buildUserQuery($request)->get();
        
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        return response()->stream(
            fn() => $this->generateCsvOutput($users), 
            200, 
            $headers
        );
    }

    /**
     * Generate CSV output for export
     */
    private function generateCsvOutput($users): void
    {
        $file = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($file, [
            'ID', 'Name', 'Email', 'Role', 'Status', 
            'Phone', 'Created At', 'Last Login'
        ]);
        
        // Add user data
        foreach ($users as $user) {
            fputcsv($file, [
                $user->id,
                $user->name,
                $user->email,
                $user->roleText(),
                $user->status ? 'Active' : 'Inactive',
                $user->phone ?? 'N/A',
                $user->created_at->format('Y-m-d H:i:s'),
                $user->last_login_at?->format('Y-m-d H:i:s') ?? 'Never',
            ]);
        }
        
        fclose($file);
    }
}
