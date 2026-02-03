@extends('admin.layouts.app')

@section('title', 'Shipping Analytics')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Shipping Analytics</h1>
            <p class="mt-1 text-sm text-gray-500">Track shipping performance and courier usage</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.shipping.index') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Settings
            </a>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-6">
    <!-- Total Orders Shipped -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500">Total Orders Shipped</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($courierStats->sum('total_orders')) }}</p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Shipping Cost -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500">Total Shipping Cost</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($courierStats->sum('total_cost'), 0, ',', '.') }}</p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Average Shipping Cost -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500">Average Shipping Cost</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($avgShippingCost, 0, ',', '.') }}</p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="h-12 w-12 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <!-- Courier Usage Statistics -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
        <div class="border-b border-gray-200 bg-white px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Courier Usage Distribution</h2>
        </div>
        <div class="p-6">
            @if($courierStats->count() > 0)
                <div class="mb-6">
                    <canvas id="courierChart" height="300"></canvas>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Courier</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Orders</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Total Cost</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Avg Cost</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($courierStats as $stat)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">{{ strtoupper($stat->shipping_courier) }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-900">{{ number_format($stat->total_orders) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-900">Rp {{ number_format($stat->total_cost, 0, ',', '.') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-900">Rp {{ number_format($stat->total_cost / $stat->total_orders, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    <p class="mt-4 text-sm text-gray-500">No shipping data available</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Shipping Cost Distribution -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
        <div class="border-b border-gray-200 bg-white px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Shipping Cost Distribution</h2>
        </div>
        <div class="p-6">
            @if($costDistribution->count() > 0)
                <div class="mb-6">
                    <canvas id="costDistributionChart" height="300"></canvas>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cost Range</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Orders</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Percentage</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @php $totalOrders = $costDistribution->sum('count'); @endphp
                            @foreach($costDistribution as $dist)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $dist->range }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-900">{{ number_format($dist->count) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-900">{{ number_format(($dist->count / $totalOrders) * 100, 1) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    <p class="mt-4 text-sm text-gray-500">No cost distribution data</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Monthly Trends -->
<div class="mt-6 overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
    <div class="border-b border-gray-200 bg-white px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-900">Monthly Shipping Trends (Last 12 Months)</h2>
    </div>
    <div class="p-6">
        @if($monthlyTrends->count() > 0)
            <canvas id="monthlyTrendsChart" height="100"></canvas>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                <p class="mt-4 text-sm text-gray-500">No monthly trend data available</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Courier Usage Chart
    @if($courierStats->count() > 0)
    const courierCtx = document.getElementById('courierChart');
    if (courierCtx) {
        new Chart(courierCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($courierStats->pluck('shipping_courier')->map(fn($c) => strtoupper($c))) !!},
                datasets: [{
                    data: {!! json_encode($courierStats->pluck('total_orders')) !!},
                    backgroundColor: [
                        '#3b82f6', '#8b5cf6', '#ec4899', '#f43f5e', '#ef4444',
                        '#f97316', '#f59e0b', '#10b981', '#14b8a6', '#06b6d4'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    @endif

    // Cost Distribution Chart
    @if($costDistribution->count() > 0)
    const costDistCtx = document.getElementById('costDistributionChart');
    if (costDistCtx) {
        new Chart(costDistCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($costDistribution->pluck('range')) !!},
                datasets: [{
                    label: 'Number of Orders',
                    data: {!! json_encode($costDistribution->pluck('count')) !!},
                    backgroundColor: '#3b82f6'
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
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
    @endif

    // Monthly Trends Chart
    @if($monthlyTrends->count() > 0)
    const trendsCtx = document.getElementById('monthlyTrendsChart');
    if (trendsCtx) {
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyTrends->pluck('month')) !!},
                datasets: [
                    {
                        label: 'Total Orders',
                        data: {!! json_encode($monthlyTrends->pluck('total_orders')) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        yAxisID: 'y'
                    },
                    {
                        label: 'Average Cost (Rp)',
                        data: {!! json_encode($monthlyTrends->pluck('avg_cost')) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Orders'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Average Cost (Rp)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endpush
