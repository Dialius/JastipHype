<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Inspect\Crawler;
use App\Models\Category;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('debug:image-paths', function () {
    $this->info("Checking Category Images...");
    
    $categories = Category::whereIn('slug', ['accessories', 'clothing', 'hoodies', 'sneakers'])->get();

    foreach ($categories as $category) {
        $this->line("Category: {$category->name} (ID: {$category->id})");
        $this->line("DB Image Path: " . ($category->image ?? 'NULL'));
        
        $url = ImageHelper::getCategoryImageUrl($category);
        $this->line("Generated URL: {$url}");
        
        if ($category->image) {
            // Check if file exists in storage/app/public
            $existsStorage = Storage::disk('public')->exists($category->image);
            $this->line("Exists in 'public' disk (via Storage facade): " . ($existsStorage ? 'YES' : 'NO'));
            
            // Check physical path
            $fullPath = storage_path('app/public/' . $category->image);
            $this->line("Full System Path: {$fullPath}");
            $this->line("File Exists at Path: " . (file_exists($fullPath) ? 'YES' : 'NO'));
        }
        
        $this->line("-----------------------------------");
    }

    $this->info("\nChecking Banners...");
    $banners = \App\Models\Banner::all();
    foreach ($banners as $banner) {
        $this->line("Banner: {$banner->title} (ID: {$banner->id})");
        $this->line("DB Image Path: " . ($banner->image_path ?? 'NULL'));
        
        $url = ImageHelper::getBannerImageUrl($banner);
        $this->line("Generated URL: {$url}");

        if ($banner->image_path) {
             $existsPublic = Storage::disk('public')->exists($banner->image_path);
            $this->line("Exists in 'public' disk: " . ($existsPublic ? 'YES' : 'NO'));
        }
        $this->line("-----------------------------------");
    }
})->purpose('Debug category and banner image paths');
