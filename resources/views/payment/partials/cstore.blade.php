<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Pay at {{ $instructions['store'] }}</h3>
        <p class="text-gray-600">Show the payment code below to the cashier</p>
    </div>

    <!-- Payment Code -->
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-2">Payment Code</p>
            <div class="flex items-center justify-center space-x-2">
                <p class="text-3xl font-bold text-gray-900 tracking-wider" id="payment-code">{{ $instructions['payment_code'] }}</p>
                <button onclick="copyPaymentCode()" class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="mt-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
            <p class="text-sm text-orange-800 text-center">
                <strong>Total Payment:</strong> Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-bold text-gray-900 mb-3">Payment Instructions:</h4>
        
        @if($instructions['store'] === 'INDOMARET')
        <ol class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                <span>Visit the nearest <strong>Indomaret</strong> store</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                <span>Show payment code <strong>{{ $instructions['payment_code'] }}</strong> to the cashier</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                <span>Cashier will process your payment</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">4</span>
                <span>Make payment of <strong>Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</strong></span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">5</span>
                <span>Keep the payment receipt as proof</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">6</span>
                <span>Payment will be automatically verified</span>
            </li>
        </ol>
        @elseif($instructions['store'] === 'ALFAMART')
        <ol class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                <span>Visit the nearest <strong>Alfamart</strong> or <strong>Alfamidi</strong> store</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                <span>Show payment code <strong>{{ $instructions['payment_code'] }}</strong> to the cashier</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                <span>Cashier will process your payment</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">4</span>
                <span>Make payment of <strong>Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</strong></span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">5</span>
                <span>Keep the payment receipt as proof</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">6</span>
                <span>Payment will be automatically verified</span>
            </li>
        </ol>
        @else
        <ol class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                <span>Visit the nearest <strong>{{ $instructions['store'] }}</strong> store</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                <span>Show payment code to the cashier</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                <span>Make payment and keep the receipt</span>
            </li>
        </ol>
        @endif
    </div>

    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
        <p class="text-sm text-yellow-800">
            <strong>Note:</strong> Make sure you pay the exact amount. Payment will be automatically verified after the cashier processes the transaction.
        </p>
    </div>
</div>

<script>
function copyPaymentCode() {
    const code = document.getElementById('payment-code').textContent;
    navigator.clipboard.writeText(code).then(() => {
        alert('Payment code copied successfully!');
    });
}
</script>
