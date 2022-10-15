<?php

namespace App\UseCases\User;

use App\Models\User;
use App\Repositories\User\UserRepository;

class GetUserByEmailUseCase
{
    private $repository;
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function __invoke(string $email): User
    {
        return $this->repository->byEmail($email);
    }
}
