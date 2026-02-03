@extends('admin.layouts.app')

@section('title', 'Customer Management')

@section('page-title', 'Customers')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Customer Management</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage customer accounts and view analytics</p>
        </div>
        <a href="{{ route('admin.customers.export', request()->query()) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export CSV
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-admin.metric-card 
            title="Total Customers"
            :value="number_format($stats['total'])"
            icon="users"
        />
        <x-admin.metric-card 
            title="Active"
            :value="number_format($stats['active'])"
            icon="chart"
            trend="up"
            trendValue="Active"
        />
        <x-admin.metric-card 
            title="Suspended"
            :value="number_format($stats['suspended'])"
            icon="chart"
            trendValue="Inactive"
        />
        <x-admin.metric-card 
            title="New This Month"
            :value="number_format($stats['new_this_month'])"
            icon="users"
            trend="up"
            trendValue="New"
        />
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-6">
            <div class="sm:col-span-2">
                <label for="search" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" id="search" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200" placeholder="Search by name or email..." value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="sm:col-span-1">
                <label for="status" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    <option value="">All Status</option>
                    <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ ($filters['status'] ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label for="sort" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort By</label>
                <select name="sort" id="sort" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    <option value="latest" {{ ($filters['sort'] ?? 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ ($filters['sort'] ?? '') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="most_orders" {{ ($filters['sort'] ?? '') == 'most_orders' ? 'selected' : '' }}>Most Orders</option>
                    <option value="highest_spending" {{ ($filters['sort'] ?? '') == 'highest_spending' ? 'selected' : '' }}>Highest Spending</option>
                </select>
            </div>
            <div class="flex items-end sm:col-span-1">
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Customer Table --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 py-4 md:px-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Customer List</h3>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    {{ $customers->total() }} customers
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-y border-gray-100 bg-gray-50/50 dark:border-gray-800 dark:bg-gray-900/50">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">ID</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Phone</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Orders</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Total Spent</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Registered</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($customers as $customer)
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-gray-400 md:px-6">
                            #{{ $customer->id }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-500/20">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $customer->name }}</p>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-gray-400 md:px-6">
                            {{ $customer->email }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-gray-400 md:px-6">
                            {{ $customer->phone ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                                {{ $customer->orders_count ?? 0 }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-800 dark:text-white/90 md:px-6">
                            Rp {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            @if(($customer->status ?? 'active') == 'active')
                                <span class="inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-600 dark:bg-red-500/15 dark:text-red-500">
                                    Suspended
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-gray-400 md:px-6">
                            {{ $customer->created_at->format('d M Y') }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-blue-50 hover:text-blue-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-blue-500/10 dark:hover:text-blue-400" title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-yellow-50 hover:text-yellow-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-yellow-500/10 dark:hover:text-yellow-400" title="Edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-12 text-center md:px-6">
                            <div class="flex flex-col items-center">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-gray-800 dark:text-white/90">No customers found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No customers match your search criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($customers->hasPages())
        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Showing <span class="font-medium text-gray-800 dark:text-white/90">{{ $customers->firstItem() }}</span> to <span class="font-medium text-gray-800 dark:text-white/90">{{ $customers->lastItem() }}</span> of <span class="font-medium text-gray-800 dark:text-white/90">{{ $customers->total() }}</span> results
                </p>
                <div>
                    {{ $customers->withQueryString()->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
