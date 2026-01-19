@extends('layouts.app')

@section('title', $product->name . ' - JastipHype')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Product Detail Section -->

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            <!-- Product Gallery (Left - 50%) -->
            <div class="lg:col-span-6 space-y-4">
                <x-product-gallery :product="$product" />
            </div>

            <!-- Product Info (Right - 50%, Sticky) -->
            <div class="lg:col-span-6 space-y-6 lg:sticky lg:top-24 lg:self-start">
                <!-- Header -->
                <div class="space-y-4">
                    <div class="text-xs uppercase tracking-[2px] text-gray-500 font-medium">
                        {{ $product->brand->name }}
                    </div>
                    
                    <h1 class="text-3xl lg:text-4xl font-bold text-black uppercase leading-tight">
                        {{ $product->name }}
                    </h1>

                    {{-- Rating Display (Chambre Style) --}}
                    @if($product->rating && $product->review_count)
                        <div class="flex items-center gap-2">
                            {{-- Star Icons --}}
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= floor($product->rating) ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" 
                                         fill="{{ $i <= floor($product->rating) ? 'currentColor' : 'none' }}" 
                                         stroke="currentColor" 
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                @endfor
                            </div>
                            {{-- Rating Text --}}
                            <span class="text-sm text-gray-700">
                                {{ number_format($product->rating, 1) }} ({{ $product->review_count }})</span>
                        </div>
                    @endif

                    {{-- Price + Wishlist Heart --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-baseline space-x-3">
                            <div class="text-2xl font-bold text-black">
                                Rp {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}
                            </div>
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <div class="text-lg text-gray-400 line-through">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                        
                        {{-- Wishlist Heart Icon --}}
                        <button 
                            type="button"
                            @auth
                                onclick="toggleWishlist({{ $product->id }})"
                            @else
                                onclick="alert('Please login to add to wishlist')"
                            @endauth
                            class="p-2 hover:bg-gray-100 rounded-full transition-colors"
                            aria-label="Add to wishlist"
                        >
                            <svg class="w-6 h-6 text-gray-700 hover:text-red-500 transition-colors wishlist-heart" data-product-id="{{ $product->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Quantity Information Box (Chambre Style) --}}
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-black mb-1">Quantity Information</h4>
                            <p class="text-xs text-gray-500">Maximum Quantity:</p>
                        </div>
                        <div class="text-2xl font-bold text-black">
                            {{ $product->stock ?? 100 }}
                        </div>
                    </div>
                </div>

                {{-- Size Guide Box (Like Quantity Information) --}}
                <div x-data="{ showSizeGuide: false }">
                    <button 
                        @click="showSizeGuide = true"
                        type="button"
                        class="w-full border border-gray-200 rounded-lg p-4 bg-gray-50 hover:bg-gray-100 transition-colors"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-black">Size Guide</span>
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </button>
                    
                    {{-- Size Guide Modal Component --}}
                    <x-size-guide-modal :product="$product" />
                </div>

                {{-- Size Selector (Chambre Style) --}}
                <div x-data="{ selectedSize: null }">
                    {{-- Size Label Only --}}
                    <label class="block text-sm font-bold text-black mb-4">
                        Size
                    </label>
                    
                    {{-- Size Buttons Grid (Rounded Corners) --}}
                    <div class="grid grid-cols-4 gap-3">
                        @php
                            // Use product sizes if available, otherwise use default
                            $availableSizes = $product->sizes && is_array($product->sizes) && count($product->sizes) > 0 
                                ? $product->sizes 
                                : ['S', 'M', 'L', 'XL'];
                            
                            // Dummy stock data per size (in future, this should come from inventory table)
                            // For demo: S and XL available, M and L sold out
                            $sizeStock = [
                                'S' => 10,
                                'M' => 0,  // Sold out
                                'L' => 0,  // Sold out
                                'XL' => 5,
                            ];
                        @endphp
                        
                        @foreach($availableSizes as $size)
                            @php
                                $isOutOfStock = isset($sizeStock[$size]) && $sizeStock[$size] === 0;
                            @endphp
                            <button
                                type="button"
                                @if(!$isOutOfStock)
                                    @click="selectedSize = '{{ $size }}'"
                                @endif
                                {{ $isOutOfStock ? 'disabled' : '' }}
                                :class="selectedSize === '{{ $size }}' ? 'bg-black text-white border-black' : 'bg-white text-black border-gray-200 {{ !$isOutOfStock ? 'hover:border-black' : '' }}'"
                                class="relative h-12 border-2 rounded-md text-sm font-bold uppercase tracking-wide transition-all duration-200 focus:outline-none overflow-hidden
                                       {{ $isOutOfStock ? 'opacity-40 cursor-not-allowed' : 'focus:ring-2 focus:ring-black focus:ring-offset-2' }}"
                            >
                                {{ $size }}
                                @if($isOutOfStock)
                                    {{-- Diagonal strikethrough line --}}
                                    <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <span class="absolute w-[140%] h-[2px] bg-gray-400 transform rotate-[-18deg] origin-center scale-x-75"></span>
                                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Horizontal Divider --}}
                <hr class="my-6 border-gray-200">

                {{-- Quantity & Add to Cart --}}
                <div x-data="{ quantity: 1 }" class="space-y-4">
                    {{-- Quantity Selector (Centered) --}}
                    <div class="flex items-center justify-center gap-4">
                        <button 
                            @click="quantity = Math.max(1, quantity - 1)"
                            class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>
                        
                        <div class="text-xl font-bold text-black min-w-[60px] text-center" x-text="quantity"></div>
                        
                        <button 
                            @click="quantity = Math.min({{ $product->stock ?? 100 }}, quantity + 1)"
                            class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors bg-gray-200"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    {{-- Add to Cart Button (Rounded, Outline) --}}
                    <button 
                        @if($product->stock === 0) disabled @endif
                        class="w-full bg-white border-2 border-black text-black h-12 rounded-full uppercase text-sm font-bold hover:bg-black hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ $product->stock === 0 ? 'SOLD OUT' : 'Add to Cart' }}
                    </button>

                    {{-- Buy It Now Button (Rounded, Filled) --}}
                    <button 
                        @if($product->stock === 0) disabled @endif
                        class="w-full bg-black text-white h-12 rounded-full uppercase text-sm font-bold hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Buy It Now
                    </button>
                </div>

                <!-- Product Details Accordion/Stack -->
                <div class="pt-8 border-t border-gray-200 space-y-6">
                    {{-- Shipping Calculator --}}
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wide mb-3">Shipping</h3>
                        <x-shipping-calculator :product="$product" />
                    </div>

                    <div class="prose prose-sm max-w-none">
                        <h3 class="text-xs font-bold uppercase tracking-wide mb-2">Description</h3>
                        <div class="text-gray-600 leading-relaxed">
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>

                    <div class="prose prose-sm max-w-none">
                        <h3 class="text-xs font-bold uppercase tracking-wide mb-2">Delivery & Returns</h3>
                        <div class="text-gray-600 text-sm space-y-1">
                            <p>Estimated delivery: 3-5 business days.</p>
                            <p>Free shipping on orders over Rp 2.000.000.</p>
                            <p>30-day return policy for unworn items.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-product-reviews :product="$product" />
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <x-related-products :products="$relatedProducts" />
    @endif
