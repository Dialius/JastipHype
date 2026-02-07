<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class StorageController extends Controller
{
    /**
     * Serve files from storage/app/public directory
     * This is an alternative to symlink for Windows environments
     * 
     * @param Request $request
     * @param string $path
     * @return \Illuminate\Http\Response
     */
    public function serve(Request $request, $path)
    {
        // Security: Prevent directory traversal attacks
        $path = str_replace(['../', '..\\'], '', $path);
        
        // Check if file exists in public disk
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }
        
        // Get file path
        $filePath = Storage::disk('public')->path($path);
        
        // Get mime type
        $mimeType = Storage::disk('public')->mimeType($path);
        
        // Return file response with proper headers
        return Response::file($filePath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
        ]);
    }
}
