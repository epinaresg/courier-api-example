<?php

namespace App\UseCases\Auth;

class RefreshTokenUseCase
{
    public function __invoke(): string
    {
        return auth()->refresh();
    }
}
