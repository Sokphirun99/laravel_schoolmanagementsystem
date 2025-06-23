<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ParentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create user accounts for parents
        $parents = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St, Cityville',
                'occupation' => 'Engineer',
                'gender' => 'male',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '9876543210',
                'address' => '456 Oak Dr, Townsville',
                'occupation' => 'Doctor',
                'gender' => 'female',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
                'email' => 'michael.johnson@example.com',
                'phone' => '5551234567',
                'address' => '789 Pine Rd, Villagetown',
                'occupation' => 'Architect',
                'gender' => 'male',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        
        foreach ($parents as $parent) {
            // First create a user
            $userId = DB::table('users')->insertGetId([
                'name' => $parent['first_name'] . ' ' . $parent['last_name'],
                'email' => $parent['email'],
                'password' => Hash::make('password'), // Default password
                'role' => 'parent',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            // Then create parent with reference to user
            $parent['user_id'] = $userId;
            DB::table('parents')->insert($parent);
        }
    }
}
