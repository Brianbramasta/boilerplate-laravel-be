<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private const TEST_EMAIL = 'testuser@example.com';

    public function test_user_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email' => self::TEST_EMAIL,
            'password' => bcrypt('12345678'),
        ]);

        $payload = [
            'email' => self::TEST_EMAIL,
            'password' => '12345678',
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'token',
                'expires_in',
            ],
        ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $payload = [
            'email' => 'invalid@gmail.com',
            'password' => 'invalidpassword',
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(401);

        $response->assertJsonStructure([
            'meta' => [
                'status',
                'message',
            ],
            'data',
            'errors',
        ]);
    }
}
