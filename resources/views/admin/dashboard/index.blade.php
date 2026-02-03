@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-5">
    {{-- Metrics Row 1 --}}
    <div class="col-span-12 space-y-4 xl:col-span-8">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {{-- Total Revenue --}}
            <x-admin.metric-card 
                title="Total Revenue"
                :value="'Rp ' . number_format(($revenue['this_month'] ?? 0) / 1000000, 1) . 'M'"
                icon="dollar"
                trend="up"
                trendValue="This Month"
            />

            {{-- Total Orders --}}
            <x-admin.metric-card 
                title="Total Orders"
                :value="$orders['total'] ?? 0"
                icon="order"
                :trendValue="($orders['pending'] ?? 0) . ' Pending'"
            />
        </div>
    </div>

    <div class="col-span-12 xl:col-span-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-1 md:gap-6">
            {{-- Total Customers --}}
            <x-admin.metric-card 
                title="Total Customers"
                :value="$customers['total'] ?? 0"
                icon="users"
                trend="up"
                :trendValue="($onlineUsers ?? 0) . ' Online'"
            />
        </div>
    </div>

    {{-- Metrics Row 2 --}}
    <div class="col-span-12">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 md:gap-6">
            {{-- Total Products --}}
            <x-admin.metric-card 
                title="Total Products"
                :value="$products['total'] ?? 0"
                icon="shopping"
                trend="down"
                :trendValue="($products['low_stock'] ?? 0) . ' Low Stock'"
            />

            {{-- Page Views --}}
            <x-admin.metric-card 
                title="Page Views"
                :value="number_format($visitorsToday ?? 0)"
                icon="eye"
                trend="up"
                trendValue="Today"
            />

            {{-- Completed Orders --}}
            <x-admin.metric-card 
                title="Completed Orders"
                :value="$orders['completed'] ?? 0"
                icon="star"
                trendValue="All Time"
            />
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="col-span-12 xl:col-span-7">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Revenue Trend</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Last 7 days revenue</p>
                </div>
            </div>
            <div style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Visitor Chart --}}
    <div class="col-span-12 xl:col-span-5">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Visitor Trend</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Last 7 days visitors</p>
                </div>
            </div>
            <div style="height: 300px;">
                <canvas id="visitorChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="col-span-12 xl:col-span-7">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 pt-5 md:px-6 md:pt-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Recent Orders</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        View all
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-y border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Order</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Customer</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Total</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($recentOrders as $order)
                        <tr>
                            <td class="px-5 py-4 md:px-6">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600 dark:text-white/90">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-4 md:px-6">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $order->user->name ?? 'Guest' }}</span>
                            </td>
                            <td class="px-5 py-4 md:px-6">
                                <span class="text-sm font-medium text-gray-800 dark:text-white/90">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-5 py-4 md:px-6">
                                @switch($order->status)
                                    @case('completed')
                                        <span class="inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">Completed</span>
                                        @break
                                    @case('processing')
                                        <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-500/15 dark:text-blue-500">Processing</span>
                                        @break
                                    @case('pending')
                                        <span class="inline-flex rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-medium text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-500">Pending</span>
                                        @break
                                    @case('cancelled')
                                        <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-600 dark:bg-red-500/15 dark:text-red-500">Cancelled</span>
                                        @break
                                    @default
                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ ucfirst($order->status) }}</span>
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400 md:px-6">
                                No recent orders
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Low Stock Products --}}
    <div class="col-span-12 xl:col-span-5">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 pt-5 md:px-6 md:pt-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Low Stock Products</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        View all
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-y border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Product</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">SKU</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Stock</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 md:px-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($lowStockProducts as $product)
                        <tr>
                            <td class="px-5 py-4 md:px-6">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600 dark:text-white/90">
                                    {{ Str::limit($product->name, 25) }}
                                </a>
                            </td>
                            <td class="px-5 py-4 md:px-6">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $product->sku }}</span>
                            </td>
                            <td class="px-5 py-4 md:px-6">
                                <span class="text-sm font-bold {{ $product->stock <= 5 ? 'text-red-600 dark:text-red-500' : 'text-yellow-600 dark:text-yellow-500' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-5 py-4 md:px-6">
                                @if($product->stock <= 5)
                                    <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-600 dark:bg-red-500/15 dark:text-red-500">Critical</span>
                                @else
                                    <span class="inline-flex rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-medium text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-500">Low</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400 md:px-6">
                                All products have sufficient stock
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js default dark mode colors
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
    const textColor = isDark ? 'rgba(255, 255, 255, 0.7)' : '#6B7280';

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($revenueChartData['labels']),
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: @json($revenueChartData['data']),
                    borderColor: '#465FFF',
                    backgroundColor: 'rgba(70, 95, 255, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#465FFF',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor,
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Visitor Chart
    const visitorCtx = document.getElementById('visitorChart');
    if (visitorCtx) {
        new Chart(visitorCtx, {
            type: 'bar',
            data: {
                labels: @json($visitorChartData['labels']),
                datasets: [{
                    label: 'Visitors',
                    data: @json($visitorChartData['data']),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 0,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor,
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
