<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create some sample school classes
        $classes = [
            [
                'name' => 'Grade 1',
                'description' => 'First grade elementary class',
                'academic_year' => date('Y'),
                'status' => true
            ],
            [
                'name' => 'Grade 2',
                'description' => 'Second grade elementary class',
                'academic_year' => date('Y'),
                'status' => true
            ],
            [
                'name' => 'Grade 3',
                'description' => 'Third grade elementary class',
                'academic_year' => date('Y'),
                'status' => true
            ],
            [
                'name' => 'Grade 4',
                'description' => 'Fourth grade elementary class',
                'academic_year' => date('Y'),
                'status' => true
            ],
        ];

        foreach ($classes as $class) {
            DB::table('school_classes')->insert($class);
        }
    }
}
