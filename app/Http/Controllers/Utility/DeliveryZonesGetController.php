<?php

namespace App\Http\Controllers\Utility;

use App\Http\Resources\Utility\GetDeliveryZonesResource;
use App\Repositories\Utility\EloquentUtilityRespository;
use App\UseCases\Utility\GetDeliveryZonesUseCase;
use Illuminate\Http\JsonResponse;

class DeliveryZonesGetController
{
    private $repository;
    public function __construct(EloquentUtilityRespository $eloquentUtilityRespository)
    {
        $this->repository = $eloquentUtilityRespository;
    }

    public function __invoke()
    {
        $getDeliveryZonesUseCase = new GetDeliveryZonesUseCase($this->repository);
        $deliveryZones = $getDeliveryZonesUseCase->__invoke();

        return response()->json(GetDeliveryZonesResource::collection($deliveryZones), JsonResponse::HTTP_OK);
    }
}
