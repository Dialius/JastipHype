{{-- Delivery & Returns Modal Component --}}
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
        x-show="showDeliveryModal"
        x-cloak
        style="display: none;"
        @keydown.escape.window="showDeliveryModal = false"
        class="fixed inset-0 z-[99999] overflow-y-auto hide-scrollbar"
        aria-labelledby="delivery-modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showDeliveryModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/80 transition-opacity"
            @click="showDeliveryModal = false"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-screen items-center justify-center p-4">
            <div 
                x-show="showDeliveryModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white w-full max-w-lg mx-auto overflow-hidden shadow-2xl transform transition-all rounded-lg"
                @click.outside="showDeliveryModal = false"
            >
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-200 bg-white flex items-center justify-between">
                    <h3 class="text-xl font-bold text-black" id="delivery-modal-title">
                        Delivery & Returns
                    </h3>
                    <button 
                        @click="showDeliveryModal = false"
                        class="p-2 -mr-2 hover:bg-gray-100 rounded-full transition-colors"
                        aria-label="Close delivery info"
                    >
                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6 max-h-[70vh] overflow-y-auto hide-scrollbar space-y-6 text-sm text-gray-600">
                    {{-- Delivery Section --}}
                    <div>
                        <h4 class="font-bold text-black uppercase mb-2">Delivery</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Estimated delivery: 3-5 business days for Jabodetabek.</li>
                            <li>Estimated delivery: 5-7 business days for other regions.</li>
                            <li><strong class="text-black">Free shipping</strong> on orders over Rp 2.000.000.</li>
                            <li>All orders are shipped via JNE/Sicepat/GoSend.</li>
                        </ul>
                    </div>

                    {{-- Local Delivery --}}
                    <div>
                        <h4 class="font-bold text-black uppercase mb-2">Instant Delivery</h4>
                        <p class="mb-2">Available for Jakarta area only. Orders placed before 14:00 will be delivered the same day.</p>
                    </div>

                    {{-- Returns Section --}}
                    <div>
                        <h4 class="font-bold text-black uppercase mb-2">Returns & Exchanges</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>30-day return policy for unworn items with tags attached.</li>
                            <li>Free exchanges for size issues (subject to availability).</li>
                            <li>Refunds will be processed to your original payment method within 5-7 business days.</li>
                        </ul>
                    </div>
                    
                    {{-- Contact --}}
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs">Need help? <a href="#" class="underline text-black">Contact our support</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
