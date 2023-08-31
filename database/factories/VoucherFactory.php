<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $total = rand(2, 16) * 150;
        $tax = $total * 0.02;
        $net_total = $total + $tax;
        $carbon = new Carbon();
        $carbon->subMonths(rand(1,3));
        $carbon->addDays(rand(1, 30));
        return [
            'customer_name' => fake()->name(),
            'phone_number' => fake()->phoneNumber(),
            'voucher_number' => fake()->regexify('[A-Z0-9]{8}'),
            'total' => $total,
            'tax' => $tax,
            'net_total' => $net_total,
            'user_id' => rand(1, 3),
            'more_information' => fake()->sentence(rand(1, 13)),
            'created_at' => $carbon->getTimestamp(),
            'updated_at' => $carbon->getTimestamp()
            // 'more_information' => fake()->paragraph(rand(1, 3))
        ];
    }
}
