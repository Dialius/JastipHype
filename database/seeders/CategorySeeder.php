<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Premium accessories including Chrome Hearts, jewelry, belts, and more',
                'is_active' => true,
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Luxury clothing, shirts, jackets, and pants from top brands',
                'is_active' => true,
            ],
            [
                'name' => 'Hoodies',
                'slug' => 'hoodies',
                'description' => 'Premium hoodies and sweatshirts from streetwear brands',
                'is_active' => true,
            ],
            [
                'name' => 'Sneakers',
                'slug' => 'sneakers',
                'description' => 'Limited edition sneakers and exclusive footwear',
                'is_active' => true,
            ],
            [
                'name' => 'Limited Edition',
                'slug' => 'limited-edition',
                'description' => 'Exclusive and hard-to-find limited edition items',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
