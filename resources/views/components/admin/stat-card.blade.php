{{--
    Admin Stat Card Component (TailAdmin Style)
    
    Usage:
    <x-admin.stat-card 
        title="Total Revenue"
        value="Rp 125.5M"
        trend="up"
        trendValue="+12.5%"
        color="primary"
        icon="dollar"
    />
--}}

@props([
    'title' => 'Metric',
    'value' => '0',
    'trend' => null, // 'up', 'down', or null
    'trendValue' => null,
    'trendLabel' => 'vs last month',
    'color' => 'primary', // primary, success, warning, danger, info
    'icon' => 'chart', // chart, dollar, users, shopping, eye, star, calendar
])

@php
    $colorClasses = [
        'primary' => [
            'bg' => 'bg-admin-primary/10',
            'text' => 'text-admin-primary',
            'fill' => 'fill-admin-primary',
        ],
        'success' => [
            'bg' => 'bg-meta-3/10',
            'text' => 'text-meta-3',
            'fill' => 'fill-meta-3',
        ],
        'warning' => [
            'bg' => 'bg-meta-6/10',
            'text' => 'text-meta-6',
            'fill' => 'fill-meta-6',
        ],
        'danger' => [
            'bg' => 'bg-meta-1/10',
            'text' => 'text-meta-1',
            'fill' => 'fill-meta-1',
        ],
        'info' => [
            'bg' => 'bg-meta-5/10',
            'text' => 'text-meta-5',
            'fill' => 'fill-meta-5',
        ],
    ];
    
    $icons = [
        'chart' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />',
        'dollar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />',
        'shopping' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />',
        'eye' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
        'star' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />',
        'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />',
    ];
    
    $classes = $colorClasses[$color] ?? $colorClasses['primary'];
    $iconPath = $icons[$icon] ?? $icons['chart'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 bg-white p-6 shadow-card dark:border-strokedark dark:bg-boxdark']) }}>
    <div class="flex items-center justify-between">
        <!-- Icon -->
        <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $classes['bg'] }}">
            <svg class="h-6 w-6 {{ $classes['text'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                {!! $iconPath !!}
            </svg>
        </div>
        
        <!-- Trend Indicator -->
        @if($trend && $trendValue)
            <div class="flex items-center gap-1 text-sm font-medium {{ $trend === 'up' ? 'text-meta-3' : 'text-meta-1' }}">
                @if($trend === 'up')
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                @else
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181" />
                    </svg>
                @endif
                <span>{{ $trendValue }}</span>
            </div>
        @endif
    </div>
    
    <div class="mt-4">
        <h4 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $value }}
        </h4>
        <p class="text-sm text-gray-500 dark:text-bodydark2 mt-1">
            {{ $title }}
        </p>
        @if($trend && $trendLabel)
            <p class="text-xs text-gray-400 dark:text-bodydark2 mt-1">
                {{ $trendLabel }}
            </p>
        @endif
    </div>
    
    @if(isset($slot) && $slot->isNotEmpty())
        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-strokedark">
            {{ $slot }}
        </div>
    @endif
</div>
