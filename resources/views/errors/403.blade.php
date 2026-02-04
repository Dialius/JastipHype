<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden | JastipHype</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-yellow-50 to-orange-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Logo -->
        <div class="mb-8">
            <a href="/" class="inline-block">
                <img src="{{ asset('images/logo/JastipHype_logo.png') }}" alt="JastipHype" class="h-16 mx-auto">
            </a>
        </div>

        <!-- 403 Illustration -->
        <div class="mb-8">
            <svg class="w-64 h-64 mx-auto text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-gray-800 mb-4">403</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Access Forbidden</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            You don't have permission to access this page. Please contact an administrator if you believe this is an error.
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/" class="inline-flex items-center justify-center px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Back to Home
            </a>
            @auth
            <a href="{{ route('profile.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-800 border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Account
            </a>
            @else
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-800 border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Login
            </a>
            @endauth
        </div>
    </div>
</body>
</html>
