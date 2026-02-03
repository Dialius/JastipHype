@extends('admin.layouts.app')

@section('title', 'Payment Details')

@section('content')
<!-- Page Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Payment Details</h1>
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
                        <a href="{{ route('admin.payments.index') }}" class="ml-1 text-gray-600 hover:text-blue-600">Payments</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500">Details</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.payments.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Payment Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Transaction Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Transaction Details</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Transaction ID</p>
                        <p class="text-sm font-mono text-gray-900">{{ $payment->transaction_id }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Order Number</p>
                        <div class="text-sm">
                            @if($payment->order)
                            <a href="{{ route('admin.orders.show', $payment->order) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $payment->order->order_number }}
                            </a>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Payment Type</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                            {{ $payment->getPaymentTypeName() }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Status</p>
                        @php
                            if ($payment->isSuccess()) {
                                $badgeClass = 'bg-green-100 text-green-800';
                            } elseif ($payment->isPending()) {
                                $badgeClass = 'bg-amber-100 text-amber-800';
                            } else {
                                $badgeClass = 'bg-red-100 text-red-800';
                            }
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                            {{ $payment->getStatusLabel() }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Amount</p>
                        <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        @if($payment->fraud_status)
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Fraud Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ ucfirst($payment->fraud_status) }}
                        </span>
                        @endif
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Transaction Time</p>
                        <p class="text-sm text-gray-900">{{ $payment->transaction_time ? $payment->transaction_time->format('d M Y, H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Settlement Time</p>
                        <p class="text-sm text-gray-900">{{ $payment->settlement_time ? $payment->settlement_time->format('d M Y, H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Expiry Time</p>
                        <p class="text-sm text-gray-900">{{ $payment->expiry_time ? $payment->expiry_time->format('d M Y, H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        @if($payment->payment_data)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Payment Instructions</h3>
            </div>
            <div class="p-6">
                @php $instructions = $payment->getInstructions(); @endphp
                
                @if($instructions['type'] === 'bank_transfer')
                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                        <p class="font-semibold text-cyan-900 mb-2">Bank: {{ $instructions['bank'] }}</p>
                        <p class="text-sm text-cyan-800">Virtual Account: <code class="bg-cyan-100 px-2 py-1 rounded">{{ $instructions['va_number'] }}</code></p>
                    </div>
                @elseif($instructions['type'] === 'qris')
                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                        <p class="font-semibold text-cyan-900 mb-2">QRIS Payment</p>
                        <p class="text-sm text-cyan-800">Acquirer: {{ ucfirst($instructions['acquirer']) }}</p>
                    </div>
                @elseif($instructions['type'] === 'gopay')
                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                        <p class="font-semibold text-cyan-900 mb-2">GoPay Payment</p>
                        @if($instructions['deeplink'])
                        <a href="{{ $instructions['deeplink'] }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800">Open in GoPay App</a>
                        @endif
                    </div>
                @elseif($instructions['type'] === 'cstore')
                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                        <p class="font-semibold text-cyan-900 mb-2">{{ ucfirst($instructions['store']) }}</p>
                        <p class="text-sm text-cyan-800">Payment Code: <code class="bg-cyan-100 px-2 py-1 rounded">{{ $instructions['payment_code'] }}</code></p>
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-700">Payment Type: {{ $payment->getPaymentTypeName() }}</p>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Order Items -->
        @if($payment->order && $payment->order->items->count() > 0)
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payment->order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->product_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Customer Info -->
        @if($payment->order && $payment->order->user)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
            </div>
            <div class="p-6">
                <div class="text-center mb-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full">
                        <span class="text-2xl font-bold text-blue-600">{{ strtoupper(substr($payment->order->user->name, 0, 1)) }}</span>
                    </div>
                </div>
                <div class="text-center mb-4">
                    <h4 class="text-base font-semibold text-gray-900 mb-1">{{ $payment->order->user->name }}</h4>
                    <p class="text-sm text-gray-600">{{ $payment->order->user->email }}</p>
                </div>
                <hr class="my-4 border-gray-200">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Phone:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $payment->order->user->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Member Since:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $payment->order->user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
            </div>
            <div class="p-6 space-y-3">
                <!-- Sync Status -->
                <form action="{{ route('admin.payments.sync-status', $payment) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Sync from Midtrans
                    </button>
                </form>

                <!-- Manual Verify -->
                <button type="button" 
                        onclick="document.getElementById('verifyModal').classList.remove('hidden')"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Manual Verification
                </button>

                <hr class="my-4 border-gray-200">

                <!-- View Order -->
                @if($payment->order)
                <a href="{{ route('admin.orders.show', $payment->order) }}" 
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-blue-300 rounded-lg text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    View Order
                </a>
                @endif
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Payment Info</h3>
            </div>
            <div class="p-6 space-y-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Created</p>
                    <p class="text-sm text-gray-900">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Last Updated</p>
                    <p class="text-sm text-gray-900">{{ $payment->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manual Verification Modal -->
<div id="verifyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <form action="{{ route('admin.payments.manual-verify', $payment) }}" method="POST">
            @csrf
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Manual Payment Verification</h3>
                <button type="button" 
                        onclick="document.getElementById('verifyModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-4 space-y-4">
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-amber-700">
                                <strong>Warning:</strong> Manual verification should only be used when automatic sync fails or for special cases.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        New Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Status</option>
                        <option value="settlement">Settlement (Paid)</option>
                        <option value="paid">Paid</option>
                        <option value="cancel">Cancel</option>
                        <option value="expire">Expire</option>
                    </select>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              placeholder="Enter verification notes..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" 
                        onclick="document.getElementById('verifyModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    Verify Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
