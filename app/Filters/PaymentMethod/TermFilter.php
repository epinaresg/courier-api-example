<?php

namespace App\Filters\PaymentMethod;

class TermFilter
{
    public function filter($builder, $value)
    {
        return $builder
			->where('name', 'LIKE', "%{$value}%");
    }
}
