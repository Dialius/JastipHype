{{-- Brand Carousel Component - Infinite Scroll --}}
<section class="py-16 bg-white overflow-hidden">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Brands</h2>
        
        <div class="relative">
            {{-- Infinite Scroll Container with improved smooth loop --}}
            <div class="brand-carousel-wrapper">
                <div class="brand-carousel-track">
                    {{-- Triple duplication for ultra-smooth seamless loop --}}
                    
                    {{-- Set 1 --}}
                    <div class="brand-item"><img src="{{ asset('images/brands/Supreme.png') }}" alt="Supreme" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Off-White.png') }}" alt="Off-White" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/BAPE.png') }}" alt="BAPE" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Chrome-Hearts.png') }}" alt="Chrome Hearts" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Essentials.png') }}" alt="Essentials" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Stone-Island.png') }}" alt="Stone Island" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Stüssy.png') }}" alt="Stüssy" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Palace.png') }}" alt="Palace" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Nike.png') }}" alt="Nike" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Jordan-Brand.png') }}" alt="Jordan Brand" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Carhartt.png') }}" alt="Carhartt WIP" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Kith.png') }}" alt="Kith" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/The-North-Face.png') }}" alt="The North Face" class="brand-logo"></div>

                    {{-- Set 2 --}}
                    <div class="brand-item"><img src="{{ asset('images/brands/Supreme.png') }}" alt="Supreme" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Off-White.png') }}" alt="Off-White" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/BAPE.png') }}" alt="BAPE" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Chrome-Hearts.png') }}" alt="Chrome Hearts" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Essentials.png') }}" alt="Essentials" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Stone-Island.png') }}" alt="Stone Island" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Stüssy.png') }}" alt="Stüssy" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Palace.png') }}" alt="Palace" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Nike.png') }}" alt="Nike" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Jordan-Brand.png') }}" alt="Jordan Brand" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Carhartt.png') }}" alt="Carhartt WIP" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Kith.png') }}" alt="Kith" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/The-North-Face.png') }}" alt="The North Face" class="brand-logo"></div>

                    {{-- Set 3 --}}
                    <div class="brand-item"><img src="{{ asset('images/brands/Supreme.png') }}" alt="Supreme" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Off-White.png') }}" alt="Off-White" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/BAPE.png') }}" alt="BAPE" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Chrome-Hearts.png') }}" alt="Chrome Hearts" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Essentials.png') }}" alt="Essentials" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Stone-Island.png') }}" alt="Stone Island" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Stüssy.png') }}" alt="Stüssy" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Palace.png') }}" alt="Palace" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Nike.png') }}" alt="Nike" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Jordan-Brand.png') }}" alt="Jordan Brand" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Carhartt.png') }}" alt="Carhartt WIP" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/Kith.png') }}" alt="Kith" class="brand-logo"></div>
                    <div class="brand-item"><img src="{{ asset('images/brands/The-North-Face.png') }}" alt="The North Face" class="brand-logo"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .brand-carousel-wrapper {
        overflow: hidden;
        position: relative;
        width: 100%;
    }

    .brand-carousel-track {
        display: flex;
        gap: 80px;
        width: max-content;
        animation: scroll-smooth 60s linear infinite;
        will-change: transform;
    }

    .brand-carousel-track:hover {
        animation-play-state: paused;
    }

    .brand-item {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 150px;
        height: 80px;
    }

    .brand-logo {
        max-width: 150px;
        max-height: 60px;
        width: auto;
        height: auto;
        object-fit: contain;
        filter: grayscale(100%) opacity(0.7);
        transition: all 0.3s ease;
    }

    .brand-logo:hover {
        filter: grayscale(0%) opacity(1);
        transform: scale(1.1);
    }

    /* Smooth infinite animation - no jump at loop point */
    @keyframes scroll-smooth {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(calc(-100% / 3));
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .brand-carousel-track {
            gap: 60px;
            animation: scroll-smooth 45s linear infinite;
        }

        .brand-item {
            min-width: 120px;
            height: 60px;
        }

        .brand-logo {
            max-width: 120px;
            max-height: 50px;
        }
    }

    @media (max-width: 480px) {
        .brand-carousel-track {
            gap: 40px;
            animation: scroll-smooth 35s linear infinite;
        }

        .brand-item {
            min-width: 100px;
            height: 50px;
        }

        .brand-logo {
            max-width: 100px;
            max-height: 40px;
        }
    }
</style>
