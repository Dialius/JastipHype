@extends('admin.layouts.app')

@section('title', 'Orders')

@section('page-title', 'Orders')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Orders</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage customer orders and track shipments</p>
        </div>
        <button type="button" 
                onclick="document.getElementById('exportModal').classList.remove('hidden')"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export Orders
        </button>
    </div>

    {{-- Status Tabs --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Mobile Select --}}
        <div class="sm:hidden">
            <select id="mobile-tabs" onchange="window.location.href=this.value" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                <option value="{{ route('admin.orders.index') }}" {{ !request('status') ? 'selected' : '' }}>All Orders ({{ $statusCounts['all'] ?? 0 }})</option>
                <option value="{{ route('admin.orders.index', ['status' => 'pending']) }}" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending ({{ $statusCounts['pending'] ?? 0 }})</option>
                <option value="{{ route('admin.orders.index', ['status' => 'processing']) }}" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing ({{ $statusCounts['processing'] ?? 0 }})</option>
                <option value="{{ route('admin.orders.index', ['status' => 'shipped']) }}" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped ({{ $statusCounts['shipped'] ?? 0 }})</option>
                <option value="{{ route('admin.orders.index', ['status' => 'delivered']) }}" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered ({{ $statusCounts['delivered'] ?? 0 }})</option>
                <option value="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled ({{ $statusCounts['cancelled'] ?? 0 }})</option>
            </select>
        </div>
        
        {{-- Desktop Tabs --}}
        <nav class="hidden sm:flex sm:flex-wrap sm:gap-2" aria-label="Tabs">
            <a href="{{ route('admin.orders.index') }}" class="{{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                All Orders
                <span class="{{ !request('status') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} rounded-full px-2 py-0.5 text-xs font-medium">{{ $statusCounts['all'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="{{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                Pending
                <span class="{{ request('status') === 'pending' ? 'bg-yellow-400 text-yellow-900' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} rounded-full px-2 py-0.5 text-xs font-medium">{{ $statusCounts['pending'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="{{ request('status') === 'processing' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                Processing
                <span class="{{ request('status') === 'processing' ? 'bg-blue-400 text-blue-900' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} rounded-full px-2 py-0.5 text-xs font-medium">{{ $statusCounts['processing'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="{{ request('status') === 'shipped' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                Shipped
                <span class="{{ request('status') === 'shipped' ? 'bg-indigo-400 text-indigo-900' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} rounded-full px-2 py-0.5 text-xs font-medium">{{ $statusCounts['shipped'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="{{ request('status') === 'delivered' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                Delivered
                <span class="{{ request('status') === 'delivered' ? 'bg-green-400 text-green-900' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} rounded-full px-2 py-0.5 text-xs font-medium">{{ $statusCounts['delivered'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="{{ request('status') === 'cancelled' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                Cancelled
                <span class="{{ request('status') === 'cancelled' ? 'bg-red-400 text-red-900' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} rounded-full px-2 py-0.5 text-xs font-medium">{{ $statusCounts['cancelled'] ?? 0 }}</span>
            </a>
        </nav>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-6">
            <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">
            
            <div class="sm:col-span-2">
                <label for="search" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" id="search" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" placeholder="Order number, customer..." value="{{ $filters['search'] ?? '' }}">
            </div>
            
            <div class="sm:col-span-1">
                <label for="payment_method" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Payment</label>
                <select name="payment_method" id="payment_method" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    <option value="">All Methods</option>
                    <option value="bank_transfer" {{ ($filters['payment_method'] ?? '') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="credit_card" {{ ($filters['payment_method'] ?? '') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="e-wallet" {{ ($filters['payment_method'] ?? '') === 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="qris" {{ ($filters['payment_method'] ?? '') === 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            
            <div class="sm:col-span-1">
                <label for="date_from" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">From</label>
                <input type="date" name="date_from" id="date_from" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" value="{{ $filters['date_from'] ?? '' }}">
            </div>
            
            <div class="sm:col-span-1">
                <label for="date_to" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">To</label>
                <input type="date" name="date_to" id="date_to" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" value="{{ $filters['date_to'] ?? '' }}">
            </div>
            
            <div class="flex items-end gap-2 sm:col-span-1">
                <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full" id="ordersTable">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50 dark:border-gray-800 dark:bg-gray-900/50">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Order #</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Payment</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Total</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($orders as $order)
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                #{{ $order->order_number }}
                            </a>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $order->user->name ?? 'Guest' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->email ?? '-' }}</p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <p class="text-sm text-gray-800 dark:text-white/90">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <p class="text-sm text-gray-800 dark:text-white/90">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? '-')) }}</p>
                            @switch($order->payment_status)
                                @case('paid')
                                    <span class="inline-flex rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">Paid</span>
                                    @break
                                @case('pending')
                                    <span class="inline-flex rounded-full bg-yellow-50 px-2 py-0.5 text-xs font-medium text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-500">Pending</span>
                                    @break
                                @default
                                    <span class="inline-flex rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-600 dark:bg-red-500/15 dark:text-red-500">{{ ucfirst($order->payment_status) }}</span>
                            @endswitch
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-800 dark:text-white/90 md:px-6">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            @switch($order->status)
                                @case('delivered')
                                    <span class="inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">Delivered</span>
                                    @break
                                @case('shipped')
                                    <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-500">Shipped</span>
                                    @break
                                @case('processing')
                                    <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-500/15 dark:text-blue-500">Processing</span>
                                    @break
                                @case('pending')
                                    <span class="inline-flex rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-medium text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-500">Pending</span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-600 dark:bg-red-500/15 dark:text-red-500">Cancelled</span>
                                    @break
                                @default
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ ucfirst($order->status) }}</span>
                            @endswitch
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-600 transition hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20">
                                View
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center md:px-6">
                            <div class="flex flex-col items-center">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-gray-800 dark:text-white/90">No orders found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your filters or search criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Showing <span class="font-medium text-gray-800 dark:text-white/90">{{ $orders->firstItem() }}</span> to <span class="font-medium text-gray-800 dark:text-white/90">{{ $orders->lastItem() }}</span> of <span class="font-medium text-gray-800 dark:text-white/90">{{ $orders->total() }}</span> results
                </p>
                <div>
                    {{ $orders->withQueryString()->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Export Modal --}}
<div id="exportModal" class="fixed inset-0 z-[99999] hidden overflow-y-auto" x-data>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="document.getElementById('exportModal').classList.add('hidden')"></div>
        <div class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Export Orders</h3>
                <button onclick="document.getElementById('exportModal').classList.add('hidden')" class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form method="GET" action="{{ route('admin.orders.export') }}">
                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Export Format</label>
                        <select name="format" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" required>
                            <option value="csv">CSV</option>
                            <option value="excel" disabled>Excel (Coming Soon)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Date Range (Optional)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="date" name="start_date" class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                            <input type="date" name="end_date" class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                        </div>
                    </div>
                    
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status (Optional)</label>
                        <select name="status" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')" class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
<script>
$(document).ready(function() {
    $('#ordersTable').DataTable({
        pageLength: 25,
        order: [[2, 'desc']],
        columnDefs: [
            { orderable: false, targets: [6] }
        ],
        dom: '<"hidden"f>rt',
        language: {
            search: "",
            searchPlaceholder: "Search orders..."
        }
    });
});
</script>
@endpush
