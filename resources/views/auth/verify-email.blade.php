@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex items-center justify-center p-8 bg-gray-50">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Icon -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold mb-2">Verify Your Email</h1>
                <p class="text-gray-600">
                    We've sent a verification link to<br>
                    <strong>{{ auth()->user()->email }}</strong>
                </p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-center">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Info Message -->
            @if(session('info'))
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-center">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Instructions -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-700 mb-3">
                    <strong>📧 Check your inbox</strong><br>
                    Click the verification link in the email to activate your account.
                </p>
                <p class="text-sm text-gray-700">
                    <strong>📁 Check spam folder</strong><br>
                    If you don't see the email, check your spam or junk folder.
                </p>
            </div>

            <!-- Resend Form -->
            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors"
                >
                    Resend Verification Email
                </button>
            </form>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors"
                >
                    Logout
                </button>
            </form>
        </div>

        <!-- Help Text -->
        <p class="text-center text-sm text-gray-600 mt-6">
            Need help? Contact us at <a href="mailto:support@jastiphype.com" class="text-black font-semibold hover:underline">support@jastiphype.com</a>
        </p>
    </div>
</div>
@endsection
