@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="mt-2 text-sm text-gray-600">Complete your details to place the order.</p>
        </div>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
            <!-- Left Column: Shipping & Contact Info -->
            <section class="lg:col-span-7">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <!-- Contact Information -->
                    <div class="bg-white rounded-lg p-6 mb-6 shadow-sm border border-gray-100">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h2>
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm" required>
                            </div>
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm" required>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm" required>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-lg p-6 mb-6 shadow-sm border border-gray-100">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Shipping Address</h2>
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea name="address" id="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm" required>{{ old('address', $user->address ?? '') }}</textarea>
                            </div>
                            
                            <!-- Placeholder for RajaOngkir Province/City dropdowns later -->
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                                <select id="province" name="province_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                                    <option>Select Province</option>
                                    <!-- TODO: Populate via AJAX -->
                                </select>
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <select id="city" name="city_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                                    <option>Select City</option>
                                    <!-- TODO: Populate via AJAX -->
                                </select>
                            </div>
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                <input type="text" name="postal_code" id="postal_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg p-6 mb-6 shadow-sm border border-gray-100">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Payment Method</h2>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input id="manual" name="payment_method" type="radio" value="manual" checked class="focus:ring-black h-4 w-4 text-black border-gray-300">
                                <label for="manual" class="ml-3 block text-sm font-medium text-gray-700">
                                    Manual Bank Transfer (BCA/Mandiri)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="gateway" name="payment_method" type="radio" value="gateway" class="focus:ring-black h-4 w-4 text-black border-gray-300">
                                <label for="gateway" class="ml-3 block text-sm font-medium text-gray-700">
                                    Online Payment (Midtrans) - <span class="text-xs text-gray-500">Coming Soon</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            <!-- Right Column: Order Summary -->
            <section class="lg:col-span-5 mt-8 lg:mt-0">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>

                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <li class="flex py-4">
                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                    @if($item->product->productImages->first())
                                        <img src="{{ asset('storage/' . $item->product->productImages->first()->image_path) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="h-full w-full object-cover object-center">
                                    @endif
                                </div>

                                <div class="ml-4 flex flex-1 flex-col">
                                    <div>
                                        <div class="flex justify-between text-base font-medium text-gray-900">
                                            <h3>{{ $item->product->name }}</h3>
                                            <p class="ml-4">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">{{ $item->product->brand->name ?? '' }}</p>
                                        <p class="mt-1 text-sm text-gray-500">Size: {{ $item->size }}</p>
                                    </div>
                                    <div class="flex flex-1 items-end justify-between text-sm">
                                        <p class="text-gray-500">Qty {{ $item->quantity }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <dl class="space-y-4 border-t border-gray-200 pt-6 mt-6">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Shipping</dt>
                            <dd class="text-sm font-medium text-gray-900" id="shipping-cost-display">Rp {{ number_format($shippingCost, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-bold text-gray-900">Total</dt>
                            <dd class="text-base font-bold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <button type="submit" form="checkout-form" class="w-full bg-black border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                            Place Order
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
