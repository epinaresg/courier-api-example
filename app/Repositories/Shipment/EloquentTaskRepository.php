<?php

namespace App\Repositories\Shipment;

use App\Models\Shipment;
use App\Models\State;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentTaskRepository implements TaskRepository
{
    public function byShipmentId(string $shipmentId): LengthAwarePaginator
    {
        return Task::where('shipment_id', $shipmentId)->get();
    }

    public function save(Shipment $shipment, array $data): Task
    {
        $data['shipment_id'] = $shipment->id;
        $data['state_id'] = State::where('name', 'Por asignar')->first()->id;

        return $shipment->tasks()->create($data);
    }

    public function update(Task $task, array $data): bool
    {
        return $task->update($data);
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
