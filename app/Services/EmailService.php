<?php

namespace App\Services;

use App\Mail\OrderConfirmation;
use App\Mail\OrderStatusUpdate;
use App\Mail\ContactFormSubmission;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send order confirmation email
     */
    public function sendOrderConfirmation(Order $order)
    {
        try {
            Mail::to($order->user->email)
                ->send(new OrderConfirmation($order));
            
            Log::info('Order confirmation email sent', ['order_id' => $order->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send order status update email
     */
    public function sendOrderStatusUpdate(Order $order, $oldStatus, $newStatus)
    {
        try {
            Mail::to($order->user->email)
                ->send(new OrderStatusUpdate($order, $oldStatus, $newStatus));
            
            Log::info('Order status update email sent', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order status update email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send contact form email to admin
     */
    public function sendContactFormToAdmin(array $data)
    {
        try {
            Mail::to(config('mail-addresses.admin'))
                ->send(new ContactFormSubmission($data));
            
            Log::info('Contact form email sent to admin', ['email' => $data['email'] ?? 'N/A']);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send contact form email', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Test email connection
     */
    public function testEmailConnection()
    {
        try {
            Mail::raw('Test email from JastipHype', function ($message) {
                $message->to(config('mail-addresses.admin'))
                    ->subject('Test Email Connection');
            });
            
            return [
                'success' => true,
                'message' => 'Test email sent successfully!'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ];
        }
    }
}
