<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get New Categories
        $accessories = Category::where('slug', 'accessories')->first()->id;
        $clothing = Category::where('slug', 'clothing')->first()->id;
        $hoodies = Category::where('slug', 'hoodies')->first()->id;
        $sneakers = Category::where('slug', 'sneakers')->first()->id;
        $limitedEdition = Category::where('slug', 'limited-edition')->first()->id;

        // Get Brands
        $chromeHearts = Brand::where('slug', 'chrome-hearts')->first()->id;
        $supreme = Brand::where('slug', 'supreme')->first()->id;
        $offwhite = Brand::where('slug', 'off-white')->first()->id;
        $balenciaga = Brand::where('slug', 'balenciaga')->first()->id;
        $fearOfGod = Brand::where('slug', 'fear-of-god')->first()->id;
        $essentials = Brand::where('slug', 'essentials')->first()->id;
        $nike = Brand::where('slug', 'nike')->first()->id;
        $jordan = Brand::where('slug', 'jordan')->first()->id;
        $yeezy = Brand::where('slug', 'yeezy')->first()->id;
        $gucci = Brand::where('slug', 'gucci')->first()->id;
        $lv = Brand::where('slug', 'louis-vuitton')->first()->id;

        $products = [
            // Chrome Hearts Accessories
            [
                'category_id' => $accessories,
                'brand_id' => $chromeHearts,
                'name' => 'Chrome Hearts Cross Pendant Necklace',
                'slug' => 'chrome-hearts-cross-pendant',
                'description' => 'Authentic Chrome Hearts sterling silver cross pendant necklace',
                'price' => 8500000,
                'rating' => 4.9,
                'review_count' => 53,
                'sku' => 'CH-NECK-001',
                'stock' => 3,
                'weight' => 150,
                'is_limited_edition' => true,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/1a1a1a/FFF?text=Chrome+Hearts+Necklace',
                'images' => json_encode([
                    'https://placehold.co/600x800/1a1a1a/FFF?text=Chrome+Hearts+Necklace',
                    'https://placehold.co/600x800/1a1a1a/FFF?text=Detail+View',
                ]),
                'sizes' => json_encode(['One Size']),
                'colors' => json_encode(['Silver']),
            ],
            [
                'category_id' => $accessories,
                'brand_id' => $chromeHearts,
                'name' => 'Chrome Hearts Dagger Ring',
                'slug' => 'chrome-hearts-dagger-ring',
                'description' => 'Sterling silver dagger ring with intricate detailing',
                'price' => 6200000,
                'rating' => 4.8,
                'review_count' => 42,
                'sku' => 'CH-RING-001',
                'stock' => 5,
                'is_limited_edition' => false,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/2a2a2a/FFF?text=Chrome+Hearts+Ring',
                'images' => json_encode([
                    'https://placehold.co/600x800/2a2a2a/FFF?text=Chrome+Hearts+Ring',
                ]),
                'sizes' => json_encode(['7', '8', '9', '10', '11']),
                'colors' => json_encode(['Silver']),
            ],

            // Supreme Hoodies
            [
                'category_id' => $hoodies,
                'brand_id' => $supreme,
                'name' => 'Supreme Box Logo Hoodie Black',
                'slug' => 'supreme-box-logo-hoodie-black',
                'description' => 'Classic Supreme Box Logo hoodie in black colorway',
                'price' => 12500000,
                'rating' => 5.0,
                'review_count' => 127,
                'sku' => 'SUP-HOOD-001',
                'stock' => 2,
                'is_limited_edition' => true,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/000000/FFF?text=Supreme+Box+Logo',
                'images' => json_encode([
                    'https://placehold.co/600x800/000000/FFF?text=Supreme+Box+Logo',
                    'https://placehold.co/600x800/000000/FFF?text=Back+View',
                ]),
                'sizes' => json_encode(['M', 'L', 'XL']),
                'colors' => json_encode(['Black']),
            ],
            [
                'category_id' => $hoodies,
                'brand_id' => $supreme,
                'name' => 'Supreme x The North Face Mountain Hoodie',
                'slug' => 'supreme-tnf-mountain-hoodie',
                'description' => 'Supreme x The North Face collaboration mountain hoodie',
                'price' => 15000000,
                'rating' => 4.9,
                'review_count' => 89,
                'sku' => 'SUP-HOOD-002',
                'stock' => 1,
                'is_limited_edition' => true,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/FF0000/FFF?text=Supreme+x+TNF',
                'images' => json_encode([
                    'https://placehold.co/600x800/FF0000/FFF?text=Supreme+x+TNF',
                ]),
                'sizes' => json_encode(['M', 'L', 'XL']),
                'colors' => json_encode(['Red', 'Black']),
            ],

            // Off-White Clothing
            [
                'category_id' => $clothing,
                'brand_id' => $offwhite,
                'name' => 'Off-White Diagonal Arrows T-Shirt',
                'slug' => 'off-white-diagonal-arrows-tee',
                'description' => 'Iconic Off-White diagonal arrows graphic tee',
                'price' => 4500000,
                'rating' => 4.7,
                'review_count' => 64,
                'sku' => 'OW-TEE-001',
                'stock' => 8,
                'is_limited_edition' => false,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/FFFFFF/000?text=Off-White+Tee',
                'images' => json_encode([
                    'https://placehold.co/600x800/FFFFFF/000?text=Off-White+Tee',
                ]),
                'sizes' => json_encode(['S', 'M', 'L', 'XL']),
                'colors' => json_encode(['White', 'Black']),
            ],

            // Fear of God Essentials
            [
                'category_id' => $hoodies,
                'brand_id' => $essentials,
                'name' => 'Essentials Fear of God Hoodie Cream',
                'slug' => 'essentials-fog-hoodie-cream',
                'description' => 'Fear of God Essentials oversized hoodie in cream',
                'price' => 3200000,
                'rating' => 4.6,
                'review_count' => 156,
                'sku' => 'ESS-HOOD-001',
                'stock' => 12,
                'is_limited_edition' => false,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/F5F5DC/000?text=Essentials+Hoodie',
                'images' => json_encode([
                    'https://placehold.co/600x800/F5F5DC/000?text=Essentials+Hoodie',
                ]),
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
                'colors' => json_encode(['Cream', 'Black', 'Grey']),
            ],

            // Sneakers
            [
                'category_id' => $sneakers,
                'brand_id' => $nike,
                'name' => 'Nike Air Force 1 Low White',
                'slug' => 'nike-af1-low-white',
                'description' => 'Classic Nike Air Force 1 in all white leather',
                'price' => 1850000,
                'rating' => 4.8,
                'review_count' => 243,
                'sku' => 'NIKE-AF1-001',
                'stock' => 15,
                'is_limited_edition' => false,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/FFFFFF/000?text=Nike+AF1+White',
                'images' => json_encode([
                    'https://placehold.co/600x800/FFFFFF/000?text=Nike+AF1+White',
                ]),
                'sizes' => json_encode(['US 7', 'US 8', 'US 9', 'US 10', 'US 11', 'US 12']),
                'colors' => json_encode(['White']),
            ],
            [
                'category_id' => $sneakers,
                'brand_id' => $jordan,
                'name' => 'Air Jordan 1 Retro High OG Chicago',
                'slug' => 'jordan-1-chicago',
                'description' => 'Air Jordan 1 Retro High OG in iconic Chicago colorway',
                'price' => 9500000,
                'rating' => 5.0,
                'review_count' => 312,
                'sku' => 'AJ1-CHI-001',
                'stock' => 3,
                'is_limited_edition' => true,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/FF0000/FFF?text=Jordan+1+Chicago',
                'images' => json_encode([
                    'https://placehold.co/600x800/FF0000/FFF?text=Jordan+1+Chicago',
                ]),
                'sizes' => json_encode(['US 8', 'US 9', 'US 10', 'US 11']),
                'colors' => json_encode(['Red', 'White', 'Black']),
            ],
            [
                'category_id' => $sneakers,
                'brand_id' => $yeezy,
                'name' => 'Adidas Yeezy Boost 350 V2 Zebra',
                'slug' => 'yeezy-350-zebra',
                'description' => 'Yeezy Boost 350 V2 in Zebra colorway with primeknit upper',
                'price' => 6800000,
                'rating' => 4.7,
                'review_count' => 198,
                'sku' => 'YZY-350-001',
                'stock' => 5,
                'is_limited_edition' => true,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/FFFFFF/000?text=Yeezy+Zebra',
                'images' => json_encode([
                    'https://placehold.co/600x800/FFFFFF/000?text=Yeezy+Zebra',
                ]),
                'sizes' => json_encode(['US 8', 'US 9', 'US 10', 'US 11', 'US 12']),
                'colors' => json_encode(['White', 'Black']),
            ],

            // Gucci Accessories
            [
                'category_id' => $accessories,
                'brand_id' => $gucci,
                'name' => 'Gucci GG Marmont Belt',
                'slug' => 'gucci-gg-marmont-belt',
                'description' => 'Authentic Gucci GG Marmont leather belt with gold buckle',
                'price' => 7500000,
                'rating' => 4.9,
                'review_count' => 76,
                'sku' => 'GUC-BELT-001',
                'stock' => 6,
                'is_limited_edition' => false,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/000000/FFD700?text=Gucci+Belt',
                'images' => json_encode([
                    'https://placehold.co/600x800/000000/FFD700?text=Gucci+Belt',
                ]),
                'sizes' => json_encode(['75', '80', '85', '90', '95']),
                'colors' => json_encode(['Black', 'Brown']),
            ],

            // Balenciaga
            [
                'category_id' => $clothing,
                'brand_id' => $balenciaga,
                'name' => 'Balenciaga Triple S Sneaker',
                'slug' => 'balenciaga-triple-s',
                'description' => 'Balenciaga Triple S chunky sneaker in grey colorway',
                'price' => 11500000,
                'rating' => 4.5,
                'review_count' => 91,
                'sku' => 'BAL-SNEAK-001',
                'stock' => 4,
                'is_limited_edition' => false,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/808080/FFF?text=Balenciaga+Triple+S',
                'images' => json_encode([
                    'https://placehold.co/600x800/808080/FFF?text=Balenciaga+Triple+S',
                ]),
                'sizes' => json_encode(['US 7', 'US 8', 'US 9', 'US 10', 'US 11']),
                'colors' => json_encode(['Grey', 'Black', 'White']),
            ],

            // Limited Edition
            [
                'category_id' => $limitedEdition,
                'brand_id' => $lv,
                'name' => 'Louis Vuitton x Supreme Box Logo Hoodie',
                'slug' => 'lv-supreme-box-logo',
                'description' => 'Ultra rare Louis Vuitton x Supreme collaboration hoodie',
                'price' => 35000000,
                'rating' => 5.0,
                'review_count' => 28,
                'sku' => 'LV-SUP-001',
                'stock' => 1,
                'is_limited_edition' => true,
                'is_featured' => true,
                'is_active' => true,
                'image' => 'https://placehold.co/600x800/8B4513/FFD700?text=LV+x+Supreme',
                'images' => json_encode([
                    'https://placehold.co/600x800/8B4513/FFD700?text=LV+x+Supreme',
                ]),
                'sizes' => json_encode(['M', 'L']),
                'colors' => json_encode(['Brown', 'Red']),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
