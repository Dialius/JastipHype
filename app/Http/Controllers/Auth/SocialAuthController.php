<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect to OAuth provider
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle OAuth provider callback
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Validate that we got user data
            if (!$socialUser || !$socialUser->getEmail()) {
                throw new \Exception('Unable to retrieve user information from ' . ucfirst($provider));
            }
            
            // Check if user exists by email
            $user = User::where('email', $socialUser->getEmail())->first();
            
            if ($user) {
                // User exists, just login
                Auth::login($user);
                
                return redirect()->intended(route('home'))->with('success', 'Welcome back, ' . $user->name . '!');
            } else {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(24)), // Random password
                    'email_verified_at' => now(), // Auto-verify social logins
                    'is_admin' => false, // Default to non-admin
                ]);
                
                Auth::login($user);
                
                return redirect()->route('home')->with('success', 'Welcome to JastipHype, ' . $user->name . '!');
            }
            
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Google OAuth Invalid State: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Authentication session expired. Please try again.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}
