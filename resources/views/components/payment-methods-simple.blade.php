@props(['selected' => 'bank_transfer'])

<div x-data="paymentMethods('{{ $selected }}')" class="space-y-3">
    <!-- QRIS -->
    <div class="border-2 rounded-xl overflow-hidden transition-all"
         :class="selectedPayment === 'qris' ? 'border-black bg-gray-50' : 'border-gray-200'">
        <button type="button"
                @click="selectedPayment = 'qris'"
                class="w-full p-5 text-left">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-bold text-gray-900">QRIS</h4>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">Instant</span>
                </div>
            </div>
            <!-- Divider Line -->
            <div class="border-t border-gray-200 my-3"></div>
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/payment/ewallet/qris.svg') }}" alt="QRIS" class="h-8 object-contain opacity-60">
                <span class="text-sm text-gray-600">Scan QR code dengan aplikasi e-wallet atau mobile banking</span>
            </div>
        </button>
    </div>

    <!-- E-Wallet -->
    <div class="border-2 rounded-xl overflow-hidden transition-all"
         :class="selectedPayment === 'ewallet' ? 'border-black bg-gray-50' : 'border-gray-200'">
        <button type="button"
                @click="selectedPayment = selectedPayment === 'ewallet' ? null : 'ewallet'"
                class="w-full p-5 text-left">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-bold text-gray-900">E-Wallet</h4>
                <svg class="w-5 h-5 text-gray-400 transition-transform" 
                     :class="selectedPayment === 'ewallet' ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <!-- Divider Line -->
            <div class="border-t border-gray-200 my-3"></div>
            <div class="flex items-center gap-3 flex-wrap">
                <img src="{{ asset('images/payment/ewallet/dana.svg') }}" alt="Dana" class="h-6 object-contain opacity-60">
                <img src="{{ asset('images/payment/ewallet/gopay.svg') }}" alt="GoPay" class="h-6 object-contain opacity-60">
                <img src="{{ asset('images/payment/ewallet/ovo.svg') }}" alt="OVO" class="h-6 object-contain opacity-60">
                <img src="{{ asset('images/payment/ewallet/shopeepay.svg') }}" alt="ShopeePay" class="h-6 object-contain opacity-60">
            </div>
        </button>
        
        <!-- E-Wallet Options -->
        <div x-show="selectedPayment === 'ewallet'" 
             x-collapse
             class="px-5 pb-5 bg-gray-50">
            <div class="grid grid-cols-2 gap-3">
                <label class="relative cursor-pointer">
                    <input type="radio" name="ewallet_type" value="gopay" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                        <img src="{{ asset('images/payment/ewallet/gopay.svg') }}" alt="GoPay" class="h-8 mx-auto object-contain">
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="ewallet_type" value="dana" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                        <img src="{{ asset('images/payment/ewallet/dana.svg') }}" alt="Dana" class="h-8 mx-auto object-contain">
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="ewallet_type" value="ovo" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                        <img src="{{ asset('images/payment/ewallet/ovo.svg') }}" alt="OVO" class="h-8 mx-auto object-contain">
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="ewallet_type" value="shopeepay" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                        <img src="{{ asset('images/payment/ewallet/shopeepay.svg') }}" alt="ShopeePay" class="h-8 mx-auto object-contain">
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Virtual Account -->
    <div class="border-2 rounded-xl overflow-hidden transition-all"
         :class="selectedPayment === 'bank_transfer' ? 'border-black bg-gray-50' : 'border-gray-200'">
        <button type="button"
                @click="selectedPayment = selectedPayment === 'bank_transfer' ? null : 'bank_transfer'"
                class="w-full p-5 text-left">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-bold text-gray-900">Virtual Account</h4>
                <svg class="w-5 h-5 text-gray-400 transition-transform" 
                     :class="selectedPayment === 'bank_transfer' ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <!-- Divider Line -->
            <div class="border-t border-gray-200 my-3"></div>
            <div class="flex items-center gap-3 flex-wrap">
                <img src="{{ asset('images/payment/banks/bca.svg') }}" alt="BCA" class="h-6 object-contain opacity-60">
                <img src="{{ asset('images/payment/banks/mandiri.svg') }}" alt="Mandiri" class="h-6 object-contain opacity-60">
                <img src="{{ asset('images/payment/banks/bni.svg') }}" alt="BNI" class="h-6 object-contain opacity-60">
                <img src="{{ asset('images/payment/banks/bri.svg') }}" alt="BRI" class="h-6 object-contain opacity-60">
            </div>
        </button>
        
        <!-- Bank Options -->
        <div x-show="selectedPayment === 'bank_transfer'" 
             x-collapse
             class="px-5 pb-5 bg-gray-50">
            <div class="grid grid-cols-3 gap-3">
                <label class="relative cursor-pointer">
                    <input type="radio" name="bank_type" value="bca" class="peer sr-only" checked>
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                        <img src="{{ asset('images/payment/banks/bca.svg') }}" alt="BCA" class="h-8 mx-auto object-contain">
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="bank_type" value="mandiri" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                        <img src="{{ asset('images/payment/banks/mandiri.svg') }}" alt="Mandiri" class="h-8 mx-auto object-contain">
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="bank_type" value="bni" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                        <img src="{{ asset('images/payment/banks/bni.svg') }}" alt="BNI" class="h-8 mx-auto object-contain">
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="bank_type" value="bri" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all">
                        <img src="{{ asset('images/payment/banks/bri.svg') }}" alt="BRI" class="h-8 mx-auto object-contain">
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="bank_type" value="permata" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all flex items-center justify-center">
                        <span class="text-sm font-bold text-gray-700">Permata</span>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Convenience Store -->
    <div class="border-2 rounded-xl overflow-hidden transition-all"
         :class="selectedPayment === 'convenience_store' ? 'border-black bg-gray-50' : 'border-gray-200'">
        <button type="button"
                @click="selectedPayment = selectedPayment === 'convenience_store' ? null : 'convenience_store'"
                class="w-full p-5 text-left">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-bold text-gray-900">Convenience Store</h4>
                <svg class="w-5 h-5 text-gray-400 transition-transform" 
                     :class="selectedPayment === 'convenience_store' ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <!-- Divider Line -->
            <div class="border-t border-gray-200 my-3"></div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-red-600 opacity-60">Indomaret</span>
                <span class="text-sm font-semibold text-blue-600 opacity-60">Alfamart</span>
            </div>
        </button>
        
        <!-- Store Options -->
        <div x-show="selectedPayment === 'convenience_store'" 
             x-collapse
             class="px-5 pb-5 bg-gray-50">
            <div class="grid grid-cols-2 gap-3">
                <label class="relative cursor-pointer">
                    <input type="radio" name="store_type" value="indomaret" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all flex items-center justify-center">
                        <span class="text-lg font-bold text-red-600">Indomaret</span>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="store_type" value="alfamart" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-white hover:border-gray-300 peer-checked:border-black peer-checked:bg-gray-50 transition-all flex items-center justify-center">
                        <span class="text-lg font-bold text-blue-600">Alfamart</span>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="payment_method" :value="selectedPayment">
    <input type="hidden" name="payment_detail" :value="getPaymentDetail()">
</div>

<script>
window.paymentMethods = function(initialSelection = 'bank_transfer') {
    return {
        selectedPayment: initialSelection,
        
        init() {
            // Watch for payment method changes and log for debugging
            this.$watch('selectedPayment', (value) => {
                console.log('Payment method changed to:', value);
                console.log('Payment detail:', this.getPaymentDetail());
            });
        },
        
        getPaymentDetail() {
            // Get the selected detail based on payment type
            if (this.selectedPayment === 'bank_transfer') {
                const selected = document.querySelector('input[name="bank_type"]:checked');
                return selected ? selected.value : 'bca';
            } else if (this.selectedPayment === 'ewallet') {
                const selected = document.querySelector('input[name="ewallet_type"]:checked');
                return selected ? selected.value : '';
            } else if (this.selectedPayment === 'convenience_store') {
                const selected = document.querySelector('input[name="store_type"]:checked');
                return selected ? selected.value : '';
            } else if (this.selectedPayment === 'qris') {
                return 'qris';
            }
            return '';
        }
    };
};
</script>
