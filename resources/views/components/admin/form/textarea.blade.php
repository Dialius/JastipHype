{{--
    Admin Form Textarea Component
    
    Usage:
    <x-admin.form.textarea 
        name="description" 
        label="Description"
        :value="$product->description ?? ''"
        placeholder="Enter description"
        rows="4"
        :error="$errors->first('description')"
    />
--}}

@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'rows' => 4,
    'error' => null,
    'help' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'maxlength' => null,
    'showCount' => false,
])

@php
    $hasError = !empty($error);
    
    $textareaClasses = 'w-full rounded-lg border bg-transparent py-3 px-4 font-medium outline-none transition resize-y
                        focus:border-admin-primary focus:ring-1 focus:ring-admin-primary 
                        disabled:cursor-not-allowed disabled:opacity-50
                        dark:border-form-strokedark dark:bg-boxdark dark:focus:border-admin-primary dark:text-white';
    
    $textareaClasses .= $hasError 
        ? ' border-meta-1 text-meta-1 focus:border-meta-1 focus:ring-meta-1' 
        : ' border-gray-300 text-gray-900 dark:text-white';
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
    
    <div class="relative" @if($showCount && $maxlength) x-data="{ count: {{ strlen($value) }} }" @endif>
        <textarea 
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($maxlength) maxlength="{{ $maxlength }}" @endif
            @if($showCount && $maxlength) x-on:input="count = $el.value.length" @endif
            class="{{ $textareaClasses }}"
        >{{ old($name, $value) }}</textarea>
        
        @if($showCount && $maxlength)
            <span class="absolute bottom-3 right-3 text-xs text-gray-400 dark:text-bodydark2" x-text="count + '/{{ $maxlength }}'"></span>
        @endif
    </div>
    
    @if($help && !$hasError)
        <p class="mt-1.5 text-xs text-gray-500 dark:text-bodydark2">{{ $help }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1.5 text-xs text-meta-1">{{ $error }}</p>
    @endif
</div>
