<?php

namespace App\Repositories\Utility;

use App\Models\Customer;
use App\Models\DeliveryZone;
use App\Models\Driver;
use App\Models\PaymentMethod;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

class EloquentUtilityRespository implements UtilityRespository
{
    public function getCustomers(): Collection
    {
        return Customer::get();
    }

    public function getDrivers(): Collection
    {
        return Driver::get();
    }

    public function getPaymentMethods(): Collection
    {
        return PaymentMethod::get();
    }

    public function getVehicles(): Collection
    {
        return Vehicle::get();
    }

    public function getDeliveryZones(): Collection
    {
        return DeliveryZone::get();
    }
}
