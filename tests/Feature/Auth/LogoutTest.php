<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_success(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->postJson('/api/v1/auth/logout', [], [
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'status' => 200,
                    'message' => 'Logout berhasil',
                ],
                'data' => null,
            ]);
    }

    public function test_logout_unauthorized(): void
    {
        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertStatus(401)
            ->assertJson([
                'meta' => [
                    'status' => 401,
                    'message' => 'Unauthenticated. Token tidak valid atau tidak ditemukan.',
                ],
                'data' => null,
            ]);
    }
}