<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_success(): void
    {
        $user = User::factory()->create([
            'email' => 'employee@dasaria.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'employee@dasaria.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'meta' => ['status', 'message'],
                'data' => [
                    'token',
                    'user' => ['id', 'name', 'email', 'role']
                ]
            ])
            ->assertJson([
                'meta' => [
                    'status' => 200,
                    'message' => 'Login berhasil',
                ],
                'data' => [
                    'user' => [
                        'email' => 'employee@dasaria.com',
                    ]
                ]
            ]);
    }

    public function test_login_validation_error(): void
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'meta' => ['status', 'message'],
                'errors' => ['email', 'password'],
            ]);
    }

    public function test_login_invalid_credentials(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'meta' => [
                    'status' => 401,
                    'message' => 'Email atau password salah',
                ],
                'data' => null,
            ]);
    }
}