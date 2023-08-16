<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\VoucherRecord;

class VoucherRecordObserver
{
    /**
     * Handle the VoucherRecord "created" event.
     */
    public function created(VoucherRecord $voucherRecord): void
    {
        $voucherRecord->product()->decrement('total_stock', $voucherRecord->quantity);
        //
    }

    /**
     * Handle the VoucherRecord "updated" event.
     */
    public function updating(VoucherRecord $voucherRecord): void
    {
        // $voucherRecord->product->decrement($voucherRecord->quantity);
        //
    }
    public function updated(VoucherRecord $voucherRecord): void
    {
        // $voucherRecord->product->increment($voucherRecord->quantity);
        //
    }

    /**
     * Handle the VoucherRecord "deleted" event.
     */
    public function deleting(VoucherRecord $voucherRecord): void
    {
        $voucherRecord->product()->increment('total_stock', $voucherRecord->quantity);
        //
    }
}
