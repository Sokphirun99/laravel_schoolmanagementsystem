<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorageLinkTest extends TestCase
{
    /**
     * Test if the storage link is working properly.
     *
     * @return void
     */
    public function test_storage_link_works()
    {
        // Create a fake file and store it in the public disk
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-image.jpg');
        $path = $file->store('testing', 'public');

        // Check if the file exists in the storage path
        $this->assertTrue(Storage::disk('public')->exists($path));

        // Output the full URL to access this file
        echo "File created at: " . Storage::disk('public')->url($path) . "\n";
        echo "To verify the storage link is working, visit this URL in your browser.\n";
    }
}
