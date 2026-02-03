@extends('layouts.app')

@section('title', 'Privacy Policy - JastipHype')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-gray-900 to-black text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-gold/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Privacy Policy</h1>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    Your privacy is important to us. Learn how we protect your data.
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
                        Information We Collect
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>We collect information to provide better services to our users:</p>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-2">Personal Information</h4>
                            <ul class="list-disc pl-6 space-y-1 text-sm">
                                <li>Name and email address (during registration)</li>
                                <li>Phone number and shipping address (during checkout)</li>
                                <li>Payment information (processed securely via Midtrans)</li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-2">Usage Information</h4>
                            <ul class="list-disc pl-6 space-y-1 text-sm">
                                <li>Browser type and device information</li>
                                <li>Pages visited and time spent on site</li>
                                <li>IP address and approximate location</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- Section 2 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">2</span>
                        How We Use Your Information
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>Your information is used to:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Process orders</strong> – fulfill purchases and arrange shipping</li>
                            <li><strong>Customer support</strong> – respond to inquiries and resolve issues</li>
                            <li><strong>Improve services</strong> – analyze usage patterns to enhance user experience</li>
                            <li><strong>Send notifications</strong> – order updates, shipping confirmations, and promotional emails (with opt-out option)</li>
                            <li><strong>Prevent fraud</strong> – detect and prevent unauthorized transactions</li>
                        </ul>
                    </div>
                </section>

                {{-- Section 3 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">3</span>
                        Information Sharing
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>We do not sell your personal information. We may share data with:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Payment processors</strong> (Midtrans) – to securely process transactions</li>
                            <li><strong>Shipping partners</strong> (JNE, J&T, SiCepat) – to deliver your orders</li>
                            <li><strong>Analytics services</strong> – to understand and improve our platform</li>
                            <li><strong>Legal authorities</strong> – when required by law or to protect our rights</li>
                        </ul>
                    </div>
                </section>

                {{-- Section 4 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">4</span>
                        Data Security
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>We implement industry-standard security measures:</p>
                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span class="font-semibold text-green-800">SSL Encryption</span>
                                </div>
                                <p class="text-sm text-green-700">All data transmitted is encrypted using HTTPS</p>
                            </div>
                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span class="font-semibold text-blue-800">PCI-DSS Compliant</span>
                                </div>
                                <p class="text-sm text-blue-700">Payment processing meets industry security standards</p>
                            </div>
                            <div class="bg-purple-50 border border-purple-100 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                    <span class="font-semibold text-purple-800">Secure Passwords</span>
                                </div>
                                <p class="text-sm text-purple-700">Passwords are hashed, never stored in plain text</p>
                            </div>
                            <div class="bg-orange-50 border border-orange-100 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-semibold text-orange-800">Rate Limiting</span>
                                </div>
                                <p class="text-sm text-orange-700">Protection against brute-force attacks</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Section 5 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">5</span>
                        Cookies & Tracking
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>We use cookies and similar technologies for:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Essential cookies</strong> – required for login and cart functionality</li>
                            <li><strong>Analytics cookies</strong> – to understand how visitors use our site</li>
                            <li><strong>Preference cookies</strong> – to remember your settings</li>
                        </ul>
                        <p class="text-sm mt-4">You can control cookies through your browser settings. Disabling essential cookies may affect site functionality.</p>
                    </div>
                </section>

                {{-- Section 6 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">6</span>
                        Your Rights
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>You have the right to:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Access</strong> – request a copy of your personal data</li>
                            <li><strong>Correction</strong> – update inaccurate information</li>
                            <li><strong>Deletion</strong> – request removal of your data (subject to legal requirements)</li>
                            <li><strong>Opt-out</strong> – unsubscribe from marketing emails</li>
                        </ul>
                        <p class="mt-4">To exercise these rights, contact us at <a href="mailto:privacy@jastiphype.com" class="text-accent-gold hover:underline">privacy@jastiphype.com</a></p>
                    </div>
                </section>

                {{-- Section 7 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">7</span>
                        Data Retention
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>We retain your data for as long as necessary to provide services and comply with legal obligations:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Account information – until account deletion</li>
                            <li>Order history – 5 years (for tax and legal purposes)</li>
                            <li>Analytics data – 2 years (anonymized)</li>
                        </ul>
                    </div>
                </section>

                {{-- Section 8 --}}
                <section>
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-accent-gold/20 rounded-lg flex items-center justify-center text-accent-gold text-sm font-bold">8</span>
                        Contact Us
                    </h2>
                    <div class="text-gray-600 leading-relaxed">
                        <p>For privacy-related inquiries:</p>
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p><strong>Email:</strong> privacy@jastiphype.com</p>
                            <p><strong>Address:</strong> Jakarta, Indonesia</p>
                        </div>
                    </div>
                </section>

            </div>

            {{-- Related Links --}}
            <div class="mt-8 flex flex-wrap gap-4 justify-center">
                <a href="{{ route('info.terms') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Terms of Service
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
