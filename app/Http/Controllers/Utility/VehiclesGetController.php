<?php

namespace App\Http\Controllers\Utility;

use App\Http\Resources\Utility\GetVehiclesResource;
use App\Repositories\Utility\EloquentUtilityRespository;
use App\UseCases\Utility\GetVehiclesUseCase;
use Illuminate\Http\JsonResponse;

class VehiclesGetController
{
    private $repository;
    public function __construct(EloquentUtilityRespository $eloquentUtilityRespository)
    {
        $this->repository = $eloquentUtilityRespository;
    }

    public function __invoke()
    {
        $getVehiclesUseCase = new GetVehiclesUseCase($this->repository);
        $vehicles = $getVehiclesUseCase->__invoke();

        return response()->json(GetVehiclesResource::collection($vehicles), JsonResponse::HTTP_OK);
    }
}
