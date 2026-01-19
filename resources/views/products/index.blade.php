@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Page Header -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @php
                $selectedCategoryDetails = null;
                if(request('category')) {
                    $selectedCategoryDetails = $categories->firstWhere('id', request('category'));
                }
            @endphp
            
            <h1 class="text-3xl font-bold">
                @if($selectedCategoryDetails)
                    {{ $selectedCategoryDetails->name }}
                @else
                    All Products
                @endif
            </h1>
            <p class="text-gray-600 mt-2">
                @if($selectedCategoryDetails)
                    Explore our curated selection of {{ strtolower($selectedCategoryDetails->name) }}
                @else
                    Discover our latest collection of luxury fashion and accessories
                @endif
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="lg:grid lg:grid-cols-4 lg:gap-8">
            <!-- Filter Sidebar (Desktop) -->
            <aside class="hidden lg:block lg:col-span-1">
                <x-filter-sidebar 
                    :categories="$categories" 
                    :brands="$brands"
                    :minPrice="$minPrice"
                    :maxPrice="$maxPrice"
                />
            </aside>

            <!-- Mobile Filter Button -->
            <div class="lg:hidden mb-4" x-data="{ open: false }">
                <button @click="open = true" class="w-full bg-black text-white py-3 rounded-md font-semibold flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    Filters
                </button>

                <!-- Mobile Filter Drawer -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-black bg-opacity-50 z-40" 
                     @click="open = false"
                     style="display: none;">
                </div>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="fixed left-0 top-0 bottom-0 w-80 bg-white z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold">Filters</h2>
                            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <x-filter-sidebar 
                            :categories="$categories" 
                            :brands="$brands"
                            :minPrice="$minPrice"
                            :maxPrice="$maxPrice"
                        />
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                <!-- Sort & Results Bar -->
                <div class="bg-white rounded-lg p-4 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    @php
                        // Build active filters array with removal links
                        $activeFilters = [];
                        
                        // Category filter (single selection)
                        if(request('category')) {
                            $category = $categories->firstWhere('id', request('category'));
                            if($category) {
                                $removeUrl = route('products.index', request()->except('category'));
                                $activeFilters[] = [
                                    'label' => $category->name,
                                    'url' => $removeUrl
                                ];
                            }
                        }
                        
                        // Brand filters
                        if(request('brands')) {
                            foreach(request('brands') as $brandId) {
                                $brand = $brands->firstWhere('id', $brandId);
                                if($brand) {
                                    $removeUrl = route('products.index', array_merge(
                                        request()->except('brands'),
                                        ['brands' => array_diff(request('brands'), [$brandId])]
                                    ));
                                    $activeFilters[] = [
                                        'label' => $brand->name,
                                        'url' => $removeUrl
                                    ];
                                }
                            }
                        }
                        
                        // Price range filter
                        if(request('min_price') || request('max_price')) {
                            $priceLabel = 'Price: ';
                            if(request('min_price')) {
                                $priceLabel .= 'Rp ' . number_format(request('min_price'), 0, ',', '.');
                            } else {
                                $priceLabel .= 'Rp ' . number_format($minPrice, 0, ',', '.');
                            }
                            $priceLabel .= ' - ';
                            if(request('max_price')) {
                                $priceLabel .= 'Rp ' . number_format(request('max_price'), 0, ',', '.');
                            } else {
                                $priceLabel .= 'Rp ' . number_format($maxPrice, 0, ',', '.');
                            }
                            $removeUrl = route('products.index', request()->except(['min_price', 'max_price']));
                            $activeFilters[] = [
                                'label' => $priceLabel,
                                'url' => $removeUrl
                            ];
                        }
                        
                        // Availability filter
                        if(request('availability') == 'in_stock') {
                            $removeUrl = route('products.index', request()->except('availability'));
                            $activeFilters[] = [
                                'label' => 'In Stock Only',
                                'url' => $removeUrl
                            ];
                        }
                        
                        // New arrivals filter
                        if(request('new')) {
                            $removeUrl = route('products.index', request()->except('new'));
                            $activeFilters[] = [
                                'label' => 'New Arrivals',
                                'url' => $removeUrl
                            ];
                        }
                        
                        // Discount filter
                        if(request('discount')) {
                            $removeUrl = route('products.index', request()->except('discount'));
                            $activeFilters[] = [
                                'label' => 'Discounted Items',
                                'url' => $removeUrl
                            ];
                        }
                    @endphp
                    
                    <div class="text-gray-600">
                        @if(count($activeFilters) > 0)
                            <p class="font-semibold mb-2">Applied Filters:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($activeFilters as $filter)
                                    <a href="{{ $filter['url'] }}" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium bg-black text-white hover:bg-gray-800 transition-colors group">
                                        <span>{{ $filter['label'] }}</span>
                                        <svg class="w-3.5 h-3.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm">
                                Showing <span class="font-semibold">{{ $products->total() }}</span> products
                            </p>
                        @endif
                    </div>

                    <!-- Custom Sort Dropdown -->
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" 
                                class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-300 rounded-md hover:bg-gray-100 transition-colors min-w-[200px] justify-between">
                            <span class="text-sm font-medium">
                                @if(request('sort') == 'newest')
                                    Newest
                                @elseif(request('sort') == 'price_asc')
                                    Price: Low to High
                                @elseif(request('sort') == 'price_desc')
                                    Price: High to Low
                                @elseif(request('sort') == 'name')
                                    Name: A to Z
                                @else
                                    Featured
                                @endif
                            </span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 z-10 overflow-hidden"
                             style="display: none;">
                            <a href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'featured'])) }}"
                               class="block px-4 py-3 text-sm hover:bg-gray-50 transition-colors {{ request('sort', 'featured') == 'featured' ? 'bg-gray-100 font-semibold' : '' }}">
                                <div class="flex items-center justify-between">
                                    <span>Featured</span>
                                    @if(request('sort', 'featured') == 'featured')
                                        <svg class="w-4 h-4 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </div>
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}"
                               class="block px-4 py-3 text-sm hover:bg-gray-50 transition-colors {{ request('sort') == 'newest' ? 'bg-gray-100 font-semibold' : '' }}">
                                <div class="flex items-center justify-between">
                                    <span>Newest</span>
                                    @if(request('sort') == 'newest')
                                        <svg class="w-4 h-4 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </div>
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}"
                               class="block px-4 py-3 text-sm hover:bg-gray-50 transition-colors {{ request('sort') == 'price_asc' ? 'bg-gray-100 font-semibold' : '' }}">
                                <div class="flex items-center justify-between">
                                    <span>Price: Low to High</span>
                                    @if(request('sort') == 'price_asc')
                                        <svg class="w-4 h-4 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </div>
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}"
                               class="block px-4 py-3 text-sm hover:bg-gray-50 transition-colors {{ request('sort') == 'price_desc' ? 'bg-gray-100 font-semibold' : '' }}">
                                <div class="flex items-center justify-between">
                                    <span>Price: High to Low</span>
                                    @if(request('sort') == 'price_desc')
                                        <svg class="w-4 h-4 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </div>
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'name'])) }}"
                               class="block px-4 py-3 text-sm hover:bg-gray-50 transition-colors {{ request('sort') == 'name' ? 'bg-gray-100 font-semibold' : '' }}">
                                <div class="flex items-center justify-between">
                                    <span>Name: A to Z</span>
                                    @if(request('sort') == 'name')
                                        <svg class="w-4 h-4 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-3 gap-4 md:gap-6">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-4 text-xl font-semibold text-gray-900">No products found</h3>
                        <p class="mt-2 text-gray-600">Try adjusting your filters to find what you're looking for.</p>
                        <a href="{{ route('products.index') }}" class="mt-6 inline-block bg-black text-white px-6 py-3 rounded-md font-semibold hover:bg-gray-900">
                            Clear all filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function updateQueryString(key, value) {
    const url = new URL(window.location.href);
    if (value) {
        url.searchParams.set(key, value);
    } else {
        url.searchParams.delete(key);
    }
    return url.toString();
}
</script>
@endsection
