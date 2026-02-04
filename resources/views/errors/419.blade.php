<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired | JastipHype</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Logo -->
        <div class="mb-8">
            <a href="/" class="inline-block">
                <img src="{{ asset('images/logo/JastipHype_logo.png') }}" alt="JastipHype" class="h-16 mx-auto">
            </a>
        </div>

        <!-- 419 Illustration -->
        <div class="mb-8">
            <svg class="w-64 h-64 mx-auto text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-gray-800 mb-4">419</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Page Expired</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Your session has expired. This usually happens when the page has been inactive for too long. Please refresh and try again.
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh Page
            </button>
            <a href="/" class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-800 border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Back to Home
            </a>
        </div>
    </div>
</body>
</html>
