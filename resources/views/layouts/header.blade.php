<header class="sticky top-0 z-50 bg-white border-b border-gray-200" 
        x-data="{
            mobileMenuOpen: false,
            searchModalOpen: false,
            searchActive: false,
            searchQuery: '',
            suggestions: [],
            isLoading: false,
            hasSearched: false,
            recentSearches: [],
            
            init() {
                // Load recent searches from localStorage
                const saved = localStorage.getItem('recentSearches');
                if (saved) {
                    this.recentSearches = JSON.parse(saved).slice(0, 5);
                }
            },
            
            openSearch() {
                this.searchActive = true;
                this.hasSearched = false;
                // Wait for transition to complete before focusing
                setTimeout(() => {
                    const input = this.$refs.searchInput;
                    if (input) {
                        input.focus();
                        console.log('✓ Search input auto-focused');
                    } else {
                        console.error('✗ Search input not found');
                    }
                }, 450); // Wait for full transition (400ms animation + 50ms buffer)
            },
            
            closeSearch() {
                this.searchActive = false;
                this.searchQuery = '';
                this.suggestions = [];
                this.isLoading = false;
                this.hasSearched = false;
            },
            
            addToRecentSearch(query) {
                if (!query.trim()) return;
                this.recentSearches = [query, ...this.recentSearches.filter(s => s !== query)].slice(0, 5);
                localStorage.setItem('recentSearches', JSON.stringify(this.recentSearches));
            },
            
            removeRecentSearch(query) {
                this.recentSearches = this.recentSearches.filter(s => s !== query);
                localStorage.setItem('recentSearches', JSON.stringify(this.recentSearches));
            },
            
            clearRecentSearches() {
                this.recentSearches = [];
                localStorage.removeItem('recentSearches');
            },
            
            async fetchSuggestions() {
                if (this.searchQuery.length < 1) {
                    this.suggestions = [];
                    this.hasSearched = false;
                    this.isLoading = false;
                    return;
                }
                
                this.isLoading = true;
                this.hasSearched = false;
                
                try {
                    const response = await fetch(`/api/search/suggestions?q=${encodeURIComponent(this.searchQuery)}`);
                    this.suggestions = await response.json();
                    this.hasSearched = true;
                } catch (error) {
                    console.error('Search error:', error);
                    this.suggestions = [];
                    this.hasSearched = true;
                } finally {
                    this.isLoading = false;
                }
            },
            
            performSearch(query) {
                if (query.trim()) {
                    this.addToRecentSearch(query.trim());
                    window.location.href = `/products?search=${encodeURIComponent(query)}`;
                }
            }
        }">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16 relative">
            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Logo -->
            <a href="/" class="flex items-center ml-3 transition-opacity hover:opacity-80">
                <img src="{{ asset('images/logo/JastipHype_logo.png') }}" alt="JastipHype" class="h-12 w-auto">
            </a>


            {{-- Desktop Navigation & Search Container --}}
            <div class="hidden lg:flex items-center absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-xl justify-center h-10">
                {{-- Navigation (hidden when search active) --}}
                <nav x-show="!searchActive" 
                     x-transition:enter="transition-opacity duration-300 ease-out delay-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-200 ease-out"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 flex items-center justify-center">
                    <div class="w-full max-w-xl">
                        <div class="flex items-center justify-center space-x-8">
                        <a href="{{ route('products.index') }}" class="text-sm font-medium text-red-600 hover:text-red-700 transition-colors whitespace-nowrap animate-shake">SALE</a>
                        <a href="{{ route('products.index', ['new' => true]) }}" class="text-sm font-medium hover:text-accent-gold transition-colors whitespace-nowrap">NEW</a>
                        <a href="#" class="text-sm font-medium hover:text-accent-gold transition-colors whitespace-nowrap">REQUEST</a>
                        <a href="{{ route('products.index') }}" class="text-sm font-medium hover:text-accent-gold transition-colors whitespace-nowrap">BRANDS</a>
                    </div>
                </nav>

                {{-- Inline Search Bar (shown when search active) --}}
                <div x-show="searchActive"
                     x-transition:enter="transition-opacity duration-300 ease-out delay-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-200 ease-out"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display: none;"
                     class="absolute inset-0 flex items-center justify-center">
                    <div class="w-full max-w-xl">
                        <form action="{{ route('products.index') }}" method="GET" 
                              class="w-full"
                              @submit.prevent="performSearch(searchQuery)">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="search"
                                    x-ref="searchInput"
                                    x-model="searchQuery"
                                    @input.debounce.300ms="fetchSuggestions()"
                                    @keydown.escape="closeSearch()"
                                    placeholder="Search products..."
                                    autocomplete="off"
                                    class="w-full px-4 py-2.5 pr-20 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-accent-gold transition-colors"
                                >
                                <button type="submit" class="absolute right-12 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent-gold transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </button>
                                <button type="button" 
                                        @click="closeSearch()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </form>

                    {{-- Search Dropdown with States --}}
                    <div x-show="searchActive && (searchQuery.length < 2 || isLoading || hasSearched)"
                         x-transition:enter="transition-opacity duration-200 ease-out delay-100"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity duration-150 ease-in"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute top-[calc(100%+0.5rem)] left-0 right-0 max-w-xl mx-auto bg-white rounded-lg shadow-2xl border border-gray-200 z-50 max-h-96 overflow-y-auto scrollbar-hide">
                        
                        {{-- Initial State: Show Recent Searches & Popular Terms --}}
                        <div x-show="searchQuery.length < 2 && !isLoading">
                            {{-- Recent Search --}}
                            <div x-show="recentSearches.length > 0" class="p-4 border-b">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-sm font-semibold text-gray-700">Recent Search</h3>
                                    <button @click="clearRecentSearches()" class="text-xs text-gray-500 hover:text-gray-700 underline">Delete all</button>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="term in recentSearches" :key="term">
                                        <button @click="searchQuery = term; fetchSuggestions()" 
                                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-black text-white text-sm rounded-full hover:bg-gray-800 transition-colors">
                                            <span x-text="term"></span>
                                            <svg @click.stop="removeRecentSearch(term)" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            
                            {{-- Popular Search Terms --}}
                            <div class="p-4 border-b">
                                <h3 class="text-sm font-semibold text-gray-700 mb-3">Popular Search Terms</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(['hoodie', 'dompet', 'wallet', 'jacket', 'topi', 'brodo', 'kaos', 'pants'] as $term)
                                        <button @click="searchQuery = '{{ $term }}'; fetchSuggestions()" 
                                                class="px-4 py-2 bg-black text-white text-sm rounded-full hover:bg-gray-800 transition-colors">
                                            {{ $term }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            
                            {{-- Recent Viewed --}}
                            @php
                                $recentlyViewed = session('recently_viewed', []);
                                if(count($recentlyViewed) > 0) {
                                    $recentProducts = \App\Models\Product::whereIn('id', array_slice($recentlyViewed, 0, 3))
                                        ->with(['brand', 'productImages'])
                                        ->get();
                                } else {
                                    $recentProducts = collect();
                                }
                            @endphp
                            
                            @if($recentProducts->count() > 0)
                                <div class="p-4">
                                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Viewed</h3>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach($recentProducts as $product)
                                            <a href="{{ route('products.show', $product->slug) }}" 
                                               class="block hover:bg-gray-50 rounded-lg p-2 transition-colors">
                                                <div class="relative">
                                                    @if($product->productImages->count() > 0)
                                                        <img src="{{ asset('storage/' . $product->productImages->first()->image_path) }}" 
                                                             alt="{{ $product->name }}"
                                                             class="w-full aspect-square object-cover rounded">
                                                    @else
                                                        <div class="w-full aspect-square bg-gray-200 rounded flex items-center justify-center">
                                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    @if($product->stock <= 10 && $product->stock > 0)
                                                        <span class="absolute top-1 left-1 px-2 py-0.5 bg-black text-white text-xs font-semibold">Low Stock</span>
                                                    @endif
                                                </div>
                                                <div class="mt-2">
                                                    <p class="text-xs text-gray-500 truncate">{{ $product->brand->name ?? '' }}</p>
                                                    <p class="font-medium text-sm truncate" title="{{ $product->name }}">{{ Str::limit($product->name, 30) }}</p>
                                                    <p class="text-sm font-semibold mt-1">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Loading State --}}
                        <div x-show="isLoading" class="p-8 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-accent-gold"></div>
                            <p class="mt-3 text-sm text-gray-600">Searching...</p>
                        </div>
                        
                        {{-- No Results State --}}
                        <div x-show="hasSearched && suggestions.length === 0 && !isLoading" class="p-6">
                            <div class="text-center">
                                <p class="text-lg font-bold text-gray-900 mb-2">Products</p>
                                <p class="text-sm text-gray-600 mb-3">0 Results Found</p>
                                <p class="text-sm text-gray-700">
                                    Ups, sorry no results for "<span x-text="searchQuery" class="font-semibold"></span>"
                                </p>
                            </div>
                        </div>
                        
                        {{-- Has Results State --}}
                        <div x-show="hasSearched && suggestions.length > 0 && !isLoading">
                            <div class="p-4 border-b bg-gray-50">
                                <p class="text-sm font-bold text-gray-900">Products</p>
                                <p class="text-xs text-gray-600" x-text="`${suggestions.length} Results Found`"></p>
                            </div>
                            <div class="max-h-80 overflow-y-auto scrollbar-hide">
                                <template x-for="product in suggestions" :key="product.id">
                                    <a :href="`/products/${product.slug}`"
                                       @click="addToRecentSearch(searchQuery)"
                                       class="flex items-center gap-3 p-3 hover:bg-gray-50 border-b last:border-b-0 transition-colors">
                                        <template x-if="product.product_images && product.product_images.length > 0">
                                            <img :src="`/storage/${product.product_images[0].image_path}`" 
                                                 :alt="product.name"
                                                 class="w-14 h-14 object-cover rounded flex-shrink-0">
                                        </template>
                                        <template x-if="!product.product_images || product.product_images.length === 0">
                                            <div class="w-14 h-14 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                                                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </template>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-sm truncate" x-text="product.name"></p>
                                            <p class="text-sm text-gray-600" x-text="'Rp ' + parseInt(product.price).toLocaleString('id-ID')"></p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Icons -->
            <div class="flex items-center space-x-4">
                {{-- Desktop Search Icon (hidden when search active) --}}
                <button :class="{ 'invisible': searchActive }"
                        @click="openSearch()" 
                        class="hidden lg:block p-2 hover:text-accent-gold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                {{-- Mobile Search Button --}}
                <button @click="searchModalOpen = true" class="md:hidden p-2 hover:text-accent-gold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- Cart Dropdown -->
                @php
                    $cartCount = 0;
                    if(Auth::check()) {
                        $cart = Auth::user()->cart;
                        if($cart) $cartCount = $cart->items->sum('quantity');
                    } else {
                        $sessionId = Session::getId();
                        $cart = \App\Models\Cart::where('session_id', $sessionId)->first();
                        if($cart) $cartCount = $cart->items->sum('quantity');
                    }
                @endphp
                <div x-data="{ 
                        open: false,
                        count: {{ $cartCount }},
                        isLoading: false,
                        html: '',
                        
                        init() {
                            // Listen for cart updates
                            window.addEventListener('cart-updated', (e) => {
                                this.count = e.detail.count;
                                this.fetchMiniCart(); // Refresh content
                            });
                        },

                        fetchMiniCart() {
                            this.isLoading = true;
                            fetch('{{ route('cart.mini') }}')
                                .then(res => res.json())
                                .then(data => {
                                    this.html = data.html;
                                    this.count = data.count; // Sync count just in case
                                    this.isLoading = false;
                                })
                                .catch(err => {
                                    console.error('Error fetching mini cart:', err);
                                    this.isLoading = false;
                                });
                        }
                     }" 
                     @mouseenter="open = true; if(!html) fetchMiniCart()"
                     @mouseleave="open = false"
                     class="relative">
                    
                    <a href="/cart" class="p-2 hover:text-accent-gold transition-colors relative block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span x-show="count > 0" 
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="transform scale-0"
                              x-transition:enter-end="transform scale-100"
                              class="absolute -top-1 -right-1 bg-accent-gold text-white text-xs rounded-full min-w-[1.25rem] h-5 flex items-center justify-center px-1"
                              x-text="count > 99 ? '99+' : count"
                              style="display: none;">
                        </span>
                    </a>

                    <!-- Mini Cart Dropdown -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-100 z-50 overflow-hidden"
                         style="display: none;">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-gray-900">Shopping Cart</h3>
                            <span class="text-xs text-gray-500" x-text="`${count} Items`"></span>
                        </div>

                        <!-- Content -->
                        <div class="min-h-[100px] relative">
                            <!-- Loading Spinner -->
                            <div x-show="isLoading" class="absolute inset-0 bg-white/80 z-10 flex items-center justify-center">
                                <svg class="animate-spin h-6 w-6 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>

                            <!-- Cart Items (Injected HTML) -->
                            <div x-html="html"></div>
                        </div>
                    </div>
                </div>

                {{-- Account / Auth --}}
                @auth
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button 
                            @click="profileOpen = !profileOpen"
                            @click.away="profileOpen = false"
                            class="p-2 hover:text-accent-gold transition-colors flex items-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="hidden lg:block text-sm font-medium">{{ Str::limit(Auth::user()->name, 15) }}</span>
                            <svg class="w-4 h-4 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        {{-- Dropdown Menu --}}
                        <div 
                            x-show="profileOpen" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-1 z-50"
                            style="display: none;"
                        >
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                My Account
                            </a>
                            
                            <a href="{{ route('profile.index', ['tab' => 'orders']) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Order History
                            </a>
                            
                            <a href="{{ route('wishlist.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                My Wishlist
                            </a>
                            
                            <div class="border-t border-gray-100 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="p-2 hover:text-accent-gold transition-colors" title="Sign In">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="lg:hidden border-t border-gray-200 bg-white"
         @click.away="mobileMenuOpen = false">
        <nav class="px-4 py-4 space-y-3">
            <a href="{{ route('products.index') }}" class="block text-sm font-medium text-red-600 hover:text-red-700 transition-colors animate-shake">SALE</a>
            <a href="{{ route('products.index', ['new' => true]) }}" class="block text-sm font-medium hover:text-accent-gold transition-colors">NEW</a>
            <a href="#" class="block text-sm font-medium hover:text-accent-gold transition-colors">REQUEST</a>
            <a href="{{ route('products.index') }}" class="block text-sm font-medium hover:text-accent-gold transition-colors">BRANDS</a>
        </nav>
    </div>

    {{-- Search Modal Overlay --}}
    <div x-show="searchModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         @click="searchModalOpen = false"
         class="fixed inset-0 bg-black/50 z-50">
        
        <div @click.stop class="bg-white max-w-2xl mx-auto mt-20 rounded-lg shadow-2xl">
            {{-- Search Header --}}
            <div class="border-b p-4">
                <form action="{{ route('products.index') }}" method="GET" 
                      @submit.prevent="if($refs.searchInput.value.trim()) $el.submit()">
                    <div class="relative">
                        <input 
                            x-ref="searchInput"
                            type="text" 
                            name="search"
                            placeholder="Search our products"
                            value="{{ request('search') }}"
                            autofocus
                            autocomplete="off"
                            class="w-full px-4 py-3 pr-20 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-gold"
                        >
                        <button type="submit" class="absolute right-12 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent-gold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                        <button type="button" @click="searchModalOpen = false" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Modal Content --}}
            <div class="p-4 max-h-[60vh] overflow-y-auto scrollbar-hide">
                <style>
                    .scrollbar-hide::-webkit-scrollbar {
                        display: none;
                    }
                    .scrollbar-hide {
                        -ms-overflow-style: none;
                        scrollbar-width: none;
                    }
                </style>
                {{-- Popular Search Terms --}}
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Popular Search Terms</h3>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $popularTerms = ['hoodie', 'jacket', 'pants', 'wallet', 'bag', 'shirt', 'shoes', 'accessories'];
                        @endphp
                        @foreach($popularTerms as $term)
                            <a href="{{ route('products.index', ['search' => $term]) }}" 
                               class="px-4 py-2 bg-gray-900 text-white text-sm rounded-full hover:bg-gray-800 transition-colors">
                                {{ $term }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Recent Viewed --}}
                @php
                    // Get recently viewed products from session
                    $recentlyViewed = session('recently_viewed', []);
                    if(count($recentlyViewed) > 0) {
                        $recentProducts = \App\Models\Product::whereIn('id', array_slice($recentlyViewed, 0, 3))
                            ->with(['brand', 'productImages'])
                            ->get();
                    } else {
                        $recentProducts = collect();
                    }
                @endphp
                
                @if($recentProducts->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Viewed</h3>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($recentProducts as $product)
                                <a href="{{ route('products.show', $product->slug) }}" 
                                   class="block hover:bg-gray-50 rounded-lg p-2 transition-colors">
                                    <div class="relative">
                                        @if($product->productImages->count() > 0)
                                            <img src="{{ asset('storage/' . $product->productImages->first()->image_path) }}" 
                                                 alt="{{ $product->name }}"
                                                 class="w-full aspect-square object-cover rounded">
                                        @else
                                            <div class="w-full aspect-square bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        @if($product->stock <= 10 && $product->stock > 0)
                                            <span class="absolute top-1 left-1 px-2 py-0.5 bg-black text-white text-xs font-semibold">Low Stock</span>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-500 truncate">{{ $product->brand->name ?? '' }}</p>
                                        <p class="font-medium text-sm truncate" title="{{ $product->name }}">{{ Str::limit($product->name, 30) }}</p>
                                        <p class="text-sm font-semibold mt-1">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>

<script>
function searchAutocomplete() {
    return {
        query: '',
        suggestions: [],
        showSuggestions: false,
        
        async fetchSuggestions() {
            if (this.query.length < 1) {
                this.suggestions = [];
                return;
            }
            
            try {
                const response = await fetch(`/api/search/suggestions?q=${encodeURIComponent(this.query)}`);
                this.suggestions = await response.json();
            } catch (error) {
                console.error('Search error:', error);
                this.suggestions = [];
            }
        },
        
        formatPrice(price) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
        }
    }
}
</script>
