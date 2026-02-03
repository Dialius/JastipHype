@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Dashboard</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
    <div>
        <button class="btn btn-sm btn-outline-secondary me-2">
            <i class="bi bi-share"></i> Share
        </button>
        <button class="btn btn-sm btn-outline-secondary me-2">
            <i class="bi bi-printer"></i> Print
        </button>
        <button class="btn btn-sm btn-primary">
            <i class="bi bi-download"></i> Export
        </button>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <!-- Total Revenue -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card-compact">
            <div class="card-body p-3">
                <p class="text-muted mb-1" style="font-size: 0.75rem;">Total Revenue</p>
                <h3 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">Rp {{ number_format(($revenue['this_month'] ?? 0) / 1000000, 1) }}jt</h3>
                <small class="text-success">
                    <i class="bi bi-arrow-up"></i> This Month
                </small>
            </div>
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card-compact">
            <div class="card-body p-3">
                <p class="text-muted mb-1" style="font-size: 0.75rem;">Total Orders</p>
                <h3 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">{{ $orders['total'] ?? 0 }}</h3>
                <small class="text-warning">
                    <i class="bi bi-clock"></i> {{ $orders['pending'] ?? 0 }} Pending
                </small>
            </div>
        </div>
    </div>
    
    <!-- Total Customers -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card-compact">
            <div class="card-body p-3">
                <p class="text-muted mb-1" style="font-size: 0.75rem;">Total Customers</p>
                <h3 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">{{ $customers['total'] ?? 0 }}</h3>
                <small class="text-success">
                    <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> {{ $onlineUsers ?? 0 }} Online
                </small>
            </div>
        </div>
    </div>
    
    <!-- Total Products -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card-compact">
            <div class="card-body p-3">
                <p class="text-muted mb-1" style="font-size: 0.75rem;">Total Products</p>
                <h3 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">{{ $products['total'] ?? 0 }}</h3>
                <small class="text-danger">
                    <i class="bi bi-exclamation-triangle"></i> {{ $products['low_stock'] ?? 0 }} Low Stock
                </small>
            </div>
        </div>
    </div>
    
    <!-- Page Views -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card-compact">
            <div class="card-body p-3">
                <p class="text-muted mb-1" style="font-size: 0.75rem;">Page Views</p>
                <h3 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">{{ number_format($visitorsToday ?? 0) }}</h3>
                <small class="text-success">
                    <i class="bi bi-arrow-up"></i> Today
                </small>
            </div>
        </div>
    </div>
    
    <!-- Delivered Orders -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card-compact">
            <div class="card-body p-3">
                <p class="text-muted mb-1" style="font-size: 0.75rem;">Delivered</p>
                <h3 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">{{ $orders['delivered'] ?? 0 }}</h3>
                <small class="text-success">
                    <i class="bi bi-check-circle"></i> Completed
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Overview Chart -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">Revenue Overview</h5>
                        <p class="text-muted mb-0" style="font-size: 0.8125rem;">Track your revenue performance over time</p>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle me-2" style="width: 8px; height: 8px;"></span>
                            <small class="text-muted">This week</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-secondary rounded-circle me-2" style="width: 8px; height: 8px;"></span>
                            <small class="text-muted">Last week</small>
                        </div>
                    </div>
                </div>
                
                <!-- Revenue Stats -->
                <div class="row text-center mb-3">
                    <div class="col-3">
                        <small class="text-muted d-block mb-1">Today</small>
                        <h6 class="mb-0">Rp {{ number_format($revenue['today'] ?? 0, 0, ',', '.') }}</h6>
                    </div>
                    <div class="col-3">
                        <small class="text-muted d-block mb-1">This Week</small>
                        <h6 class="mb-0">Rp {{ number_format($revenue['this_week'] ?? 0, 0, ',', '.') }}</h6>
                    </div>
                    <div class="col-3">
                        <small class="text-muted d-block mb-1">This Month</small>
                        <h6 class="mb-0">Rp {{ number_format($revenue['this_month'] ?? 0, 0, ',', '.') }}</h6>
                    </div>
                    <div class="col-3">
                        <small class="text-muted d-block mb-1">This Year</small>
                        <h6 class="mb-0">Rp {{ number_format($revenue['this_year'] ?? 0, 0, ',', '.') }}</h6>
                    </div>
                </div>
                
                <!-- Chart Canvas -->
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Order Status Distribution -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">Order Status Distribution</h5>
                        <p class="text-muted mb-0" style="font-size: 0.8125rem;">Overview of all order statuses</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Chart Canvas -->
                        <canvas id="orderStatusChart" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning rounded-circle me-2" style="width: 12px; height: 12px;"></span>
                                    <span>Pending</span>
                                </div>
                                <strong>{{ $orders['pending'] ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info rounded-circle me-2" style="width: 12px; height: 12px;"></span>
                                    <span>Processing</span>
                                </div>
                                <strong>{{ $orders['processing'] ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-circle me-2" style="width: 12px; height: 12px;"></span>
                                    <span>Shipped</span>
                                </div>
                                <strong>{{ $orders['shipped'] ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></span>
                                    <span>Delivered</span>
                                </div>
                                <strong>{{ $orders['delivered'] ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-danger rounded-circle me-2" style="width: 12px; height: 12px;"></span>
                                    <span>Cancelled</span>
                                </div>
                                <strong>{{ $orders['cancelled'] ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Visitor Stats & Low Stock Alert -->
<div class="row">
    <!-- Visitor Stats -->
    <div class="col-xl-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Visitor Stats</h5>
                    <span class="badge bg-primary">Last 7 Days</span>
                </div>
                
                <!-- Visitor Chart -->
                <div class="mb-3" style="height: 180px;">
                    <canvas id="visitorChart"></canvas>
                </div>
                
                <!-- Stats Summary -->
                <div class="row g-2">
                    <div class="col-4">
                        <div class="text-center p-2 rounded" style="background: #f8f9fc;">
                            <small class="text-muted d-block mb-1">Today</small>
                            <h5 class="mb-0 text-primary">{{ $visitorsToday ?? 0 }}</h5>
                            <small class="text-muted">visitors</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-2 rounded" style="background: #f8f9fc;">
                            <small class="text-muted d-block mb-1">This Month</small>
                            <h5 class="mb-0 text-success">{{ $visitorsMonth ?? 0 }}</h5>
                            <small class="text-muted">visitors</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-2 rounded" style="background: #f8f9fc;">
                            <small class="text-muted d-block mb-1">Online Now</small>
                            <h5 class="mb-0 text-warning">
                                <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i>
                                {{ $onlineUsers ?? 0 }}
                            </h5>
                            <small class="text-muted">users</small>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Info -->
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-graph-up"></i> Avg. Daily Visitors
                        </small>
                        <strong>{{ $visitorsMonth > 0 ? round($visitorsMonth / 30) : 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Products -->
    <div class="col-xl-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Low Stock Alert
                </h5>
                <span class="badge bg-danger">{{ $lowStockProducts->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($lowStockProducts->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($lowStockProducts->take(5) as $product)
                    <div class="list-group-item">
                        <div class="d-flex align-items-start">
                            <div class="bg-warning bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                <i class="bi bi-box-seam text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 fw-semibold">{{ Str::limit($product->name, 30) }}</p>
                                <small class="text-muted">SKU: {{ $product->sku }}</small>
                                <div class="mt-2">
                                    <span class="badge bg-warning">{{ $product->stock }} units left</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-check-circle" style="font-size: 2rem; color: #1cc88a;"></i>
                    <p class="mt-2 mb-0 text-muted">All products well stocked</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Recent Orders</h5>
                    <small class="text-muted">Latest customer orders</small>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                    View All Orders <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 40px;"></th>
                                <th>Customer</th>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</div>
                                            <small class="text-muted">{{ $order->user->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-primary">#{{ $order->order_number }}</span>
                                </td>
                                <td>
                                    <div>{{ $order->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td class="fw-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['color' => 'warning', 'label' => 'Pending'],
                                            'processing' => ['color' => 'info', 'label' => 'Processing'],
                                            'shipped' => ['color' => 'primary', 'label' => 'Shipped'],
                                            'delivered' => ['color' => 'success', 'label' => 'Delivered'],
                                            'cancelled' => ['color' => 'danger', 'label' => 'Cancelled']
                                        ];
                                        $config = $statusConfig[$order->status] ?? ['color' => 'secondary', 'label' => ucfirst($order->status)];
                                    @endphp
                                    <span class="badge bg-{{ $config['color'] }}">{{ $config['label'] }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 2.5rem; color: #858796;"></i>
                                    <p class="mt-3 mb-0 text-muted">No orders yet</p>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart - Using Real Data
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const chartData = @json($revenueChartData);
        
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Revenue',
                    data: chartData.data,
                    borderColor: '#2196F3',
                    backgroundColor: 'rgba(33, 150, 243, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#2196F3',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#2196F3',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                }
                                return 'Rp ' + value;
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    // Order Status Pie Chart - Using Real Data
    const orderStatusCtx = document.getElementById('orderStatusChart');
    if (orderStatusCtx) {
        new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $orders['pending'] ?? 0 }},
                        {{ $orders['processing'] ?? 0 }},
                        {{ $orders['shipped'] ?? 0 }},
                        {{ $orders['delivered'] ?? 0 }},
                        {{ $orders['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#FFC107', // Warning - Pending
                        '#17A2B8', // Info - Processing
                        '#2196F3', // Primary - Shipped
                        '#28A745', // Success - Delivered
                        '#DC3545'  // Danger - Cancelled
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#2196F3',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Visitor Chart - Bar Chart
    const visitorCtx = document.getElementById('visitorChart');
    if (visitorCtx) {
        const visitorData = @json($visitorChartData);
        
        new Chart(visitorCtx, {
            type: 'bar',
            data: {
                labels: visitorData.labels,
                datasets: [{
                    label: 'Visitors',
                    data: visitorData.data,
                    backgroundColor: 'rgba(33, 150, 243, 0.8)',
                    borderColor: '#2196F3',
                    borderWidth: 1,
                    borderRadius: 4,
                    barThickness: 25
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
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#2196F3',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Visitors: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return Math.floor(value);
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    .stat-card-compact {
        transition: all 0.3s ease;
    }
    
    .stat-card-compact:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 0.8125rem;
    }
    
    .breadcrumb-item a {
        color: #858796;
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        color: var(--primary-color);
    }
    
    .breadcrumb-item.active {
        color: #858796;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
        padding: 1rem 1.25rem;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
</style>
@endpush
