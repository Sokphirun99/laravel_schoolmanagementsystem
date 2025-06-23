<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Override database configuration for Docker environment
$config = app('config');
$config->set('database.connections.mysql.host', 'db');
$config->set('database.connections.mysql.database', 'laravel');
$config->set('database.connections.mysql.username', 'laravel');
$config->set('database.connections.mysql.password', 'secret');

use TCG\Voyager\Models\User;
use TCG\Voyager\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Force reconnecting to database with new settings
DB::reconnect();

$adminRole = Role::where('name', 'admin')->firstOrFail();

// Check if admin user exists
$adminUser = User::where('email', 'admin@school.com')->first();

if (!$adminUser) {
    // Create admin user if it doesn't exist
    echo "Creating admin user...\n";
    $admin = new User();
    $admin->name = 'Admin User';
    $admin->email = 'admin@school.com';
    $admin->password = Hash::make('password');
    $admin->role_id = $adminRole->id;
    $admin->save();
    echo "Admin user created successfully!\n";
} else {
    // Update admin password
    echo "Updating admin user password...\n";
    $adminUser->password = Hash::make('password');
    $adminUser->save();
    echo "Admin password updated successfully!\n";
}

echo "Admin login credentials:\n";
echo "Email: admin@school.com\n";
echo "Password: password\n";
