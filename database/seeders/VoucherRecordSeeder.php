<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Element;

class VoucherRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $voucherIds = Voucher::orderBy('id', 'asc')->pluck('id');
        $productsIds = Product::orderBy('id', 'asc')->pluck('id');
        $price = Product::orderBy('id', 'asc')->pluck('sale_price');
        // $price = DB::table('products')->pluck('sale_price');

        foreach ($voucherIds as $voucherId) {
            $quantity = $faker->numberBetween(1, 20);

            DB::table('voucher_records')->insert([
                'voucher_id' => $voucherId,
                'product_id' => $faker->randomElement($productsIds),
                'quantity' => $quantity,
                'cost' => $quantity * $faker->randomElement($price),
                'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                // 'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            // foreach (range(1, 5) as $index) {

            // }
        }
    }
}
