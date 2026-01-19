<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            // Luxury Accessories
            ['name' => 'Chrome Hearts', 'slug' => 'chrome-hearts', 'is_active' => true],
            ['name' => 'Cartier', 'slug' => 'cartier', 'is_active' => true],
            ['name' => 'Tiffany & Co', 'slug' => 'tiffany-co', 'is_active' => true],
            ['name' => 'Hermès', 'slug' => 'hermes', 'is_active' => true],
            
            // Streetwear & Luxury Fashion
            ['name' => 'Supreme', 'slug' => 'supreme', 'is_active' => true],
            ['name' => 'Off-White', 'slug' => 'off-white', 'is_active' => true],
            ['name' => 'Balenciaga', 'slug' => 'balenciaga', 'is_active' => true],
            ['name' => 'Fear of God', 'slug' => 'fear-of-god', 'is_active' => true],
            ['name' => 'Essentials', 'slug' => 'essentials', 'is_active' => true],
            ['name' => 'Stüssy', 'slug' => 'stussy', 'is_active' => true],
            ['name' => 'BAPE', 'slug' => 'bape', 'is_active' => true],
            
            // Sneakers
            ['name' => 'Nike', 'slug' => 'nike', 'is_active' => true],
            ['name' => 'Adidas', 'slug' => 'adidas', 'is_active' => true],
            ['name' => 'Jordan', 'slug' => 'jordan', 'is_active' => true],
            ['name' => 'Yeezy', 'slug' => 'yeezy', 'is_active' => true],
            ['name' => 'New Balance', 'slug' => 'new-balance', 'is_active' => true],
            
            // Designer Brands
            ['name' => 'Gucci', 'slug' => 'gucci', 'is_active' => true],
            ['name' => 'Louis Vuitton', 'slug' => 'louis-vuitton', 'is_active' => true],
            ['name' => 'Prada', 'slug' => 'prada', 'is_active' => true],
            ['name' => 'Dior', 'slug' => 'dior', 'is_active' => true],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
