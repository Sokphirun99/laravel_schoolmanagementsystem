<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator'],
            ['name' => 'teacher', 'display_name' => 'Teacher'],
            ['name' => 'student', 'display_name' => 'Student'],
            ['name' => 'parent', 'display_name' => 'Parent'],
            ['name' => 'staff', 'display_name' => 'Staff'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}

