<?php

/**
 * Vercel-specific environment configurations
 */

// Set the storage path for Vercel
$_ENV['STORAGE_PATH'] = $_ENV['STORAGE_PATH'] ?? '/tmp/storage';

// Create storage directories if they don't exist
if (!is_dir($_ENV['STORAGE_PATH'])) {
    mkdir($_ENV['STORAGE_PATH'], 0755, true);
    mkdir($_ENV['STORAGE_PATH'] . '/app', 0755, true);
    mkdir($_ENV['STORAGE_PATH'] . '/app/public', 0755, true);
    mkdir($_ENV['STORAGE_PATH'] . '/framework', 0755, true);
    mkdir($_ENV['STORAGE_PATH'] . '/framework/cache', 0755, true);
    mkdir($_ENV['STORAGE_PATH'] . '/framework/sessions', 0755, true);
    mkdir($_ENV['STORAGE_PATH'] . '/framework/views', 0755, true);
    mkdir($_ENV['STORAGE_PATH'] . '/logs', 0755, true);
}

// Support HTTPS behind a proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

// Fix for trusted proxies with Vercel
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $trustedProxies = ['127.0.0.1', 'localhost'];
    
    // Add Vercel's proxy IPs
    $trustedProxies = array_merge($trustedProxies, [
        '76.76.21.0/24',
        '76.76.29.0/24'
    ]);
    
    // We set this globally so it can be accessed in AppServiceProvider
    $_SERVER['TRUSTED_PROXIES'] = $trustedProxies;
}

// Set APP_URL from Vercel URL if available
if (isset($_SERVER['VERCEL_URL']) && empty($_ENV['APP_URL'])) {
    $_ENV['APP_URL'] = 'https://' . $_SERVER['VERCEL_URL'];
}

// Return the include guard
return true;
