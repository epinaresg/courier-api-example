<?php

namespace App\Filters\Customer;

use App\Filters\AbstractFilter;
use App\Filters\Customer\TermFilter;

class CustomerFilter extends AbstractFilter
{
    protected $filters = [
        'term' => TermFilter::class
    ];
}
