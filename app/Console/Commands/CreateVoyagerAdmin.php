<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TCG\Voyager\Models\User;
use TCG\Voyager\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateVoyagerAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:admin {--email=admin@school.com} {--password=password} {--name=Admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update Voyager admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');
        
        $this->info("Setting up Voyager admin user...");
        
        // Find admin role
        try {
            $adminRole = Role::where('name', 'admin')->firstOrFail();
        } catch (\Exception $e) {
            $this->error('Admin role not found. Make sure Voyager is installed correctly.');
            $this->info('Running voyager:install command...');
            $this->call('voyager:install', ['--with-dummy' => true]);
            
            // Try again to get the admin role
            $adminRole = Role::where('name', 'admin')->firstOrFail();
        }

        // Check if user exists
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            // Create new admin
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->role_id = $adminRole->id;
            $user->save();
            
            $this->info("Admin user created successfully: {$email} / {$password}");
        } else {
            // Update existing admin
            $user->name = $name;
            $user->password = Hash::make($password);
            $user->role_id = $adminRole->id;
            $user->save();
            
            $this->info("Admin user updated successfully: {$email} / {$password}");
        }
    }
}
