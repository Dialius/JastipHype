{{--
    Admin Card Component
    
    Usage:
    <x-admin.card title="Recent Orders">
        <x-slot name="actions">
            <a href="#" class="text-sm text-admin-primary">View all</a>
        </x-slot>
        
        <!-- Card content -->
    </x-admin.card>
--}}

@props([
    'title' => '',
    'subtitle' => '',
    'padding' => true,
    'shadow' => true,
])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 bg-white dark:border-strokedark dark:bg-boxdark' . ($shadow ? ' shadow-card' : '')]) }}>
    @if($title || isset($actions))
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-strokedark px-6 py-4">
            <div>
                @if($title)
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ $title }}
                    </h3>
                @endif
                @if($subtitle)
                    <p class="text-sm text-gray-500 dark:text-bodydark2 mt-0.5">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
            
            @if(isset($actions))
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    
    <div @class([
        'px-6 py-5' => $padding,
    ])>
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="border-t border-gray-200 dark:border-strokedark px-6 py-4">
            {{ $footer }}
        </div>
    @endif
</div>
