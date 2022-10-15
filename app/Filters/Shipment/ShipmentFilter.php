<?php

namespace App\Filters\Shipment;

use App\Filters\AbstractFilter;

class ShipmentFilter extends AbstractFilter
{
    protected $filters = [
        'dates' => BetweenDatesFilter::class,
        'state_id' => StateFilter::class,
        'customer_account_id' => CustomerAccountFilter::class
    ];
}
