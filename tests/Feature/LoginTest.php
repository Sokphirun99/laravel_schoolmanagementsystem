<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * Test if a user can log in.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        // First user created during seeding
        $credentials = [
            'email' => 'student1@school.test',
            'password' => 'password', // Default password from seeder
        ];

        $response = $this->post('/login', $credentials);

        // Check if redirected to dashboard or home
        $response->assertStatus(302);
        $this->assertAuthenticated();
    }
}
