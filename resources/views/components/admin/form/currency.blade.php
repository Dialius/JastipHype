{{--
    Admin Currency Input Component (Rupiah)
    
    Usage:
    <x-admin.form.currency 
        name="price" 
        label="Price"
        :value="$product->price ?? 0"
        placeholder="0"
        :error="$errors->first('price')"
        required
    />
--}}

@props([
    'name' => '',
    'label' => '',
    'value' => 0,
    'placeholder' => '0',
    'error' => null,
    'help' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'min' => 0,
    'max' => null,
])

@php
    $hasError = !empty($error);
    $displayId = $name . '_display';
    $oldValue = old($name, $value);
    
    $inputClasses = 'w-full rounded-lg border bg-transparent py-3 pl-12 pr-4 font-medium outline-none transition 
                     focus:border-admin-primary focus:ring-1 focus:ring-admin-primary 
                     disabled:cursor-not-allowed disabled:opacity-50
                     dark:border-form-strokedark dark:bg-boxdark dark:focus:border-admin-primary dark:text-white';
    
    $inputClasses .= $hasError 
        ? ' border-meta-1 text-meta-1 focus:border-meta-1 focus:ring-meta-1' 
        : ' border-gray-300 text-gray-900 dark:text-white';
@endphp

<div {{ $attributes->merge(['class' => 'mb-4.5']) }}>
    @if($label)
        <label for="{{ $displayId }}" class="mb-2.5 block text-sm font-medium text-gray-900 dark:text-white">
            {{ $label }}
            @if($required)
                <span class="text-meta-1">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        <!-- Currency Prefix -->
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-bodydark font-medium">
            Rp
        </span>
        
        <!-- Display Input (formatted) -->
        <input 
            type="text"
            id="{{ $displayId }}"
            placeholder="{{ $placeholder }}"
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            class="{{ $inputClasses }}"
            data-currency="true"
            data-currency-hidden="#{{ $name }}"
            autocomplete="off"
        >
        
        <!-- Hidden Input (actual value) -->
        <input 
            type="hidden"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $oldValue }}"
        >
    </div>
    
    @if($help && !$hasError)
        <p class="mt-1.5 text-xs text-gray-500 dark:text-bodydark2">{{ $help }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1.5 text-xs text-meta-1">{{ $error }}</p>
    @endif
</div>

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all currency inputs
        document.querySelectorAll('[data-currency="true"]').forEach(function(displayInput) {
            const hiddenSelector = displayInput.dataset.currencyHidden;
            const hiddenInput = document.querySelector(hiddenSelector);
            
            if (!hiddenInput) return;
            
            // Format number with thousand separators
            function formatNumber(value) {
                const num = parseInt(value, 10);
                if (isNaN(num)) return '';
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            
            // Parse formatted string to number
            function parseNumber(value) {
                if (!value) return 0;
                return parseInt(value.toString().replace(/\./g, ''), 10) || 0;
            }
            
            // Set initial value
            if (hiddenInput.value && parseInt(hiddenInput.value, 10) > 0) {
                displayInput.value = formatNumber(hiddenInput.value);
            }
            
            // Handle input event
            displayInput.addEventListener('input', function(e) {
                let value = parseNumber(e.target.value);
                
                // Apply constraints
                @if($min !== null)
                if (value < {{ $min }}) value = {{ $min }};
                @endif
                @if($max !== null)
                if (value > {{ $max }}) value = {{ $max }};
                @endif
                
                // Update display
                if (value > 0) {
                    e.target.value = formatNumber(value);
                } else {
                    e.target.value = '';
                }
                
                // Update hidden input
                hiddenInput.value = value;
            });
            
            // Handle blur - ensure valid value
            displayInput.addEventListener('blur', function(e) {
                if (!e.target.value) {
                    e.target.value = '0';
                    hiddenInput.value = '0';
                }
            });
            
            // Prevent non-numeric input
            displayInput.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which);
                if (!/[0-9]/.test(char) && !e.ctrlKey && !e.metaKey) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
@endonce
