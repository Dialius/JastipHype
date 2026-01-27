{{-- Address Selector Modal Component --}}
<div x-data="addressSelector()">
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label ?? 'Complete Address' }} *</label>
    
    {{-- Hidden inputs for form --}}
    <input type="hidden" name="province_id" :value="selectedProvinceId">
    <input type="hidden" name="city_id" :value="selectedCityId">
    <input type="hidden" name="subdistrict_id" :value="selectedSubdistrictId">
    <input type="hidden" name="postal_code" :value="selectedPostalCode">
    
    {{-- Trigger Button --}}
    <button type="button"
            @click="openModal()"
            :disabled="loadingProvinces"
            class="block w-full px-4 py-3 text-left text-base bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-0 focus:border-black transition-colors disabled:bg-gray-100 disabled:cursor-not-allowed"
            :class="selectedCityId ? 'text-gray-900' : 'text-gray-400'">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <template x-if="selectedCityId">
                    <div>
                        <div class="font-medium text-gray-900" x-text="selectedCityName"></div>
                        <div class="text-xs text-gray-500 mt-0.5">
                            <span x-text="selectedProvinceName"></span>
                            <template x-if="selectedSubdistrictName">
                                <span> • <span x-text="selectedSubdistrictName"></span></span>
                            </template>
                            <span x-show="selectedPostalCode"> • </span>
                            <span x-text="selectedPostalCode"></span>
                        </div>
                    </div>
                </template>
                <template x-if="!selectedCityId">
                    <span>Select Province, City, Subdistrict & Postal Code</span>
                </template>
            </div>
            <svg x-show="!loadingProvinces" class="h-5 w-5 text-gray-400 flex-shrink-0 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            <div x-show="loadingProvinces" class="animate-spin text-black flex-shrink-0 ml-2">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
        </div>
    </button>

    {{-- Modal --}}
    <template x-teleport="body">
        <div x-show="isOpen"
             x-cloak
             @keydown.escape.window="closeModal()"
             x-init="$watch('isOpen', value => { 
                 if (value) {
                     document.body.style.overflow = 'hidden';
                     $nextTick(() => $refs.searchInput?.focus());
                 } else {
                     document.body.style.overflow = 'auto';
                 }
             })"
             class="fixed inset-0 z-[99999]"
             style="display: none;">
            
            {{-- Backdrop --}}
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 @click="closeModal()"
                 class="fixed inset-0 bg-black/50"></div>

            {{-- Modal Container --}}
            <div class="flex min-h-screen items-end lg:items-center justify-center p-0 lg:p-4">
                <div x-show="isOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-full lg:translate-y-4 lg:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 lg:scale-100"
                     @click.outside="closeModal()"
                     class="relative bg-white w-full lg:max-w-lg shadow-2xl transform transition-all rounded-t-3xl lg:rounded-3xl flex flex-col"
                     style="max-height: 90vh;">
                    
                    {{-- Header --}}
                    <div class="px-6 py-5 border-b border-gray-200 bg-white flex-shrink-0 rounded-t-3xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-black">
                                    <span x-show="step === 'province'">Select Province</span>
                                    <span x-show="step === 'city'">Select City</span>
                                    <span x-show="step === 'subdistrict'">Select Subdistrict</span>
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    <span x-show="step === 'province'">Choose your province first</span>
                                    <span x-show="step === 'city'" x-text="'Province: ' + selectedProvinceName"></span>
                                    <span x-show="step === 'subdistrict'" x-text="'City: ' + selectedCityName"></span>
                                </p>
                            </div>
                            <button @click="closeModal()"
                                    class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Search Box --}}
                    <div class="px-6 py-4 border-b border-gray-100 flex-shrink-0">
                        <input type="text"
                               x-ref="searchInput"
                               x-model="searchQuery"
                               :placeholder="step === 'province' ? 'Search province...' : step === 'city' ? 'Search city...' : 'Search subdistrict...'"
                               class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:outline-none focus:border-black transition-colors">
                    </div>

                    {{-- Items List --}}
                    <div class="flex-1" style="overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                        <style>
                            .hide-scrollbar::-webkit-scrollbar { display: none; }
                        </style>
                        <div class="hide-scrollbar" style="overflow-y: auto; scrollbar-width: none;">
                            {{-- Province List --}}
                            <div x-show="step === 'province'" class="p-4 space-y-2">
                                <template x-for="province in filteredProvinces" :key="province.province_id">
                                    <button type="button"
                                            @click="selectProvince(province)"
                                            class="w-full text-left px-4 py-3 border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-colors"
                                            :class="selectedProvinceId == province.province_id ? 'bg-gray-50 border-black font-medium' : ''">
                                        <span x-text="province.province" class="text-base"></span>
                                    </button>
                                </template>
                                
                                <div x-show="filteredProvinces.length === 0" 
                                     class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <p class="font-medium">No results found</p>
                                </div>
                            </div>

                            {{-- City List --}}
                            <div x-show="step === 'city'" class="p-4 space-y-2">
                                <div x-show="loadingCities" class="text-center py-8">
                                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-black mb-3"></div>
                                    <p class="text-sm text-gray-500">Loading cities...</p>
                                </div>

                                <template x-if="!loadingCities">
                                    <div>
                                        <template x-for="city in filteredCities" :key="city.city_id">
                                            <button type="button"
                                                    @click="selectCity(city)"
                                                    class="w-full text-left px-4 py-3 border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-colors"
                                                    :class="selectedCityId == city.city_id ? 'bg-gray-50 border-black font-medium' : ''">
                                                <div class="text-base font-medium" x-text="city.type + ' ' + city.city_name"></div>
                                            </button>
                                        </template>
                                        
                                        <div x-show="filteredCities.length === 0 && !loadingCities" 
                                             class="text-center py-8 text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                            <p class="font-medium">No results found</p>
                                        </div>
                                    </div>
                                </template>

                                {{-- Back Button --}}
                                <button type="button"
                                        @click="backToProvinces()"
                                        class="w-full mt-4 px-4 py-3 border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-gray-700 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Back to Provinces
                                </button>
                            </div>

                            {{-- Subdistrict List --}}
                            <div x-show="step === 'subdistrict'" class="p-4 space-y-2">
                                <div x-show="loadingSubdistricts" class="text-center py-8">
                                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-black mb-3"></div>
                                    <p class="text-sm text-gray-500">Loading subdistricts...</p>
                                </div>

                                <template x-if="!loadingSubdistricts">
                                    <div>
                                        <template x-for="subdistrict in filteredSubdistricts" :key="subdistrict.subdistrict_id">
                                            <button type="button"
                                                    @click="selectSubdistrict(subdistrict)"
                                                    class="w-full text-left px-4 py-3 border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-colors"
                                                    :class="selectedSubdistrictId == subdistrict.subdistrict_id ? 'bg-gray-50 border-black font-medium' : ''">
                                                <div class="text-base font-medium" x-text="subdistrict.subdistrict_name"></div>
                                                <div class="text-xs text-gray-500 mt-1" x-text="'Postal Code: ' + subdistrict.postal_code"></div>
                                            </button>
                                        </template>
                                        
                                        <div x-show="filteredSubdistricts.length === 0 && !loadingSubdistricts" 
                                             class="text-center py-8 text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                            <p class="font-medium">No results found</p>
                                        </div>
                                    </div>
                                </template>

                                {{-- Back Button --}}
                                <button type="button"
                                        @click="backToCities()"
                                        class="w-full mt-4 px-4 py-3 border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-gray-700 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Back to Cities
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function addressSelector() {
    return {
        isOpen: false,
        step: 'province', // 'province', 'city', or 'subdistrict'
        searchQuery: '',
        selectedProvinceId: '',
        selectedProvinceName: '',
        selectedCityId: '',
        selectedCityName: '',
        selectedSubdistrictId: '',
        selectedSubdistrictName: '',
        selectedPostalCode: '',
        loadingProvinces: false,
        loadingCities: false,
        loadingSubdistricts: false,
        provinces: [],
        cities: [],
        subdistricts: [],
        
        get filteredProvinces() {
            if (!this.searchQuery) return this.provinces;
            
            const query = this.searchQuery.toLowerCase();
            return this.provinces.filter(province => {
                return province.province.toLowerCase().includes(query);
            });
        },
        
        get filteredCities() {
            if (!this.searchQuery) return this.cities;
            
            const query = this.searchQuery.toLowerCase();
            return this.cities.filter(city => {
                const fullName = `${city.type} ${city.city_name}`.toLowerCase();
                return fullName.includes(query);
            });
        },

        get filteredSubdistricts() {
            if (!this.searchQuery) return this.subdistricts;
            
            const query = this.searchQuery.toLowerCase();
            return this.subdistricts.filter(subdistrict => {
                return subdistrict.subdistrict_name.toLowerCase().includes(query);
            });
        },
        
        init() {
            this.fetchProvinces();
        },
        
        fetchProvinces() {
            this.loadingProvinces = true;
            fetch('{{ route('location.provinces') }}')
                .then(res => res.json())
                .then(data => {
                    if(data.rajaongkir && data.rajaongkir.results) {
                        this.provinces = data.rajaongkir.results;
                    }
                })
                .catch(err => console.error('Error fetching provinces:', err))
                .finally(() => this.loadingProvinces = false);
        },
        
        fetchCities(provinceId) {
            this.loadingCities = true;
            this.cities = [];
            
            fetch(`{{ url('api/location/cities') }}/${provinceId}`)
                .then(res => res.json())
                .then(data => {
                    if(data.rajaongkir && data.rajaongkir.results) {
                        this.cities = data.rajaongkir.results;
                    }
                })
                .catch(err => console.error('Error fetching cities:', err))
                .finally(() => this.loadingCities = false);
        },

        fetchSubdistricts(cityId) {
            this.loadingSubdistricts = true;
            this.subdistricts = [];
            
            fetch(`{{ url('api/location/subdistricts') }}/${cityId}`)
                .then(res => res.json())
                .then(data => {
                    if(data.rajaongkir && data.rajaongkir.results) {
                        this.subdistricts = data.rajaongkir.results;
                    }
                })
                .catch(err => console.error('Error fetching subdistricts:', err))
                .finally(() => this.loadingSubdistricts = false);
        },
        
        openModal() {
            if (!this.loadingProvinces) {
                this.isOpen = true;
                this.step = 'province';
                this.searchQuery = '';
            }
        },
        
        closeModal() {
            this.isOpen = false;
            this.searchQuery = '';
            this.step = 'province';
        },
        
        selectProvince(province) {
            this.selectedProvinceId = province.province_id;
            this.selectedProvinceName = province.province;
            this.searchQuery = '';
            this.step = 'city';
            this.fetchCities(province.province_id);
        },
        
        selectCity(city) {
            this.selectedCityId = city.city_id;
            this.selectedCityName = `${city.type} ${city.city_name}`;
            this.searchQuery = '';
            this.step = 'subdistrict';
            this.fetchSubdistricts(city.city_id);
        },

        selectSubdistrict(subdistrict) {
            this.selectedSubdistrictId = subdistrict.subdistrict_id;
            this.selectedSubdistrictName = subdistrict.subdistrict_name;
            this.selectedPostalCode = subdistrict.postal_code;
            this.closeModal();
            
            // Notify parent to calculate shipping
            window.dispatchEvent(new CustomEvent('address-selected', {
                detail: { 
                    provinceId: this.selectedProvinceId,
                    cityId: this.selectedCityId,
                    subdistrictId: this.selectedSubdistrictId,
                    postalCode: this.selectedPostalCode
                }
            }));
        },
        
        backToProvinces() {
            this.step = 'province';
            this.searchQuery = '';
        },
        
        backToCities() {
            this.step = 'city';
            this.searchQuery = '';
        }
    }
}
</script>
