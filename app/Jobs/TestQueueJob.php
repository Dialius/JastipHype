<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TestQueueJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $message = 'Queue is working!'
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Log the message to verify queue is working
        Log::info('TestQueueJob executed: ' . $this->message);
        
        // Simulate some work
        sleep(2);
        
        Log::info('TestQueueJob completed successfully');
    }
}
