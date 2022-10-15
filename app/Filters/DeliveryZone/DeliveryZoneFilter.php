<?php

namespace App\Filters\DeliveryZone;

use App\Filters\AbstractFilter;
use App\Filters\DeliveryZone\TermFilter;

class DeliveryZoneFilter extends AbstractFilter
{
    protected $filters = [
        'term' => TermFilter::class
    ];
}
