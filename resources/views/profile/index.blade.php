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
                            :class="activeTab === 'profile' ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="w-full text-left px-4 py-3 rounded-lg font-medium transition-colors flex items-center"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </button>
                        <button 
                            @click="activeTab = 'orders'"
                            :class="activeTab === 'orders' ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="w-full text-left px-4 py-3 rounded-lg font-medium transition-colors flex items-center"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Order History
                        </button>
                        <button 
                            @click="activeTab = 'password'"
                            :class="activeTab === 'password' ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100'"
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

                {{-- Order History Tab --}}
                <div x-show="activeTab === 'orders'" x-transition style="display: none;">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Order History</h2>
                        
                        @if(isset($orders) && $orders->count() > 0)
                            <div class="space-y-4">
                                @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    {{-- Order Header --}}
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 pb-4 border-b border-gray-100">
                                        <div>
                                            <h3 class="font-bold text-gray-900">Order #{{ $order->order_number }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <div class="mt-3 md:mt-0 flex items-center gap-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                @if($order->status === 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            <a href="{{ route('payment.show', $order->order_number) }}" 
                                               class="text-sm font-medium text-black hover:text-gray-700 underline">
                                                View Details
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Order Items --}}
                                    <div class="space-y-3 mb-4">
                                        @foreach($order->items->take(2) as $item)
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $item->product->productImages->first()?->image_url ?? '/images/placeholder.png' }}" 
                                                 alt="{{ $item->product->name }}"
                                                 class="w-16 h-16 object-cover rounded-lg">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-medium text-gray-900 truncate">{{ $item->product->name }}</h4>
                                                <p class="text-sm text-gray-500">Size: {{ $item->size }} | Qty: {{ $item->quantity }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                        
                                        @if($order->items->count() > 2)
                                        <p class="text-sm text-gray-500 pl-20">
                                            +{{ $order->items->count() - 2 }} more item(s)
                                        </p>
                                        @endif
                                    </div>

                                    {{-- Order Total --}}
                                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                        <span class="text-sm text-gray-600">Total Payment</span>
                                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Pagination --}}
                            @if($orders->hasPages())
                            <div class="mt-6">
                                {{ $orders->links() }}
                            </div>
                            @endif
                        @else
                            {{-- Empty State --}}
                            <div class="text-center py-12">
                                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Orders Yet</h3>
                                <p class="text-gray-600 mb-6">You haven't placed any orders yet.</p>
                                <a href="{{ route('products.index') }}" 
                                   class="inline-block px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                                    Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Password Tab --}}
                <div x-show="activeTab === 'password'" x-transition style="display: none;" x-data="{ showOtpModal: {{ session('otp_sent') ? 'true' : 'false' }} }">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Change Password</h2>
                        
                        @if(session('otp_sent'))
                            <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg">
                                {{ session('otp_sent') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('profile.password.request-otp') }}">
                            @csrf
                            
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
                                        required
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
                                        required
                                        minlength="8"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-gold focus:border-transparent @error('password') border-red-500 @enderror"
                                    >
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
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
                                        required
                                        minlength="8"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-gold focus:border-transparent"
                                    >
                                </div>
                            </div>

                            <div class="mt-6">
                                <button 
                                    type="submit"
                                    class="px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors"
                                >
                                    Send OTP to Email
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- OTP Verification Modal --}}
                    <div 
                        x-show="showOtpModal" 
                        x-transition
                        class="fixed inset-0 z-50 overflow-y-auto"
                        style="display: none;"
                    >
                        <div class="flex items-center justify-center min-h-screen px-4">
                            {{-- Backdrop --}}
                            <div 
                                class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                                @click="showOtpModal = false"
                            ></div>

                            {{-- Modal Content --}}
                            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                                {{-- Close Button --}}
                                <button 
                                    @click="showOtpModal = false"
                                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>

                                {{-- Header --}}
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Verify OTP</h3>
                                    <p class="text-sm text-gray-600">Enter the 6-digit code sent to your email</p>
                                </div>

                                {{-- OTP Form --}}
                                <form method="POST" action="{{ route('profile.password.verify-otp') }}">
                                    @csrf
                                    
                                    {{-- OTP Inputs --}}
                                    <div class="mb-6">
                                        <div class="flex gap-2 justify-center">
                                            <input 
                                                type="text" 
                                                maxlength="1" 
                                                class="otp-input-profile w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                                                data-index="0"
                                            >
                                            <input 
                                                type="text" 
                                                maxlength="1" 
                                                class="otp-input-profile w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                                                data-index="1"
                                            >
                                            <input 
                                                type="text" 
                                                maxlength="1" 
                                                class="otp-input-profile w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                                                data-index="2"
                                            >
                                            <input 
                                                type="text" 
                                                maxlength="1" 
                                                class="otp-input-profile w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                                                data-index="3"
                                            >
                                            <input 
                                                type="text" 
                                                maxlength="1" 
                                                class="otp-input-profile w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                                                data-index="4"
                                            >
                                            <input 
                                                type="text" 
                                                maxlength="1" 
                                                class="otp-input-profile w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                                                data-index="5"
                                            >
                                        </div>
                                        <input type="hidden" name="otp" id="otp-value-profile">
                                        @error('otp')
                                            <p class="text-red-600 text-sm mt-2 text-center">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Submit Button --}}
                                    <button 
                                        type="submit"
                                        class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors"
                                    >
                                        Verify & Change Password
                                    </button>
                                </form>

                                {{-- Info --}}
                                <p class="text-xs text-gray-500 text-center mt-4">
                                    OTP expires in 10 minutes
                                </p>
                            </div>
                        </div>
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

<script>
// OTP Input Handler for Profile Password Change
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('.otp-input-profile');
    const otpValue = document.getElementById('otp-value-profile');
    
    if (otpInputs.length > 0) {
        otpInputs.forEach((input, index) => {
            // Handle input
            input.addEventListener('input', function(e) {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Update hidden input with complete OTP
                updateOtpValueProfile();
                
                // Auto-focus next input
                if (this.value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });
            
            // Handle backspace
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
            
            // Handle paste
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                
                // Distribute pasted digits across inputs
                for (let i = 0; i < pastedData.length && index + i < otpInputs.length; i++) {
                    otpInputs[index + i].value = pastedData[i];
                }
                
                updateOtpValueProfile();
                
                // Focus last filled input or next empty
                const lastIndex = Math.min(index + pastedData.length, otpInputs.length - 1);
                otpInputs[lastIndex].focus();
            });
        });
        
        // Update hidden input value
        function updateOtpValueProfile() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            otpValue.value = otp;
        }
        
        // Auto-focus first input when modal opens
        setTimeout(() => {
            if (otpInputs[0]) {
                otpInputs[0].focus();
            }
        }, 300);
    }
});
</script>
