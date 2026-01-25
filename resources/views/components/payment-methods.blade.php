@props(['selected' => 'bank_transfer'])

<div x-data="paymentMethods('{{ $selected }}')" class="space-y-3">
    <!-- QRIS Payment -->
    <x-payment-card 
        method="qris" 
        name="QRIS" 
        logo="{{ asset('images/payment/ewallet/qris.svg') }}"
        description="Pay with any e-wallet app"
        :available="true"
        :selected="$selected === 'qris'" />
    
    <!-- E-Wallets -->
    <div x-show="selectedPayment === 'qris'" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="grid grid-cols-2 gap-3 ml-4">
        <div class="bg-gray-50 rounded-lg p-3 text-center hover:bg-gray-100 transition-colors">
            <div class="w-6 h-6 mx-auto mb-1 flex items-center justify-center text-xs font-bold text-gray-600 bg-gray-50 rounded">
                G
            </div>
            <span class="text-xs text-gray-600">GoPay</span>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 text-center hover:bg-gray-100 transition-colors">
            <div class="w-6 h-6 mx-auto mb-1 flex items-center justify-center text-xs font-bold text-gray-600 bg-gray-50 rounded">
                D
            </div>
            <span class="text-xs text-gray-600">Dana</span>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 text-center hover:bg-gray-100 transition-colors">
            <div class="w-6 h-6 mx-auto mb-1 flex items-center justify-center text-xs font-bold text-gray-600 bg-gray-50 rounded">
                O
            </div>
            <span class="text-xs text-gray-600">OVO</span>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 text-center hover:bg-gray-100 transition-colors">
            <div class="w-6 h-6 mx-auto mb-1 flex items-center justify-center text-xs font-bold text-gray-600 bg-gray-50 rounded">
                SP
            </div>
            <span class="text-xs text-gray-600">ShopeePay</span>
        </div>
    </div>
    
    <!-- Bank Transfer -->
    <x-payment-card 
        method="bank_transfer" 
        name="Bank Transfer" 
        logo="{{ asset('images/payment/banks/combined.svg') }}"
        description="Manual transfer to our bank accounts"
        :available="true"
        :selected="$selected === 'bank_transfer'" />
    
    <!-- Bank Details (Shown when Bank Transfer is selected) -->
    <div x-show="selectedPayment === 'bank_transfer'" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="bg-gray-50 rounded-xl p-4 border border-gray-200">
        
        <h4 class="font-medium text-gray-900 mb-3">Our Bank Accounts:</h4>
        
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-black transition-colors">
                <div class="h-5 mb-2 flex items-center justify-center text-xs font-bold text-blue-600 bg-blue-50 rounded">
                    BCA
                </div>
                <p class="text-xs font-medium">BCA</p>
                <p class="text-xs text-gray-600">123-456-7890</p>
            </div>
            
            <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-black transition-colors">
                <div class="h-5 mb-2 flex items-center justify-center text-xs font-bold text-blue-600 bg-blue-50 rounded">
                    Mandiri
                </div>
                <p class="text-xs font-medium">Mandiri</p>
                <p class="text-xs text-gray-600">987-654-3210</p>
            </div>
            
            <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-black transition-colors">
                <div class="h-5 mb-2 flex items-center justify-center text-xs font-bold text-orange-600 bg-orange-50 rounded">
                    BNI
                </div>
                <p class="text-xs font-medium">BNI</p>
                <p class="text-xs text-gray-600">456-789-0123</p>
            </div>
            
            <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-black transition-colors">
                <div class="h-5 mb-2 flex items-center justify-center text-xs font-bold text-orange-600 bg-orange-50 rounded">
                    BRI
                </div>
                <p class="text-xs font-medium">BRI</p>
                <p class="text-xs text-gray-600">789-012-3456</p>
            </div>
        </div>
        
        <p class="text-xs text-gray-500 mt-3 text-center">
            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            All transfers are secured and confirmed within 1-2 hours
        </p>
    </div>
    
    <!-- COD (Coming Soon) -->
    <x-payment-card 
        method="cod" 
        name="Cash on Delivery" 
        logo="{{ asset('images/payment/other/cod.svg') }}"
        description="Pay when you receive your order"
        :available="false" />
</div>

<script>
window.paymentMethods = function(initialSelection = 'bank_transfer') {
    return {
        selectedPayment: initialSelection
    };
};
</script>