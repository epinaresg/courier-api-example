<?php

namespace App\Filters\Driver;

use App\Filters\AbstractFilter;
use App\Filters\Driver\TermFilter;

class DriverFilter extends AbstractFilter
{
    protected $filters = [
        'term' => TermFilter::class
    ];
}
