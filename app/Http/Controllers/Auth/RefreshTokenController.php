<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\Auth\RefreshTokenUseCase;
use Illuminate\Http\JsonResponse;

final class RefreshTokenController extends Controller
{
    public function __invoke()
    {
		$refreshTokenUseCase = new RefreshTokenUseCase();
        $authToken = $refreshTokenUseCase->__invoke();

        return response()->json([
            'access_token' => $authToken,
            'token_type' => 'bearer',
            'expires_in' => time() + (config('jwt.ttl') * 60)
        ], JsonResponse::HTTP_OK);
    }
}
