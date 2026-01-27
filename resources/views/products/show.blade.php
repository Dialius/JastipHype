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
            <div class="lg:col-span-6 space-y-6 lg:sticky lg:top-24 lg:self-start" x-data="productPage">
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
                                onclick="$notify('Please login to add to wishlist', 'info')"
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

                {{-- Voucher Trigger --}}
                <button 
                    type="button"
                    @click="showVoucherModal = true"
                    class="w-full flex items-center justify-between p-3 mb-4 bg-red-50 border border-red-100 rounded-lg group hover:bg-red-100 transition-colors"
                >
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        <span class="text-sm font-bold text-red-600">View Available Vouchers</span>
                    </div>
                    <svg class="w-4 h-4 text-red-400 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

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
                <div> {{-- Removed nested x-data to use global productPage state --}}
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
                    {{-- Voucher Modal Component --}}
                    <x-voucher-modal />
                    {{-- Delivery Modal Component --}}
                    <x-delivery-modal />
                </div>

                {{-- Size Selector (Chambre Style) --}}
                <div> {{-- Removed x-data here --}}
                    {{-- Size Label Only --}}
                    <label class="block text-sm font-bold text-black mb-4">
                        Size
                        <span x-show="shake" class="ml-2 text-red-500 text-xs font-normal transition-opacity" style="display: none;">
                            * Required
                        </span>
                    </label>
                    
                    {{-- Size Buttons Grid (Rounded Corners) --}}
                    <div class="grid grid-cols-4 gap-3 p-1 rounded-lg transition-colors duration-300"
                         :class="{ 'animate-shake ring-2 ring-red-500 ring-offset-2': shake }">
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
                                    @click="selectedSize = '{{ $size }}'; shake = false"
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

                <style>
                    @keyframes shake {
                        0%, 100% { transform: translateX(0); }
                        25% { transform: translateX(-5px); }
                        75% { transform: translateX(5px); }
                    }
                    .animate-shake {
                        animation: shake 0.3s ease-in-out;
                    }
                </style>

                {{-- Horizontal Divider --}}
                <hr class="my-6 border-gray-200">

                {{-- Quantity & Add to Cart --}}
                <form @submit.prevent="addToCart"> {{-- Ajax Form --}}
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="size" :value="selectedSize">
                    <input type="hidden" name="quantity" :value="quantity">
                    
                    <div class="space-y-4">
                        {{-- Quantity Selector (Centered) --}}
                        <div class="flex items-center justify-center gap-4">
                            <button 
                                type="button"
                                @click="quantity = Math.max(1, quantity - 1)"
                                class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>
                            
                            <div class="text-xl font-bold text-black min-w-[60px] text-center" x-text="quantity"></div>
                            
                            <button 
                                type="button"
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
                            type="submit"
                            @if($product->stock === 0) disabled @endif
                            class="w-full bg-white border-2 border-black text-black h-12 rounded-full uppercase text-sm font-bold hover:bg-black hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ $product->stock === 0 ? 'SOLD OUT' : 'Add to Cart' }}
                        </button>

                        {{-- Buy It Now Button (Rounded, Filled) --}}
                        <button 
                            type="button"
                            @if($product->stock === 0) disabled @endif
                            class="w-full bg-black text-white h-12 rounded-full uppercase text-sm font-bold hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Buy It Now
                        </button>
                    </div>
                </form>

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
                        <button 
                            @click="showDeliveryModal = true"
                            class="w-full flex items-center justify-between group text-left"
                        >
                            <h3 class="text-xs font-bold uppercase tracking-wide mb-0 group-hover:text-gray-600 transition-colors">Delivery & Returns</h3>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <div class="text-gray-600 text-sm mt-2">
                            <p class="line-clamp-2 text-xs">Estimated delivery: 3-5 business days. Free shipping on orders over Rp 2.000.000.</p>
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
            $notify('Please login to add items to wishlist', 'info');
            return;
        }

        if (data.success) {
            // Toggle heart icon fill state
            if (data.in_wishlist) {
                heartIcon.classList.add('fill-current', 'text-red-500');
                heartIcon.classList.remove('text-gray-700');
                $notify(data.message || 'Added to wishlist', 'success', {
                description: 'Item saved to your wishlist',
                duration: 3000
            });
            } else {
                heartIcon.classList.remove('fill-current', 'text-red-500');
                heartIcon.classList.add('text-gray-700');
                $notify(data.message || 'Removed from wishlist', 'info', {
                description: 'Item removed from your wishlist',
                duration: 3000
            });
            }
        } else {
            $notify(data.message || 'Something went wrong', 'error', {
                description: 'Please try again or contact support',
                duration: 4000
            });
        }
    } catch (error) {
        console.error('Wishlist error:', error);
        $notify('Failed to update wishlist', 'error', {
            description: 'Please check your connection and try again',
            duration: 3000
        });
    }
}

