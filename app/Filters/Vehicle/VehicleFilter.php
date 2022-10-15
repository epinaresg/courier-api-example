<?php

namespace App\Filters\Vehicle;

use App\Filters\AbstractFilter;
use App\Filters\Vehicle\TermFilter;

class VehicleFilter extends AbstractFilter
{
    protected $filters = [
        'term' => TermFilter::class
    ];
}
