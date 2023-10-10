<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\VoucherRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VoucherRecord>
 */
class VoucherRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::find(rand(1, config('seeding.product_count')));
        $qty = rand(1, config('seeding.voucher_record.quantity'));
        $cost = $product->sale_price * $qty;

        // // proud of finding case to use this
        // // keep finding another product if the current one have less than 10 stocks
        // do {
        //     $product = Product::find(rand(1, config('seeding.product_count')));
        // } while($product->total_stock < config('seeding.voucher_record.min_stock'));

        // do {
        //     $qty = rand(1, config('seeding.voucher_record.quantity'));
        // } while($product->total_stock < $qty);

        return [
            // 'voucher_id' => ,
            'product_id' => $product->id,
            'quantity' => $qty,
            'cost' => $cost
        ];
    }
}
