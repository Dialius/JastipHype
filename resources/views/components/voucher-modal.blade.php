{{-- Voucher Modal Component --}}
<style>
    body.modal-open {
        overflow: hidden !important;
        position: fixed;
        width: 100%;
        height: 100%;
    }
    
    .voucher-modal-body::-webkit-scrollbar {
        width: 6px;
    }
    .voucher-modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .voucher-modal-body::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    .voucher-modal-body::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

<template x-teleport="body">
    <div 
        x-show="showVoucherModal"
        x-cloak
        style="display: none;"
        @keydown.escape.window="showVoucherModal = false"
        x-init="$watch('showVoucherModal', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
                document.body.classList.add('modal-open');
            } else {
                document.body.style.overflow = '';
                document.body.classList.remove('modal-open');
            }
        })"
        class="fixed inset-0 z-[99999] overflow-hidden"
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
            class="fixed inset-0 bg-black/50 transition-opacity"
            @click="showVoucherModal = false"
        ></div>

        {{-- Modal Container --}}
        <div class="fixed inset-0 flex items-end lg:items-center justify-center p-0 lg:p-4 pointer-events-none">
            <div 
                x-show="showVoucherModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full lg:translate-y-4 lg:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 lg:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 lg:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 lg:translate-y-0 lg:scale-95"
                class="relative bg-white w-full lg:max-w-lg shadow-2xl transform transition-all rounded-t-3xl lg:rounded-3xl flex flex-col pointer-events-auto"
                style="max-height: 85vh;"
                @click.outside="showVoucherModal = false"
            >
                {{-- Header (Fixed) --}}
                <div class="px-6 py-5 border-b border-gray-200 bg-white flex-shrink-0 rounded-t-3xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-black" id="voucher-modal-title">Available Vouchers</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Copy code to use at checkout</p>
                        </div>
                        <button 
                            @click="showVoucherModal = false"
                            class="p-2 hover:bg-gray-100 rounded-full transition-colors"
                            aria-label="Close vouchers"
                        >
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Body (Scrollable) --}}
                <div class="flex-1 overflow-y-auto px-6 py-6 voucher-modal-body" style="scrollbar-width: thin;">
                    @if(isset($availableVouchers) && $availableVouchers->count() > 0)
                        <div class="space-y-3">
                            @foreach($availableVouchers as $voucher)
                                <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="text-lg font-bold text-gray-900">{{ $voucher->code }}</h4>
                                                @if($voucher->type === 'percentage')
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">{{ $voucher->value }}% Off</span>
                                                @else
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">Rp {{ number_format($voucher->value, 0, ',', '.') }} Off</span>
                                                @endif
                                            </div>
                                            
                                            @if($voucher->type === 'percentage')
                                                <p class="text-sm text-gray-600 mb-1">{{ $voucher->value }}% discount</p>
                                            @else
                                                <p class="text-sm text-gray-600 mb-1">Rp {{ number_format($voucher->value, 0, ',', '.') }} discount</p>
                                            @endif
                                            
                                            @if($voucher->min_order_amount)
                                                <p class="text-xs text-gray-500">Min. spend Rp {{ number_format($voucher->min_order_amount, 0, ',', '.') }}</p>
                                            @else
                                                <p class="text-xs text-gray-500">No minimum spend</p>
                                            @endif
                                            
                                            @if($voucher->end_date)
                                                <p class="text-xs text-gray-500 mt-1">Valid until {{ $voucher->end_date->format('d M Y') }}</p>
                                            @endif
                                        </div>
                                        <button 
                                            @click="
                                                navigator.clipboard.writeText('{{ $voucher->code }}');
                                                $notify('Voucher {{ $voucher->code }} copied!', 'success', {
                                                    description: 'Use this code at checkout',
                                                    duration: 3000
                                                });
                                                showVoucherModal = false;
                                            "
                                            class="px-4 py-2 bg-gray-100 text-gray-900 font-bold text-sm rounded-lg hover:bg-gray-200 transition-colors"
                                        >
                                            COPY
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <p class="font-medium text-gray-700">No vouchers available</p>
                            <p class="text-sm mt-1 text-gray-500">Check back later for new discount codes</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</template>
