<?php

namespace App\Filters\Shipment;

class CustomerAccountFilter
{
    public function filter($builder, $value)
    {
        return $builder->where(function ($query) use ($value) {
            $query->where('customer_account_id', $value);
        });
    }
}
