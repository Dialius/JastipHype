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
