<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest Routes (only for non-authenticated users)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Forgot Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
    Route::post('/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.resend');
});

// Social Authentication Routes (Google Only)

Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])
    ->name('social.redirect')
    ->where('provider', 'google');
    
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
    ->name('social.callback')
    ->where('provider', 'google');

// Search API
Route::get('/api/search/suggestions', [ProductController::class, 'searchSuggestions'])
    ->name('search.suggestions');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart Routes
use App\Http\Controllers\CartController;
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart.index');
    Route::get('/cart/mini', 'miniCart')->name('cart.mini');
    Route::post('/cart', 'store')->name('cart.store');
    Route::patch('/cart/{item}', 'update')->name('cart.update');
    Route::delete('/cart/{item}', 'destroy')->name('cart.destroy');
});

// Checkout Routes
use App\Http\Controllers\CheckoutController;
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

// Payment Routes
use App\Http\Controllers\PaymentController;
Route::get('/payment/{orderNumber}', [PaymentController::class, 'show'])->name('payment.show');
Route::get('/payment/{orderNumber}/status', [PaymentController::class, 'checkStatus'])->name('payment.status');
Route::post('/payment/{orderNumber}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

// Webhook Routes (CSRF exception configured in VerifyCsrfToken middleware)
use App\Http\Controllers\WebhookController;
Route::post('/payment/webhook', [WebhookController::class, 'handle'])->name('payment.webhook');

// Authenticated Routes (requires login)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    
    // Email Verification Routes
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware(['throttle:6,1'])->name('verification.send');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Password Change with OTP
    Route::post('/profile/password/request-otp', [ProfileController::class, 'requestPasswordChangeOtp'])->name('profile.password.request-otp');
    Route::post('/profile/password/verify-otp', [ProfileController::class, 'verifyPasswordChangeOtp'])->name('profile.password.verify-otp');
    
    // Wishlist Routes
    Route::post('/wishlist/{product}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    
    // Review Routes
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Shipping Calculator Routes
// Location API Routes (RajaOngkir Proxy)
use App\Http\Controllers\LocationController;
Route::prefix('api/location')->name('location.')->group(function () {
    Route::get('/provinces', [LocationController::class, 'getProvinces'])->name('provinces');
    Route::get('/cities/{province}', [LocationController::class, 'getCities'])->name('cities');
    Route::get('/subdistricts/{city}', [LocationController::class, 'getSubdistricts'])->name('subdistricts');
    Route::post('/cost', [LocationController::class, 'getCost'])->name('cost');
});
