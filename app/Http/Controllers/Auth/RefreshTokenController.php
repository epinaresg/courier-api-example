<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class RefreshTokenController extends Controller
{
    public function __invoke()
    {
        $authToken = auth()->refresh();
        return response()->json([
            'access_token' => $authToken,
            'token_type' => 'bearer',
            'expires_in' => time() + (config('jwt.ttl') * 60)
        ], JsonResponse::HTTP_OK);
    }
}
