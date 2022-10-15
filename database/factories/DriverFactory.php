<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryZone>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'email' => fake()->safeEmail(),

            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'identification_number' => 'ID-' . fake()->randomNumber(),
            'licence_number' => 'LN-' . fake()->randomNumber(),

            'phone_code' => '+' . rand(1, 150),
            'phone_number' => fake()->phoneNumber(),

            'vehicle_brand' => fake()->jobTitle(),
            'vehicle_model' => fake()->jobTitle(),
            'vehicle_identification_number' => 'VID-' . fake()->randomNumber(),
        ];
    }
}
