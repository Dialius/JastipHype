@extends('layouts.checkout')

@section('content')
<div class="py-10 bg-gray-50" x-data="checkoutPage()">
    <!-- Visual Stepper -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <nav aria-label="Progress">
            <ol role="list" class="flex items-center justify-center gap-8">
                <li class="flex flex-col items-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-black shadow-lg mb-2">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Cart</span>
                </li>
                
                <div class="h-0.5 w-20 bg-black"></div>
                
                <li class="flex flex-col items-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-black shadow-lg mb-2">
                        <span class="text-white font-bold">2</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Checkout</span>
                </li>
                
                <div class="h-0.5 w-20 bg-gray-300"></div>
                
                <li class="flex flex-col items-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white border-2 border-gray-300 mb-2">
                        <span class="text-gray-500 font-bold">3</span>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Payment</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Form Section -->
            <section class="w-full lg:w-3/5">
                <!-- Guest Checkout Notice -->
                @guest
                <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 mb-2">Continue as Guest or Login</h3>
                            <p class="text-sm text-gray-600 mb-4">You can checkout as a guest, or login for a faster checkout experience with saved addresses.</p>
                            <div class="flex gap-3">
                                <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout.index')) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-black text-white text-sm font-bold rounded-lg hover:bg-gray-800 transition-colors">
                                    Login
                                </a>
                                <button type="button" 
                                        onclick="this.closest('.bg-blue-50').remove()"
                                        class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                    Continue as Guest
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endguest

                <!-- Error Messages -->
                @if(session('error'))
                <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-red-900 mb-1">Error</h3>
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                        <button type="button" onclick="this.closest('.bg-red-50').remove()" class="text-red-400 hover:text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-red-900 mb-2">Validation Errors</h3>
                            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" onclick="this.closest('.bg-red-50').remove()" class="text-red-400 hover:text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" @submit="validateForm">
                    @csrf
                    
                    <!-- Contact Information -->
                    <div class="bg-white rounded-2xl p-8 mb-6 shadow-sm border border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white text-sm font-bold">1</span>
                            Contact Information
                        </h2>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            
                            <!-- Email -->
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="email" id="email" 
                                       class="block w-full px-4 py-3 text-base text-gray-900 bg-gray-50 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-0 focus:border-black transition-colors" 
                                       placeholder="your@email.com" required value="{{ old('email', $user->email ?? '') }}" />
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="name" id="name" 
                                       class="block w-full px-4 py-3 text-base text-gray-900 bg-gray-50 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-0 focus:border-black transition-colors" 
                                       placeholder="John Doe" required value="{{ old('name', $user->name ?? '') }}" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" name="phone" id="phone" 
                                       class="block w-full px-4 py-3 text-base text-gray-900 bg-gray-50 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-0 focus:border-black transition-colors" 
                                       placeholder="08123456789" required value="{{ old('phone', $user->phone ?? '') }}" />
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-2xl p-8 mb-6 shadow-sm border border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white text-sm font-bold">2</span>
                            Shipping Address
                        </h2>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            
                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Full Address *</label>
                                <textarea name="address" id="address" rows="3" 
                                          class="block w-full px-4 py-3 text-base text-gray-900 bg-gray-50 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-0 focus:border-black transition-colors resize-none" 
                                          placeholder="Street address, apartment, suite, etc." required>{{ old('address', $user->address ?? '') }}</textarea>
                            </div>
                            
                            <!-- Address Selector (Province, City, Postal Code in one modal) -->
                            <div class="sm:col-span-2">
                                <x-address-selector-modal label="Province, City & Postal Code" />
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="bg-white rounded-2xl p-8 mb-6 shadow-sm border border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white text-sm font-bold">2</span>
                            Shipping Method
                        </h2>
                        
                        <div x-show="!selectedCity" class="bg-gray-50 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-gray-500 font-medium">Complete address detail to see available shipping methods.</p>
                        </div>

                        <div x-show="selectedCity && loadingShipping" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-black mb-3"></div>
                            <p class="text-sm text-gray-500">Calculating shipping costs...</p>
                        </div>

                        <div x-show="selectedCity && !loadingShipping && shippingOptions.length > 0" class="space-y-3">
                            <template x-for="(courier, index) in shippingOptions" :key="index">
                                <div class="border-2 rounded-xl overflow-hidden transition-all"
                                     :class="selectedShipping === (courier.courier + '-' + courier.service) ? 'border-black bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                    <label class="flex items-center justify-between p-4 cursor-pointer">
                                        <div class="flex items-center gap-4 flex-1">
                                            <input type="radio" name="shipping_method" :value="courier.courier + '-' + courier.service" 
                                                   x-model="selectedShipping"
                                                   @change="updateShippingCost(courier.cost, courier.courier + '-' + courier.service)"
                                                   class="w-5 h-5 text-black border-gray-300 focus:ring-black">
                                            <div>
                                                <p class="font-bold text-gray-900" x-text="courier.courier + ' - ' + courier.service"></p>
                                                <p class="text-sm text-gray-500" x-text="courier.description"></p>
                                                <p class="text-xs text-gray-500 mt-1">Estimated: <span x-text="courier.etd"></span> days</p>
                                            </div>
                                        </div>
                                        <p class="font-bold text-lg text-gray-900" x-text="'Rp ' + courier.cost.toLocaleString('id-ID')"></p>
                                    </label>
                                </div>
                            </template>
                        </div>
                        
                        <div x-show="selectedCity && !loadingShipping && shippingOptions.length === 0" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 mx-auto mb-3 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-gray-700 font-medium">No shipping options available</p>
                            <p class="text-sm text-gray-600 mt-1">Please try a different city or contact support</p>
                        </div>
                    </div>


                    <!-- Payment Method -->
                    <div class="bg-white rounded-2xl p-8 mb-6 shadow-sm border border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white text-sm font-bold">3</span>
                            Payment Method
                        </h2>

                        <div x-data="paymentSelector()" class="space-y-3">
                            <!-- Virtual Account -->
                            <div class="border-2 rounded-xl overflow-hidden transition-all" :class="openSection === 'va' ? 'border-black' : 'border-gray-200'">
                                <button type="button"
                                    @click="openSection = openSection === 'va' ? null : 'va'"
                                    class="w-full flex items-center justify-between px-5 py-4 bg-gray-50 hover:bg-gray-100 transition">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        <span class="font-semibold text-gray-900">Virtual Account (VA)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex gap-1.5">
                                            <img src="{{ asset('images/payment/banks/mandiri-va.webp') }}" alt="Mandiri" class="h-5 object-contain" onerror="this.style.display='none'">
                                            <img src="{{ asset('images/payment/banks/bri-va.webp') }}" alt="BRI" class="h-5 object-contain" onerror="this.style.display='none'">
                                        </div>
                                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="openSection === 'va' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </button>
                                <div x-show="openSection === 'va'" x-collapse class="px-5 pb-5 pt-3 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach([
                                        ['value'=>'bank_transfer', 'detail'=>'bca', 'label'=>'BCA Virtual Account', 'logo'=>asset('images/payment/banks/bca.svg'), 'color'=>'#005BAC'],
                                        ['value'=>'bank_transfer', 'detail'=>'mandiri', 'label'=>'Mandiri Virtual Account', 'logo'=>asset('images/payment/banks/mandiri-va.webp'), 'color'=>'#003087'],
                                        ['value'=>'bank_transfer', 'detail'=>'bni', 'label'=>'BNI Virtual Account', 'logo'=>asset('images/payment/banks/bni-va.webp'), 'color'=>'#F58220'],
                                        ['value'=>'bank_transfer', 'detail'=>'bri', 'label'=>'BRI Virtual Account', 'logo'=>asset('images/payment/banks/bri-va.webp'), 'color'=>'#00529B'],
                                        ['value'=>'bank_transfer', 'detail'=>'permata', 'label'=>'Permata Virtual Account', 'logo'=>'', 'color'=>'#E31E24'],
                                    ] as $bank)
                                    <label class="relative flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all"
                                        :class="selectedMethod === '{{ $bank['value'] }}' && selectedDetail === '{{ $bank['detail'] }}' ? 'border-black bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                        <input type="radio" name="_payment_choice" value="{{ $bank['value'] }}|{{ $bank['detail'] }}"
                                            @change="selectMethod('{{ $bank['value'] }}', '{{ $bank['detail'] }}')"
                                            class="sr-only">
                                        <div class="w-16 h-10 flex items-center justify-center flex-shrink-0 bg-white rounded border border-gray-100 p-1">
                                            @if($bank['logo'])
                                            <img src="{{ $bank['logo'] }}" alt="{{ $bank['label'] }}" class="max-h-8 max-w-full object-contain" onerror="this.parentElement.innerHTML='<span style=\'font-size:10px;font-weight:700;color:{{ $bank['color'] }}\'>{{ strtoupper($bank['detail']) }}</span>'">
                                            @else
                                            <span class="text-xs font-bold" style="color:{{ $bank['color'] }}">{{ strtoupper($bank['detail']) }}</span>
                                            @endif
                                        </div>
                                        <span class="text-sm font-medium text-gray-800">{{ $bank['label'] }}</span>
                                        <div class="ml-auto w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all"
                                            :class="selectedMethod === '{{ $bank['value'] }}' && selectedDetail === '{{ $bank['detail'] }}' ? 'border-black bg-black' : 'border-gray-300'">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- E-Wallet & QRIS -->
                            <div class="border-2 rounded-xl overflow-hidden transition-all" :class="openSection === 'ewallet' ? 'border-black' : 'border-gray-200'">
                                <button type="button"
                                    @click="openSection = openSection === 'ewallet' ? null : 'ewallet'"
                                    class="w-full flex items-center justify-between px-5 py-4 bg-gray-50 hover:bg-gray-100 transition">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span class="font-semibold text-gray-900">E-Wallet & QRIS</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex gap-1.5 items-center">
                                            <img src="{{ asset('images/payment/ewallet/qrisfinal.webp') }}" alt="QRIS" class="h-5 object-contain" onerror="this.style.display='none'">
                                        </div>
                                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="openSection === 'ewallet' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </button>
                                <div x-show="openSection === 'ewallet'" x-collapse class="px-5 pb-5 pt-3 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach([
                                        ['value'=>'qris', 'detail'=>'', 'label'=>'QRIS (Semua Dompet)', 'logo'=>asset('images/payment/ewallet/qrisfinal.webp'), 'color'=>'#00AED6', 'desc'=>'GoPay, OVO, DANA, dll'],
                                        ['value'=>'ewallet', 'detail'=>'gopay', 'label'=>'GoPay', 'logo'=>asset('images/payment/ewallet/qris-gopay.webp'), 'color'=>'#00A651', 'desc'=>'Bayar dengan GoPay'],
                                        ['value'=>'ewallet', 'detail'=>'shopeepay', 'label'=>'ShopeePay', 'logo'=>asset('images/payment/ewallet/qris-shopee.webp'), 'color'=>'#EE4D2D', 'desc'=>'Bayar dengan ShopeePay'],
                                        ['value'=>'ewallet', 'detail'=>'dana', 'label'=>'DANA', 'logo'=>asset('images/payment/ewallet/qris-dana.webp'), 'color'=>'#0079C1', 'desc'=>'Bayar dengan DANA'],
                                    ] as $wallet)
                                    <label class="relative flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all"
                                        :class="selectedMethod === '{{ $wallet['value'] }}' && selectedDetail === '{{ $wallet['detail'] }}' ? 'border-black bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                        <input type="radio" name="_payment_choice" value="{{ $wallet['value'] }}|{{ $wallet['detail'] }}"
                                            @change="selectMethod('{{ $wallet['value'] }}', '{{ $wallet['detail'] }}')"
                                            class="sr-only">
                                        <div class="w-16 h-10 flex items-center justify-center flex-shrink-0 bg-white rounded border border-gray-100 p-1">
                                            @if($wallet['logo'])
                                            <img src="{{ $wallet['logo'] }}" alt="{{ $wallet['label'] }}" class="max-h-8 max-w-full object-contain" onerror="this.parentElement.innerHTML='<span style=\'font-size:10px;font-weight:700;color:{{ $wallet['color'] }}\'>{{ strtoupper($wallet['detail'] ?: $wallet['value']) }}</span>'">
                                            @else
                                            <span class="text-xs font-bold" style="color:{{ $wallet['color'] }}">{{ strtoupper($wallet['detail'] ?: $wallet['value']) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $wallet['label'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $wallet['desc'] }}</p>
                                        </div>
                                        <div class="ml-auto w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all"
                                            :class="selectedMethod === '{{ $wallet['value'] }}' && selectedDetail === '{{ $wallet['detail'] }}' ? 'border-black bg-black' : 'border-gray-300'">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Convenience Store -->
                            <div class="border-2 rounded-xl overflow-hidden transition-all" :class="openSection === 'cstore' ? 'border-black' : 'border-gray-200'">
                                <button type="button"
                                    @click="openSection = openSection === 'cstore' ? null : 'cstore'"
                                    class="w-full flex items-center justify-between px-5 py-4 bg-gray-50 hover:bg-gray-100 transition">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        <span class="font-semibold text-gray-900">Convenience Store</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex gap-1.5 items-center">
                                            <img src="{{ asset('images/payment/cstore/alfamart.png') }}" alt="Alfamart" class="h-5 object-contain" onerror="this.style.display='none'">
                                            <img src="{{ asset('images/payment/cstore/indomaret.png') }}" alt="Indomaret" class="h-5 object-contain" onerror="this.style.display='none'">
                                        </div>
                                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="openSection === 'cstore' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </button>
                                <div x-show="openSection === 'cstore'" x-collapse class="px-5 pb-5 pt-3 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach([
                                        ['value'=>'convenience_store', 'detail'=>'indomaret', 'label'=>'Indomaret', 'logo'=>asset('images/payment/cstore/indomaret.png'), 'color'=>'#005BAC'],
                                        ['value'=>'convenience_store', 'detail'=>'alfamart', 'label'=>'Alfamart', 'logo'=>asset('images/payment/cstore/alfamart.png'), 'color'=>'#E31E24'],
                                    ] as $store)
                                    <label class="relative flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all"
                                        :class="selectedMethod === '{{ $store['value'] }}' && selectedDetail === '{{ $store['detail'] }}' ? 'border-black bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                        <input type="radio" name="_payment_choice" value="{{ $store['value'] }}|{{ $store['detail'] }}"
                                            @change="selectMethod('{{ $store['value'] }}', '{{ $store['detail'] }}')"
                                            class="sr-only">
                                        <div class="w-16 h-10 flex items-center justify-center flex-shrink-0 bg-white rounded border border-gray-100 p-1">
                                            @if($store['logo'])
                                            <img src="{{ $store['logo'] }}" alt="{{ $store['label'] }}" class="max-h-8 max-w-full object-contain" onerror="this.parentElement.innerHTML='<span style=\'font-size:10px;font-weight:700;color:{{ $store['color'] }}\'>{{ strtoupper($store['detail']) }}</span>'">
                                            @else
                                            <span class="text-xs font-bold" style="color:{{ $store['color'] }}">{{ strtoupper($store['detail']) }}</span>
                                            @endif
                                        </div>
                                        <span class="text-sm font-medium text-gray-800">{{ $store['label'] }}</span>
                                        <div class="ml-auto w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all"
                                            :class="selectedMethod === '{{ $store['value'] }}' && selectedDetail === '{{ $store['detail'] }}' ? 'border-black bg-black' : 'border-gray-300'">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Validation note -->
                            <p x-show="showValidationError" class="text-sm text-red-600 flex items-center gap-2 mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pilih metode pembayaran terlebih dahulu.
                            </p>
                        </div>

                        <!-- Hidden real inputs that get submitted -->
                        <input type="hidden" name="payment_method" id="payment_method_input" value="">
                        <input type="hidden" name="payment_detail" id="payment_detail_input" value="">
                    </div>



                    <!-- Hidden input for shipping cost -->
                    <input type="hidden" name="shipping_cost" x-model="shippingCost">
                </form>
            </section>

            <!-- Order Summary -->
            <section class="w-full lg:w-2/5">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sticky top-24" 
                     x-data="orderSummary()">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

                    <!-- Product List with See More -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-gray-700">Items ({{ $cartItems->count() }})</h3>
                            <button type="button" 
                                    @click="showProducts = !showProducts" 
                                    class="flex items-center gap-1 text-sm font-medium transition-colors text-black hover:text-gray-700">
                                <span x-text="showProducts ? 'Show Less' : 'See All Items'"></span>
                                <svg class="w-4 h-4 transition-transform duration-200" 
                                     :class="showProducts ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="relative">
                            <ul role="list" 
                                class="divide-y divide-gray-200 transition-all duration-300 overflow-hidden"
                                :class="showProducts ? 'max-h-[600px]' : 'max-h-[180px]'">
                                @foreach($cartItems as $item)
                                    <li class="flex py-3 gap-3">
                                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                            <img src="{{ product_image_url($item->product) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="h-full w-full object-cover object-center">
                                        </div>
                                        <div class="flex flex-1 flex-col justify-between text-sm">
                                            <div>
                                                <h3 class="font-medium text-gray-900 line-clamp-1">{{ $item->product->name }}</h3>
                                                <p class="text-xs text-gray-500 mt-1">Size: {{ $item->size }} | Qty: {{ $item->quantity }}</p>
                                            </div>
                                            <p class="font-semibold text-gray-900">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <!-- Gradient Overlay when collapsed -->
                            <div x-show="!showProducts" 
                                 class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-white to-transparent pointer-events-none">
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Message Button -->
                    <button type="button" 
                            @click="openDeliveryModal()"
                            class="w-full flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg hover:border-gray-300 transition-colors mb-4">
                        <span class="text-sm text-gray-600">Leave a message for delivery (Optional)</span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Vouchers Button -->
                    <button type="button" 
                            @click="openVoucherModal()"
                            class="w-full flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg hover:border-gray-300 transition-colors mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="text-sm text-gray-600">Vouchers</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <dl class="space-y-4 border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Subtotal ({{ $cartItems->sum('quantity') }} items)</dt>
                            <dd class="text-sm font-semibold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Shipping</dt>
                            <dd class="text-sm font-medium text-gray-900" id="shipping-cost-display">Rp {{ number_format($shippingCost, 0, ',', '.') }}</dd>
                        </div>
                        @if(session('discount'))
                        <div class="flex items-center justify-between text-green-600">
                            <dt class="text-sm font-medium">
                                Discount ({{ session('discount.code') }})
                            </dt>
                            <dd class="text-sm font-semibold">
                                - Rp {{ number_format(session('discount.amount'), 0, ',', '.') }}
                            </dd>
                        </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Tax</dt>
                            <dd class="text-sm text-gray-500">Included</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-lg font-bold text-gray-900">Total</dt>
                            <dd class="text-2xl font-bold text-gray-900">Rp {{ number_format($total - (session('discount.amount') ?? 0), 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <button type="submit" form="checkout-form" class="w-full bg-black border border-transparent rounded-full shadow-lg py-4 px-4 text-base font-bold text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-all hover:translate-y-[-1px] hover:shadow-xl">
                            PLACE ORDER
                        </button>
                    </div>

                    <!-- Trust Signals -->
                    <div class="mt-6 border-t border-gray-100 pt-6 space-y-3">
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span>Secure Checkout</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <span>Multiple Payment Options</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span>Free Returns within 30 Days</span>
                        </div>
                    </div>

                    <!-- Payment Logos -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-xs text-gray-500 text-center mb-3">We Accept</p>
                        <div class="grid grid-cols-4 gap-3 opacity-60 hover:opacity-100 transition-opacity">
                            <img src="{{ asset('images/payment/banks/bca.svg') }}" alt="BCA" class="h-6 object-contain grayscale hover:grayscale-0 transition-all">
                            <img src="{{ asset('images/payment/banks/mandiri.svg') }}" alt="Mandiri" class="h-6 object-contain grayscale hover:grayscale-0 transition-all">
                            <img src="{{ asset('images/payment/banks/bni.svg') }}" alt="BNI" class="h-6 object-contain grayscale hover:grayscale-0 transition-all">
                            <img src="{{ asset('images/payment/banks/bri.svg') }}" alt="BRI" class="h-6 object-contain grayscale hover:grayscale-0 transition-all">
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    </div>
</div>

{{-- Voucher Modal (Outside Alpine scope, using Teleport) --}}
<div x-data="voucherModal()" style="display: none;">
    <template x-teleport="body">
        <div x-show="isOpen" 
             @keydown.escape.window="close()"
             class="fixed inset-0 z-[99999] overflow-hidden" 
             style="display: none;">
            
            {{-- Global Style to Prevent Body Scroll --}}
            <style>
                body.modal-open {
                    overflow: hidden !important;
                    position: fixed;
                    width: 100%;
                    height: 100%;
                }
            </style>
            
            {{-- Backdrop --}}
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 @click="close()"
                 class="fixed inset-0 bg-black/50"></div>

            {{-- Modal Container --}}
            <div class="fixed inset-0 flex items-end lg:items-center justify-center p-0 lg:p-4 pointer-events-none">
                <div x-show="isOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-full lg:translate-y-4 lg:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 lg:scale-100"
                     @click.outside="close()"
                     class="relative bg-white w-full lg:max-w-lg shadow-2xl transform transition-all rounded-t-3xl lg:rounded-3xl flex flex-col pointer-events-auto"
                     style="max-height: 85vh;">
                    
                    {{-- Header (Fixed) --}}
                    <div class="px-6 py-5 border-b border-gray-200 bg-white flex-shrink-0 rounded-t-3xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-black">Vouchers</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Apply discount code</p>
                            </div>
                            <button @click="close()"
                                    class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body (Scrollable) --}}
                    <div class="flex-1 overflow-y-auto px-6 py-6 voucher-modal-body" style="scrollbar-width: thin;">
                        <style>
                            .voucher-modal-body::-webkit-scrollbar {
                                width: 6px;
                            }
                            .voucher-modal-body::-webkit-scrollbar-track {
                                background: #f1f1f1;
                                border-radius: 10px;
                            }
                            .voucher-modal-body::-webkit-scrollbar-thumb {
                                background: #888;
                                border-radius: 10px;
                            }
                            .voucher-modal-body::-webkit-scrollbar-thumb:hover {
                                background: #555;
                            }
                        </style>
                        <div>
                            <!-- Applied Voucher Display -->
                            <div x-show="appliedVoucher" class="mb-6 p-4 bg-green-50 border-2 border-green-200 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-green-900" x-text="appliedVoucher?.code"></p>
                                            <p class="text-sm text-green-700">
                                                Discount: <span x-text="appliedVoucher?.formatted_amount"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <button @click="removeVoucher()" 
                                            class="text-red-600 hover:text-red-800 font-medium text-sm">
                                        Remove
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Voucher Input -->
                            <div x-show="!appliedVoucher" class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Enter Voucher Code</label>
                                <div class="flex gap-2">
                                    <input type="text" 
                                           x-model="voucherCode" 
                                           @keyup.enter="applyVoucher()"
                                           placeholder="e.g. DISCOUNT20"
                                           class="flex-1 px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:outline-none focus:border-black">
                                    <button type="button" 
                                            @click="applyVoucher()"
                                            :disabled="isApplying"
                                            class="px-6 py-3 bg-black text-white font-bold rounded-xl hover:bg-gray-800 transition-colors whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span x-show="!isApplying">Apply</span>
                                        <span x-show="isApplying">...</span>
                                    </button>
                                </div>
                                
                                <!-- Error Message -->
                                <p x-show="errorMessage" 
                                   x-text="errorMessage" 
                                   class="mt-2 text-sm text-red-600"></p>
                            </div>

                            <!-- Available Vouchers List -->
                            <div x-show="!appliedVoucher">
                                @if($availableVouchers->count() > 0)
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Available Vouchers</h3>
                                    <div class="space-y-3">
                                        @foreach($availableVouchers as $voucher)
                                        <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <h4 class="text-lg font-bold text-gray-900">{{ $voucher->code }}</h4>
                                                        @if($voucher->type === 'percentage')
                                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">{{ $voucher->value }}% Off</span>
                                                        @else
                                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">Rp {{ number_format($voucher->value, 0, ',', '.') }} Off</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($voucher->type === 'percentage')
                                                        <p class="text-sm text-gray-600 mb-1">{{ $voucher->value }}% discount</p>
                                                    @else
                                                        <p class="text-sm text-gray-600 mb-1">Rp {{ number_format($voucher->value, 0, ',', '.') }} discount</p>
                                                    @endif
                                                    
                                                    @if($voucher->min_order_amount)
                                                        <p class="text-xs text-gray-500">Min. spend Rp {{ number_format($voucher->min_order_amount, 0, ',', '.') }}</p>
                                                    @else
                                                        <p class="text-xs text-gray-500">No minimum spend</p>
                                                    @endif
                                                    
                                                    @if($voucher->end_date)
                                                        <p class="text-xs text-gray-500 mt-1">Valid until {{ $voucher->end_date->format('d M Y') }}</p>
                                                    @endif
                                                </div>
                                                <button type="button" 
                                                        @click="voucherCode = '{{ $voucher->code }}'; applyVoucher()"
                                                        class="px-4 py-2 bg-gray-100 text-gray-900 font-bold text-sm rounded-lg hover:bg-gray-200 transition-colors">
                                                    COPY
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <p class="font-medium text-gray-700">No vouchers available</p>
                                        <p class="text-sm mt-1 text-gray-500">Enter a voucher code above to redeem your discount</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- End Body --}}
                </div>
                {{-- End Modal Container --}}
            </div>
        </div>
    </template>
