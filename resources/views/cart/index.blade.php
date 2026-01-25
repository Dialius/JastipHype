@extends('layouts.app')

@section('content')
<div class="pt-32 pb-20 container mx-auto px-4" x-data="cartPage()">
    <h1 class="text-4xl font-bold mb-8 text-center" style="font-family: 'Montserrat', sans-serif;">SHOPPING CART</h1>

    @if($cartItems->count() > 0)
        <!-- Free Shipping Progress Bar -->
        <div class="max-w-2xl mx-auto mb-8">
            <div class="bg-gray-100 rounded-full h-2 mb-2 overflow-hidden">
                <div class="bg-black h-full transition-all duration-500 ease-out"
                     :style="`width: ${Object.keys(cartItems).length ? Math.min((subtotal / 500000) * 100, 100) : 0}%`"></div>
            </div>
            <p class="text-center text-sm font-medium">
                <span x-show="subtotal < 500000">
                    Spend <span class="text-black font-bold">Rp <span x-text="formatMoney(500000 - subtotal)"></span></span> more for Free Shipping!
                </span>
                <span x-show="subtotal >= 500000" class="text-green-600 flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    You've unlocked Free Shipping!
                </span>
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items List -->
            <div class="w-full lg:w-2/3">
                <div class="bg-secondary/20 rounded-xl p-6 border border-white/10 space-y-6">
                    @foreach($cartItems as $item)
                        <div class="flex flex-col sm:flex-row items-center gap-6 py-6 border-b border-gray-100 last:border-0 relative" 
                             id="cart-item-{{ $item->id }}"
                             x-data="{ itemQty: {{ $item->quantity }} }">
                            <!-- Loading Overlay -->
                            <div class="absolute inset-0 bg-white/50 z-10 hidden" id="loading-{{ $item->id }}">
                                <div class="h-full w-full flex items-center justify-center">
                                    <svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Product Image -->
                            <div class="w-full sm:w-24 h-24 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                                <img src="{{ $item->product->productImages->first() ? asset('storage/' . $item->product->productImages->first()->image_path) : asset('images/placeholder.jpg') }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-full h-full object-cover">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-grow text-center sm:text-left">
                                <h3 class="text-lg font-bold mb-1">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500 mb-1">
                                    Size: <span class="text-black font-semibold">{{ $item->size }}</span>
                                </p>
                                <p class="text-black font-bold">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        @click="updateQuantity({{ $item->id }}, itemQty - 1).then(s => { if(s) itemQty-- })"
                                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors disabled:opacity-50"
                                        :disabled="itemQty <= 1">
                                    -
                                </button>
                                
                                <span class="w-8 text-center font-semibold" x-text="itemQty"></span>
                                
                                <button type="button" 
                                        @click="updateQuantity({{ $item->id }}, itemQty + 1).then(s => { if(s) itemQty++ })"
                                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                                    +
                                </button>
                            </div>

                            <!-- Subtotal & Remove -->
                            <div class="flex flex-col items-end gap-2 min-w-[120px]">
                                <span class="font-bold text-lg" id="item-total-{{ $item->id }}">
                                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                </span>
                                <button type="button" 
                                        @click="removeItem({{ $item->id }})"
                                        class="text-red-500 hover:text-red-700 text-sm transition-colors flex items-center gap-1 group">
                                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-gray-50 rounded-xl p-8 border-2 border-black sticky top-32">
                    <h2 class="text-xl font-bold mb-6 pb-4 border-b border-gray-200 uppercase tracking-wide">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Subtotal</span>
                            <span class="text-black font-bold">Rp <span x-text="formatMoney(subtotal)"></span></span>
                        </div>
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Shipping</span>
                            <span class="text-xs italic text-gray-500">Calculated at checkout</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-8">
                        <div class="flex justify-between items-center text-xl font-bold text-black">
                            <span>Total</span>
                            <span>Rp <span x-text="formatMoney(subtotal)"></span></span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-right">Tax included. Shipping calculated at checkout.</p>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="block w-full bg-black text-white font-bold py-4 rounded-full text-center hover:bg-gray-800 transition-colors uppercase tracking-wider shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Proceed to Checkout
                    </a>
                    
                    <a href="{{ route('products.index') }}" class="block w-full text-center text-gray-500 hover:text-black mt-4 text-sm underline transition-colors">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>

        <!-- You Might Also Like -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mt-20">
            <h2 class="text-2xl font-bold mb-8 uppercase tracking-wider text-center">You Might Also Like</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <div class="group cursor-pointer">
                        <div class="relative overflow-hidden mb-4 rounded-lg bg-gray-100 aspect-[3/4]">
                            <img src="{{ $related->productImages->first() ? asset('storage/' . $related->productImages->first()->image_path) : asset('images/placeholder.jpg') }}" 
                                 class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-105" 
                                 alt="{{ $related->name }}">
                            
                            {{-- Add to Cart Overlay (Simplified) --}}
                            <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <a href="{{ route('products.show', $related->slug) }}" class="block w-full bg-white text-black text-center py-3 text-sm font-bold uppercase hover:bg-black hover:text-white transition-colors">
                                    View Product
                                </a>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold uppercase truncate">{{ $related->name }}</h3>
                        <p class="text-gray-500 text-sm">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    @else
        <div class="min-h-[50vh] flex items-center justify-center">
            <div class="text-center">
                <div class="mb-6 text-gray-300">
                    <svg class="w-16 h-16 mx-auto stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold mb-3 uppercase tracking-wider">Your Cart is Empty</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Looks like you haven't added any items yet.</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-black text-white font-bold text-sm uppercase px-8 py-3 rounded-full hover:bg-gray-800 transition-colors tracking-wide">
                    Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Mobile Sticky Summary -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-40"
     x-data="{ subtotal: {{ $subtotal ?? 0 }} }"
     @cart-updated.window="subtotal = $event.detail.subtotal">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs text-gray-500 uppercase">Total</p>
            <p class="text-lg font-bold">Rp <span x-text="new Intl.NumberFormat('id-ID').format(subtotal)"></span></p>
        </div>
        <a href="{{ route('checkout.index') }}" 
           class="flex-1 bg-black text-white font-bold h-12 rounded-full uppercase text-sm flex items-center justify-center hover:bg-gray-800 transition-colors">
            Checkout
        </a>
    </div>
</div>

<script>
function cartPage() {
    return {
        subtotal: {{ $subtotal ?? 0 }},
        
        formatMoney(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        },

        updateQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return Promise.resolve(false);

            // Show loading
            const loadingEl = document.getElementById(`loading-${itemId}`);
            if(loadingEl) loadingEl.classList.remove('hidden');

            return fetch(`/cart/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: newQuantity })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Update DOM directly for totals
                    const itemTotalEl = document.getElementById(`item-total-${itemId}`);
                    
                    if(itemTotalEl) itemTotalEl.innerText = 'Rp ' + this.formatMoney(data.item_total);
                    
                    // Update global state
                    this.subtotal = data.subtotal;
                    
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                    return true;
                }
                return false;
            })
            .catch(err => {
                console.error(err);
                return false;
            })
            .finally(() => {
                if(loadingEl) loadingEl.classList.add('hidden');
            });
        },
        
        removeItem(itemId) {
            if(!confirm('Are you sure you want to remove this item?')) return;
            
            fetch(`/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    if(data.empty) {
                        window.location.reload();
                    } else {
                        // animate remove
                        const el = document.getElementById(`cart-item-${itemId}`);
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 300);
                        this.subtotal = data.subtotal;
                        window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                    }
                }
            });
        }
    }
}
</script>
@endsection
