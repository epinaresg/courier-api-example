<?php

namespace App\Filters\Driver;

class TermFilter
{
    public function filter($builder, $value)
    {
        return $builder
			->where('first_name', 'LIKE', "%{$value}%")
			->orWhere('last_name', 'LIKE', "%{$value}%")
			->orWhere('email', 'LIKE', "%{$value}%")
			->orWhere('phone_number', 'LIKE', "%{$value}%")
			->orWhere('identification_number', 'LIKE', "%{$value}%");
    }
}
