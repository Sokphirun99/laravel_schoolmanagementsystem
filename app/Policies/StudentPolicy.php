<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function browse(User $user)
    {
        // Always allow admin access
        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasPermissionTo('browse_students');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function read(User $user, Student $student)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasPermissionTo('read_students');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function add(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasPermissionTo('add_students');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function edit(User $user, Student $student)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasPermissionTo('edit_students');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Student $student)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasPermissionTo('delete_students');
    }
}
