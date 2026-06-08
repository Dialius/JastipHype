<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'JastipHype') }} - @yield('title', 'Limited Edition Fashion')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo/JastipHype_tab-logo.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900">
    <!-- Announcement/Testing Banner -->
    <div class="bg-amber-50 border-b border-amber-200 text-amber-800 text-center py-2.5 px-4 text-xs font-medium flex items-center justify-center gap-2">
        <svg class="w-4 h-4 text-amber-600 flex-shrink-0 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span>Platform ini masih dalam tahap pengetesan &amp; pengembangan. Semua data transaksi adalah simulasi.</span>
    </div>

    @include('layouts.header')
    
    <main>
        @yield('content')
    </main>
    
@include('layouts.footer')
    
    <!-- Toast Container -->
    @include('components.toast-container')
    
    <!-- Back to Top Button -->
    @include('components.back-to-top')
    
    <!-- Live Chat Widget -->
    @include('components.live-chat-widget')
    
    <!-- Cookie Consent Banner -->
    @include('components.cookie-consent')
    <!-- Global Image Error Handler -->
    <script>
        document.addEventListener('error', function(e) {
            if (e.target.tagName.toLowerCase() === 'img') {
                e.target.onerror = null;
                if (!e.target.getAttribute('data-has-error')) {
                    e.target.setAttribute('data-has-error', 'true');
                    e.target.src = "{{ asset('images/placeholder-product.svg') }}";
                    e.target.classList.add('bg-gray-50', 'object-contain');
                }
            }
        }, true);
    </script>
</body>
</html>
