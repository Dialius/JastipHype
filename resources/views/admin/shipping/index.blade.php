@extends('admin.layouts.app')

@section('title', 'Shipping Management')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Shipping Management</h1>
            <p class="mt-1 text-sm text-gray-500">Configure shipping options and courier services</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.shipping.analytics') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                View Analytics
            </a>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 rounded-md bg-green-50 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 rounded-md bg-red-50 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    </div>
</div>
@endif

@if(session('info'))
<div class="mb-6 rounded-md bg-blue-50 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
        </div>
    </div>
</div>
@endif

<!-- Tabs -->
<div x-data="{ activeTab: 'couriers' }" class="mb-6">
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'couriers'" :class="activeTab === 'couriers' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="flex items-center whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                Couriers
            </button>
            <button @click="activeTab = 'origin'" :class="activeTab === 'origin' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="flex items-center whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                Origin Settings
            </button>
            <button @click="activeTab = 'free-shipping'" :class="activeTab === 'free-shipping' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="flex items-center whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                Free Shipping
            </button>
            <button @click="activeTab = 'zones'" :class="activeTab === 'zones' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="flex items-center whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                </svg>
                Shipping Zones
            </button>
        </nav>
    </div>

    <!-- Tab Panels -->
    <div class="mt-6">
        <!-- Couriers Tab -->
        <div x-show="activeTab === 'couriers'" x-cloak>
            <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
                <div class="border-b border-gray-200 bg-white px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Available Couriers</h2>
                    <p class="mt-1 text-sm text-gray-500">Select which courier services to enable for your store</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.shipping.couriers.update') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($availableCouriers as $courier)
                                <div class="relative flex items-start rounded-lg border-2 p-4 {{ in_array($courier['code'], $enabledCouriers) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white' }}">
                                    <div class="flex h-6 items-center">
                                        <input type="checkbox" 
                                               name="couriers[]" 
                                               value="{{ $courier['code'] }}"
                                               id="courier_{{ $courier['code'] }}"
                                               {{ in_array($courier['code'], $enabledCouriers) ? 'checked' : '' }}
                                               class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <label for="courier_{{ $courier['code'] }}" class="font-semibold text-gray-900 cursor-pointer">
                                            {{ $courier['name'] }}
                                            @if($courier['popular'])
                                                <span class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Popular</span>
                                            @endif
                                        </label>
                                        <p class="mt-1 text-sm text-gray-500">{{ $courier['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Save Courier Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Origin Settings Tab -->
        <div x-show="activeTab === 'origin'" x-cloak>
            <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
                <div class="border-b border-gray-200 bg-white px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Origin Address</h2>
                    <p class="mt-1 text-sm text-gray-500">Set your warehouse or store location for shipping calculations</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.shipping.origin.update') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="province_id" class="block text-sm font-medium text-gray-700">Province <span class="text-red-600">*</span></label>
                                <select name="province_id" id="province_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Select Province</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province['province_id'] }}">{{ $province['province'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="origin_city_id" class="block text-sm font-medium text-gray-700">City <span class="text-red-600">*</span></label>
                                <select name="origin_city_id" id="origin_city_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city['city_id'] }}" 
                                                data-city-name="{{ $city['type'] }} {{ $city['city_name'] }}"
                                                {{ $originCity == $city['city_id'] ? 'selected' : '' }}>
                                            {{ $city['type'] }} {{ $city['city_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="origin_city_name" id="origin_city_name" value="{{ $originCityName }}">
                            </div>

                            <div>
                                <label for="origin_postal_code" class="block text-sm font-medium text-gray-700">Postal Code <span class="text-red-600">*</span></label>
                                <input type="text" 
                                       name="origin_postal_code" 
                                       id="origin_postal_code" 
                                       value="{{ old('origin_postal_code', $originPostalCode) }}"
                                       placeholder="e.g., 12345"
                                       required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>

                        @if($originCity)
                            <div class="mt-6 rounded-md bg-blue-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-blue-800">
                                            <strong>Current Origin:</strong> {{ $originCityName }}, Postal Code: {{ $originPostalCode }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Save Origin Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Free Shipping Tab -->
        <div x-show="activeTab === 'free-shipping'" x-cloak>
            <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
                <div class="border-b border-gray-200 bg-white px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Free Shipping Rules</h2>
                    <p class="mt-1 text-sm text-gray-500">Configure free shipping promotions for your customers</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.shipping.free-shipping.update') }}" method="POST" x-data="{ freeShippingEnabled: {{ $freeShippingEnabled ? 'true' : 'false' }} }">
                        @csrf

                        <div class="mb-6">
                            <div class="relative flex items-start">
                                <div class="flex h-6 items-center">
                                    <input type="checkbox" 
                                           name="free_shipping_enabled" 
                                           id="free_shipping_enabled"
                                           value="1"
                                           x-model="freeShippingEnabled"
                                           {{ $freeShippingEnabled ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                </div>
                                <div class="ml-3">
                                    <label for="free_shipping_enabled" class="font-semibold text-gray-900">Enable Free Shipping</label>
                                    <p class="text-sm text-gray-500">When enabled, customers will get free shipping if they meet the minimum order amount</p>
                                </div>
                            </div>
                        </div>

                        <div x-show="freeShippingEnabled" x-cloak class="mb-6">
                            <label for="free_shipping_min_amount" class="block text-sm font-medium text-gray-700">Minimum Order Amount (Rp)</label>
                            <input type="number" 
                                   name="free_shipping_min_amount" 
                                   id="free_shipping_min_amount" 
                                   value="{{ old('free_shipping_min_amount', $freeShippingMinAmount) }}"
                                   min="0"
                                   step="1000"
                                   placeholder="e.g., 100000"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500">Orders above this amount will qualify for free shipping</p>
                        </div>

                        @if($freeShippingEnabled)
                            <div class="mb-6 rounded-md bg-green-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">
                                            <strong>Free Shipping Active:</strong> Orders above Rp {{ number_format($freeShippingMinAmount, 0, ',', '.') }} get free shipping
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Save Free Shipping Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Shipping Zones Tab -->
        <div x-show="activeTab === 'zones'" x-cloak>
            <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
                <div class="border-b border-gray-200 bg-white px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Shipping Zones</h2>
                    <p class="mt-1 text-sm text-gray-500">Manage delivery areas and zone-specific settings</p>
                </div>
                <div class="p-6">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Shipping Zones Feature</h3>
                        <p class="mt-2 text-sm text-gray-500">This feature is coming soon. You'll be able to:</p>
                        <ul class="mt-4 space-y-2 text-sm text-gray-500">
                            <li class="flex items-center justify-center">
                                <svg class="mr-2 h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                Define custom shipping zones
                            </li>
                            <li class="flex items-center justify-center">
                                <svg class="mr-2 h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                Set zone-specific rates
                            </li>
                            <li class="flex items-center justify-center">
                                <svg class="mr-2 h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                Manage delivery restrictions
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Province change handler
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('origin_city_id');
    const cityNameInput = document.getElementById('origin_city_name');
    
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            
            if (!provinceId) {
                citySelect.innerHTML = '<option value="">Select City</option>';
                return;
            }
            
            // Show loading
            citySelect.innerHTML = '<option value="">Loading cities...</option>';
            citySelect.disabled = true;
            
            // Fetch cities
            fetch(`/admin/shipping/cities?province_id=${provinceId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                citySelect.innerHTML = '<option value="">Select City</option>';
                
                if (data.success && data.cities) {
                    data.cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.city_id;
                        option.textContent = `${city.type} ${city.city_name}`;
                        option.dataset.cityName = `${city.type} ${city.city_name}`;
                        citySelect.appendChild(option);
                    });
                }
                
                citySelect.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching cities:', error);
                citySelect.innerHTML = '<option value="">Error loading cities</option>';
                citySelect.disabled = false;
            });
        });
    }
    
    // City change handler - update hidden city name field
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.cityName) {
                cityNameInput.value = selectedOption.dataset.cityName;
            }
        });
    }
});
</script>
@endpush
