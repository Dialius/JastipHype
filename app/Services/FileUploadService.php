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
    public function upload(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): string
    {
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
    public function uploadMultiple(array $files, string $directory = 'uploads', string $disk = 'public'): array
    {
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
    public function delete(string $path, string $disk = 'public'): bool
    {
        try {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->delete($path);
            }
            return true;
        } catch (\Exception $e) {
            \Log::error('File deletion failed: ' . $e->getMessage(), [
                'path' => $path,
                'disk' => $disk,
            ]);
            return false;
        }
    }
    
    /**
     * Delete multiple files
     */
    public function deleteMultiple(array $paths, string $disk = 'public'): bool
    {
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
        try {
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
    public function getUrl(string $path, string $disk = 'public'): string
    {
        try {
            return Storage::disk($disk)->url($path);
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
    public function exists(string $path, string $disk = 'public'): bool
    {
        try {
            return Storage::disk($disk)->exists($path);
        } catch (\Exception $e) {
            return false;
        }
    }
}
