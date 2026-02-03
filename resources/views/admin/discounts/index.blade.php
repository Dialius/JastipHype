@extends('admin.layouts.app')

@section('title', 'Discount Management')

@section('page-title', 'Discounts')

@section('content')
<div class="space-y-6" x-data="{ 
    showDeleteModal: false, 
    showToggleModal: false,
    selectedId: null,
    selectedCode: '',
    currentStatus: ''
}">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Discount Management</h2>
            <nav class="mt-2 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-300">Home</a>
                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-gray-800 dark:text-white/90">Discounts</span>
            </nav>
        </div>
        <a href="{{ route('admin.discounts.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Discount
        </a>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-admin.metric-card 
            title="Total Discounts"
            :value="$statistics['total']"
            icon="chart"
        />
        <x-admin.metric-card 
            title="Active"
            :value="$statistics['active']"
            icon="star"
            trend="up"
            trendValue="Available"
        />
        <x-admin.metric-card 
            title="Total Uses"
            :value="number_format($statistics['total_uses'])"
            icon="users"
        />
        <x-admin.metric-card 
            title="Total Discount"
            :value="'Rp ' . number_format($statistics['total_discount_amount'], 0, ',', '.')"
            icon="dollar"
        />
    </div>

    {{-- Discounts Table --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 py-4 md:px-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All Discounts</h3>
                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-sm font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    {{ $discounts->total() }} items
                </span>
            </div>
        </div>

        @if($discounts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-y border-gray-100 bg-gray-50/50 dark:border-gray-800 dark:bg-gray-900/50">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Code</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Value</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Usage</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Valid Period</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($discounts as $discount)
                    @php
                        $now = now();
                        $isActive = $discount->status === 'active';
                        $isScheduled = $discount->start_date && $now->lt($discount->start_date);
                        $isExpired = $discount->end_date && $now->gt($discount->end_date);
                        $isMaxedOut = $discount->max_uses && $discount->uses_count >= $discount->max_uses;
                        
                        if ($isMaxedOut) {
                            $statusClass = 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400';
                            $statusText = 'Maxed Out';
                        } elseif ($isExpired) {
                            $statusClass = 'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-400';
                            $statusText = 'Expired';
                        } elseif ($isScheduled) {
                            $statusClass = 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400';
                            $statusText = 'Scheduled';
                        } elseif ($isActive) {
                            $statusClass = 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400';
                            $statusText = 'Active';
                        } else {
                            $statusClass = 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400';
                            $statusText = 'Inactive';
                        }
                    @endphp
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $discount->code }}</p>
                            @if($discount->min_order_amount)
                            <p class="text-xs text-gray-500 dark:text-gray-400">Min: Rp {{ number_format($discount->min_order_amount, 0, ',', '.') }}</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            @if($discount->type === 'percentage')
                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">Percentage</span>
                            @else
                            <span class="inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-400">Fixed Amount</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <span class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                @if($discount->type === 'percentage')
                                {{ $discount->value }}%
                                @else
                                Rp {{ number_format($discount->value, 0, ',', '.') }}
                                @endif
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <p class="text-sm text-gray-800 dark:text-white/90">
                                <strong>{{ number_format($discount->uses_count) }}</strong>
                                / {{ $discount->max_uses ? number_format($discount->max_uses) : '∞' }}
                            </p>
                            @if($discount->remaining_uses !== null)
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $discount->remaining_uses }} left</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            @if($discount->start_date || $discount->end_date)
                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                @if($discount->start_date)
                                <p>From: {{ $discount->start_date->format('d M Y') }}</p>
                                @endif
                                @if($discount->end_date)
                                <p>To: {{ $discount->end_date->format('d M Y') }}</p>
                                @endif
                            </div>
                            @else
                            <span class="text-xs text-gray-400">No limit</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            <span class="inline-flex rounded-full {{ $statusClass }} px-2.5 py-0.5 text-xs font-medium">{{ $statusText }}</span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.discounts.edit', $discount) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-blue-50 hover:text-blue-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-blue-500/10 dark:hover:text-blue-400" title="Edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                                <button type="button" 
                                        @click="showToggleModal = true; selectedId = {{ $discount->id }}; selectedCode = '{{ $discount->code }}'; currentStatus = '{{ $discount->status }}'"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-yellow-50 hover:text-yellow-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-yellow-500/10 dark:hover:text-yellow-400" 
                                        title="Toggle Status">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        @if($discount->status === 'active')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                        @endif
                                    </svg>
                                </button>
                                <button type="button" 
                                        @click="showDeleteModal = true; selectedId = {{ $discount->id }}; selectedCode = '{{ $discount->code }}'"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-red-50 hover:text-red-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-red-500/10 dark:hover:text-red-400" 
                                        title="Delete">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($discounts->hasPages())
        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
            {{ $discounts->links() }}
        </div>
        @endif
        @else
        <div class="px-5 py-12 text-center md:px-6">
            <div class="flex flex-col items-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-sm font-semibold text-gray-800 dark:text-white/90">No discounts yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create your first discount to get started!</p>
                <a href="{{ route('admin.discounts.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Create Discount
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[99999] overflow-y-auto" 
         style="display: none;">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-700 dark:bg-gray-800">
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-800 dark:text-white/90">Delete Discount</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to delete discount <strong class="text-gray-800 dark:text-white/90" x-text="selectedCode"></strong>? This action cannot be undone.
                    </p>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" @click="showDeleteModal = false" class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <form id="delete-form" method="POST" class="flex-1" :action="'/admin/discounts/' + selectedId">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Toggle Status Modal --}}
    <div x-show="showToggleModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[99999] overflow-y-auto" 
         style="display: none;">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showToggleModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-700 dark:bg-gray-800">
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-500/20">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-800 dark:text-white/90">Toggle Status</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to <span x-text="currentStatus === 'active' ? 'deactivate' : 'activate'"></span> discount <strong class="text-gray-800 dark:text-white/90" x-text="selectedCode"></strong>?
                    </p>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" @click="showToggleModal = false" class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <form id="toggle-form" method="POST" class="flex-1" :action="'/admin/discounts/' + selectedId + '/toggle-status'">
                        @csrf
                        <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                            Confirm
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
