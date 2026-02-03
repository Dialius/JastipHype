{{-- 
    JastipHype Admin Sidebar Content - TailAdmin Style
    Menu items with dropdown support
--}}

@php
    $currentPath = request()->path();
    
    $menuGroups = [
        [
            'title' => 'Menu',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'icon' => 'dashboard',
                    'path' => route('admin.dashboard'),
                    'active' => request()->routeIs('admin.dashboard')
                ]
            ]
        ],
        [
            'title' => 'Catalog',
            'items' => [
                [
                    'name' => 'Products',
                    'icon' => 'product',
                    'path' => route('admin.products.index'),
                    'active' => request()->routeIs('admin.products.*')
                ],
                [
                    'name' => 'Brands',
                    'icon' => 'brand',
                    'path' => route('admin.brands.index'),
                    'active' => request()->routeIs('admin.brands.*')
                ],
                [
                    'name' => 'Categories',
                    'icon' => 'category',
                    'path' => route('admin.categories.index'),
                    'active' => request()->routeIs('admin.categories.*')
                ]
            ]
        ],
        [
            'title' => 'Sales',
            'items' => [
                [
                    'name' => 'Orders',
                    'icon' => 'order',
                    'path' => route('admin.orders.index'),
                    'active' => request()->routeIs('admin.orders.*')
                ],
                [
                    'name' => 'Customers',
                    'icon' => 'users',
                    'path' => route('admin.customers.index'),
                    'active' => request()->routeIs('admin.customers.*')
                ],
                [
                    'name' => 'Reviews',
                    'icon' => 'review',
                    'path' => route('admin.reviews.index'),
                    'active' => request()->routeIs('admin.reviews.*')
                ]
            ]
        ],
        [
            'title' => 'Marketing',
            'items' => [
                [
                    'name' => 'Banners',
                    'icon' => 'banner',
                    'path' => route('admin.banners.index'),
                    'active' => request()->routeIs('admin.banners.*')
                ],
                [
                    'name' => 'Discounts',
                    'icon' => 'discount',
                    'path' => route('admin.discounts.index'),
                    'active' => request()->routeIs('admin.discounts.*')
                ]
            ]
        ],
        [
            'title' => 'Support',
            'items' => [
                [
                    'name' => 'Support Tickets',
                    'icon' => 'ticket',
                    'path' => route('admin.support.index'),
                    'active' => request()->routeIs('admin.support.*') && !request()->routeIs('admin.support.chat')
                ],
                [
                    'name' => 'Live Chat',
                    'icon' => 'chat',
                    'path' => route('admin.support.chat'),
                    'active' => request()->routeIs('admin.support.chat')
                ]
            ]
        ],
        [
            'title' => 'System',
            'items' => [
                [
                    'name' => 'Visitors',
                    'icon' => 'chart',
                    'path' => route('admin.visitors.index'),
                    'active' => request()->routeIs('admin.visitors.*')
                ],
                [
                    'name' => 'System Monitor',
                    'icon' => 'settings',
                    'path' => route('admin.system.index'),
                    'active' => request()->routeIs('admin.system.*')
                ],
                [
                    'name' => 'Settings',
                    'icon' => 'settings',
                    'path' => route('admin.settings.index'),
                    'active' => request()->routeIs('admin.settings.*')
                ]
            ]
        ]
    ];
    
    $icons = [
        'dashboard' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />',
        'product' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />',
        'brand' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />',
        'category' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />',
        'order' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />',
        'review' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />',
        'banner' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />',
        'discount' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />',
        'ticket' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />',
        'chat' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />',
        'chart' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />',
        'settings' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />'
    ];
@endphp

@foreach ($menuGroups as $groupIndex => $menuGroup)
    <div>
        <!-- Menu Group Title -->
        <h2 class="mb-4 text-xs uppercase flex leading-5 text-gray-400 dark:text-gray-500"
            :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
            <template x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                <span>{{ $menuGroup['title'] }}</span>
            </template>
            <template x-if="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="6" cy="12" r="1.5"/>
                    <circle cx="12" cy="12" r="1.5"/>
                    <circle cx="18" cy="12" r="1.5"/>
                </svg>
            </template>
        </h2>

        <!-- Menu Items -->
        <ul class="flex flex-col gap-1">
            @foreach ($menuGroup['items'] as $itemIndex => $item)
                <li x-data="{ open: {{ isset($item['subItems']) && collect($item['subItems'])->contains('active', true) ? 'true' : 'false' }} }">
                    @if (isset($item['subItems']))
                        <!-- Menu Item with Submenu -->
                        <button @click="open = !open"
                            class="group flex items-center w-full gap-3 px-3 py-2 font-medium rounded-lg text-sm transition-colors duration-200"
                            :class="open ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5'">
                            
                            <!-- Icon -->
                            <span :class="open ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 group-hover:text-gray-700 dark:text-gray-400'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    {!! $icons[$item['icon']] ?? '' !!}
                                </svg>
                            </span>

                            <!-- Text -->
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                  class="flex-1 text-left flex items-center gap-2">
                                {{ $item['name'] }}
                                @if (!empty($item['new']))
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full uppercase"
                                          :class="open ? 'bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400' : 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400'">
                                        new
                                    </span>
                                @endif
                            </span>

                            <!-- Chevron -->
                            <svg x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                 class="w-5 h-5 transition-transform duration-200"
                                 :class="{ 'rotate-180 text-blue-600 dark:text-blue-400': open }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Submenu -->
                        <div x-show="open && ($store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen)"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             style="display: none;">
                            <ul class="mt-2 space-y-1 ml-9">
                                @foreach ($item['subItems'] as $subItem)
                                    <li>
                                        <a href="{{ $subItem['path'] }}" 
                                           class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors duration-200
                                                  {{ $subItem['active'] ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5' }}">
                                            {{ $subItem['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <!-- Simple Menu Item -->
                        <a href="{{ $item['path'] }}" 
                           class="group flex items-center gap-3 px-3 py-2 font-medium rounded-lg text-sm transition-colors duration-200
                                  {{ $item['active'] ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5' }}"
                           :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
                            
                            <!-- Icon -->
                            <span class="{{ $item['active'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 group-hover:text-gray-700 dark:text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    {!! $icons[$item['icon']] ?? '' !!}
                                </svg>
                            </span>

                            <!-- Text -->
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                  class="whitespace-nowrap">
                                {{ $item['name'] }}
                            </span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endforeach
