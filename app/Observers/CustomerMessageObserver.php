<?php

namespace App\Observers;

use App\Models\CustomerMessage;

class CustomerMessageObserver
{
    /**
     * Handle the CustomerMessage "created" event.
     */
    public function created(CustomerMessage $customerMessage): void
    {
        // Send email notification to customer or admin
        if ($customerMessage->admin_id) {
            // Admin replied to customer - send email to customer
            // Mail::to($customerMessage->customer->email)->queue(new AdminReplyNotification($customerMessage));
        } else {
            // Customer sent message - send email to admin
            // Mail::to(config('mail.admin_email'))->queue(new NewCustomerMessageNotification($customerMessage));
        }
    }

    /**
     * Handle the CustomerMessage "updated" event.
     */
    public function updated(CustomerMessage $customerMessage): void
    {
        // Send notification when status changes
        if ($customerMessage->isDirty('status')) {
            // Mail::to($customerMessage->customer->email)->queue(new MessageStatusUpdateNotification($customerMessage));
        }
    }

    /**
     * Handle the CustomerMessage "deleted" event.
     */
    public function deleted(CustomerMessage $customerMessage): void
    {
        //
    }

    /**
     * Handle the CustomerMessage "restored" event.
     */
    public function restored(CustomerMessage $customerMessage): void
    {
        //
    }

    /**
     * Handle the CustomerMessage "force deleted" event.
     */
    public function forceDeleted(CustomerMessage $customerMessage): void
    {
        //
    }
}
