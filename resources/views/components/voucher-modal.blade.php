{{-- Voucher Modal Component --}}
<style>
    .hide-scrollbar {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
</style>

<template x-teleport="body">
    <div 
        x-show="showVoucherModal"
        x-cloak
        style="display: none;"
        @keydown.escape.window="showVoucherModal = false"
        class="fixed inset-0 z-[99999] overflow-y-auto hide-scrollbar"
        aria-labelledby="voucher-modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showVoucherModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/80 transition-opacity"
            @click="showVoucherModal = false"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-screen items-center justify-center p-4">
            <div 
                x-show="showVoucherModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white w-full max-w-lg mx-auto overflow-hidden shadow-2xl transform transition-all rounded-lg"
                @click.outside="showVoucherModal = false"
            >
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-200 bg-white flex items-center justify-between">
                    <h3 class="text-xl font-bold text-black" id="voucher-modal-title">
                        Available Vouchers
                    </h3>
                    <button 
                        @click="showVoucherModal = false"
                        class="p-2 -mr-2 hover:bg-gray-100 rounded-full transition-colors"
                        aria-label="Close vouchers"
                    >
                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6 max-h-[70vh] overflow-y-auto hide-scrollbar space-y-4">
                    {{-- Mock Vouchers --}}
                    @foreach([
                        ['code' => 'WELCOME10', 'discount' => '10%', 'desc' => 'New user discount', 'min' => 'No minimum spend'],
                        ['code' => 'FREESHIP', 'discount' => 'Free Shipping', 'desc' => 'Free shipping on orders over Rp 2.000.000', 'min' => 'Min. spend Rp 2.000.000'],
                        ['code' => 'HYPE2026', 'discount' => 'Rp 50.000', 'desc' => 'Special promo for selected items', 'min' => 'Min. spend Rp 1.000.000']
                    ] as $voucher)
                        <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center group hover:border-black transition-colors">
                            <div>
                                <div class="font-bold text-black text-lg">{{ $voucher['code'] }}</div>
                                <div class="text-sm font-medium text-gray-900">{{ $voucher['discount'] }} Off</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $voucher['desc'] }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $voucher['min'] }}</div>
                            </div>
                            <button 
                                @click="$notify('Voucher {{ $voucher['code'] }} copied!', 'success'); showVoucherModal = false;"
                                class="px-4 py-2 bg-gray-100 text-black text-xs font-bold uppercase rounded hover:bg-black hover:text-white transition-colors"
                            >
                                Copy
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</template>
