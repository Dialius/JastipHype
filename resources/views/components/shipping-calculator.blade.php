{{-- Shipping Calculator Modal Component --}}
<div 
    x-data="{
        showShipping: false,
        step: 1,
        selectedProvince: null,
        selectedCity: null,
        provinces: [],
        cities: [],
        shippingCosts: [],
        isLoading: false,
        provinceSearch: '',
        citySearch: '',
        
        async init() {
            // Load provinces when component mounts
            await this.loadProvinces();
        },
        
        async loadProvinces() {
            this.isLoading = true;
            try {
                const response = await fetch('/api/location/provinces');
                const data = await response.json();
                if(data.rajaongkir && data.rajaongkir.results) {
                    this.provinces = data.rajaongkir.results;
                }
            } catch (error) {
                console.error('Failed to load provinces:', error);
                $notify('Failed to load provinces', 'error');
            } finally {
                this.isLoading = false;
            }
        },
        
        async loadCities(provinceId) {
            this.isLoading = true;
            this.cities = [];
            try {
                const response = await fetch(`/api/location/cities/${provinceId}`);
                const data = await response.json();
                if(data.rajaongkir && data.rajaongkir.results) {
                    this.cities = data.rajaongkir.results;
                }
            } catch (error) {
                console.error('Failed to load cities:', error);
                $notify('Failed to load cities', 'error');
            } finally {
                this.isLoading = false;
            }
        },
        
        selectProvince(province) {
            this.selectedProvince = province;
            this.selectedCity = null;
            this.cities = [];
            this.loadCities(province.province_id);
            this.step = 2;
        },
        
        async selectCity(city) {
            this.selectedCity = city;
            this.step = 3;
            await this.calculateShipping();
        },
        
        async calculateShipping() {
            this.isLoading = true;
            try {
                const response = await fetch('/api/location/cost', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({
                        destination: this.selectedCity.city_id,
                        weight: {{ $product->weight ?? 500 }},
                        courier: 'jne'
                    })
                });
                
                const data = await response.json();
                if (data.rajaongkir && data.rajaongkir.results) {
                    this.shippingCosts = data.rajaongkir.results;
                }
            } catch (error) {
                console.error('Failed to calculate shipping:', error);
                $notify('Failed to calculate shipping cost', 'error');
            } finally {
                this.isLoading = false;
            }
        },
        
        reset() {
            this.step = 1;
            this.selectedProvince = null;
            this.selectedCity = null;
            this.cities = [];
            this.shippingCosts = [];
            this.provinceSearch = '';
            this.citySearch = '';
        },
        
        get filteredProvinces() {
            if (!this.provinceSearch) return this.provinces;
            return this.provinces.filter(p => 
                p.province.toLowerCase().includes(this.provinceSearch.toLowerCase())
            );
        },
        
        get filteredCities() {
            if (!this.citySearch) return this.cities;
            return this.cities.filter(c => 
                c.city_name.toLowerCase().includes(this.citySearch.toLowerCase())
            );
        }
    }"
