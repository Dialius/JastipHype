<!-- Cookie Consent Banner -->
<div id="cookieConsent" 
     class="hidden fixed bottom-0 left-0 right-0 z-[9999]"
     style="animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;">
    
    <style>
        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes sparkle {
            0%, 100% { opacity: 1; transform: scale(1) rotate(0deg); }
            50% { opacity: 0.5; transform: scale(0.8) rotate(180deg); }
        }
        
        /* Custom Toggle Switch */
        .cookie-toggle {
            position: relative;
            width: 52px;
            height: 28px;
            flex-shrink: 0;
        }
        .cookie-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .cookie-toggle .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #374151;
            border-radius: 28px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .cookie-toggle .toggle-slider::before {
            content: '';
            position: absolute;
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .cookie-toggle input:checked + .toggle-slider {
            background: linear-gradient(135deg, #d4a843, #c49a38);
        }
        .cookie-toggle input:checked + .toggle-slider::before {
            transform: translateX(24px);
        }
        .cookie-toggle input:disabled + .toggle-slider {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Glassmorphism Banner */
        .cookie-banner-glass {
            background: rgba(17, 17, 30, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid rgba(212, 168, 67, 0.15);
        }

        /* Settings Modal */
        .cookie-modal-overlay {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .cookie-modal-card {
            background: linear-gradient(145deg, #1a1a2e, #16213e);
            border: 1px solid rgba(212, 168, 67, 0.12);
        }
        .cookie-category-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }
        .cookie-category-card:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(212, 168, 67, 0.2);
        }
        .cookie-category-card .category-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Scrollbar */
        .cookie-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .cookie-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .cookie-scroll::-webkit-scrollbar-thumb {
            background: rgba(212, 168, 67, 0.3);
            border-radius: 4px;
        }

        /* Gold Button */
        .btn-cookie-gold {
            background: linear-gradient(135deg, #d4a843, #b8922e);
            color: #000;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212, 168, 67, 0.25);
        }
        .btn-cookie-gold:hover {
            background: linear-gradient(135deg, #e0b94e, #c49a38);
            box-shadow: 0 6px 20px rgba(212, 168, 67, 0.35);
            transform: translateY(-1px);
        }
        .btn-cookie-outline {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #e5e7eb;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-cookie-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.25);
        }
        
        /* Sparkle Animation */
        .cookie-sparkle {
            animation: sparkle 2s ease-in-out infinite;
        }
    </style>

    <!-- Banner -->
    <div class="cookie-banner-glass px-4 py-5 md:py-4 shadow-2xl">
        <div class="container mx-auto max-w-6xl">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                <!-- Icon + Text -->
                <div class="flex items-start gap-3 flex-1">
                    <!-- Cookie Illustration -->
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-600/20 to-yellow-500/10 flex items-center justify-center flex-shrink-0 relative">
                        <!-- Cookie SVG -->
                        <svg class="w-7 h-7" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Cookie Base -->
                            <circle cx="32" cy="32" r="28" fill="#D4A843"/>
                            <circle cx="32" cy="32" r="28" fill="url(#cookieGradient)"/>
                            
                            <!-- Cookie Chips -->
                            <circle cx="22" cy="24" r="3" fill="#8B4513" opacity="0.8"/>
                            <circle cx="38" cy="22" r="2.5" fill="#654321" opacity="0.7"/>
                            <circle cx="28" cy="36" r="2.8" fill="#8B4513" opacity="0.8"/>
                            <circle cx="42" cy="34" r="2.3" fill="#654321" opacity="0.7"/>
                            <circle cx="20" cy="40" r="2" fill="#8B4513" opacity="0.6"/>
                            <circle cx="40" cy="44" r="2.5" fill="#654321" opacity="0.7"/>
                            <circle cx="32" cy="28" r="2" fill="#8B4513" opacity="0.6"/>
                            
                            <!-- Bite Mark -->
                            <path d="M 48 18 Q 52 22 48 26 Q 44 30 40 26 Q 36 22 40 18 Q 44 14 48 18 Z" fill="#1a1a2e" opacity="0.3"/>
                            
                            <!-- Gradient Definition -->
                            <defs>
                                <radialGradient id="cookieGradient" cx="0.3" cy="0.3">
                                    <stop offset="0%" stop-color="#E0B94E" stop-opacity="0.8"/>
                                    <stop offset="100%" stop-color="#C49A38" stop-opacity="0.9"/>
                                </radialGradient>
                            </defs>
                        </svg>
                        
                        <!-- Sparkle Effect -->
                        <div class="absolute -top-1 -right-1 w-3 h-3 cookie-sparkle">
                            <svg viewBox="0 0 24 24" fill="#FCD34D">
                                <path d="M12 2L13.5 8.5L20 10L13.5 11.5L12 18L10.5 11.5L4 10L10.5 8.5L12 2Z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-white text-sm font-medium leading-relaxed">
                            Kami menggunakan cookies untuk meningkatkan pengalaman belanja Anda.
                        </p>
                        <p class="text-gray-400 text-xs mt-1">
                            Pilih preferensi cookies Anda atau pelajari lebih lanjut di 
                            <a href="{{ route('gdpr.cookie-policy') }}" class="text-yellow-500 hover:text-yellow-400 underline underline-offset-2 transition-colors">Kebijakan Cookie</a> kami.
                        </p>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto flex-shrink-0">
                    <button 
                        onclick="acceptAllCookies()" 
                        class="btn-cookie-gold px-6 py-2.5 rounded-xl text-sm whitespace-nowrap"
                    >
                        Terima Semua
                    </button>
                    <button 
                        onclick="showCookieSettings()" 
                        class="btn-cookie-outline px-6 py-2.5 rounded-xl text-sm whitespace-nowrap"
                    >
                        Pengaturan
                    </button>
                    <button 
                        onclick="rejectNonEssential()" 
                        class="btn-cookie-outline px-6 py-2.5 rounded-xl text-sm whitespace-nowrap"
                    >
                        Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cookie Settings Modal -->
<div id="cookieSettings" class="hidden fixed inset-0 z-[10000] flex items-center justify-center p-4 cookie-modal-overlay"
     style="animation: fadeIn 0.3s ease forwards;">
    <div class="cookie-modal-card rounded-2xl w-full max-w-xl max-h-[90vh] overflow-hidden shadow-2xl"
         style="animation: scaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Pengaturan Cookies</h2>
                    <p class="text-gray-400 text-xs mt-1">Kelola preferensi privasi Anda</p>
                </div>
                <button onclick="closeCookieSettings()" 
                        class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-5 overflow-y-auto cookie-scroll" style="max-height: calc(90vh - 180px);">
            <p class="text-gray-400 text-sm mb-5 leading-relaxed">
                Cookies penting diperlukan untuk fungsi dasar website dan tidak dapat dinonaktifkan. 
                Anda dapat memilih untuk mengaktifkan atau menonaktifkan cookie lainnya.
            </p>

            <div class="space-y-3">
                <!-- Necessary Cookies -->
                <div class="cookie-category-card rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="category-icon bg-emerald-500/15">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-white font-semibold text-sm">Cookies Penting</h3>
                                <span class="text-xs text-emerald-400 bg-emerald-400/10 px-2.5 py-1 rounded-full font-medium">Selalu Aktif</span>
                            </div>
                            <p class="text-gray-400 text-xs leading-relaxed">
                                Diperlukan untuk login, shopping cart, CSRF token, dan fungsi dasar keamanan website.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Functional Cookies -->
                <div class="cookie-category-card rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="category-icon bg-blue-500/15">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-white font-semibold text-sm">Cookies Fungsional</h3>
                                <label class="cookie-toggle">
                                    <input type="checkbox" id="functionalCookies">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <p class="text-gray-400 text-xs leading-relaxed">
                                Mengingat preferensi seperti bahasa, tampilan, dan filter produk yang tersimpan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Analytics Cookies -->
                <div class="cookie-category-card rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="category-icon bg-purple-500/15">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-white font-semibold text-sm">Cookies Analitik</h3>
                                <label class="cookie-toggle">
                                    <input type="checkbox" id="analyticsCookies">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <p class="text-gray-400 text-xs leading-relaxed">
                                Membantu memahami cara pengunjung berinteraksi melalui Google Analytics dan metrik kunjungan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Marketing Cookies -->
                <div class="cookie-category-card rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="category-icon bg-orange-500/15">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-white font-semibold text-sm">Cookies Marketing</h3>
                                <label class="cookie-toggle">
                                    <input type="checkbox" id="marketingCookies">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <p class="text-gray-400 text-xs leading-relaxed">
                                Menampilkan iklan yang relevan melalui Facebook Pixel, Google Ads, dan retargeting.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-white/10 flex flex-col sm:flex-row gap-3">
            <button 
                onclick="saveCustomCookieSettings()" 
                class="btn-cookie-gold flex-1 px-5 py-3 rounded-xl text-sm font-semibold"
            >
                Simpan Pengaturan
            </button>
            <button 
                onclick="acceptAllCookies()" 
                class="btn-cookie-outline flex-1 px-5 py-3 rounded-xl text-sm font-medium"
            >
                Terima Semua
            </button>
        </div>
    </div>
</div>

<script>
// Check if user has already consented
document.addEventListener('DOMContentLoaded', function() {
    if (!getCookie('cookie_consent')) {
        setTimeout(() => {
            const banner = document.getElementById('cookieConsent');
            banner.classList.remove('hidden');
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
    const modal = document.getElementById('cookieSettings');
    modal.classList.remove('hidden');
    
    // Load current preferences if they exist
    const consent = getCookie('cookie_consent');
    if (consent) {
        try {
            const prefs = JSON.parse(decodeURIComponent(consent));
            document.getElementById('functionalCookies').checked = prefs.functional || false;
            document.getElementById('analyticsCookies').checked = prefs.analytics || false;
            document.getElementById('marketingCookies').checked = prefs.marketing || false;
        } catch(e) {}
    }
}

// Make showCookieConsent available globally for the cookie policy page
window.showCookieConsent = showCookieSettings;

function closeCookieSettings() {
    const modal = document.getElementById('cookieSettings');
    modal.classList.add('hidden');
}

// Close modal when clicking outside (on overlay)
document.addEventListener('click', function(event) {
    const modal = document.getElementById('cookieSettings');
    if (event.target === modal) {
        closeCookieSettings();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('cookieSettings');
        if (!modal.classList.contains('hidden')) {
            closeCookieSettings();
        }
    }
});

// Prevent modal close when clicking inside modal content
document.addEventListener('DOMContentLoaded', function() {
    const modalContent = document.querySelector('#cookieSettings .cookie-modal-card');
    if (modalContent) {
        modalContent.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    }
});

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
}

function loadMarketing() {
    // Load marketing pixels (Facebook, Google Ads, etc)
}
</script>
