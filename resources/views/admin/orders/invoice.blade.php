<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    @vite(['resources/css/app.css'])
    
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .page-break {
                page-break-after: always;
            }
        }
        
        @page {
            size: A4;
            margin: 1cm;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Print Button (Hidden when printing) -->
    <div class="no-print fixed top-4 right-4 z-50">
        <button onclick="window.print()" 
                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>
            </svg>
            Print Invoice
        </button>
        <button onclick="window.close()" 
                class="ml-2 inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Close
        </button>
    </div>

    <!-- Invoice Content -->
    <div class="mx-auto max-w-4xl bg-white p-8 shadow-lg" style="min-height: 100vh;">
        <!-- Header -->
        <div class="mb-8 flex items-start justify-between border-b-2 border-gray-900 pb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">INVOICE</h1>
                <p class="mt-2 text-sm text-gray-600">{{ config('app.name', 'JastipHype') }}</p>
                <p class="text-sm text-gray-600">{{ config('app.url') }}</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-600">Invoice Number</div>
                <div class="text-xl font-bold text-gray-900">{{ $order->order_number }}</div>
                <div class="mt-2 text-sm text-gray-600">Date</div>
                <div class="font-semibold text-gray-900">{{ $order->created_at->format('d F Y') }}</div>
            </div>
        </div>

        <!-- Customer & Order Info -->
        <div class="mb-8 grid grid-cols-2 gap-8">
            <!-- Bill To -->
            <div>
                <h2 class="mb-3 text-sm font-semibold uppercase text-gray-600">Bill To</h2>
                <div class="text-gray-900">
                    <div class="font-semibold">{{ $order->customer_name }}</div>
                    <div class="mt-1 text-sm">{{ $order->customer_email }}</div>
                    @if($order->customer_phone)
                    <div class="text-sm">{{ $order->customer_phone }}</div>
                    @endif
                </div>
            </div>

            <!-- Ship To -->
            <div>
                <h2 class="mb-3 text-sm font-semibold uppercase text-gray-600">Ship To</h2>
                <div class="text-sm text-gray-900">
                    <div>{{ $order->shipping_address }}</div>
                    @if($order->shipping_city)
                    <div>{{ $order->shipping_city }}</div>
                    @endif
                    @if($order->shipping_postal_code)
                    <div>{{ $order->shipping_postal_code }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="mb-8 grid grid-cols-3 gap-4 rounded-lg bg-gray-50 p-4">
            <div>
                <div class="text-xs font-semibold uppercase text-gray-600">Order Status</div>
                <div class="mt-1">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase text-gray-600">Payment Status</div>
                <div class="mt-1">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->payment_status === 'paid') bg-green-100 text-green-800
                        @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase text-gray-600">Payment Method</div>
                <div class="mt-1 text-sm font-medium text-gray-900">
                    {{ $order->payment_method ? ucwords(str_replace('_', ' ', $order->payment_method)) : 'Not specified' }}
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-8">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-900">
                        <th class="pb-3 text-left text-xs font-semibold uppercase text-gray-600">Item</th>
                        <th class="pb-3 text-center text-xs font-semibold uppercase text-gray-600">Qty</th>
                        <th class="pb-3 text-right text-xs font-semibold uppercase text-gray-600">Price</th>
                        <th class="pb-3 text-right text-xs font-semibold uppercase text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b border-gray-200">
                        <td class="py-4">
                            <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                            @if($item->product_sku)
                            <div class="text-xs text-gray-500">SKU: {{ $item->product_sku }}</div>
                            @endif
                        </td>
                        <td class="py-4 text-center text-gray-900">{{ $item->quantity }}</td>
                        <td class="py-4 text-right text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-4 text-right font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="mb-8 flex justify-end">
            <div class="w-64">
                <div class="flex justify-between border-b border-gray-200 py-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 py-2">
                    <span class="text-gray-600">Shipping</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between border-b border-gray-200 py-2">
                    <span class="text-gray-600">Discount</span>
                    <span class="font-medium text-red-600">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between border-t-2 border-gray-900 py-3">
                    <span class="text-lg font-semibold text-gray-900">Total</span>
                    <span class="text-lg font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
        <div class="mb-8 rounded-lg bg-gray-50 p-4">
            <h3 class="mb-2 text-sm font-semibold text-gray-900">Notes</h3>
            <p class="text-sm text-gray-600">{{ $order->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="border-t border-gray-200 pt-8 text-center text-sm text-gray-600">
            <p>Thank you for your business!</p>
            <p class="mt-2">This is a computer-generated invoice and does not require a signature.</p>
            <p class="mt-4">{{ config('app.name') }} &copy; {{ date('Y') }}</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
