<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Create a new student user with proper role assignments
     *
     * @param array $userData
     * @param array $studentData
     * @return array
     */
    public function createStudentUser(array $userData, array $studentData)
    {
        DB::beginTransaction();

        try {
            // Create user
            $user = new User();
            $user->name = $userData['name'] ?? ($studentData['first_name'] . ' ' . $studentData['last_name']);
            $user->email = $userData['email'];
            $user->password = Hash::make($userData['password'] ?? 'password123');
            $user->role_id = Role::STUDENT; // Set primary role
            $user->save();

            // Ensure role is in user_roles table
            $user->assignRole(Role::STUDENT);

            // Create student record
            $student = new Student();
            $student->fill($studentData);
            $student->user_id = $user->id;
            $student->save();

            DB::commit();

            return [
                'success' => true,
                'user' => $user,
                'student' => $student
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Error creating student user: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create a new teacher user with proper role assignments
     *
     * @param array $userData
     * @param array $teacherData
     * @return array
     */
    public function createTeacherUser(array $userData, array $teacherData)
    {
        DB::beginTransaction();

        try {
            // Create user
            $user = new User();
            $user->name = $userData['name'] ?? ($teacherData['first_name'] . ' ' . $teacherData['last_name']);
            $user->email = $userData['email'];
            $user->password = Hash::make($userData['password'] ?? 'password123');
            $user->role_id = Role::TEACHER; // Set primary role
            $user->save();

            // Ensure role is in user_roles table
            $user->assignRole(Role::TEACHER);

            // Create teacher record
            $teacher = new Teacher();
            $teacher->fill($teacherData);
            $teacher->user_id = $user->id;
            $teacher->save();

            DB::commit();

            return [
                'success' => true,
                'user' => $user,
                'teacher' => $teacher
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Error creating teacher user: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update a student user and related student record
     *
     * @param User $user
     * @param array $userData
     * @param array $studentData
     * @return array
     */
    public function updateStudentUser(User $user, array $userData, array $studentData)
    {
        DB::beginTransaction();

        try {
            // Update user data
            $user->name = $userData['name'] ?? $user->name;

            if (isset($userData['email'])) {
                $user->email = $userData['email'];
            }

            if (isset($userData['password'])) {
                $user->password = Hash::make($userData['password']);
            }

            // Make sure user has student role
            $user->role_id = Role::STUDENT;
            $user->save();
            $user->assignRole(Role::STUDENT);

            // Update student record
            $student = $user->student;

            if ($student) {
                $student->fill($studentData);
                $student->save();
            }

            DB::commit();

            return [
                'success' => true,
                'user' => $user,
                'student' => $student
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Error updating student user: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update a teacher user and related teacher record
     *
     * @param User $user
     * @param array $userData
     * @param array $teacherData
     * @return array
     */
    public function updateTeacherUser(User $user, array $userData, array $teacherData)
    {
        DB::beginTransaction();

        try {
            // Update user data
            $user->name = $userData['name'] ?? $user->name;

            if (isset($userData['email'])) {
                $user->email = $userData['email'];
            }

            if (isset($userData['password'])) {
                $user->password = Hash::make($userData['password']);
            }

            // Make sure user has teacher role
            $user->role_id = Role::TEACHER;
            $user->save();
            $user->assignRole(Role::TEACHER);

            // Update teacher record
            $teacher = $user->teacher;

            if ($teacher) {
                $teacher->fill($teacherData);
                $teacher->save();
            }

            DB::commit();

            return [
                'success' => true,
                'user' => $user,
                'teacher' => $teacher
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Error updating teacher user: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get all users with student role
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllStudentUsers()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('roles.id', Role::STUDENT);
        })->orWhere('role_id', Role::STUDENT)->get();
    }

    /**
     * Get all users with teacher role
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTeacherUsers()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('roles.id', Role::TEACHER);
        })->orWhere('role_id', Role::TEACHER)->get();
    }
}
