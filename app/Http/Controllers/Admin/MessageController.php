<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MessageService;
use App\Services\NotificationService;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $messageService;
    protected $notificationService;
    protected $customerRepository;

    public function __construct(
        MessageService $messageService,
        NotificationService $notificationService,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->messageService = $messageService;
        $this->notificationService = $notificationService;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get messages for a specific customer
     */
    public function getMessages($customerId)
    {
        $customer = $this->customerRepository->find($customerId);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $messages = $this->messageService->getMessageThread($customerId);

        return response()->json([
            'customer' => $customer,
            'messages' => $messages,
        ]);
    }

    /**
     * Send message to a customer
     */
    public function sendMessage(Request $request, $customerId)
    {
        $customer = $this->customerRepository->find($customerId);

        if (!$customer) {
            return redirect()
                ->back()
                ->with('error', 'Customer not found!');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'send_email' => 'boolean',
        ]);

        // Send message
        $message = $this->messageService->sendToCustomer(
            $customerId,
            auth()->id(),
            $validated['message']
        );

        // Send email notification if requested
        if ($request->input('send_email', true)) {
            $this->notificationService->sendCustomerMessage($customer, $validated['message']);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Show bulk messaging form
     */
    public function showBulkForm()
    {
        return view('admin.messages.bulk');
    }

    /**
     * Send bulk message to multiple customers
     */
    public function sendBulk(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'subject' => 'required|string|max:255',
            'segment' => 'required|in:all,active,suspended,high_spenders,recent_orders',
            'min_spending' => 'nullable|numeric|min:0',
            'max_spending' => 'nullable|numeric|min:0',
            'min_orders' => 'nullable|integer|min:0',
            'days_since_order' => 'nullable|integer|min:0',
        ]);

        // Build customer filter based on segmentation
        $filters = $this->buildSegmentFilters($validated);

        // Get customers based on filters
        $customers = $this->customerRepository->getWithFilters($filters);

        // Send messages
        $sentCount = 0;
        foreach ($customers as $customer) {
            try {
                // Create message record
                $this->messageService->sendToCustomer(
                    $customer->id,
                    auth()->id(),
                    $validated['message']
                );

                // Send email
                $this->notificationService->sendBulkMessage(
                    $customer,
                    $validated['subject'],
                    $validated['message']
                );

                $sentCount++;
            } catch (\Exception $e) {
                \Log::error('Failed to send bulk message to customer ' . $customer->id . ': ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('admin.messages.bulk')
            ->with('success', "Bulk message sent to {$sentCount} customers!");
    }

    /**
     * Get message statistics
     */
    public function statistics()
    {
        $stats = $this->messageService->getStatistics();

        return view('admin.messages.statistics', compact('stats'));
    }

    /**
     * Mark message as read
     */
    public function markAsRead($messageId)
    {
        $this->messageService->markAsRead($messageId);

        return response()->json(['success' => true]);
    }

    /**
     * Delete message
     */
    public function destroy($messageId)
    {
        $this->messageService->deleteMessage($messageId);

        return redirect()
            ->back()
            ->with('success', 'Message deleted successfully!');
    }

    /**
     * Build segment filters for bulk messaging
     */
    protected function buildSegmentFilters(array $validated)
    {
        $filters = [];

        switch ($validated['segment']) {
            case 'active':
                $filters['status'] = 'active';
                break;
            case 'suspended':
                $filters['status'] = 'suspended';
                break;
            case 'high_spenders':
                $filters['min_spending'] = $validated['min_spending'] ?? 1000000;
                break;
            case 'recent_orders':
                $filters['days_since_order'] = $validated['days_since_order'] ?? 30;
                break;
        }

        // Apply additional filters
        if (!empty($validated['min_spending'])) {
            $filters['min_spending'] = $validated['min_spending'];
        }
        if (!empty($validated['max_spending'])) {
            $filters['max_spending'] = $validated['max_spending'];
        }
        if (!empty($validated['min_orders'])) {
            $filters['min_orders'] = $validated['min_orders'];
        }

        return $filters;
    }
}
