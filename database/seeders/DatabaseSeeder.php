<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserManagementMenuItemsSeeder;
use Database\Seeders\StudentsSeeder;
use Database\Seeders\ParentsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\ClassesBreadSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // other seeders
            UserManagementMenuItemsSeeder::class,
            StudentsSeeder::class,
            ClassesBreadSeeder::class,
        ]);
    }
}
