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
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">OTP Code</label>
                    <input 
                        type="text" 
                        id="otp" 
                        name="otp" 
                        required
                        maxlength="6"
                        pattern="[0-9]{6}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent text-center text-2xl tracking-widest font-bold @error('otp') border-red-500 @enderror"
                        placeholder="000000"
                    >
                    @error('otp')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
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

// Auto-focus OTP input and format
document.getElementById('otp').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endsection
