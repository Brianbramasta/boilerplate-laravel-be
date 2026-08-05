<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private const TEST_NAME = 'test user';
    private const TEST_EMAIL = 'testuser@example.com';

    public function test_user_can_register(): void
    {
        $payload = [
            'name' => self::TEST_NAME,
            'email' => self::TEST_EMAIL,
            'password' => '12345678',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(201);

        $user = User::where('email', self::TEST_EMAIL)->first();

        $this->assertNotNull($user);
    }

    public function test_user_cannot_register_with_duplicate_email(): void
    {
        User::factory()->create([
            'email' => self::TEST_EMAIL,
        ]);

        $payload = [
            'name' => self::TEST_NAME,
            'email' => self::TEST_EMAIL,
            'password' => '12345678',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'meta' => [
                    'status' => 422,
                    'message' => 'Failed Validation',
                ],
                'data' => null,
                'errors' => [
                    'email' => [
                        'The email has already been taken.',
                    ],
                ],
            ]);
    }

    public function test_name_email_and_password_are_required(): void
    {
        $response = $this->postJson('/api/auth/register', []);

        $response->assertStatus(422)
            ->assertJson([
                'meta' => [
                    'status' => 422,
                    'message' => 'Failed Validation',
                ],
                'data' => null,
                'errors' => [
                    'name' => [
                        'The name field is required.',
                    ],
                    'email' => [
                        'The email field is required.',
                    ],
                    'password' => [
                        'The password field is required.',
                    ],
                ],
            ]);
    }
}
