<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>
    
    <!-- Fonts - Outfit like TailAdmin -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite Assets (includes Tailwind CSS, Alpine.js, Flowbite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- ApexCharts for analytics -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <!-- Chart.js fallback for legacy charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Toastify for notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <!-- Alpine Store for Sidebar/Theme -->
    <script>
        document.addEventListener('alpine:init', () => {
            // Theme Store
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                }
            });

            // Sidebar Store
            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                    localStorage.setItem('sidebarExpanded', this.isExpanded);
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    
    @stack('styles')
</head>
<body class="h-full font-outfit bg-gray-50 dark:bg-gray-900"
      x-data
      x-init="
          $store.sidebar.isExpanded = localStorage.getItem('sidebarExpanded') !== 'false' && window.innerWidth >= 1280;
          const checkMobile = () => {
              if (window.innerWidth < 1280) {
                  $store.sidebar.setMobileOpen(false);
                  $store.sidebar.isExpanded = false;
              } else {
                  $store.sidebar.isMobileOpen = false;
              }
          };
          window.addEventListener('resize', checkMobile);
      ">
    
    <div class="min-h-screen xl:flex">
        <!-- Backdrop for mobile -->
        <div x-show="$store.sidebar.isMobileOpen" 
             @click="$store.sidebar.setMobileOpen(false)"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900/50 xl:hidden"
             style="display: none;"></div>

        <!-- Sidebar -->
        @include('admin.layouts.sidebar')
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col transition-all duration-300 ease-in-out"
             :class="{
                 'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                 'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                 'ml-0': $store.sidebar.isMobileOpen
             }">
            <!-- Top Navbar -->
            @include('admin.layouts.navbar')
            
            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-5 lg:p-6">
                <div class="mx-auto max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- Confirm Modal Component -->
    <div id="confirm-modal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0" id="confirm-modal-content">
            <div class="p-6">
                <!-- Icon -->
                <div id="confirm-modal-icon" class="flex h-14 w-14 mx-auto items-center justify-center rounded-full mb-4">
                    <!-- Icon will be inserted here -->
                </div>
                
                <!-- Title -->
                <h3 id="confirm-modal-title" class="text-lg font-semibold text-center text-gray-800 dark:text-white mb-2"></h3>
                
                <!-- Message -->
                <p id="confirm-modal-message" class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6"></p>
                
                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="button" onclick="closeConfirmModal()" class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 text-gray-700 font-medium text-sm hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="button" id="confirm-modal-btn" class="flex-1 px-4 py-2.5 rounded-lg font-medium text-sm text-white transition">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global JavaScript Functions -->
    <script>
        let confirmCallback = null;

        function showConfirmModal(options) {
            const modal = document.getElementById('confirm-modal');
            const content = document.getElementById('confirm-modal-content');
            const iconEl = document.getElementById('confirm-modal-icon');
            const titleEl = document.getElementById('confirm-modal-title');
            const messageEl = document.getElementById('confirm-modal-message');
            const confirmBtn = document.getElementById('confirm-modal-btn');

            // Set content
            titleEl.textContent = options.title || 'Confirm Action';
            messageEl.textContent = options.message || 'Are you sure you want to proceed?';
            confirmBtn.textContent = options.confirmText || 'Confirm';
            confirmCallback = options.onConfirm;

            // Set styles based on type
            const type = options.type || 'warning';
            const typeStyles = {
                danger: {
                    iconBg: 'bg-red-100 dark:bg-red-500/15',
                    iconColor: 'text-red-600 dark:text-red-400',
                    btnBg: 'bg-red-600 hover:bg-red-700',
                    icon: '<svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>'
                },
                warning: {
                    iconBg: 'bg-yellow-100 dark:bg-yellow-500/15',
                    iconColor: 'text-yellow-600 dark:text-yellow-400',
                    btnBg: 'bg-yellow-600 hover:bg-yellow-700',
                    icon: '<svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>'
                },
                success: {
                    iconBg: 'bg-green-100 dark:bg-green-500/15',
                    iconColor: 'text-green-600 dark:text-green-400',
                    btnBg: 'bg-green-600 hover:bg-green-700',
                    icon: '<svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                },
                info: {
                    iconBg: 'bg-blue-100 dark:bg-blue-500/15',
                    iconColor: 'text-blue-600 dark:text-blue-400',
                    btnBg: 'bg-blue-600 hover:bg-blue-700',
                    icon: '<svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>'
                }
            };

            const style = typeStyles[type] || typeStyles.warning;
            iconEl.className = `flex h-14 w-14 mx-auto items-center justify-center rounded-full mb-4 ${style.iconBg} ${style.iconColor}`;
            iconEl.innerHTML = style.icon;
            confirmBtn.className = `flex-1 px-4 py-2.5 rounded-lg font-medium text-sm text-white transition ${style.btnBg}`;

            // Show modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirm-modal');
            const content = document.getElementById('confirm-modal-content');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                confirmCallback = null;
            }, 200);
        }

        document.getElementById('confirm-modal-btn').addEventListener('click', function() {
            if (confirmCallback) {
                confirmCallback();
            }
            closeConfirmModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConfirmModal();
            }
        });

        // Global toast function
        function showToast(message, type = 'success') {
            const colors = {
                success: '#10B981',
                error: '#EF4444',
                warning: '#F59E0B',
                info: '#3B82F6'
            };

            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: colors[type] || colors.success,
                stopOnFocus: true,
                style: {
                    borderRadius: "8px",
                    fontWeight: "500",
                    boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1)"
                }
            }).showToast();
        }
    </script>

    @stack('scripts')

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#10B981",
                stopOnFocus: true,
                style: {
                    borderRadius: "8px",
                    fontWeight: "500",
                    boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1)"
                }
            }).showToast();
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Toastify({
                text: "{{ session('error') }}",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#EF4444",
                stopOnFocus: true,
                style: {
                    borderRadius: "8px",
                    fontWeight: "500",
                    boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1)"
                }
            }).showToast();
        });
    </script>
    @endif
    
    @if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Toastify({
                text: "{{ session('warning') }}",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#F59E0B",
                stopOnFocus: true,
                style: {
                    borderRadius: "8px",
                    fontWeight: "500",
                    boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1)"
                }
            }).showToast();
        });
    </script>
    @endif
</body>
</html>