// Toast notifications are now handled by the global toastManager
// New improved toast system with better UX and animations
</script>


<style>
    [x-cloak] { display: none !important; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productPage', () => ({
        selectedSize: null,
        quantity: 1,
        shake: false,
        isAdding: false,
        showModal: false,
        showVoucherModal: false,
        showDeliveryModal: false,
        showSizeGuide: false,
        
        addToCart() {
            if (!this.selectedSize) {
                // Shake validation
                this.shake = true;
                setTimeout(() => this.shake = false, 500);
                
                $notify('Please select a size first!', 'error', { 
                    description: 'Choose your preferred size before adding to cart',
                    duration: 4000 
                });
                return;
            }
            
            this.isAdding = true;
            
            fetch('{{ route('cart.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    size: this.selectedSize,
                    quantity: this.quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Dispatch event to header
                    window.dispatchEvent(new CustomEvent('cart-updated', { 
                        detail: { count: data.cartCount } 
                    }));
                    
                    // Show modal
                    this.showModal = true;
                } else {
                    $notify(data.message || 'Something went wrong', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $notify('Failed to add to cart: ' + error.message, 'error');
            })
            .finally(() => {
                this.isAdding = false;
            });
        }
    }));
});
</script>

{{-- Add to Cart Success Modal --}}
<div x-data="{ show: false }" 
     x-show="show" 
     @cart-updated.window="show = true" 
     style="display: none;"
     class="fixed inset-0 z-[60] overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    {{-- Backdrop --}}
    <div x-show="show" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black/50 transition-opacity" 
         @click="show = false"></div>

    {{-- Modal Panel --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            
            {{-- Close Button --}}
            <div class="absolute right-4 top-4">
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 sm:p-8">
                {{-- Success Icon --}}
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>

                <div class="text-center">
                    <h3 class="text-xl font-bold leading-6 text-gray-900 mb-2" id="modal-title">Added to Bag!</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        <span class="font-semibold text-gray-900">{{ $product->name }}</span> has been added to your shopping cart.
                    </p>
                </div>

                {{-- Product Preview --}}
                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg mb-6 text-left">
                    @if($product->productImages->count() > 0)
                        <img src="{{ asset('storage/' . $product->productImages->first()->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="h-16 w-16 object-cover rounded-md border border-gray-200">
                    @else
                        <div class="h-16 w-16 bg-gray-200 rounded-md flex items-center justify-center text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $product->brand->name ?? 'Brand' }}</p>
                        <p class="text-sm text-gray-500">Price: Rp {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col gap-3">
                    <a href="{{ route('cart.index') }}" 
                       class="w-full inline-flex justify-center items-center rounded-md bg-black px-3 py-3 text-sm font-bold text-white shadow-sm hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                        VIEW BAG
                    </a>
                    <button type="button" 
                            @click="show = false" 
                            class="w-full inline-flex justify-center items-center rounded-md bg-white px-3 py-3 text-sm font-bold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        CONTINUE SHOPPING
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
