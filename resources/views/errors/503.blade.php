<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Service Unavailable | JastipHype</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spin-slow {
            animation: spin-slow 3s linear infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Logo -->
        <div class="mb-8">
            <a href="/" class="inline-block">
                <img src="{{ asset('images/logo/JastipHype_logo.png') }}" alt="JastipHype" class="h-16 mx-auto">
            </a>
        </div>

        <!-- 503 Illustration -->
        <div class="mb-8">
            <svg class="w-64 h-64 mx-auto text-gray-300 spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-gray-800 mb-4">503</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Service Unavailable</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            We're currently performing maintenance. We'll be back shortly. Thank you for your patience!
        </p>

        <!-- Status -->
        <div class="mb-8 inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
            <svg class="w-4 h-4 mr-2 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                <circle cx="10" cy="10" r="8"/>
            </svg>
            Maintenance in Progress
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Try Again
            </button>
        </div>

        <!-- Help Text -->
        <p class="mt-8 text-sm text-gray-500">
            Follow us on social media for updates
        </p>
    </div>
</body>
</html>
