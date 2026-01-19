<header class="sticky top-0 z-50 bg-white border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Logo -->
            <a href="/" class="text-2xl font-bold tracking-tight">
                JASTIP<span class="text-accent-gold">HYPE</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('products.index') }}" class="text-sm font-medium hover:text-accent-gold transition-colors">SHOP</a>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-sm font-medium hover:text-accent-gold transition-colors">NEW ARRIVALS</a>
                <a href="{{ route('products.index', ['availability' => ['limited']]) }}" class="text-sm font-medium hover:text-accent-gold transition-colors">LIMITED</a>
                <a href="{{ route('products.index') }}" class="text-sm font-medium hover:text-accent-gold transition-colors">BRANDS</a>
            </nav>

            <!-- Right Icons -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <button class="p-2 hover:text-accent-gold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- Cart -->
                <a href="/cart" class="p-2 hover:text-accent-gold transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-accent-gold text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                </a>

                <!-- Account -->
                <a href="/account" class="p-2 hover:text-accent-gold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
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
            <a href="{{ route('products.index') }}" class="block text-sm font-medium hover:text-accent-gold transition-colors">SHOP</a>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="block text-sm font-medium hover:text-accent-gold transition-colors">NEW ARRIVALS</a>
            <a href="{{ route('products.index', ['availability' => ['limited']]) }}" class="block text-sm font-medium hover:text-accent-gold transition-colors">LIMITED</a>
            <a href="{{ route('products.index') }}" class="block text-sm font-medium hover:text-accent-gold transition-colors">BRANDS</a>
        </nav>
    </div>
</header>
