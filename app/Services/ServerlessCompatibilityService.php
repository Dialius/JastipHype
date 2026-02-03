<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ServerlessCompatibilityService
{
    /**
     * Check if running in serverless environment
     */
    public function isServerless(): bool
    {
        return !empty(env('VERCEL_ENV')) || 
               !empty(env('AWS_LAMBDA_FUNCTION_NAME')) ||
               !empty(env('NETLIFY'));
    }
    
    /**
     * Check if filesystem is writable
     */
    public function isFilesystemWritable(string $path = null): bool
    {
        if ($this->isServerless()) {
            // In serverless, only /tmp is writable
            $path = $path ?? storage_path();
            return str_starts_with($path, '/tmp');
        }
        
        $path = $path ?? storage_path();
        return is_writable($path);
    }
    
    /**
     * Get writable path for serverless
     */
    public function getWritablePath(string $relativePath = ''): string
    {
        if ($this->isServerless()) {
            return '/tmp/' . ltrim($relativePath, '/');
        }
        
        return storage_path($relativePath);
    }
    
    /**
     * Write file with serverless compatibility
     */
    public function writeFile(string $path, string $content): bool
    {
        try {
            if ($this->isServerless()) {
                // In serverless, store in cache instead
                $cacheKey = 'file_' . md5($path);
                Cache::put($cacheKey, $content, now()->addYears(1));
                
                Log::warning("File write attempted in serverless environment: {$path}. Content stored in cache instead.");
                return true;
            }
            
            // Ensure directory exists
            $directory = dirname($path);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            return file_put_contents($path, $content) !== false;
        } catch (\Exception $e) {
            Log::error("Failed to write file: {$path}", [
                'error' => $e->getMessage(),
                'is_serverless' => $this->isServerless(),
            ]);
            return false;
        }
    }
    
    /**
     * Read file with serverless compatibility
     */
    public function readFile(string $path): ?string
    {
        try {
            if ($this->isServerless()) {
                // Try to read from cache first
                $cacheKey = 'file_' . md5($path);
                $cached = Cache::get($cacheKey);
                
                if ($cached !== null) {
                    return $cached;
                }
            }
            
            if (file_exists($path)) {
                return file_get_contents($path);
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error("Failed to read file: {$path}", [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
    
    /**
     * Check if file exists with serverless compatibility
     */
    public function fileExists(string $path): bool
    {
        if ($this->isServerless()) {
            $cacheKey = 'file_' . md5($path);
            return Cache::has($cacheKey) || file_exists($path);
        }
        
        return file_exists($path);
    }
    
    /**
     * Delete file with serverless compatibility
     */
    public function deleteFile(string $path): bool
    {
        try {
            if ($this->isServerless()) {
                $cacheKey = 'file_' . md5($path);
                Cache::forget($cacheKey);
            }
            
            if (file_exists($path)) {
                return unlink($path);
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete file: {$path}", [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
    /**
     * Get environment variable with fallback
     */
    public function getEnv(string $key, $default = null)
    {
        $value = env($key, $default);
        
        // If in serverless and value is empty, try to get from cache
        if ($this->isServerless() && empty($value)) {
            $cacheKey = 'env_' . $key;
            $cached = Cache::get($cacheKey);
            
            if ($cached !== null) {
                return $cached;
            }
        }
        
        return $value;
    }
    
    /**
     * Set environment variable with serverless compatibility
     */
    public function setEnv(string $key, $value): bool
    {
        if ($this->isServerless()) {
            // Store in cache for serverless
            $cacheKey = 'env_' . $key;
            Cache::put($cacheKey, $value, now()->addYears(1));
            
            Log::warning("Environment variable update attempted in serverless: {$key}. Please update in hosting dashboard.");
            return true;
        }
        
        // For traditional hosting, update .env file
        $envFile = base_path('.env');
        
        if (!file_exists($envFile) || !is_writable($envFile)) {
            Log::error('.env file does not exist or is not writable');
            return false;
        }
        
        try {
            $envContent = file_get_contents($envFile);
            $value = str_replace('"', '\"', $value);
            
            if (preg_match("/^{$key}=/m", $envContent)) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envContent
                );
            } else {
                $envContent .= "\n{$key}=\"{$value}\"";
            }
            
            return file_put_contents($envFile, $envContent) !== false;
        } catch (\Exception $e) {
            Log::error("Failed to update .env file", [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
    /**
     * Get cache driver suitable for serverless
     */
    public function getCacheDriver(): string
    {
        if ($this->isServerless()) {
            // Use database or array cache for serverless
            return env('CACHE_STORE', 'database');
        }
        
        return config('cache.default');
    }
    
    /**
     * Get session driver suitable for serverless
     */
    public function getSessionDriver(): string
    {
        if ($this->isServerless()) {
            // Use cookie or database session for serverless
            return env('SESSION_DRIVER', 'cookie');
        }
        
        return config('session.driver');
    }
    
    /**
     * Get queue driver suitable for serverless
     */
    public function getQueueDriver(): string
    {
        if ($this->isServerless()) {
            // Use sync or database queue for serverless
            // For production, use SQS or Redis
            return env('QUEUE_CONNECTION', 'sync');
        }
        
        return config('queue.default');
    }
    
    /**
     * Check if can run artisan commands
     */
    public function canRunArtisan(): bool
    {
        // In serverless, artisan commands might not work properly
        return !$this->isServerless();
    }
    
    /**
     * Get recommendations for serverless setup
     */
    public function getServerlessRecommendations(): array
    {
        if (!$this->isServerless()) {
            return [];
        }
        
        $recommendations = [];
        
        // Check cache driver
        if (config('cache.default') === 'file') {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'File cache driver is not recommended for serverless. Use database or Redis.',
                'config' => 'CACHE_STORE=database',
            ];
        }
        
        // Check session driver
        if (config('session.driver') === 'file') {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'File session driver is not recommended for serverless. Use cookie or database.',
                'config' => 'SESSION_DRIVER=cookie',
            ];
        }
        
        // Check queue driver
        if (config('queue.default') === 'database' && !$this->isServerless()) {
            $recommendations[] = [
                'type' => 'info',
                'message' => 'For production serverless, consider using SQS or Redis for queue.',
                'config' => 'QUEUE_CONNECTION=sqs',
            ];
        }
        
        // Check storage
        if (config('filesystems.default') === 'local') {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Local storage is not persistent in serverless. Use S3 or similar.',
                'config' => 'FILESYSTEM_DISK=s3',
            ];
        }
        
        return $recommendations;
    }
}
