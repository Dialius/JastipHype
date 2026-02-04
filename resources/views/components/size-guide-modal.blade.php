{{-- Size Guide Modal Component --}}
{{-- Custom CSS to hide scrollbar but keep scroll functionality --}}
<style>
    .hide-scrollbar {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none; /* Chrome/Safari/Opera */
    }
</style>

{{-- Teleport modal to body to escape stacking context --}}
<template x-teleport="body">
    <div 
        x-show="showSizeGuide"
        x-cloak
        @keydown.escape.window="showSizeGuide = false"
        x-init="$watch('showSizeGuide', value => { 
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        })"
        class="fixed inset-0 z-[99999] overflow-y-auto hide-scrollbar"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showSizeGuide"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/80 transition-opacity"
            @click="showSizeGuide = false; document.body.style.overflow = 'auto'"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-screen items-center justify-center p-4">
            <div 
                x-show="showSizeGuide"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white w-full max-w-lg mx-auto overflow-hidden shadow-2xl transform transition-all rounded-lg"
                @click.outside="showSizeGuide = false"
            >
                {{-- Header with Back Arrow (Faith Style) --}}
                <div class="px-6 py-5 border-b border-gray-200 bg-white">
                    <div class="flex items-center gap-4">
                        {{-- Back Arrow Button --}}
                        <button 
                            @click="showSizeGuide = false; document.body.style.overflow = 'auto'"
                            class="p-2 -ml-2 hover:bg-gray-100 rounded-full transition-colors"
                            aria-label="Close size guide"
                        >
                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </button>
                        
                        {{-- Title --}}
                        <div>
                            <h3 class="text-xl font-bold text-black" id="modal-title">
                                Size Chart Recommendation
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Based on Height and Weight</p>
                        </div>
                    </div>
                </div>
                {{-- Modal Body - Size Guide Content --}}
                <div class="p-6 max-h-[70vh] overflow-y-auto hide-scrollbar">
                    @if(isset($product) && $product->size_guide_image)
                        <img 
                            src="{{ \App\Helpers\ImageHelper::getImageUrl($product->size_chart) }}" 
                            alt="Size Chart" 
                            class="w-full h-auto rounded-lg"
                            loading="lazy"
                        >
                    @else
                        {{-- Default Size Guide Table --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs font-bold uppercase bg-black text-white">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">Size</th>
                                        <th scope="col" class="px-6 py-4">Chest (cm)</th>
                                        <th scope="col" class="px-6 py-4">Length (cm)</th>
                                        <th scope="col" class="px-6 py-4">Shoulder (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-bold">S</td>
                                        <td class="px-6 py-4">50-52</td>
                                        <td class="px-6 py-4">68-70</td>
                                        <td class="px-6 py-4">44-46</td>
                                    </tr>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-bold">M</td>
                                        <td class="px-6 py-4">54-56</td>
                                        <td class="px-6 py-4">70-72</td>
                                        <td class="px-6 py-4">46-48</td>
                                    </tr>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-bold">L</td>
                                        <td class="px-6 py-4">58-60</td>
                                        <td class="px-6 py-4">72-74</td>
                                        <td class="px-6 py-4">48-50</td>
                                    </tr>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-bold">XL</td>
                                        <td class="px-6 py-4">62-64</td>
                                        <td class="px-6 py-4">74-76</td>
                                        <td class="px-6 py-4">50-52</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-bold">XXL</td>
                                        <td class="px-6 py-4">66-68</td>
                                        <td class="px-6 py-4">76-78</td>
                                        <td class="px-6 py-4">52-54</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Measurement Guide --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="font-bold text-sm uppercase mb-3">How to Measure:</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start">
                                    <span class="font-bold mr-2">•</span>
                                    <span><strong>Chest:</strong> Measure around the fullest part of your chest</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold mr-2">•</span>
                                    <span><strong>Length:</strong> Measure from the highest point of shoulder to hem</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold mr-2">•</span>
                                    <span><strong>Shoulder:</strong> Measure from shoulder seam to shoulder seam</span>
                                </li>
                            </ul>
                        </div>

                        {{-- Fit Note --}}
                        <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                            <p class="text-xs text-gray-700">
                                <strong>Note:</strong> All measurements are approximate and may vary by 1-2cm due to the production process.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</template>
