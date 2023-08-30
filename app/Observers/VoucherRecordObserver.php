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
}
