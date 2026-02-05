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
        // Cache notifications for 5 minutes to reduce database load
        return Cache::remember('admin_notifications_' . auth()->id(), 300, function() use ($limit) {
            $notifications = [];

            // New orders (pending/processing) - minimal data
            $newOrders = Order::whereIn('status', ['pending', 'processing'])
                ->select('id', 'order_number', 'customer_name', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            foreach ($newOrders as $order) {
                $notifications[] = [
                    'type' => 'order',
                    'icon' => 'bi-cart-check',
                    'color' => 'primary',
                    'title' => 'New Order',
                    'message' => "Order #{$order->order_number}",
                    'time' => $order->created_at,
                    'url' => route('admin.orders.show', $order->id),
                    'read' => false,
                ];
            }

            // Recent reviews (last 7 days) - minimal data
            $newReviews = Review::where('created_at', '>=', now()->subDays(7))
                ->select('id', 'product_id', 'rating', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            foreach ($newReviews as $review) {
                $notifications[] = [
                    'type' => 'review',
                    'icon' => 'bi-star',
                    'color' => 'warning',
                    'title' => 'New Review',
                    'message' => "New {$review->rating} star review",
                    'time' => $review->created_at,
                    'url' => route('admin.reviews.show', $review->id),
                    'read' => false,
                ];
            }

            // Low stock products - minimal data
            $lowStockProducts = Product::where('stock', '<=', 5)
                ->where('stock', '>', 0)
                ->select('id', 'name', 'stock', 'updated_at')
                ->orderBy('stock', 'asc')
                ->limit(3)
                ->get();

            foreach ($lowStockProducts as $product) {
                $notifications[] = [
                    'type' => 'stock',
                    'icon' => 'bi-exclamation-triangle',
                    'color' => 'danger',
                    'title' => 'Low Stock',
                    'message' => \Str::limit($product->name, 30) . " - {$product->stock} left",
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
        });
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount(): int
    {
        return Cache::remember('admin_notification_count_' . auth()->id(), 300, function() {
            $count = 0;

            // Count new orders
            $count += Order::whereIn('status', ['pending', 'processing'])->count();

            // Count recent reviews (last 7 days)
            $count += Review::where('created_at', '>=', now()->subDays(7))->count();

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
