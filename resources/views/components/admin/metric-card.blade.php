{{--
    TailAdmin-Style Metric Card Component
    
    Usage:
    <x-admin.metric-card 
        title="Customers"
        value="3,782"
        icon="users"
        trend="up"
        trendValue="11.01%"
    />
--}}

@props([
    'title' => 'Metric',
    'value' => '0',
    'icon' => 'chart',
    'trend' => null, // 'up' or 'down'
    'trendValue' => null,
    'prefix' => '',
    'suffix' => ''
])

@php
$iconPaths = [
    'dollar' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.75C12.4142 3.75 12.75 4.08579 12.75 4.5V5.07C14.28 5.3 15.5 6.26 15.5 7.5C15.5 7.91 15.16 8.25 14.75 8.25S14 7.91 14 7.5C14 7.1 13.39 6.61 12.75 6.53V10.43C14.28 10.65 15.5 11.62 15.5 12.86C15.5 14.1 14.28 15.06 12.75 15.28V16.5C12.75 16.91 12.41 17.25 12 17.25S11.25 16.91 11.25 16.5V15.28C9.72 15.06 8.5 14.1 8.5 12.86C8.5 12.44 8.84 12.1 9.25 12.1S10 12.44 10 12.86C10 13.25 10.61 13.75 11.25 13.83V9.93C9.72 9.7 8.5 8.74 8.5 7.5C8.5 6.26 9.72 5.3 11.25 5.07V4.5C11.25 4.09 11.59 3.75 12 3.75ZM11.25 6.53C10.61 6.61 10 7.1 10 7.5S10.61 8.39 11.25 8.47V6.53ZM12.75 11.88V13.83C13.39 13.75 14 13.25 14 12.86C14 12.46 13.39 12.02 12.75 11.88Z"/>',
    'shopping' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 6.5C5.5 4.01 7.51 2 10 2C12.49 2 14.5 4.01 14.5 6.5V7H15C16.1 7 17 7.9 17 9V16C17 18.21 15.21 20 13 20H7C4.79 20 3 18.21 3 16V9C3 7.9 3.9 7 5 7H5.5V6.5ZM7 7H13V6.5C13 4.84 11.66 3.5 10 3.5C8.34 3.5 7 4.84 7 6.5V7ZM5 8.5C4.72 8.5 4.5 8.72 4.5 9V16C4.5 17.38 5.62 18.5 7 18.5H13C14.38 18.5 15.5 17.38 15.5 16V9C15.5 8.72 15.28 8.5 15 8.5H5Z"/>',
    'users' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M8.8 5.6C7.59 5.6 6.61 6.59 6.61 7.8C6.61 9.01 7.59 10 8.8 10C10.02 10 11 9.01 11 7.8C11 6.59 10.02 5.6 8.8 5.6ZM5.11 7.8C5.11 5.76 6.76 4.1 8.8 4.1C10.85 4.1 12.5 5.76 12.5 7.8C12.5 9.84 10.85 11.5 8.8 11.5C6.76 11.5 5.11 9.84 5.11 7.8ZM4.86 15.32C4.09 16.09 3.7 17.06 3.52 17.86C3.48 18 3.52 18.12 3.61 18.21C3.7 18.31 3.87 18.4 4.08 18.4H13.42C13.63 18.4 13.8 18.31 13.89 18.21C13.98 18.12 14.02 18 13.98 17.86C13.8 17.06 13.41 16.09 12.64 15.32C11.88 14.57 10.69 13.96 8.75 13.96C6.81 13.96 5.62 14.57 4.86 15.32ZM3.81 14.25C4.87 13.2 6.46 12.46 8.75 12.46C11.04 12.46 12.63 13.2 13.69 14.25C14.74 15.29 15.22 16.56 15.44 17.52C15.77 18.9 14.61 19.9 13.42 19.9H4.08C2.89 19.9 1.74 18.9 2.06 17.52C2.28 16.56 2.76 15.29 3.81 14.25Z"/>',
    'chart' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 4C4.66 4 5 4.34 5 4.75V17.25C5 17.66 4.66 18 4.25 18C3.84 18 3.5 17.66 3.5 17.25V4.75C3.5 4.34 3.84 4 4.25 4ZM8.75 9C9.16 9 9.5 9.34 9.5 9.75V17.25C9.5 17.66 9.16 18 8.75 18C8.34 18 8 17.66 8 17.25V9.75C8 9.34 8.34 9 8.75 9ZM14 6.75C14 6.34 13.66 6 13.25 6C12.84 6 12.5 6.34 12.5 6.75V17.25C12.5 17.66 12.84 18 13.25 18C13.66 18 14 17.66 14 17.25V6.75ZM17.75 11C18.16 11 18.5 11.34 18.5 11.75V17.25C18.5 17.66 18.16 18 17.75 18C17.34 18 17 17.66 17 17.25V11.75C17 11.34 17.34 11 17.75 11Z"/>',
    'eye' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M12 5.25C7.5 5.25 3.75 8.25 2.25 12C3.75 15.75 7.5 18.75 12 18.75C16.5 18.75 20.25 15.75 21.75 12C20.25 8.25 16.5 5.25 12 5.25ZM12 16.5C9.5 16.5 7.5 14.5 7.5 12C7.5 9.5 9.5 7.5 12 7.5C14.5 7.5 16.5 9.5 16.5 12C16.5 14.5 14.5 16.5 12 16.5ZM12 9.75C10.75 9.75 9.75 10.75 9.75 12C9.75 13.25 10.75 14.25 12 14.25C13.25 14.25 14.25 13.25 14.25 12C14.25 10.75 13.25 9.75 12 9.75Z"/>',
    'star' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2L14.9 8.6L22 9.3L16.8 14.1L18.2 21.2L12 17.8L5.8 21.2L7.2 14.1L2 9.3L9.1 8.6L12 2Z"/>',
    'order' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M11.67 3.76C11.88 3.65 12.12 3.65 12.34 3.76L18.78 6.98L12.34 10.2C12.12 10.31 11.88 10.31 11.67 10.2L5.22 6.98L11.67 3.76ZM4.29 8.19V16.09C4.29 16.38 4.45 16.64 4.71 16.77L11.25 20.04V11.65C11.16 11.62 11.08 11.58 10.99 11.54L4.29 8.19ZM12.75 20.04L19.29 16.77C19.55 16.64 19.71 16.38 19.71 16.09V8.19L13.01 11.54C12.92 11.58 12.84 11.62 12.75 11.65V20.04Z"/>',
    'product' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M6 3C4.34 3 3 4.34 3 6V18C3 19.66 4.34 21 6 21H18C19.66 21 21 19.66 21 18V6C21 4.34 19.66 3 18 3H6ZM4.5 6C4.5 5.17 5.17 4.5 6 4.5H18C18.83 4.5 19.5 5.17 19.5 6V18C19.5 18.83 18.83 19.5 18 19.5H6C5.17 19.5 4.5 18.83 4.5 18V6ZM8 9C8 8.45 8.45 8 9 8H15C15.55 8 16 8.45 16 9C16 9.55 15.55 10 15 10H9C8.45 10 8 9.55 8 9Z"/>'
];

