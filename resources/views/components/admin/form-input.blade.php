@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'help' => '',
    'prefix' => '',
    'suffix' => ''
])

<div>
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-semibold text-gray-900 mb-2">
        {{ $label }}
        @if($required)
        <span class="text-red-600">*</span>
        @endif
    </label>
    @endif
    
    <div class="relative">
        @if($prefix)
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <span class="text-gray-500 sm:text-sm">{{ $prefix }}</span>
        </div>
        @endif
        
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 sm:text-sm' . ($prefix ? ' pl-12' : '') . ($suffix ? ' pr-12' : '') . ($error ? ' border-red-300' : '')]) }}
        >
        
        @if($suffix)
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <span class="text-gray-500 sm:text-sm">{{ $suffix }}</span>
        </div>
        @endif
    </div>
    
    @if($error)
    <p class="mt-2 flex items-center text-sm text-red-600">
        <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        {{ $error }}
    </p>
    @endif
    
    @if($help && !$error)
    <p class="mt-2 text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>
