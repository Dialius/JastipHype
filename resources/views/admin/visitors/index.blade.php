@extends('admin.layouts.app')

@section('title', 'Visitor Analytics')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Visitor Analytics</h1>
            <p class="text-gray-600 mt-1">Track website visitors and online users</p>
        </div>
        <div>
            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center gap-2" id="refreshBtn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Visitor Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-blue-600 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs text-blue-600 uppercase tracking-wide font-semibold mb-1">Visitors Today</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ number_format($statistics['today']) }}
                    </h3>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border-l-4 border-green-600 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs text-green-600 uppercase tracking-wide font-semibold mb-1">Visitors This Month</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ number_format($statistics['this_month']) }}
                    </h3>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border-l-4 border-cyan-600 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs text-cyan-600 uppercase tracking-wide font-semibold mb-1">Online Now</p>
                    <h3 class="text-2xl font-bold text-gray-900" id="onlineCount">
                        {{ number_format($onlineCount) }}
                    </h3>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-cyan-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8"></circle>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border-l-4 border-amber-600 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs text-amber-600 uppercase tracking-wide font-semibold mb-1">Page Views Today</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ number_format($statistics['total_page_views_today']) }}
                    </h3>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-amber-100 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitor Trends Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Visitor Trends</h2>
            <div class="inline-flex rounded-lg border border-gray-300 overflow-hidden">
                <input type="radio" class="hidden" name="trendPeriod" id="daily" value="daily" {{ $period === 'daily' ? 'checked' : '' }}>
                <label class="px-4 py-2 text-sm font-medium cursor-pointer {{ $period === 'daily' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}" for="daily">Daily</label>
                
                <input type="radio" class="hidden" name="trendPeriod" id="monthly" value="monthly" {{ $period === 'monthly' ? 'checked' : '' }}>
                <label class="px-4 py-2 text-sm font-medium cursor-pointer {{ $period === 'monthly' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}" for="monthly">Monthly</label>
            </div>
        </div>
        <div class="p-6">
            <canvas id="trendsChart" height="80"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Online Users Table -->
        <div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Online Users</h2>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full" id="onlineCountBadge">{{ $onlineCount }} online</span>
                </div>
                <div class="overflow-x-auto">
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="w-full text-sm" id="onlineUsersTable">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($onlineUsers as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-2 h-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="8"></circle>
                                            </svg>
                                            <span class="font-medium text-gray-900">{{ $user['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user['email'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($user['last_activity'])->diffForHumans() }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                        </svg>
                                        <p>No users online</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Views Statistics -->
        <div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Top Pages (Last 7 Days)</h2>
                </div>
                <div class="overflow-x-auto">
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pageViews as $page)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ $page['date'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ number_format($page['views'] ?? 0) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p>No page view data</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Prepare chart data
    const trends = @json($trends);
    const labels = trends.map(t => t.period);
    const visitors = trends.map(t => parseInt(t.unique_visitors));
    
    // Create chart
    const ctx = document.getElementById('trendsChart').getContext('2d');
    const trendsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Unique Visitors',
                data: visitors,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Period toggle functionality
    document.querySelectorAll('input[name="trendPeriod"]').forEach(radio => {
        radio.addEventListener('change', function() {
            window.location.href = '{{ route("admin.visitors.index") }}?period=' + this.value;
        });
    });

    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        location.reload();
    });

    // Auto-refresh online users every 30 seconds
    setInterval(function() {
        fetch('{{ route("admin.visitors.online-users") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('onlineCount').textContent = data.count.toLocaleString();
                document.getElementById('onlineCountBadge').textContent = data.count + ' online';
                
                // Update table
                const tbody = document.querySelector('#onlineUsersTable tbody');
                if (data.users.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                <p>No users online</p>
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = data.users.map(user => `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-2 h-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="10" cy="10" r="8"></circle>
                                    </svg>
                                    <span class="font-medium text-gray-900">${user.name}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">${user.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Just now</td>
                        </tr>
                    `).join('');
                }
            })
            .catch(error => console.error('Error fetching online users:', error));
    }, 30000); // 30 seconds
</script>
@endpush
@endsection
