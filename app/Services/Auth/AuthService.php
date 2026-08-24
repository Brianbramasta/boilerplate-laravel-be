<?php

namespace App\Services\Auth;

use App\Interface\Auth\AuthInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(protected AuthInterface $authinterface)
    {
        // Dependency Injection untuk repository
    }

    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            $createdData = $this->authinterface->register($data);

            if (!$createdData) {
                throw new \Exception('Failed to register user');
            }

            return $createdData;
        });
    }

    public function login(array $credential)
    {
        return DB::transaction(function () use ($credential) {
            $token = JWTAuth::attempt($credential);
            if (!$token) {
                throw new HttpException(401, 'Email atau password salah');
            }

            $user = JWTAuth::user();

            if (!$user->is_active) {
                JWTAuth::setToken($token)->invalidate();
                throw new HttpException(403, 'Akun tidak aktif, silakan hubungi admin');
            }

            [$role, $access] = $this->resolveRoleAccess($user);

            return [
                'user' => $user,
                'role' => $role,
                'access' => $access,
                'token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ];
        });
    }

    public function me(): array
    {
        $user = JWTAuth::user();

        if (!$user) {
            throw new HttpException(404, 'User tidak ditemukan');
        }

        [$role, $access] = $this->resolveRoleAccess($user);

        return [
            'user' => $user,
            'role' => $role,
            'access' => $access,
        ];
    }

    protected function resolveRoleAccess($user): array
    {
        $user->load('role.roleMenus.menu');

        $role = null;
        $access = [];

        if ($user->role) {
            $role = [
                'id' => $user->role->id,
                'name' => $user->role->name,
                'slug' => $user->role->slug,
            ];

            foreach ($user->role->roleMenus as $rm) {
                $access[$rm->menu->code] = [
                    'view' => $rm->can_view,
                    'create' => $rm->can_create,
                    'update' => $rm->can_update,
                    'delete' => $rm->can_delete,
                ];
            }
        }

        return [$role, $access];
    }

    public function refresh(): array
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
        } catch (JWTException $e) {
            throw new HttpException(401, 'Token tidak valid atau sudah tidak bisa di-refresh');
        }

        return [
            'token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    public function changePassword(array $data): void
    {
        $user = JWTAuth::user();

        if (!$user) {
            throw new HttpException(404, 'User tidak ditemukan');
        }

        if (!Hash::check($data['current_password'], $user->password)) {
            throw new HttpException(400, 'Password lama salah');
        }

        $this->authinterface->updatePassword($user->id, Hash::make($data['password']));
    }
}
