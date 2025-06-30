<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Call seeders in the correct order
        $this->call([
            SchoolClassesSeeder::class,
            SectionsSeeder::class,
            ParentsSeeder::class,
            StudentsSeeder::class,
            PortalUsersSeeder::class,
            EventsSeeder::class,
            FeesTableSeeder::class,
            TestUsersSeeder::class,
        ]);
    }
}
