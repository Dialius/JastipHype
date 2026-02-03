@extends('admin.layouts.app')

@section('title', 'Revenue Analytics')

@section('page-title', 'Analytics')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Revenue Analytics</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track revenue performance and trends</p>
        </div>
        <button type="button" 
                onclick="document.getElementById('exportModal').classList.remove('hidden')"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
            Export Report
        </button>
    </div>

    {{-- Date Range Filter --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <form method="GET" action="{{ route('admin.analytics.revenue') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label for="start_date" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                <input type="date" 
                       id="start_date" 
                       name="start_date" 
                       value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
            </div>
            <div>
                <label for="end_date" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                <input type="date" 
                       id="end_date" 
                       name="end_date" 
                       value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}"
                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
            </div>
            <div class="flex items-end gap-2 sm:col-span-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                    Apply Filter
                </button>
                <a href="{{ route('admin.analytics.revenue') }}" class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Revenue Metrics Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-admin.metric-card 
            title="Today"
            :value="'Rp ' . number_format($analytics['today'], 0, ',', '.')"
            icon="dollar"
            trend="up"
            trendValue="Today"
        />
        <x-admin.metric-card 
            title="This Week"
            :value="'Rp ' . number_format($analytics['this_week'], 0, ',', '.')"
            icon="chart"
        />
        <x-admin.metric-card 
            title="This Month"
            :value="'Rp ' . number_format($analytics['this_month'], 0, ',', '.')"
            icon="chart"
        />
        <x-admin.metric-card 
            title="This Year"
            :value="'Rp ' . number_format($analytics['this_year'], 0, ',', '.')"
            icon="chart"
        />
    </div>

    {{-- Revenue Chart --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between px-5 py-4 md:px-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Revenue Trend</h3>
            <div class="flex gap-1 rounded-lg bg-gray-100 p-1 dark:bg-gray-800" x-data="{ period: 'daily' }">
                <button type="button" 
                        @click="period = 'daily'" 
                        :class="period === 'daily' ? 'bg-white text-gray-800 dark:bg-gray-700 dark:text-white/90 shadow-sm' : 'text-gray-600 dark:text-gray-400'"
                        class="rounded-md px-3 py-1.5 text-sm font-medium transition">
                    Daily
                </button>
                <button type="button" 
                        @click="period = 'weekly'" 
                        :class="period === 'weekly' ? 'bg-white text-gray-800 dark:bg-gray-700 dark:text-white/90 shadow-sm' : 'text-gray-600 dark:text-gray-400'"
                        class="rounded-md px-3 py-1.5 text-sm font-medium transition">
                    Weekly
                </button>
                <button type="button" 
                        @click="period = 'monthly'" 
                        :class="period === 'monthly' ? 'bg-white text-gray-800 dark:bg-gray-700 dark:text-white/90 shadow-sm' : 'text-gray-600 dark:text-gray-400'"
                        class="rounded-md px-3 py-1.5 text-sm font-medium transition">
                    Monthly
                </button>
            </div>
        </div>
        <div class="px-5 pb-5 md:px-6 md:pb-6">
            <div style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Selected Period Summary --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 py-4 md:px-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Selected Period Summary</h3>
        </div>
        <div class="border-t border-gray-100 px-5 py-5 dark:border-gray-800 md:px-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Period</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90">
                        {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90">
                        Rp {{ number_format($analytics['total'], 0, ',', '.') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Days</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90">
                        {{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} days
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Average per Day</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90">
                        Rp {{ number_format($analytics['total'] / max(1, \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1), 0, ',', '.') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

{{-- Export Modal --}}
<div id="exportModal" class="fixed inset-0 z-[99999] hidden overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="document.getElementById('exportModal').classList.add('hidden')"></div>
        <div class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Export Revenue Report</h3>
                <button onclick="document.getElementById('exportModal').classList.add('hidden')" class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.analytics.export') }}">
                @csrf
                <input type="hidden" name="type" value="revenue">
                <input type="hidden" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                <input type="hidden" name="end_date" value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                
                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Export Format</label>
                        <select name="format" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" required>
                            <option value="csv">CSV</option>
                            <option value="excel" disabled>Excel (Coming Soon)</option>
                            <option value="pdf" disabled>PDF (Coming Soon)</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')" class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
    const textColor = isDark ? 'rgba(255, 255, 255, 0.7)' : '#6B7280';

    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
                datasets: [{
                    label: 'Revenue',
                    data: [0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#465FFF',
                    backgroundColor: 'rgba(70, 95, 255, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
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
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
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
});
</script>
@endpush
