<?php

namespace App\Filters\DeliveryZone;

class TermFilter
{
    public function filter($builder, $value)
    {
        return $builder
			->where('name', 'LIKE', "%{$value}%");
    }
}
