<?php

namespace App\Repositories\Shipment;

use App\Models\Shipment;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskRepository
{
    public function byShipmentId(string $shipmentId): LengthAwarePaginator;

    public function save(Shipment $shipment, array $data): Task;

    public function update(Task $task, array $data): bool;

    public function delete(Task $task): bool;
}
