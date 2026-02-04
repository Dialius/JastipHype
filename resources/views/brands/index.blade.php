@extends('layouts.app')

@section('title', 'All Brands - JastipHype')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4">Our Brands</h1>
        <p class="text-gray-600 text-lg">Explore exclusive drops from the world's most coveted streetwear and luxury brands</p>
    </div>

    <!-- Brands Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($brands as $brand)
            <a href="{{ route('products.index', ['brands' => [$brand->id]]) }}" 
               class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="aspect-square flex items-center justify-center p-8 bg-gray-50 group-hover:bg-gray-100 transition-colors">
                    <img src="{{ \App\Helpers\ImageHelper::getBrandLogoUrl($brand) }}" 
                         alt="{{ $brand->name }}" 
                         class="max-w-full max-h-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">
                </div>
                <div class="p-4 border-t border-gray-200">
                    <h3 class="font-semibold text-center group-hover:text-accent-gold transition-colors">
                        {{ $brand->name }}
                    </h3>
                    <p class="text-sm text-gray-500 text-center mt-1">
                        {{ $brand->products_count }} {{ Str::plural('product', $brand->products_count) }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>

    @if($brands->isEmpty())
        <div class="text-center py-12">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Brands Available</h3>
            <p class="text-gray-500">Check back soon for new brands!</p>
        </div>
    @endif

    <!-- Featured Brands Section -->
    @if($brands->isNotEmpty())
        <div class="mt-16 bg-gray-50 rounded-lg p-8">
            <h2 class="text-2xl font-semibold mb-6 text-center">Why Shop These Brands?</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-accent-gold text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">100% Authentic</h3>
                    <p class="text-sm text-gray-600">Every item is verified authentic with proof of purchase</p>
                </div>
                <div class="text-center">
                    <div class="bg-accent-gold text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Limited Editions</h3>
                    <p class="text-sm text-gray-600">Access to exclusive drops and hard-to-find pieces</p>
                </div>
                <div class="text-center">
                    <div class="bg-accent-gold text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Best Prices</h3>
                    <p class="text-sm text-gray-600">Competitive pricing on premium streetwear</p>
                </div>
            </div>
        </div>
    @endif

    <!-- CTA Section -->
    <div class="mt-12 bg-black text-white rounded-lg p-8 text-center">
        <h2 class="text-2xl font-semibold mb-4">Can't Find Your Favorite Brand?</h2>
        <p class="text-gray-300 mb-6">Let us know what you're looking for and we'll source it for you</p>
        <a href="{{ route('request.index') }}" 
           class="inline-block bg-accent-gold text-black px-8 py-3 rounded-lg hover:bg-yellow-500 transition-colors font-semibold">
            Request a Product
        </a>
    </div>
</div>
@endsection
