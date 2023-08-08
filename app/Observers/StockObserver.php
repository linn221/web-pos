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
     * @fix cannot access the pre-update value yet
     */
    public function saving(Stock $stock) : void
    {
        logger($stock->quantity);
        // $stock->product()->decrement('total_stock', $stock->quantity);
    }

    public function updating(Stock $stock) : void
    {
        logger($stock->quantity);
        // $stock->product()->decrement('total_stock', $stock->quantity);
    }

    public function updated(Stock $stock): void
    {
        logger($stock->quantity);
        // $stock->product()->increment('total_stock', $stock->quantity);
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
