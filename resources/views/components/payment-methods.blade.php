@props(['selected' => 'bank_transfer'])

<div x-data="paymentMethods('{{ $selected }}')" class="space-y-4">
    <!-- E-Wallet Dropdown -->
    <div class="border-2 rounded-xl overflow-hidden transition-all"
         :class="selectedPayment === 'ewallet' ? 'border-black bg-gray-50' : 'border-gray-200 bg-white hover:border-gray-300'">
        <button type="button"
                @click="selectedPayment = selectedPayment === 'ewallet' ? null : 'ewallet'"
                class="w-full p-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <h4 class="font-bold text-gray-900">E-Wallet</h4>
                    <p class="text-sm text-gray-500">GoPay, Dana, OVO, ShopeePay</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                     :class="selectedPayment === 'ewallet' ? 'border-black bg-black' : 'border-gray-300'">
                    <svg x-show="selectedPayment === 'ewallet'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-transform" 
                     :class="selectedPayment === 'ewallet' ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </button>
        
        <!-- E-Wallet Options -->
        <div x-show="selectedPayment === 'ewallet'" 
             x-collapse
             class="px-5 pb-5">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-3">Select E-Wallet:</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="ewallet_type" value="gopay" class="peer sr-only">
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <img src="{{ asset('images/payment/ewallet/gopay.svg') }}" alt="GoPay" class="h-8 mb-2 object-contain">
                            <span class="text-xs font-medium text-gray-700">GoPay</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="ewallet_type" value="dana" class="peer sr-only">
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <img src="{{ asset('images/payment/ewallet/dana.svg') }}" alt="Dana" class="h-8 mb-2 object-contain">
                            <span class="text-xs font-medium text-gray-700">Dana</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="ewallet_type" value="ovo" class="peer sr-only">
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <img src="{{ asset('images/payment/ewallet/ovo.svg') }}" alt="OVO" class="h-8 mb-2 object-contain">
                            <span class="text-xs font-medium text-gray-700">OVO</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="ewallet_type" value="shopeepay" class="peer sr-only">
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <img src="{{ asset('images/payment/ewallet/shopeepay.svg') }}" alt="ShopeePay" class="h-8 mb-2 object-contain">
                            <span class="text-xs font-medium text-gray-700">ShopeePay</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Virtual Account / Bank Transfer Dropdown -->
    <div class="border-2 rounded-xl overflow-hidden transition-all"
         :class="selectedPayment === 'bank_transfer' ? 'border-black bg-gray-50' : 'border-gray-200 bg-white hover:border-gray-300'">
        <button type="button"
                @click="selectedPayment = selectedPayment === 'bank_transfer' ? null : 'bank_transfer'"
                class="w-full p-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <h4 class="font-bold text-gray-900">Virtual Account</h4>
                    <p class="text-sm text-gray-500">BCA, Mandiri, BNI, BRI, Permata</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                     :class="selectedPayment === 'bank_transfer' ? 'border-black bg-black' : 'border-gray-300'">
                    <svg x-show="selectedPayment === 'bank_transfer'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-transform" 
                     :class="selectedPayment === 'bank_transfer' ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </button>
        
        <!-- Bank Options -->
        <div x-show="selectedPayment === 'bank_transfer'" 
             x-collapse
             class="px-5 pb-5">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-3">Select Bank:</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="bank_type" value="bca" class="peer sr-only" checked>
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <img src="{{ asset('images/payment/banks/bca.svg') }}" alt="BCA" class="h-8 mb-2 object-contain">
                            <span class="text-xs font-medium text-gray-700">BCA</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="bank_type" value="mandiri" class="peer sr-only">
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <img src="{{ asset('images/payment/banks/mandiri.svg') }}" alt="Mandiri" class="h-8 mb-2 object-contain">
                            <span class="text-xs font-medium text-gray-700">Mandiri</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="bank_type" value="bni" class="peer sr-only">
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <img src="{{ asset('images/payment/banks/bni.svg') }}" alt="BNI" class="h-8 mb-2 object-contain">
                            <span class="text-xs font-medium text-gray-700">BNI</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="bank_type" value="bri" class="peer sr-only">
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <img src="{{ asset('images/payment/banks/bri.svg') }}" alt="BRI" class="h-8 mb-2 object-contain">
                            <span class="text-xs font-medium text-gray-700">BRI</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="bank_type" value="permata" class="peer sr-only">
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <div class="h-8 mb-2 flex items-center justify-center">
                                <span class="text-lg font-bold text-gray-700">Permata</span>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Permata Bank</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Convenience Store -->
    <div class="border-2 rounded-xl overflow-hidden transition-all"
         :class="selectedPayment === 'convenience_store' ? 'border-black bg-gray-50' : 'border-gray-200 bg-white hover:border-gray-300'">
        <button type="button"
                @click="selectedPayment = selectedPayment === 'convenience_store' ? null : 'convenience_store'"
                class="w-full p-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="text-left">
                    <h4 class="font-bold text-gray-900">Convenience Store</h4>
                    <p class="text-sm text-gray-500">Indomaret, Alfamart</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                     :class="selectedPayment === 'convenience_store' ? 'border-black bg-black' : 'border-gray-300'">
                    <svg x-show="selectedPayment === 'convenience_store'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-transform" 
                     :class="selectedPayment === 'convenience_store' ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </button>
        
        <!-- Store Options -->
        <div x-show="selectedPayment === 'convenience_store'" 
             x-collapse
             class="px-5 pb-5">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-3">Select Store:</p>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="store_type" value="indomaret" class="peer sr-only">
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <div class="h-8 mb-2 flex items-center justify-center">
                                <span class="text-lg font-bold text-red-600">Indomaret</span>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Indomaret</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="store_type" value="alfamart" class="peer sr-only">
                        <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                            <div class="h-8 mb-2 flex items-center justify-center">
                                <span class="text-lg font-bold text-blue-600">Alfamart</span>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Alfamart</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden input for form submission -->
    <input type="hidden" name="payment_method" :value="selectedPayment">
</div>

<script>
window.paymentMethods = function(initialSelection = 'bank_transfer') {
    return {
        selectedPayment: initialSelection
    };
};
</script>
