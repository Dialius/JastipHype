<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // Show forgot password form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Send OTP to email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email not found in our system.'
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save OTP and expiration (60 minutes)
        $user->update([
            'password_reset_otp' => $otp,
            'password_reset_otp_expires_at' => now()->addMinutes(60)
        ]);

        // Send email
        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp, $user->name));
            
            return response()->json([
                'success' => true,
                'message' => 'OTP has been sent to your email.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please try again.'
            ], 500);
        }
    }

    // Show reset password form with OTP
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'email' => $request->email
        ]);
    }

    // Verify OTP and reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if OTP exists
        if (!$user->password_reset_otp) {
            return back()->withErrors(['otp' => 'No OTP found. Please request a new one.']);
        }

        // Check if OTP is expired
        if (now()->isAfter($user->password_reset_otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // Verify OTP
        if ($user->password_reset_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP code.']);
        }

        // Reset password
        $user->update([
            'password' => Hash::make($request->password),
            'password_reset_otp' => null,
            'password_reset_otp_expires_at' => null
        ]);

        // Auto login
        auth()->login($user);

        return redirect()->route('home')->with('success', 'Password has been reset successfully!');
    }

    // Resend OTP
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        return $this->sendOtp($request);
    }
}
