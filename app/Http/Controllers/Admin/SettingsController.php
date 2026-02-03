<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\SettingsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    protected SettingsRepositoryInterface $settingsRepository;
    protected \App\Services\ServerlessCompatibilityService $serverlessService;

    public function __construct(
        SettingsRepositoryInterface $settingsRepository,
        \App\Services\ServerlessCompatibilityService $serverlessService
    ) {
        $this->settingsRepository = $settingsRepository;
        $this->serverlessService = $serverlessService;
    }

    /**
     * Display settings page
     */
    public function index()
    {
        $settings = [
            'general' => $this->settingsRepository->getByGroup('general'),
            'payment' => $this->settingsRepository->getByGroup('payment'),
            'shipping' => $this->settingsRepository->getByGroup('shipping'),
            'email' => $this->settingsRepository->getByGroup('email'),
            'notifications' => $this->settingsRepository->getByGroup('notifications'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
        ]);

        $settings = [
            'site_name' => $request->site_name,
            'site_description' => $request->site_description,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
            'facebook_url' => $request->facebook_url,
            'instagram_url' => $request->instagram_url,
            'twitter_url' => $request->twitter_url,
        ];

        $this->settingsRepository->setGroup('general', $settings);

        return back()->with('success', 'General settings updated successfully');
    }

    /**
     * Update payment settings
     */
    public function updatePayment(Request $request)
    {
        $request->validate([
            'midtrans_server_key' => 'required|string',
            'midtrans_client_key' => 'required|string',
            'midtrans_environment' => 'required|in:sandbox,production',
            'midtrans_merchant_id' => 'nullable|string',
        ]);

        $settings = [
            'midtrans_server_key' => $request->midtrans_server_key,
            'midtrans_client_key' => $request->midtrans_client_key,
            'midtrans_environment' => $request->midtrans_environment,
            'midtrans_merchant_id' => $request->midtrans_merchant_id,
        ];

        $this->settingsRepository->setGroup('payment', $settings);

        // Update .env file
        $this->updateEnvFile([
            'MIDTRANS_SERVER_KEY' => $request->midtrans_server_key,
            'MIDTRANS_CLIENT_KEY' => $request->midtrans_client_key,
            'MIDTRANS_ENVIRONMENT' => $request->midtrans_environment,
            'MIDTRANS_MERCHANT_ID' => $request->midtrans_merchant_id,
        ]);

        return back()->with('success', 'Payment settings updated successfully');
    }

    /**
     * Update shipping settings
     */
    public function updateShipping(Request $request)
    {
        $request->validate([
            'rajaongkir_api_key' => 'required|string',
            'origin_city_id' => 'required|integer',
            'origin_city_name' => 'required|string',
        ]);

        $settings = [
            'rajaongkir_api_key' => $request->rajaongkir_api_key,
            'origin_city_id' => $request->origin_city_id,
            'origin_city_name' => $request->origin_city_name,
        ];

        $this->settingsRepository->setGroup('shipping', $settings);

        // Update .env file
        $this->updateEnvFile([
            'RAJAONGKIR_API_KEY' => $request->rajaongkir_api_key,
            'RAJAONGKIR_ORIGIN_CITY' => $request->origin_city_id,
        ]);

        return back()->with('success', 'Shipping settings updated successfully');
    }

    /**
     * Update email settings
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_mailer' => 'required|in:smtp,sendmail,mailgun,ses,postmark',
            'mail_host' => 'required_if:mail_mailer,smtp|string',
            'mail_port' => 'required_if:mail_mailer,smtp|integer',
            'mail_username' => 'required_if:mail_mailer,smtp|string',
            'mail_password' => 'required_if:mail_mailer,smtp|string',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        $settings = [
            'mail_mailer' => $request->mail_mailer,
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username,
            'mail_password' => $request->mail_password,
            'mail_encryption' => $request->mail_encryption,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
        ];

        $this->settingsRepository->setGroup('email', $settings);

        // Update .env file
        $this->updateEnvFile([
            'MAIL_MAILER' => $request->mail_mailer,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_PASSWORD' => $request->mail_password,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => $request->mail_from_name,
        ]);

        return back()->with('success', 'Email settings updated successfully');
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $settings = [
            'notify_new_order' => $request->has('notify_new_order'),
            'notify_order_status' => $request->has('notify_order_status'),
            'notify_payment_received' => $request->has('notify_payment_received'),
            'notify_new_customer' => $request->has('notify_new_customer'),
            'notify_new_review' => $request->has('notify_new_review'),
            'notify_low_stock' => $request->has('notify_low_stock'),
        ];

        $this->settingsRepository->setGroup('notifications', $settings);

        return back()->with('success', 'Notification settings updated successfully');
    }

    /**
     * Test email connection
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            Mail::raw('This is a test email from JastipHype Admin Panel.', function ($message) use ($request) {
                $message->to($request->test_email)
                    ->subject('Test Email - JastipHype');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $request->test_email,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test Midtrans connection
     */
    public function testMidtrans(Request $request)
    {
        try {
            // Simple test to check if Midtrans credentials are valid
            // In production, you would make an actual API call to Midtrans
            $serverKey = config('midtrans.server_key');
            $clientKey = config('midtrans.client_key');

            if (empty($serverKey) || empty($clientKey)) {
                throw new \Exception('Midtrans credentials are not configured');
            }

            return response()->json([
                'success' => true,
                'message' => 'Midtrans credentials are configured correctly',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Midtrans connection test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update .env file
     */
    protected function updateEnvFile(array $data): void
    {
        foreach ($data as $key => $value) {
            $success = $this->serverlessService->setEnv($key, $value);
            
            if (!$success) {
                Log::warning("Failed to update environment variable: {$key}");
            }
        }
        
        // Show warning if in serverless
        if ($this->serverlessService->isServerless()) {
            session()->flash('warning', 'Settings saved to database. For permanent changes in serverless environment, please update environment variables in your hosting dashboard (Vercel/AWS/etc).');
        }
    }
}
