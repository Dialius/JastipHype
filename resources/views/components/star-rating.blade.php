{{-- Star Rating Component --}}
@props([
    'rating' => 0,
    'size' => 'md',
    'interactive' => false,
    'name' => 'rating'
])

@php
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-8 h-8',
    ];
    $starSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

@if($interactive)
    {{-- Interactive Star Rating (for input) --}}
    <div 
        x-data="{
            rating: {{ $rating }},
            hoverRating: 0,
            setRating(value) {
                this.rating = value;
                this.$refs.hiddenInput.value = value;
            }
        }"
        class="flex items-center gap-1"
    >
        <input type="hidden" name="{{ $name }}" x-ref="hiddenInput" :value="rating">
        
        @for ($i = 1; $i <= 5; $i++)
            <button
                type="button"
                @click="setRating({{ $i }})"
                @mouseenter="hoverRating = {{ $i }}"
                @mouseleave="hoverRating = 0"
                class="focus:outline-none transition-transform hover:scale-110"
            >
                <svg 
                    class="{{ $starSize }} transition-colors"
                    :class="{
                        'text-black fill-current': (hoverRating || rating) >= {{ $i }},
                        'text-gray-300 fill-current': (hoverRating || rating) < {{ $i }}
                    }"
                    viewBox="0 0 24 24"
                >
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
            </button>
        @endfor
        
        <span x-show="rating > 0" class="ml-2 text-sm font-medium text-gray-700" x-text="rating + ' stars'"></span>
    </div>
@else
    {{-- Display-only Star Rating --}}
    <div class="flex items-center gap-0.5">
        @for ($i = 1; $i <= 5; $i++)
            <svg 
                class="{{ $starSize }} {{ $i <= $rating ? 'text-black fill-current' : 'text-gray-300 fill-current' }}"
                viewBox="0 0 24 24"
            >
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
        @endfor
    </div>
@endif
