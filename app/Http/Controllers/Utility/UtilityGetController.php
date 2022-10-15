<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Http\Resources\Utility\GetCustomersResource;
use App\Http\Resources\Utility\GetDeliveryZonesResource;
use App\Http\Resources\Utility\GetPaymentMethodsResource;
use App\Http\Resources\Utility\GetVehiclesResource;
use App\Repositories\Utility\EloquentUtilityRespository;
use App\UseCases\Utility\GetCustomersUseCase;
use App\UseCases\Utility\GetDeliveryZonesUseCase;
use App\UseCases\Utility\GetDriversUseCase;
use App\UseCases\Utility\GetPaymentMethodsUseCase;
use App\UseCases\Utility\GetVehiclesUseCase;
use Illuminate\Http\JsonResponse;

final class UtilityGetController extends Controller
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

        $getDeliveryZonesUseCase = new GetDeliveryZonesUseCase($this->repository);
        $deliveryZones = $getDeliveryZonesUseCase->__invoke();

        $getVehiclesUseCase = new GetVehiclesUseCase($this->repository);
        $vehicles = $getVehiclesUseCase->__invoke();

        $getPaymentMethodsUseCase = new GetPaymentMethodsUseCase($this->repository);
        $paymentMethods = $getPaymentMethodsUseCase->__invoke();

        return response()->json([
            'customers' =>  GetCustomersResource::collection($customers),
            'delivery_zones' =>  GetDeliveryZonesResource::collection($deliveryZones),
            'payment_methods' =>  GetPaymentMethodsResource::collection($paymentMethods),
            'vehicles' =>  GetVehiclesResource::collection($vehicles),
        ], JsonResponse::HTTP_CREATED);
    }
}
