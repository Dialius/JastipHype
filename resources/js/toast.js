import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.store('toasts', {
        items: [],
        add(message, type = 'info', options = {}) {
            const id = Date.now();
            // Support third argument as string (description) or object
            let description = '';
            let duration = 5000; // Increased default duration

            if (typeof options === 'string') {
                description = options;
            } else if (typeof options === 'object') {
                description = options.description || '';
                duration = options.duration || 5000;
            }

            const toast = {
                id,
                message,
                description,
                type,
                visible: false, // Start invisible for enter animation
                progress: 100,
                paused: false
            };

            this.items.push(toast);

            // Trigger enter animation
            requestAnimationFrame(() => {
                const t = this.items.find(i => i.id === id);
                if (t) t.visible = true;
            });

            const step = 50; // Smoother updates
            const decrement = 100 / (duration / step);

            const timer = setInterval(() => {
                const t = this.items.find(i => i.id === id);
                if (!t) {
                    clearInterval(timer);
                    return;
                }
                if (!t.paused) {
                    t.progress -= decrement;
                    if (t.progress <= 0) {
                        this.remove(id);
                        clearInterval(timer);
                    }
                }
            }, step);
        },
        remove(id) {
            const toast = this.items.find(t => t.id === id);
            if (toast) {
                toast.visible = false; // Trigger leave animation
                setTimeout(() => {
                    this.items = this.items.filter(t => t.id !== id);
                }, 400); // Wait for transition
            }
        },
        getDesign(type) {
            const designs = {
                success: {
                    container: 'bg-gradient-to-r from-emerald-500 to-green-500',
                    iconBg: 'bg-white/20',
                    icon: `<svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>`
                },
                error: {
                    container: 'bg-gradient-to-r from-rose-500 to-red-500',
                    iconBg: 'bg-white/20',
                    icon: `<svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>`
                },
                warning: {
                    container: 'bg-gradient-to-r from-amber-500 to-orange-500',
                    iconBg: 'bg-white/20',
                    icon: `<svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`
                },
                info: {
                    container: 'bg-gradient-to-r from-blue-500 to-indigo-500',
                    iconBg: 'bg-white/20',
                    icon: `<svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`
                }
            };
            return designs[type] || designs.info;
        }
    });
});

window.$notify = (message, type = 'info', options = {}) => {
    if (window.Alpine) {
        window.Alpine.store('toasts').add(message, type, options);
    } else {
        // Fallback if Alpine isn't ready yet or not found
        console.warn('Alpine not ready for toast notification');
        document.addEventListener('alpine:init', () => {
            window.Alpine.store('toasts').add(message, type, options);
        }, { once: true });
    }
};

// Backwards compatibility shim
window.toastManager = function () { return {}; };
window.showToast = window.$notify;