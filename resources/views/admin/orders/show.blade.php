@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<!-- Page Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Order #{{ $order->order_number }}</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin.orders.index') }}" class="ml-1 text-gray-600 hover:text-blue-600">Orders</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500">#{{ $order->order_number }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.orders.invoice', $order->id) }}" 
           target="_blank"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Print Invoice
        </a>
        <a href="{{ route('admin.orders.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content (2 columns) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($item->product && $item->product->images)
                                        @php
                                            $images = json_decode($item->product->images, true);
                                            $firstImage = !empty($images) ? $images[0] : null;
                                        @endphp
                                        @if($firstImage)
                                            <img src="{{ Storage::url($firstImage) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="w-12 h-12 rounded object-cover mr-3">
                                        @endif
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                        <div class="text-sm text-gray-500">SKU: {{ $item->product->sku ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Subtotal:</td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Shipping:</td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Discount:</td>
                            <td class="px-6 py-3 text-sm font-medium text-green-600">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="bg-gray-100">
                            <td colspan="3" class="px-6 py-4 text-right text-base font-bold text-gray-900">Total:</td>
                            <td class="px-6 py-4 text-base font-bold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Order Timeline</h3>
            </div>
            <div class="p-6">
                <div class="relative pl-12">
                    @foreach($timeline as $event)
                    <div class="relative pb-8 last:pb-0">
                        <!-- Vertical Line -->
                        @if(!$loop->last)
                        <span class="absolute left-[-35px] top-10 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @endif
                        
                        <!-- Timeline Marker -->
                        <div class="absolute left-[-45px] top-0 flex h-10 w-10 items-center justify-center rounded-full 
                            @if($event['color'] === 'success') bg-green-500
                            @elseif($event['color'] === 'primary') bg-blue-500
                            @elseif($event['color'] === 'info') bg-cyan-500
                            @elseif($event['color'] === 'warning') bg-amber-500
                            @elseif($event['color'] === 'danger') bg-red-500
                            @else bg-gray-500
                            @endif">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                @if(str_contains($event['icon'], 'check'))
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                @elseif(str_contains($event['icon'], 'clock'))
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                @elseif(str_contains($event['icon'], 'truck'))
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                                @else
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                @endif
                            </svg>
                        </div>
                        
                        <!-- Timeline Content -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $event['label'] }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ $event['description'] }}</p>
                            <p class="text-xs text-gray-500">{{ $event['timestamp']->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Order Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Order Status</h3>
            </div>
            <div class="p-6">
                @php
                    $statusConfig = [
                        'pending' => ['color' => 'amber', 'label' => 'Pending'],
                        'processing' => ['color' => 'cyan', 'label' => 'Processing'],
                        'shipped' => ['color' => 'blue', 'label' => 'Shipped'],
                        'delivered' => ['color' => 'green', 'label' => 'Delivered'],
                        'cancelled' => ['color' => 'red', 'label' => 'Cancelled']
                    ];
                    $config = $statusConfig[$order->status] ?? ['color' => 'gray', 'label' => ucfirst($order->status)];
                @endphp
                
                <div class="text-center mb-4">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($config['color'] === 'amber') bg-amber-100 text-amber-800
                        @elseif($config['color'] === 'cyan') bg-cyan-100 text-cyan-800
                        @elseif($config['color'] === 'blue') bg-blue-100 text-blue-800
                        @elseif($config['color'] === 'green') bg-green-100 text-green-800
                        @elseif($config['color'] === 'red') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $config['label'] }}
                    </span>
                </div>

                @if($order->status !== 'cancelled' && $order->status !== 'delivered')
                <button type="button" 
                        onclick="document.getElementById('updateStatusModal').classList.remove('hidden')"
                        class="w-full mb-2 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Update Status
                </button>
                @endif

                @if($order->status !== 'cancelled')
                <button type="button" 
                        onclick="document.getElementById('cancelOrderModal').classList.remove('hidden')"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Cancel Order
                </button>
                @endif
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Name</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->customer_name ?? ($order->user->name ?? 'Guest') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Email</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->customer_email ?? ($order->user->email ?? '-') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Phone</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->customer_phone ?? ($order->user->phone ?? '-') }}</p>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Shipping Information</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Address</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->shipping_address ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">City</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->shipping_city ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Postal Code</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->shipping_postal_code ?? '-' }}</p>
                </div>
                @if($order->shipping_courier)
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Courier</p>
                    <p class="text-sm font-medium text-gray-900">{{ strtoupper($order->shipping_courier) }} - {{ $order->shipping_service }}</p>
                </div>
                @endif
                @if($order->shipping_cost)
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Shipping Cost</p>
                    <p class="text-sm font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Information -->
        @if($order->payment)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Payment Information</h3>
                <div class="flex gap-2">
                    <button type="button" 
                            onclick="document.getElementById('editPaymentModal').classList.remove('hidden')"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </button>
                    <button type="button" 
                            class="sync-payment-btn inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" 
                            data-id="{{ $order->id }}">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Sync
                    </button>
                </div>
            </div>
            <div class="p-6">
                @php
                    // Get payment method logo
                    $paymentType = $order->payment->payment_type ?? 'unknown';
                    $paymentData = $order->payment->payment_data ?? [];
                    
                    // Determine logo path
                    $logoPath = null;
                    $paymentLabel = '';
                    
                    switch($paymentType) {
                        case 'qris':
                            $logoPath = '/images/payment/ewallet/qris.svg';
                            $paymentLabel = 'QRIS';
                            break;
                        case 'gopay':
                            $logoPath = '/images/payment/ewallet/gopay.svg';
                            $paymentLabel = 'GoPay';
                            break;
                        case 'shopeepay':
                            $logoPath = '/images/payment/ewallet/shopeepay.svg';
                            $paymentLabel = 'ShopeePay';
                            break;
                        case 'bank_transfer':
                            $bank = strtolower($paymentData['va_numbers'][0]['bank'] ?? 'bca');
                            $logoPath = '/images/payment/banks/' . $bank . '.svg';
                            $paymentLabel = strtoupper($bank) . ' Virtual Account';
                            break;
                        case 'echannel':
                            $logoPath = '/images/payment/banks/mandiri.svg';
                            $paymentLabel = 'Mandiri Bill Payment';
                            break;
                        default:
                            $paymentLabel = ucfirst($paymentType);
                    }
                    
                    // Status badge color
                    $statusColor = 'secondary';
                    $statusText = ucfirst($order->payment->transaction_status ?? 'pending');
                    
                    if (in_array($order->payment->transaction_status, ['settlement', 'capture', 'paid'])) {
                        $statusColor = 'success';
                        $statusText = 'Paid';
                    } elseif ($order->payment->transaction_status === 'pending') {
                        $statusColor = 'warning';
                        $statusText = 'Pending';
                    } elseif (in_array($order->payment->transaction_status, ['deny', 'cancel', 'expire', 'failed'])) {
                        $statusColor = 'danger';
                        $statusText = 'Failed';
                    }
                @endphp
                
                <div class="text-center py-4">
                    @if($logoPath && file_exists(public_path($logoPath)))
                        <img src="{{ asset($logoPath) }}" 
                             alt="{{ $paymentLabel }}" 
                             class="h-20 w-auto max-w-[160px] object-contain mx-auto"
                             title="{{ $paymentLabel }}">
                    @else
                        <div class="inline-flex items-center px-6 py-3 rounded-lg text-base font-medium bg-gray-100 text-gray-800">
                            {{ $paymentLabel }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Update Order Status</h3>
            <button type="button" 
                    onclick="document.getElementById('updateStatusModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
            @csrf
            <div class="mt-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="send_email" value="1" id="sendEmail" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="sendEmail" class="ml-2 block text-sm text-gray-700">
                        Send email notification to customer
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" 
                        onclick="document.getElementById('updateStatusModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelOrderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Cancel Order</h3>
            <button type="button" 
                    onclick="document.getElementById('cancelOrderModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
            @csrf
            <div class="mt-4 space-y-4">
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm text-amber-700">
                            This will cancel the order and restore product stock.
                        </p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Reason</label>
                    <textarea name="reason" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                @if($order->payment && $order->payment->status === 'paid')
                <div class="flex items-center">
                    <input type="checkbox" name="refund" value="1" id="processRefund" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="processRefund" class="ml-2 block text-sm text-gray-700">
                        Process refund (if payment was made)
                    </label>
                </div>
                @endif
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" 
                        onclick="document.getElementById('cancelOrderModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Close
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Cancel Order
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Payment Modal -->
@if($order->payment)
<div id="editPaymentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Edit Payment Information</h3>
            <button type="button" 
                    onclick="document.getElementById('editPaymentModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mt-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <select name="transaction_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="pending" {{ $order->payment->transaction_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="settlement" {{ $order->payment->transaction_status === 'settlement' ? 'selected' : '' }}>Settlement (Paid)</option>
                        <option value="capture" {{ $order->payment->transaction_status === 'capture' ? 'selected' : '' }}>Capture (Paid)</option>
                        <option value="paid" {{ $order->payment->transaction_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="deny" {{ $order->payment->transaction_status === 'deny' ? 'selected' : '' }}>Denied</option>
                        <option value="cancel" {{ $order->payment->transaction_status === 'cancel' ? 'selected' : '' }}>Cancelled</option>
                        <option value="expire" {{ $order->payment->transaction_status === 'expire' ? 'selected' : '' }}>Expired</option>
                        <option value="failed" {{ $order->payment->transaction_status === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Current: {{ ucfirst($order->payment->transaction_status) }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                    <input type="text" name="transaction_id" value="{{ $order->payment->transaction_id }}" 
                           placeholder="Enter transaction ID"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Code / VA Number</label>
                    <input type="text" name="payment_code" value="{{ $order->payment->payment_code }}" 
                           placeholder="Enter payment code or VA number"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <input type="number" name="gross_amount" value="{{ $order->payment->gross_amount }}" 
                           placeholder="Enter amount" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Current: Rp {{ number_format($order->payment->gross_amount, 0, ',', '.') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3" 
                              placeholder="Add notes about this payment update..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-xs text-blue-700">
                            Changing payment status to "Paid" will automatically update order status to "Processing".
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" 
                        onclick="document.getElementById('editPaymentModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Sync Payment Status
    document.querySelectorAll('.sync-payment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.id;
            const button = this;
            const icon = button.querySelector('svg');
            
            // Add loading state
            icon.classList.add('animate-spin');
            button.disabled = true;
            
            fetch(`/admin/orders/${orderId}/sync-payment-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payment status synced successfully!');
                    location.reload();
                } else {
                    alert('Failed to sync payment status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to sync payment status');
            })
            .finally(() => {
                icon.classList.remove('animate-spin');
                button.disabled = false;
            });
        });
    });
</script>
@endpush
