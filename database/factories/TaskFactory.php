<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $types = config('enum.task_types');
        shuffle($types);

        return [

            'type' => array_shift($types),
            'date' => Carbon::now()->addDays(rand(1, 25)),
            'package_content' => fake()->sentence(),
            'package_instruction' => fake()->sentence(),
            'address' => fake()->address(),
            'address_reference' => fake()->sentence(),

            'contact_full_name' => fake()->firstName() . ' ' . fake()->lastName(),
            'contact_phone_code' => '+' . rand(1, 150),
            'contact_phone_number' => fake()->phoneNumber(),

            'contact_email' => fake()->email(),

            'total_receivable' => rand(50, 150),
        ];
    }
}
