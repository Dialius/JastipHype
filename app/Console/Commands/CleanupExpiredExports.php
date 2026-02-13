<?php

namespace App\Console\Commands;

use App\Models\DataExportRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupExpiredExports extends Command
{
    protected $signature = 'gdpr:cleanup-exports';
    protected $description = 'Delete expired GDPR export files';

    public function handle()
    {
        $expiredRequests = DataExportRequest::where('status', 'completed')
            ->where('expires_at', '<', now())
            ->get();

        if ($expiredRequests->isEmpty()) {
            $this->info('No expired exports to clean up');
            return 0;
        }

        $this->info("Cleaning up {$expiredRequests->count()} expired exports...");

        foreach ($expiredRequests as $request) {
            if ($request->file_path && Storage::disk('local')->exists($request->file_path)) {
                Storage::disk('local')->delete($request->file_path);
                $this->info("✓ Deleted: {$request->file_path}");
            }
        }

        $this->info('Cleanup completed!');
        return 0;
    }
}
