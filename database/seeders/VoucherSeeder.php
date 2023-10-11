<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $remaining_vouchers = config('seeding.voucher_count');

        // generate a random number of vouchers with 2 records
        $no_of_records = 2;
        $generated_voucher_count = rand(0, $remaining_vouchers);
        $remaining_vouchers -= $generated_voucher_count;
        Voucher::factory()
            ->has(VoucherRecord::factory()->count($no_of_records), 'voucher_records')
            ->count($generated_voucher_count)->create();

        // generate a random number of vouchers with 1 records, 
        $no_of_records = 1;
        $generated_voucher_count = rand(0, $remaining_vouchers);
        $remaining_vouchers -= $generated_voucher_count;
        Voucher::factory()
            ->has(VoucherRecord::factory()->count($no_of_records), 'voucher_records')
            ->count($generated_voucher_count)->create();

        $no_of_records = 4;
        $generated_voucher_count = rand(0, $remaining_vouchers);
        $remaining_vouchers -= $generated_voucher_count;
        Voucher::factory()
            ->has(VoucherRecord::factory()->count($no_of_records), 'voucher_records')
            ->count($generated_voucher_count)->create();

        // finally, finish remaining vouchers with records of 3
        $no_of_records = 3;
        Voucher::factory()
            ->has(VoucherRecord::factory()->count($no_of_records), 'voucher_records')
            ->count($remaining_vouchers)->create();


        // adding stocks for products with negative total_stock
        $products = Product::where('total_stock', '<', 1)->get();
        foreach ($products as $product) {
            // add product a random number of stocks
            do {
                Stock::factory()->create([
                    'product_id' => $product->id
                ]);
                // refresh product to see if the product stock count is still negative or not
                $product->refresh();
                // keep adding stocks, as long as the stock count is negative
            } while ($product->total_stock < 0);
        }
    }
}
