<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get orders with filters
     */
    public function getWithFilters(array $filters, $perPage = 15)
    {
        return $this->orderRepository->getWithFilters($filters, $perPage);
    }

    /**
     * Get order by ID
     */
    public function getById($id)
    {
        return $this->orderRepository->find($id);
    }

    /**
     * Update order status
     */
    public function updateStatus($id, $status, $notes = null)
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return null;
        }

        $data = ['status' => $status];
        
        if ($notes) {
            $data['notes'] = $notes;
        }

        return $this->orderRepository->update($id, $data);
    }

    /**
     * Cancel order and restore stock
     */
    public function cancel($id, $reason = null)
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return null;
        }

        // Restore stock for each item
        foreach ($order->items as $item) {
            $product = $this->productRepository->find($item->product_id);
            if ($product) {
                $this->productRepository->update($product->id, [
                    'stock' => $product->stock + $item->quantity
                ]);
            }
        }

        // Update order status
        return $this->orderRepository->update($id, [
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);
    }

    /**
     * Get recent orders
     */
    public function getRecent($limit = 10)
    {
        return $this->orderRepository->getRecent($limit);
    }

    /**
     * Get order statistics
     */
    public function getStatistics()
    {
        return $this->orderRepository->getStatistics();
    }

    /**
     * Get order timeline
     */
    public function getTimeline($id)
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return [];
        }

        $timeline = [
            [
                'status' => 'pending',
                'label' => 'Order Placed',
                'date' => $order->created_at,
                'completed' => true,
            ],
        ];

        $statuses = ['processing', 'shipped', 'delivered'];
        $currentIndex = array_search($order->status, $statuses);

        foreach ($statuses as $index => $status) {
            $timeline[] = [
                'status' => $status,
                'label' => ucfirst($status),
                'date' => $index <= $currentIndex ? $order->updated_at : null,
                'completed' => $index <= $currentIndex,
            ];
        }

        return $timeline;
    }

    /**
     * Update order status (alias for updateStatus)
     */
    public function updateOrderStatus($id, $status, $notes = null)
    {
        return $this->updateStatus($id, $status, $notes);
    }

    /**
     * Cancel order (alias for cancel)
     */
    public function cancelOrder($id, $reason = null)
    {
        return $this->cancel($id, $reason);
    }
}
