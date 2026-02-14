<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Scan QRIS Code</h3>
        <p class="text-gray-600 mb-6">Scan the QR code below using your e-wallet or mobile banking app</p>

        <!-- QR Code -->
        @if(isset($instructions['qr_string']))
        <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg mb-6">
            <div id="qrcode"></div>
        </div>
        @endif

        <!-- Instructions -->
        <div class="bg-gray-50 rounded-lg p-4 text-left">
            <h4 class="font-bold text-gray-900 mb-3">Payment Instructions:</h4>
            <ol class="space-y-2 text-sm text-gray-700">
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                    <span>Open your e-wallet or mobile banking app (GoPay, OVO, Dana, ShopeePay, etc.)</span>
                </li>
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                    <span>Select "Scan QR" or "QRIS" menu</span>
                </li>
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                    <span>Scan the QR code above</span>
                </li>
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">4</span>
                    <span>Confirm payment of <strong>Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</strong></span>
                </li>
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">5</span>
                    <span>Payment will be automatically verified</span>
                </li>
            </ol>
        </div>
    </div>
</div>

@if(isset($instructions['qr_string']))
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
new QRCode(document.getElementById("qrcode"), {
    text: "{{ $instructions['qr_string'] }}",
    width: 256,
    height: 256,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});
</script>
@endif
