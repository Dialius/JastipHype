{{-- 
    JastipHype Admin Sidebar - TailAdmin Style
    Uses Alpine Store for state management
--}}

<aside id="sidebar"
    class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-50 border-r border-gray-200"
    :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="$store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">
    
    <!-- Logo Section -->
    <div class="pt-8 pb-7 flex"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <!-- JastipHype Logo Image -->
            <div class="flex-shrink-0">
                <img src="{{ asset('images/logo/JastipHype_tab-logo.png') }}" 
                     alt="JastipHype Logo" 
                     class="w-10 h-10 object-contain">
            </div>
            <!-- Logo Text -->
            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                  x-transition:enter="transition ease-out duration-200"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100"
                  class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent dark:from-blue-400 dark:to-blue-600 whitespace-nowrap">
                JastipHype
            </span>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar" style="-ms-overflow-style: none; scrollbar-width: none;">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">
                @include('admin.layouts.sidebar-content')
            </div>
        </nav>
    </div>
    
    <!-- Toggle Button (Desktop only) -->
    <button @click="$store.sidebar.toggleExpanded()"
            class="hidden xl:flex absolute -right-3 top-1/2 -translate-y-1/2 z-50 h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg hover:bg-blue-700 focus:outline-none transition-all duration-200">
        <svg x-show="$store.sidebar.isExpanded" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
        <svg x-show="!$store.sidebar.isExpanded" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    </button>
</aside>

<!-- Mobile Menu Button -->
<button @click="$store.sidebar.toggleMobileOpen()"
        class="fixed top-4 left-4 z-40 xl:hidden inline-flex items-center justify-center rounded-lg p-2 text-gray-700 bg-white shadow-lg hover:bg-gray-50 focus:outline-none dark:bg-gray-800 dark:text-gray-300">
    <span class="sr-only">Toggle sidebar</span>
    <svg x-show="!$store.sidebar.isMobileOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
    </svg>
    <svg x-show="$store.sidebar.isMobileOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>
