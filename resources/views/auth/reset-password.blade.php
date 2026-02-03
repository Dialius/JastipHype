@extends('layouts.app')

@section('title', 'Reset Password')

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
                <h1 class="text-3xl font-bold mb-2">Reset Password</h1>
                <p class="text-gray-600">Enter the OTP code sent to your email and create a new password.</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                
                <input type="hidden" name="email" value="{{ request('email') ?? old('email') }}">
                
                <!-- Email Display -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                        {{ request('email') ?? old('email') }}
                    </div>
                </div>

                <!-- OTP Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">OTP Code</label>
                    <div class="flex gap-2 justify-center">
                        <input 
                            type="text" 
                            maxlength="1" 
                            class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                            data-index="0"
                        >
                        <input 
                            type="text" 
                            maxlength="1" 
                            class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                            data-index="1"
                        >
                        <input 
                            type="text" 
                            maxlength="1" 
                            class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                            data-index="2"
                        >
                        <input 
                            type="text" 
                            maxlength="1" 
                            class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                            data-index="3"
                        >
                        <input 
                            type="text" 
                            maxlength="1" 
                            class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                            data-index="4"
                        >
                        <input 
                            type="text" 
                            maxlength="1" 
                            class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black @error('otp') border-red-500 @enderror"
                            data-index="5"
                        >
                    </div>
                    <!-- Hidden input to store complete OTP -->
                    <input type="hidden" name="otp" id="otp-value">
                    @error('otp')
                        <p class="text-red-600 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        minlength="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent @error('password') border-red-500 @enderror"
                        placeholder="Minimum 8 characters"
                    >
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required
                        minlength="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                        placeholder="Re-enter your password"
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors"
                >
                    Reset Password
                </button>

                <!-- Resend OTP -->
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">Didn't receive the code?</p>
                    <button 
                        type="button"
                        id="resendBtn"
                        class="text-sm text-black font-semibold hover:underline"
                        onclick="resendOtp()"
                    >
                        Resend OTP
                    </button>
                </div>

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
                <h2 class="text-4xl font-bold mb-4">Almost There!</h2>
                <p class="text-xl text-gray-300 leading-relaxed max-w-md">
                    Create a strong password to secure your account and continue shopping.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// OTP Input Handler
const otpInputs = document.querySelectorAll('.otp-input');
const otpValue = document.getElementById('otp-value');

otpInputs.forEach((input, index) => {
    // Handle input
    input.addEventListener('input', function(e) {
        // Only allow numbers
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Update hidden input with complete OTP
        updateOtpValue();
        
        // Auto-focus next input
        if (this.value && index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
        }
    });
    
    // Handle backspace
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && !this.value && index > 0) {
            otpInputs[index - 1].focus();
        }
    });
    
    // Handle paste
    input.addEventListener('paste', function(e) {
        e.preventDefault();
        const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
        
        // Distribute pasted digits across inputs
        for (let i = 0; i < pastedData.length && index + i < otpInputs.length; i++) {
            otpInputs[index + i].value = pastedData[i];
        }
        
        updateOtpValue();
        
        // Focus last filled input or next empty
        const lastIndex = Math.min(index + pastedData.length, otpInputs.length - 1);
        otpInputs[lastIndex].focus();
    });
});

// Update hidden input value
function updateOtpValue() {
    const otp = Array.from(otpInputs).map(input => input.value).join('');
    otpValue.value = otp;
}

// Auto-focus first input on page load
otpInputs[0].focus();

async function resendOtp() {
    const resendBtn = document.getElementById('resendBtn');
    const email = document.querySelector('input[name="email"]').value;
    
    resendBtn.disabled = true;
    resendBtn.textContent = 'Sending...';
    
    try {
        const response = await fetch('{{ route("password.resend") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ email: email })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('OTP has been resent to your email!');
            // Clear OTP inputs
            otpInputs.forEach(input => input.value = '');
            otpValue.value = '';
            otpInputs[0].focus();
        } else {
            alert(data.message || 'Failed to resend OTP. Please try again.');
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    } finally {
        resendBtn.disabled = false;
        resendBtn.textContent = 'Resend OTP';
    }
}
</script>
@endsection
