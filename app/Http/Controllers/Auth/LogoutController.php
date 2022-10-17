<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class LogoutController extends Controller
{
    public function __invoke()
    {
        auth()->logout();
        return response()->json([ ], JsonResponse::HTTP_OK);
    }
}
