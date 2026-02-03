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