</div>

{{-- Delivery Message Modal (Outside Alpine scope, using Teleport) --}}
<div x-data="deliveryModal()" style="display: none;">
    <template x-teleport="body">
        <div x-show="isOpen" 
             @keydown.escape.window="close()"
             class="fixed inset-0 z-[99999] overflow-hidden" 
             style="display: none;">
            
            {{-- Backdrop --}}
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 @click="close()"
                 class="fixed inset-0 bg-black/50"></div>

            {{-- Modal Container --}}
            <div class="fixed inset-0 flex items-end lg:items-center justify-center p-0 lg:p-4 pointer-events-none">
                <div x-show="isOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-full lg:translate-y-4 lg:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 lg:scale-100"
                     @click.outside="close()"
                     class="relative bg-white w-full lg:max-w-lg shadow-2xl transform transition-all rounded-t-3xl lg:rounded-3xl flex flex-col pointer-events-auto"
                     style="max-height: 90vh;">
                    
                    {{-- Header --}}
                    <div class="px-6 py-5 border-b border-gray-200 bg-white flex-shrink-0 rounded-t-3xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-black">Delivery Message</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Leave instructions for delivery</p>
                            </div>
                            <button @click="close()"
                                    class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 flex-1" style="overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                        <style>
                            .hide-scrollbar::-webkit-scrollbar { display: none; }
                        </style>
                        <div class="hide-scrollbar">
                            <div class="space-y-3 mb-6">
                                <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300 transition-colors">
                                    <input type="radio" name="delivery_option" value="front" class="w-5 h-5 text-black focus:ring-black mt-0.5">
                                    <span class="text-gray-700 text-sm">Leave the package in front of my house</span>
                                </label>
                                <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300 transition-colors">
                                    <input type="radio" name="delivery_option" value="mailbox" class="w-5 h-5 text-black focus:ring-black mt-0.5">
                                    <span class="text-gray-700 text-sm">Leave the package in my mailbox</span>
                                </label>
                                <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300 transition-colors">
                                    <input type="radio" name="delivery_option" value="custom" class="w-5 h-5 text-black focus:ring-black mt-0.5" checked>
                                    <span class="text-gray-700 text-sm">Custom message</span>
                                </label>
                                
                                <textarea x-model="deliveryMessage" rows="4" placeholder="Type your custom delivery instructions here..."
                                          class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:outline-none focus:border-black resize-none"></textarea>
                            </div>

                            <button type="button" @click="close()"
                                    class="w-full bg-black text-white font-bold py-3 rounded-xl hover:bg-gray-800 transition-colors">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
