<?php

namespace App\Observers;

use App\Models\Voucher;

class VoucherObserver
{
    /**
     * Handle the Voucher "created" event.
     */
    public function created(Voucher $voucher): void
    {
        //
    }
    /**
     * Handle the Voucher "deleted" event.
     */
    public function deleted(Voucher $voucher): void
    {
        foreach($voucher->voucher_records as $voucher_record) {
            $voucher_record->product()->increment('total_stock', $voucher_record->quantity);
        }
        //
    }

    /**
     * Handle the Voucher "restored" event.
     */
    public function restored(Voucher $voucher): void
    {
        foreach($voucher->voucher_records as $voucher_record) {
            $voucher_record->product()->decrement('total_stock', $voucher_record->quantity);
        }
        //
    }

    /**
     * Handle the Voucher "force deleted" event.
     */
    public function forceDeleted(Voucher $voucher): void
    {
        //
    }
}
