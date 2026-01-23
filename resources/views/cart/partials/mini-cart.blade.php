@if($cartItems->count() > 0)
    <div class="max-h-80 overflow-y-auto scrollbar-hide p-4 space-y-4">
        @foreach($cartItems as $item)
            <div class="flex items-start gap-3">
                <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden">
                    @if($item->product->productImages->first())
                        <img src="{{ asset('storage/' . $item->product->productImages->first()->image_path) }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</h4>
                    <p class="text-xs text-gray-500">{{ $item->product->brand->name ?? '' }}</p>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-xs text-gray-500">Size: {{ $item->size }}</p>
                        <p class="text-xs text-gray-900 font-medium">x{{ $item->quantity }}</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900 mt-1">
                        Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @if(!$loop->last) <hr class="border-gray-100"> @endif
        @endforeach
    </div>
    
    <div class="p-4 bg-gray-50 border-t border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-gray-900">Subtotal</span>
            <span class="text-lg font-bold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="space-y-2">
            <a href="{{ route('cart.index') }}" class="block w-full text-center px-4 py-2 border border-black text-black text-sm font-medium rounded-md hover:bg-gray-50 transition-colors">
                View Cart
            </a>
            {{-- Checkout button will go here later --}}
            <a href="{{ route('checkout.index') }}" class="block w-full text-center px-4 py-2 bg-black text-white text-sm font-medium rounded-md hover:bg-gray-800 transition-colors">
                Checkout
            </a>
        </div>
    </div>
@else
    <div class="p-8 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        <p class="mt-4 text-sm font-medium text-gray-900">Your cart is empty</p>
        <p class="mt-1 text-xs text-gray-500">Time to start shopping!</p>
    </div>
@endif
