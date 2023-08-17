<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(10),
            'company' => fake()->company(),
            'agent' => fake()->company(),
            'description' => fake()->sentence(rand(1, 13)),
            'phone_no' => fake()->phoneNumber(),
            //
        ];
    }
}
