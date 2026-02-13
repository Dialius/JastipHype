<?php

namespace App\Console\Commands;

use App\Services\SecurityService;
use Illuminate\Console\Command;

class CleanupSecurityLogs extends Command
{
    protected $signature = 'security:cleanup';
    protected $description = 'Clean up old security logs and expired blocks';

    protected $securityService;

    public function __construct(SecurityService $securityService)
    {
        parent::__construct();
        $this->securityService = $securityService;
    }

    public function handle()
    {
        $this->info('Cleaning up old login attempts...');
        $this->securityService->cleanOldLoginAttempts();

        $this->info('Cleaning up expired blocked IPs...');
        $this->securityService->cleanExpiredBlockedIps();

        $this->info('Security cleanup completed!');
        return 0;
    }
}
