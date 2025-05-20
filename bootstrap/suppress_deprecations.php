<?php

/**
 * This file suppresses PHP 8.4 deprecation warnings related to nullable parameters
 * which are common in Laravel 9.x when running on PHP 8.4+
 */

// Method 1: Completely suppress all deprecation warnings
if (PHP_VERSION_ID >= 80400) {
    error_reporting(E_ALL & ~E_DEPRECATED);
}

// Method 2: Only suppress specific deprecation messages (as a fallback)
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if ($errno === E_DEPRECATED) {
        // Match Laravel 9.x specific deprecation messages about nullable parameters
        if (strpos($errstr, 'Implicitly marking parameter') !== false &&
            strpos($errstr, 'as nullable is deprecated') !== false) {
            return true; // Suppress this warning
        }
    }

    // For all other errors, use the default error handler
    return false;
}, E_DEPRECATED);
