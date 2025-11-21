<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;

final class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function creating(Order $order): void
    {
        $order->order_number = 'ORD-'.mb_strtoupper(uniqid()).'-'.now()->format('Ymd');
        $order->save();
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(): void
    {
        //
    }
}
