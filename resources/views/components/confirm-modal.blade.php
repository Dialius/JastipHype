<!-- Modern Confirmation Modal -->
<div id="confirm-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeConfirmModal()"></div>
        
        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
        
        <!-- Modal panel -->
        <div class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Icon -->
                    <div id="modal-icon" class="mx-auto flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-12 sm:w-12">
                        <!-- Icon will be inserted here -->
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">
                            <!-- Title will be inserted here -->
                        </h3>
                        <div class="mt-3">
                            <p class="text-sm text-gray-600" id="modal-message">
                                <!-- Message will be inserted here -->
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                <button type="button" id="modal-confirm-btn" 
                        class="inline-flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-sm sm:w-auto transition-all duration-200">
                    Confirm
                </button>
                <button type="button" onclick="closeConfirmModal()" 
                        class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all duration-200">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let confirmCallback = null;
    
    window.showConfirmModal = function(options = {}) {
        const {
            title = 'Confirm Action',
            message = 'Are you sure you want to proceed?',
            type = 'warning', // warning, danger, info, success
            confirmText = 'Confirm',
            cancelText = 'Cancel',
            onConfirm = () => {}
        } = options;
        
        const modal = document.getElementById('confirm-modal');
        const iconContainer = document.getElementById('modal-icon');
        const titleEl = document.getElementById('modal-title');
        const messageEl = document.getElementById('modal-message');
        const confirmBtn = document.getElementById('modal-confirm-btn');
        
        // Set content
        titleEl.textContent = title;
        messageEl.textContent = message;
        confirmBtn.textContent = confirmText;
        
        // Set icon and colors based on type
        const configs = {
            warning: {
                icon: `<svg class="h-7 w-7 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>`,
                iconBg: 'bg-yellow-100',
                btnClass: 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500'
            },
            danger: {
                icon: `<svg class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>`,
                iconBg: 'bg-red-100',
                btnClass: 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
            },
            info: {
                icon: `<svg class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>`,
                iconBg: 'bg-blue-100',
                btnClass: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
            },
            success: {
                icon: `<svg class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>`,
                iconBg: 'bg-green-100',
                btnClass: 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
            }
        };
        
        const config = configs[type];
        iconContainer.className = `mx-auto flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full ${config.iconBg} sm:mx-0 sm:h-12 sm:w-12`;
        iconContainer.innerHTML = config.icon;
        confirmBtn.className = `inline-flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-sm sm:w-auto transition-all duration-200 ${config.btnClass} focus:outline-none focus:ring-2 focus:ring-offset-2`;
        
        // Set callback
        confirmCallback = onConfirm;
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };
    
    window.closeConfirmModal = function() {
        const modal = document.getElementById('confirm-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        confirmCallback = null;
    };
    
    // Confirm button click
    document.getElementById('modal-confirm-btn').addEventListener('click', function() {
        if (confirmCallback) {
            confirmCallback();
        }
        closeConfirmModal();
    });
    
    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });
</script>
@endpush
