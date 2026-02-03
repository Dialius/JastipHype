<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\NotificationService;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderRepository;
    protected $paymentService;
    protected $notificationService;

    public function __construct(
        OrderService $orderService,
        OrderRepositoryInterface $orderRepository,
        PaymentService $paymentService,
        NotificationService $notificationService
    ) {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
        $this->paymentService = $paymentService;
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of orders with filters
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'), // order_number, customer name, email
            'status' => $request->input('status'),
            'payment_method' => $request->input('payment_method'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];

        $orders = $this->orderRepository->paginate(15, $filters);

        // Get status counts for filter badges
        $statusCounts = [
            'all' => $this->orderRepository->count(),
            'pending' => $this->orderRepository->countByStatus('pending'),
            'processing' => $this->orderRepository->countByStatus('processing'),
            'shipped' => $this->orderRepository->countByStatus('shipped'),
            'delivered' => $this->orderRepository->countByStatus('delivered'),
            'cancelled' => $this->orderRepository->countByStatus('cancelled'),
        ];

        // Apply custom pagination view
        $orders->setPath(route('admin.orders.index'));

        return view('admin.orders.index', compact('orders', 'filters', 'statusCounts'));
    }

    /**
     * Display the specified order with details
     */
    public function show($id)
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return redirect()
                ->route('admin.orders.index')
                ->with('error', 'Order not found!');
        }

        // Load relationships
        $order->load(['user', 'items.product', 'payment']);

        // Get order timeline
        $timeline = $this->getOrderTimeline($order);

        return view('admin.orders.show', compact('order', 'timeline'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
            'send_email' => 'boolean',
        ]);

        $order = $this->orderRepository->find($id);

        if (!$order) {
            return redirect()
                ->back()
                ->with('error', 'Order not found!');
        }

        $oldStatus = $order->status;
        
        // Update order status
        $this->orderService->updateOrderStatus($id, $validated['status'], $validated['notes'] ?? null);

        // Send email notification if requested
        if ($request->input('send_email', false)) {
            $this->notificationService->sendOrderStatusUpdate($order, $oldStatus, $validated['status']);
        }

        return redirect()
            ->route('admin.orders.show', $id)
            ->with('success', 'Order status updated successfully!');
    }

    /**
     * Update payment information
     */
    public function updatePayment(Request $request, $id)
    {
        $validated = $request->validate([
            'transaction_status' => 'required|in:pending,settlement,capture,paid,deny,cancel,expire,failed',
            'transaction_id' => 'nullable|string',
            'payment_code' => 'nullable|string',
            'gross_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $order = $this->orderRepository->find($id);

        if (!$order || !$order->payment) {
            return redirect()
                ->back()
                ->with('error', 'Order or payment not found!');
        }

        $oldStatus = $order->payment->transaction_status;

        // Update payment
        $order->payment->update([
            'transaction_status' => $validated['transaction_status'],
            'transaction_id' => $validated['transaction_id'] ?? $order->payment->transaction_id,
            'payment_code' => $validated['payment_code'] ?? $order->payment->payment_code,
            'gross_amount' => $validated['gross_amount'],
        ]);

        // If payment status changed to paid, update order status to processing
        if (in_array($validated['transaction_status'], ['settlement', 'capture', 'paid']) && 
            !in_array($oldStatus, ['settlement', 'capture', 'paid'])) {
            $this->orderService->updateOrderStatus($id, 'processing', 'Payment confirmed by admin');
        }

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model' => 'Payment',
            'model_id' => $order->payment->id,
            'description' => "Updated payment status from {$oldStatus} to {$validated['transaction_status']}",
            'changes' => json_encode([
                'old' => ['status' => $oldStatus],
                'new' => ['status' => $validated['transaction_status']],
                'notes' => $validated['notes'] ?? null,
            ]),
        ]);

        return redirect()
            ->route('admin.orders.show', $id)
            ->with('success', 'Payment information updated successfully!');
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'refund' => 'boolean',
        ]);

        $order = $this->orderRepository->find($id);

        if (!$order) {
            return redirect()
                ->back()
                ->with('error', 'Order not found!');
        }

        // Cancel order (will restore stock via observer)
        $this->orderService->cancelOrder($id, $validated['reason']);

        // Process refund if requested
        if ($request->input('refund', false) && $order->payment) {
            // TODO: Implement refund logic with Midtrans
            // $this->paymentService->processRefund($order->payment);
        }

        // Send cancellation email
        $this->notificationService->sendOrderCancellation($order, $validated['reason']);

        return redirect()
            ->route('admin.orders.show', $id)
            ->with('success', 'Order cancelled successfully!');
    }

    /**
     * Generate invoice PDF
     */
    public function generateInvoice($id)
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return redirect()
                ->back()
                ->with('error', 'Order not found!');
        }

        $order->load(['user', 'items.product', 'payment']);

        // TODO: Implement PDF generation using DomPDF or similar
        // For now, return view
        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Export orders to CSV/Excel
     */
    public function export(Request $request)
    {
        $filters = [
            'status' => $request->input('status'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];

        // Get all orders with filters (no pagination)
        $orders = $this->orderRepository->getWithFilters($filters, 999999)->items();

        $filename = 'orders_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Status',
                'Total',
                'Payment Method',
                'Created At',
            ]);

            // Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name ?? 'Guest',
                    $order->user->email ?? '-',
                    $order->status,
                    $order->total,
                    $order->payment_method ?? '-',
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Sync payment status from Midtrans
     */
    public function syncPaymentStatus($id)
    {
        $order = $this->orderRepository->find($id);

        if (!$order || !$order->payment) {
            return response()->json(['error' => 'Order or payment not found!'], 404);
        }

        try {
            // Sync with Midtrans
            $status = $this->paymentService->syncPaymentStatus($order->payment);

            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => 'Payment status synced successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to sync payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order timeline
     */
    private function getOrderTimeline($order)
    {
        $timeline = [];

        // Order created
        $timeline[] = [
            'status' => 'created',
            'label' => 'Order Created',
            'description' => 'Order was placed by customer',
            'timestamp' => $order->created_at,
            'icon' => 'bi-cart-check',
            'color' => 'primary',
        ];

        // Payment
        if ($order->payment) {
            $timeline[] = [
                'status' => 'payment',
                'label' => 'Payment ' . ucfirst($order->payment->status),
                'description' => 'Payment via ' . ($order->payment_method ?? 'Unknown'),
                'timestamp' => $order->payment->created_at,
                'icon' => 'bi-credit-card',
                'color' => $order->payment->status === 'paid' ? 'success' : 'warning',
            ];
        }

        // Status changes (from activity logs if available)
        // For now, we'll show current status
        if ($order->status === 'processing') {
            $timeline[] = [
                'status' => 'processing',
                'label' => 'Order Processing',
                'description' => 'Order is being prepared',
                'timestamp' => $order->updated_at,
                'icon' => 'bi-hourglass-split',
                'color' => 'info',
            ];
        }

        if ($order->status === 'shipped') {
            $timeline[] = [
                'status' => 'shipped',
                'label' => 'Order Shipped',
                'description' => 'Order has been shipped',
                'timestamp' => $order->updated_at,
                'icon' => 'bi-truck',
                'color' => 'primary',
            ];
        }

        if ($order->status === 'delivered') {
            $timeline[] = [
                'status' => 'delivered',
                'label' => 'Order Delivered',
                'description' => 'Order has been delivered to customer',
                'timestamp' => $order->updated_at,
                'icon' => 'bi-check-circle',
                'color' => 'success',
            ];
        }

        if ($order->status === 'cancelled') {
            $timeline[] = [
                'status' => 'cancelled',
                'label' => 'Order Cancelled',
                'description' => $order->cancellation_reason ?? 'Order was cancelled',
                'timestamp' => $order->updated_at,
                'icon' => 'bi-x-circle',
                'color' => 'danger',
            ];
        }

        return $timeline;
    }

    /**
     * Display invoice for printing
     */
    public function invoice($id)
    {
        $order = $this->orderRepository->findOrFail($id);
        
        return view('admin.orders.invoice', compact('order'));
    }
}
