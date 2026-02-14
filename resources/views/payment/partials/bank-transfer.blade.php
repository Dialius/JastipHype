<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Bank Transfer {{ $instructions['bank'] }}</h3>
        <p class="text-gray-600">Transfer to the Virtual Account number below</p>
    </div>

    <!-- VA Number -->
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-2">Virtual Account Number</p>
            <div class="flex items-center justify-center space-x-2">
                <p class="text-3xl font-bold text-gray-900 tracking-wider" id="va-number">{{ $instructions['va_number'] }}</p>
                <button onclick="copyVA()" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-2">Bank {{ $instructions['bank'] }}</p>
        </div>

        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800 text-center">
                <strong>Total Transfer:</strong> Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-bold text-gray-900 mb-3">Payment Instructions:</h4>
        
        @if($instructions['bank'] === 'BCA')
        <div class="space-y-3 text-sm text-gray-700">
            <div>
                <p class="font-semibold mb-2">Via BCA ATM:</p>
                <ol class="space-y-1 ml-4 list-decimal">
                    <li>Insert your ATM card and PIN</li>
                    <li>Select <strong>Other Transactions > Transfer > to BCA Virtual Account</strong></li>
                    <li>Enter Virtual Account number: <strong>{{ $instructions['va_number'] }}</strong></li>
                    <li>Confirm payment details</li>
                    <li>Complete the transaction</li>
                </ol>
            </div>
            <div>
                <p class="font-semibold mb-2">Via m-BCA (Mobile Banking):</p>
                <ol class="space-y-1 ml-4 list-decimal">
                    <li>Login to m-BCA app</li>
                    <li>Select <strong>m-Transfer > BCA Virtual Account</strong></li>
                    <li>Enter Virtual Account number: <strong>{{ $instructions['va_number'] }}</strong></li>
                    <li>Confirm payment details</li>
                    <li>Enter m-BCA PIN</li>
                </ol>
            </div>
        </div>
        @elseif($instructions['bank'] === 'BNI')
        <div class="space-y-3 text-sm text-gray-700">
            <div>
                <p class="font-semibold mb-2">Via BNI ATM:</p>
                <ol class="space-y-1 ml-4 list-decimal">
                    <li>Insert your ATM card and PIN</li>
                    <li>Select <strong>Other Menu > Transfer > Savings Account</strong></li>
                    <li>Select <strong>Virtual Account Billing</strong></li>
                    <li>Enter Virtual Account number: <strong>{{ $instructions['va_number'] }}</strong></li>
                    <li>Confirm payment details</li>
                    <li>Complete the transaction</li>
                </ol>
            </div>
            <div>
                <p class="font-semibold mb-2">Via BNI Mobile Banking:</p>
                <ol class="space-y-1 ml-4 list-decimal">
                    <li>Login to BNI Mobile Banking app</li>
                    <li>Select <strong>Transfer > Virtual Account Billing</strong></li>
                    <li>Select debit account</li>
                    <li>Enter Virtual Account number: <strong>{{ $instructions['va_number'] }}</strong></li>
                    <li>Confirm payment details</li>
                    <li>Enter transaction password</li>
                </ol>
            </div>
        </div>
        @elseif($instructions['bank'] === 'BRI')
        <div class="space-y-3 text-sm text-gray-700">
            <div>
                <p class="font-semibold mb-2">Via BRI ATM:</p>
                <ol class="space-y-1 ml-4 list-decimal">
                    <li>Insert your ATM card and PIN</li>
                    <li>Select <strong>Other Transactions > Payment > Other > BRIVA</strong></li>
                    <li>Enter Virtual Account number: <strong>{{ $instructions['va_number'] }}</strong></li>
                    <li>Confirm payment details</li>
                    <li>Complete the transaction</li>
                </ol>
            </div>
            <div>
                <p class="font-semibold mb-2">Via BRI Mobile:</p>
                <ol class="space-y-1 ml-4 list-decimal">
                    <li>Login to BRI Mobile app</li>
                    <li>Select <strong>Payment > BRIVA</strong></li>
                    <li>Enter Virtual Account number: <strong>{{ $instructions['va_number'] }}</strong></li>
                    <li>Confirm payment details</li>
                    <li>Enter PIN</li>
                </ol>
            </div>
        </div>
        @else
        <ol class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                <span>Open {{ $instructions['bank'] }} mobile banking app or ATM</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                <span>Select Transfer or Payment menu</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                <span>Select Virtual Account or VA Billing</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">4</span>
                <span>Enter Virtual Account number: <strong>{{ $instructions['va_number'] }}</strong></span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">5</span>
                <span>Confirm payment and complete the transaction</span>
            </li>
        </ol>
        @endif
    </div>

    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
        <p class="text-sm text-yellow-800">
            <strong>Important:</strong> Transfer must be made with the exact amount. Payment will be automatically verified after successful transfer.
        </p>
    </div>
</div>

<script>
function copyVA() {
    const vaNumber = document.getElementById('va-number').textContent;
    navigator.clipboard.writeText(vaNumber).then(() => {
        alert('Virtual Account number copied successfully!');
    });
}
</script>
