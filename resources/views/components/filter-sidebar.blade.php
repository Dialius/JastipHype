@props(['categories', 'brands', 'minPrice', 'maxPrice'])

<div class="bg-white rounded-lg p-6 sticky top-4">
    <form id="filter-form" method="GET" action="{{ route('products.index') }}" x-data="filterForm()" @submit.prevent>
        <!-- Filter Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold">Filters</h2>
            @if(request()->hasAny(['category', 'brands', 'min_price', 'max_price', 'new', 'availability']))
                <button type="button" 
                        onclick="window.location.href='{{ route('products.index') }}'"
                        class="text-sm text-gray-600 hover:text-black underline">
                    Clear all
                </button>
            @endif
        </div>

        <!-- Availability Filter (Radio Buttons) -->
        <div class="mb-6 pb-6 border-b" x-data="{ open: true }">
            <button type="button" 
                    @click="open = !open"
                    class="w-full flex justify-between items-center text-left font-semibold mb-4">
                <span>Availability</span>
                <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-3">
                <!-- All -->
                <label class="flex items-center cursor-pointer group">
                    <input type="radio" 
                           name="availability" 
                           value="all"
                           {{ !request('availability') || request('availability') == 'all' ? 'checked' : '' }}
                           class="w-4 h-4 text-black border-gray-300 focus:ring-black focus:ring-2">
                    <span class="ml-3 text-sm text-gray-700 group-hover:text-black">All</span>
                </label>
                
                <!-- In Stock -->
                <label class="flex items-center cursor-pointer group">
                    <input type="radio" 
                           name="availability" 
                           value="in_stock"
                           {{ request('availability') == 'in_stock' ? 'checked' : '' }}
                           class="w-4 h-4 text-black border-gray-300 focus:ring-black focus:ring-2">
                    <span class="ml-3 text-sm text-gray-700 group-hover:text-black">In Stock</span>
                </label>
            </div>
        </div>

        <!-- Categories Filter (Radio Buttons) -->
        <div class="mb-6 pb-6 border-b" x-data="{ open: true }">
            <button type="button" 
                    @click="open = !open"
                    class="w-full flex justify-between items-center text-left font-semibold mb-4">
                <span>Categories</span>
                <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-3">
                <!-- All Categories Option -->
                <label class="flex items-center cursor-pointer group">
                    <input type="radio" 
                           name="category" 
                           value=""
                           {{ !request('category') ? 'checked' : '' }}
                           class="w-4 h-4 text-black border-gray-300 focus:ring-black focus:ring-2">
                    <span class="ml-3 text-sm text-gray-700 group-hover:text-black">All Categories</span>
                </label>
                
                @foreach($categories as $category)
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" 
                               name="category" 
                               value="{{ $category->id }}"
                               {{ request('category') == $category->id ? 'checked' : '' }}
                               class="w-4 h-4 text-black border-gray-300 focus:ring-black focus:ring-2">
                        <span class="ml-3 text-sm text-gray-700 group-hover:text-black">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- New Arrivals Filter (Checkbox) -->
        <div class="mb-6 pb-6 border-b" x-data="{ open: true }">
            <button type="button" 
                    @click="open = !open"
                    class="w-full flex justify-between items-center text-left font-semibold mb-4">
                <span>Product Type</span>
                <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-3">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" 
                           name="new" 
                           value="1"
                           {{ request('new') ? 'checked' : '' }}
                           class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black focus:ring-2">
                    <span class="ml-3 text-sm text-gray-700 group-hover:text-black">New</span>
                </label>
                
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" 
                           name="discount" 
                           value="1"
                           {{ request('discount') ? 'checked' : '' }}
                           class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black focus:ring-2">
                    <span class="ml-3 text-sm text-gray-700 group-hover:text-black">Discount</span>
                </label>
            </div>
        </div>

        <!-- Brands Filter (Checkboxes) -->
        <div class="mb-6 pb-6 border-b" x-data="{ open: true, showAll: false }">
            <button type="button" 
                    @click="open = !open"
                    class="w-full flex justify-between items-center text-left font-semibold mb-4">
                <span>Brand</span>
                <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-3">
                @foreach($brands as $index => $brand)
                    <label class="flex items-center cursor-pointer group"
                           x-show="showAll || {{ $index }} < 5">
                        <input type="checkbox" 
                               name="brands[]" 
                               value="{{ $brand->id }}"
                               {{ in_array($brand->id, request('brands', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black focus:ring-2">
                        <span class="ml-3 text-sm text-gray-700 group-hover:text-black">{{ $brand->name }}</span>
                    </label>
                @endforeach
                
                @if($brands->count() > 5)
                    <button type="button" 
                            @click="showAll = !showAll"
                            class="flex items-center gap-1 text-sm text-gray-700 hover:text-black mt-2">
                        <span x-text="showAll ? 'See Less' : 'See More'"></span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showAll }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Price Range Filter with Dual Slider -->
        <div class="mb-6 pb-6 border-b" x-data="priceSlider({{ $minPrice }}, {{ $maxPrice }})">
            <button type="button" 
                    @click="open = !open"
                    class="w-full flex justify-between items-center text-left font-semibold mb-4">
                <span>Price</span>
                <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" x-collapse>
                <div class="space-y-4">
                    <!-- Dynamic Price Display (Updates when dragging) -->
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Min</div>
                            <div class="text-sm font-semibold">Rp <span x-text="formatPrice(minValue)"></span></div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500 mb-1">Max</div>
                            <div class="text-sm font-semibold">Rp <span x-text="formatPrice(maxValue)"></span></div>
                        </div>
                    </div>

                    <!-- Dual Handle Range Slider -->
                    <div class="slider-wrapper">
                        <!-- Background Track (Gray) -->
                        <div class="slider-track"></div>
                        
                        <!-- Active Range (Black bar between thumbs) -->
                        <div class="slider-range" 
                             :style="`left: ${minPercent}%; right: ${100 - maxPercent}%`"></div>
                        
                        <!-- Min Range Input -->
                        <input type="range" 
                               x-model.number="minValue"
                               :min="absoluteMin"
                               :max="absoluteMax"
                               step="10000"
                               @input="updateMin"
                               @change="$dispatch('change')"
                               class="thumb thumb-left">
                        
                        <!-- Max Range Input -->
                        <input type="range" 
                               x-model.number="maxValue"
                               :min="absoluteMin"
                               :max="absoluteMax"
                               step="10000"
                               @input="updateMax"
                               @change="$dispatch('change')"
                               class="thumb thumb-right">
                    </div>

                    <!-- Static Range Labels (Min - Max absolute values) -->
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Rp <span x-text="formatPrice(absoluteMin)"></span></span>
                        <span>Rp <span x-text="formatPrice(absoluteMax)"></span></span>
                    </div>

                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" name="min_price" :value="minValue">
                    <input type="hidden" name="max_price" :value="maxValue">
                </div>
            </div>
        </div>


    </form>
</div>

<style>
/* Checkbox and Radio Button Styles - BLACK */
input[type="checkbox"],
input[type="radio"] {
    accent-color: #000000;
    cursor: pointer;
}

/* Custom Checkbox */
input[type="checkbox"] {
    appearance: none;
    -webkit-appearance: none;
    width: 16px;
    height: 16px;
    border: 2px solid #d1d5db;
    border-radius: 0.25rem;
    background-color: white;
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease;
}

input[type="checkbox"]:hover {
    border-color: #9ca3af;
}

input[type="checkbox"]:checked {
    background-color: #000000;
    border-color: #000000;
}

input[type="checkbox"]:checked::after {
    content: '';
    position: absolute;
    left: 4px;
    top: 1px;
    width: 4px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

/* Custom Radio Button */
input[type="radio"] {
    appearance: none;
    -webkit-appearance: none;
    width: 16px;
    height: 16px;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    background-color: white;
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

input[type="radio"]:hover {
    border-color: #9ca3af;
}

input[type="radio"]:checked {
    border-color: #000000;
}

input[type="radio"]:checked::after {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #000000;
}

/* Focus states */
input[type="checkbox"]:focus,
input[type="radio"]:focus {
    outline: 1px solid rgba(0, 0, 0, 0.4);
    outline-offset: 1px;
}

/* ============================================
   DUAL RANGE SLIDER STYLES (BLACK THEME)
   ============================================ */

/* Slider Container */
.slider-wrapper {
    position: relative;
    height: 50px;
    display: flex;
    align-items: center;
}

/* Background Track (Gray) */
.slider-track {
    position: absolute;
    width: 100%;
    height: 4px;
    background-color: #e5e7eb;
    border-radius: 4px;
    z-index: 1;
}

/* Active Range (Black bar between thumbs) */
.slider-range {
    position: absolute;
    height: 4px;
    background-color: #000000;
    border-radius: 4px;
    z-index: 2;
}

/* Base Range Input Styles */
.thumb {
    position: absolute;
    width: 100%;
    height: 4px;
    background: none;
    pointer-events: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

/* Z-index layering */
.thumb-left {
    z-index: 3;
}

.thumb-right {
    z-index: 4;
}

/* Remove default focus outline */
.thumb:focus {
    outline: none;
}

/* ===== Webkit (Chrome, Safari, Edge) ===== */
.thumb::-webkit-slider-runnable-track {
    width: 100%;
    height: 4px;
    background: transparent;
    border: none;
    outline: none;
}

.thumb::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ffffff;
    border: 3px solid #000000;
    cursor: pointer;
    pointer-events: auto;
    margin-top: -8px; /* Center thumb on track */
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    transition: all 0.2s ease;
}

.thumb::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
}

.thumb::-webkit-slider-thumb:active {
    cursor: grabbing;
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

/* ===== Firefox ===== */
.thumb::-moz-range-track {
    width: 100%;
    height: 4px;
    background: transparent;
    border: none;
    outline: none;
}

.thumb::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ffffff;
    border: 3px solid #000000;
    cursor: pointer;
    pointer-events: auto;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    transition: all 0.2s ease;
}

.thumb::-moz-range-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
}

.thumb::-moz-range-thumb:active {
    cursor: grabbing;
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

/* ===== Edge Legacy ===== */
.thumb::-ms-track {
    width: 100%;
    height: 4px;
    background: transparent;
    border-color: transparent;
    color: transparent;
}

.thumb::-ms-fill-lower,
.thumb::-ms-fill-upper {
    background: transparent;
}

.thumb::-ms-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ffffff;
    border: 3px solid #000000;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}
</style>

<script>
function filterForm() {
    return {
        open: true
    }
}

function priceSlider(min, max) {
    // Calculate center position with gap (35% to 65% of range)
    const range = max - min;
    const defaultMin = min; // Start at 0%
    const defaultMax = max; // End at 100%
    
    return {
        open: true,
        absoluteMin: min,
        absoluteMax: max,
        minValue: {{ request('min_price') ?: 'null' }} ?? defaultMin,
        maxValue: {{ request('max_price') ?: 'null' }} ?? defaultMax,
        
        get minPercent() {
            return ((this.minValue - this.absoluteMin) / (this.absoluteMax - this.absoluteMin)) * 100;
        },
        
        get maxPercent() {
            return ((this.maxValue - this.absoluteMin) / (this.absoluteMax - this.absoluteMin)) * 100;
        },
        
        updateMin() {
            const min = parseInt(this.minValue);
            const max = parseInt(this.maxValue);
            if (min >= max) {
                this.minValue = max - 10000;
            }
        },
        
        updateMax() {
            const min = parseInt(this.minValue);
            const max = parseInt(this.maxValue);
            if (max <= min) {
                this.maxValue = min + 10000;
            }
        },
        
        formatPrice(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        }
    }
}
</script>