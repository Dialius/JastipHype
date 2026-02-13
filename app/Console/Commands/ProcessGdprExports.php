<?php

namespace App\Console\Commands;

use App\Models\DataExportRequest;
use App\Services\GdprService;
use Illuminate\Console\Command;

class ProcessGdprExports extends Command
{
    protected $signature = 'gdpr:process-exports';
    protected $description = 'Process pending GDPR data export requests';

    protected $gdprService;

    public function __construct(GdprService $gdprService)
    {
        parent::__construct();
        $this->gdprService = $gdprService;
    }

    public function handle()
    {
        $pendingRequests = DataExportRequest::where('status', 'pending')->get();

        if ($pendingRequests->isEmpty()) {
            $this->info('No pending export requests');
            return 0;
        }

        $this->info("Processing {$pendingRequests->count()} export requests...");

        foreach ($pendingRequests as $request) {
            try {
                $this->info("Processing export for user {$request->user_id}...");
                $this->gdprService->exportUserData($request->user);
                $this->info("✓ Completed");
            } catch (\Exception $e) {
                $this->error("✗ Failed: {$e->getMessage()}");
            }
        }

        $this->info('All export requests processed!');
        return 0;
    }
}
