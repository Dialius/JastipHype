@props([
    'title' => 'Form Section',
    'description' => '',
    'icon' => 'info',
    'color' => 'blue'
])

@php
$colorClasses = [
    'blue' => [
        'gradient' => 'from-blue-50 to-indigo-50',
        'icon-bg' => 'bg-blue-600',
        'focus' => 'focus:border-blue-500 focus:ring-blue-500'
    ],
    'green' => [
        'gradient' => 'from-green-50 to-emerald-50',
        'icon-bg' => 'bg-green-600',
        'focus' => 'focus:border-green-500 focus:ring-green-500'
    ],
    'purple' => [
        'gradient' => 'from-purple-50 to-pink-50',
        'icon-bg' => 'bg-purple-600',
        'focus' => 'focus:border-purple-500 focus:ring-purple-500'
    ],
    'orange' => [
        'gradient' => 'from-orange-50 to-amber-50',
        'icon-bg' => 'bg-orange-600',
        'focus' => 'focus:border-orange-500 focus:ring-orange-500'
    ],
    'cyan' => [
        'gradient' => 'from-cyan-50 to-sky-50',
        'icon-bg' => 'bg-cyan-600',
        'focus' => 'focus:border-cyan-500 focus:ring-cyan-500'
    ],
    'red' => [
        'gradient' => 'from-red-50 to-rose-50',
        'icon-bg' => 'bg-red-600',
        'focus' => 'focus:border-red-500 focus:ring-red-500'
    ]
];

$icons = [
    'info' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />',
    'dollar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
    'image' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />',
    'tag' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />',
    'check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
    'cog' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
    'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />',
    'document' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />'
];

$classes = $colorClasses[$color] ?? $colorClasses['blue'];
$iconPath = $icons[$icon] ?? $icons['info'];
@endphp

<div class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-200">
    <div class="bg-gradient-to-r {{ $classes['gradient'] }} px-6 py-4 border-b border-gray-200">
        <div class="flex items-center">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $classes['icon-bg'] }}">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    {!! $iconPath !!}
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
                @if($description)
                <p class="text-sm text-gray-600">{{ $description }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
