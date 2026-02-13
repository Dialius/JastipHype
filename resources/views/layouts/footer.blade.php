<footer class="bg-black text-white mt-20">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-lg font-bold mb-4">JASTIP<span class="text-accent-gold">HYPE</span></h3>
                <p class="text-sm text-gray-400">Your destination for limited edition fashion and exclusive drops from the world's top brands.</p>
            </div>

            <!-- Shop -->
            <div>
                <h4 class="font-semibold mb-4">SHOP</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">All Products</a></li>
                    <li><a href="{{ route('products.index', ['sort' => 'newest']) }}" class="hover:text-white transition-colors">New Arrivals</a></li>
                    <li><a href="{{ route('products.index', ['availability' => ['limited']]) }}" class="hover:text-white transition-colors">Limited Editions</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Brands</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h4 class="font-semibold mb-4">CUSTOMER SERVICE</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('info.contact') }}" class="hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="{{ route('info.shipping') }}" class="hover:text-white transition-colors">Shipping Info</a></li>
                    <li><a href="{{ route('info.returns') }}" class="hover:text-white transition-colors">Returns</a></li>
                    <li><a href="{{ route('info.faq') }}" class="hover:text-white transition-colors">FAQ</a></li>
                </ul>
            </div>

            <!-- Follow Us -->
            <div>
                <h4 class="font-semibold mb-4">FOLLOW US</h4>
                <div class="flex space-x-4">
                    <a href="https://www.instagram.com/jastip.hype.id" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors" aria-label="Follow us on Instagram">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div id="footer-copyright" class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} JastipHype. All rights reserved.</p>
            <div class="mt-4 flex flex-wrap justify-center gap-4">
                <a href="{{ route('info.terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                <span>•</span>
                <a href="{{ route('info.privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                <span>•</span>
                <a href="{{ route('gdpr.cookie-policy') }}" class="hover:text-white transition-colors">Cookie Policy</a>
                @auth
                <span>•</span>
                <a href="{{ route('gdpr.dashboard') }}" class="hover:text-white transition-colors">My Data</a>
                @endauth
            </div>
        </div>
    </div>
</footer>
