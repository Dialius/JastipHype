<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class FileUploadService
{
    /**
     * Upload file with proper error handling for serverless environments
     */
    public function upload(UploadedFile $file, string $directory = 'uploads', ?string $disk = null): string
    {
        $disk = $disk ?? config('filesystems.default');
        try {
            // Ensure directory exists
            $this->ensureDirectoryExists($directory, $disk);
            
            // Generate unique filename
            $filename = $this->generateUniqueFilename($file);
            
            // Store file
            $path = $file->storeAs($directory, $filename, $disk);
            
            if (!$path) {
                throw new \Exception('Failed to store file');
            }
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('File upload failed: ' . $e->getMessage(), [
                'directory' => $directory,
                'disk' => $disk,
                'original_name' => $file->getClientOriginalName(),
            ]);
            
            throw new \Exception('Failed to upload file: ' . $e->getMessage());
        }
    }
    
    /**
     * Upload multiple files
     */
    /**
     * Upload multiple files
     */
    public function uploadMultiple(array $files, string $directory = 'uploads', ?string $disk = null): array
    {
        $disk = $disk ?? config('filesystems.default');
        $paths = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->upload($file, $directory, $disk);
            }
        }
        
        return $paths;
    }
    
    /**
     * Delete file
     */
    public function delete(string $path, ?string $disk = null): bool
    {
        $disk = $disk ?? config('filesystems.default');
        try {
            // In serverless, files in /tmp are ephemeral and may not exist
            // Don't fail if file doesn't exist
            if (!Storage::disk($disk)->exists($path)) {
                \Log::info('File does not exist, skipping deletion', [
                    'path' => $path,
                    'disk' => $disk,
                ]);
                return true;
            }
            
            $result = Storage::disk($disk)->delete($path);
            
            if (!$result) {
                \Log::warning('File deletion returned false', [
                    'path' => $path,
                    'disk' => $disk,
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            \Log::error('File deletion failed: ' . $e->getMessage(), [
                'path' => $path,
                'disk' => $disk,
                'trace' => $e->getTraceAsString()
            ]);
            // Don't throw exception, just return false
            // This prevents 500 errors when deleting files in serverless
            return false;
        }
    }
    
    /**
     * Delete multiple files
     */
    public function deleteMultiple(array $paths, ?string $disk = null): bool
    {
        $disk = $disk ?? config('filesystems.default');
        try {
            $existingPaths = array_filter($paths, function($path) use ($disk) {
                return Storage::disk($disk)->exists($path);
            });
            
            if (empty($existingPaths)) {
                return true;
            }
            
            return Storage::disk($disk)->delete($existingPaths);
        } catch (\Exception $e) {
            \Log::error('Multiple file deletion failed: ' . $e->getMessage(), [
                'paths' => $paths,
                'disk' => $disk,
            ]);
            return false;
        }
    }
    
    /**
     * Ensure directory exists
     */
    protected function ensureDirectoryExists(string $directory, string $disk = 'public'): void
    {
        // For S3/Cloudinary, we don't need to explicitly create directories
        if (in_array($disk, ['s3', 'cloudinary'])) {
            return;
        }

        // In serverless environments, skip directory creation
        if ($this->isServerless()) {
            return;
        }

        try {
            // Only try to get path if we're not in serverless
            $fullPath = Storage::disk($disk)->path($directory);
            
            if (!file_exists($fullPath)) {
                // Create directory with proper permissions
                if (!mkdir($fullPath, 0755, true) && !is_dir($fullPath)) {
                    throw new \Exception("Failed to create directory: {$fullPath}");
                }
            }
        } catch (\Exception $e) {
            // In serverless environments, we might not be able to create directories
            // Log the error but don't throw - let Laravel's storage handle it
            \Log::warning('Directory creation warning: ' . $e->getMessage(), [
                'directory' => $directory,
                'disk' => $disk,
            ]);
        }
    }
    
    /**
     * Check if running in serverless environment
     */
    protected function isServerless(): bool
    {
        return !empty(getenv('VERCEL_ENV')) || 
               !empty(getenv('AWS_LAMBDA_FUNCTION_NAME')) ||
               !empty(getenv('NETLIFY'));
    }
    
    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $basename = Str::slug($basename);
        
        return $basename . '-' . time() . '-' . Str::random(8) . '.' . $extension;
    }
    
    /**
     * Get file URL
     */
    public function getUrl(string $path, ?string $disk = null): string
    {
        $disk = $disk ?? config('filesystems.default');
        try {
            return \App\Helpers\ImageHelper::getImageUrl($path, $disk);
        } catch (\Exception $e) {
            \Log::error('Failed to get file URL: ' . $e->getMessage(), [
                'path' => $path,
                'disk' => $disk,
            ]);
            return '';
        }
    }
    
    /**
     * Check if file exists
     */
    public function exists(string $path, ?string $disk = null): bool
    {
        $disk = $disk ?? config('filesystems.default');
        try {
            return Storage::disk($disk)->exists($path);
        } catch (\Exception $e) {
            return false;
        }
    }
}
