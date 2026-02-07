<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display email templates list
     */
    public function templates()
    {
        $templates = $this->getAvailableTemplates();
        
        return view('admin.notifications.templates', compact('templates'));
    }

    /**
     * Show edit form for a template
     */
    public function editTemplate(string $template)
    {
        if (!$this->templateExists($template)) {
            return redirect()->route('admin.notifications.templates')
                ->with('error', 'Template not found.');
        }

        $templatePath = $this->getTemplatePath($template);
        $content = file_get_contents($templatePath);
        $variables = $this->getTemplateVariables($template);
        
        return view('admin.notifications.edit-template', compact('template', 'content', 'variables'));
    }

    /**
     * Update email template
     */
    public function updateTemplate(Request $request, string $template)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        if (!$this->templateExists($template)) {
            return redirect()->route('admin.notifications.templates')
                ->with('error', 'Template not found.');
        }

        try {
            $templatePath = $this->getTemplatePath($template);
            
            // In serverless environments, we can't write to the filesystem
            // Store template content in database or cache instead
            if (env('VERCEL_ENV')) {
                // Store in cache for serverless
                \Cache::put("email_template_{$template}", $request->input('content'), now()->addYears(1));
                
                return redirect()->route('admin.notifications.templates')
                    ->with('warning', 'Template updated in cache. Note: Changes will be lost on deployment. Consider using a database-backed template system for production.');
            }
            
            // For local/traditional hosting, write to file
            file_put_contents($templatePath, $request->input('content'));

            return redirect()->route('admin.notifications.templates')
                ->with('success', 'Template updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Template update failed: ' . $e->getMessage());
            
            return redirect()->route('admin.notifications.templates')
                ->with('error', 'Failed to update template: ' . $e->getMessage());
        }
    }

    /**
     * Preview email template with sample data
     */
    public function previewTemplate(Request $request, string $template)
    {
        if (!$this->templateExists($template)) {
            return response()->json(['error' => 'Template not found.'], 404);
        }

        $sampleData = $this->getSampleData($template);
        
        try {
            $html = View::make('emails.' . $template, $sampleData)->render();
            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to render template: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display notification history
     */
    public function history(Request $request)
    {
        // For now, we'll show queued jobs from the jobs table
        // In a production app, you'd want a dedicated notifications table
        $query = \DB::table('jobs')
            ->select('id', 'queue', 'payload', 'attempts', 'created_at')
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            // Filter by status if needed
        }

        $notifications = $query->paginate(20);

        return view('admin.notifications.history', compact('notifications'));
    }

    /**
     * Retry failed notification
     */
    public function retry(int $id)
    {
        // Get failed job from failed_jobs table
        $failedJob = \DB::table('failed_jobs')->find($id);

        if (!$failedJob) {
            return redirect()->route('admin.notifications.history')
                ->with('error', 'Failed notification not found.');
        }

        // Retry the job
        \Artisan::call('queue:retry', ['id' => $id]);

        return redirect()->route('admin.notifications.history')
            ->with('success', 'Notification queued for retry.');
    }

    /**
     * Get list of available email templates
     */
    protected function getAvailableTemplates(): array
    {
        return [
            [
                'name' => 'order-confirmation',
                'title' => 'Order Confirmation',
                'description' => 'Sent when an order is placed',
                'path' => 'emails.order-confirmation',
            ],
            [
                'name' => 'payment-success',
                'title' => 'Payment Success',
                'description' => 'Sent when payment is confirmed',
                'path' => 'emails.payment-success',
            ],
            [
                'name' => 'welcome',
                'title' => 'Welcome Email',
                'description' => 'Sent to new users',
                'path' => 'emails.welcome',
            ],
            [
                'name' => 'password-reset-otp',
                'title' => 'Password Reset OTP',
                'description' => 'Sent when user requests password reset',
                'path' => 'emails.password-reset-otp',
            ],
            [
                'name' => 'password-change-otp',
                'title' => 'Password Change OTP',
                'description' => 'Sent when user changes password',
                'path' => 'emails.password-change-otp',
            ],
        ];
    }

    /**
     * Check if template exists
     */
    protected function templateExists(string $template): bool
    {
        $templatePath = $this->getTemplatePath($template);
        return file_exists($templatePath);
    }

    /**
     * Get template file path
     */
    protected function getTemplatePath(string $template): string
    {
        return resource_path('views/emails/' . $template . '.blade.php');
    }

    /**
     * Get available variables for a template
     */
    protected function getTemplateVariables(string $template): array
    {
        $variables = [
            'order-confirmation' => [
                'order' => 'Order object with items, total, shipping details',
                'user' => 'User object with name, email',
            ],
            'payment-success' => [
                'order' => 'Order object',
                'payment' => 'Payment object with transaction details',
                'user' => 'User object',
            ],
            'welcome' => [
                'user' => 'User object with name, email',
            ],
            'password-reset-otp' => [
                'user' => 'User object',
                'otp' => 'OTP code',
            ],
            'password-change-otp' => [
                'user' => 'User object',
                'otp' => 'OTP code',
            ],
        ];

        return $variables[$template] ?? [];
    }

    /**
     * Get sample data for template preview
     */
    protected function getSampleData(string $template): array
    {
        $sampleUser = (object) [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $sampleOrder = (object) [
            'order_number' => 'ORD-2026-001',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'customer_phone' => '+62 812-3456-7890',
            'shipping_address' => 'Jl. Sudirman No. 123, Jakarta Selatan, DKI Jakarta 12190',
            'shipping_courier' => 'JNE',
            'shipping_service' => 'REG',
            'shipping_cost' => 15000,
            'subtotal' => 500000,
            'discount_amount' => 50000,
            'total_amount' => 465000,
            'estimated_delivery' => '2-3 business days',
            'created_at' => now(),
            'items' => [
                (object) [
                    'product_name' => 'Supreme Box Logo Hoodie',
                    'size' => 'L',
                    'quantity' => 1,
                    'price' => 500000,
                ],
            ],
            'payment' => (object) [
                'payment_method' => 'Bank Transfer (BCA)',
                'transaction_id' => 'TXN-123456789',
                'status' => 'paid',
                'paid_at' => now(),
            ],
        ];

        $sampleData = [
            'order-confirmation' => [
                'order' => $sampleOrder,
                'user' => $sampleUser,
            ],
            'payment-success' => [
                'order' => $sampleOrder,
                'payment' => $sampleOrder->payment,
                'user' => $sampleUser,
                'userName' => $sampleUser->name,
            ],
            'welcome' => [
                'user' => $sampleUser,
                'userName' => $sampleUser->name,
            ],
            'password-reset-otp' => [
                'user' => $sampleUser,
                'userName' => $sampleUser->name,
                'otp' => '123456',
            ],
            'password-change-otp' => [
                'user' => $sampleUser,
                'userName' => $sampleUser->name,
                'otp' => '654321',
            ],
        ];

        return $sampleData[$template] ?? [];
    }
}
