<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Send order confirmation email
        // This can be queued for better performance
        // Mail::to($order->user->email)->queue(new OrderConfirmationMail($order));
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Send email notification when status changes
        if ($order->isDirty('status')) {
            // Queue email notification
            // Mail::to($order->user->email)->queue(new OrderStatusUpdateMail($order));
            
            // If order is cancelled, restore stock
            if ($order->status === 'cancelled') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
