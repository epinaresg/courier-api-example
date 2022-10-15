<?php

namespace App\UseCases\Shipment;

use App\Models\Shipment;
use App\Repositories\Shipment\TaskRepository;

class TaskCreator
{
    private $repository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->repository = $taskRepository;
    }

    public function __invoke(Shipment $shipment, array $data)
    {
        return $this->repository->save($shipment, $data);
    }
}
