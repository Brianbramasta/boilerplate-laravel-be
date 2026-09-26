<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class MeTest extends TestCase
{
    use RefreshDatabase;

    public function test_me_success(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'employee@dasaria.com',
            'role' => 'employee',
            'manager_id' => 5,
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/v1/auth/me', [
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'meta' => ['status', 'message'],
                'data' => ['id', 'name', 'email', 'role', 'manager_id', 'created_at'],
            ])
            ->assertJson([
                'meta' => [
                    'status' => 200,
                    'message' => 'Profil berhasil diambil',
                ],
                'data' => [
                    'name' => 'John Doe',
                    'email' => 'employee@dasaria.com',
                    'role' => 'employee',
                    'manager_id' => 5,
                ]
            ]);
    }

    public function test_me_unauthorized(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

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