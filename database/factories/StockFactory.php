<?php

namespace Database\Factories;

use App\Models\Product;
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
        $product_id = rand(1,30);
        $quantity = rand(1,50);
        $product = Product::find($product_id);
        $product->total_stock += $quantity;
        $product->save();


        return [
            'user_id' => 1,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'more_information' => fake()->sentence(rand(1, 13))
        ];
    }
}
