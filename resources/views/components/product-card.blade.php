@php
    // Get product images from relationship
    $productImages = $product->productImages ?? collect([]);
    
    // Primary image (first image or null)
    if ($productImages->first()) {
        $primaryImage = image_url($productImages->first()->image_path);
    } else {
        $primaryImage = asset('images/placeholder-product.svg');
    }
    
    // Second image for hover effect (second image or same as primary)
    if ($productImages->count() > 1) {
        $secondImage = image_url($productImages->skip(1)->first()->image_path);
    } else {
        $secondImage = $primaryImage;
    }
    
    // Calculate discount
    $discount = 0;
    if (isset($product->sale_price) && $product->sale_price > 0 && $product->sale_price < $product->price) {
        $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
    }
@endphp

<div class="product-card bg-gray-50 rounded-lg overflow-hidden group transition-colors hover:bg-white">
    <a href="/products/{{ $product->slug }}" class="block">
        <!-- Product Image with Hover Effect -->
        <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">
            <!-- Primary Image -->
            <img src="{{ $primaryImage }}" 
                 alt="{{ $product->name }}"
                 class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-0">
            
            <!-- Second Image (Shows on Hover) -->
            <img src="{{ $secondImage }}" 
                 alt="{{ $product->name }} - Back View"
                 class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 opacity-0 group-hover:opacity-100">
            
            <!-- Discount Percentage Badge (Top Right) -->
            @if($discount > 0)
                <div class="absolute top-2 right-2 bg-black text-white text-sm px-2.5 py-1 font-bold z-10">
                    {{ $discount }}%
                </div>
            @endif
            
            <!-- Limited Edition Badge -->
            @if($product->is_limited_edition)
                <div class="absolute top-2 left-2 bg-accent-gold text-white text-xs px-2 py-1 rounded font-semibold z-10">
                    LIMITED
                </div>
            @endif
            
            <!-- Low Stock Warning -->
            @if($product->stock < 10 && $product->stock > 0)
                <div class="absolute bottom-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded z-10">
                    Only {{ $product->stock }} left
                </div>
            @endif
        </div>
        
        <!-- Product Info with Padding -->
        <div class="p-4">
            <!-- Product Name -->
            <h3 class="font-medium mb-2 line-clamp-2 text-sm text-gray-900">{{ $product->name }}</h3>
            
            <!-- Price -->
            <div class="flex flex-col gap-1">
                @if($discount > 0)
                    <!-- Original Price (Strikethrough) -->
                    <div class="text-gray-400 line-through text-sm">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                    <!-- Sale Price -->
                    <div class="text-black font-bold text-base">
                        Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                    </div>
                @else
                    <!-- Regular Price -->
                    <div class="text-black font-bold text-base">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                @endif
            </div>
        </div>
    </a>
</div>
