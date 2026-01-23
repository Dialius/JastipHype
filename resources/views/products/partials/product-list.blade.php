@if($products->count() > 0)
    <div class="grid grid-cols-3 gap-4 md:gap-6">
        @foreach($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>

    <!-- Hidden Pagination Data -->
    @if($products->hasMorePages())
        <div id="pagination-data" data-next-page="{{ $products->currentPage() + 1 }}" class="hidden"></div>
    @endif
@else
    <div class="bg-white rounded-lg p-12 text-center col-span-3">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="mt-4 text-xl font-semibold text-gray-900">No products found</h3>
        <p class="mt-2 text-gray-600">Try adjusting your filters to find what you're looking for.</p>
    </div>
@endif
