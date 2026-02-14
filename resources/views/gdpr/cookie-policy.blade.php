@extends('layouts.app')

@section('title', 'Cookie Policy - JastipHype')

@section('content')
<style>
    .cookie-policy-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        position: relative;
        overflow: hidden;
    }
    .cookie-policy-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(212,168,67,0.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .cookie-policy-hero::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -15%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(212,168,67,0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    .policy-section-title {
        position: relative;
        display: inline-block;
    }
    .policy-section-title::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #d4a843, transparent);
        border-radius: 2px;
    }

    .cookie-type-card {
        background: white;
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }
    .cookie-type-card:hover {
        border-color: #d4a843;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .cookie-type-card .card-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .cookie-detail-table {
        border-collapse: separate;
        border-spacing: 0;
    }
    .cookie-detail-table thead th {
        background: #1a1a2e;
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 16px;
    }
    .cookie-detail-table thead th:first-child {
        border-radius: 10px 0 0 0;
    }
    .cookie-detail-table thead th:last-child {
        border-radius: 0 10px 0 0;
    }
    .cookie-detail-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.8125rem;
    }
    .cookie-detail-table tbody tr:last-child td:first-child {
        border-radius: 0 0 0 10px;
    }
    .cookie-detail-table tbody tr:last-child td:last-child {
        border-radius: 0 0 10px 0;
    }
    .cookie-detail-table tbody tr:hover td {
        background: #fefce8;
    }

    .browser-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        background: #f9fafb;
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }
    .browser-card:hover {
        border-color: #d4a843;
        background: #fffbeb;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .browser-card .browser-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>

<!-- Hero Section -->
<section class="cookie-policy-hero py-16 md:py-24">
    <div class="container mx-auto px-4 max-w-4xl relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm text-gray-400">
                <li><a href="/" class="hover:text-yellow-500 transition-colors">Home</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                <li class="text-yellow-500">Cookie Policy</li>
            </ol>
        </nav>

        <div class="flex items-start gap-5">
            <div class="hidden md:flex w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-600/20 to-yellow-500/10 items-center justify-center flex-shrink-0 border border-yellow-500/10">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Cookie Policy</h1>
                <p class="text-gray-300 text-base leading-relaxed max-w-2xl">
                    Transparency about how we use cookies to provide the best experience at JastipHype.
                </p>
                <div class="flex items-center gap-3 mt-4">
                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-400 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Last updated: {{ date('d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="container mx-auto px-4 max-w-4xl -mt-8 relative z-20 pb-20">

    <!-- What Are Cookies Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-900 policy-section-title mb-6">What are Cookies?</h2>
        <p class="text-gray-600 text-sm leading-relaxed">
            Cookies are small text files stored on your device when you visit a website. 
            Cookies help us provide a better and more personalized experience, remember your preferences, 
            and understand how you use our services so we can continuously improve quality.
        </p>
    </div>

    <!-- Cookie Types Section -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 policy-section-title mb-6">Types of Cookies We Use</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Necessary -->
            <div class="cookie-type-card rounded-2xl p-5">
                <div class="flex items-start gap-4">
                    <div class="card-icon bg-emerald-50">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="font-bold text-gray-900 text-sm">Essential Cookies</h3>
                            <span class="text-xs bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full font-medium">Required</span>
                        </div>
                        <p class="text-gray-500 text-xs leading-relaxed mb-3">Required for the website to function properly.</p>
                        <ul class="space-y-1.5">
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Session cookies for login
                            </li>
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                CSRF token for security
                            </li>
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Shopping cart cookies
                            </li>
                        </ul>
                        <p class="text-xs text-gray-400 mt-2 italic">These cookies cannot be disabled.</p>
                    </div>
                </div>
            </div>

            <!-- Functional -->
            <div class="cookie-type-card rounded-2xl p-5">
                <div class="flex items-start gap-4">
                    <div class="card-icon bg-blue-50">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="font-bold text-gray-900 text-sm">Functional Cookies</h3>
                            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-medium">Optional</span>
                        </div>
                        <p class="text-gray-500 text-xs leading-relaxed mb-3">Remember your preferences for a better experience.</p>
                        <ul class="space-y-1.5">
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Language preference
                            </li>
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Display preferences
                            </li>
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Saved product filters
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Analytics -->
            <div class="cookie-type-card rounded-2xl p-5">
                <div class="flex items-start gap-4">
                    <div class="card-icon bg-purple-50">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="font-bold text-gray-900 text-sm">Analytics Cookies</h3>
                            <span class="text-xs bg-purple-50 text-purple-600 px-2 py-0.5 rounded-full font-medium">Optional</span>
                        </div>
                        <p class="text-gray-500 text-xs leading-relaxed mb-3">Help us understand website usage.</p>
                        <ul class="space-y-1.5">
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Google Analytics
                            </li>
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Popular pages
                            </li>
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Visit duration
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Marketing -->
            <div class="cookie-type-card rounded-2xl p-5">
                <div class="flex items-start gap-4">
                    <div class="card-icon bg-orange-50">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="font-bold text-gray-900 text-sm">Cookies Marketing</h3>
                            <span class="text-xs bg-orange-50 text-orange-600 px-2 py-0.5 rounded-full font-medium">Opsional</span>
                        </div>
                        <p class="text-gray-500 text-xs leading-relaxed mb-3">Menampilkan iklan yang relevan dengan minat Anda.</p>
                        <ul class="space-y-1.5">
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-orange-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Facebook Pixel
                            </li>
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-orange-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Google Ads
                            </li>
                            <li class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-orange-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Retargeting ads
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cookie Detail Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-900 policy-section-title mb-6">Cookie Details</h2>
        <p class="text-gray-500 text-sm mb-5">Complete information about each cookie we use.</p>
        
        <div class="overflow-x-auto">
            <table class="cookie-detail-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Cookie Name</th>
                        <th class="text-left">Category</th>
                        <th class="text-left">Duration</th>
                        <th class="text-left">Purpose</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-medium text-gray-900">laravel_session</td>
                        <td><span class="text-xs bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">Essential</span></td>
                        <td class="text-gray-500">2 hours</td>
                        <td class="text-gray-600">Manage user session</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-900">XSRF-TOKEN</td>
                        <td><span class="text-xs bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">Essential</span></td>
                        <td class="text-gray-500">2 hours</td>
                        <td class="text-gray-600">CSRF protection</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-900">cookie_consent</td>
                        <td><span class="text-xs bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">Essential</span></td>
                        <td class="text-gray-500">1 year</td>
                        <td class="text-gray-600">Store cookie preferences</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-900">locale</td>
                        <td><span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">Functional</span></td>
                        <td class="text-gray-500">1 year</td>
                        <td class="text-gray-600">Store language preference</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-900">_ga</td>
                        <td><span class="text-xs bg-purple-50 text-purple-600 px-2 py-0.5 rounded-full">Analytics</span></td>
                        <td class="text-gray-500">2 years</td>
                        <td class="text-gray-600">Google Analytics - identify visitors</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-900">_gid</td>
                        <td><span class="text-xs bg-purple-50 text-purple-600 px-2 py-0.5 rounded-full">Analytics</span></td>
                        <td class="text-gray-500">24 hours</td>
                        <td class="text-gray-600">Google Analytics - distinguish visitors</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-900">_fbp</td>
                        <td><span class="text-xs bg-orange-50 text-orange-600 px-2 py-0.5 rounded-full">Marketing</span></td>
                        <td class="text-gray-500">3 months</td>
                        <td class="text-gray-600">Facebook Pixel tracking</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Manage Cookies Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-900 policy-section-title mb-6">Manage Cookies</h2>
        <p class="text-gray-600 text-sm leading-relaxed mb-5">You can manage your cookie preferences at any time.</p>
        
        <!-- Manage Button CTA -->
        <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-xl p-5 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-white font-semibold text-sm mb-1">Change Cookie Preferences</h3>
                <p class="text-gray-400 text-xs">Choose which cookies you want to enable or disable</p>
            </div>
            <button 
                onclick="openCookieSettings()" 
                class="flex-shrink-0 bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-semibold px-6 py-2.5 rounded-xl text-sm hover:from-yellow-500 hover:to-yellow-400 transition-all shadow-lg shadow-yellow-500/20 hover:shadow-yellow-500/30"
            >
                Manage Preferences
            </button>
        </div>

        <!-- Browser Management -->
        <p class="text-gray-600 text-sm mb-4">You can also manage cookies through your browser settings:</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener noreferrer" class="browser-card">
                <div class="browser-icon bg-blue-50">
                    <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-900">Google Chrome</span>
                    <span class="block text-xs text-gray-400">support.google.com</span>
                </div>
                <svg class="w-4 h-4 text-gray-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <a href="https://support.mozilla.org/en-US/kb/cookies-information-websites-store-on-your-computer" target="_blank" rel="noopener noreferrer" class="browser-card">
                <div class="browser-icon bg-orange-50">
                    <svg class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2V7zm0 8h2v2h-2v-2z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-900">Mozilla Firefox</span>
                    <span class="block text-xs text-gray-400">support.mozilla.org</span>
                </div>
                <svg class="w-4 h-4 text-gray-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <a href="https://support.apple.com/guide/safari/manage-cookies-sfri11471/mac" target="_blank" rel="noopener noreferrer" class="browser-card">
                <div class="browser-icon bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-900">Safari</span>
                    <span class="block text-xs text-gray-400">support.apple.com</span>
                </div>
                <svg class="w-4 h-4 text-gray-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" rel="noopener noreferrer" class="browser-card">
                <div class="browser-icon bg-sky-50">
                    <svg class="w-5 h-5 text-sky-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21 12c0-4.97-4.03-9-9-9S3 7.03 3 12c0 2.39.93 4.56 2.45 6.17C6.42 19.44 8.07 20.34 10 20.79V14h-2v-2h2V9.5C10 7.57 11.57 6 13.5 6H16v2h-2c-.55 0-1 .45-1 1v3h3l-.5 2H13v6.95c4.84-.55 8.5-4.68 8-9.95z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-900">Microsoft Edge</span>
                    <span class="block text-xs text-gray-400">support.microsoft.com</span>
                </div>
                <svg class="w-4 h-4 text-gray-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-6 md:p-8 text-center">
        <div class="w-12 h-12 rounded-full bg-yellow-500/10 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-white font-bold text-lg mb-2">Have Questions?</h2>
        <p class="text-gray-400 text-sm mb-4">Contact us if you have questions about cookie usage.</p>
        <a href="mailto:info@jastiphype.shop" class="inline-flex items-center gap-2 text-yellow-500 hover:text-yellow-400 text-sm font-medium transition-colors">
            <span>info@jastiphype.shop</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</div>

<script>
function openCookieSettings() {
    // This will trigger the cookie consent modal
    if (typeof showCookieConsent === 'function') {
        showCookieConsent();
    } else if (typeof showCookieSettings === 'function') {
        showCookieSettings();
    }
}
</script>
@endsection
