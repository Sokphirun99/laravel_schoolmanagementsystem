<?php

/**
 * Vercel-specific environment configurations
 */

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
}

// Return the include guard
return true;
