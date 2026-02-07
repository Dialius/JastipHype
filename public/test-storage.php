<?php
// Simple test to check if storage files can be accessed

// Get the first image file from storage
$storageDir = __DIR__ . '/../storage/app/public/products';
$files = array_diff(scandir($storageDir), ['.', '..', '.gitkeep']);
$firstFile = reset($files);

echo "<h1>Storage Test</h1>";
echo "<p>Testing storage file serving...</p>";

if ($firstFile) {
    $imageUrl = '/storage/products/' . $firstFile;
    echo "<h2>Test Image:</h2>";
    echo "<p>File: $firstFile</p>";
    echo "<p>URL: <a href='$imageUrl' target='_blank'>$imageUrl</a></p>";
    echo "<img src='$imageUrl' style='max-width: 500px; border: 2px solid #ccc; padding: 10px;' alt='Test Image'>";
    echo "<p>If you see the image above, storage serving is working!</p>";
} else {
    echo "<p style='color: red;'>No image files found in storage/app/public/products</p>";
}

echo "<hr>";
echo "<h2>Available Files:</h2>";
echo "<ul>";
foreach ($files as $file) {
    echo "<li>$file</li>";
}
echo "</ul>";
?>
