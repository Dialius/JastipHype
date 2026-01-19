<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\ReviewResponse;
use App\Models\ReviewImage;
use App\Models\Product;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test user if doesn't exist
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password')
            ]
        );

        // Get ALL products
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->error('No products found. Please run ProductSeeder first.');
            return;
        }

        // Sample review templates
        $reviewTemplates = [
            // 5-star reviews
            ['rating' => 5, 'title' => 'Absolutely love this!', 'comment' => 'This product exceeded my expectations. The quality is outstanding and it arrived exactly as described. Highly recommend to anyone looking for a premium product.'],
            ['rating' => 5, 'title' => 'Best purchase ever', 'comment' => 'I\'ve been using this for a month now and I\'m extremely satisfied. The attention to detail is remarkable and it feels very premium. Worth every penny!'],
            ['rating' => 5, 'title' => 'Exactly what I wanted', 'comment' => 'Perfect fit, perfect quality, perfect everything. This is exactly what I was looking for. The craftsmanship is top-notch and it feels very durable.'],
            ['rating' => 5, 'title' => 'Highly recommended!', 'comment' => 'This is a must-have item. The quality speaks for itself and it\'s clear that a lot of thought went into the design. Very pleased with my purchase.'],
            ['rating' => 5, 'title' => 'Amazing quality', 'comment' => 'The quality is exceptional and it looks even better in person than in the photos. Absolutely no regrets with this purchase. Five stars all the way!'],
            ['rating' => 5, 'title' => 'Love it!', 'comment' => 'Such a great find! The quality is superb and it\'s exactly as advertised. Fast shipping too! Will definitely be shopping here again.', 'has_response' => true],
            ['rating' => 5, 'title' => 'Produk original, kualitas mantap!', 'comment' => 'Barang sampai dengan aman, packaging rapi banget. Kualitas produk sesuai harga, material premium dan jahitan rapi. Puas banget sama pembelian ini!'],
            ['rating' => 5, 'title' => 'Premium quality!', 'comment' => 'You can really feel the quality of the materials used. Everything about this product screams premium - from the packaging to the product itself. 10/10 would recommend to friends.'],
            ['rating' => 5, 'title' => 'Authentic dan berkualitas tinggi', 'comment' => 'Setelah cek authenticity, produk ini 100% original. Kualitas bahan sangat bagus, jahitan rapi, dan detail finishing nya sempurna. Harga memang premium tapi sebanding dengan kualitasnya. Highly recommended!', 'has_response' => true],
            ['rating' => 5, 'title' => 'Perfect addition to my collection', 'comment' => 'This is a beautiful piece that complements my existing collection perfectly. The craftsmanship is evident in every detail. Couldn\'t be happier with this purchase!'],
            
            // 4-star reviews
            ['rating' => 4, 'title' => 'Great quality, minor issue', 'comment' => 'Overall very happy with this purchase. The quality is excellent and it looks amazing. Only giving 4 stars because delivery took a bit longer than expected, but the product itself is fantastic.'],
            ['rating' => 4, 'title' => 'Very good product', 'comment' => 'Really impressed with the quality and attention to detail. It\'s a solid product that feels premium. Would definitely purchase from this brand again.'],
            ['rating' => 4, 'title' => 'Bagus, tapi pengiriman agak lama', 'comment' => 'Produknya sendiri memuaskan, kualitas oke dan sesuai ekspektasi. Cuma agak kecewa karena pengiriman lebih lama dari estimasi. Tapi overall worth it sih untuk kualitas barangnya.', 'has_response' => true],
            ['rating' => 4, 'title' => 'Good value for money', 'comment' => 'Considering the price point, this is an excellent product. The quality is good, not perfect, but definitely above average. Would buy again if there\'s a sale.'],
            
            // 3-star reviews
            ['rating' => 3, 'title' => 'Decent but has room for improvement', 'comment' => 'The product is okay, meets basic expectations but nothing extraordinary. Quality is average for the price. Not bad, but not amazing either.'],
        ];

        // Create admin user for staff responses
        $admin = User::firstOrCreate(
            ['email' => 'admin@jastiphype.com'],
            [
                'name' => 'JastipHype Staff',
                'password' => bcrypt('password'),
            ]
        );

        // Create reviewer users (50 users to distribute across products)
        $reviewers = [];
        for ($i = 1; $i <= 50; $i++) {
            $reviewers[] = User::firstOrCreate(
                ['email' => "reviewer{$i}@example.com"],
                [
                    'name' => "Reviewer {$i}",
                    'password' => bcrypt('password')
                ]
            );
        }

        $totalReviews = 0;
        $totalImages = 0;

        // Add reviews to each product
        foreach ($products as $product) {
            // Each product gets 12-15 reviews (ensures pagination appears)
            $numReviews = rand(12, 15);
            
            // Shuffle reviewers and take the first N to ensure no duplicates
            $shuffledReviewers = $reviewers;
            shuffle($shuffledReviewers);
            $selectedReviewers = array_slice($shuffledReviewers, 0, $numReviews);
            
            foreach ($selectedReviewers as $reviewer) {
                // Pick random review template
                $template = $reviewTemplates[array_rand($reviewTemplates)];
                
                // Create review
                $review = Review::create([
                    'user_id' => $reviewer->id,
                    'product_id' => $product->id,
                    'rating' => $template['rating'],
                    'title' => $template['title'],
                    'comment' => $template['comment'],
                    'verified_purchase' => rand(0, 1) == 1,
                ]);

                // Add review images to 30% of reviews (2-4 images per review)
                if (rand(1, 100) <= 30) {
                    $numImages = rand(2, 4);
                    for ($j = 0; $j < $numImages; $j++) {
                        ReviewImage::create([
                            'review_id' => $review->id,
                            'image_path' => '/images/products/placeholder.jpg', // Placeholder image
                            'order' => $j,
                        ]);
                        $totalImages++;
                    }
                }

                // Add staff response to some reviews (30% chance)
                if (isset($template['has_response']) && $template['has_response'] && rand(1, 100) <= 30) {
                    ReviewResponse::create([
                        'review_id' => $review->id,
                        'user_id' => $admin->id,
                        'response' => 'Terimakasih telah belanja di JastipHype! Semoga suka barangnya, ditunggu next ordernya <3',
                    ]);
                }
                
                $totalReviews++;
            }
            
            // Update product rating and review count after adding all reviews
            $product->update([
                'rating' => $product->averageRating(),
                'review_count' => $product->reviewsCount(),
            ]);
        }

        $this->command->info('✓ Created ' . $totalReviews . ' reviews across ' . $products->count() . ' products!');
        $this->command->info('✓ Each product has ' . round(Review::count() / $products->count(), 1) . ' reviews on average');
        $this->command->info('✓ Added ' . ReviewResponse::count() . ' staff responses');
        $this->command->info('✓ Added ' . $totalImages . ' review images to ' . Review::has('images')->count() . ' reviews with photos');
        $this->command->info('✓ Updated product ratings to match review averages');
    }
}
