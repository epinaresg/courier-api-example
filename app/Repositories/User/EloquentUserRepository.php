<?php

namespace App\Repositories\User;

use App\Models\User;

final class EloquentUserRepository implements UserRepository
{
    public function byEmail(string $email): User
    {
        return User::email($email)->first();
    }
}
