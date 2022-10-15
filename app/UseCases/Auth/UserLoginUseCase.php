<?php

namespace App\UseCases\Auth;

use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserLoginUseCase
{
    public function __invoke(string $email, string $password): string
    {
        if (!$token = JWTAuth::attempt(['email' => $email, 'password' => $password])) {
            throw new \Exception(__('api.auth_invalid_credentials') . ' #AUTHL2', JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $token;
    }
}
