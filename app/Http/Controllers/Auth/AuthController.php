<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Docs\AuthDocs;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Auth', 'Endpoint autentikasi: registrasi, login, dan ganti password.')]
class AuthController extends BaseController
{
    public function __construct(protected AuthService $authService)
    {
        //
    }

    #[Unauthenticated]
    #[Response(AuthDocs::REGISTER_SUCCESS, status: 201, description: 'Registrasi berhasil')]
    #[Response(AuthDocs::REGISTER_VALIDATION_ERROR, status: 422, description: 'Validasi gagal — field wajib kosong')]
    #[Response(AuthDocs::REGISTER_EMAIL_TAKEN, status: 422, description: 'Validasi gagal — email sudah terdaftar')]
    #[Response(AuthDocs::REGISTER_SERVER_ERROR, status: 500, description: 'Kesalahan tak terduga di server')]
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        try {
            $succes = $this->authService->register($data);

            return $this->createdResponse(
                data: [
                    'user' => new UserResource($succes['user']),
                    'token' => $succes['token'],
                ],
                message: 'User berhasil registrasi'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[Unauthenticated]
    #[Response(AuthDocs::LOGIN_SUCCESS, status: 200, description: 'Login berhasil')]
    #[Response(AuthDocs::LOGIN_VALIDATION_ERROR, status: 422, description: 'Validasi gagal — field wajib kosong')]
    #[Response(AuthDocs::LOGIN_INVALID_CREDENTIALS, status: 401, description: 'Email atau password salah')]
    #[Response(AuthDocs::LOGIN_SERVER_ERROR, status: 500, description: 'Kesalahan tak terduga di server')]
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        try {
            $succes = $this->authService->login($data);

            return $this->successResponse(
                data: [
                    'user' => new UserResource($succes['user']),
                    'token' => $succes['token'],
                    'expires_in' => $succes['expires_in'],
                ],
                message: 'User berhasil login'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[Response(AuthDocs::ME_SUCCESS, status: 200, description: 'Berhasil mengambil data user')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function me()
    {
        try {
            $user = $this->authService->me();

            return $this->successResponse(
                data: new UserResource($user),
                message: 'Berhasil mengambil data user'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[Response(AuthDocs::REFRESH_SUCCESS, status: 200, description: 'Token berhasil di-refresh')]
    #[Response(AuthDocs::REFRESH_UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah tidak bisa di-refresh')]
    public function refresh()
    {
        try {
            $data = $this->authService->refresh();

            return $this->successResponse(
                data: $data,
                message: 'Token berhasil di-refresh'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    #[Response(AuthDocs::CHANGE_PASSWORD_SUCCESS, status: 200, description: 'Password berhasil diubah')]
    #[Response(AuthDocs::CHANGE_PASSWORD_VALIDATION_ERROR, status: 422, description: 'Validasi gagal — field kosong atau konfirmasi password tidak cocok')]
    #[Response(AuthDocs::CHANGE_PASSWORD_WRONG_CURRENT, status: 400, description: 'Password lama yang dikirim salah')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $data = $request->mappedData();
            $this->authService->changePassword($data);

            return $this->successResponse(
                message: 'Password berhasil diubah'
            );
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }


}
