<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolClass;
use App\Models\Section;

class BasicSchoolStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create basic school classes
        $currentYear = date('Y');
        $classes = [
            ['name' => 'Grade 1', 'description' => 'First Grade', 'academic_year' => $currentYear],
            ['name' => 'Grade 2', 'description' => 'Second Grade', 'academic_year' => $currentYear],
            ['name' => 'Grade 3', 'description' => 'Third Grade', 'academic_year' => $currentYear],
            ['name' => 'Grade 4', 'description' => 'Fourth Grade', 'academic_year' => $currentYear],
            ['name' => 'Grade 5', 'description' => 'Fifth Grade', 'academic_year' => $currentYear],
        ];

        $createdClasses = [];
        foreach ($classes as $class) {
            $createdClass = SchoolClass::firstOrCreate(['name' => $class['name']], $class);
            $createdClasses[] = $createdClass;
        }

        // Create basic sections for each class
        $sectionNames = ['Section A', 'Section B', 'Section C'];
        
        foreach ($createdClasses as $schoolClass) {
            foreach ($sectionNames as $sectionName) {
                Section::firstOrCreate(
                    ['name' => $sectionName, 'class_id' => $schoolClass->id],
                    ['name' => $sectionName, 'class_id' => $schoolClass->id, 'capacity' => 30]
                );
            }
        }

        echo "Basic school structure created successfully!\n";
    }
}
