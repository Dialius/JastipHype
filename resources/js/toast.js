window.toastManager = function() {
    return {
        toasts: [],
        nextId: 1,
        
        init() {
            // Listen for global toast events
            window.addEventListener('toast', (event) => {
                this.showToast(event.detail.message, event.detail.type, event.detail.options);
            });
        },
        
        showToast(message, type = 'info', options = {}) {
            const toast = {
                id: this.nextId++,
                message: message,
                type: type,
                duration: options.duration || 4000,
                autoClose: options.autoClose !== false,
                description: options.description || null,
                persistent: options.persistent || false
            };
            
            this.toasts.push(toast);
            
            // Auto-remove toast
            if (toast.autoClose && !toast.persistent) {
                setTimeout(() => {
                    this.removeToast(toast.id);
                }, toast.duration);
            }
            
            return toast.id;
        },
        
        removeToast(id) {
            const index = this.toasts.findIndex(toast => toast.id === id);
            if (index > -1) {
                this.toasts.splice(index, 1);
            }
        },
        
        getToastClasses(type) {
            const typeClasses = {
                success: 'border-green-200 text-green-900',
                error: 'border-red-200 text-red-900', 
                warning: 'border-yellow-200 text-yellow-900',
                info: 'border-blue-200 text-blue-900'
            };
            
            return {
                class: typeClasses[type] || typeClasses.info
            };
        },
        
        getIcon(type) {
            const icons = {
                success: `<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`,
                error: `<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`,
                warning: `<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>`,
                info: `<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`
            };
            
            return icons[type] || icons.info;
        }
    };
};

// Global toast helper function
window.showToast = function(message, type = 'info', options = {}) {
    const event = new CustomEvent('toast', {
        detail: { message, type, options }
    });
    window.dispatchEvent(event);
};