@extends('admin.layouts.app')

@section('title', 'Payment Tracking')

@section('page-title', 'Payments')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Payment Tracking</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Monitor payment transactions and Midtrans integration</p>
        </div>
        <a href="{{ route('admin.payments.analytics') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            Analytics
        </a>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-admin.metric-card 
            title="Total Transactions"
            :value="number_format($statistics['total'])"
            icon="cart"
        />
        <x-admin.metric-card 
            title="Successful"
            :value="number_format($statistics['paid'])"
            icon="chart"
            trend="up"
            trendValue="Completed"
        />
        <x-admin.metric-card 
            title="Pending"
            :value="number_format($statistics['pending'])"
            icon="chart"
        />
        <x-admin.metric-card 
            title="Success Rate"
            :value="$statistics['success_rate']"
            suffix="%"
            icon="chart"
        />
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-6">
            <div class="sm:col-span-2">
                <label for="search" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       placeholder="Transaction ID or Order Number" 
                       value="{{ $filters['search'] ?? '' }}"
                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
            </div>
            
            <div class="sm:col-span-1">
                <label for="status" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    <option value="">All Status</option>
                    <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ ($filters['status'] ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ ($filters['status'] ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="expired" {{ ($filters['status'] ?? '') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            
            <div class="sm:col-span-1">
                <label for="payment_method" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Method</label>
                <select name="payment_method" id="payment_method" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    <option value="">All Methods</option>
                    <option value="bank_transfer" {{ ($filters['payment_method'] ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="credit_card" {{ ($filters['payment_method'] ?? '') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="e-wallet" {{ ($filters['payment_method'] ?? '') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="qris" {{ ($filters['payment_method'] ?? '') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            
            <div class="sm:col-span-1">
                <label for="date_from" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Date From</label>
                <input type="date" 
                       name="date_from" 
                       id="date_from" 
                       value="{{ $filters['date_from'] ?? '' }}"
                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
            </div>
            
            <div class="flex items-end gap-2 sm:col-span-1">
                <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('admin.payments.index') }}" class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Payments Table --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 py-4 md:px-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Payment Transactions</h3>
                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-sm font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    {{ $payments->total() }} transactions
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-y border-gray-100 bg-gray-50/50 dark:border-gray-800 dark:bg-gray-900/50">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Transaction ID</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Order</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Method</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Amount</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Date</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($payments as $payment)
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <span class="font-mono text-sm text-gray-800 dark:text-white/90">{{ $payment->transaction_id }}</span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                #{{ $payment->order->order_number ?? 'N/A' }}
                            </a>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $payment->order->user->name ?? 'Guest' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->order->user->email ?? '-' }}</p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500 dark:text-gray-400 md:px-6">
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? '-')) }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <span class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            @if($payment->status === 'paid')
                                <span class="inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-400">Paid</span>
                            @elseif($payment->status === 'pending')
                                <span class="inline-flex rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-medium text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400">Pending</span>
                            @elseif($payment->status === 'failed')
                                <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-600 dark:bg-red-500/15 dark:text-red-400">Failed</span>
                            @elseif($payment->status === 'expired')
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">Expired</span>
                            @else
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <p class="text-sm text-gray-800 dark:text-white/90">{{ $payment->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->created_at->format('H:i') }}</p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            <a href="{{ route('admin.payments.show', $payment->id) }}" class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                View
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center md:px-6">
                            <div class="flex flex-col items-center">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-gray-800 dark:text-white/90">No payments found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your filters or search criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->hasPages())
        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
