{{--
    Admin Button Component
    
    Usage:
    <x-admin.button type="submit" variant="primary">Save</x-admin.button>
    <x-admin.button variant="danger" icon="trash">Delete</x-admin.button>
    <x-admin.button variant="outline" href="{{ route('admin.products.index') }}">Cancel</x-admin.button>
--}}

@props([
    'variant' => 'primary', // primary, secondary, success, warning, danger, outline, ghost, link
    'size' => 'default', // small, default, large
    'type' => 'button',
    'href' => null,
    'icon' => null, // plus, edit, trash, save, cancel, download, upload, view
    'iconPosition' => 'left',
    'loading' => false,
    'disabled' => false,
    'fullWidth' => false,
])

@php
    $variantClasses = [
        'primary' => 'bg-admin-primary text-white hover:bg-admin-primary-dark border border-admin-primary',
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200 dark:bg-meta-4 dark:text-bodydark dark:hover:bg-strokedark dark:border-strokedark',
        'success' => 'bg-meta-3 text-white hover:bg-meta-3/90 border border-meta-3',
        'warning' => 'bg-meta-6 text-white hover:bg-meta-6/90 border border-meta-6',
        'danger' => 'bg-meta-1 text-white hover:bg-meta-1/90 border border-meta-1',
        'outline' => 'bg-transparent text-admin-primary border border-admin-primary hover:bg-admin-primary hover:text-white',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 dark:text-bodydark dark:hover:bg-meta-4 border border-transparent',
        'link' => 'bg-transparent text-admin-primary hover:underline border-0 p-0 shadow-none',
    ];
    
    $sizeClasses = [
        'small' => 'px-3 py-1.5 text-xs',
        'default' => 'px-5 py-2.5 text-sm',
        'large' => 'px-6 py-3 text-base',
    ];
    
    $icons = [
        'plus' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />',
        'edit' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />',
        'trash' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />',
        'save' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />',
        'cancel' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />',
        'download' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />',
        'upload' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />',
        'view' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
        'check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />',
        'arrow-left' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />',
    ];
    
    $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-admin-primary/50 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm';
    $variantClass = $variantClasses[$variant] ?? $variantClasses['primary'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['default'];
    $widthClass = $fullWidth ? 'w-full' : '';
    
    $iconPath = $icon ? ($icons[$icon] ?? null) : null;
    
    $classes = "$baseClasses $variantClass $sizeClass $widthClass";
    
    if ($variant === 'link') {
        $classes = 'inline-flex items-center gap-2 text-admin-primary hover:underline font-medium transition';
    }
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($iconPath && $iconPosition === 'left')
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                {!! $iconPath !!}
            </svg>
        @endif
        
        {{ $slot }}
        
        @if($iconPath && $iconPosition === 'right')
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                {!! $iconPath !!}
            </svg>
        @endif
    </a>
@else
    <button 
        type="{{ $type }}"
        @if($disabled || $loading) disabled @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($loading)
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($iconPath && $iconPosition === 'left')
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                {!! $iconPath !!}
            </svg>
        @endif
        
        {{ $slot }}
        
        @if($iconPath && $iconPosition === 'right' && !$loading)
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                {!! $iconPath !!}
            </svg>
        @endif
    </button>
@endif
