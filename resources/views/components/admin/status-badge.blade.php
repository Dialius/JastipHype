{{--
    Admin Status Badge Component
    
    Usage:
    <x-admin.status-badge status="success" label="Active" />
    <x-admin.status-badge status="pending" label="Pending" />
    <x-admin.status-badge status="danger" label="Cancelled" />
--}}

@props([
    'status' => 'default', // success, warning, danger, info, pending, default
    'label' => '',
    'size' => 'default', // small, default, large
    'dot' => false, // Show dot indicator
])

@php
    $statusClasses = [
        'success' => 'bg-meta-3/10 text-meta-3',
        'warning' => 'bg-meta-6/10 text-meta-6',
        'danger' => 'bg-meta-1/10 text-meta-1',
        'info' => 'bg-meta-5/10 text-meta-5',
        'pending' => 'bg-meta-8/10 text-meta-8',
        'default' => 'bg-gray-100 text-gray-600 dark:bg-meta-4 dark:text-bodydark',
    ];
    
    $dotClasses = [
        'success' => 'bg-meta-3',
        'warning' => 'bg-meta-6',
        'danger' => 'bg-meta-1',
        'info' => 'bg-meta-5',
        'pending' => 'bg-meta-8',
        'default' => 'bg-gray-400',
    ];
    
    $sizeClasses = [
        'small' => 'px-2 py-0.5 text-xs',
        'default' => 'px-2.5 py-1 text-xs',
        'large' => 'px-3 py-1.5 text-sm',
    ];
    
    $badgeClass = $statusClasses[$status] ?? $statusClasses['default'];
    $dotClass = $dotClasses[$status] ?? $dotClasses['default'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['default'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full font-medium {$badgeClass} {$sizeClass}"]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
    @endif
    {{ $label ?: $slot }}
</span>
