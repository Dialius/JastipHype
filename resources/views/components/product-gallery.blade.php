@props(['product'])

<div x-data="productGallery()" x-init="init()" class="space-y-4">
    @php
        $productImages = $product->productImages ?? collect([]);
        $imageCount = $productImages->count();
    @endphp

    @if($imageCount > 0)
        {{-- Main Image Display --}}
        <div class="relative aspect-square bg-gray-100 overflow-hidden group cursor-pointer" @click="openLightbox()">
            @foreach($productImages as $index => $image)
                @php
                    // Check if image_path is a full URL or a storage path
                    $imageSrc = str_starts_with($image->image_path, 'http') 
                        ? $image->image_path 
                        : \App\Helpers\ImageHelper::getImageUrl($image->image_path);
                @endphp
                <img 
                    x-show="selectedIndex === {{ $index }}"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    src="{{ $imageSrc }}" 
                    alt="{{ $product->name }}"
                    class="absolute inset-0 w-full h-full object-cover"
                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                >
            @endforeach
            
            {{-- Zoom Indicator on Hover --}}
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors flex items-center justify-center">
                <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-60 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                </svg>
            </div>
        </div>

        {{-- Thumbnails Grid --}}
        @if($imageCount > 1)
            <div class="grid grid-cols-4 gap-2 lg:gap-3">
                @foreach($productImages as $index => $image)
                    @php
                        $imageSrc = str_starts_with($image->image_path, 'http') 
                            ? $image->image_path 
                            : asset('storage/' . $image->image_path);
                    @endphp
                    <button
                        type="button"
                        @click="selectImage({{ $index }})"
                        class="relative aspect-square bg-gray-100 overflow-hidden border-2 transition-all duration-200 hover:border-black"
                        :class="selectedIndex === {{ $index }} ? 'border-black' : 'border-gray-200'"
                    >
                        <img 
                            src="{{ $imageSrc }}" 
                            alt="{{ $product->name }} - View {{ $index + 1 }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                        
                        {{-- Checkmark Indicator (Chambre Style) --}}
                        <div 
                            x-show="selectedIndex === {{ $index }}"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-50"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute top-1 right-1 bg-black rounded-full p-1"
                        >
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Lightbox Modal --}}
        <div 
            x-show="lightboxOpen"
            x-cloak
            @keydown.escape.window="lightboxOpen = false"
            @click.self="lightboxOpen = false"
            class="fixed inset-0 z-[9999] bg-black/95 flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            {{-- Close Button --}}
            <button 
                @click="lightboxOpen = false"
                class="absolute top-4 right-4 z-10 p-2 bg-white/10 hover:bg-white/20 rounded-full transition-colors"
                aria-label="Close lightbox"
            >
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Lightbox Image --}}
            <div class="relative max-w-6xl max-h-screen">
                @foreach($productImages as $index => $image)
                    @php
                        $imageSrc = str_starts_with($image->image_path, 'http') 
                            ? $image->image_path 
                            : asset('storage/' . $image->image_path);
                    @endphp
                    <img 
                        x-show="selectedIndex === {{ $index }}"
                        src="{{ $imageSrc }}" 
                        alt="{{ $product->name }}"
                        class="max-w-full max-h-screen object-contain"
                    >
                @endforeach
            </div>

            {{-- Navigation Arrows (if multiple images) --}}
            @if($imageCount > 1)
                <button 
                    @click.stop="prevImage()"
                    class="absolute left-4 top-1/2 -translate-y-1/2 p-3 bg-white/10 hover:bg-white/20 rounded-full transition-colors"
                    aria-label="Previous image"
                >
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button 
                    @click.stop="nextImage()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 p-3 bg-white/10 hover:bg-white/20 rounded-full transition-colors"
                    aria-label="Next image"
                >
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Image Counter --}}
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 px-4 py-2 bg-black/50 rounded-full text-white text-sm">
                    <span x-text="selectedIndex + 1"></span> / <span x-text="totalImages"></span>
                </div>
            @endif
        </div>

    @else
        {{-- No Images Placeholder --}}
        <div class="aspect-square bg-gray-100 flex items-center justify-center text-gray-400">
            <div class="text-center">
                <svg class="w-24 h-24 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm">No images available</p>
            </div>
        </div>
    @endif
</div>

<script>
function productGallery() {
    return {
        selectedIndex: 0,
        totalImages: {{ $imageCount }},
        lightboxOpen: false,
        
        selectImage(index) {
            this.selectedIndex = index;
        },
        
        nextImage() {
            this.selectedIndex = (this.selectedIndex + 1) % this.totalImages;
        },
        
        prevImage() {
            this.selectedIndex = (this.selectedIndex - 1 + this.totalImages) % this.totalImages;
        },
        
        openLightbox() {
            this.lightboxOpen = true;
            // Prevent body scroll when lightbox is open
            document.body.style.overflow = 'hidden';
        },
        
        init() {
            // Watch for lightbox close to restore scroll
            this.$watch('lightboxOpen', (value) => {
                if (!value) {
                    document.body.style.overflow = '';
                }
            });
            
            // Keyboard navigation in lightbox
            window.addEventListener('keydown', (e) => {
                if (this.lightboxOpen) {
                    if (e.key === 'ArrowRight') this.nextImage();
                    if (e.key === 'ArrowLeft') this.prevImage();
                }
            });
        }
    }
}
</script>
