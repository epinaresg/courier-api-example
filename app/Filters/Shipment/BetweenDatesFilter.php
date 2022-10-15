<?php

namespace App\Filters\Shipment;

class BetweenDatesFilter
{
    public function filter($builder, $value)
    {
        $explodeDates = explode(',', $value);

        if (count($explodeDates) == 2) {
            return $builder->whereHas('tasks', function ($query) use ($explodeDates) {
                $query->whereBetween('date', [$explodeDates[0], $explodeDates[1]]);
            });
        } elseif (count($explodeDates) == 1) {
            return $builder->whereHas('tasks', function ($query) use ($explodeDates) {
                $query->where('date', $explodeDates[0]);
            });
        }

        return $builder;
    }
}
