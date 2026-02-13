<!-- Cookie Consent Banner -->
<div id="cookieConsent" class="hidden fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 shadow-lg z-50">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex-1">
                <p class="text-sm">
                    🍪 Kami menggunakan cookies untuk meningkatkan pengalaman Anda. 
                    Dengan melanjutkan, Anda menyetujui penggunaan cookies kami.
                    <a href="{{ route('gdpr.cookie-policy') }}" class="underline hover:text-gray-300">Pelajari lebih lanjut</a>
                </p>
            </div>
            <div class="flex gap-3">
                <button 
                    onclick="acceptAllCookies()" 
                    class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded text-sm font-medium"
                >
                    Terima Semua
                </button>
                <button 
                    onclick="showCookieSettings()" 
                    class="bg-gray-700 hover:bg-gray-600 px-6 py-2 rounded text-sm font-medium"
                >
                    Pengaturan
                </button>
                <button 
                    onclick="rejectNonEssential()" 
                    class="bg-gray-700 hover:bg-gray-600 px-6 py-2 rounded text-sm font-medium"
                >
                    Tolak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cookie Settings Modal -->
<div id="cookieSettings" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Pengaturan Cookies</h2>
                <button onclick="closeCookieSettings()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <p class="text-gray-600 mb-6">
                Kelola preferensi cookies Anda. Cookies penting diperlukan untuk fungsi dasar website dan tidak dapat dinonaktifkan.
            </p>

            <div class="space-y-4">
                <!-- Necessary Cookies -->
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold">Cookies Penting</h3>
                        <span class="text-sm text-gray-500">Selalu Aktif</span>
                    </div>
                    <p class="text-sm text-gray-600">
                        Cookies ini diperlukan untuk fungsi dasar website seperti login, shopping cart, dan keamanan.
                    </p>
                </div>

                <!-- Functional Cookies -->
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold">Cookies Fungsional</h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="functionalCookies" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <p class="text-sm text-gray-600">
                        Cookies ini mengingat preferensi Anda seperti bahasa dan pengaturan tampilan.
                    </p>
                </div>

                <!-- Analytics Cookies -->
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold">Cookies Analitik</h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="analyticsCookies" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <p class="text-sm text-gray-600">
                        Cookies ini membantu kami memahami bagaimana pengunjung berinteraksi dengan website.
                    </p>
                </div>

                <!-- Marketing Cookies -->
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold">Cookies Marketing</h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="marketingCookies" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <p class="text-sm text-gray-600">
                        Cookies ini digunakan untuk menampilkan iklan yang relevan dengan minat Anda.
                    </p>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button 
                    onclick="saveCustomCookieSettings()" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded font-medium"
                >
                    Simpan Pengaturan
                </button>
                <button 
                    onclick="acceptAllCookies()" 
                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded font-medium"
                >
                    Terima Semua
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Check if user has already consented
document.addEventListener('DOMContentLoaded', function() {
    if (!getCookie('cookie_consent')) {
        setTimeout(() => {
            document.getElementById('cookieConsent').classList.remove('hidden');
        }, 1000);
    }
});

function acceptAllCookies() {
    saveCookieConsent(true, true, true, true);
}

function rejectNonEssential() {
    saveCookieConsent(true, false, false, false);
}

function showCookieSettings() {
    document.getElementById('cookieConsent').classList.add('hidden');
    document.getElementById('cookieSettings').classList.remove('hidden');
}

function closeCookieSettings() {
    document.getElementById('cookieSettings').classList.add('hidden');
}

function saveCustomCookieSettings() {
    const functional = document.getElementById('functionalCookies').checked;
    const analytics = document.getElementById('analyticsCookies').checked;
    const marketing = document.getElementById('marketingCookies').checked;
    
    saveCookieConsent(true, functional, analytics, marketing);
}

function saveCookieConsent(necessary, functional, analytics, marketing) {
    // Save to backend
    fetch('{{ route("gdpr.cookie-consent") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            necessary: necessary,
            functional: functional,
            analytics: analytics,
            marketing: marketing
        })
    });

    // Save to cookie
    setCookie('cookie_consent', JSON.stringify({
        necessary, functional, analytics, marketing
    }), 365);

    // Hide banners
    document.getElementById('cookieConsent').classList.add('hidden');
    document.getElementById('cookieSettings').classList.add('hidden');

    // Load analytics if accepted
    if (analytics) {
        loadAnalytics();
    }

    // Load marketing if accepted
    if (marketing) {
        loadMarketing();
    }
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

function setCookie(name, value, days) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax`;
}

function loadAnalytics() {
    // Load Google Analytics or other analytics scripts
    console.log('Analytics loaded');
}

function loadMarketing() {
    // Load marketing pixels (Facebook, Google Ads, etc)
    console.log('Marketing scripts loaded');
}
</script>
