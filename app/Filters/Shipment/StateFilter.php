<?php

namespace App\Filters\Shipment;

class StateFilter
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('tasks', function ($query) use ($value) {
            $query->where('state_id', $value);
        });
    }
}
