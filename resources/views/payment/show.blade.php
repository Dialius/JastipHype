@extends('layouts.app')

@section('title', 'Payment - ' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50">
    @if($payment->isPending() && (!$payment->payment_type || $payment->payment_type === 'pending') && !isset($payment->payment_data['snap_token']))
        <!-- Payment Method Selection -->
        <div class="py-12 px-4">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Select Payment Method</h2>

                <form action="{{ route('payment.process', $order->order_number) }}" method="POST" id="payment-form">
                    @csrf

                    <div class="space-y-4">
                        <!-- Virtual Account -->
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <button type="button"
                                class="w-full px-6 py-4 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition focus:outline-none"
                                onclick="toggleAccordion('va-methods')">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    <span class="font-semibold text-gray-900">Virtual Account (VA)</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex gap-1.5 items-center">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/200px-Bank_Central_Asia.svg.png" alt="BCA" class="h-5 object-contain" onerror="this.style.display='none'">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/200px-Bank_Mandiri_logo_2016.svg.png" alt="Mandiri" class="h-5 object-contain" onerror="this.style.display='none'">
                                        <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/200px-BNI_logo.svg.png" alt="BNI" class="h-5 object-contain" onerror="this.style.display='none'">
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="va-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </button>
                            <div id="va-methods" class="hidden px-6 py-5 border-t border-gray-100">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                    <!-- BCA -->
                                    <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300 transition-all payment-label" id="label-bca">
                                        <input type="radio" name="payment_method" value="bank_transfer"
                                            class="sr-only"
                                            onchange="selectBank('bca', this)">
                                        <div class="w-12 h-8 flex items-center justify-center bg-white rounded border border-gray-100 p-1 flex-shrink-0">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/200px-Bank_Central_Asia.svg.png"
                                                alt="BCA" class="max-h-6 max-w-full object-contain"
                                                onerror="this.parentElement.innerHTML='<span style=\'font-size:10px;font-weight:700;color:#005BAC\'>BCA</span>'">
                                        </div>
                                        <span class="font-medium text-gray-900 text-sm">BCA VA</span>
                                        <div class="ml-auto w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center radio-dot flex-shrink-0">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                    </label>

                                    <!-- Mandiri -->
                                    <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300 transition-all payment-label" id="label-mandiri">
                                        <input type="radio" name="payment_method" value="bank_transfer"
                                            class="sr-only"
                                            onchange="selectBank('mandiri', this)">
                                        <div class="w-12 h-8 flex items-center justify-center bg-white rounded border border-gray-100 p-1 flex-shrink-0">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/200px-Bank_Mandiri_logo_2016.svg.png"
                                                alt="Mandiri" class="max-h-6 max-w-full object-contain"
                                                onerror="this.parentElement.innerHTML='<span style=\'font-size:10px;font-weight:700;color:#003087\'>MANDIRI</span>'">
                                        </div>
                                        <span class="font-medium text-gray-900 text-sm">Mandiri VA</span>
                                        <div class="ml-auto w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center radio-dot flex-shrink-0">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                    </label>

                                    <!-- BNI -->
                                    <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300 transition-all payment-label" id="label-bni">
                                        <input type="radio" name="payment_method" value="bank_transfer"
                                            class="sr-only"
                                            onchange="selectBank('bni', this)">
                                        <div class="w-12 h-8 flex items-center justify-center bg-white rounded border border-gray-100 p-1 flex-shrink-0">
                                            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/200px-BNI_logo.svg.png"
                                                alt="BNI" class="max-h-6 max-w-full object-contain"
                                                onerror="this.parentElement.innerHTML='<span style=\'font-size:10px;font-weight:700;color:#F58220\'>BNI</span>'">
                                        </div>
                                        <span class="font-medium text-gray-900 text-sm">BNI VA</span>
                                        <div class="ml-auto w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center radio-dot flex-shrink-0">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                    </label>

                                    <!-- BRI -->
                                    <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300 transition-all payment-label" id="label-bri">
                                        <input type="radio" name="payment_method" value="bank_transfer"
                                            class="sr-only"
                                            onchange="selectBank('bri', this)">
                                        <div class="w-12 h-8 flex items-center justify-center bg-white rounded border border-gray-100 p-1 flex-shrink-0">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/200px-BANK_BRI_logo.svg.png"
                                                alt="BRI" class="max-h-6 max-w-full object-contain"
                                                onerror="this.parentElement.innerHTML='<span style=\'font-size:10px;font-weight:700;color:#00529B\'>BRI</span>'">
                                        </div>
                                        <span class="font-medium text-gray-900 text-sm">BRI VA</span>
                                        <div class="ml-auto w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center radio-dot flex-shrink-0">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                    </label>

                                </div>
                            </div>
                        </div>

                        <!-- E-Wallet & QRIS -->
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <button type="button"
                                class="w-full px-6 py-4 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition focus:outline-none"
                                onclick="toggleAccordion('ewallet-methods')">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span class="font-semibold text-gray-900">E-Wallet &amp; QRIS</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex gap-1.5 items-center">
                                        <span class="text-xs font-bold px-1.5 py-0.5 rounded" style="background:#00AED6;color:white">QRIS</span>
                                        <span class="text-xs font-bold px-1.5 py-0.5 rounded" style="background:#00A651;color:white">GoPay</span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="ewallet-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </button>
                            <div id="ewallet-methods" class="hidden px-6 py-5 border-t border-gray-100">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                    <!-- QRIS -->
                                    <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300 transition-all payment-label" id="label-qris">
                                        <input type="radio" name="payment_method" value="qris"
                                            class="sr-only"
                                            onchange="selectWallet('qris', '', this)">
                                        <div class="w-12 h-8 flex items-center justify-center rounded flex-shrink-0 overflow-hidden">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/200px-Logo_QRIS.svg.png"
                                                alt="QRIS" class="max-h-7 max-w-full object-contain"
                                                onerror="this.parentElement.innerHTML='<span style=\'font-size:10px;font-weight:700;background:#00AED6;color:white;padding:2px 5px;border-radius:4px\'>QRIS</span>'">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 text-sm">QRIS (Any app)</p>
                                            <p class="text-xs text-gray-500">GoPay, OVO, DANA, dll</p>
                                        </div>
                                        <div class="ml-auto w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center radio-dot flex-shrink-0">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                    </label>

                                    <!-- GoPay -->
                                    <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300 transition-all payment-label" id="label-gopay">
                                        <input type="radio" name="payment_method" value="gopay"
                                            class="sr-only"
                                            onchange="selectWallet('gopay', 'gopay', this)">
                                        <div class="w-12 h-8 flex items-center justify-center rounded flex-shrink-0 overflow-hidden">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/200px-Gopay_logo.svg.png"
                                                alt="GoPay" class="max-h-7 max-w-full object-contain"
                                                onerror="this.parentElement.innerHTML='<span style=\'font-size:10px;font-weight:700;background:#00A651;color:white;padding:2px 5px;border-radius:4px\'>GoPay</span>'">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 text-sm">GoPay</p>
                                            <p class="text-xs text-gray-500">Bayar dengan GoPay</p>
                                        </div>
                                        <div class="ml-auto w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center radio-dot flex-shrink-0">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>
                                    </label>

                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="payment_detail" id="payment_detail" value="">

                    <div class="mt-8">
                        <button type="submit"
                            class="w-full py-4 bg-black text-white rounded-xl hover:bg-gray-800 transition font-bold text-base disabled:opacity-40 disabled:cursor-not-allowed"
                            id="pay-button" disabled>
                            Bayar Rp {{ number_format($order->total, 0, ',', '.') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function toggleAccordion(id) {
                const content = document.getElementById(id);
                const iconId = id.replace('-methods', '-icon');
                const icon = document.getElementById(iconId);
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            }

            function highlightLabel(labelId) {
                document.querySelectorAll('.payment-label').forEach(label => {
                    label.classList.remove('border-black', 'bg-gray-50');
                    label.classList.add('border-gray-200');
                    const dot = label.querySelector('.radio-dot');
                    if (dot) { dot.classList.remove('border-black', 'bg-black'); dot.classList.add('border-gray-300'); }
                });
                const selected = document.getElementById(labelId);
                if (selected) {
                    selected.classList.remove('border-gray-200');
                    selected.classList.add('border-black', 'bg-gray-50');
                    const dot = selected.querySelector('.radio-dot');
                    if (dot) { dot.classList.remove('border-gray-300'); dot.classList.add('border-black', 'bg-black'); }
                }
            }

            function selectBank(bank, input) {
                document.getElementById('payment_detail').value = bank;
                document.getElementById('pay-button').disabled = false;
                highlightLabel('label-' + bank);
            }

            function selectWallet(method, detail, input) {
                document.getElementById('payment_detail').value = detail;
                document.getElementById('pay-button').disabled = false;
                highlightLabel('label-' + method);
            }
        </script>

    @elseif($payment->isPending() && $payment->payment_type && $payment->payment_type !== 'pending' && !isset($payment->payment_data['snap_token']))
        <!-- Payment Instructions -->
        <div class="py-12 px-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-8">
                    <div class="p-6 sm:p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Complete Your Payment</h2>
                        
                        <div class="bg-gray-50 rounded-lg p-6 mb-6 text-center">
                            <p class="text-sm text-gray-500 mb-1">Total Amount</p>
                            <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>

                        @if($instructions)
                            <div class="space-y-6">
                                <div class="border border-gray-200 rounded-lg p-6 text-center">
                                    <h3 class="font-semibold text-gray-900 mb-4">{{ $instructions['title'] ?? ($instructions['type'] ?? 'Payment Instructions') }}</h3>
                                    
                                    @if(isset($instructions['qr_url']))
                                        <div class="flex justify-center mb-4">
                                            <img src="{{ $instructions['qr_url'] }}" alt="QR Code" class="w-64 h-64 border border-gray-200 rounded-lg p-2">
                                        </div>
                                    @endif
                                    
                                    @if(isset($instructions['deeplink']))
                                        <div class="text-center mb-4">
                                            <a href="{{ $instructions['deeplink'] }}" target="_blank" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                                Pay with {{ ucfirst($order->payment_detail ?? $payment->payment_type) }} App
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Virtual Account Details Box -->
                                    @if(isset($instructions['va_number']))
                                        <div class="my-6 p-4 bg-gray-50 border border-gray-100 rounded-xl max-w-md mx-auto">
                                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Nomor Virtual Account {{ $instructions['bank'] ?? '' }}</p>
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="text-2xl font-bold font-mono text-gray-900 select-all" id="va-number-text">{{ $instructions['va_number'] }}</span>
                                                <button type="button" onclick="copyToClipboard('{{ $instructions['va_number'] }}', this)" class="text-gray-500 hover:text-black p-1.5 hover:bg-gray-100 rounded-lg transition" title="Salin">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Mandiri Bill Payment Details Box -->
                                    @if(isset($instructions['bill_key']) && isset($instructions['biller_code']))
                                        <div class="my-6 p-4 bg-gray-50 border border-gray-100 rounded-xl max-w-md mx-auto space-y-3">
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Kode Perusahaan (Biller Code)</p>
                                                <div class="flex items-center justify-center gap-2">
                                                    <span class="text-xl font-bold font-mono text-gray-900 select-all">{{ $instructions['biller_code'] }}</span>
                                                    <button type="button" onclick="copyToClipboard('{{ $instructions['biller_code'] }}', this)" class="text-gray-500 hover:text-black p-1 hover:bg-gray-100 rounded-lg transition" title="Salin">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="border-t border-gray-200 pt-3">
                                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Nomor Bill Key</p>
                                                <div class="flex items-center justify-center gap-2">
                                                    <span class="text-xl font-bold font-mono text-gray-900 select-all">{{ $instructions['bill_key'] }}</span>
                                                    <button type="button" onclick="copyToClipboard('{{ $instructions['bill_key'] }}', this)" class="text-gray-500 hover:text-black p-1 hover:bg-gray-100 rounded-lg transition" title="Salin">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Convenience Store Details Box -->
                                    @if(isset($instructions['payment_code']) && $instructions['type'] === 'cstore')
                                        <div class="my-6 p-4 bg-gray-50 border border-gray-100 rounded-xl max-w-md mx-auto">
                                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Kode Pembayaran {{ $instructions['store'] ?? '' }}</p>
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="text-2xl font-bold font-mono text-gray-900 select-all">{{ $instructions['payment_code'] }}</span>
                                                <button type="button" onclick="copyToClipboard('{{ $instructions['payment_code'] }}', this)" class="text-gray-500 hover:text-black p-1.5 hover:bg-gray-100 rounded-lg transition" title="Salin">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    <script>
                                        function copyToClipboard(text, button) {
                                            navigator.clipboard.writeText(text).then(function() {
                                                const originalHTML = button.innerHTML;
                                                button.innerHTML = '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                                                setTimeout(function() {
                                                    button.innerHTML = originalHTML;
                                                }, 2000);
                                            }).catch(function(err) {
                                                console.error('Could not copy text: ', err);
                                            });
                                        }

                                        // Poll every 3 seconds to check if payment status has changed to success or failed
                                        document.addEventListener("DOMContentLoaded", function() {
                                            console.log("Starting payment status polling for {{ $order->order_number }}...");
                                            const pollInterval = setInterval(function() {
                                                fetch('{{ route('payment.status', $order->order_number) }}')
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        console.log('Polled status data:', data);
                                                        if (data.success && (data.is_success || data.is_failed)) {
                                                            clearInterval(pollInterval);
                                                            console.log('Payment status updated, reloading page...');
                                                            window.location.reload();
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error polling payment status:', error);
                                                    });
                                            }, 3000);
                                        });
                                    </script>

                                    @if(isset($instructions['steps']) && count($instructions['steps']) > 0)
                                        <ul class="space-y-3 text-sm text-gray-600 text-left mt-6">
                                            @foreach($instructions['steps'] as $index => $step)
                                                <li class="flex items-start">
                                                    <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-blue-100 text-blue-800 font-bold text-xs mr-3 mt-0.5">{{ $index + 1 }}</span>
                                                    <span>{!! $step !!}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    
                                    @if($payment->expiry_time)
                                    <div class="mt-6 pt-4 border-t border-gray-200 text-center text-sm text-red-600 font-medium">
                                        Please complete payment before: <br>
                                        {{ date('d M Y, H:i', strtotime($payment->expiry_time)) }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center text-gray-600">
                                Instructions are currently unavailable. Please check your email or contact support.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @elseif($payment->isPending() && isset($payment->payment_data['snap_token']))
        <!-- Midtrans Snap Payment Container - Full Width -->
        <div class="bg-white shadow-sm" style="overflow: hidden; height: calc(100vh - 80px);">
            <!-- Snap Container - Full Screen -->
            <div id="snap-container" class="min-h-screen" style="overflow: hidden !important; scrollbar-width: none; -ms-overflow-style: none; height: calc(100vh - 80px);"></div>
        </div>
    @elseif($payment->isSuccess())
        <div class="py-12 px-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-green-50 border border-green-200 rounded-2xl p-8 text-center">
                    <svg class="w-20 h-20 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-green-900 mb-2">Payment Successful!</h3>
                    <p class="text-green-700 mb-6">Thank you for your payment. Your order is being processed.</p>
                    <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="py-12 px-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-red-50 border border-red-200 rounded-2xl p-8 text-center">
                    <svg class="w-20 h-20 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-red-900 mb-2">Payment {{ $payment->getStatusLabel() }}</h3>
                    <p class="text-red-700 mb-6">Please create a new order if you want to continue shopping.</p>
                    <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Order Details - Full Width -->
    <div class="bg-white border-t border-gray-200">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Order Details - #{{ $order->order_number }}</h3>
                
                <!-- Order Items -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($order->items as $item)
                    <div class="bg-gray-50 rounded-lg p-4 flex items-center gap-4">
                        <img src="{{ product_image_url($item->product) }}" 
                             alt="{{ $item->product->name }}"
                             class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 truncate">{{ $item->product->name }}</h4>
                            <p class="text-sm text-gray-600 mt-1">Size: {{ $item->size }}</p>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                            <p class="font-bold text-gray-900 mt-2">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Shipping Info -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Shipping Address</h4>
                            <p class="text-sm text-gray-600">{{ $order->name }}</p>
                            <p class="text-sm text-gray-600">{{ $order->phone }}</p>
                            <p class="text-sm text-gray-600 mt-2">{{ $order->address }}</p>
                            <p class="text-sm text-gray-600">{{ $order->postal_code }}</p>
                        </div>

                        <!-- Payment Info -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Payment Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($payment->isSuccess()) bg-green-100 text-green-800
                                        @elseif($payment->isPending()) bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $payment->getStatusLabel() }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Order Date:</span>
                                    <span class="text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Price Summary -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Price Summary</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping:</span>
                                    <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-base font-bold border-t border-gray-300 pt-2 mt-2">
                                    <span class="text-gray-900">Total:</span>
                                    <span class="text-blue-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('home') }}" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition font-medium">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($payment->isPending() && isset($payment->payment_data['snap_token']))
    <!-- Midtrans Snap Script -->
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <style>
        /* Hide scrollbar on payment page */
        body {
            overflow-x: hidden;
        }
        
        /* Make Snap container seamless and full */
        #snap-container {
            width: 100%;
            height: calc(100vh - 80px) !important;
            min-height: calc(100vh - 80px);
            max-height: calc(100vh - 80px);
            overflow: hidden !important;
            position: relative;
        }
        
        /* Remove any default padding/margin from Snap iframe */
        #snap-container iframe {
            width: 100% !important;
            height: calc(100vh - 80px) !important;
            min-height: calc(100vh - 80px) !important;
            max-height: calc(100vh - 80px) !important;
            border: none !important;
            overflow: hidden !important;
            position: absolute;
            top: 0;
            left: 0;
        }
        
        /* Hide all scrollbars - Webkit browsers */
        #snap-container::-webkit-scrollbar,
        #snap-container *::-webkit-scrollbar,
        #snap-container iframe::-webkit-scrollbar,
        #snap-container iframe *::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
            background: transparent !important;
        }
        
        /* Hide scrollbar - Firefox */
        #snap-container,
        #snap-container *,
        #snap-container iframe,
        #snap-container iframe * {
            scrollbar-width: none !important;
        }
        
        /* Hide scrollbar - IE and Edge Legacy */
        #snap-container,
        #snap-container *,
        #snap-container iframe,
        #snap-container iframe * {
            -ms-overflow-style: none !important;
        }
        
        /* Force hide overflow on all elements inside */
        #snap-container * {
            overflow: hidden !important;
        }
    </style>
    
    <script type="text/javascript">
        // Embedded Snap Integration - Show all payment methods
        document.addEventListener("DOMContentLoaded", function(event) { 
            // Embed Snap to container
            window.snap.embed('{{ $payment->payment_data['snap_token'] }}', {
                embedId: 'snap-container',
                onSuccess: function(result){
                    console.log('Payment success:', result);
                    // Check status and reload
                    checkPaymentStatusAndReload();
                },
                onPending: function(result){
                    console.log('Payment pending:', result);
                    // Check status and reload
                    checkPaymentStatusAndReload();
                },
                onError: function(result){
                    console.log('Payment error:', result);
                    alert('Payment failed. Please try again.');
                    // Still check status in case webhook updated it
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                },
                onClose: function(){
                    console.log('Payment popup closed');
                }
            });
            
            // Additional script to hide scrollbar after iframe loads
            setTimeout(function() {
                const container = document.getElementById('snap-container');
                const iframe = container.querySelector('iframe');
                
                if (iframe) {
                    // Hide scrollbar on iframe
                    iframe.style.overflow = 'hidden';
                    iframe.scrolling = 'no';
                    
                    // Try to access iframe content (may be blocked by CORS)
                    try {
                        if (iframe.contentDocument) {
                            iframe.contentDocument.body.style.overflow = 'hidden';
                            iframe.contentDocument.documentElement.style.overflow = 'hidden';
                        }
                    } catch(e) {
                        console.log('Cannot access iframe content (CORS)');
                    }
                }
                
                // Hide scrollbar on container
                container.style.overflow = 'hidden';
            }, 1000);
            
            // Monitor for iframe changes and reapply styles
            const observer = new MutationObserver(function(mutations) {
                const container = document.getElementById('snap-container');
                const iframe = container.querySelector('iframe');
                
                if (iframe) {
                    iframe.style.overflow = 'hidden';
                    iframe.scrolling = 'no';
                    container.style.overflow = 'hidden';
                }
            });
            
            observer.observe(document.getElementById('snap-container'), {
                childList: true,
                subtree: true
            });
        });
        
        // Function to check payment status and reload
        function checkPaymentStatusAndReload() {
            console.log('Checking payment status...');
            
            // Show loading indicator
            const loadingDiv = document.createElement('div');
            loadingDiv.innerHTML = `
                <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 99999;">
                    <div style="background: white; padding: 2rem; border-radius: 1rem; text-align: center;">
                        <div style="width: 3rem; height: 3rem; border: 4px solid #f3f4f6; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
                        <p style="color: #374151; font-weight: 600;">Verifying payment status...</p>
                    </div>
                </div>
                <style>
                    @keyframes spin {
                        to { transform: rotate(360deg); }
                    }
                </style>
            `;
            document.body.appendChild(loadingDiv);
            
            // Wait a bit for webhook to process
            setTimeout(function() {
                fetch('{{ route('payment.status', $order->order_number) }}')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Payment status response:', data);
                        
                        if (data.success) {
                            // Always reload to show updated status
                            window.location.reload();
                        } else {
                            // Still reload to show any updates
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error checking payment status:', error);
                        // Reload anyway to show any updates
                        window.location.reload();
                    });
            }, 2000); // Wait 2 seconds for webhook to process
        }
    </script>
@endif
@endsection
