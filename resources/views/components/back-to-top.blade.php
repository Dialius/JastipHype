<!-- Back to Top Button -->
<div x-data="backToTop()" 
     x-show="showButton" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     :style="buttonStyle"
     class="fixed left-1/2 transform -translate-x-1/2 z-50"
     style="display: none;">
    <button @click="scrollToTop" 
            :class="isDarkBackground ? 'ring-1 ring-white ring-offset-1' : ''"
            class="bg-black text-white p-2 rounded-full shadow-lg hover:bg-gray-800 transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2"
            aria-label="Back to top">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
</div>

<script>
function backToTop() {
    return {
        showButton: false,
        isDarkBackground: false,
        buttonStyle: '',
        
        init() {
            window.addEventListener('scroll', () => {
                this.showButton = window.pageYOffset > 300;
                this.updateButtonPosition();
                this.checkBackgroundColor();
            });
            
            window.addEventListener('resize', () => {
                this.updateButtonPosition();
            });
        },
        
        updateButtonPosition() {
            const copyrightSection = document.getElementById('footer-copyright');
            
            if (!copyrightSection) {
                this.buttonStyle = 'bottom: 2rem;';
                return;
            }
            
            const copyrightRect = copyrightSection.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const buttonHeight = 40; // Approximate button height (p-2 + icon = ~40px)
            
            // Calculate distance from viewport bottom to the top of copyright section (the border line)
            const copyrightTopFromBottom = viewportHeight - copyrightRect.top;
            
            // Default position from bottom
            const defaultBottom = 32; // 2rem = 32px
            const gapFromBorder = -15; // Minimal gap between button and border line
            
            // If the copyright border line is visible in the viewport
            if (copyrightRect.top < viewportHeight && copyrightRect.top > 0) {
                // Position button above the border line
                const newBottom = copyrightTopFromBottom + gapFromBorder;
                
                // Only apply if it's different from default (i.e., footer is in view)
                if (newBottom > defaultBottom) {
                    this.buttonStyle = `bottom: ${newBottom}px;`;
                } else {
                    this.buttonStyle = `bottom: ${defaultBottom}px;`;
                }
            } else {
                // Use default position when footer is not in view
                this.buttonStyle = `bottom: ${defaultBottom}px;`;
            }
        },
        
        checkBackgroundColor() {
            const scrollY = window.pageYOffset;
            const viewportHeight = window.innerHeight;
            const buttonY = viewportHeight - 100;
            
            const footer = document.querySelector('footer');
            const darkSections = document.querySelectorAll('[class*="bg-black"], [class*="bg-gray-900"], [class*="bg-gray-800"]');
            
            this.isDarkBackground = false;
            
            if (footer) {
                const footerTop = footer.getBoundingClientRect().top + scrollY;
                if (scrollY + buttonY >= footerTop - 50) {
                    this.isDarkBackground = true;
                    return;
                }
            }
            
            darkSections.forEach(section => {
                const rect = section.getBoundingClientRect();
                const sectionTop = rect.top + scrollY;
                const sectionBottom = sectionTop + rect.height;
                const buttonAbsoluteY = scrollY + buttonY;
                
                if (buttonAbsoluteY >= sectionTop && buttonAbsoluteY <= sectionBottom) {
                    this.isDarkBackground = true;
                }
            });
        },
        
        scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }
}
</script>
