@extends('layouts.app')

@section('title', 'My Profile - JastipHype')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Account</h1>
            <p class="text-gray-600 mt-1">Manage your profile and preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" x-data="{ activeTab: '{{ request()->get('tab', 'profile') }}' }">
            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <nav class="space-y-1">
                        <button 
                            @click="activeTab = 'profile'"
                            :class="activeTab === 'profile' ? 'bg-accent-gold text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="w-full text-left px-4 py-3 rounded-lg font-medium transition-colors flex items-center"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </button>
                        <button 
                            @click="activeTab = 'password'"
                            :class="activeTab === 'password' ? 'bg-accent-gold text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="w-full text-left px-4 py-3 rounded-lg font-medium transition-colors flex items-center"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Password
                        </button>
                        <a href="{{ route('wishlist.index') }}" class="w-full text-left px-4 py-3 rounded-lg font-medium text-gray-700 hover:bg-gray-100 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Wishlist
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 rounded-lg font-medium text-red-600 hover:bg-red-50 transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-3">
                {{-- Success Message --}}
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Profile Tab --}}
                <div x-show="activeTab === 'profile'" x-transition>
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Personal Information</h2>
                        
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Name --}}
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name
                                    </label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name', $user->name) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-gold focus:border-transparent @error('name') border-red-500 @enderror"
                                    >
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address
                                    </label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email', $user->email) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-gold focus:border-transparent @error('email') border-red-500 @enderror"
                                    >
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                {{-- Phone --}}
                                <div x-data="phoneInput()" class="relative">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number
                                    </label>
                                    
                                    <div class="relative flex rounded-lg shadow-sm">
                                        <!-- Country Dropdown Button -->
                                        <button 
                                            type="button" 
                                            @click="toggleDropdown()"
                                            class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-900 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-accent-gold"
                                        >
                                            <img :src="`https://flagcdn.com/w20/${country.toLowerCase()}.png`" 
                                                 :alt="country"
                                                 class="w-5 h-auto rounded-sm shadow-sm"
                                                 loading="lazy">
                                            <span x-text="code" class="ml-2 font-medium text-sm"></span>
                                            <svg class="ml-1 h-4 w-4 text-gray-500 transition-transform duration-200" 
                                                 :class="{'rotate-180': open}"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Phone Input -->
                                        <input 
                                            type="text" 
                                            x-model="phoneNumber"
                                            class="flex-1 min-w-0 block w-full px-4 py-2 rounded-none rounded-r-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-accent-gold focus:border-transparent @error('phone') border-red-500 @enderror"
                                            placeholder="812-3456-7890"
                                            @input="formatNumber()"
                                        >
                                        
                                        <!-- Hidden input to send full value to backend -->
                                        <input type="hidden" name="phone" :value="fullPhoneNumber">
                                    </div>

                                    <!-- Dropdown Menu -->
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         @click.away="open = false"
                                         class="absolute z-50 mt-1 w-72 bg-white shadow-xl rounded-lg border border-gray-100 overflow-hidden"
                                         style="display: none;"
                                    >
                                        <!-- Search -->
                                        <div class="p-2 border-b border-gray-100 bg-gray-50">
                                            <div class="relative">
                                                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                </svg>
                                                <input 
                                                    x-ref="searchInput"
                                                    type="text" 
                                                    x-model="search"
                                                    class="w-full pl-9 pr-3 py-1.5 text-sm border border-gray-200 rounded-md focus:outline-none focus:border-accent-gold focus:ring-1 focus:ring-accent-gold"
                                                    placeholder="Search country..."
                                                >
                                            </div>
                                        </div>

                                        <!-- Country List -->
                                        <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                            <template x-for="c in filteredCountries" :key="c.code">
                                                <button 
                                                    type="button"
                                                    @click="selectCountry(c)"
                                                    class="w-full px-4 py-2.5 text-left hover:bg-gray-50 flex items-center justify-between transition-colors group"
                                                    :class="{'bg-gray-50': c.code === country}"
                                                >
                                                    <div class="flex items-center min-w-0">
                                                        <img :src="`https://flagcdn.com/w40/${c.code.toLowerCase()}.png`" 
                                                             :alt="c.name"
                                                             class="w-6 h-auto rounded shadow-sm flex-shrink-0"
                                                             loading="lazy">
                                                        <span class="ml-3 text-sm text-gray-700 truncate group-hover:text-gray-900" 
                                                              :class="{'font-medium': c.code === country}" 
                                                              x-text="c.name"></span>
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-400 font-mono group-hover:text-accent-gold" x-text="c.dial_code"></span>
                                                </button>
                                            </template>
                                            
                                            <!-- Empty State -->
                                            <div x-show="filteredCountries.length === 0" class="px-4 py-6 text-center text-sm text-gray-500">
                                                No countries found
                                            </div>
                                        </div>
                                    </div>

                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Member Since --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Member Since
                                    </label>
                                    <input 
                                        type="text" 
                                        value="{{ $user->created_at->format('F Y') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                        disabled
                                    >
                                </div>

                                {{-- Address --}}
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                        Address
                                    </label>
                                    <textarea 
                                        id="address" 
                                        name="address" 
                                        rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-gold focus:border-transparent @error('address') border-red-500 @enderror"
                                        placeholder="Your delivery address"
                                    >{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <button 
                                    type="submit"
                                    class="px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors"
                                >
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Password Tab --}}
                <div x-show="activeTab === 'password'" x-transition style="display: none;">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Change Password</h2>
                        
                        <form method="POST" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-6">
                                {{-- Current Password --}}
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Current Password
                                    </label>
                                    <input 
                                        type="password" 
                                        id="current_password" 
                                        name="current_password" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-gold focus:border-transparent @error('current_password') border-red-500 @enderror"
                                    >
                                    @error('current_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- New Password --}}
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        New Password
                                    </label>
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-gold focus:border-transparent @error('password') border-red-500 @enderror"
                                    >
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Minimum 8 characters with uppercase, lowercase and numbers</p>
                                </div>

                                {{-- Confirm Password --}}
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirm New Password
                                    </label>
                                    <input 
                                        type="password" 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-gold focus:border-transparent"
                                    >
                                </div>
                            </div>

                            <div class="mt-6">
                                <button 
                                    type="submit"
                                    class="px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors"
                                >
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function phoneInput() {
    return {
        country: 'ID', 
        code: '+62',
        phoneNumber: '',
        open: false,
        search: '',
        countries: [
            { code: 'ID', name: 'Indonesia', dial_code: '+62' },
            { code: 'SG', name: 'Singapore', dial_code: '+65' },
            { code: 'MY', name: 'Malaysia', dial_code: '+60' },
            { code: 'JP', name: 'Japan', dial_code: '+81' },
            { code: 'KR', name: 'South Korea', dial_code: '+82' },
            { code: 'CN', name: 'China', dial_code: '+86' },
            { code: 'US', name: 'United States', dial_code: '+1' },
            { code: 'AU', name: 'Australia', dial_code: '+61' },
            { code: 'GB', name: 'United Kingdom', dial_code: '+44' },
            { code: 'TH', name: 'Thailand', dial_code: '+66' },
            { code: 'VN', name: 'Vietnam', dial_code: '+84' },
            { code: 'PH', name: 'Philippines', dial_code: '+63' },
            { code: 'HK', name: 'Hong Kong', dial_code: '+852' },
            { code: 'TW', name: 'Taiwan', dial_code: '+886' },
        ],
        
        get filteredCountries() {
            if (this.search === '') return this.countries;
            return this.countries.filter(c => 
                c.name.toLowerCase().includes(this.search.toLowerCase()) || 
                c.dial_code.includes(this.search)
            );
        },
        
        get fullPhoneNumber() {
            // Remove any spaces or dashes user might have typed before sending
            return this.code + this.phoneNumber.replace(/[\s-]/g, '');
        },
        
        init() {
            let initialPhone = '{{ old('phone', $user->phone) }}';
            
            if (initialPhone) {
                // Find matching country by dial code
                // Sort by length desc to match +852 before +85
                const sortedCountries = [...this.countries].sort((a, b) => b.dial_code.length - a.dial_code.length);
                let match = sortedCountries.find(c => initialPhone.startsWith(c.dial_code));
                
                if (match) {
                    this.country = match.code;
                    this.code = match.dial_code;
                    this.phoneNumber = initialPhone.replace(match.dial_code, '').trim();
                } else {
                    this.phoneNumber = initialPhone;
                }
            }
        },
        
        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => {
                    this.$refs.searchInput.focus();
                });
            } else {
                this.search = '';
            }
        },
        
        selectCountry(c) {
            this.country = c.code;
            this.code = c.dial_code;
            this.open = false;
            this.search = '';
        },
        
        formatNumber() {
            // Optional: Basic formatting logic could go here
            // For now just allow free typing
        }
    }
}
</script>
