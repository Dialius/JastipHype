<div x-data class="fixed bottom-4 right-4 z-[999999] flex flex-col gap-3 pointer-events-none p-4 w-full max-w-md">
    <template x-for="toast in $store.toasts.items" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             class="pointer-events-auto relative rounded-2xl shadow-2xl overflow-hidden text-white backdrop-blur-sm"
             :class="$store.toasts.getDesign(toast.type).container"
             @mouseenter="toast.paused = true"
             @mouseleave="toast.paused = false"
             role="alert">
            
            <div class="relative z-10 flex p-5 items-start gap-4">
                <!-- Icon Bubble -->
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg"
                         :class="$store.toasts.getDesign(toast.type).iconBg"
                         x-html="$store.toasts.getDesign(toast.type).icon">
                    </div>
                </div>
                
                <!-- Content -->
                <div class="flex-1 w-0 pt-1">
                    <p class="font-bold text-lg leading-6 mb-1" x-text="toast.message"></p>
                    <p x-show="toast.description" x-text="toast.description" class="text-sm text-white/90 leading-relaxed font-medium"></p>
                </div>

                <!-- Close Button -->
                <div class="flex-shrink-0">
                    <button @click="$store.toasts.remove(toast.id)" 
                            class="rounded-full p-1.5 text-white/70 hover:text-white hover:bg-white/20 transition-all focus:outline-none focus:ring-2 focus:ring-white/30">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-white/10 blur-2xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 rounded-full bg-black/10 blur-xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 w-full h-1 bg-black/20">
                 <div class="h-full transition-all linear duration-75 ease-linear bg-white/50 shadow-sm"
                      :style="`width: ${toast.progress}%`"></div>
            </div>
        </div>
    </template>
</div>