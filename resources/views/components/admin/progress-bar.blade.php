{{--
    Admin Progress Bar Component
    
    Usage:
    <x-admin.progress-bar :value="75" color="success" label="Progress" showValue />
--}}

@props([
    'value' => 0, // 0-100
    'color' => 'primary', // primary, success, warning, danger, info
    'label' => '',
    'showValue' => false,
    'size' => 'default', // small, default, large
    'striped' => false,
    'animated' => false,
])

@php
    $value = min(100, max(0, $value));
    
    $colorClasses = [
        'primary' => 'bg-admin-primary',
        'success' => 'bg-meta-3',
        'warning' => 'bg-meta-6',
        'danger' => 'bg-meta-1',
        'info' => 'bg-meta-5',
    ];
    
    $sizeClasses = [
        'small' => 'h-1.5',
        'default' => 'h-2.5',
        'large' => 'h-4',
    ];
    
    $barColor = $colorClasses[$color] ?? $colorClasses['primary'];
    $barSize = $sizeClasses[$size] ?? $sizeClasses['default'];
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($label || $showValue)
        <div class="flex items-center justify-between mb-1.5">
            @if($label)
                <span class="text-sm font-medium text-gray-700 dark:text-bodydark">{{ $label }}</span>
            @endif
            @if($showValue)
                <span class="text-sm font-medium text-gray-700 dark:text-bodydark">{{ $value }}%</span>
            @endif
        </div>
    @endif
    
    <div class="w-full {{ $barSize }} bg-gray-200 rounded-full dark:bg-meta-4 overflow-hidden">
        <div 
            class="{{ $barColor }} {{ $barSize }} rounded-full transition-all duration-500 ease-out {{ $striped ? 'bg-stripes' : '' }} {{ $animated ? 'animate-stripes' : '' }}"
            style="width: {{ $value }}%"
        ></div>
    </div>
</div>

@once
@push('styles')
<style>
    .bg-stripes {
        background-image: linear-gradient(
            45deg,
            rgba(255, 255, 255, 0.15) 25%,
            transparent 25%,
            transparent 50%,
            rgba(255, 255, 255, 0.15) 50%,
            rgba(255, 255, 255, 0.15) 75%,
            transparent 75%,
            transparent
        );
        background-size: 1rem 1rem;
    }
    
    .animate-stripes {
        animation: stripes 1s linear infinite;
    }
    
    @keyframes stripes {
        0% { background-position: 1rem 0; }
        100% { background-position: 0 0; }
    }
</style>
@endpush
@endonce
