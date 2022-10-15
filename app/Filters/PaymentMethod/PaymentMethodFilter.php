<?php

namespace App\Filters\PaymentMethod;

use App\Filters\AbstractFilter;
use App\Filters\Vehicle\TermFilter;

class PaymentMethodFilter extends AbstractFilter
{
    protected $filters = [
        'term' => TermFilter::class
    ];
}
