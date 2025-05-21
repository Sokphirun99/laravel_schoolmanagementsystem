<?php

/**
 * Custom helpers for Vercel deployment
 */

if (!function_exists('custom_storage_path')) {
    /**
     * Get the path to the storage folder.
     * This helper function overwrites the default storage_path function for Vercel
     *
     * @param  string  $path
     * @return string
     */
    function custom_storage_path($path = '')
    {
        // Check if STORAGE_PATH is defined in the environment (for Vercel)
        if (isset($_ENV['STORAGE_PATH'])) {
            return $_ENV['STORAGE_PATH'].($path ? DIRECTORY_SEPARATOR.$path : $path);
        }
        
        // Default Laravel behavior
        return app('path.storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
