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

        // $total = rand(2, 16) * 100;
        // $tax = $total * 0.05;
        // $net_tottal = $total + $tax;
        return [
            'customer_name' => fake()->name(),
            'phone_number' => fake()->phoneNumber(),
            'voucher_number' => fake()->regexify('[A-Z0-9]{8}'),
            'total' => 0,
            'tax' => 0,
            'net_total' => 0,
            'user_id' => 1

        ];
    }
}
