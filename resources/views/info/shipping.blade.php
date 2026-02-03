@extends('layouts.app')

@section('title', 'Shipping Information - JastipHype')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-gray-900 to-black text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-gold/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Shipping Information</h1>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    Fast and reliable delivery across Indonesia. Track your order every step of the way.
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto">
            {{-- Courier Cards --}}
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-6 text-center">Available Couriers</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- JNE Regular --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <span class="text-red-600 font-bold text-sm">JNE</span>
                            </div>
                            <div>
                                <h3 class="font-semibold">JNE Regular</h3>
                                <p class="text-sm text-gray-500">Standard Delivery</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>3-5 business days</span>
                        </div>
                    </div>

                    {{-- JNE YES --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <span class="text-red-600 font-bold text-sm">YES</span>
                            </div>
                            <div>
                                <h3 class="font-semibold">JNE YES</h3>
                                <p class="text-sm text-gray-500">Express Delivery</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>1-2 business days</span>
                        </div>
                    </div>

                    {{-- J&T Express --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <span class="text-orange-600 font-bold text-sm">J&T</span>
                            </div>
                            <div>
                                <h3 class="font-semibold">J&T Express</h3>
                                <p class="text-sm text-gray-500">Fast Delivery</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>2-4 business days</span>
                        </div>
                    </div>

                    {{-- SiCepat --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <span class="text-yellow-600 font-bold text-xs">SiCepat</span>
                            </div>
                            <div>
                                <h3 class="font-semibold">SiCepat</h3>
                                <p class="text-sm text-gray-500">Fast Delivery</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>2-4 business days</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Processing Timeline --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
                <h2 class="text-2xl font-bold mb-8 text-center">How It Works</h2>
                <div class="relative">
                    {{-- Timeline Line --}}
                    <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-0.5 bg-gray-200"></div>
                    
                    <div class="space-y-8 md:space-y-0 md:grid md:grid-cols-4 md:gap-8">
                        {{-- Step 1 --}}
                        <div class="relative text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-black text-white rounded-full font-bold mb-4 relative z-10">1</div>
                            <h3 class="font-semibold mb-2">Order Placed</h3>
                            <p class="text-sm text-gray-600">Your order is confirmed and payment verified</p>
                        </div>

                        {{-- Step 2 --}}
                        <div class="relative text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-black text-white rounded-full font-bold mb-4 relative z-10">2</div>
                            <h3 class="font-semibold mb-2">Processing</h3>
                            <p class="text-sm text-gray-600">We prepare and pack your items carefully</p>
                        </div>

                        {{-- Step 3 --}}
                        <div class="relative text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-black text-white rounded-full font-bold mb-4 relative z-10">3</div>
                            <h3 class="font-semibold mb-2">Shipped</h3>
                            <p class="text-sm text-gray-600">Package handed over to courier with tracking</p>
                        </div>

                        {{-- Step 4 --}}
                        <div class="relative text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-accent-gold text-white rounded-full font-bold mb-4 relative z-10">4</div>
                            <h3 class="font-semibold mb-2">Delivered</h3>
                            <p class="text-sm text-gray-600">Package arrives at your doorstep</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Cards Grid --}}
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                {{-- Processing Time --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Processing Time</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Orders are typically processed within <strong>1-2 business days</strong>. You will receive a tracking number via email once your order has been shipped.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Order Tracking --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Order Tracking</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Once your order ships, you'll receive an email with tracking information. You can also track your order from your <a href="{{ route('profile.index') }}" class="text-accent-gold hover:underline font-medium">account dashboard</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping Restrictions Alert --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-amber-800 mb-2">Shipping Restrictions</h3>
                        <ul class="text-amber-700 text-sm space-y-2">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>We currently ship within <strong>Indonesia only</strong></span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>Some remote areas may require additional delivery time</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>Shipping costs are calculated based on weight and destination at checkout</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- CTA Section --}}
            <div class="bg-gradient-to-r from-gray-900 to-black rounded-2xl p-8 text-center text-white">
                <h2 class="text-2xl font-bold mb-3">Need Help?</h2>
                <p class="text-gray-300 mb-6 max-w-md mx-auto">
                    If you have questions about shipping, please don't hesitate to contact us.
                </p>
                <a href="{{ route('info.contact') }}" class="inline-flex items-center gap-2 bg-white text-black px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
