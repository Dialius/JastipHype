<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Pay with {{ $provider }}</h3>
        <p class="text-gray-600 mb-6">Click the button below to open {{ $provider }} app</p>

        <!-- Deeplink Button -->
        @if(isset($instructions['deeplink']))
        <a href="{{ $instructions['deeplink'] }}" 
           class="inline-block px-8 py-4 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition mb-6">
            Open {{ $provider }} App
        </a>
        @endif

        <!-- QR Code Alternative (for GoPay) -->
        @if(isset($instructions['qr_code']))
        <div class="mt-6">
            <p class="text-sm text-gray-600 mb-4">Or scan this QR code:</p>
            <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg">
                <img src="{{ $instructions['qr_code'] }}" alt="QR Code" class="w-64 h-64">
            </div>
        </div>
        @endif

        <!-- Instructions -->
        <div class="bg-gray-50 rounded-lg p-4 text-left mt-6">
            <h4 class="font-bold text-gray-900 mb-3">Payment Instructions:</h4>
            <ol class="space-y-2 text-sm text-gray-700">
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                    <span>Click the "Open {{ $provider }} App" button above</span>
                </li>
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                    <span>{{ $provider }} app will open automatically</span>
                </li>
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                    <span>Confirm payment of <strong>Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</strong></span>
                </li>
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">4</span>
                    <span>Enter your {{ $provider }} PIN</span>
                </li>
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">5</span>
                    <span>Payment will be automatically verified</span>
                </li>
            </ol>
        </div>

        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>Note:</strong> If the button doesn't work, make sure {{ $provider }} app is installed on your device.
            </p>
        </div>
    </div>
</div>
