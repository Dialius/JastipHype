@extends('layouts.app')

@section('content')
<div class="pt-32 pb-20 container mx-auto px-4" x-data="cartPage()">
    <h1 class="text-4xl font-bold mb-8 text-center" style="font-family: 'Montserrat', sans-serif;">SHOPPING CART</h1>

    @if($cartItems->count() > 0)

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items List -->
            <div class="w-full lg:w-2/3 space-y-4">
                @foreach($cartItems as $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow relative" 
                         id="cart-item-{{ $item->id }}"
                         x-data="{ itemQty: {{ $item->quantity }} }">
                        <!-- Loading Overlay -->
                        <div class="absolute inset-0 bg-white/80 backdrop-blur-sm z-10 rounded-2xl hidden" id="loading-{{ $item->id }}">
                            <div class="h-full w-full flex items-center justify-center">
                                <svg class="animate-spin h-8 w-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-6">
                            <!-- Product Image -->
                            <div class="w-full sm:w-32 h-32 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden">
                                <img src="{{ $item->product->productImages->first() ? asset('storage/' . $item->product->productImages->first()->image_path) : asset('images/placeholder.jpg') }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-grow flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-500">
                                                {{ $item->product->brand->name ?? 'Brand' }}
                                            </p>
                                        </div>
                                        <button type="button" 
                                                @click="removeItem({{ $item->id }})"
                                                class="text-gray-400 hover:text-red-500 transition-colors p-2 hover:bg-red-50 rounded-lg group">
                                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="px-3 py-1 bg-gray-100 rounded-full text-gray-700 font-medium">
                                            Size: {{ $item->size }}
                                        </span>
                                        <span class="text-gray-500">
                                            Rp {{ number_format($item->product->price, 0, ',', '.') }} each
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-4">
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center gap-3 bg-gray-50 rounded-full px-2 py-1">
                                        <button type="button" 
                                                @click="updateQuantity({{ $item->id }}, itemQty - 1).then(s => { if(s) itemQty-- })"
                                                class="w-8 h-8 rounded-full bg-white hover:bg-gray-200 flex items-center justify-center transition-colors disabled:opacity-50 shadow-sm"
                                                :disabled="itemQty <= 1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        </button>
                                        
                                        <span class="w-10 text-center font-bold text-gray-900" x-text="itemQty"></span>
                                        
                                        <button type="button" 
                                                @click="updateQuantity({{ $item->id }}, itemQty + 1).then(s => { if(s) itemQty++ })"
                                                class="w-8 h-8 rounded-full bg-white hover:bg-gray-200 flex items-center justify-center transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 mb-1">Subtotal</p>
                                        <p class="font-bold text-xl text-gray-900" id="item-total-{{ $item->id }}">
                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 sticky top-32">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6 pb-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span class="text-gray-900 font-semibold">Rp <span x-text="formatMoney(subtotal)"></span></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-sm text-gray-500">Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tax</span>
                            <span class="text-sm text-gray-500">Included</span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-gray-900">Rp <span x-text="formatMoney(subtotal)"></span></span>
                        </div>
                        <p class="text-xs text-gray-500 text-right">Final price calculated at checkout</p>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="block w-full bg-black text-white font-bold py-4 rounded-full text-center hover:bg-gray-800 transition-all uppercase tracking-wider shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 mb-4">
                        Proceed to Checkout
                    </a>
                    
                    <a href="{{ route('products.index') }}" class="block w-full text-center text-gray-600 hover:text-black text-sm font-medium transition-colors">
                        ← Continue Shopping
                    </a>

                    <!-- Trust Badges -->
                    <div class="mt-8 pt-6 border-t border-gray-200 space-y-4">
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Secure Checkout</p>
                                <p class="text-xs text-gray-500">SSL encrypted payment</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Multiple Payment</p>
                                <p class="text-xs text-gray-500">Bank, E-wallet & more</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Easy Returns</p>
                                <p class="text-xs text-gray-500">30 days return policy</p>
                            </div>
                        </div>
                    </div>
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
