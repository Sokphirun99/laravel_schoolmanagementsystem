<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DatabaseConfigProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Check if we're running in Docker or locally
        $this->configureDatabase();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Configure database connection based on environment
     */
    protected function configureDatabase(): void
    {
        try {
            // Check if Docker's "db" host is resolvable
            $isDockerDbAvailable = gethostbyname('db') !== 'db';

            // If we can't resolve 'db' hostname, use localhost
            if (!$isDockerDbAvailable) {
                config(['database.connections.mysql.host' => '127.0.0.1']);
                app('log')->info('Using local database connection (127.0.0.1) instead of Docker "db" hostname');
            } else {
                app('log')->info('Using Docker database connection with "db" hostname');
            }
        } catch (\Exception $e) {
            // In case of any error, default to localhost
            config(['database.connections.mysql.host' => '127.0.0.1']);
            app('log')->error('Error detecting database environment: ' . $e->getMessage());
        }
    }
}
