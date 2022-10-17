<?php

namespace App\UseCases\Shipment;

use App\Repositories\Shipment\ShipmentRepository;

class ShipmentCreatorUseCase
{
    private $repository;

    public function __construct(ShipmentRepository $shipmentRepository)
    {
        $this->repository = $shipmentRepository;
    }

    public function __invoke(array $data)
    {
        $data['tasks_qty'] = count($data['tasks']);
        $data['tasks_missing_qty'] = $data['tasks_qty'];

        return $this->repository->save($data);
    }
}
