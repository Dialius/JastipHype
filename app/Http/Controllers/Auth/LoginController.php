<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Check if user is admin and redirect to admin panel
        if (auth()->user()->is_admin) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome to Admin Panel!');
        }

        // Regular users go to homepage
        return redirect()->intended(route('home'))
            ->with('success', 'Welcome back!');
    }
}
