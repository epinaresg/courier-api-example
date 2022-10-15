<?php

namespace App\Repositories\User;

use App\Models\User;

final class DoctrineUserRepository implements UserRepository
{
    public function byEmail(string $email): User
    {
        return new User();
    }
}
