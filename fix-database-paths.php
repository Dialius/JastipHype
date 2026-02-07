<?php
/**
 * Script untuk memperbaiki path gambar di database
 * Menghapus prefix yang salah seperti /storage/, /uploads/, dll
 * 
 * Jalankan di server: php fix-database-paths.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== FIX DATABASE IMAGE PATHS ===\n\n";

// Tables yang perlu dicek
$tables = [
    'products' => 'image',
    'brands' => 'logo',
    'categories' => 'image',
    'banners' => 'image',
    'product_images' => 'image_path',
];

$totalFixed = 0;

foreach ($tables as $table => $column) {
    echo "📋 Checking table: $table\n";
    
    try {
        $records = DB::table($table)->whereNotNull($column)->get();
        $fixedCount = 0;
        
        foreach ($records as $record) {
            $oldPath = $record->$column;
            $newPath = $oldPath;
            $needsUpdate = false;
            
            // Skip jika kosong atau null
            if (empty($oldPath)) {
                continue;
            }
            
            // Skip jika placeholder
            if (strpos($oldPath, 'placehold.co') !== false) {
                echo "   ⚠️  ID {$record->id}: Placeholder image (skipped)\n";
                continue;
            }
            
            // Skip jika sudah benar (hanya nama folder/file)
            if (!preg_match('#^(https?://|/storage/|/uploads/|storage/|uploads/)#', $oldPath)) {
                echo "   ✅ ID {$record->id}: Path sudah benar ($oldPath)\n";
                continue;
            }
            
            // Fix: Hapus prefix yang salah
            $patterns = [
                '#^https?://[^/]+/storage/#' => '',  // https://domain.com/storage/
                '#^https?://[^/]+/uploads/#' => '',  // https://domain.com/uploads/
                '#^/storage/#' => '',                 // /storage/
                '#^/uploads/#' => '',                 // /uploads/
                '#^storage/#' => '',                  // storage/
                '#^uploads/#' => '',                  // uploads/
            ];
            
            foreach ($patterns as $pattern => $replacement) {
                if (preg_match($pattern, $newPath)) {
                    $newPath = preg_replace($pattern, $replacement, $newPath);
                    $needsUpdate = true;
                    break;
                }
            }
            
            // Update jika perlu
            if ($needsUpdate && $newPath !== $oldPath) {
                DB::table($table)
                    ->where('id', $record->id)
                    ->update([$column => $newPath]);
                
                echo "   🔧 ID {$record->id}: Fixed\n";
                echo "      Old: $oldPath\n";
                echo "      New: $newPath\n";
                $fixedCount++;
                $totalFixed++;
            }
        }
        
        echo "   📊 Fixed $fixedCount record(s) in $table\n\n";
        
    } catch (\Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n\n";
    }
}

echo "\n=== RINGKASAN ===\n";
echo "✅ Total records fixed: $totalFixed\n";

// Verifikasi hasil
echo "\n=== VERIFIKASI ===\n";
foreach ($tables as $table => $column) {
    try {
        $sample = DB::table($table)
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->limit(3)
            ->get(['id', $column]);
        
        if ($sample->count() > 0) {
            echo "\n📋 $table (sample):\n";
            foreach ($sample as $record) {
                echo "   ID {$record->id}: {$record->$column}\n";
            }
        }
    } catch (\Exception $e) {
        // Skip jika table tidak ada
    }
}

echo "\n✅ SELESAI!\n";
echo "\n📝 LANGKAH SELANJUTNYA:\n";
echo "1. Cek website apakah gambar sudah muncul\n";
echo "2. Jika masih ada yang belum muncul, cek apakah file ada di public/uploads/\n";
echo "3. Upload gambar baru via admin untuk test\n";
