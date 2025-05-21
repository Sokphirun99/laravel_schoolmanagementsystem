<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Use custom storage path for Vercel if STORAGE_PATH is set
        if (isset($_ENV['STORAGE_PATH'])) {
            $this->app->instance('path.storage', $_ENV['STORAGE_PATH']);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS on production (like Vercel)
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        
        // Fix for avatars and other assets
        $appUrl = config('app.url');
        
        // Handle Vercel URL specifically
        if (isset($_SERVER['VERCEL_URL']) && (empty($appUrl) || $appUrl === 'http://localhost')) {
            $vercelUrl = 'https://' . $_SERVER['VERCEL_URL'];
            config(['app.url' => $vercelUrl]);
        }
        // Ensure we have a properly formatted APP_URL for assets
        elseif (!str_starts_with($appUrl, 'http://') && !str_starts_with($appUrl, 'https://')) {
            config(['app.url' => url('/')]);
        }
        
        // Set correct trusted proxies for Vercel deployment
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            // Use the bit value instead of the constant to avoid dependency issues
            // This is equivalent to \Illuminate\Http\Request::HEADER_X_FORWARDED_ALL
            $trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? ['127.0.0.1', '76.76.21.0/24', '76.76.29.0/24'];
            $this->app['request']->setTrustedProxies(
                $trustedProxies,
                7  // This is equivalent to HEADER_X_FORWARDED_ALL
            );
        }
    }
}