// Order Summary Component
function orderSummary() {
    return {
        showProducts: false,
        
        openVoucherModal() {
            window.dispatchEvent(new CustomEvent('open-voucher-modal'));
        },
        
        openDeliveryModal() {
            window.dispatchEvent(new CustomEvent('open-delivery-modal'));
        }
    }
}

// Voucher Modal Component
function voucherModal() {
    return {
        isOpen: false,
        voucherCode: '',
        isApplying: false,
        appliedVoucher: null,
        errorMessage: '',
        
        init() {
            window.addEventListener('open-voucher-modal', () => {
                this.open();
            });
            
            // Check if voucher already applied in session
            this.checkAppliedVoucher();
        },
        
        open() {
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
            document.body.classList.add('modal-open');
        },
        
        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
            document.body.classList.remove('modal-open');
            this.errorMessage = '';
        },
        
        async applyVoucher() {
            if (!this.voucherCode.trim()) {
                this.errorMessage = 'Please enter a voucher code';
                return;
            }
            
            this.isApplying = true;
            this.errorMessage = '';
            
            try {
                const response = await fetch('{{ route("checkout.apply-discount") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        code: this.voucherCode
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.appliedVoucher = data.discount;
                    this.voucherCode = '';
                    
                    // Show success toast
                    window.showToast(data.message, 'success');
                    
                    // Reload page to update totals
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    this.errorMessage = data.message;
                }
            } catch (error) {
                console.error('Error applying voucher:', error);
                this.errorMessage = 'Failed to apply voucher. Please try again.';
            } finally {
                this.isApplying = false;
            }
        },
        
        async removeVoucher() {
            try {
                const response = await fetch('{{ route("checkout.remove-discount") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.appliedVoucher = null;
                    window.showToast(data.message, 'success');
                    
                    // Reload page to update totals
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } catch (error) {
                console.error('Error removing voucher:', error);
            }
        },
        
        checkAppliedVoucher() {
            // This will be populated from backend session
            @if(session('discount'))
                this.appliedVoucher = {
                    code: '{{ session("discount.code") }}',
                    type: '{{ session("discount.type") }}',
                    value: {{ session('discount.value') }},
                    amount: {{ session('discount.amount') }},
                    formatted_amount: 'Rp {{ number_format(session("discount.amount"), 0, ",", ".") }}'
                };
            @endif
        }
    }
}

