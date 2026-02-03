@extends('admin.layouts.app')

@section('title', 'Edit Customer - ' . $customer->name)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header 
        title="Edit Customer"
        :breadcrumbs="[
            ['label' => 'Customers', 'route' => 'admin.customers.index'],
            ['label' => $customer->name, 'route' => 'admin.customers.show', 'params' => $customer->id],
            ['label' => 'Edit']
        ]"
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-800/50">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h5 class="font-semibold text-gray-800 dark:text-white">Customer Information</h5>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-5">
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $customer->name) }}"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 @error('name') border-red-500 dark:border-red-500 @enderror"
                            >
                            @error('name')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-5">
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $customer->email) }}"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 @error('email') border-red-500 dark:border-red-500 @enderror"
                            >
                            @error('email')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Email must be unique and valid</p>
                        </div>

                        <!-- Phone -->
                        <div class="mb-6">
                            <label for="phone" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Phone Number
                            </label>
                            <input 
                                type="text" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone', $customer->phone) }}"
                                placeholder="e.g., 081234567890"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 @error('phone') border-red-500 dark:border-red-500 @enderror"
                            >
                            @error('phone')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">
                            <a href="{{ route('admin.customers.show', $customer->id) }}" 
                               class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Status -->
            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-800/50">
                <div class="border-b border-gray-200 px-5 py-3.5 dark:border-gray-700">
                    <h6 class="font-medium text-gray-800 dark:text-white">Customer Status</h6>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-500/15">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Current Status</p>
                            @if(($customer->status ?? 'active') == 'active')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-500/15 dark:text-green-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700 dark:bg-red-500/15 dark:text-red-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                    Suspended
                                </span>
                            @endif
                        </div>
                    </div>
                    <hr class="border-gray-200 dark:border-gray-700 mb-4">
                    <div class="space-y-2.5 text-sm">
                        <p class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Customer ID:</span>
                            <span class="font-medium text-gray-800 dark:text-white">#{{ $customer->id }}</span>
                        </p>
                        <p class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Registered:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $customer->created_at->format('d M Y') }}</span>
                        </p>
                        <p class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Last Updated:</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $customer->updated_at->format('d M Y') }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-800/50">
                <div class="border-b border-gray-200 px-5 py-3.5 dark:border-gray-700">
                    <h6 class="font-medium text-gray-800 dark:text-white">Quick Stats</h6>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Total Orders:</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ $customer->orders->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Total Spent:</span>
                        <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($customer->orders->where('status', 'delivered')->sum('total'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Total Reviews:</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ $customer->reviews->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
