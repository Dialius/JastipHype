<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | JastipHype</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .pulse-slow {
            animation: pulse-slow 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Logo -->
        <div class="mb-8">
            <a href="/" class="inline-block">
                <img src="{{ asset('images/logo/JastipHype_logo.png') }}" alt="JastipHype" class="h-16 mx-auto">
            </a>
        </div>

        <!-- 500 Illustration -->
        <div class="mb-8 pulse-slow">
            <svg class="w-64 h-64 mx-auto text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-gray-800 mb-4">500</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Internal Server Error</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Something went wrong on our end. We're working to fix it. Please try again later.
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Try Again
            </button>
            <a href="/" class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-800 border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Back to Home
            </a>
        </div>

        <!-- Help Text -->
        <p class="mt-8 text-sm text-gray-500">
            Error persists? <a href="{{ route('info.contact') }}" class="text-blue-600 hover:underline">Contact Support</a>
        </p>

        @if(config('app.debug'))
        <div class="mt-8 p-4 bg-red-100 border border-red-300 rounded-lg text-left">
            <p class="text-sm font-semibold text-red-800 mb-2">Debug Information:</p>
            <pre class="text-xs text-red-700 overflow-auto">{{ $exception->getMessage() ?? 'No exception message' }}</pre>
        </div>
        @endif
    </div>
</body>
</html>
