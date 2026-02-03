@extends('layouts.app')

@section('title', 'Returns & Refunds - JastipHype')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-gray-900 to-black text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-gold/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Returns & Refunds</h1>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    100% satisfaction guaranteed. Easy returns within 7 days.
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto">
            {{-- Return Window Banner --}}
            <div class="bg-gradient-to-r from-accent-gold to-yellow-500 rounded-2xl p-6 md:p-8 mb-12 text-center text-white">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                    <span class="text-3xl font-bold">7</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold mb-2">Day Return Window</h2>
                <p class="text-white/90 max-w-md mx-auto">
                    Return any item within 7 days of delivery for a full refund
                </p>
            </div>

            {{-- Return Process Steps --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
                <h2 class="text-2xl font-bold mb-8 text-center">How to Return an Item</h2>
                
                <div class="grid md:grid-cols-4 gap-6">
                    {{-- Step 1 --}}
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            <div class="w-16 h-16 bg-gray-900 text-white rounded-full flex items-center justify-center text-xl font-bold">1</div>
                            <div class="hidden md:block absolute top-1/2 left-full w-full h-0.5 bg-gray-200 -translate-y-1/2"></div>
                        </div>
                        <h3 class="font-semibold mb-2">Request Return</h3>
                        <p class="text-sm text-gray-600">Contact our support team or submit a return request from your order history</p>
                    </div>

                    {{-- Step 2 --}}
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            <div class="w-16 h-16 bg-gray-900 text-white rounded-full flex items-center justify-center text-xl font-bold">2</div>
                            <div class="hidden md:block absolute top-1/2 left-full w-full h-0.5 bg-gray-200 -translate-y-1/2"></div>
                        </div>
                        <h3 class="font-semibold mb-2">Pack Item</h3>
                        <p class="text-sm text-gray-600">Pack the item in its original packaging with all tags attached</p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            <div class="w-16 h-16 bg-gray-900 text-white rounded-full flex items-center justify-center text-xl font-bold">3</div>
                            <div class="hidden md:block absolute top-1/2 left-full w-full h-0.5 bg-gray-200 -translate-y-1/2"></div>
                        </div>
                        <h3 class="font-semibold mb-2">Ship Back</h3>
                        <p class="text-sm text-gray-600">Ship the item using the provided return label or preferred courier</p>
                    </div>

                    {{-- Step 4 --}}
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            <div class="w-16 h-16 bg-accent-gold text-white rounded-full flex items-center justify-center text-xl font-bold">4</div>
                        </div>
                        <h3 class="font-semibold mb-2">Get Refund</h3>
                        <p class="text-sm text-gray-600">Receive your refund within 5-7 business days after inspection</p>
                    </div>
                </div>
            </div>

            {{-- Eligibility Section --}}
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                {{-- Eligible --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-800">Eligible for Return</h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700 text-sm">Items in original, unused condition</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700 text-sm">All original tags and packaging intact</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700 text-sm">Return requested within 7 days of delivery</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700 text-sm">Defective or damaged items</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700 text-sm">Wrong item received</span>
                        </li>
                    </ul>
                </div>

                {{-- Not Eligible --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-red-800">Not Eligible for Return</h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="text-gray-700 text-sm">Items worn, washed, or altered</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="text-gray-700 text-sm">Missing original tags or packaging</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="text-gray-700 text-sm">Return requested after 7 days</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="text-gray-700 text-sm">Personal items (underwear, swimwear)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="text-gray-700 text-sm">Items marked as "Final Sale"</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Refund Timeline --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-3">
                    <svg class="w-6 h-6 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Refund Timeline
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Payment Method</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Processing Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-3 px-4 text-gray-600">Bank Transfer</td>
                                <td class="py-3 px-4 text-gray-600">3-5 business days</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-gray-600">Credit Card</td>
                                <td class="py-3 px-4 text-gray-600">5-7 business days</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-gray-600">E-Wallet (GoPay, OVO, DANA)</td>
                                <td class="py-3 px-4 text-gray-600">1-3 business days</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-gray-600">Virtual Account</td>
                                <td class="py-3 px-4 text-gray-600">3-5 business days</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <p class="text-sm text-gray-500 mt-4 flex items-start gap-2">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Refund processing begins after we receive and inspect your returned item.</span>
                </p>
            </div>

            {{-- CTA Section --}}
            <div class="bg-gradient-to-r from-gray-900 to-black rounded-2xl p-8 text-center text-white">
                <h2 class="text-2xl font-bold mb-3">Ready to Start a Return?</h2>
                <p class="text-gray-300 mb-6 max-w-md mx-auto">
                    Contact our support team or visit your order history to initiate a return.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('info.contact') }}" class="inline-flex items-center justify-center gap-2 bg-white text-black px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Contact Support
                    </a>
                    @auth
                    <a href="{{ route('profile.index') }}" class="inline-flex items-center justify-center gap-2 bg-accent-gold text-white px-6 py-3 rounded-lg hover:bg-accent-gold/90 transition-colors font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        My Orders
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
