<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actual_price = rand(1, 100) * 50;
        $sale_price = $actual_price + rand(1, 5) * 50;

        return [
            'name' => fake()->word(),
            'brand_id' => rand(1, 15),
            'category_id' => rand(1, 5),
            'actual_price' => $actual_price,
            'sale_price' => $sale_price,
            'unit' => fake()->randomElement(['single', 'dozen']),
            'more_information' => fake()->sentence(rand(1, 13)),
        ];
    }
}
