<?php

namespace App\Services;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Models\Payment;

class PaymentService
{
    protected $paymentRepository;
    protected $midtransService;

    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        MidtransService $midtransService
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->midtransService = $midtransService;
    }

    /**
     * Sync payment status from Midtrans
     */
    public function syncPaymentStatus(Payment $payment)
    {
        try {
            // Get status from Midtrans
            $status = $this->midtransService->getTransactionStatus($payment->transaction_id);

            // Update payment status
            $payment->update([
                'status' => $this->mapMidtransStatus($status->transaction_status),
                'payment_type' => $status->payment_type ?? $payment->payment_type,
                'fraud_status' => $status->fraud_status ?? null,
            ]);

            return $payment->status;
        } catch (\Exception $e) {
            \Log::error('Failed to sync payment status: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Map Midtrans status to our payment status
     */
    private function mapMidtransStatus($midtransStatus)
    {
        $statusMap = [
            'capture' => 'paid',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'cancelled',
        ];

        return $statusMap[$midtransStatus] ?? 'pending';
    }

    /**
     * Get payment method distribution
     */
    public function getPaymentMethodDistribution()
    {
        return $this->paymentRepository->getPaymentMethodDistribution();
    }

    /**
     * Process refund (placeholder)
     */
    public function processRefund(Payment $payment)
    {
        // TODO: Implement refund logic with Midtrans
        // This is a placeholder for future implementation
        \Log::info("Refund requested for payment {$payment->id}");
        
        return true;
    }
}
