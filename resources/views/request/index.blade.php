@extends('layouts.app')

@section('title', 'Request Product - JastipHype')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    {{-- Hero Section --}}
    <div class="relative bg-black text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)"/>
            </svg>
        </div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 relative z-10">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                    <svg class="w-5 h-5 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Personal Sourcing Service</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Request a Product</h1>
                <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto">
                    Can't find what you're looking for? Let our team source it for you from around the world.
                </p>
            </div>
        </div>
        {{-- Wave Divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" class="w-full h-auto">
                <path d="M0 60V30C240 50 480 10 720 30C960 50 1200 10 1440 30V60H0Z" fill="#F9FAFB"/>
            </svg>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4 relative z-20">
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold">{{ session('success') }}</p>
                        <p class="text-sm text-green-600">We'll get back to you within 24-48 hours.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- How It Works --}}
        <div class="mb-16">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">How It Works</h2>
                <p class="text-gray-500 mt-2">Simple 3-step process to get your dream item</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Step 1 --}}
                <div class="relative group">
                    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-lg hover:border-accent-gold/30 transition-all duration-300 h-full">
                        <div class="w-14 h-14 bg-gradient-to-br from-accent-gold to-amber-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:scale-110 transition-transform">
                            1
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Submit Your Request</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Tell us what you're looking for with as much detail as possible – brand, size, color, and budget.
                        </p>
                    </div>
                    {{-- Connector Line (hidden on mobile) --}}
                    <div class="hidden md:block absolute top-1/2 -right-4 w-8 border-t-2 border-dashed border-gray-200"></div>
                </div>

                {{-- Step 2 --}}
                <div class="relative group">
                    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-lg hover:border-accent-gold/30 transition-all duration-300 h-full">
                        <div class="w-14 h-14 bg-gradient-to-br from-accent-gold to-amber-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:scale-110 transition-transform">
                            2
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">We Source It</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Our team searches trusted suppliers worldwide to find the best quality and price for you.
                        </p>
                    </div>
                    <div class="hidden md:block absolute top-1/2 -right-4 w-8 border-t-2 border-dashed border-gray-200"></div>
                </div>

                {{-- Step 3 --}}
                <div class="group">
                    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-lg hover:border-accent-gold/30 transition-all duration-300 h-full">
                        <div class="w-14 h-14 bg-gradient-to-br from-accent-gold to-amber-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:scale-110 transition-transform">
                            3
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Get Notified</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            We'll contact you with pricing, availability, and shipping options. No obligation to buy!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Form Section --}}
        <div class="lg:grid lg:grid-cols-3 lg:gap-10">
            {{-- Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    {{-- Form Header --}}
                    <div class="bg-gradient-to-r from-gray-900 to-gray-800 px-8 py-6">
                        <h2 class="text-xl font-bold text-white flex items-center gap-3">
                            <svg class="w-6 h-6 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Product Request Form
                        </h2>
                        <p class="text-gray-400 text-sm mt-1">Fill out the details and we'll handle the rest</p>
                    </div>

                    <form action="{{ route('request.store') }}" method="POST" class="p-8 space-y-8" x-data="requestForm()">
                        @csrf

                        {{-- Personal Information --}}
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-accent-gold/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Your Information</h3>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" required 
                                           value="{{ old('name', auth()->user()->name ?? '') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent focus:bg-white transition-all @error('name') border-red-500 bg-red-50 @enderror"
                                           placeholder="John Doe">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" required 
                                           value="{{ old('email', auth()->user()->email ?? '') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent focus:bg-white transition-all @error('email') border-red-500 bg-red-50 @enderror"
                                           placeholder="john@example.com">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        WhatsApp / Phone <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                        </span>
                                        <input type="tel" id="phone" name="phone" required 
                                               value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent focus:bg-white transition-all @error('phone') border-red-500 bg-red-50 @enderror"
                                               placeholder="08xxxxxxxxxx">
                                    </div>
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100"></div>

                        {{-- Product Details --}}
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-accent-gold/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Product Details</h3>
                            </div>

                            <div class="space-y-5">
                                {{-- Product Name --}}
                                <div>
                                    <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Product Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="product_name" name="product_name" required 
                                           value="{{ old('product_name') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent focus:bg-white transition-all @error('product_name') border-red-500 bg-red-50 @enderror"
                                           placeholder="e.g., Supreme Box Logo Hoodie FW21">
                                    @error('product_name')
                                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Brand & Category --}}
                                <div class="grid sm:grid-cols-2 gap-5">
                                    {{-- Brand Dropdown --}}
                                    <div x-data="customSelect({
                                        items: [
                                            { value: '', label: 'Select a brand' },
                                            @foreach($brands as $brand)
                                            { value: '{{ $brand->name }}', label: '{{ $brand->name }}' },
                                            @endforeach
                                            { value: 'other', label: 'Other (specify in notes)' }
                                        ],
                                        selected: '{{ old('brand', '') }}',
                                        name: 'brand'
                                    })" @click.away="open = false" class="relative">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                                        <input type="hidden" :name="name" :value="selected">
                                        
                                        <button type="button" @click="open = !open"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent text-left flex items-center justify-between hover:bg-white transition-all">
                                            <span x-text="getLabel()" :class="selected ? 'text-gray-900' : 'text-gray-400'"></span>
                                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>

                                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="absolute z-20 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                                            <div class="p-2 border-b border-gray-100">
                                                <input type="text" x-model="search" placeholder="Search..." @click.stop
                                                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-accent-gold focus:border-transparent">
                                            </div>
                                            <div class="max-h-48 overflow-y-auto">
                                                <template x-for="item in filteredItems" :key="item.value">
                                                    <button type="button" @click="select(item)"
                                                            class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 transition-colors"
                                                            :class="{ 'bg-accent-gold/10 text-accent-gold font-medium': selected === item.value }">
                                                        <span x-text="item.label"></span>
                                                    </button>
                                                </template>
                                                <div x-show="filteredItems.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                                                    No results found
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Category Dropdown --}}
                                    <div x-data="customSelect({
                                        items: [
                                            { value: '', label: 'Select a category' },
                                            @foreach($categories as $category)
                                            { value: '{{ $category->name }}', label: '{{ $category->name }}' },
                                            @endforeach
                                        ],
                                        selected: '{{ old('category', '') }}',
                                        name: 'category'
                                    })" @click.away="open = false" class="relative">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                        <input type="hidden" :name="name" :value="selected">
                                        
                                        <button type="button" @click="open = !open"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent text-left flex items-center justify-between hover:bg-white transition-all">
                                            <span x-text="getLabel()" :class="selected ? 'text-gray-900' : 'text-gray-400'"></span>
                                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>

                                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="absolute z-20 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                                            <div class="p-2 border-b border-gray-100">
                                                <input type="text" x-model="search" placeholder="Search..." @click.stop
                                                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-accent-gold focus:border-transparent">
                                            </div>
                                            <div class="max-h-48 overflow-y-auto">
                                                <template x-for="item in filteredItems" :key="item.value">
                                                    <button type="button" @click="select(item)"
                                                            class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 transition-colors"
                                                            :class="{ 'bg-accent-gold/10 text-accent-gold font-medium': selected === item.value }">
                                                        <span x-text="item.label"></span>
                                                    </button>
                                                </template>
                                                <div x-show="filteredItems.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                                                    No results found
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Size & Color --}}
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="size" class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                                        <input type="text" id="size" name="size" value="{{ old('size') }}"
                                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent focus:bg-white transition-all"
                                               placeholder="e.g., M, L, XL, US 9">
                                    </div>

                                    <div>
                                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                                        <input type="text" id="color" name="color" value="{{ old('color') }}"
                                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent focus:bg-white transition-all"
                                               placeholder="e.g., Black, White, Multi">
                                    </div>
                                </div>

                                {{-- Budget --}}
                                <div x-data="budgetInput('{{ old('budget', '') }}')">
                                    <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">
                                        Budget (IDR)
                                        <span class="text-gray-400 font-normal ml-1">- Optional</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                        <input type="text" x-ref="input" @input="format($event)"
                                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent focus:bg-white transition-all"
                                               placeholder="5.000.000">
                                        <input type="hidden" name="budget" :value="raw">
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1.5">Your maximum budget helps us find the best match</p>
                                </div>

                                {{-- Product URL --}}
                                <div>
                                    <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                        Product URL
                                        <span class="text-gray-400 font-normal ml-1">- Optional</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                        </span>
                                        <input type="url" id="url" name="url" value="{{ old('url') }}"
                                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent focus:bg-white transition-all @error('url') border-red-500 bg-red-50 @enderror"
                                               placeholder="https://...">
                                    </div>
                                    @error('url')
                                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Notes --}}
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                                    <textarea id="notes" name="notes" rows="4"
                                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent-gold focus:border-transparent focus:bg-white transition-all resize-none"
                                              placeholder="Any specific requirements, condition preferences, or additional details...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full bg-black text-white py-4 rounded-xl hover:bg-gray-800 transition-all font-semibold text-lg flex items-center justify-center gap-3 group shadow-lg shadow-gray-900/10">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Submit Request
                            </button>
                            <p class="text-sm text-gray-400 text-center mt-4 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                We typically respond within 24-48 hours
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 mt-10 lg:mt-0">
                <div class="sticky top-24 space-y-6">
                    {{-- FAQ Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                FAQ
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-100" x-data="{ active: null }">
                            <div class="px-6 py-4">
                                <button @click="active = active === 1 ? null : 1" class="w-full text-left flex items-start justify-between gap-2">
                                    <span class="font-medium text-sm text-gray-900">How long does sourcing take?</span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5 transition-transform" :class="{ 'rotate-180': active === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <p x-show="active === 1" x-collapse class="text-sm text-gray-500 mt-2">
                                    Typically 3-7 days, depending on product availability and location.
                                </p>
                            </div>
                            <div class="px-6 py-4">
                                <button @click="active = active === 2 ? null : 2" class="w-full text-left flex items-start justify-between gap-2">
                                    <span class="font-medium text-sm text-gray-900">Is there a request fee?</span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5 transition-transform" :class="{ 'rotate-180': active === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <p x-show="active === 2" x-collapse class="text-sm text-gray-500 mt-2">
                                    No, submitting a request is completely free. You only pay if you decide to purchase.
                                </p>
                            </div>
                            <div class="px-6 py-4">
                                <button @click="active = active === 3 ? null : 3" class="w-full text-left flex items-start justify-between gap-2">
                                    <span class="font-medium text-sm text-gray-900">Can I request multiple items?</span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5 transition-transform" :class="{ 'rotate-180': active === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <p x-show="active === 3" x-collapse class="text-sm text-gray-500 mt-2">
                                    Yes! Submit separate forms for each item or list them all in the notes section.
                                </p>
                            </div>
                            <div class="px-6 py-4">
                                <button @click="active = active === 4 ? null : 4" class="w-full text-left flex items-start justify-between gap-2">
                                    <span class="font-medium text-sm text-gray-900">What if product is unavailable?</span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5 transition-transform" :class="{ 'rotate-180': active === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <p x-show="active === 4" x-collapse class="text-sm text-gray-500 mt-2">
                                    We'll let you know and can suggest similar alternatives or notify you when it's available.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Trust Badges --}}
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 text-white">
                        <h3 class="font-semibold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Why Choose Us
                        </h3>
                        <ul class="space-y-3 text-sm">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-300">100% Authentic Products</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-300">Global Sourcing Network</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-300">Competitive Pricing</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-300">Secure Transactions</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Contact Card --}}
                    <div class="bg-accent-gold/10 rounded-2xl p-6 border border-accent-gold/20">
                        <h3 class="font-semibold text-gray-900 mb-2">Need Help?</h3>
                        <p class="text-sm text-gray-600 mb-4">Our team is ready to assist you with your request.</p>
                        <a href="{{ route('info.contact') }}" class="inline-flex items-center gap-2 text-accent-gold font-medium text-sm hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function requestForm() {
    return {}
}

function customSelect(config) {
    return {
        open: false,
        search: '',
        items: config.items,
        selected: config.selected,
        name: config.name,
        get filteredItems() {
            if (!this.search) return this.items;
            return this.items.filter(i => i.label.toLowerCase().includes(this.search.toLowerCase()));
        },
        getLabel() {
            const item = this.items.find(i => i.value === this.selected);
            return item ? item.label : this.items[0]?.label || 'Select...';
        },
        select(item) {
            this.selected = item.value;
            this.open = false;
            this.search = '';
        }
    }
}

function budgetInput(initial) {
    return {
        raw: initial,
        init() {
            if (this.raw) {
                this.$refs.input.value = this.formatDisplay(this.raw);
            }
        },
        format(event) {
            let value = event.target.value.replace(/\D/g, '');
            this.raw = value;
            event.target.value = this.formatDisplay(value);
        },
        formatDisplay(val) {
            if (!val) return '';
            return val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    }
}
</script>
@endsection
