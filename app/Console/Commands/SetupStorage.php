<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SetupStorage extends Command
{
    protected $signature = 'storage:setup';
    protected $description = 'Create required upload directories and verify storage setup for Hostinger';

    public function handle()
    {
        $this->info('Setting up storage directories...');

        // Directories that need to exist under public/uploads/
        $directories = [
            'products',
            'categories',
            'brands',
            'banners',
            'avatars',
        ];

        $publicUploads = public_path('uploads');

        // Create base uploads dir
        if (!is_dir($publicUploads)) {
            mkdir($publicUploads, 0755, true);
            $this->info("Created: public/uploads/");
        } else {
            $this->line("Exists : public/uploads/");
        }

        // Create each subdirectory
        foreach ($directories as $dir) {
            $path = $publicUploads . DIRECTORY_SEPARATOR . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
                $this->info("Created: public/uploads/{$dir}/");
            } else {
                $this->line("Exists : public/uploads/{$dir}/");
            }
        }

        // Add .htaccess to allow image serving
        $htaccess = $publicUploads . DIRECTORY_SEPARATOR . '.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "Options -Indexes\n<FilesMatch \"\.(jpg|jpeg|png|gif|webp|svg)$\">\n    Allow from all\n</FilesMatch>\n");
            $this->info("Created: public/uploads/.htaccess");
        }

        // Verify filesystem disk config
        $disk = config('filesystems.disks.public');
        $this->newLine();
        $this->info('Filesystem disk config:');
        $this->line('  root: ' . ($disk['root'] ?? 'NOT SET'));
        $this->line('  url:  ' . ($disk['url'] ?? 'NOT SET'));

        // Check APP_URL
        $this->newLine();
        $this->info('APP_URL: ' . config('app.url'));

        $this->newLine();
        $this->info('✅ Storage setup complete!');
        $this->warn('Make sure APP_URL in .env matches your Hostinger domain exactly (e.g. https://jastiphype.shop)');

        return Command::SUCCESS;
    }
}
