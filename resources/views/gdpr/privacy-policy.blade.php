@extends('layouts.app')

@section('title', 'Privacy Policy - JastipHype')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">Privacy Policy</h1>
    
    <div class="prose max-w-none">
        <p class="text-gray-600 mb-4">Terakhir diperbarui: {{ date('d F Y') }}</p>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">1. Informasi yang Kami Kumpulkan</h2>
            <p class="mb-4">Kami mengumpulkan informasi berikut:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Informasi pribadi (nama, email, nomor telepon, alamat)</li>
                <li>Informasi pembayaran (diproses melalui Midtrans)</li>
                <li>Riwayat pembelian dan transaksi</li>
                <li>Data penggunaan website (cookies, log files)</li>
                <li>Informasi perangkat dan browser</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">2. Bagaimana Kami Menggunakan Informasi</h2>
            <p class="mb-4">Informasi Anda digunakan untuk:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Memproses dan mengirim pesanan Anda</li>
                <li>Mengirim notifikasi terkait pesanan</li>
                <li>Meningkatkan layanan kami</li>
                <li>Mengirim promosi (dengan persetujuan Anda)</li>
                <li>Mencegah penipuan dan aktivitas ilegal</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">3. Berbagi Informasi</h2>
            <p class="mb-4">Kami tidak menjual data pribadi Anda. Kami hanya berbagi informasi dengan:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Payment gateway (Midtrans) untuk memproses pembayaran</li>
                <li>Kurir untuk pengiriman produk</li>
                <li>Pihak ketiga yang membantu operasional (dengan perjanjian kerahasiaan)</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">4. Keamanan Data</h2>
            <p class="mb-4">Kami menggunakan langkah-langkah keamanan untuk melindungi data Anda:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Enkripsi SSL/TLS untuk transmisi data</li>
                <li>Password hashing dengan algoritma bcrypt</li>
                <li>Monitoring aktivitas mencurigakan</li>
                <li>Backup data secara berkala</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">5. Hak Anda (GDPR)</h2>
            <p class="mb-4">Anda memiliki hak untuk:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Akses:</strong> Meminta salinan data pribadi Anda</li>
                <li><strong>Perbaikan:</strong> Memperbaiki data yang tidak akurat</li>
                <li><strong>Penghapusan:</strong> Meminta penghapusan data Anda</li>
                <li><strong>Portabilitas:</strong> Menerima data dalam format yang dapat dibaca mesin</li>
                <li><strong>Keberatan:</strong> Menolak pemrosesan data tertentu</li>
            </ul>
            <p class="mb-4">
                Untuk menggunakan hak-hak ini, kunjungi 
                <a href="{{ route('gdpr.dashboard') }}" class="text-blue-600 hover:underline">GDPR Dashboard</a>
                atau hubungi kami di info@jastiphype.shop
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">6. Cookies</h2>
            <p class="mb-4">Kami menggunakan cookies untuk:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Menjaga sesi login Anda</li>
                <li>Mengingat preferensi Anda</li>
                <li>Menganalisis penggunaan website</li>
                <li>Menampilkan iklan yang relevan</li>
            </ul>
            <p class="mb-4">
                Anda dapat mengelola preferensi cookies di 
                <a href="{{ route('gdpr.cookie-policy') }}" class="text-blue-600 hover:underline">Cookie Policy</a>
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">7. Retensi Data</h2>
            <p class="mb-4">
                Kami menyimpan data Anda selama akun Anda aktif atau sepanjang diperlukan untuk memberikan layanan.
                Data transaksi disimpan sesuai dengan peraturan perpajakan (minimal 5 tahun).
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">8. Perubahan Kebijakan</h2>
            <p class="mb-4">
                Kami dapat memperbarui kebijakan ini dari waktu ke waktu. Perubahan signifikan akan diberitahukan
                melalui email atau notifikasi di website.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">9. Kontak</h2>
            <p class="mb-4">Jika Anda memiliki pertanyaan tentang kebijakan privasi ini:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Email: info@jastiphype.shop</li>
                <li>Website: <a href="https://jastiphype.shop" class="text-blue-600 hover:underline">jastiphype.shop</a></li>
            </ul>
        </section>
    </div>
</div>
@endsection
