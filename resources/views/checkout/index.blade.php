@extends('layouts.checkout')

@section('content')
<div class="py-10" x-data="checkoutPage()">
    <!-- Visual Stepper -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <nav aria-label="Progress">
            <ol role="list" class="flex items-center justify-center">
                <li class="relative pr-8 sm:pr-20">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-black"></div>
                    </div>
                    <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-black hover:bg-gray-900">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                        <span class="absolute -bottom-8 w-max text-sm font-medium text-black">Cart</span>
                    </a>
                </li>
                <li class="relative pr-8 sm:pr-20">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-black"></div>
                    </div>
                    <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-black hover:bg-gray-900">
                        <span class="text-white font-bold text-sm">2</span>
                        <span class="absolute -bottom-8 w-max text-sm font-medium text-black">Details</span>
                    </a>
                </li>
                <li class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-gray-200"></div>
                    </div>
                    <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-white border-2 border-gray-300 hover:border-gray-400">
                        <span class="text-gray-500 font-bold text-sm">3</span>
                        <span class="absolute -bottom-8 w-max text-sm font-medium text-gray-500">Payment</span>
                    </a>
                </li>
            </ol>
        </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
            <!-- Left Column: Shipping & Contact Info -->
            <section class="lg:col-span-7">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <!-- Contact Information -->
                    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-black text-white text-xs">1</span>
                            Contact Information
                        </h2>
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm h-11 placeholder-gray-400" 
                                       placeholder="you@example.com" required>
                            </div>
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm h-11 placeholder-gray-400" 
                                       placeholder="Your Full Name" required>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm h-11 placeholder-gray-400" 
                                       placeholder="0812-3456-7890" required>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-black text-white text-xs">2</span>
                            Shipping Address
                        </h2>
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Full Address</label>
                                <textarea name="address" id="address" rows="3" 
                                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm placeholder-gray-400" 
                                          required placeholder="Street name, house number, block, unit no.">{{ old('address', $user->address ?? '') }}</textarea>
                            </div>
                            
                            <!-- RajaOngkir Province/City -->
                            <div class="relative">
                                <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                <div class="relative">
                                    <select id="province" name="province_id" x-model="selectedProvince" @change="fetchCities()"
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm h-11 bg-white disabled:bg-gray-100 disabled:text-gray-400">
                                        <option value="">Select Province</option>
                                        <template x-for="province in provinces" :key="province.province_id">
                                            <option :value="province.province_id" x-text="province.province"></option>
                                        </template>
                                    </select>
                                    <div x-show="loadingProvinces" class="absolute right-3 top-3">
                                        <svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="relative">
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <select id="city" name="city_id" x-model="selectedCity" :disabled="!selectedProvince"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm h-11 bg-white disabled:bg-gray-100 disabled:text-gray-400">
                                    <option value="">Select City</option>
                                    <template x-for="city in cities" :key="city.city_id">
                                        <option :value="city.city_id" x-text="city.type + ' ' + city.city_name"></option>
                                    </template>
                                </select>
                                <div x-show="loadingCities" class="absolute right-3 top-3">
                                    <svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                <input type="text" name="postal_code" id="postal_code" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm h-11 placeholder-gray-400"
                                       placeholder="12345">
                            </div>
                        </div>
                    </div>

<!-- Payment Method -->
                    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-black text-white text-xs">3</span>
                            Payment Method
                        </h2>
                        
                        <!-- New Payment Methods Component -->
                        <x-payment-methods selected="bank_transfer" />
                    </div>
                </form>
            </section>

            <!-- Right Column: Order Summary -->
            <section class="lg:col-span-5 mt-8 lg:mt-0">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>

                    <ul role="list" class="divide-y divide-gray-200 max-h-[300px] overflow-auto pr-2 custom-scrollbar">
                        @foreach($cartItems as $item)
                            <li class="flex py-4">
                                <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                    @if($item->product->productImages->first())
                                        <img src="{{ asset('storage/' . $item->product->productImages->first()->image_path) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="h-full w-full object-cover object-center">
                                    @endif
                                </div>

                                <div class="ml-4 flex flex-1 flex-col justify-center">
                                    <div>
                                        <div class="flex justify-between text-sm font-medium text-gray-900">
                                            <h3>{{ $item->product->name }}</h3>
                                            <p class="ml-4">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Size: {{ $item->size }} | Qty: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <dl class="space-y-4 border-t border-gray-200 pt-6 mt-6">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Shipping</dt>
                            <dd class="text-sm font-medium text-gray-900" id="shipping-cost-display">Rp {{ number_format($shippingCost, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-bold text-gray-900">Total</dt>
                            <dd class="text-base font-bold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <button type="submit" form="checkout-form" class="w-full bg-black border border-transparent rounded-full shadow-lg py-4 px-4 text-base font-bold text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-all hover:translate-y-[-1px]">
                            PLACE ORDER
                        </button>
                    </div>

                    <!-- Trust Signals -->
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <div class="flex items-center justify-center gap-2 mb-3 text-sm text-gray-500">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span class="font-medium">Secure Checkout</span>
                        </div>
                        <div class="grid grid-cols-4 gap-2 opacity-80 hover:opacity-100 transition-opacity">
                             <!-- Bank Logos -->
                             <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" alt="BCA" class="h-6 object-contain grayscale hover:grayscale-0 transition-all">
                             <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/1200px-Bank_Mandiri_logo_2016.svg.png" alt="Mandiri" class="h-6 object-contain grayscale hover:grayscale-0 transition-all">
                             <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png" alt="BNI" class="h-6 object-contain grayscale hover:grayscale-0 transition-all">
                             <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/1280px-BANK_BRI_logo.svg.png" alt="BRI" class="h-6 object-contain grayscale hover:grayscale-0 transition-all">
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
function checkoutPage() {
    return {
        provinces: [],
        cities: [],
        selectedProvince: '',
        selectedCity: '',
        loadingProvinces: false,
        loadingCities: false,
        
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
        
        fetchCities() {
            if (!this.selectedProvince) {
                this.cities = [];
                this.selectedCity = '';
                return;
            }
            
            this.loadingCities = true;
            this.cities = []; // Clear current cities
            
            fetch(`{{ url('api/location/cities') }}/${this.selectedProvince}`)
                .then(res => res.json())
                .then(data => {
                     if(data.rajaongkir && data.rajaongkir.results) {
                        this.cities = data.rajaongkir.results;
                     }
                })
                .catch(err => console.error('Error fetching cities:', err))
                .finally(() => this.loadingCities = false);
        }
    }
}
</script>
@endsection
