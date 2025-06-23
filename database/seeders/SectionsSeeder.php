<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all class IDs
        $classes = DB::table('school_classes')->select('id')->get();
        
        foreach ($classes as $class) {
            // Create A and B sections for each class
            DB::table('sections')->insert([
                [
                    'name' => 'Section A',
                    'class_id' => $class->id,
                    'capacity' => 30,
                    'status' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'Section B',
                    'class_id' => $class->id,
                    'capacity' => 30,
                    'status' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ]);
        }
    }
}
