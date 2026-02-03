@extends('admin.layouts.app')

@section('title', 'Edit Discount')

@section('page-title', 'Discounts')

@section('content')
<div class="space-y-6" x-data="{ showDeleteModal: false }">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Edit Discount</h2>
            <nav class="mt-2">
                <ol class="flex items-center gap-2 text-sm">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Dashboard</a>
                    </li>
                    <li class="text-gray-400">/</li>
                    <li>
                        <a href="{{ route('admin.discounts.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Discounts</a>
                    </li>
                    <li class="text-gray-400">/</li>
                    <li class="text-blue-600 dark:text-blue-400">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('admin.discounts.update', $discount) }}" method="POST" id="discount-form">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Main Form (2 columns) --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Basic Information --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Basic Information</h3>
                    </div>
                    <div class="space-y-5 p-5 md:p-6">
                        {{-- Code --}}
                        <div>
                            <label for="code" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Discount Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code', $discount->code) }}" 
                                   required
                                   placeholder="e.g., SUMMER2026"
                                   class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 uppercase focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('code') border-red-500 dark:border-red-500 @enderror">
                            @error('code')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Code will be automatically converted to uppercase</p>
                        </div>

                        {{-- Type & Value --}}
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="type" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Discount Type <span class="text-red-500">*</span>
                                </label>
                                <select id="type" 
                                        name="type" 
                                        required
                                        onchange="updateValueLabel()"
                                        class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('type') border-red-500 dark:border-red-500 @enderror">
                                    <option value="">Select Type</option>
                                    <option value="percentage" {{ old('type', $discount->type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="fixed" {{ old('type', $discount->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                                </select>
                                @error('type')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="value" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span id="value-label">Discount Value</span> <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="value" 
                                       name="value" 
                                       value="{{ old('value', $discount->value) }}" 
                                       required
                                       min="0"
                                       step="0.01"
                                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('value') border-red-500 dark:border-red-500 @enderror">
                                @error('value')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400" id="value-help">Enter discount value</p>
                            </div>
                        </div>

                        {{-- Min Order Amount --}}
                        <div>
                            <label for="min_order_amount" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Minimum Order Amount
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center rounded-l-lg border border-r-0 border-gray-200 bg-gray-100 px-4 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    Rp
                                </span>
                                <input type="number" 
                                       id="min_order_amount" 
                                       name="min_order_amount" 
                                       value="{{ old('min_order_amount', $discount->min_order_amount) }}" 
                                       min="0"
                                       step="1000"
                                       class="flex-1 rounded-r-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('min_order_amount') border-red-500 dark:border-red-500 @enderror">
                            </div>
                            @error('min_order_amount')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Optional: Minimum order amount required to use this discount</p>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status" 
                                    required
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('status') border-red-500 dark:border-red-500 @enderror">
                                <option value="active" {{ old('status', $discount->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $discount->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Usage Limits --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Usage Limits</h3>
                    </div>
                    <div class="p-5 md:p-6">
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="max_uses" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Maximum Total Uses
                                </label>
                                <input type="number" 
                                       id="max_uses" 
                                       name="max_uses" 
                                       value="{{ old('max_uses', $discount->max_uses) }}" 
                                       min="1"
                                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('max_uses') border-red-500 dark:border-red-500 @enderror">
                                @error('max_uses')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Leave empty for unlimited uses</p>
                            </div>

                            <div>
                                <label for="uses_per_customer" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Uses Per Customer
                                </label>
                                <input type="number" 
                                       id="uses_per_customer" 
                                       name="uses_per_customer" 
                                       value="{{ old('uses_per_customer', $discount->uses_per_customer) }}" 
                                       min="1"
                                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('uses_per_customer') border-red-500 dark:border-red-500 @enderror">
                                @error('uses_per_customer')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Leave empty for unlimited per customer</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Valid Period --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Valid Period</h3>
                    </div>
                    <div class="p-5 md:p-6">
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="start_date" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Start Date
                                </label>
                                <input type="datetime-local" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date', $discount->start_date ? $discount->start_date->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('start_date') border-red-500 dark:border-red-500 @enderror">
                                @error('start_date')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Leave empty to start immediately</p>
                            </div>

                            <div>
                                <label for="end_date" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    End Date
                                </label>
                                <input type="datetime-local" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date', $discount->end_date ? $discount->end_date->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('end_date') border-red-500 dark:border-red-500 @enderror">
                                @error('end_date')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Leave empty for no expiration</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Applicability --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Applicability</h3>
                    </div>
                    <div class="space-y-5 p-5 md:p-6">
                        <div>
                            <label for="applicable_to" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Apply To <span class="text-red-500">*</span>
                            </label>
                            <select id="applicable_to" 
                                    name="applicable_to" 
                                    required
                                    onchange="toggleApplicableIds()"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('applicable_to') border-red-500 dark:border-red-500 @enderror">
                                <option value="all" {{ old('applicable_to', $discount->applicable_to) == 'all' ? 'selected' : '' }}>All Products</option>
                                <option value="products" {{ old('applicable_to', $discount->applicable_to) == 'products' ? 'selected' : '' }}>Specific Products</option>
                                <option value="categories" {{ old('applicable_to', $discount->applicable_to) == 'categories' ? 'selected' : '' }}>Specific Categories</option>
                            </select>
                            @error('applicable_to')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Products Selection --}}
                        <div id="products-selection" class="hidden">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Select Products</label>
                            <div class="max-h-72 space-y-2 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                                @foreach($products as $product)
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg p-2 transition hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <input type="checkbox" 
                                           name="applicable_ids[]" 
                                           value="{{ $product->id }}" 
                                           id="product_{{ $product->id }}"
                                           {{ in_array($product->id, old('applicable_ids', $discount->applicable_ids ?? [])) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $product->name }} <span class="text-gray-400">({{ $product->sku }})</span>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Categories Selection --}}
                        <div id="categories-selection" class="hidden">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Select Categories</label>
                            <div class="max-h-72 space-y-2 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                                @foreach($categories as $category)
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg p-2 transition hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <input type="checkbox" 
                                           name="applicable_ids[]" 
                                           value="{{ $category->id }}" 
                                           id="category_{{ $category->id }}"
                                           {{ in_array($category->id, old('applicable_ids', $discount->applicable_ids ?? [])) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Discount
                    </button>
                    <a href="{{ route('admin.discounts.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="button" 
                            @click="showDeleteModal = true"
                            class="ml-auto inline-flex items-center gap-2 rounded-lg border border-red-200 bg-white px-5 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:border-red-800 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-500/10">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Discount
                    </button>
                </div>
            </div>

            {{-- Sidebar (1 column) --}}
            <div class="space-y-6 lg:col-span-1">
                {{-- Discount Info --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Discount Info</h3>
                    </div>
                    <div class="space-y-4 p-5 md:p-6">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Created</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $discount->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Last Updated</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $discount->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total Uses</p>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ number_format($discount->uses_count) }}</p>
                        </div>
                        @if($discount->remaining_uses !== null)
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Remaining Uses</p>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ number_format($discount->remaining_uses) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Guidelines --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Discount Guidelines</h3>
                    </div>
                    <div class="space-y-5 p-5 md:p-6">
                        <div>
                            <h4 class="mb-3 font-medium text-gray-800 dark:text-white/90">Discount Types</h4>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li><strong class="text-gray-700 dark:text-gray-300">Percentage:</strong> Discount as % of order total</li>
                                <li><strong class="text-gray-700 dark:text-gray-300">Fixed Amount:</strong> Fixed discount in Rupiah</li>
                            </ul>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-800">

                        <div>
                            <h4 class="mb-3 font-medium text-gray-800 dark:text-white/90">Best Practices</h4>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li>• Use clear, memorable codes</li>
                                <li>• Set minimum order amounts</li>
                                <li>• Limit uses to prevent abuse</li>
                                <li>• Set expiration dates for urgency</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Delete Modal --}}
    <div x-show="showDeleteModal" 
         x-cloak
         class="fixed inset-0 z-[99999] overflow-y-auto"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-700 dark:bg-gray-800"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">Delete Discount</h3>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete this discount? This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button type="button" 
                            @click="showDeleteModal = false"
                            class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="flex-1">
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
</div>
@endsection

@push('scripts')
<script>
    function updateValueLabel() {
        const type = document.getElementById('type').value;
        const label = document.getElementById('value-label');
        const help = document.getElementById('value-help');
        const input = document.getElementById('value');
        
        if (type === 'percentage') {
            label.textContent = 'Percentage (%)';
            help.textContent = 'Enter percentage value (0-100)';
            input.max = 100;
        } else if (type === 'fixed') {
            label.textContent = 'Fixed Amount (Rp)';
            help.textContent = 'Enter fixed discount amount in Rupiah';
            input.removeAttribute('max');
        } else {
            label.textContent = 'Discount Value';
            help.textContent = 'Enter discount value';
            input.removeAttribute('max');
        }
    }

    function toggleApplicableIds() {
        const applicableTo = document.getElementById('applicable_to').value;
        const productsDiv = document.getElementById('products-selection');
        const categoriesDiv = document.getElementById('categories-selection');
        
        productsDiv.classList.add('hidden');
        categoriesDiv.classList.add('hidden');
        
        if (applicableTo === 'products') {
            productsDiv.classList.remove('hidden');
        } else if (applicableTo === 'categories') {
            categoriesDiv.classList.remove('hidden');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateValueLabel();
        toggleApplicableIds();
        
        // Auto-uppercase code input
        document.getElementById('code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    });
</script>
@endpush
