<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        try {
            $token = JWTAuth::attempt($data);

            if (!$token) {
                return $this->unauthorizedResponse('Email atau password salah');
            }

            $user = JWTAuth::user();

            $role = $user->role ?? 'employee';

            return $this->successResponse(
                data: [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $role,
                    ],
                ],
                message: 'Login berhasil'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function me()
    {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                return $this->unauthorizedResponse('Unauthenticated. Token tidak valid atau tidak ditemukan.');
            }

            $role = $user->role ?? 'employee';

            return $this->successResponse(
                data: [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $role,
                    'manager_id' => $user->manager_id,
                    'created_at' => $user->created_at,
                ],
                message: 'Profil berhasil diambil'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();

            return $this->successResponse(
                data: null,
                message: 'Logout berhasil'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }
}