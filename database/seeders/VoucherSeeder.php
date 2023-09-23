<?php

namespace Database\Seeders;

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
        // voucher with 5 records
        Voucher::factory()
        ->has(
            VoucherRecord::factory()->count(config('seeding.voucher_record.count')),
            'voucher_records'
        )
        ->count(config('seeding.voucher_count') * 0.4)
        ->create();

        // voucher with 3 records
        Voucher::factory()
        ->has(
            VoucherRecord::factory()->count(config('seeding.voucher_record.count') - 2),
            'voucher_records'
        )
        ->count(config('seeding.voucher_count') * 0.3)
        ->create();

        // voucher with 4 records
        Voucher::factory()
        ->has(
            VoucherRecord::factory()->count(config('seeding.voucher_record.count') - 1),
            'voucher_records'
        )
        ->count(config('seeding.voucher_count') * 0.2)
        ->create();

        // voucher with 6 records
        Voucher::factory()
        ->has(
            VoucherRecord::factory()->count(config('seeding.voucher_record.count') - 1),
            'voucher_records'
        )
        ->count(config('seeding.voucher_count') * 0.1)
        ->create();
        // @
        // for ($i = 1; $i <= $voucher_count; $i++) {
        //     $voucher = Voucher::find($i);
        //     $total_cost = $voucher->voucher_records()->sum('cost');
        //     $voucher->net_total = $total_cost;
        //     $voucher->save();
        // }
    }
}
