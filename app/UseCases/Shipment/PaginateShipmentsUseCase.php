<?php

namespace App\UseCases\Shipment;

use App\Repositories\Shipment\ShipmentRepository;

class PaginateShipmentsUseCase
{
    private $repository;

    public function __construct(ShipmentRepository $shipmentRepository)
    {
        $this->repository = $shipmentRepository;
    }

    public function __invoke()
    {
        return $this->repository->get();
    }
}
