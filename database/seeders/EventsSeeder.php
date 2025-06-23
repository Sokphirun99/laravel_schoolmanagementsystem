<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [
            [
                'title' => 'Annual Day Celebration',
                'description' => 'Annual day celebration of the school with various cultural programs and performances.',
                'date' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'location' => 'School Auditorium',
                'type' => 'other',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'title' => 'Mid-Term Examinations',
                'description' => 'Mid-term examinations for all classes.',
                'date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'location' => 'Respective Classrooms',
                'type' => 'exam',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'title' => 'Parent-Teacher Meeting',
                'description' => 'Meeting to discuss student progress and performance.',
                'date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'location' => 'School Conference Hall',
                'type' => 'meeting',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'title' => 'Science Fair',
                'description' => 'Annual science fair showcasing student projects and innovations.',
                'date' => Carbon::now()->addDays(20)->format('Y-m-d'),
                'location' => 'School Playground',
                'type' => 'other',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];
        
        DB::table('events')->insert($events);
    }
}
