@extends('admin.layouts.app')

@section('title', 'Product Management')

@section('page-title', 'Products')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Product Management</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your product catalog and inventory</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Product
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-admin.metric-card 
            title="Total Products"
            :value="$products->count()"
            icon="shopping"
        />
        <x-admin.metric-card 
            title="Active Products"
            :value="$products->where('is_active', true)->count()"
            icon="chart"
            trend="up"
            trendValue="Active"
        />
        <x-admin.metric-card 
            title="Low Stock"
            :value="$products->where('stock', '<', 10)->where('stock', '>', 0)->count()"
            icon="chart"
            trend="down"
            trendValue="Warning"
        />
        <x-admin.metric-card 
            title="Out of Stock"
            :value="$products->where('stock', 0)->count()"
            icon="chart"
            trendValue="Critical"
        />
    </div>

    {{-- Products Table --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 py-4 md:px-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Product List</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $products->count() }} items</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="dt-button-excel inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Export Excel
                    </button>
                    <button type="button" class="dt-button-csv inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        Export CSV
                    </button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full" id="productsTable">
                <thead>
                    <tr class="border-y border-gray-100 bg-gray-50/50 dark:border-gray-800 dark:bg-gray-900/50">
                        <th class="px-5 py-3 md:px-6">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800" id="selectAll">
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Product</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">SKU</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Category</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Brand</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Price</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Stock</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($products as $product)
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-5 py-4 md:px-6">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 product-checkbox dark:border-gray-600 dark:bg-gray-800" value="{{ $product->id }}">
                        </td>
                        <td class="px-5 py-4 md:px-6">
                            <div class="flex items-center gap-3">
                                @php
                                    $firstImage = $product->productImages->first();
                                    if ($firstImage) {
                                        $imageSrc = image_url($firstImage->image_path);
                                    } else {
                                        $imageSrc = asset('images/placeholder-product.svg');
                                    }
                                @endphp
                                <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-lg">
                                    <img class="h-full w-full object-cover" src="{{ $imageSrc }}" alt="{{ $product->name }}">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ Str::limit($product->name, 35) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-gray-400 md:px-6">
                            {{ $product->sku }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-gray-400 md:px-6">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-gray-400 md:px-6">
                            {{ $product->brand->name ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 md:px-6">
                            <div class="text-sm font-medium text-gray-800 dark:text-white/90">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <div class="text-xs text-gray-500 line-through dark:text-gray-400">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            @if($product->stock == 0)
                                <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-600 dark:bg-red-500/15 dark:text-red-500">
                                    Out of Stock
                                </span>
                            @elseif($product->stock < 10)
                                <span class="inline-flex rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-medium text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-500">
                                    {{ $product->stock }} left
                                </span>
                            @else
                                <span class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            @if($product->is_active)
                                <span class="inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-blue-50 hover:text-blue-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-blue-500/10 dark:hover:text-blue-400" title="Edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-red-50 hover:text-red-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Delete">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-12 text-center md:px-6">
                            <div class="flex flex-col items-center">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-gray-800 dark:text-white/90">No products</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new product.</p>
                                <a href="{{ route('admin.products.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    New Product
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.tailwindcss.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<style>
    /* Custom DataTables styling for TailAdmin */
    .dataTables_wrapper .dataTables_filter input {
        @apply rounded-lg border border-gray-200 bg-gray-50 py-2 pl-4 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200;
    }
    .dataTables_wrapper .dataTables_length select {
        @apply rounded-lg border border-gray-200 bg-gray-50 py-2 pl-4 pr-8 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        @apply rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700;
    }
    .dataTables_wrapper .dataTables_info {
        @apply text-sm text-gray-500 dark:text-gray-400;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#productsTable').DataTable({
        dom: '<"flex flex-wrap items-center justify-between gap-4 mb-4"lf>rt<"flex flex-wrap items-center justify-between gap-4 mt-4"ip>',
        pageLength: 25,
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: [0, 8] }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search products...",
            lengthMenu: "Show _MENU_",
        }
    });

    // Custom export buttons
    $('.dt-button-excel').on('click', function() {
        // Trigger Excel export
        table.button('.buttons-excel').trigger();
    });

    $('.dt-button-csv').on('click', function() {
        // Trigger CSV export
        table.button('.buttons-csv').trigger();
    });

    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.product-checkbox').prop('checked', this.checked);
    });
});
</script>
@endpush
