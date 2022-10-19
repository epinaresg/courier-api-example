<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryZone>
 */
class DeliveryZoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $enumDeliveryZones = config('enum.delivery_zones');
        shuffle($enumDeliveryZones);

        return [
            'name' => ucwords(fake()->word()) . ' ' . ucwords(fake()->word()) . ' Zona',
            'type' => reset($enumDeliveryZones),
			'currency' => 'PEN',
            'price_pick_up' => rand(900, 1500),
            'price_drop_off' =>  rand(900, 1500) ,
        ];
    }
}
