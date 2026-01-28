@extends('layouts.app')

@section('title', 'Home')

@section('content')

{{-- Hero Section --}}
<section class="hero relative h-screen bg-black text-white" x-data="countdownTimer('2026-01-20 00:00:00')">
    <div class="hero-image absolute inset-0">
        @if($featuredDrop && $featuredDrop->primaryImage)
            <img src="{{ $featuredDrop->primaryImage->image_path }}" 
                 alt="{{ $featuredDrop->name }}" 
                 class="w-full h-full object-cover opacity-60">
        @else
            <div class="w-full h-full bg-gradient-to-br from-gray-900 to-black"></div>
        @endif
        <div class="overlay absolute inset-0 bg-black/40"></div>
    </div>
    
    <div class="hero-content relative z-10 container mx-auto px-4 h-full flex items-center">
        <div class="max-w-2xl">
            <p class="text-sm uppercase tracking-wider mb-4 text-accent-gold">New Limited Drop</p>
            @if($featuredDrop)
                <div class="mb-6">
                    <div class="text-3xl md:text-4xl font-bold text-gray-400 mb-2">
                        {{ $featuredDrop->brand->name }}
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold text-white leading-tight">
                        {{ $featuredDrop->name }}
                    </h1>
                </div>
                <p class="text-lg mb-8 text-gray-300">
                    Only {{ $featuredDrop->stock }} pieces worldwide. Don't miss out.
                </p>
            @else
                <h1 class="text-5xl md:text-6xl font-bold mb-6">
                    Limited Edition <br> Fashion Drops
                </h1>
                <p class="text-lg mb-8 text-gray-300">
                    Exclusive releases from the world's top brands.
                </p>
            @endif
            
            {{-- Countdown Timer --}}
            <div class="countdown mb-8">
                <div class="flex gap-4 text-center">
                    <div>
                        <div class="text-4xl font-bold" x-text="days">00</div>
                        <div class="text-xs uppercase text-gray-400">Days</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold" x-text="hours">00</div>
                        <div class="text-xs uppercase text-gray-400">Hours</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold" x-text="minutes">00</div>
                        <div class="text-xs uppercase text-gray-400">Minutes</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold" x-text="seconds">00</div>
                        <div class="text-xs uppercase text-gray-400">Seconds</div>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('products.index') }}" class="btn-primary text-center">Shop Now</a>
                <a href="{{ route('products.index', ['availability' => ['limited']]) }}" class="btn-secondary text-center">View Collection</a>
            </div>
        </div>
    </div>
</section>

{{-- Featured Categories --}}
<section class="categories py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Shop by Category</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                   class="category-card group relative overflow-hidden rounded-lg aspect-square">
                    @if($category->image)
                        <img src="{{ $category->image }}" 
                             alt="{{ $category->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900"></div>
                    @endif
                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition-colors">
                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="text-xl font-bold">{{ $category->name }}</h3>
                            <p class="text-sm">Explore →</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- New Arrivals --}}
<section class="new-arrivals py-16">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">New Arrivals</h2>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-sm uppercase tracking-wider hover:underline">
                View All →
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($newArrivals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

{{-- Limited Editions Showcase --}}
@if($limitedShowcase)
<section class="limited-editions py-16 bg-black text-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Limited Edition Drops</h2>
        
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
                @if($limitedShowcase->primaryImage)
                    <img src="{{ $limitedShowcase->primaryImage->image_path }}" 
                         alt="{{ $limitedShowcase->name }}" 
                         class="rounded-lg">
                @else
                    <div class="aspect-square bg-gray-800 rounded-lg"></div>
                @endif
            </div>
            <div>
                <p class="text-sm uppercase tracking-wider text-accent-gold mb-4">Exclusive Release</p>
                <h3 class="text-4xl font-bold mb-6">
                    {{ $limitedShowcase->brand->name }}<br>
                    {{ $limitedShowcase->name }}
                </h3>
                <p class="text-gray-300 mb-8">
                    {{ Str::limit($limitedShowcase->description, 200) }}
                </p>
                <div class="mb-8">
                    <div class="text-sm text-gray-400 mb-2">Stock Remaining</div>
                    <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                        @php
                            $stockPercentage = ($limitedShowcase->stock / 100) * 100;
                        @endphp
                        <div class="h-full bg-accent-gold" style="width: {{ $stockPercentage }}%"></div>
                    </div>
                    <div class="text-sm mt-1">{{ $limitedShowcase->stock }} / 100 pieces left</div>
                </div>
                <a href="/products/{{ $limitedShowcase->slug }}" class="btn-primary inline-block">
                    Shop Now - ${{ number_format($limitedShowcase->final_price, 2) }}
                </a>
            </div>
        </div>
    </div>
</section>
@endif

{{-- Brand Showcase - Infinite Scroll Carousel --}}
<x-brand-carousel />

{{-- Newsletter --}}
<section class="newsletter py-16 bg-gray-50">
    <div class="container mx-auto px-4 max-w-2xl text-center">
        <h2 class="text-3xl font-bold mb-4">Stay Updated</h2>
        <p class="text-gray-600 mb-8">Subscribe to get notified about exclusive drops and limited releases.</p>
        
        <form class="flex flex-col sm:flex-row gap-4" x-data="{ email: '', submitted: false }" @submit.prevent="submitted = true">
            <input type="email" 
                   x-model="email"
                   placeholder="Enter your email" 
                   class="flex-1 px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-black"
                   required>
            <button type="submit" class="btn-primary">Subscribe</button>
        </form>
        
        <p class="text-sm text-gray-500 mt-4">We respect your privacy. Unsubscribe anytime.</p>
    </div>
</section>

<script>
function countdownTimer(targetDate) {
    return {
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        init() {
            this.updateCountdown();
            setInterval(() => this.updateCountdown(), 1000);
        },
        updateCountdown() {
            const now = new Date().getTime();
            const target = new Date(targetDate).getTime();
            const distance = target - now;
            
            if (distance < 0) {
                this.days = this.hours = this.minutes = this.seconds = 0;
                return;
            }
            
            this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
            this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
        }
    }
}
</script>

@endsection
