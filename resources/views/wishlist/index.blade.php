@extends('layouts.app')

@section('title', 'My Wishlist - JastipHype')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-black mb-2">My Wishlist</h1>
            <p class="text-gray-600">{{ $wishlists->count() }} {{ Str::plural('item', $wishlists->count()) }}</p>
        </div>

        @if($wishlists->count() > 0)
            {{-- Wishlist Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlists as $wishlist)
                    <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        {{-- Product Image --}}
                        <a href="{{ route('products.show', $wishlist->product->slug) }}" class="block aspect-[3/4] bg-gray-100">
                            <img 
                                src="{{ product_image_url($wishlist->product) }}" 
                                alt="{{ $wishlist->product->name }}"
                                class="w-full h-full object-cover"
                            >
                                    class="w-full h-full object-cover"
                                >
                            @endif
                        </a>

                        {{-- Product Info --}}
                        <div class="p-4">
                            <a href="{{ route('products.show', $wishlist->product->slug) }}" class="block">
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">
                                    {{ $wishlist->product->brand->name }}
                                </p>
                                <h3 class="font-bold text-sm text-black mb-2 line-clamp-2">
                                    {{ $wishlist->product->name }}
                                </h3>
                                <p class="text-lg font-bold text-black">
                                    Rp {{ number_format($wishlist->product->final_price, 0, ',', '.') }}
                                </p>
                            </a>

                            {{-- Action Buttons --}}
                            <div class="mt-4 flex gap-2">
                                <button 
                                    onclick="toggleWishlist({{ $wishlist->product->id }})"
                                    class="flex-1 bg-white border-2 border-black text-black py-2 px-4 rounded-full text-sm font-bold hover:bg-black hover:text-white transition-colors"
                                >
                                    Remove
                                </button>
                                <a 
                                    href="{{ route('products.show', $wishlist->product->slug) }}"
                                    class="flex-1 bg-black text-white py-2 px-4 rounded-full text-sm font-bold hover:bg-gray-800 transition-colors text-center"
                                >
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Your wishlist is empty</h2>
                <p class="text-gray-600 mb-6">Add items you love to your wishlist!</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-black text-white px-8 py-3 rounded-full font-bold hover:bg-gray-800 transition-colors">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Wishlist Toggle Script (Shared) --}}
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

        if (data.success) {
            // Reload page if on wishlist page and item was removed
            if (window.location.pathname === '/wishlist' && !data.in_wishlist) {
                window.location.reload();
            } else if (heartIcon) {
                // Toggle heart icon on product page
                if (data.in_wishlist) {
                    heartIcon.classList.add('fill-current', 'text-red-500');
                    heartIcon.classList.remove('text-gray-700');
                } else {
                    heartIcon.classList.remove('fill-current', 'text-red-500');
                    heartIcon.classList.add('text-gray-700');
                }
            }
            
            showToast(data.message || 'Wishlist updated', 'success', {
            description: 'Your wishlist has been updated successfully',
            duration: 3000
        });
        }
    } catch (error) {
        console.error('Wishlist error:', error);
        showToast('Failed to update wishlist', 'error', {
            description: 'Please check your connection and try again',
            duration: 3000
        });
    }
}

// Toast notifications are now handled by the global toastManager
// Improved toast system with better UX and animations
</script>
@endsection
