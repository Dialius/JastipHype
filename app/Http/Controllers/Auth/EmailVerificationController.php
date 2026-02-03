<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    // Show email verification notice
    public function notice()
    {
        return view('auth.verify-email');
    }

    // Handle email verification
    public function verify(Request $request, $id, $hash)
    {
        \Log::info('Email verification attempt', ['id' => $id, 'hash' => $hash]);
        
        $user = \App\Models\User::findOrFail($id);
        $expectedHash = sha1($user->getEmailForVerification());
        
        \Log::info('Verification details', [
            'user_id' => $user->id,
            'email' => $user->email,
            'expected_hash' => $expectedHash,
            'received_hash' => $hash,
            'hash_match' => hash_equals($hash, $expectedHash)
        ]);

        // Verify the hash matches the user's email
        if (!hash_equals($hash, $expectedHash)) {
            \Log::error('Hash mismatch for verification', [
                'user_id' => $id,
                'expected' => $expectedHash,
                'received' => $hash
            ]);
            abort(403, 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            \Log::info('Email already verified', ['user_id' => $user->id]);
            return redirect()->route('login')->with('info', 'Email already verified. You can now login.');
        }

        $verified = $user->markEmailAsVerified();
        \Log::info('Mark as verified result', ['user_id' => $user->id, 'result' => $verified]);

        if ($verified) {
            event(new Verified($user));
            
            // Send welcome email
            try {
                \Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user->name));
            } catch (\Exception $e) {
                \Log::error('Failed to send welcome email: ' . $e->getMessage());
            }
        }

        return redirect()->route('login')->with('success', 'Email verified successfully! You can now login.');
    }

    // Resend verification email
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('info', 'Email already verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent to your email!');
    }
}
