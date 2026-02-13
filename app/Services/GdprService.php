<?php

namespace App\Services;

use App\Models\User;
use App\Models\DataExportRequest;
use App\Models\DataDeletionRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class GdprService
{
    /**
     * Export user data
     */
    public function exportUserData(User $user): DataExportRequest
    {
        $request = DataExportRequest::create([
            'user_id' => $user->id,
            'status' => 'processing',
        ]);

        try {
            $data = $this->collectUserData($user);
            $filePath = $this->createExportFile($user, $data);
            
            $request->update([
                'status' => 'completed',
                'file_path' => $filePath,
                'completed_at' => now(),
                'expires_at' => now()->addDays(7),
            ]);
        } catch (\Exception $e) {
            $request->update(['status' => 'failed']);
            throw $e;
        }

        return $request;
    }

    /**
     * Collect all user data
     */
    protected function collectUserData(User $user): array
    {
        return [
            'personal_information' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'created_at' => $user->created_at,
                'email_verified_at' => $user->email_verified_at,
            ],
            'orders' => $user->orders()->with('items.product')->get()->toArray(),
            'reviews' => $user->reviews()->with('product')->get()->toArray(),
            'wishlist' => $user->wishlists()->with('product')->get()->toArray(),
            'cart' => $user->cart ? $user->cart->items()->with('product')->get()->toArray() : [],
            'activity_logs' => DB::table('activity_logs')->where('user_id', $user->id)->get()->toArray(),
            'security_events' => DB::table('security_events')->where('user_id', $user->id)->get()->toArray(),
        ];
    }

    /**
     * Create export file
     */
    protected function createExportFile(User $user, array $data): string
    {
        $fileName = 'user_data_' . $user->id . '_' . time() . '.json';
        $filePath = 'gdpr-exports/' . $fileName;
        
        Storage::disk('local')->put($filePath, json_encode($data, JSON_PRETTY_PRINT));
        
        return $filePath;
    }

    /**
     * Request data deletion
     */
    public function requestDataDeletion(User $user, ?string $reason = null): DataDeletionRequest
    {
        return DataDeletionRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'reason' => $reason,
        ]);
    }

    /**
     * Process data deletion
     */
    public function processDataDeletion(DataDeletionRequest $request): void
    {
        $user = $request->user;
        
        DB::transaction(function () use ($user, $request) {
            // Anonymize orders (keep for legal/accounting purposes)
            $user->orders()->update([
                'customer_name' => 'Deleted User',
                'customer_email' => 'deleted@example.com',
                'customer_phone' => null,
                'shipping_address' => 'Address Deleted',
            ]);

            // Delete reviews
            $user->reviews()->delete();

            // Delete wishlist
            $user->wishlists()->delete();

            // Delete cart
            if ($user->cart) {
                $user->cart->items()->delete();
                $user->cart->delete();
            }

            // Delete activity logs
            DB::table('activity_logs')->where('user_id', $user->id)->delete();
            
            // Delete security events
            DB::table('security_events')->where('user_id', $user->id)->delete();

            // Delete cookie consents
            DB::table('cookie_consents')->where('user_id', $user->id)->delete();

            // Delete user account
            $user->delete();

            $request->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        });
    }
}
