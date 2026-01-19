<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Create 3-4 images per product with different placeholder styles
            $imageCount = rand(3, 4);
            
            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => "https://placehold.co/800x1000/e2e2e2/000000?text=" . urlencode($product->name . " View " . ($i + 1)),
                    'order' => $i,
                    'is_primary' => $i === 0, // First image is primary
                ]);
            }
        }
    }
}
