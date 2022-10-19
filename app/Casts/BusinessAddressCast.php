<?php

namespace App\Casts;

use App\Models\VOAddress;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class BusinessAddressCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return new VOAddress(
			$attributes['business_address_line_one'],
			$attributes['business_address_line_two'],
			$attributes['business_address_line_three']
		);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return [
			'business_address_line_one' => $value->lineOne,
			'business_address_line_two' => $value->lineTwo,
			'business_address_line_three' => $value->lineThree,
		];
    }
}
