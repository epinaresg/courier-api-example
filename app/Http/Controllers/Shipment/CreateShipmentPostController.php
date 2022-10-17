<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Requests\Shipment\ShipmentCreateRequest;
use App\Repositories\Shipment\EloquentShipmentRespository;
use App\Repositories\Shipment\EloquentTaskRepository;
use App\UseCases\Shipment\ShipmentCalculateUseCase;
use App\UseCases\Shipment\ShipmentCreatorUseCase;
use App\UseCases\Shipment\TaskCreatorUseCase;
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
        $shipmentCreator = new ShipmentCreatorUseCase($this->eloquentShipmentRespository);
        $shipment = $shipmentCreator->__invoke($data);

        $taskCreator = new TaskCreatorUseCase($this->eloquentTaskRepository);
        foreach ($data['tasks'] as $task) {
            $taskCreator->__invoke($shipment, $task);
        }

        $shipmentCalculate = new ShipmentCalculateUseCase($this->eloquentShipmentRespository);
        $shipmentCalculate->__invoke($shipment);

        return response()->json([], JsonResponse::HTTP_CREATED);
    }
}
