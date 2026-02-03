{{--
    Admin Empty State Component
    
    Usage:
    <x-admin.empty-state 
        title="No products found"
        description="Get started by creating a new product."
        icon="box"
    >
        <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary">
            Add Product
        </a>
    </x-admin.empty-state>
--}}

@props([
    'title' => 'No data found',
    'description' => '',
    'icon' => 'box', // box, search, users, file, chart
    'size' => 'default', // small, default, large
])

@php
    $icons = [
        'box' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />',
        'search' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />',
        'file' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />',
        'chart' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />',
    ];
    
    $sizeClasses = [
        'small' => ['icon' => 'w-12 h-12', 'title' => 'text-base', 'desc' => 'text-sm', 'py' => 'py-8'],
        'default' => ['icon' => 'w-16 h-16', 'title' => 'text-lg', 'desc' => 'text-sm', 'py' => 'py-12'],
        'large' => ['icon' => 'w-20 h-20', 'title' => 'text-xl', 'desc' => 'text-base', 'py' => 'py-16'],
    ];
    
    $iconPath = $icons[$icon] ?? $icons['box'];
    $sizes = $sizeClasses[$size] ?? $sizeClasses['default'];
@endphp

<div {{ $attributes->merge(['class' => "flex flex-col items-center justify-center text-center {$sizes['py']}"]) }}>
    <div class="flex items-center justify-center {{ $sizes['icon'] }} rounded-full bg-gray-100 dark:bg-meta-4 mb-4">
        <svg class="w-1/2 h-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            {!! $iconPath !!}
        </svg>
    </div>
    
    <h3 class="{{ $sizes['title'] }} font-semibold text-gray-900 dark:text-white mb-1">
        {{ $title }}
    </h3>
    
    @if($description)
        <p class="{{ $sizes['desc'] }} text-gray-500 dark:text-bodydark2 mb-4 max-w-sm">
            {{ $description }}
        </p>
    @endif
    
    @if(isset($slot) && $slot->isNotEmpty())
        <div class="flex items-center gap-3 mt-2">
            {{ $slot }}
        </div>
    @endif
</div>