</div>

<!-- Mobile Sticky Add to Cart -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 z-40">
    <button 
        @if($product->stock === 0) disabled @endif
        class="w-full bg-black text-white h-12 rounded-full uppercase text-sm font-bold hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
    >
        {{ $product->stock === 0 ? 'SOLD OUT' : 'ADD TO CART' }}
    </button>
</div>

{{-- Wishlist Toggle Script (Full AJAX Implementation) --}}
<script>
async function toggleWishlist(productId) {
    const heartIcon = document.querySelector(`.wishlist-heart[data-product-id="${productId}"]`);
    
    try {
        const response = await fetch(`/wishlist/${productId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.status === 401) {
            // Not authenticated - redirect to login or show message
            alert('Please login to add items to wishlist');
            return;
        }

        if (data.success) {
            // Toggle heart icon fill state
            if (data.in_wishlist) {
                heartIcon.classList.add('fill-current', 'text-red-500');
                heartIcon.classList.remove('text-gray-700');
                showToast(data.message || 'Added to wishlist', 'success');
            } else {
                heartIcon.classList.remove('fill-current', 'text-red-500');
                heartIcon.classList.add('text-gray-700');
                showToast(data.message || 'Removed from wishlist', 'info');
            }
        } else {
            showToast(data.message || 'Something went wrong', 'error');
        }
    } catch (error) {
        console.error('Wishlist error:', error);
        showToast('Failed to update wishlist', 'error');
    }
}

// Simple toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white text-sm font-medium z-[999999] ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-gray-800'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endsection
