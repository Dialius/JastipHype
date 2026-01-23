@extends('layouts.app')

@section('content')
<div class="pt-32 pb-20 container mx-auto px-4">
    <h1 class="text-4xl font-bold mb-8 text-center" style="font-family: 'Montserrat', sans-serif;">SHOPPING CART</h1>

    @if($cartItems->count() > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items List -->
            <div class="w-full lg:w-2/3">
                <div class="bg-secondary/20 rounded-xl p-6 border border-white/10">
                    @foreach($cartItems as $item)
                        <div class="flex flex-col sm:flex-row items-center gap-6 py-6 border-b border-white/10 last:border-0">
                            <!-- Product Image -->
                            <div class="w-full sm:w-24 h-24 flex-shrink-0 bg-white/5 rounded-lg overflow-hidden">
                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/placeholder.jpg') }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-full h-full object-cover">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-grow text-center sm:text-left">
                                <h3 class="text-lg font-bold mb-1">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-400 mb-1">
                                    Size: <span class="text-white font-semibold">{{ $item->size }}</span>
                                </p>
                                <p class="text-primary font-bold">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-3">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                    <button type="submit" 
                                            class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                        -
                                    </button>
                                </form>
                                
                                <span class="w-8 text-center font-semibold">{{ $item->quantity }}</span>
                                
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                    <button type="submit" 
                                            class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                                        +
                                    </button>
                                </form>
                            </div>

                            <!-- Subtotal & Remove -->
                            <div class="flex flex-col items-end gap-2">
                                <span class="font-bold text-lg">
                                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                </span>
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400 text-sm transition-colors flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                                        Remove
                                    </button>
                                </form>
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
                            <span class="text-black font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Shipping</span>
                            <span class="text-xs italic text-gray-500">Calculated at checkout</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-8">
                        <div class="flex justify-between items-center text-xl font-bold text-black">
                            <span>Total</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="block w-full bg-black text-white font-bold py-4 rounded-full text-center hover:bg-gray-800 transition-colors uppercase tracking-wider">
                        Proceed to Checkout
                    </a>
                    
                    <a href="{{ route('products.index') }}" class="block w-full text-center text-gray-500 hover:text-black mt-4 text-sm underline transition-colors">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="min-h-[50vh] flex items-center justify-center">
            <div class="text-center">
                {{-- Simple Bag Icon --}}
                <div class="mb-6 text-gray-300">
                    <svg class="w-16 h-16 mx-auto stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold mb-3 uppercase tracking-wider">
                    Your Cart is Empty
                </h2>
                
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Looks like you haven't added any items yet.
                </p>

                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-black text-white font-bold text-sm uppercase px-8 py-3 rounded-full hover:bg-gray-800 transition-colors tracking-wide">
                    Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
