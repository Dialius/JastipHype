{{-- Custom Select Modal Component --}}
@props([
    'id' => 'custom-select',
    'label' => 'Select Option',
    'placeholder' => 'Search...',
    'items' => [],
    'displayKey' => 'name',
    'valueKey' => 'id',
    'searchable' => true,
    'required' => false,
    'disabled' => false
])

<div x-data="customSelectModal{{ ucfirst($id) }}()" class="relative">
    {{-- Hidden Input for Form Submission --}}
    <input type="hidden" 
           :name="'{{ $id }}'" 
           :value="selectedValue"
           {{ $required ? 'required' : '' }}>
    
    {{-- Trigger Button --}}
    <button type="button"
            @click="open()"
            :disabled="{{ $disabled ? 'true' : 'disabled' }}"
            class="block w-full px-4 py-3 text-left text-base bg-gray-50 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-0 focus:border-black transition-colors disabled:bg-gray-100 disabled:cursor-not-allowed"
            :class="selectedValue ? 'text-gray-900' : 'text-gray-400'">
        <span x-text="selectedLabel || '{{ $placeholder }}'"></span>
    </button>

    {{-- Modal --}}
    <template x-teleport="body">
        <div x-show="isOpen"
             x-cloak
             @keydown.escape.window="close()"
             x-init="$watch('isOpen', value => { 
                 if (value) {
                     document.body.style.overflow = 'hidden';
                     $nextTick(() => $refs.searchInput?.focus());
                 } else {
                     document.body.style.overflow = 'auto';
                 }
             })"
             class="fixed inset-0 z-[99999] overflow-y-auto"
             style="display: none;">
            
            {{-- Backdrop --}}
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="close()"
                 class="fixed inset-0 bg-black/50"></div>

            {{-- Modal Container --}}
            <div class="flex min-h-screen items-end lg:items-center justify-center p-0 lg:p-4">
                <div x-show="isOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-full lg:translate-y-4 lg:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 lg:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 lg:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-full lg:translate-y-4 lg:scale-95"
                     @click.outside="close()"
                     class="relative bg-white w-full lg:max-w-lg overflow-hidden shadow-2xl transform transition-all rounded-t-2xl lg:rounded-lg max-h-[90vh] flex flex-col">
                    
                    {{-- Header --}}
                    <div class="px-6 py-5 border-b border-gray-200 bg-white flex-shrink-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-black">{{ $label }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Select from list below</p>
                            </div>
                            <button @click="close()"
                                    class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Search Box --}}
                    @if($searchable)
                    <div class="px-6 py-4 border-b border-gray-100 flex-shrink-0">
                        <input type="text"
                               x-ref="searchInput"
                               x-model="searchQuery"
                               placeholder="Search {{ strtolower($label) }}..."
                               class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-lg focus:outline-none focus:border-black transition-colors">
                    </div>
                    @endif

                    {{-- Items List --}}
                    <div class="overflow-y-auto flex-1" style="scrollbar-width: thin;">
                        <div class="p-4 space-y-2">
                            <template x-for="item in filteredItems" :key="item.{{ $valueKey }}">
                                <button type="button"
                                        @click="selectItem(item)"
                                        class="w-full text-left px-4 py-3 border-2 border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-colors"
                                        :class="selectedValue == item.{{ $valueKey }} ? 'bg-gray-50 border-black font-medium' : ''">
                                    <span x-text="item.{{ $displayKey }}" class="text-base"></span>
                                </button>
                            </template>
                            
                            {{-- No Results --}}
                            <div x-show="filteredItems.length === 0" 
                                 class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <p class="font-medium">No results found</p>
                                <p class="text-sm mt-1">Try a different search term</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
window.customSelectModal{{ ucfirst($id) }} = function() {
    return {
        isOpen: false,
        searchQuery: '',
        selectedValue: '',
        selectedLabel: '',
        items: @json($items),
        
        get filteredItems() {
            if (!this.searchQuery) return this.items;
            
            const query = this.searchQuery.toLowerCase();
            return this.items.filter(item => {
                const label = item.{{ $displayKey }}.toLowerCase();
                return label.includes(query);
            });
        },
        
        open() {
            if (!{{ $disabled ? 'true' : 'false' }}) {
                this.isOpen = true;
            }
        },
        
        close() {
            this.isOpen = false;
            this.searchQuery = '';
        },
        
        selectItem(item) {
            this.selectedValue = item.{{ $valueKey }};
            this.selectedLabel = item.{{ $displayKey }};
            this.close();
            
            // Dispatch event for parent component
            this.$dispatch('select-changed', {
                id: '{{ $id }}',
                value: item.{{ $valueKey }},
                label: item.{{ $displayKey }},
                item: item
            });
        },
        
        reset() {
            this.selectedValue = '';
            this.selectedLabel = '';
            this.searchQuery = '';
        }
    }
}
</script>
