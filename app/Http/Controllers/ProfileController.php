<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\PasswordChangeOtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $wishlists = $user->wishlists()->with(['product.productImages', 'product.brand'])->latest()->take(6)->get();
        
        // Get user orders with items and products
        $orders = $user->orders()
            ->with(['items.product.productImages', 'payment'])
            ->latest()
            ->paginate(10);

        return view('profile.index', [
            'user' => $user,
            'wishlists' => $wishlists,
            'orders' => $orders
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    // Request OTP for password change
    public function requestPasswordChangeOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save OTP, expiration (10 minutes), and pending password
        $user->update([
            'password_change_otp' => $otp,
            'password_change_otp_expires_at' => now()->addMinutes(10),
            'pending_password' => Hash::make($request->password)
        ]);

        // Send email
        try {
            Mail::to($user->email)->send(new PasswordChangeOtpMail($otp, $user->name));
            
            return back()->with('otp_sent', 'OTP has been sent to your email. Please check your inbox.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email. Please try again.']);
        }
    }

    // Verify OTP and change password
    public function verifyPasswordChangeOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $user = Auth::user();

        // Check if OTP exists
        if (!$user->password_change_otp) {
            return back()->withErrors(['otp' => 'No OTP found. Please request a new one.']);
        }

        // Check if OTP is expired
        if (now()->isAfter($user->password_change_otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // Verify OTP
        if ($user->password_change_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP code.']);
        }

        // Change password
        $user->update([
            'password' => $user->pending_password,
            'password_change_otp' => null,
            'password_change_otp_expires_at' => null,
            'pending_password' => null
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }
}
