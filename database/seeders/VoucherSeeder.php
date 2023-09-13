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
        $voucher_count = config('seeding.voucher_count');
        Voucher::factory()->count($voucher_count)->create();
        for ($i = 1; $i <= $voucher_count; $i++) {
            VoucherRecord::factory()->count(rand(1, config('seeding.voucher_record.count')))->create([
                'voucher_id' => $i
            ]);
            
            $voucher = Voucher::find($i);
            $total_cost = $voucher->voucher_records()->sum('cost');
            $voucher->net_total = $total_cost;
            $voucher->save();
        }
    }
}
