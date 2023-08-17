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
        $actual_price = fake()->numberBetween($min = 100, $max = 1000);
        $sale_price = $actual_price + rand(1, 3) * 5;
        // $units = ['sigle', 'dozen'];
        return [
            'name' => fake()->word(),
            'brand_id' => rand(1, 15),
            'actual_price' => $actual_price,
            'sale_price' => $sale_price,
            // 'total_stock' => rand(10, 50),
            'unit' => fake()->randomElement(['single', 'dozen']),
            'more_information' => fake()->sentence(rand(1, 13)),
            'photo' => fake()->imageUrl($width = 640, $height = 480),
        ];
    }
}