// Delivery Modal Component
function deliveryModal() {
    return {
        isOpen: false,
        deliveryMessage: '',
        
        init() {
            window.addEventListener('open-delivery-modal', () => {
                this.open();
            });
        },
        
        open() {
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
            document.body.classList.add('modal-open');
        },
        
        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
            document.body.classList.remove('modal-open');
        }
    }
}

// Main Checkout Page Component
function checkoutPage() {
    return {
        provinces: [],
        cities: [],
        selectedProvince: '',
        selectedCity: '',
        loadingProvinces: false,
        loadingCities: false,
        loadingShipping: false,
        shippingOptions: [],
        selectedShipping: '',
        shippingCost: 0,
        
        init() {
            // Listen for address selection from new component
            window.addEventListener('address-selected', (e) => {
                this.selectedProvince = e.detail.provinceId;
                this.selectedCity = e.detail.cityId;
                this.calculateShipping();
            });
            
            // Make data available globally for child components
            window.checkoutPageData = this;
        },
        
        async calculateShipping() {
            if (!this.selectedCity) return;

            this.loadingShipping = true;
            this.shippingOptions = [];

            try {
                const totalWeight = {{ $cartItems->sum(function($item) { return $item->product->weight * $item->quantity; }) ?: 1000 }};
                
                const response = await fetch('{{ route('location.cost') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        destination: this.selectedCity,
                        weight: totalWeight,
                        courier: 'jne'
                    })
                });

                const data = await response.json();
                
                console.log('Shipping API Response:', data);
                
                if (data.rajaongkir && data.rajaongkir.results && data.rajaongkir.results.length > 0) {
                    // Flatten shipping options
                    const options = [];
                    data.rajaongkir.results.forEach(courier => {
                        courier.costs.forEach(cost => {
                            options.push({
                                courier: courier.code.toUpperCase(),
                                service: cost.service,
                                description: cost.description,
                                cost: cost.cost[0].value,
                                etd: cost.cost[0].etd
                            });
                        });
                    });
                    this.shippingOptions = options;
                    
                    if (options.length > 0) {
                        if (typeof $notify !== 'undefined') {
                            $notify('Shipping options loaded', 'success');
                        }
                    }
                } else if (data.error) {
                    console.error('Shipping API Error:', data.error);
                    if (typeof $notify !== 'undefined') {
                        $notify('Error: ' + data.error, 'error');
                    }
                } else {
                    if (typeof $notify !== 'undefined') {
                        $notify('No shipping options available for this location', 'warning');
                    }
                }
            } catch (error) {
                console.error('Error calculating shipping:', error);
                if (typeof $notify !== 'undefined') {
                    $notify('Failed to calculate shipping cost', 'error');
                }
            } finally {
                this.loadingShipping = false;
            }
        },

        updateShippingCost(cost, service) {
            this.shippingCost = cost;
            // Update display
            const displayEl = document.getElementById('shipping-cost-display');
            if (displayEl) {
                displayEl.textContent = 'Rp ' + cost.toLocaleString('id-ID');
            }
            // Update total
            const subtotal = {{ $subtotal }};
            const total = subtotal + cost;
            // You can dispatch event or update DOM here
        },

        validateForm(event) {
            // Check if province and city are selected
            const provinceId = document.querySelector('input[name="province_id"]').value;
            const cityId = document.querySelector('input[name="city_id"]').value;
            const postalCode = document.querySelector('input[name="postal_code"]').value;

            if (!provinceId || !cityId || !postalCode) {
                event.preventDefault();
                if (typeof $notify !== 'undefined') {
                    $notify('Please select Province, City, and Postal Code', 'error');
                } else {
                    alert('Please select Province, City, and Postal Code');
                }
                return false;
            }

            // Check payment method selected
            const paymentMethod = document.getElementById('payment_method_input').value;
            if (!paymentMethod) {
                event.preventDefault();
                // Trigger Alpine validation error
                window.dispatchEvent(new CustomEvent('show-payment-validation'));
                if (typeof $notify !== 'undefined') {
                    $notify('Pilih metode pembayaran terlebih dahulu', 'error');
                } else {
                    alert('Pilih metode pembayaran terlebih dahulu');
                }
                return false;
            }

            return true;
        }
    }
}

// Payment Method Selector Component
function paymentSelector() {
    return {
        openSection: null,
        selectedMethod: '',
        selectedDetail: '',
        showValidationError: false,

        init() {
            window.addEventListener('show-payment-validation', () => {
                this.showValidationError = true;
            });
        },

        selectMethod(method, detail) {
            this.selectedMethod = method;
            this.selectedDetail = detail;
            this.showValidationError = false;
            document.getElementById('payment_method_input').value = method;
            document.getElementById('payment_detail_input').value = detail;
        }
    }
}
</script>
@endsection

