{{--
    Admin Page Header Component
    
    Usage:
    <x-admin.page-header 
        title="Products" 
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Products'],
        ]"
    >
        <x-slot name="actions">
            <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary">
                Add Product
            </a>
        </x-slot>
    </x-admin.page-header>
--}}

@props([
    'title' => 'Page Title',
    'subtitle' => null,
    'breadcrumbs' => [],
])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    <!-- Breadcrumb -->
    @if(count($breadcrumbs) > 0)
        <x-admin.breadcrumb :items="$breadcrumbs" />
    @endif
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="mt-1 text-sm text-gray-500 dark:text-bodydark2">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
        
        @if(isset($actions))
            <div class="flex items-center gap-3">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
