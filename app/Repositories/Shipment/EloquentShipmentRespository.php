<?php

namespace App\Repositories\Shipment;

use App\Models\Shipment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentShipmentRespository implements ShipmentRepository
{
    public function get(): LengthAwarePaginator
    {
        return Shipment::paginate(2);
    }

    public function save(array $data): Shipment
    {
        return Shipment::create($data);
    }

    public function update(Shipment $shipment, array $data): bool
    {
        return $shipment->update($data);
    }

    public function delete(Shipment $shipment): bool
    {
        return $shipment->delete();
    }
}
