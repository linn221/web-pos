<?php

namespace Database\Factories;

use Carbon\Carbon;
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
        $carbon = Carbon::now();
        $month = $carbon->subMonths(rand(1, config('seeding.month_count')));
        $carbon->addDays(rand(1, $month->endOfMonth()->format('d')));

        return [
            'user_id' => 1,
            'product_id' => rand(1, config('seeding.product_count')),
            'quantity' => rand(1, config('seeding.stock.quantity')),
            'more_information' => fake()->sentence(rand(1, 13)),
            'created_at' => $carbon->getTimestamp()
        ];
    }
}
