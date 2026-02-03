<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Review;
use App\Models\CustomerMessage;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class AdminNotificationService
{
    /**
     * Get all admin notifications
     */
    public function getNotifications(int $limit = 10): array
    {
        $notifications = [];

        // New orders (pending/processing)
        $newOrders = Order::whereIn('status', ['pending', 'processing'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($newOrders as $order) {
            $notifications[] = [
                'type' => 'order',
                'icon' => 'bi-cart-check',
                'color' => 'primary',
                'title' => 'New Order',
                'message' => "Order #{$order->order_number} from {$order->customer_name}",
                'time' => $order->created_at,
                'url' => route('admin.orders.show', $order->id),
                'read' => false,
            ];
        }

        // Recent reviews (last 7 days)
        $newReviews = Review::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($newReviews as $review) {
            $notifications[] = [
                'type' => 'review',
                'icon' => 'bi-star',
                'color' => 'warning',
                'title' => 'New Review',
                'message' => "New review for {$review->product->name} - {$review->rating} stars",
                'time' => $review->created_at,
                'url' => route('admin.reviews.show', $review->id),
                'read' => false,
            ];
        }

        // New customer messages (open status)
        $newMessages = CustomerMessage::where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($newMessages as $message) {
            $notifications[] = [
                'type' => 'message',
                'icon' => 'bi-envelope',
                'color' => 'info',
                'title' => 'New Message',
                'message' => "Message from {$message->customer->name}: " . \Str::limit($message->message, 50),
                'time' => $message->created_at,
                'url' => route('admin.messages.show', $message->id),
                'read' => false,
            ];
        }

        // Low stock products
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        foreach ($lowStockProducts as $product) {
            $notifications[] = [
                'type' => 'stock',
                'icon' => 'bi-exclamation-triangle',
                'color' => 'danger',
                'title' => 'Low Stock Alert',
                'message' => "{$product->name} - Only {$product->stock} left",
                'time' => $product->updated_at,
                'url' => route('admin.products.edit', $product->id),
                'read' => false,
            ];
        }

        // Sort by time (newest first)
        usort($notifications, function($a, $b) {
            return $b['time'] <=> $a['time'];
        });

        // Limit to requested number
        return array_slice($notifications, 0, $limit);
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount(): int
    {
        return Cache::remember('admin_notification_count', 60, function() {
            $count = 0;

            // Count new orders
            $count += Order::whereIn('status', ['pending', 'processing'])->count();

            // Count recent reviews (last 7 days)
            $count += Review::where('created_at', '>=', now()->subDays(7))->count();

            // Count open messages
            $count += CustomerMessage::where('status', 'open')->count();

            // Count low stock products
            $count += Product::where('stock', '<=', 5)
                ->where('stock', '>', 0)
                ->count();

            return $count;
        });
    }

    /**
     * Get notification summary
     */
    public function getSummary(): array
    {
        return [
            'new_orders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'recent_reviews' => Review::where('created_at', '>=', now()->subDays(7))->count(),
            'open_messages' => CustomerMessage::where('status', 'open')->count(),
            'low_stock' => Product::where('stock', '<=', 5)
                ->where('stock', '>', 0)
                ->count(),
        ];
    }

    /**
     * Clear notification cache
     */
    public function clearCache(): void
    {
        Cache::forget('admin_notification_count');
    }
}
