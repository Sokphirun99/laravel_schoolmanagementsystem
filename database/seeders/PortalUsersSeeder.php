<?php

namespace Database\Seeders;

use App\Models\ParentModel;
use App\Models\PortalUser;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PortalUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create portal accounts for students
        $students = Student::all();
        foreach ($students as $student) {
            PortalUser::create([
                'name' => $student->first_name . ' ' . $student->last_name,
                'email' => $student->email,
                'password' => Hash::make('password'), // Default password, should be changed
                'user_type' => 'student',
                'related_id' => $student->id,
                'status' => true,
            ]);
        }

        // Create portal accounts for parents
        $parents = ParentModel::all();
        foreach ($parents as $parent) {
            PortalUser::create([
                'name' => $parent->first_name . ' ' . $parent->last_name,
                'email' => $parent->email,
                'password' => Hash::make('password'), // Default password, should be changed
                'user_type' => 'parent',
                'related_id' => $parent->id,
                'status' => true,
            ]);
        }
    }
}
