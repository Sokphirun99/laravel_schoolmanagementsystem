<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\PortalUser;
use App\Models\User;

class TeacherPortalUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure base user exists
        $user = User::firstOrCreate(
            ['email' => 'teacher@test.com'],
            [
                'name' => 'Test Teacher',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'status' => true,
            ]
        );

        // Create/update portal user
        $portal = PortalUser::firstOrNew(['email' => 'teacher@test.com']);
        $portal->name = $user->name;
        $portal->email = $user->email;
        $portal->password = Hash::make('password');
        $portal->user_type = 'teacher';
        $portal->related_id = null; // Optional link to teacher profile when available
        $portal->status = true;
        $portal->save();
    }
}
