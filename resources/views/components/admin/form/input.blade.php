{{--
    Admin Form Input Component
    
    Usage:
    <x-admin.form.input 
        name="email" 
        label="Email Address" 
        type="email"
        placeholder="Enter your email"
        :error="$errors->first('email')"
        required
    />
--}}

@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'error' => null,
    'help' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'prefix' => null,
    'suffix' => null,
])

@php
    $hasIcon = !empty($icon);
    $hasPrefix = !empty($prefix);
    $hasSuffix = !empty($suffix);
    $hasError = !empty($error);
    
    $inputClasses = 'w-full rounded-lg border bg-transparent py-3 font-medium outline-none transition 
                     focus:border-admin-primary focus:ring-1 focus:ring-admin-primary 
                     disabled:cursor-not-allowed disabled:opacity-50
                     dark:border-form-strokedark dark:bg-boxdark dark:focus:border-admin-primary dark:text-white';
    
    $inputClasses .= $hasError 
        ? ' border-meta-1 text-meta-1 focus:border-meta-1 focus:ring-meta-1' 
        : ' border-gray-300 text-gray-900 dark:text-white';
    
    $inputClasses .= $hasIcon && $iconPosition === 'left' ? ' pl-11 pr-4' : '';
    $inputClasses .= $hasIcon && $iconPosition === 'right' ? ' pl-4 pr-11' : '';
    $inputClasses .= !$hasIcon && !$hasPrefix && !$hasSuffix ? ' px-4' : '';
    $inputClasses .= $hasPrefix ? ' pl-10' : '';
    $inputClasses .= $hasSuffix ? ' pr-10' : '';
@endphp

<div {{ $attributes->merge(['class' => 'mb-4.5']) }}>
    @if($label)
        <label for="{{ $name }}" class="mb-2.5 block text-sm font-medium text-gray-900 dark:text-white">
            {{ $label }}
            @if($required)
                <span class="text-meta-1">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($hasIcon && $iconPosition === 'left')
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                {!! $icon !!}
            </span>
        @endif
        
        @if($hasPrefix)
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-bodydark">
                {{ $prefix }}
            </span>
        @endif
        
        <input 
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            class="{{ $inputClasses }}"
            {{ $attributes->except(['class']) }}
        >
        
        @if($hasIcon && $iconPosition === 'right')
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                {!! $icon !!}
            </span>
        @endif
        
        @if($hasSuffix)
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-bodydark">
                {{ $suffix }}
            </span>
        @endif
    </div>
    
    @if($help && !$hasError)
        <p class="mt-1.5 text-xs text-gray-500 dark:text-bodydark2">{{ $help }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1.5 text-xs text-meta-1">{{ $error }}</p>
    @endif
</div>
