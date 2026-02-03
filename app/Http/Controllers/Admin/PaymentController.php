<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Services\MidtransService;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected MidtransService $midtransService,
        protected PaymentRepositoryInterface $paymentRepository
    ) {}

    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'payment_type' => $request->get('payment_type'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'search' => $request->get('search'),
        ];

        $payments = $this->paymentRepository->getWithFilters($filters, 15);
        $statistics = $this->paymentRepository->getStatistics();

        return view('admin.payments.index', compact('payments', 'statistics', 'filters'));
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        $payment = $this->paymentRepository->findById($id);
        
        if (!$payment) {
            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'Payment not found.');
        }

        // Load order relationship
        $payment->load('order.user', 'order.items.product');

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Sync payment status from Midtrans.
     */
    public function syncStatus($id)
    {
        $payment = $this->paymentRepository->findById($id);
        
        if (!$payment) {
            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'Payment not found.');
        }

        try {
            // Get status from Midtrans
            $response = $this->midtransService->getTransactionStatus($payment->transaction_id);
            
            if ($response['success']) {
                $status = $response['data'];
                
                // Update payment
                $payment->update([
                    'transaction_status' => $status->transaction_status,
                    'payment_type' => $status->payment_type ?? $payment->payment_type,
                    'fraud_status' => $status->fraud_status ?? null,
                    'settlement_time' => isset($status->settlement_time) ? 
                        \Carbon\Carbon::parse($status->settlement_time) : null,
                ]);
                
                return redirect()
                    ->route('admin.payments.show', $id)
                    ->with('success', 'Payment status synced successfully from Midtrans.');
            } else {
                return redirect()
                    ->route('admin.payments.show', $id)
                    ->with('error', 'Failed to sync status: ' . $response['message']);
            }
        } catch (\Exception $e) {
            Log::error('Payment sync failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.payments.show', $id)
                ->with('error', 'Failed to sync payment status: ' . $e->getMessage());
        }
    }

    /**
     * Manual verification of payment.
     */
    public function manualVerify(Request $request, $id)
    {
        $payment = $this->paymentRepository->findById($id);
        
        if (!$payment) {
            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'Payment not found.');
        }

        $validated = $request->validate([
            'status' => 'required|in:settlement,paid,cancel,expire',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Update payment status
            $payment->update([
                'transaction_status' => $validated['status'],
            ]);
            
            // Log manual verification
            Log::info("Manual payment verification by admin", [
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'new_status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'admin_id' => auth()->id(),
            ]);
            
            return redirect()
                ->route('admin.payments.show', $id)
                ->with('success', 'Payment status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Manual verification failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.payments.show', $id)
                ->with('error', 'Failed to update payment status.');
        }
    }

    /**
     * Display payment analytics.
     */
    public function analytics()
    {
        $methodDistribution = $this->paymentRepository->getPaymentMethodDistribution();
        $statusDistribution = $this->paymentRepository->getStatusDistribution();
        $revenueByMethod = $this->paymentRepository->getRevenueByPaymentMethod();
        $statistics = $this->paymentRepository->getStatistics();

        return view('admin.payments.analytics', compact(
            'methodDistribution',
            'statusDistribution',
            'revenueByMethod',
            'statistics'
        ));
    }
}
