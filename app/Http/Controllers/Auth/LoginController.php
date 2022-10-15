<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Repositories\User\EloquentUserRepository;
use App\UseCases\Auth\UserLoginUseCase;
use App\UseCases\User\GetUserByEmailUseCase;
use Illuminate\Http\JsonResponse;

final class LoginController extends Controller
{
    private $userRepository;
    public function __construct(EloquentUserRepository $eloquentUserRepository)
    {
        $this->userRepository = $eloquentUserRepository;
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $userLoginUseCase = new UserLoginUseCase();
        $authToken = $userLoginUseCase->__invoke($data['email'], $data['password']);

        $getUserByEmailUseCase = new GetUserByEmailUseCase($this->userRepository);
        $user = $getUserByEmailUseCase->__invoke($data['email']);

        $user->access_token = $authToken;

        return response()->json(new LoginResource($user), JsonResponse::HTTP_OK);
    }
}
