<?php

namespace App\Interface\Auth;

interface AuthInterface
{
    public function register(array $data);

    public function findById(string $id);

    public function findByEmail(string $email);

    public function updatePassword(string $userId, string $password);
}