>
    {{-- Trigger Button --}}
    <button 
        @click="showShipping = true"
        type="button"
        class="w-full border border-gray-200 rounded-lg p-4 bg-gray-50 hover:bg-gray-100 transition-colors flex items-center justify-between"
    >
        <div class="text-left">
            <p class="text-xs font-bold text-black mb-1">Delivery Location</p>
            <p x-show="!selectedCity" class="text-sm text-gray-500">Pick Area</p>
            <p x-show="selectedCity" class="text-sm font-medium text-black" x-text="selectedCity ? `${selectedCity.type} ${selectedCity.city_name}, ${selectedProvince.province}` : ''"></p>
        </div>
        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    {{-- Shipping Calculator Modal --}}
    <template x-teleport="body">
        <div 
            x-show="showShipping"
            x-cloak
            @keydown.escape.window="showShipping = false; reset();"
            x-init="$watch('showShipping', value => { 
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'auto';
                    reset();
                }
            })"
            class="fixed inset-0 z-[99999] overflow-y-auto"
            style="scrollbar-width: none; -ms-overflow-style: none;"
        >
            {{-- Backdrop --}}
            <div 
                x-show="showShipping"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/80"
                @click="showShipping = false; reset();"
            ></div>

            {{-- Modal --}}
            <div class="flex min-h-screen items-end lg:items-center justify-center p-0 lg:p-4">
                <div 
                    x-show="showShipping"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-full lg:translate-y-4 lg:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 lg:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 lg:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-full lg:translate-y-4 lg:scale-95"
                    class="relative bg-white w-full lg:max-w-lg overflow-hidden shadow-2xl transform transition-all rounded-t-2xl lg:rounded-lg max-h-[90vh]"
                >
                    {{-- Header --}}
                    <div class="px-6 py-5 border-b border-gray-200 bg-white flex items-center justify-between sticky top-0 z-10">
                        <div>
                            <h3 class="text-lg font-bold text-black">Delivery Estimation</h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <span x-show="step === 1">Select Province</span>
                                <span x-show="step === 2">Select City</span>
                                <span x-show="step === 3">Shipping Options</span>
                            </p>
                        </div>
                        <button 
                            @click="showShipping = false; reset();"
                            class="p-2 hover:bg-gray-100 rounded-full transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]" style="scrollbar-width: none; -ms-overflow-style: none;">
                        {{-- Step 1: Province Selection --}}
                        <div x-show="step === 1">
                            {{-- Search --}}
                            <div class="mb-4">
                                <input 
                                    type="text"
                                    x-model="provinceSearch"
                                    placeholder="Search province..."
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                                >
                            </div>

                            {{-- Province List --}}
                            <div class="space-y-2">
                                <template x-for="province in filteredProvinces" :key="province.province_id">
                                    <button 
                                        @click="selectProvince(province)"
                                        class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                    >
                                        <span x-text="province.province" class="font-medium"></span>
                                    </button>
                                </template>
                                
                                <div x-show="filteredProvinces.length === 0" class="text-center py-8 text-gray-500">
                                    <p>No provinces found</p>
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: City Selection --}}
                        <div x-show="step === 2">
                            {{-- Back Button --}}
                            <button 
                                @click="step = 1; selectedCity = null;"
                                class="mb-4 flex items-center gap-2 text-sm text-gray-600 hover:text-black"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to provinces
                            </button>

                            {{-- Search --}}
                            <div class="mb-4">
                                <input 
                                    type="text"
                                    x-model="citySearch"
                                    placeholder="Search city..."
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                                >
                            </div>

                            {{-- City List --}}
                            <div x-show="!isLoading" class="space-y-2">
                                <template x-for="city in filteredCities" :key="city.city_id">
                                    <button 
                                        @click="selectCity(city)"
                                        class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                    >
                                        <span x-text="`${city.type} ${city.city_name}`" class="font-medium"></span>
                                    </button>
                                </template>
                                
                                <div x-show="filteredCities.length === 0" class="text-center py-8 text-gray-500">
                                    <p>No cities found</p>
                                </div>
                            </div>

                            {{-- Loading --}}
                            <div x-show="isLoading" class="text-center py-8">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-black"></div>
                                <p class="mt-2 text-sm text-gray-500">Loading cities...</p>
                            </div>
                        </div>

                        {{-- Step 3: Shipping Costs --}}
                        <div x-show="step === 3">
                            {{-- Back Button --}}
                            <button 
                                @click="step = 2; shippingCosts = [];"
                                class="mb-4 flex items-center gap-2 text-sm text-gray-600 hover:text-black"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Change location
                            </button>

                            {{-- Selected Location --}}
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 mb-1">Shipping to:</p>
                                <p class="font-medium text-black" x-text="`${selectedCity.type} ${selectedCity.city_name}, ${selectedProvince.province}`"></p>
                            </div>

                            {{-- Loading --}}
                            <div x-show="isLoading" class="text-center py-8">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-black"></div>
                                <p class="mt-2 text-sm text-gray-500">Calculating shipping costs...</p>
                            </div>

                            {{-- Shipping Options --}}
                            <div x-show="!isLoading && shippingCosts.length > 0" class="space-y-3">
                                <template x-for="courier in shippingCosts" :key="courier.code">
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center gap-3 mb-3">
                                            <h4 class="font-bold text-black uppercase" x-text="courier.name"></h4>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <template x-for="cost in courier.costs" :key="cost.service">
                                                <div class="flex items-center justify-between py-2 border-t border-gray-100 first:border-0">
                                                    <div>
                                                        <p class="font-medium text-sm" x-text="cost.service"></p>
                                                        <p class="text-xs text-gray-500" x-text="cost.description"></p>
                                                        <p class="text-xs text-gray-500" x-text="`Estimated: ${cost.cost[0].etd} days`"></p>
                                                    </div>
                                                    <p class="font-bold text-black" x-text="`Rp ${cost.cost[0].value.toLocaleString('id-ID')}`"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- No Results --}}
                            <div x-show="!isLoading && shippingCosts.length === 0" class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="font-medium">No shipping options available</p>
                                <p class="text-sm mt-1">Please try a different location</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
