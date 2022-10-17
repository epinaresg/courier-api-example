<?php

namespace App\Http\Resources\Shipment;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowShipmentInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,

            "customer" => [
                'id' => $this->customer_id,
                'first_name' => $this->customer->first_name,
                'last_name' => $this->customer->last_name,
            ],

            "vehicle" => [
                'id' => $this->vehicle_id,
                'name' => $this->vehicle->name,
            ],

            "tasks_qty" => $this->tasks_qty,
            "tasks_missing_qty" => $this->tasks_missing_qty,
            "tasks_completed_qty" => $this->tasks_completed_qty,
            "tasks_completed_percent" => $this->tasks_completed_percent,

            "total_pick_up" => $this->total_pick_up,
            "total_drop_off" => $this->total_drop_off,
            "total_receivable" => $this->total_receivable,
            "total" => $this->total_pick_up,

            "tasks" => ShipmentTasksResource::collection($this->tasks->sortBy('order'))
        ];
    }
}
