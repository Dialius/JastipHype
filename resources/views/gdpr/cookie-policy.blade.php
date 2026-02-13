@extends('layouts.app')

@section('title', 'Cookie Policy - JastipHype')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">Cookie Policy</h1>
    
    <div class="prose max-w-none">
        <p class="text-gray-600 mb-4">Terakhir diperbarui: {{ date('d F Y') }}</p>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Apa itu Cookies?</h2>
            <p class="mb-4">
                Cookies adalah file teks kecil yang disimpan di perangkat Anda ketika Anda mengunjungi website.
                Cookies membantu kami memberikan pengalaman yang lebih baik dan personal.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Jenis Cookies yang Kami Gunakan</h2>
            
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">1. Cookies Penting (Necessary)</h3>
                <p class="mb-2">Cookies ini diperlukan agar website berfungsi dengan baik:</p>
                <ul class="list-disc pl-6 mb-4">
                    <li>Session cookies untuk login</li>
                    <li>CSRF token untuk keamanan</li>
                    <li>Shopping cart cookies</li>
                </ul>
                <p class="text-sm text-gray-600">Cookies ini tidak dapat dinonaktifkan.</p>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">2. Cookies Fungsional (Functional)</h3>
                <p class="mb-2">Cookies ini mengingat preferensi Anda:</p>
                <ul class="list-disc pl-6 mb-4">
                    <li>Bahasa pilihan</li>
                    <li>Preferensi tampilan</li>
                    <li>Filter produk yang tersimpan</li>
                </ul>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">3. Cookies Analitik (Analytics)</h3>
                <p class="mb-2">Cookies ini membantu kami memahami bagaimana pengunjung menggunakan website:</p>
                <ul class="list-disc pl-6 mb-4">
                    <li>Google Analytics</li>
                    <li>Halaman yang paling banyak dikunjungi</li>
                    <li>Durasi kunjungan</li>
                </ul>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">4. Cookies Marketing (Marketing)</h3>
                <p class="mb-2">Cookies ini digunakan untuk menampilkan iklan yang relevan:</p>
                <ul class="list-disc pl-6 mb-4">
                    <li>Facebook Pixel</li>
                    <li>Google Ads</li>
                    <li>Retargeting ads</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Mengelola Cookies</h2>
            <p class="mb-4">Anda dapat mengelola preferensi cookies Anda:</p>
            
            <div class="bg-gray-50 p-6 rounded-lg mb-4">
                <button 
                    onclick="openCookieSettings()" 
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
                >
                    Kelola Preferensi Cookies
                </button>
            </div>

            <p class="mb-4">Anda juga dapat mengelola cookies melalui browser Anda:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" class="text-blue-600 hover:underline">Chrome</a></li>
                <li><a href="https://support.mozilla.org/en-US/kb/cookies-information-websites-store-on-your-computer" target="_blank" class="text-blue-600 hover:underline">Firefox</a></li>
                <li><a href="https://support.apple.com/guide/safari/manage-cookies-sfri11471/mac" target="_blank" class="text-blue-600 hover:underline">Safari</a></li>
                <li><a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" class="text-blue-600 hover:underline">Edge</a></li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Kontak</h2>
            <p class="mb-4">Jika Anda memiliki pertanyaan tentang penggunaan cookies:</p>
            <p>Email: info@jastiphype.shop</p>
        </section>
    </div>
</div>

<script>
function openCookieSettings() {
    // This will trigger the cookie consent modal
    if (typeof showCookieConsent === 'function') {
        showCookieConsent();
    }
}
</script>
@endsection
