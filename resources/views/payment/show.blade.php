@extends('layouts.app')

@section('title', 'Payment - ' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center py-8 px-4">
    <div class="w-full max-w-5xl">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Menunggu Pembayaran</h1>
                    <p class="text-sm text-gray-600">Order #{{ $order->order_number }}</p>
                </div>
                <div class="text-left md:text-right">
                    <div class="text-sm text-gray-600 mb-1">Total Pembayaran</div>
                    <div class="text-4xl font-bold text-blue-600">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="flex flex-wrap items-center gap-3">
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($payment->isSuccess()) bg-green-100 text-green-800
                    @elseif($payment->isPending()) bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ $payment->getStatusLabel() }}
                </span>
                @if($payment->expiry_time && $payment->isPending())
                <span class="text-sm text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Berlaku hingga: {{ $payment->expiry_time->format('d M Y, H:i') }} WIB
                </span>
                @endif
            </div>
        </div>

        <!-- Payment Instructions -->
        @if($payment->isPending())
            @if($instructions['type'] === 'qris')
                @include('payment.partials.qris', ['instructions' => $instructions, 'payment' => $payment])
            @elseif($instructions['type'] === 'gopay')
                @include('payment.partials.ewallet', ['instructions' => $instructions, 'payment' => $payment, 'provider' => 'GoPay'])
            @elseif($instructions['type'] === 'shopeepay')
                @include('payment.partials.ewallet', ['instructions' => $instructions, 'payment' => $payment, 'provider' => 'ShopeePay'])
            @elseif($instructions['type'] === 'bank_transfer')
                @include('payment.partials.bank-transfer', ['instructions' => $instructions, 'payment' => $payment])
            @elseif($instructions['type'] === 'echannel')
                @include('payment.partials.mandiri-bill', ['instructions' => $instructions, 'payment' => $payment])
            @elseif($instructions['type'] === 'cstore')
                @include('payment.partials.cstore', ['instructions' => $instructions, 'payment' => $payment])
            @endif
        @elseif($payment->isSuccess())
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-bold text-green-900 mb-2">Pembayaran Berhasil!</h3>
                <p class="text-green-700">Terima kasih atas pembayaran Anda. Pesanan Anda sedang diproses.</p>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-bold text-red-900 mb-2">Pembayaran {{ $payment->getStatusLabel() }}</h3>
                <p class="text-red-700">Silakan buat pesanan baru jika Anda ingin melanjutkan pembelian.</p>
            </div>
        @endif

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Pesanan</h3>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center space-x-4">
                    <img src="{{ $item->product->productImages->first()?->image_url ?? '/images/placeholder.png' }}" 
                         alt="{{ $item->product->name }}"
                         class="w-16 h-16 object-cover rounded">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                        <p class="text-sm text-gray-600">Size: {{ $item->size }} | Qty: {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="border-t mt-4 pt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Ongkos Kirim</span>
                    <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-2">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Kembali ke Beranda
            </a>
            
            @if($payment->isPending() && !isset($payment->payment_data['snap_token']))
            <button onclick="checkPaymentStatus()" 
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Cek Status Pembayaran
            </button>
            @endif
        </div>
    </div>
</div>

@if($payment->isPending() && isset($payment->payment_data['snap_token']))
    <!-- Midtrans Snap Script -->
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        // Embedded Snap Integration
        document.addEventListener("DOMContentLoaded", function(event) { 
            // Embed Snap to container
            window.snap.embed('{{ $payment->payment_data['snap_token'] }}', {
                embedId: 'snap-container',
                onSuccess: function(result){
                    window.location.reload();
                },
                onPending: function(result){
                    window.location.reload();
                },
                onError: function(result){
                    window.location.reload();
                },
                onClose: function(){
                    // window.location.reload();
                }
            });
        });
    </script>
@endif

@if($payment->isPending() && !isset($payment->payment_data['snap_token']))
<script>
let checkInterval;

function checkPaymentStatus() {
    fetch('{{ route('payment.status', $order->order_number) }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.is_success) {
                    window.location.reload();
                } else if (data.is_failed) {
                    window.location.reload();
                }
            }
        })
        .catch(error => console.error('Error checking payment status:', error));
}

// Auto-check every 10 seconds
checkInterval = setInterval(checkPaymentStatus, 10000);

// Stop checking when page is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(checkInterval);
    } else {
        checkInterval = setInterval(checkPaymentStatus, 10000);
    }
});
</script>
@endif
@endsection
