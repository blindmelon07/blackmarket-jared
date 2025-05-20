<?php

namespace App\Observers;

use App\Models\SellerApplication;

class SellerApplicationObserver
{
    /**
     * Handle the SellerApplication "created" event.
     */
    public function created(SellerApplication $sellerApplication): void
    {
        //
    }

    /**
     * Handle the SellerApplication "updated" event.
     */
    public function updated(SellerApplication $sellerApplication): void
    {
        //
    }

    /**
     * Handle the SellerApplication "deleted" event.
     */
    public function deleted(SellerApplication $sellerApplication): void
    {
        //
    }

    /**
     * Handle the SellerApplication "restored" event.
     */
    public function restored(SellerApplication $sellerApplication): void
    {
        //
    }

    /**
     * Handle the SellerApplication "force deleted" event.
     */
    public function forceDeleted(SellerApplication $sellerApplication): void
    {
        //
    }
}
