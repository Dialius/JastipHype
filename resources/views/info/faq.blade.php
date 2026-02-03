@extends('layouts.app')

@section('title', 'FAQ - JastipHype')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-gray-900 to-black text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-gold/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Frequently Asked Questions</h1>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    Find answers to common questions about our products and services
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            {{-- Search Box --}}
            <div class="mb-10" x-data="{ search: '' }">
                <div class="relative">
                    <input type="text" 
                           x-model="search"
                           @input="filterFAQ($event.target.value)"
                           placeholder="Search for answers..." 
                           class="w-full px-5 py-4 pl-12 border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all"
                    >
                    <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- FAQ Categories --}}
            <div class="space-y-8" x-data="{ activeCategory: 'ordering' }">
                {{-- Category Nav --}}
                <div class="flex flex-wrap gap-2 mb-8">
                    <button @click="activeCategory = 'ordering'" 
                            :class="activeCategory === 'ordering' ? 'bg-black text-white' : 'bg-white text-gray-700 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200">
                        Ordering
                    </button>
                    <button @click="activeCategory = 'payment'" 
                            :class="activeCategory === 'payment' ? 'bg-black text-white' : 'bg-white text-gray-700 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200">
                        Payment
                    </button>
                    <button @click="activeCategory = 'shipping'" 
                            :class="activeCategory === 'shipping' ? 'bg-black text-white' : 'bg-white text-gray-700 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200">
                        Shipping
                    </button>
                    <button @click="activeCategory = 'returns'" 
                            :class="activeCategory === 'returns' ? 'bg-black text-white' : 'bg-white text-gray-700 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200">
                        Returns
                    </button>
                    <button @click="activeCategory = 'account'" 
                            :class="activeCategory === 'account' ? 'bg-black text-white' : 'bg-white text-gray-700 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200">
                        Account
                    </button>
                </div>

                {{-- Ordering FAQ --}}
                <div x-show="activeCategory === 'ordering'" x-transition:enter class="faq-section">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold">Ordering</h2>
                    </div>

                    <div class="space-y-3">
                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">How do I place an order?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Simply browse our collection, add items to your cart, and proceed to checkout. You'll need to create an account or log in to complete your purchase. Follow the checkout steps to enter your shipping address and payment details.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">Can I modify or cancel my order?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    You can modify or cancel your order within 2 hours of placing it, as long as it hasn't been processed yet. Contact our support team immediately for assistance.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">Are the products authentic?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Yes, all products on JastipHype are 100% authentic. We source our items directly from authorized retailers and official brand stores. Every item comes with proof of authenticity.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment FAQ --}}
                <div x-show="activeCategory === 'payment'" x-transition:enter class="faq-section">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold">Payment</h2>
                    </div>

                    <div class="space-y-3">
                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">What payment methods do you accept?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    We accept various payment methods including Bank Transfer (BCA, Mandiri, BNI, BRI), Credit/Debit Cards (Visa, Mastercard), E-Wallets (GoPay, OVO, DANA), Virtual Accounts, and QRIS.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">Is my payment information secure?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Absolutely! All transactions are processed through Midtrans, a PCI-DSS compliant payment gateway. Your payment information is encrypted and never stored on our servers.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">How long do I have to complete payment?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Payment must be completed within 24 hours for bank transfers and virtual accounts. For limited edition items, the payment window may be shorter (1-2 hours) to ensure availability.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shipping FAQ --}}
                <div x-show="activeCategory === 'shipping'" x-transition:enter class="faq-section">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold">Shipping</h2>
                    </div>

                    <div class="space-y-3">
                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">How long does shipping take?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Shipping times vary depending on the courier and your location. Generally, JNE Regular takes 3-5 business days, J&T Express takes 2-4 days, and JNE YES delivers within 1-2 days.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">Do you ship internationally?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Currently, we only ship within Indonesia. We're working on expanding our shipping options to serve international customers in the future.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">How can I track my order?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Once your order is shipped, you'll receive an email with your tracking number. You can also track your order from your account dashboard or use the courier's website directly.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Returns FAQ --}}
                <div x-show="activeCategory === 'returns'" x-transition:enter class="faq-section">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold">Returns & Refunds</h2>
                    </div>

                    <div class="space-y-3">
                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">What is your return policy?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    We offer a 7-day return policy from the date of delivery. Items must be in their original, unused condition with all tags attached. For detailed information, visit our <a href="{{ route('info.returns') }}" class="text-accent-gold hover:underline">Returns page</a>.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">How long does it take to get a refund?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Refunds are processed within 5-7 business days after we receive and inspect your returned item. The actual time for the refund to appear in your account depends on your payment method.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Account FAQ --}}
                <div x-show="activeCategory === 'account'" x-transition:enter class="faq-section">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold">Account</h2>
                    </div>

                    <div class="space-y-3">
                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">How do I create an account?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Click the "Sign Up" button at the top of the page, enter your email address and create a password. You'll receive a verification email to confirm your account.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">How do I reset my password?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Click "Forgot Password" on the login page and enter your email address. You'll receive an email with instructions to reset your password.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-medium">How do I update my account information?</span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-4">
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Log in to your account and go to your Profile page. From there, you can update your personal information, shipping addresses, and notification preferences.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Still Need Help Section --}}
            <div class="mt-12 bg-gradient-to-r from-gray-900 to-black rounded-2xl p-8 text-center text-white">
                <h2 class="text-2xl font-bold mb-3">Still Have Questions?</h2>
                <p class="text-gray-300 mb-6 max-w-md mx-auto">
                    Can't find what you're looking for? Our support team is here to help.
                </p>
                <a href="{{ route('info.contact') }}" class="inline-flex items-center gap-2 bg-white text-black px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function filterFAQ(searchTerm) {
    const items = document.querySelectorAll('.faq-item');
    const sections = document.querySelectorAll('.faq-section');
    const searchLower = searchTerm.toLowerCase();
    
    if (!searchTerm) {
        items.forEach(item => item.style.display = 'block');
        return;
    }
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchLower)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endsection
