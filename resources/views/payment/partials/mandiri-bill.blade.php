<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Mandiri Bill Payment</h3>
        <p class="text-gray-600">Pay via Mandiri ATM or Internet Banking</p>
    </div>

    <!-- Payment Codes -->
    <div class="bg-gray-50 rounded-lg p-6 mb-6 space-y-4">
        <div>
            <p class="text-sm text-gray-600 mb-2">Company Code (Biller Code)</p>
            <div class="flex items-center justify-between bg-white p-3 rounded-lg">
                <p class="text-xl font-bold text-gray-900" id="biller-code">{{ $instructions['biller_code'] }}</p>
                <button onclick="copyCode('biller-code')" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div>
            <p class="text-sm text-gray-600 mb-2">Payment Code (Bill Key)</p>
            <div class="flex items-center justify-between bg-white p-3 rounded-lg">
                <p class="text-xl font-bold text-gray-900" id="bill-key">{{ $instructions['bill_key'] }}</p>
                <button onclick="copyCode('bill-key')" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800 text-center">
                <strong>Total Payment:</strong> Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-bold text-gray-900 mb-3">Payment Instructions:</h4>
        
        <div class="space-y-3 text-sm text-gray-700">
            <div>
                <p class="font-semibold mb-2">Via Mandiri ATM:</p>
                <ol class="space-y-1 ml-4 list-decimal">
                    <li>Insert your ATM card and PIN</li>
                    <li>Select <strong>Pay/Buy</strong> menu</li>
                    <li>Select <strong>Multi Payment</strong></li>
                    <li>Enter Company Code: <strong>{{ $instructions['biller_code'] }}</strong></li>
                    <li>Enter Payment Code: <strong>{{ $instructions['bill_key'] }}</strong></li>
                    <li>Confirm payment details</li>
                    <li>Complete the transaction</li>
                </ol>
            </div>

            <div>
                <p class="font-semibold mb-2">Via Mandiri Internet Banking:</p>
                <ol class="space-y-1 ml-4 list-decimal">
                    <li>Login to Mandiri Internet Banking</li>
                    <li>Select <strong>Payment > Multi Payment</strong> menu</li>
                    <li>Select service provider: <strong>Midtrans</strong></li>
                    <li>Enter Payment Code: <strong>{{ $instructions['bill_key'] }}</strong></li>
                    <li>Confirm payment details</li>
                    <li>Enter PIN/Token</li>
                </ol>
            </div>

            <div>
                <p class="font-semibold mb-2">Via Livin' by Mandiri (Mobile Banking):</p>
                <ol class="space-y-1 ml-4 list-decimal">
                    <li>Login to Livin' by Mandiri app</li>
                    <li>Select <strong>Pay > Multi Payment</strong> menu</li>
                    <li>Select service provider: <strong>Midtrans</strong></li>
                    <li>Enter Payment Code: <strong>{{ $instructions['bill_key'] }}</strong></li>
                    <li>Confirm payment details</li>
                    <li>Enter MPIN</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
        <p class="text-sm text-yellow-800">
            <strong>Important:</strong> Payment will be automatically verified after successful transaction.
        </p>
    </div>
</div>

<script>
function copyCode(elementId) {
    const code = document.getElementById(elementId).textContent;
    navigator.clipboard.writeText(code).then(() => {
        alert('Code copied successfully!');
    });
}
</script>
