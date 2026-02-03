@extends('layouts.app')

@section('title', 'Sign In - JastipHype')

@section('content')
<style>
    body {
        background-color: #000 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    main {
        background-color: #000 !important;
    }
</style>

<div class="bg-black text-white flex" style="min-height: calc(100vh - 80px);">
    {{-- Left Side - Branding --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-black to-gray-900">
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        {{-- Content --}}
        <div class="relative z-10 flex flex-col justify-center px-16 py-12">
            {{-- Heading --}}
            <div class="space-y-6">
                <h1 class="text-5xl font-bold leading-tight">
                    Welcome to<br>
                    <span class="text-accent-gold">Premium Streetwear</span>
                </h1>
                <p class="text-xl text-gray-400 leading-relaxed max-w-md">
                    Access exclusive drops, limited editions, and curated collections from the world's top streetwear brands.
                </p>
            </div>

            {{-- Features --}}
            <div class="mt-16 space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-accent-gold rounded-full"></div>
                    <span class="text-gray-300">Exclusive member benefits</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-accent-gold rounded-full"></div>
                    <span class="text-gray-300">Early access to drops</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-accent-gold rounded-full"></div>
                    <span class="text-gray-300">VIP customer support</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side - Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gradient-to-br from-gray-900 to-black">
        <div class="w-full max-w-md">
            {{-- Form Container --}}
            <div class="space-y-8">
                {{-- Header --}}
                <div class="text-center lg:text-left">
                    <h2 class="text-3xl font-bold mb-2">Sign In</h2>
                    <p class="text-gray-400">Enter your credentials to access your account</p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email Verification Alert --}}
                    @if(session('status'))
                        <div class="p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
                            <p class="text-sm text-green-400">{{ session('status') }}</p>
                        </div>
                    @endif

                    {{-- Info Alert for New Users --}}
                    <div class="p-4 bg-accent-gold/10 border border-accent-gold/30 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-accent-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-gray-300">
                                <span class="text-accent-gold font-medium">Note:</span> After registering, please verify your email before logging in.
                            </p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            autofocus
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-accent-gold focus:ring-2 focus:ring-accent-gold/50 transition-all @error('email') border-red-500 @enderror"
                            placeholder="your@email.com"
                        >
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-accent-gold focus:ring-2 focus:ring-accent-gold/50 transition-all @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 bg-gray-800 border-gray-700 rounded text-accent-gold focus:ring-accent-gold">
                            <span class="ml-2 text-sm text-gray-400">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-accent-gold hover:text-accent-gold/80 transition-colors">
                            Forgot password?
                        </a>
                    </div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit"
                        class="w-full py-3.5 bg-accent-gold text-black font-semibold rounded-lg hover:bg-accent-gold/90 focus:outline-none focus:ring-2 focus:ring-accent-gold focus:ring-offset-2 focus:ring-offset-gray-900 transition-all transform hover:scale-[1.02]"
                    >
                        Sign In
                    </button>

                    {{-- Divider --}}
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-gray-900 text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    {{-- Social Login --}}
                    <div class="w-full">
                        <a 
                            href="{{ route('social.redirect', 'google') }}"
                            class="w-full py-3 px-4 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="text-sm font-medium">Continue with Google</span>
                        </a>
                    </div>
                </form>

                {{-- Sign Up Link --}}
                <p class="text-center text-gray-400">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-accent-gold font-semibold hover:text-accent-gold/80 transition-colors">
                        Create Account
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
