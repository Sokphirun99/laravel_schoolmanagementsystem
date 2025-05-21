<?php

// Simple test script to verify the isAdmin() method is working
$startTime = microtime(true);

// Suppress deprecation warnings
require_once __DIR__.'/bootstrap/suppress_deprecations.php';

// Load Laravel environment
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find an admin user
$adminUser = \App\Models\User::whereHas('roles', function($query) {
    $query->where('name', 'admin');
})->first();

if (!$adminUser) {
    echo "No admin user found.\n";
    exit;
}

// Test the isAdmin method
try {
    $isAdmin = $adminUser->isAdmin();
    echo "User #{$adminUser->id} ({$adminUser->name}) is admin: " . ($isAdmin ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Show execution time
$executionTime = round(microtime(true) - $startTime, 3);
echo "Executed in {$executionTime}s\n";
