@extends('layouts.app')

@section('title', 'Privacy Policy - JastipHype')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">Privacy Policy</h1>
    
    <div class="prose max-w-none">
        <p class="text-gray-600 mb-4">Last updated: {{ date('d F Y') }}</p>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">1. Information We Collect</h2>
            <p class="mb-4">We collect the following information:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Personal information (name, email, phone number, address)</li>
                <li>Payment information (processed through Midtrans)</li>
                <li>Purchase history and transactions</li>
                <li>Website usage data (cookies, log files)</li>
                <li>Device and browser information</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">2. How We Use Your Information</h2>
            <p class="mb-4">Your information is used to:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Process and ship your orders</li>
                <li>Send order-related notifications</li>
                <li>Improve our services</li>
                <li>Send promotional materials (with your consent)</li>
                <li>Prevent fraud and illegal activities</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">3. Information Sharing</h2>
            <p class="mb-4">We do not sell your personal data. We only share information with:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Payment gateway (Midtrans) to process payments</li>
                <li>Couriers for product delivery</li>
                <li>Third parties that assist with operations (under confidentiality agreements)</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">4. Data Security</h2>
            <p class="mb-4">We use security measures to protect your data:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>SSL/TLS encryption for data transmission</li>
                <li>Password hashing with bcrypt algorithm</li>
                <li>Monitoring of suspicious activities</li>
                <li>Regular data backups</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">5. Your Rights (GDPR)</h2>
            <p class="mb-4">You have the right to:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Access:</strong> Request a copy of your personal data</li>
                <li><strong>Rectification:</strong> Correct inaccurate data</li>
                <li><strong>Erasure:</strong> Request deletion of your data</li>
                <li><strong>Portability:</strong> Receive data in a machine-readable format</li>
                <li><strong>Objection:</strong> Object to certain data processing</li>
            </ul>
            <p class="mb-4">
                To exercise these rights, visit 
                <a href="{{ route('gdpr.dashboard') }}" class="text-blue-600 hover:underline">GDPR Dashboard</a>
                or contact us at info@jastiphype.shop
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">6. Cookies</h2>
            <p class="mb-4">We use cookies to:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Maintain your login session</li>
                <li>Remember your preferences</li>
                <li>Analyze website usage</li>
                <li>Display relevant advertisements</li>
            </ul>
            <p class="mb-4">
                You can manage cookie preferences in our 
                <a href="{{ route('gdpr.cookie-policy') }}" class="text-blue-600 hover:underline">Cookie Policy</a>
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">7. Data Retention</h2>
            <p class="mb-4">
                We retain your data while your account is active or as needed to provide services.
                Transaction data is stored in accordance with tax regulations (minimum 5 years).
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">8. Policy Changes</h2>
            <p class="mb-4">
                We may update this policy from time to time. Significant changes will be notified
                via email or website notification.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">9. Contact</h2>
            <p class="mb-4">If you have questions about this privacy policy:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Email: info@jastiphype.shop</li>
                <li>Website: <a href="https://jastiphype.shop" class="text-blue-600 hover:underline">jastiphype.shop</a></li>
            </ul>
        </section>
    </div>
</div>
@endsection
