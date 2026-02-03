/**
 * JastipHype Admin - Currency Utilities
 * 
 * Global utilities for formatting Indonesian Rupiah currency
 * and number inputs in admin panel.
 */

const AdminCurrency = {
    /**
     * Default locale and currency settings
     */
    locale: 'id-ID',
    currency: 'IDR',

    /**
     * Format number to Indonesian Rupiah
     * @param {number|string} value - The number to format
     * @param {boolean} showSymbol - Whether to show 'Rp' prefix
     * @returns {string} Formatted currency string
     */
    format(value, showSymbol = true) {
        const num = this.parseNumber(value);

        if (isNaN(num)) return showSymbol ? 'Rp 0' : '0';

        const formatted = new Intl.NumberFormat(this.locale, {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(num);

        return showSymbol ? `Rp ${formatted}` : formatted;
    },

    /**
     * Format number with thousand separators (dots for Indonesian)
     * @param {number|string} value - The number to format
     * @returns {string} Formatted number string
     */
    formatNumber(value) {
        const num = this.parseNumber(value);
        if (isNaN(num)) return '0';
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    /**
     * Parse formatted string back to number
     * @param {string|number} value - The formatted string
     * @returns {number} Parsed number
     */
    parseNumber(value) {
        if (typeof value === 'number') return value;
        if (!value) return 0;

        // Remove all non-numeric characters except minus sign
        const cleanValue = value.toString()
            .replace(/[Rp\s]/gi, '')
            .replace(/\./g, '')
            .replace(/,/g, '');

        return parseInt(cleanValue, 10) || 0;
    },

    /**
     * Format for compact display (e.g., 1.5M, 500K)
     * @param {number|string} value - The number to format
     * @returns {string} Compact formatted string
     */
    formatCompact(value) {
        const num = this.parseNumber(value);

        if (num >= 1000000000) {
            return `Rp ${(num / 1000000000).toFixed(1)}B`;
        }
        if (num >= 1000000) {
            return `Rp ${(num / 1000000).toFixed(1)}M`;
        }
        if (num >= 1000) {
            return `Rp ${(num / 1000).toFixed(0)}K`;
        }

        return this.format(num);
    },

    /**
     * Initialize currency input with formatting
     * @param {HTMLInputElement} input - The input element
     * @param {HTMLInputElement} hiddenInput - Hidden input for actual value
     * @param {Object} options - Configuration options
     */
    initInput(input, hiddenInput = null, options = {}) {
        const {
            showPrefix = true,
            allowNegative = false,
            onValueChange = null
        } = options;

        // Set initial value if hidden input has value
        if (hiddenInput && hiddenInput.value) {
            input.value = this.formatNumber(hiddenInput.value);
        }

        // Handle input event
        input.addEventListener('input', (e) => {
            let value = this.parseNumber(e.target.value);

            if (!allowNegative && value < 0) {
                value = 0;
            }

            // Update display input
            if (value > 0) {
                e.target.value = this.formatNumber(value);
            } else {
                e.target.value = '';
            }

            // Update hidden input if provided
            if (hiddenInput) {
                hiddenInput.value = value;
            }

            // Callback
            if (onValueChange && typeof onValueChange === 'function') {
                onValueChange(value);
            }
        });

        // Handle blur event - ensure valid value
        input.addEventListener('blur', (e) => {
            if (!e.target.value) {
                e.target.value = '0';
                if (hiddenInput) {
                    hiddenInput.value = '0';
                }
            }
        });

        // Handle focus event - select all for easy editing
        input.addEventListener('focus', (e) => {
            if (e.target.value === '0') {
                e.target.select();
            }
        });

        // Prevent non-numeric input
        input.addEventListener('keypress', (e) => {
            const char = String.fromCharCode(e.which);
            if (!/[0-9]/.test(char) && !e.ctrlKey && !e.metaKey) {
                e.preventDefault();
            }
        });
    },

    /**
     * Auto-initialize all currency inputs on page
     * Looks for elements with data-currency="true" attribute
     */
    autoInit() {
        document.querySelectorAll('[data-currency="true"]').forEach(input => {
            const hiddenSelector = input.dataset.currencyHidden;
            const hiddenInput = hiddenSelector ? document.querySelector(hiddenSelector) : null;

            this.initInput(input, hiddenInput, {
                showPrefix: input.dataset.currencyPrefix !== 'false',
                allowNegative: input.dataset.currencyNegative === 'true'
            });
        });
    }
};

/**
 * Number utilities for general formatting
 */
const AdminNumber = {
    /**
     * Format number with thousand separators
     * @param {number|string} value - The number to format
     * @param {string} separator - Thousand separator (default: '.')
     * @returns {string} Formatted number
     */
    format(value, separator = '.') {
        const num = parseInt(value, 10);
        if (isNaN(num)) return '0';
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, separator);
    },

    /**
     * Parse formatted number back to integer
     * @param {string} value - Formatted number string
     * @returns {number} Parsed number
     */
    parse(value) {
        if (typeof value === 'number') return value;
        if (!value) return 0;
        return parseInt(value.toString().replace(/\D/g, ''), 10) || 0;
    },

    /**
     * Format as percentage
     * @param {number} value - The number to format
     * @param {number} decimals - Decimal places
     * @returns {string} Formatted percentage
     */
    formatPercent(value, decimals = 1) {
        return `${value.toFixed(decimals)}%`;
    },

    /**
     * Format as weight (grams/kilograms)
     * @param {number} grams - Weight in grams
     * @returns {string} Formatted weight
     */
    formatWeight(grams) {
        if (grams >= 1000) {
            return `${(grams / 1000).toFixed(1)} kg`;
        }
        return `${grams} gr`;
    },

    /**
     * Initialize number input with formatting
     * @param {HTMLInputElement} displayInput - Display input element
     * @param {HTMLInputElement} hiddenInput - Hidden input for actual value
     * @param {Object} options - Configuration options
     */
    initInput(displayInput, hiddenInput = null, options = {}) {
        const { suffix = '', min = 0, max = null } = options;

        // Set initial value
        if (hiddenInput && hiddenInput.value) {
            displayInput.value = this.format(hiddenInput.value);
        }

        displayInput.addEventListener('input', (e) => {
            let value = this.parse(e.target.value);

            // Apply constraints
            if (value < min) value = min;
            if (max !== null && value > max) value = max;

            // Update display
            e.target.value = value > 0 ? this.format(value) : '';

            // Update hidden input
            if (hiddenInput) {
                hiddenInput.value = value;
            }
        });

        displayInput.addEventListener('blur', (e) => {
            if (!e.target.value) {
                e.target.value = '0';
                if (hiddenInput) hiddenInput.value = '0';
            }
        });

        // Prevent non-numeric input
        displayInput.addEventListener('keypress', (e) => {
            const char = String.fromCharCode(e.which);
            if (!/[0-9]/.test(char) && !e.ctrlKey && !e.metaKey) {
                e.preventDefault();
            }
        });
    }
};

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    AdminCurrency.autoInit();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { AdminCurrency, AdminNumber };
}

// Make available globally
window.AdminCurrency = AdminCurrency;
window.AdminNumber = AdminNumber;
