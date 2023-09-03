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

        $stocks = [];
        // $product_stock = [];
        for ($i = 1; $i <= 30; $i++) {
            $currentQuantity = rand(100, 1000);
            $stocks[] = [
                "user_id" => 1,
                "product_id" => $i,
                "quantity" => $currentQuantity,
                "more_information" => fake()->text($maxNbChars = 50),
                "created_at" => now(),
                "updated_at" => now(),
            ];
            // $product_stock[] = [
            //     "id" => $i,
            //     "total_stock" => $currentQuantity
            // ];



            $currentProduct = Product::find($i);
            $currentProduct->total_stock += $currentQuantity;
            $currentProduct->update();
        }



        Stock::insert($stocks);

        // $stocks = Stock::all();
        // foreach ($stocks as $stock) {
        //     $product = Product::find($stock->product->id);

        //     if ($product) {
        //         $product->total_stock += $stock->quantity;
        //         $product->save();
        //     }
        // }


        // Stock::factory(60)->create();
        //
    }
}
