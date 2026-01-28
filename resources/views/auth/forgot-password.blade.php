@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Side - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <img src="{{ asset('images/logo/JastipHype_logo.png') }}" alt="JastipHype" class="h-12 mx-auto">
                </a>
            </div>

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-2">Forgot Password?</h1>
                <p class="text-gray-600">Enter your email and we'll send you an OTP code to reset your password.</p>
            </div>

            <!-- Form -->
            <form id="forgotPasswordForm" class="space-y-6">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                        placeholder="your@email.com"
                    >
                    <p class="text-red-600 text-sm mt-1 hidden" id="emailError"></p>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                >
                    Send OTP Code
                </button>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-black">
                        ← Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side - Image -->
    <div class="hidden lg:block lg:w-1/2 bg-black relative overflow-hidden">
        <div class="absolute inset-0 flex items-center justify-center p-12">
            <div class="text-center text-white">
                <h2 class="text-4xl font-bold mb-4">Reset Your Password</h2>
                <p class="text-xl text-gray-300 leading-relaxed max-w-md">
                    Secure your account and get back to shopping exclusive streetwear collections.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    
    // Reset error
    emailError.classList.add('hidden');
    emailError.textContent = '';
    
    // Disable button
    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';
    
    try {
        const response = await fetch('{{ route("password.email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                email: emailInput.value
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Redirect to reset password page with email
            window.location.href = '{{ route("password.reset") }}?email=' + encodeURIComponent(emailInput.value);
        } else {
            emailError.textContent = data.message || 'Failed to send OTP. Please try again.';
            emailError.classList.remove('hidden');
        }
    } catch (error) {
        emailError.textContent = 'An error occurred. Please try again.';
        emailError.classList.remove('hidden');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send OTP Code';
    }
});
</script>
@endsection