$selectedIcon = $iconPaths[$icon] ?? $iconPaths['chart'];
@endphp

<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
    {{-- Icon Container - Gray background as per TailAdmin --}}
    <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
        <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            {!! $selectedIcon !!}
        </svg>
    </div>

    {{-- Content --}}
    <div class="flex items-end justify-between mt-5">
        <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $title }}</span>
            <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ $prefix }}{{ $value }}{{ $suffix }}
            </h4>
        </div>

        @if($trend && $trendValue)
            @if($trend === 'up')
                <span class="flex items-center gap-1 rounded-full bg-green-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-green-600 dark:bg-green-500/15 dark:text-green-400">
                    <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.56 1.62C5.7 1.47 5.9 1.37 6.12 1.37C6.32 1.37 6.51 1.45 6.66 1.59L9.66 4.59C9.95 4.88 9.95 5.36 9.66 5.65C9.36 5.95 8.89 5.95 8.59 5.65L6.87 3.93V10.13C6.87 10.54 6.54 10.88 6.12 10.88C5.71 10.88 5.37 10.54 5.37 10.13V3.94L3.66 5.65C3.36 5.95 2.89 5.95 2.59 5.65C2.3 5.36 2.3 4.88 2.59 4.59L5.56 1.62Z"/>
                    </svg>
                    {{ $trendValue }}
                </span>
            @else
                <span class="flex items-center gap-1 rounded-full bg-red-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-red-600 dark:bg-red-500/15 dark:text-red-400">
                    <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.31 10.38C5.45 10.53 5.65 10.63 5.87 10.63C6.07 10.63 6.26 10.55 6.41 10.41L9.41 7.41C9.7 7.12 9.7 6.64 9.41 6.35C9.11 6.05 8.64 6.05 8.34 6.35L6.62 8.07V1.88C6.62 1.46 6.29 1.13 5.87 1.13C5.46 1.13 5.12 1.46 5.12 1.88V8.06L3.41 6.35C3.11 6.05 2.64 6.05 2.34 6.35C2.05 6.64 2.05 7.12 2.34 7.41L5.31 10.38Z"/>
                    </svg>
                    {{ $trendValue }}
                </span>
            @endif
        @elseif($trendValue)
            <span class="rounded-full bg-gray-100 py-0.5 px-2.5 text-sm font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                {{ $trendValue }}
            </span>
        @endif
    </div>
</div>
