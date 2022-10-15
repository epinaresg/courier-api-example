<?php

namespace App\Repositories\Shipment;

use App\Models\Shipment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ShipmentRepository
{
    public function get(): LengthAwarePaginator;

    public function save(array $data): Shipment;

    public function update(Shipment $shipment, array $data): bool;

    public function delete(Shipment $shipment): bool;
}
