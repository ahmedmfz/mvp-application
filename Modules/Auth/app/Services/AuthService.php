<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\Models\User;


class AuthService
{
    public function login(array $data): ?User
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return null;
        }

        return $user;
    }
}
