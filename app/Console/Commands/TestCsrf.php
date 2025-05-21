<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

class TestCsrf extends Command
{
    protected $signature = 'test:csrf';
    protected $description = 'Test CSRF token generation and storage';

    public function handle()
    {
        $this->info('Checking session configuration...');
        $this->info('Session driver: ' . config('session.driver'));
        $this->info('Session lifetime: ' . config('session.lifetime') . ' minutes');

        $this->info('Regenerating application key...');
        Artisan::call('key:generate', ['--force' => true]);
        $this->info('Application key regenerated successfully.');

        $this->info('Clearing caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        $this->info('Caches cleared.');

        $this->info('Done. Please try accessing the login page again.');
    }
}
