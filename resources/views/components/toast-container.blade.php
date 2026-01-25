<div x-data="toastManager()" x-init="init()" class="fixed top-4 right-4 z-[999999] pointer-events-none">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-bind="getToastClasses(toast.type)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
             x-transition:enter-end="opacity-100 transform translate-x-0 scale-1"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0 scale-1"
             x-transition:leave-end="opacity-0 transform translate-x-full scale-95"
             class="pointer-events-auto mb-3 p-4 rounded-xl shadow-2xl backdrop-blur-sm border bg-white min-w-[300px] max-w-[400px]">
            
            <div class="flex items-start gap-3">
                <!-- Icon Container -->
                <div x-html="getIcon(toast.type)" class="flex-shrink-0 mt-0.5"></div>
                
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium leading-tight text-gray-900" x-text="toast.message"></p>
                    <p x-show="toast.description" class="text-xs text-gray-600 mt-1" x-text="toast.description"></p>
                </div>
                
                <!-- Close Button -->
                <button @click="removeToast(toast.id)" 
                        class="flex-shrink-0 opacity-60 hover:opacity-100 transition-opacity text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Progress Bar -->
            <div x-show="toast.autoClose" class="mt-3 h-0.5 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-gray-400 rounded-full transition-all"
                     :style="`animation: progress ${toast.duration}ms linear forwards`"></div>
            </div>
        </div>
    </template>
</div>