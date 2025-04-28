<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\Permission;

class SchoolRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Teacher Role
        $teacherRole = Role::firstOrNew(['name' => 'teacher']);
        if (!$teacherRole->exists) {
            $teacherRole->fill([
                'display_name' => 'Teacher',
            ])->save();
        }

        // Create Student Role
        $studentRole = Role::firstOrNew(['name' => 'student']);
        if (!$studentRole->exists) {
            $studentRole->fill([
                'display_name' => 'Student',
            ])->save();
        }

        // Create Parent Role
        $parentRole = Role::firstOrNew(['name' => 'parent']);
        if (!$parentRole->exists) {
            $parentRole->fill([
                'display_name' => 'Parent',
            ])->save();
        }

        // Assign appropriate permissions to each role
        $this->assignPermissions($teacherRole, [
            'browse_admin',
            'browse_students',
            'read_students',
            'browse_parents',
            'read_parents',
            'browse_classes',
            'read_classes',
            'browse_subjects',
            'read_subjects',
            'browse_attendances',
            'add_attendances',
            'edit_attendances',
            'browse_exams',
            'read_exams',
            'browse_exam_results',
            'add_exam_results',
            'edit_exam_results',
            'read_exam_results',
        ]);

        $this->assignPermissions($studentRole, [
            'browse_admin',
            'browse_attendances',
            'read_attendances',
            'browse_exams',
            'read_exams',
            'browse_exam_results',
            'read_exam_results',
        ]);

        $this->assignPermissions($parentRole, [
            'browse_admin',
            'browse_students',
            'read_students',
            'browse_attendances',
            'read_attendances',
            'browse_exams',
            'read_exams',
            'browse_exam_results',
            'read_exam_results',
        ]);
    }

    /**
     * Assign permissions to a role
     *
     * @param Role $role
     * @param array $permissions
     * @return void
     */
    private function assignPermissions($role, $permissions)
    {
        $permissionIds = Permission::whereIn('key', $permissions)->pluck('id')->toArray();
        $role->permissions()->sync($permissionIds);
    }
}
