<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get class and section IDs
        $classes = DB::table('school_classes')->get();
        $sections = DB::table('sections')->get();
        $parents = DB::table('parents')->get();
        
        $studentData = [
            [
                'first_name' => 'Alex',
                'last_name' => 'Doe',
                'email' => 'alex.doe@example.com',
                'date_of_birth' => '2015-05-15',
                'gender' => 'male',
                'parent_id' => $parents[0]->id ?? null
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Doe',
                'email' => 'emma.doe@example.com',
                'date_of_birth' => '2017-03-22',
                'gender' => 'female',
                'parent_id' => $parents[0]->id ?? null
            ],
            [
                'first_name' => 'Ryan',
                'last_name' => 'Smith',
                'email' => 'ryan.smith@example.com',
                'date_of_birth' => '2016-08-10',
                'gender' => 'male',
                'parent_id' => $parents[1]->id ?? null
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Johnson',
                'email' => 'sophia.johnson@example.com',
                'date_of_birth' => '2015-11-05',
                'gender' => 'female',
                'parent_id' => $parents[2]->id ?? null
            ]
        ];
        
        $classCount = count($classes);
        $sectionCount = count($sections);
        
        foreach ($studentData as $index => $data) {
            // Assign to different classes and sections
            $classIndex = $index % $classCount;
            $sectionIndex = $index % $sectionCount;
            
            // Create user account
            $userId = DB::table('users')->insertGetId([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make('password'), // Default password
                'role' => 'student',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            // Create student
            DB::table('students')->insert([
                'student_id' => 'ST' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
                'class_id' => $classes[$classIndex]->id ?? null,
                'section_id' => $sections[$sectionIndex]->id ?? null,
                'parent_id' => $data['parent_id'],
                'admission_date' => Carbon::now()->subMonths(rand(1, 12))->format('Y-m-d'),
                'status' => true,
                'user_id' => $userId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
