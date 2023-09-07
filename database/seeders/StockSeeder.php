<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // $product_stock = [];
        for ($i = 1; $i <= config('seeding.stock_count'); $i++) {
            $currentQuantity = rand(1, 100);
            $product_id = rand(1, config('seeding.product_count'));

            Stock::create([
                "user_id" => 1,
                "product_id" => $product_id,
                "quantity" => $currentQuantity,
                "more_information" => fake()->text($maxNbChars = 50),
            ]);
            // $product_stock[] = [
            //     "id" => $i,
            //     "total_stock" => $currentQuantity
            // ];
        }


        // Stock::insert($stocks);
    }
}
