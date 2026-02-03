{{--
    Admin Form Toggle/Switch Component
    
    Usage:
    <x-admin.form.toggle 
        name="is_active" 
        label="Active Status"
        :checked="$product->is_active"
    />
--}}

@props([
    'name' => '',
    'label' => '',
    'checked' => false,
    'error' => null,
    'help' => null,
    'disabled' => false,
    'size' => 'default', // small, default, large
])

@php
    $hasError = !empty($error);
    $isChecked = old($name) !== null ? (bool) old($name) : (bool) $checked;
    
    $sizeClasses = [
        'small' => ['toggle' => 'h-5 w-10', 'dot' => 'h-4 w-4', 'translate' => 'peer-checked:translate-x-5'],
        'default' => ['toggle' => 'h-6 w-12', 'dot' => 'h-5 w-5', 'translate' => 'peer-checked:translate-x-6'],
        'large' => ['toggle' => 'h-7 w-14', 'dot' => 'h-6 w-6', 'translate' => 'peer-checked:translate-x-7'],
    ];
    $sizes = $sizeClasses[$size] ?? $sizeClasses['default'];
@endphp

<div {{ $attributes->merge(['class' => 'mb-4.5']) }}>
    <label class="flex items-center gap-3 cursor-pointer select-none">
        <div class="relative">
            <input 
                type="checkbox"
                name="{{ $name }}"
                id="{{ $name }}"
                value="1"
                @if($isChecked) checked @endif
                @if($disabled) disabled @endif
                class="peer sr-only"
            >
            <div class="{{ $sizes['toggle'] }} rounded-full bg-gray-200 dark:bg-meta-4 peer-checked:bg-admin-primary peer-disabled:opacity-50 peer-disabled:cursor-not-allowed transition-colors"></div>
            <div class="{{ $sizes['dot'] }} {{ $sizes['translate'] }} absolute left-0.5 top-1/2 -translate-y-1/2 rounded-full bg-white shadow-md transition-transform"></div>
        </div>
        
        @if($label)
            <span class="text-sm font-medium text-gray-900 dark:text-white @if($disabled) opacity-50 @endif">
                {{ $label }}
            </span>
        @endif
    </label>
    
    @if($help && !$hasError)
        <p class="mt-1.5 text-xs text-gray-500 dark:text-bodydark2 ml-15">{{ $help }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1.5 text-xs text-meta-1 ml-15">{{ $error }}</p>
    @endif
</div>
