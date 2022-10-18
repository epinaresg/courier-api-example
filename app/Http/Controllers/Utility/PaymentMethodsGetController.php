<?php

namespace App\Http\Controllers\Utility;

use App\Http\Resources\Utility\GetPaymentMethodsResource;
use App\Repositories\Utility\EloquentUtilityRespository;
use App\UseCases\Utility\GetPaymentMethodsUseCase;
use Illuminate\Http\JsonResponse;

class PaymentMethodsGetController
{
    private $repository;
    public function __construct(EloquentUtilityRespository $eloquentUtilityRespository)
    {
        $this->repository = $eloquentUtilityRespository;
    }

    public function __invoke()
    {
        $getPaymentMethodsUseCase = new GetPaymentMethodsUseCase($this->repository);
        $paymentMethods = $getPaymentMethodsUseCase->__invoke();

        return response()->json(GetPaymentMethodsResource::collection($paymentMethods), JsonResponse::HTTP_OK);
    }
}
