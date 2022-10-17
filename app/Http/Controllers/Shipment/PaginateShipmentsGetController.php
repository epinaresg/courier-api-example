<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Resources\Shipment\PaginateShipmentCollection;
use App\Repositories\Shipment\EloquentShipmentRespository;
use App\UseCases\Shipment\PaginateShipmentsUseCase;
use Illuminate\Http\JsonResponse;

class PaginateShipmentsGetController
{
    private $eloquentShipmentRespository;
    public function __construct(
        EloquentShipmentRespository $eloquentShipmentRespository,
    ) {
        $this->eloquentShipmentRespository = $eloquentShipmentRespository;
    }

    public function __invoke()
    {
        $listShipmentsUseCase = new PaginateShipmentsUseCase($this->eloquentShipmentRespository);
        $shipments = $listShipmentsUseCase->__invoke();

        return response()->json(new PaginateShipmentCollection($shipments), JsonResponse::HTTP_OK);
    }
}
