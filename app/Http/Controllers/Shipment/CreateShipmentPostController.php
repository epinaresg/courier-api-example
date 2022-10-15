<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Requests\Shipment\ShipmentCreateRequest;
use App\Repositories\Shipment\EloquentShipmentRespository;
use App\Repositories\Shipment\EloquentTaskRepository;
use App\UseCases\Shipment\ShipmentCreator;
use App\UseCases\Shipment\TaskCreator;
use Illuminate\Http\JsonResponse;

class CreateShipmentPostController
{
    private $eloquentShipmentRespository;
    private $eloquentTaskRepository;
    public function __construct(
        EloquentShipmentRespository $eloquentShipmentRespository,
        EloquentTaskRepository $eloquentTaskRepository,
    ) {
        $this->eloquentShipmentRespository = $eloquentShipmentRespository;
        $this->eloquentTaskRepository = $eloquentTaskRepository;
    }

    public function __invoke(ShipmentCreateRequest $request)
    {
        $data = $request->validated();
        $shipmentCreator = new ShipmentCreator($this->eloquentShipmentRespository);
        $shipment = $shipmentCreator->__invoke($data);

        $taskCreator = new TaskCreator($this->eloquentTaskRepository);
        foreach ($data['tasks'] as $task) {
            $taskCreator->__invoke($shipment, $task);
        }

        return response()->json([], JsonResponse::HTTP_CREATED);
    }
}
