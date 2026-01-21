@extends('layouts.app')

@section('title', 'Create Account - JastipHype')

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
                    Join the<br>
                    <span class="text-accent-gold">Elite Community</span>
                </h1>
                <p class="text-xl text-gray-400 leading-relaxed max-w-md">
                    Get instant access to exclusive streetwear collections, limited drops, and members-only perks.
                </p>
            </div>

            {{-- Features --}}
            <div class="mt-16 space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-accent-gold/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-accent-gold" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-gray-300">Priority access to new releases</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-accent-gold/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-accent-gold" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-gray-300">Exclusive member discounts</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-accent-gold/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-accent-gold" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-gray-300">Personalized recommendations</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side - Register Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gradient-to-br from-gray-900 to-black">
        <div class="w-full max-w-md">
            {{-- Form Container --}}
            <div class="space-y-8">
                {{-- Header --}}
                <div class="text-center lg:text-left">
                    <h2 class="text-3xl font-bold mb-2">Create Account</h2>
                    <p class="text-gray-400">Join thousands of streetwear enthusiasts</p>
                </div>

                {{-- Form --}}
                <form method="POST" action="#" class="space-y-5" x-data="{ 
                    password: '', 
                    strength: 0,
                    showPassword: false,
                    checkStrength() {
                        this.strength = 0;
                        if (this.password.length >= 8) this.strength++;
                        if (/[a-z]/.test(this.password) && /[A-Z]/.test(this.password)) this.strength++;
                        if (/[0-9]/.test(this.password)) this.strength++;
                    }
                }">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Full Name
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-accent-gold focus:ring-2 focus:ring-accent-gold/50 transition-all"
                            placeholder="John Doe"
                        >
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
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-accent-gold focus:ring-2 focus:ring-accent-gold/50 transition-all"
                            placeholder="john@example.com"
                        >
                    </div>

                    {{-- Password with Strength Indicator --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'"
                                id="password" 
                                name="password" 
                                required
                                x-model="password"
                                @input="checkStrength()"
                                class="w-full px-4 py-3 pr-12 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-accent-gold focus:ring-2 focus:ring-accent-gold/50 transition-all"
                                placeholder="••••••••"
                            >
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        
                        {{-- Password Strength Bar --}}
                        <div class="mt-2 flex gap-2" x-show="password.length > 0" x-transition>
                            <div class="h-1.5 flex-1 rounded-full transition-all" :class="strength >= 1 ? (strength === 1 ? 'bg-red-500' : strength === 2 ? 'bg-yellow-500' : 'bg-green-500') : 'bg-gray-700'"></div>
                            <div class="h-1.5 flex-1 rounded-full transition-all" :class="strength >= 2 ? (strength === 2 ? 'bg-yellow-500' : 'bg-green-500') : 'bg-gray-700'"></div>
                            <div class="h-1.5 flex-1 rounded-full transition-all" :class="strength >= 3 ? 'bg-green-500' : 'bg-gray-700'"></div>
                        </div>
                        <p class="mt-1.5 text-xs" x-show="password.length > 0" :class="strength === 1 ? 'text-red-400' : strength === 2 ? 'text-yellow-400' : strength === 3 ? 'text-green-400' : 'text-gray-500'" x-text="strength === 1 ? 'Weak password' : strength === 2 ? 'Medium strength' : strength === 3 ? 'Strong password' : ''"></p>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                            Confirm Password
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-accent-gold focus:ring-2 focus:ring-accent-gold/50 transition-all"
                            placeholder="••••••••"
                        >
                    </div>

                    {{-- Terms --}}
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="terms" required class="mt-1 w-4 h-4 bg-gray-800 border-gray-700 rounded text-accent-gold focus:ring-accent-gold">
                        <span class="ml-3 text-sm text-gray-400">
                            I agree to the <a href="#" class="text-accent-gold hover:text-accent-gold/80">Terms of Service</a> and <a href="#" class="text-accent-gold hover:text-accent-gold/80">Privacy Policy</a>
                        </span>
                    </label>

                    {{-- Submit Button --}}
                    <button 
                        type="submit"
                        class="w-full py-3.5 bg-accent-gold text-black font-semibold rounded-lg hover:bg-accent-gold/90 focus:outline-none focus:ring-2 focus:ring-accent-gold focus:ring-offset-2 focus:ring-offset-gray-900 transition-all transform hover:scale-[1.02]"
                    >
                        Create Account
                    </button>

                    {{-- Divider --}}
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-gray-900 text-gray-500">Or sign up with</span>
                        </div>
                    </div>

                    {{-- Social Signup --}}
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

                {{-- Login Link --}}
                <p class="text-center text-gray-400">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-accent-gold font-semibold hover:text-accent-gold/80 transition-colors">
                        Sign In
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
