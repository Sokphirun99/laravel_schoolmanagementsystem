<?php

// For debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Check for static asset request
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Define file path
$publicPath = __DIR__ . '/../public';
$filePath = $publicPath . $uri;

// Check if the request is for a static file
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    // Get file extension for basic MIME type determination
    $extension = pathinfo($uri, PATHINFO_EXTENSION);
    
    // Set content type based on extension
    switch ($extension) {
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'js':
            header('Content-Type: application/javascript');
            break;
        case 'png':
            header('Content-Type: image/png');
            break;
        case 'jpg':
        case 'jpeg':
            header('Content-Type: image/jpeg');
            break;
        case 'svg':
            header('Content-Type: image/svg+xml');
            break;
        case 'ico':
            header('Content-Type: image/x-icon');
            break;
        case 'txt':
            header('Content-Type: text/plain');
            break;
        case 'pdf':
            header('Content-Type: application/pdf');
            break;
        default:
            // Use fileinfo if available
            if (function_exists('mime_content_type')) {
                header('Content-Type: ' . mime_content_type($filePath));
            }
    }
    
    // Set cache headers for static assets
    $timestamp = filemtime($filePath);
    $etag = md5_file($filePath);
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $timestamp) . " GMT");
    header("ETag: \"$etag\"");
    header('Cache-Control: public, max-age=86400');
    
    // Output the file content and exit
    readfile($filePath);
    exit;
}

// Regular Laravel handling
require $publicPath . '/index.php';
