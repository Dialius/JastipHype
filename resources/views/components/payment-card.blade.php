@props(['method', 'name', 'logo', 'description', 'available' => true, 'selected' => false])

<div class="relative group">
    <label class="block cursor-pointer">
        <input type="radio" 
               name="payment_method" 
               value="{{ $method }}" 
               {{ $selected ? 'checked' : '' }}
               {{ !$available ? 'disabled' : '' }}
               class="sr-only peer"
               @change="$root.selectedPayment = '{{ $method }}'">
        
        <div class="relative p-4 rounded-xl border-2 transition-all duration-200
                    {{ $available 
                        ? 'border-gray-200 hover:border-black hover:shadow-md peer-checked:border-black peer-checked:bg-gray-50' 
                        : 'border-gray-100 bg-gray-50 opacity-60 cursor-not-allowed' }}">
            
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <!-- Logo Container with Hover Effect -->
                        <div class="relative w-12 h-12 bg-white rounded-lg flex items-center justify-center p-2 border border-gray-200 
                                group-hover:border-black group-hover:shadow-sm transition-all duration-200
                                {{ !$available ? 'grayscale' : '' }}">
                        @if($logo)
                            <img src="{{ $logo }}" 
                                 alt="{{ $name }}" 
                                 class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center text-gray-500 text-xs font-medium">
                                {{ Str::limit($name, 2) }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Method Info -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 group-hover:text-black transition-colors">
                            {{ $name }}
                        </h3>
                        @if($description)
                            <p class="text-sm text-gray-500 mt-0.5">{{ $description }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Status Indicator -->
                <div class="flex items-center gap-2">
                    @if(!$available)
                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full font-medium">
                            Coming Soon
                        </span>
                    @endif
                    
                    <!-- Radio Indicator -->
                    <div class="relative">
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 
                                    group-hover:border-black transition-colors
                                    peer-checked:border-black peer-checked:bg-black">
                            <div class="absolute inset-1 rounded-full bg-white 
                                        opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Info (Expandable) -->
            @if($method === 'qris' || $method === 'bank_transfer')
                <div class="mt-3 pt-3 border-t border-gray-200 opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="text-xs text-gray-600">
                        @if($method === 'qris')
                            <p>• Instant payment via QR code</p>
                            <p>• Support all major e-wallets</p>
                        @elseif($method === 'bank_transfer')
                            <p>• Transfer to BCA, Mandiri, BNI, BRI</p>
                            <p>• Confirmation within 1-2 hours</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </label>
</div>