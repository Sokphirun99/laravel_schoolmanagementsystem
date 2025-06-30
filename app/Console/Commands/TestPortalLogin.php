<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class TestPortalLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:test-login {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test portal authentication with given credentials';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        
        $this->info("Testing portal login for email: {$email}");
        
        $credentials = [
            'email' => $email,
            'password' => $password,
        ];
        
        if (Auth::guard('portal')->attempt($credentials)) {
            $user = Auth::guard('portal')->user();
            $this->info("✅ Login successful!");
            $this->info("User details:");
            $this->info("Name: {$user->name}");
            $this->info("Email: {$user->email}");
            $this->info("Type: {$user->user_type}");
            return 0;
        } else {
            $this->error("❌ Authentication failed for {$email}");
            return 1;
        }
    }
}
