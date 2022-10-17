<?php

namespace App\Http\Controllers\Utility;

use App\Http\Resources\Utility\GetCustomersResource;
use App\Repositories\Utility\EloquentUtilityRespository;
use App\UseCases\Utility\GetCustomersUseCase;
use Illuminate\Http\JsonResponse;

class CustomersGetController
{
    private $repository;
    public function __construct(EloquentUtilityRespository $eloquentUtilityRespository)
    {
        $this->repository = $eloquentUtilityRespository;
    }

    public function __invoke()
    {
        $getCustomersUseCase = new GetCustomersUseCase($this->repository);
        $customers = $getCustomersUseCase->__invoke();
        return response()->json(GetCustomersResource::collection($customers), JsonResponse::HTTP_OK);
    }
}
