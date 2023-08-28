<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Stock;

class StockObserver
{
    /**
     * Handle the Stock "created" event.
     */
    public function created(Stock $stock): void
    {
        $stock->product()->increment('total_stock', $stock->quantity);
        //
    }

    /**
     * Handle the Stock "deleted" event.
     */
    public function deleting(Stock $stock): void
    {
        $stock->product()->decrement('total_stock', $stock->quantity);
        //
    }
}
