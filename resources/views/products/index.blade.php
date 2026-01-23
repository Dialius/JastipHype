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
            
            <h1 id="page-title" class="text-3xl font-bold">
                {!! $pageTitle !!}
            </h1>
            <p id="page-description" class="text-gray-600 mt-2">
                {!! $pageDescription !!}
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
            <div class="lg:col-span-3" x-data="shopPage()">
                <!-- Sort & Results Bar -->
                <div class="bg-white rounded-lg p-4 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                     {{-- Keep existing Active Filters Logic (can serve as initial state) --}}
                    @php
                       // Active filters logic remains same for SEO/Initial load, 
                       // but JS will handle dynamic updates
                       $activeFilters = []; 
                       // ... (Existing PHP logic for active filters if you want to keep it strictly server-side rendered initially)
                    @endphp
                    
                    <div class="text-gray-600">
                         <p class="text-sm" x-show="!loading">
                            Showing <span class="font-semibold" x-text="totalProducts">{{ $products->total() }}</span> products
                        </p>
                         <p class="text-sm" x-show="loading">
                            Loading products...
                        </p>
                    </div>

                    <!-- Custom Sort Dropdown -->
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" 
                                class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-300 rounded-md hover:bg-gray-100 transition-colors min-w-[200px] justify-between">
                            <span class="text-sm font-medium" x-text="currentSortLabel">
                                {{ request('sort') == 'newest' ? 'Newest' : (request('sort') == 'price_asc' ? 'Price: Low to High' : (request('sort') == 'price_desc' ? 'Price: High to Low' : 'Featured')) }}
                            </span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 z-50 overflow-hidden"
                             style="display: none;">
                             
                            @foreach([
                                'featured' => 'Featured',
                                'newest' => 'Newest',
                                'price_asc' => 'Price: Low to High',
                                'price_desc' => 'Price: High to Low',
                                'name' => 'Name: A to Z'
                            ] as $key => $label)
                            <button @click="updateSort('{{ $key }}', '{{ $label }}'); open = false"
                               class="w-full text-left px-4 py-3 text-sm hover:bg-gray-50 transition-colors"
                               :class="currentSort === '{{ $key }}' ? 'bg-gray-100 font-semibold' : ''">
                                <div class="flex items-center justify-between">
                                    <span>{{ $label }}</span>
                                    <svg x-show="currentSort === '{{ $key }}'" class="w-4 h-4 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Products Container -->
                <div class="relative min-h-[400px]">
                    <!-- Loading Overlay -->
                    <div x-show="loading && page === 1" 
                         class="absolute inset-0 bg-white bg-opacity-70 z-10 flex items-start justify-center pt-20"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        <div class="flex items-center space-x-2">
                             <div class="w-3 h-3 bg-black rounded-full animate-bounce"></div>
                             <div class="w-3 h-3 bg-black rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                             <div class="w-3 h-3 bg-black rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>

                    <!-- Filtered Results Injection Container -->
                    <div id="product-grid-container">
                        @include('products.partials.product-list', ['products' => $products])
                    </div>
                    
                    <!-- Infinite Scroll Loading Indicator -->
                    <div x-show="loadingMore" class="py-8 flex justify-center">
                         <svg class="animate-spin h-8 w-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    
                    <!-- Trigger for Infinite Scroll -->
                    <div x-ref="scrollTrigger" class="h-4 w-full"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function shopPage() {
    return {
        loading: false,
        loadingMore: false,
        page: 1,
        hasMore: {{ $products->hasMorePages() ? 'true' : 'false' }},
        currentSort: '{{ request('sort', 'featured') }}',
        currentSortLabel: '{{ request('sort') == 'newest' ? 'Newest' : (request('sort') == 'price_asc' ? 'Price: Low to High' : (request('sort') == 'price_desc' ? 'Price: High to Low' : 'Featured')) }}',
        totalProducts: {{ $products->total() }},
        observer: null,

        init() {
            // Initialize Filters Listener
            this.$watch('currentSort', () => this.fetchProducts());
            
            // Listen to sidebar filter changes dispatched via custom events or direct form changes
            // Since sidebar is separate component, we'll hook into form change events
            const form = document.getElementById('filter-form'); 
            if(form) {
                form.addEventListener('change', () => {
                   this.page = 1;
                   this.fetchProducts();
                });
                
                // Also listen for custom price slider events if needed, 
                // or ensure slider inputs trigger 'change' or 'input' on the form
            }

            // Setup Infinite Scroll Observer
            this.setupObserver();
        },

        setupObserver() {
            if (this.observer) this.observer.disconnect();
            
            this.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !this.loadingMore && this.hasMore && !this.loading) {
                        this.loadMore();
                    }
                });
            }, { rootMargin: '200px' });
            
            if (this.$refs.scrollTrigger) {
                this.observer.observe(this.$refs.scrollTrigger);
            }
        },

        getFilterParams() {
            const form = document.getElementById('filter-form');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            params.set('sort', this.currentSort);
            
            // Handle array inputs correctly (brands[], sizes[]) if FormData doesn't auto-handle nicely with URLSearchParams
            // But usually URLSearchParams(formData) works well for standard forms.
            
            return params;
        },

        fetchProducts() {
            this.loading = true;
            const params = this.getFilterParams();
            params.set('page', 1); // Reset to page 1

            // Update URL without sizing reload
            const newUrl = `${window.location.pathname}?${params.toString()}`;
            window.history.pushState({path: newUrl}, '', newUrl);

            fetch(`${window.location.pathname}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('product-grid-container').innerHTML = data.html;
                this.hasMore = data.hasMore;
                this.page = 1;
                this.loading = false;

                // Update Header Title & Description
                if (data.pageTitle) document.getElementById('page-title').innerHTML = data.pageTitle;
                if (data.pageDescription) document.getElementById('page-description').innerHTML = data.pageDescription;
                
                // Re-initialize any Alpine components inside the new HTML if necessary
                // or simple DOM replacement is enough if components are self-contained
            })
            .catch(err => {
                console.error(err);
                this.loading = false;
            });
        },

        loadMore() {
            this.loadingMore = true;
            const params = this.getFilterParams();
            params.set('page', this.page + 1);

            fetch(`${window.location.pathname}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                // Append new products
                const container = document.getElementById('product-grid-container');
                
                // Find grid inside the response HTML and append its children
                // Since data.html returns the whole grid wrapper from partial
                // We need to extract children or just append another grid wrapper?
                // The partial contains <div class="grid..."> which wraps items.
                // We probably want to append items TO existing grid if possible, 
                // OR just append the new grid HTML below. 
                // Appending new grid block below is safer/easier for layout sometimes.
                
                // Strategy: Append the raw HTML. If it is a new grid block, tailwind handles visual flow.
                // Or better: parse HTML and append children to first grid.
                
                const parser = new DOMParser();
                const doc = parser.parseFromString(data.html, 'text/html');
                const newItems = doc.querySelector('.grid'); 
                
                if (newItems) {
                    const currentGrid = container.querySelector('.grid');
                    if (currentGrid) {
                        currentGrid.innerHTML += newItems.innerHTML;
                    } else {
                        container.innerHTML += data.html; // Fallback
                    }
                } else {
                     container.innerHTML += data.html; // If partial changes structure
                }

                this.page++;
                this.hasMore = data.hasMore;
                this.loadingMore = false;
            })
            .catch(err => {
                console.error(err);
                this.loadingMore = false;
            });
        },

        updateSort(key, label) {
            this.currentSort = key;
            this.currentSortLabel = label;
            // Watcher triggers fetchProducts
        }
    }
}
</script>
@endsection
