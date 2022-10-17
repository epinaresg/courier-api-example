<?php

namespace App\UseCases\Shipment;

use App\Models\Shipment;
use App\Repositories\Shipment\ShipmentRepository;

class ShipmentCalculateUseCase
{
    private $repository;
    public function __construct(ShipmentRepository $shipmentRepository)
    {
        $this->repository = $shipmentRepository;
    }

    public function __invoke(Shipment $shipment)
    {
        $data = [
            'total_receivable' => 0,
            'total_pick_up' => 0,
            'total_drop_off' => 0,
            'total' => 0
        ];

        $tasks = $shipment->tasks;
        foreach ($tasks as $task) {
            if ($task->type === 'pickup') {
                $data['total_pick_up'] += $task->deliveryZone->price_pick_up;
            } else {
                $data['total_drop_off'] += $task->deliveryZone->price_drop_off;
            }
            $data['total_receivable'] += $task->total_receivable;
        }

        $data['total'] = $data['total_receivable'] - ($data['total_pick_up'] + $data['total_drop_off']);

        return $this->repository->update($shipment, $data);
    }
}
