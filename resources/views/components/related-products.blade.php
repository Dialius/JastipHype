@props(['products'])

<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-black mb-8">You Might Also Like</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($products->take(8) as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</div>
