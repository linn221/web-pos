<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'user_id' => 1,
            // 'product_id' => rand(1, 30),
            // 'quantity' => rand(1, 5),
            // 'more_information' => fake()->sentence(rand(1, 13))
        ];
    }
}
