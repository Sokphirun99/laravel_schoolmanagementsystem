<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display the user's profile form.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }
    
    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists and is not the default
            if ($user->avatar && $user->avatar !== 'users/default.png' && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar
            $avatarPath = $request->file('avatar')->store('users', 'public');
            $user->avatar = $avatarPath;
        }
        
        // Update user information
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->save();
        
        // Update specific profile information based on role
        $this->updateRoleSpecificProfile($request, $user);
        
        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Update role-specific profile information.
     */
    protected function updateRoleSpecificProfile(Request $request, User $user)
    {
        if ($user->isTeacher() && $user->teacher) {
            $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'qualification' => 'nullable|string|max:255',
                'experience' => 'nullable|string|max:255',
            ]);
            
            $user->teacher->update([
                'first_name' => $request->first_name ?? $user->teacher->first_name,
                'last_name' => $request->last_name ?? $user->teacher->last_name,
                'qualification' => $request->qualification ?? $user->teacher->qualification,
                'experience' => $request->experience ?? $user->teacher->experience,
            ]);
        } elseif ($user->isStudent() && $user->student) {
            $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
            ]);
            
            $user->student->update([
                'first_name' => $request->first_name ?? $user->student->first_name,
                'last_name' => $request->last_name ?? $user->student->last_name,
            ]);
        } elseif ($user->isParent() && $user->parent) {
            $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'occupation' => 'nullable|string|max:255',
            ]);
            
            $user->parent->update([
                'first_name' => $request->first_name ?? $user->parent->first_name,
                'last_name' => $request->last_name ?? $user->parent->last_name,
                'occupation' => $request->occupation ?? $user->parent->occupation,
            ]);
        }
    }
    
    /**
     * Show the change password form.
     */
    public function showChangePasswordForm()
    {
        return view('users.change-password');
    }
    
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('profile')->with('success', 'Password changed successfully.');
    }
}
