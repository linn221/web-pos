<?php

namespace Database\Seeders;

use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for ($i = 0; $i < 20; $i++) {
        //     $voucher = Voucher::factory()->create();
        //     VoucherRecord::factory(rand(1, 5))->create([
        //         'voucher_id' => $voucher->id
        //     ]);
        //     $total_cost = $voucher->records()->sum('cost');
        //     $voucher->net_total = $total_cost;
        //     $voucher->save();
        // }

        $faker = Faker::create();


        foreach (range(1, 30) as $index) {
            $total = $faker->randomFloat(2, 10, 500);
            $tax = $total * 0.05;
            $net_total = $total + $tax;

            DB::table('vouchers')->insert([
                'customer_name' => $faker->name(),
                'phone_number' => $faker->phoneNumber(),
                'voucher_number' => 'V' . str_pad($index, 5, '0', STR_PAD_LEFT),
                'total' => $total,
                'tax' => $tax,
                'net_total' => $net_total,
                'user_id' => rand(1, 2),
                // 'created_at' => $faker->dateTimeBetween('-5 months', 'now'),
                'created_at' => Carbon::now(),
                // 'created_at' => Carbon::now()->subMonths(rand(1, 12)),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
