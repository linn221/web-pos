<?php

namespace Database\Factories;

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
        return [
            'customer_name' => fake()->name(),
            'phone_number' => fake()->phoneNumber(),
            'voucher_number' => fake()->regexify('[A-Z0-9]{8}'),
            'total' => $total,
            'tax' => $tax,
            'net_total' => $net_total,
            'user_id' => 1,
            // 'more_information' => fake()->paragraph(rand(1, 3))
        ];
    }
}
