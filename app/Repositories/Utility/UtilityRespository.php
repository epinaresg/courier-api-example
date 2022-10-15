<?php

namespace App\Repositories\Utility;

use Illuminate\Database\Eloquent\Collection;

interface UtilityRespository
{
    public function getCustomers(): Collection;

    public function getDrivers(): Collection;

    public function getPaymentMethods(): Collection;

    public function getVehicles(): Collection;

    public function getDeliveryZones(): Collection;
}
