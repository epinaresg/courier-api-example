<?php

namespace App\Filters\Customer;

class TermFilter
{
    public function filter($builder, $value)
    {
        return $builder
			->where('first_name', 'LIKE', "%{$value}%")
			->orWhere('last_name', 'LIKE', "%{$value}%")
			->orWhere('email', 'LIKE', "%{$value}%");
    }
}
