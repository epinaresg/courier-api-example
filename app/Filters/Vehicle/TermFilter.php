<?php

namespace App\Filters\Vehicle;

class TermFilter
{
    public function filter($builder, $value)
    {
        return $builder
			->where('name', 'LIKE', "%{$value}%");
    }
}
