<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\VisitorAnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\SystemMonitorController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\ExportImportController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "admin" middleware group and "/admin" prefix.
|
*/

// Admin Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Product Management
Route::resource('products', ProductController::class)->names('admin.products');
Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('admin.products.bulk-delete');
Route::post('products/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('admin.products.toggle-status');
Route::post('products/{id}/update-stock', [ProductController::class, 'updateStock'])->name('admin.products.update-stock');

// Brand Management
Route::resource('brands', BrandController::class)->names('admin.brands');
Route::post('brands/update-order', [BrandController::class, 'updateOrder'])->name('admin.brands.update-order');
Route::post('brands/{id}/toggle-status', [BrandController::class, 'toggleStatus'])->name('admin.brands.toggle-status');

// Category Management
// Category Images (must be before resource route to avoid conflict)
Route::get('categories/images/edit', [\App\Http\Controllers\Admin\CategoryImageController::class, 'edit'])->name('admin.categories.images.edit');
Route::put('categories/images/update', [\App\Http\Controllers\Admin\CategoryImageController::class, 'update'])->name('admin.categories.images.update');
Route::resource('categories', CategoryController::class)->names('admin.categories');

// Order Management
Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
Route::get('orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
Route::post('orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
Route::put('orders/{id}/update-payment', [OrderController::class, 'updatePayment'])->name('admin.orders.update-payment');
Route::post('orders/{id}/cancel', [OrderController::class, 'cancel'])->name('admin.orders.cancel');
Route::get('orders/{id}/invoice', [OrderController::class, 'invoice'])->name('admin.orders.invoice');
Route::get('orders-export', [OrderController::class, 'export'])->name('admin.orders.export');
Route::post('orders/{id}/sync-payment-status', [OrderController::class, 'syncPaymentStatus'])->name('admin.orders.sync-payment-status');

// Customer Management
Route::get('customers', [CustomerController::class, 'index'])->name('admin.customers.index');
Route::get('customers/{id}', [CustomerController::class, 'show'])->name('admin.customers.show');
Route::get('customers/{id}/edit', [CustomerController::class, 'edit'])->name('admin.customers.edit');
Route::put('customers/{id}', [CustomerController::class, 'update'])->name('admin.customers.update');
Route::post('customers/{id}/suspend', [CustomerController::class, 'suspend'])->name('admin.customers.suspend');
Route::post('customers/{id}/activate', [CustomerController::class, 'activate'])->name('admin.customers.activate');
Route::get('customers-export', [CustomerController::class, 'export'])->name('admin.customers.export');

// Customer Messaging
Route::get('messages/{customerId}', [MessageController::class, 'getMessages'])->name('admin.messages.get');
Route::post('messages/{customerId}', [MessageController::class, 'sendMessage'])->name('admin.messages.send');
Route::get('messages/bulk/form', [MessageController::class, 'showBulkForm'])->name('admin.messages.bulk');
Route::post('messages/bulk/send', [MessageController::class, 'sendBulk'])->name('admin.messages.send-bulk');
Route::post('messages/{messageId}/read', [MessageController::class, 'markAsRead'])->name('admin.messages.mark-read');
Route::delete('messages/{messageId}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');

// Banner Management
Route::resource('banners', BannerController::class)->names('admin.banners');
Route::post('banners/update-order', [BannerController::class, 'updateOrder'])->name('admin.banners.update-order');
Route::post('banners/{id}/toggle-status', [BannerController::class, 'toggleStatus'])->name('admin.banners.toggle-status');

// Review Management
Route::get('reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');
Route::get('reviews/{id}', [ReviewController::class, 'show'])->name('admin.reviews.show');
Route::post('reviews/{id}/approve', [ReviewController::class, 'approve'])->name('admin.reviews.approve');
Route::post('reviews/{id}/reject', [ReviewController::class, 'reject'])->name('admin.reviews.reject');
Route::post('reviews/{id}/respond', [ReviewController::class, 'respond'])->name('admin.reviews.respond');
Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');

// Discount Management
Route::resource('discounts', DiscountController::class)->names('admin.discounts');
Route::post('discounts/{id}/toggle-status', [DiscountController::class, 'toggleStatus'])->name('admin.discounts.toggle-status');

// Payment Tracking
Route::get('payments', [PaymentController::class, 'index'])->name('admin.payments.index');
Route::get('payments/analytics', [PaymentController::class, 'analytics'])->name('admin.payments.analytics');
Route::get('payments/{id}', [PaymentController::class, 'show'])->name('admin.payments.show');
Route::post('payments/{id}/sync-status', [PaymentController::class, 'syncStatus'])->name('admin.payments.sync-status');
Route::post('payments/{id}/manual-verify', [PaymentController::class, 'manualVerify'])->name('admin.payments.manual-verify');

// Analytics and Reports
Route::get('analytics/revenue', [AnalyticsController::class, 'revenue'])->name('admin.analytics.revenue');
Route::get('analytics/products', [AnalyticsController::class, 'productPerformance'])->name('admin.analytics.products');
Route::get('analytics/customers', [AnalyticsController::class, 'customerAnalytics'])->name('admin.analytics.customers');
Route::post('analytics/export', [AnalyticsController::class, 'exportReport'])->name('admin.analytics.export');

// Visitor Analytics
Route::get('visitors', [VisitorAnalyticsController::class, 'index'])->name('admin.visitors.index');
Route::get('visitors/online-users', [VisitorAnalyticsController::class, 'getOnlineUsers'])->name('admin.visitors.online-users');
Route::get('visitors/trends', [VisitorAnalyticsController::class, 'getTrends'])->name('admin.visitors.trends');

// Settings Management
Route::get('settings', [SettingsController::class, 'index'])->name('admin.settings.index');
Route::post('settings/general', [SettingsController::class, 'updateGeneral'])->name('admin.settings.general');
Route::post('settings/payment', [SettingsController::class, 'updatePayment'])->name('admin.settings.payment');
Route::post('settings/shipping', [SettingsController::class, 'updateShipping'])->name('admin.settings.shipping');
Route::post('settings/email', [SettingsController::class, 'updateEmail'])->name('admin.settings.email');
Route::post('settings/notifications', [SettingsController::class, 'updateNotifications'])->name('admin.settings.notifications');
Route::post('settings/test-email', [SettingsController::class, 'testEmail'])->name('admin.settings.test-email');
Route::post('settings/test-midtrans', [SettingsController::class, 'testMidtrans'])->name('admin.settings.test-midtrans');

// Activity Logs
Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
Route::get('activity-logs/{id}', [ActivityLogController::class, 'show'])->name('admin.activity-logs.show');

// System Monitoring
Route::get('system', [SystemMonitorController::class, 'index'])->name('admin.system.index');
Route::get('system/check-services', [SystemMonitorController::class, 'checkServices'])->name('admin.system.check-services');
Route::get('system/database-stats', [SystemMonitorController::class, 'getDatabaseStats'])->name('admin.system.database-stats');
Route::get('system/error-logs', [SystemMonitorController::class, 'errorLogs'])->name('admin.system.error-logs');

// Notification Management
Route::get('notifications/templates', [NotificationController::class, 'templates'])->name('admin.notifications.templates');
Route::get('notifications/templates/{template}/edit', [NotificationController::class, 'editTemplate'])->name('admin.notifications.templates.edit');
Route::post('notifications/templates/{template}', [NotificationController::class, 'updateTemplate'])->name('admin.notifications.templates.update');
Route::post('notifications/templates/{template}/preview', [NotificationController::class, 'previewTemplate'])->name('admin.notifications.templates.preview');
Route::get('notifications/history', [NotificationController::class, 'history'])->name('admin.notifications.history');
Route::post('notifications/{id}/retry', [NotificationController::class, 'retry'])->name('admin.notifications.retry');

// Shipping Management
Route::get('shipping', [ShippingController::class, 'index'])->name('admin.shipping.index');
Route::post('shipping/couriers', [ShippingController::class, 'updateCouriers'])->name('admin.shipping.couriers.update');
Route::post('shipping/origin', [ShippingController::class, 'updateOrigin'])->name('admin.shipping.origin.update');
Route::post('shipping/free-shipping', [ShippingController::class, 'updateFreeShipping'])->name('admin.shipping.free-shipping.update');
Route::post('shipping/zones', [ShippingController::class, 'updateZones'])->name('admin.shipping.zones.update');
Route::get('shipping/analytics', [ShippingController::class, 'analytics'])->name('admin.shipping.analytics');
Route::get('shipping/cities', [ShippingController::class, 'getCitiesByProvince'])->name('admin.shipping.cities');

// Export & Import
Route::get('export-import', [ExportImportController::class, 'index'])->name('admin.export-import.index');
Route::post('export/products', [ExportImportController::class, 'exportProducts'])->name('admin.export.products');
Route::post('export/orders', [ExportImportController::class, 'exportOrders'])->name('admin.export.orders');
Route::post('export/customers', [ExportImportController::class, 'exportCustomers'])->name('admin.export.customers');
Route::post('import/products', [ExportImportController::class, 'importProducts'])->name('admin.import.products');
Route::get('import/template/{type}', [ExportImportController::class, 'downloadTemplate'])->name('admin.import.template');

// Support Ticket Management
use App\Http\Controllers\Admin\SupportController;
Route::get('support', [SupportController::class, 'index'])->name('admin.support.index');
Route::get('support/chat', [SupportController::class, 'chat'])->name('admin.support.chat');
Route::get('support/unread-count', [SupportController::class, 'getUnreadCount'])->name('admin.support.unread-count');
Route::get('support/{ticket}', [SupportController::class, 'show'])->name('admin.support.show');
Route::post('support/{ticket}/reply', [SupportController::class, 'reply'])->name('admin.support.reply');
Route::post('support/{ticket}/status', [SupportController::class, 'updateStatus'])->name('admin.support.update-status');
Route::post('support/{ticket}/priority', [SupportController::class, 'updatePriority'])->name('admin.support.update-priority');
Route::post('support/{ticket}/assign', [SupportController::class, 'assign'])->name('admin.support.assign');
Route::get('support/{ticket}/messages', [SupportController::class, 'getMessages'])->name('admin.support.messages');

// Redirect /admin to /admin/dashboard
Route::redirect('/', '/admin/dashboard');
