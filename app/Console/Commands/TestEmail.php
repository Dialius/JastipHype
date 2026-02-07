<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;

class TestEmail extends Command
{
    protected $signature = 'email:test {email?}';
    protected $description = 'Test email configuration';

    public function handle(EmailService $emailService)
    {
        $this->info('Testing email configuration...');
        $this->info('SMTP Host: ' . config('mail.mailers.smtp.host'));
        $this->info('SMTP Port: ' . config('mail.mailers.smtp.port'));
        $this->info('From Address: ' . config('mail.from.address'));
        
        $this->newLine();
        
        $result = $emailService->testEmailConnection();
        
        if ($result['success']) {
            $this->info('✓ ' . $result['message']);
            return Command::SUCCESS;
        } else {
            $this->error('✗ ' . $result['message']);
            return Command::FAILURE;
        }
    }
}
