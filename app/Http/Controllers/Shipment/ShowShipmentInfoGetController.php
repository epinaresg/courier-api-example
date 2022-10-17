<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Resources\Shipment\ShowShipmentInfoResource;
use App\Models\Shipment;
use Illuminate\Http\JsonResponse;

class ShowShipmentInfoGetController
{
    public function __invoke(Shipment $shipment)
    {
        return response()->json(new ShowShipmentInfoResource($shipment), JsonResponse::HTTP_OK);
    }
}
