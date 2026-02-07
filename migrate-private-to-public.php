<?php
/**
 * Script untuk memindahkan file dari storage/app/private ke public/uploads
 * Jalankan di server production via SSH:
 * php migrate-private-to-public.php
 */

echo "=== MIGRASI FILE DARI PRIVATE KE PUBLIC ===\n\n";

// Definisi path
$basePath = __DIR__;
$privateBase = $basePath . '/storage/app/private';
$publicBase = $basePath . '/public/uploads';

// Folder yang perlu dimigrasikan
$folders = ['categories', 'banners', 'products', 'brands'];

$totalCopied = 0;
$totalErrors = 0;

foreach ($folders as $folder) {
    echo "📁 Memproses folder: $folder\n";
    
    $sourcePath = "$privateBase/$folder";
    $destPath = "$publicBase/$folder";
    
    // Cek apakah source folder ada
    if (!is_dir($sourcePath)) {
        echo "   ⚠️  Folder source tidak ada: $sourcePath\n";
        continue;
    }
    
    // Buat destination folder jika belum ada
    if (!is_dir($destPath)) {
        if (!mkdir($destPath, 0755, true)) {
            echo "   ❌ Gagal membuat folder: $destPath\n";
            $totalErrors++;
            continue;
        }
        echo "   ✅ Folder dibuat: $destPath\n";
    }
    
    // Scan files di source folder
    $files = scandir($sourcePath);
    $fileCount = 0;
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $sourceFile = "$sourcePath/$file";
        $destFile = "$destPath/$file";
        
        // Skip jika bukan file
        if (!is_file($sourceFile)) {
            continue;
        }
        
        // Copy file
        if (copy($sourceFile, $destFile)) {
            chmod($destFile, 0644);
            $fileCount++;
            $totalCopied++;
            echo "   ✅ Copied: $file\n";
        } else {
            echo "   ❌ Failed: $file\n";
            $totalErrors++;
        }
    }
    
    echo "   📊 Total file di folder ini: $fileCount\n\n";
}

echo "\n=== RINGKASAN ===\n";
echo "✅ Total file berhasil dicopy: $totalCopied\n";
echo "❌ Total error: $totalErrors\n";

// Verifikasi hasil
echo "\n=== VERIFIKASI ===\n";
foreach ($folders as $folder) {
    $destPath = "$publicBase/$folder";
    if (is_dir($destPath)) {
        $files = array_diff(scandir($destPath), ['.', '..']);
        $count = count($files);
        echo "📁 $folder: $count file(s)\n";
        
        // List 3 file pertama sebagai sample
        $sample = array_slice($files, 0, 3);
        foreach ($sample as $file) {
            echo "   - $file\n";
        }
        if ($count > 3) {
            echo "   ... dan " . ($count - 3) . " file lainnya\n";
        }
    }
}

echo "\n✅ MIGRASI SELESAI!\n";
echo "\n📝 LANGKAH SELANJUTNYA:\n";
echo "1. Cek apakah file sudah ada di public/uploads/\n";
echo "2. Test upload gambar baru via admin panel\n";
echo "3. Pastikan gambar baru masuk ke public/uploads/ bukan storage/app/private/\n";
echo "4. Jika sudah OK, hapus file lama di storage/app/private/\n";
