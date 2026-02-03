<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EnsureStorageDirectories
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure storage directories exist
        $this->ensureDirectoriesExist();
        
        return $next($request);
    }
    
    /**
     * Ensure all required storage directories exist
     */
    protected function ensureDirectoriesExist(): void
    {
        $directories = [
            'banners',
            'brands',
            'categories',
            'products',
            'reviews',
        ];
        
        foreach ($directories as $directory) {
            try {
                $disk = Storage::disk('public');
                
                // Check if directory exists
                if (!$disk->exists($directory)) {
                    // Try to create directory
                    $disk->makeDirectory($directory);
                }
            } catch (\Exception $e) {
                // Log error but don't throw - let the upload handle it
                Log::warning("Failed to ensure directory exists: {$directory}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
