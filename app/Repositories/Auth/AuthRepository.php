<?php

namespace App\Repositories\Auth;

use App\Interface\Auth\AuthInterface;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRepository implements AuthInterface
{
    public function __construct(private User $userModel)
    {
        //
    }

    public function register(array $data)
    {
        $userCreated = $this->userModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = JWTAuth::fromUser($userCreated);

        if (!$token) {
            throw new \Exception('Failed to create token');
        }

        return [
            'user' => $userCreated,
            'token' => $token,
        ];
    }

    public function findById(string $id)
    {
        return $this->userModel::find($id);
    }

    public function findByEmail(string $email)
    {
        return $this->userModel::where('email', $email)->first();
    }

    public function updatePassword(string $userId, string $password)
    {
        return $this->userModel
            ->where('id', $userId)
            ->update(['password' => $password]);
    }
}
