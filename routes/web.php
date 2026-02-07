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
use App\Http\Controllers\InfoController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\StorageController;

// TEST ROUTE - Simple test
Route::get('/test-route', function() {
    return 'Route works!';
});

// Storage File Serving Route (MUST BE FIRST - Alternative to symlink)
Route::any('/storage/{path}', function($path) {
    $path = str_replace(['../', '..\\'], '', $path);
    
    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File not found in storage: ' . $path);
    }
    
    $filePath = Storage::disk('public')->path($path);
    $mimeType = Storage::disk('public')->mimeType($path);
    
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.serve');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Unauthorized Access Page
Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');

// Info Pages
Route::prefix('info')->name('info.')->group(function () {
    Route::get('/contact', [InfoController::class, 'contact'])->name('contact');
    Route::get('/shipping', [InfoController::class, 'shipping'])->name('shipping');
    Route::get('/returns', [InfoController::class, 'returns'])->name('returns');
    Route::get('/faq', [InfoController::class, 'faq'])->name('faq');
    Route::get('/terms', [InfoController::class, 'terms'])->name('terms');
    Route::get('/privacy', [InfoController::class, 'privacy'])->name('privacy');
});

// Request Product
Route::get('/request', [RequestController::class, 'index'])->name('request.index');
Route::post('/request', [RequestController::class, 'store'])->name('request.store');

// Brands
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{slug}', [BrandController::class, 'show'])->name('brands.show');

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

// Email Verification Routes (must work for both guests and authenticated users)
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

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
Route::post('/checkout/apply-discount', [CheckoutController::class, 'applyDiscount'])->name('checkout.apply-discount');
Route::post('/checkout/remove-discount', [CheckoutController::class, 'removeDiscount'])->name('checkout.remove-discount');

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

// Support Ticket Routes (Customer Side)
use App\Http\Controllers\SupportController;
Route::prefix('support')->name('support.')->group(function () {
    Route::post('/tickets', [SupportController::class, 'store'])->name('store');
    Route::get('/tickets/active', [SupportController::class, 'getActiveTicket'])->name('active');
    Route::get('/tickets/{ticket}', [SupportController::class, 'show'])->name('show');
    Route::post('/tickets/{ticket}/messages', [SupportController::class, 'sendMessage'])->name('send-message');
    Route::get('/tickets/{ticket}/messages', [SupportController::class, 'getMessages'])->name('messages');
});

// Contact form routes
use App\Http\Controllers\ContactController;
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
