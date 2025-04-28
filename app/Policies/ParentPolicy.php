<?php

namespace App\Policies;

use App\Models\Parents;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any parents.
     */
    public function viewAny(User $user)
    {
        // Allow admins and staff to view any parents
        return $user->hasRole('admin') || $user->hasRole('staff');
    }

    /**
     * Determine whether the user can view a parent.
     */
    public function view(User $user, Parents $parents)
    {
        // Allow admins, staff, or the parent themselves
        return $user->hasRole('admin') || $user->hasRole('staff') || $user->id === $parents->user_id;
    }

    /**
     * Determine whether the user can create parents.
     */
    public function create(User $user)
    {
        // Only admins and staff can create parent records
        return $user->hasRole('admin') || $user->hasRole('staff');
    }

    /**
     * Determine whether the user can update a parent.
     */
    public function update(User $user, Parents $parents)
    {
        // Allow admins, staff, or the parent themselves
        return $user->hasRole('admin') || $user->hasRole('staff') || $user->id === $parents->user_id;
    }

    /**
     * Determine whether the user can delete a parent.
     */
    public function delete(User $user, Parents $parents)
    {
        // Only admins can delete parent records
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore a parent.
     */
    public function restore(User $user, Parents $parents)
    {
        // Only admins can restore parent records
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete a parent.
     */
    public function forceDelete(User $user, Parents $parents)
    {
        // Only admins can force delete parent records
        return $user->hasRole('admin');
    }
}
