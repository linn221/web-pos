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
        $actual_price = rand(2, 16) * 50;
        $sale_price = $actual_price + rand(1, 3) * 50;
        $units = ['sigle', 'dozen'];
        return [
            'name' => fake()->name(),
            'actual_price' => $actual_price,
            'sale_price' => $sale_price,
            'total_stock' => rand(10, 50),
            'unit' => array_rand($units),
            'more_information' => fake()->sentence(rand(1, 13)),
            'brand_id' => rand(1, 15),
            'user_id' => 1
        ];
    }
}
