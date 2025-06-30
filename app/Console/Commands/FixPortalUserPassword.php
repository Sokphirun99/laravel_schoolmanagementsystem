<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PortalUser;
use Illuminate\Support\Facades\Hash;

class FixPortalUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:fix-password {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the password for portal users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = PortalUser::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        $user->password = Hash::make('password');
        $user->save();
        
        $this->info("Password updated for {$email}");
        return 0;
    }
}
