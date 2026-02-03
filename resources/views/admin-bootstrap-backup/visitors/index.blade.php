@extends('admin.layouts.app')

@section('title', 'Visitor Analytics')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Visitor Analytics</h1>
            <p class="text-muted mb-0">Track website visitors and online users</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" id="refreshBtn">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Visitor Metrics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Visitors Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($statistics['today']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-check fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Visitors This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($statistics['this_month']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-month fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Online Now</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="onlineCount">
                                {{ number_format($onlineCount) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-circle-fill fs-2 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Page Views Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($statistics['total_page_views_today']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-eye fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitor Trends Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Visitor Trends</h6>
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" name="trendPeriod" id="daily" value="daily" {{ $period === 'daily' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary btn-sm" for="daily">Daily</label>
                
                <input type="radio" class="btn-check" name="trendPeriod" id="monthly" value="monthly" {{ $period === 'monthly' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary btn-sm" for="monthly">Monthly</label>
            </div>
        </div>
        <div class="card-body">
            <canvas id="trendsChart" height="80"></canvas>
        </div>
    </div>

    <div class="row">
        <!-- Online Users Table -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Online Users</h6>
                    <span class="badge bg-success" id="onlineCountBadge">{{ $onlineCount }} online</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm" id="onlineUsersTable">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Last Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($onlineUsers as $user)
                                <tr>
                                    <td>
                                        <i class="bi bi-circle-fill text-success me-2" style="font-size: 0.5rem;"></i>
                                        <strong>{{ $user['name'] }}</strong>
                                    </td>
                                    <td>{{ $user['email'] }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($user['last_activity'])->diffForHumans() }}
                                        </small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                                        No users online
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
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Pages (Last 7 Days)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Date</th>
                                    <th>Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pageViews as $page)
                                <tr>
                                    <td>
                                        <small>{{ $page['date'] ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ number_format($page['views'] ?? 0) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        No page view data
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

<style>
    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }
    .border-left-success {
        border-left: 4px solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 4px solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>

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
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
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
                            <td colspan="3" class="text-center text-muted py-3">
                                <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                                No users online
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = data.users.map(user => `
                        <tr>
                            <td>
                                <i class="bi bi-circle-fill text-success me-2" style="font-size: 0.5rem;"></i>
                                <strong>${user.name}</strong>
                            </td>
                            <td>${user.email}</td>
                            <td>
                                <small class="text-muted">Just now</small>
                            </td>
                        </tr>
                    `).join('');
                }
            })
            .catch(error => console.error('Error fetching online users:', error));
    }, 30000); // 30 seconds
</script>
@endpush
@endsection
