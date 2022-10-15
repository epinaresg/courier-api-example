<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),

            'business_name' => fake()->company(),
            'business_number' => fake()->ean13(),
            'business_address' => fake()->address(),

            'business_phone_code' => '+' . rand(1, 150),
            'business_phone_number' => fake()->phoneNumber(),
            'business_email' => fake()->safeEmail(),

            'contact_first_name' => fake()->firstName(),
            'contact_last_name' => fake()->lastName(),
            'contact_phone_code' => '+' . rand(1, 150),
            'contact_phone_number' => fake()->phoneNumber(),
            'contact_email' => fake()->safeEmail(),
        ];
    }
}
