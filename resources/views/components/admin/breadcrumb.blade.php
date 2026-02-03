{{--
    Admin Breadcrumb Component
    
    Usage:
    <x-admin.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Products', 'url' => route('admin.products.index')],
        ['label' => 'Create'],
    ]" />
--}}

@props([
    'items' => []
])

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center flex-wrap gap-2 text-sm">
        @foreach($items as $index => $item)
            @if($index > 0)
                <li class="flex items-center">
                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 16 16" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 011.06 0l3.25 3.25a.75.75 0 010 1.06l-3.25 3.25a.75.75 0 01-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 010-1.06z" clip-rule="evenodd" />
                    </svg>
                </li>
            @endif
            <li>
                @if(isset($item['url']) && $item['url'])
                    <a href="{{ $item['url'] }}" 
                       class="text-gray-500 dark:text-bodydark1 hover:text-admin-primary dark:hover:text-admin-primary-light transition-colors">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="font-medium text-admin-primary dark:text-white">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
