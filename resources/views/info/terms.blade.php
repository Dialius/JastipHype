@extends('layouts.app')

@section('title', 'Terms of Service - JastipHype')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-gray-900 to-black text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-gold/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Terms of Service</h1>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    Please read these terms carefully before using our services
                </p>
                <p class="text-sm text-gray-400 mt-4">Last updated: February 3, 2026</p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12 space-y-10">
                
                {{-- Section 1 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">1</span>
                        Acceptance of Terms
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>By accessing and using JastipHype ("the Platform"), you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.</p>
                        <p>We reserve the right to update these terms at any time. Continued use of the Platform after changes constitutes acceptance of the modified terms.</p>
                    </div>
                </section>

                {{-- Section 2 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">2</span>
                        Account Registration
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>To make purchases on JastipHype, you must create an account with accurate and complete information. You are responsible for:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Maintaining the confidentiality of your account credentials</li>
                            <li>All activities that occur under your account</li>
                            <li>Notifying us immediately of any unauthorized access</li>
                            <li>Ensuring your email address is valid for account verification</li>
                        </ul>
                    </div>
                </section>

                {{-- Section 3 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">3</span>
                        Products & Services
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>JastipHype is a personal shopper (Jastip) service specializing in authentic streetwear and fashion items. All products are:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>100% authentic and sourced from authorized retailers</li>
                            <li>Subject to availability and may be sold out without notice</li>
                            <li>Priced including service fees and handling costs</li>
                            <li>Shipped from our warehouse after quality verification</li>
                        </ul>
                    </div>
                </section>

                {{-- Section 4 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">4</span>
                        Orders & Payments
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>When you place an order:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Payment must be completed within 24 hours (or sooner for limited items)</li>
                            <li>Orders are confirmed only after successful payment verification</li>
                            <li>We reserve the right to cancel orders due to pricing errors or stock issues</li>
                            <li>Modifications or cancellations are allowed within 2 hours of order placement</li>
                        </ul>
                    </div>
                </section>

                {{-- Section 5 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">5</span>
                        Shipping & Delivery
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>Delivery terms:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>We currently ship within Indonesia only</li>
                            <li>Delivery times are estimates and may vary</li>
                            <li>Risk of loss transfers to you upon delivery to the carrier</li>
                            <li>You must provide accurate shipping information</li>
                        </ul>
                    </div>
                </section>

                {{-- Section 6 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">6</span>
                        Returns & Refunds
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>Our return policy:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Returns accepted within 7 days of delivery</li>
                            <li>Items must be unused, unworn, and in original packaging</li>
                            <li>Refunds are processed within 5-7 business days</li>
                            <li>Original shipping fees are non-refundable</li>
                        </ul>
                        <p>For complete details, visit our <a href="{{ route('info.returns') }}" class="text-accent-gold hover:underline">Returns & Refunds page</a>.</p>
                    </div>
                </section>

                {{-- Section 7 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">7</span>
                        Prohibited Activities
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>You agree not to:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Use the Platform for fraudulent or illegal purposes</li>
                            <li>Attempt to hack, disrupt, or compromise our systems</li>
                            <li>Create multiple accounts to abuse promotions</li>
                            <li>Resell products commercially without authorization</li>
                            <li>Submit false reviews or misleading information</li>
                        </ul>
                    </div>
                </section>

                {{-- Section 8 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">8</span>
                        Limitation of Liability
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>JastipHype shall not be liable for:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Indirect, incidental, or consequential damages</li>
                            <li>Delays or failures caused by third-party services</li>
                            <li>Product defects covered by manufacturer warranty</li>
                            <li>Loss or damage during shipping (covered by courier insurance)</li>
                        </ul>
                    </div>
                </section>

                {{-- Section 9 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">9</span>
                        Contact Information
                    </h2>
                    <div class="text-gray-600 leading-relaxed">
                        <p>For questions about these Terms of Service, contact us at:</p>
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p><strong>Email:</strong> legal@jastiphype.com</p>
                            <p><strong>Address:</strong> Jakarta, Indonesia</p>
                        </div>
                    </div>
                </section>

            </div>

            {{-- Related Links --}}
            <div class="mt-8 flex flex-wrap gap-4 justify-center">
                <a href="{{ route('info.privacy') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Privacy Policy
                </a>
                <a href="{{ route('info.faq') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-black border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    FAQ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
