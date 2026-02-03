@extends('admin.layouts.app')

@section('title', 'Shipping Analytics')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Shipping Analytics</h1>
        <a href="{{ route('admin.shipping.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Settings
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Orders Shipped</p>
                            <h3 class="mb-0">{{ $courierStats->sum('total_orders') }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Shipping Cost</p>
                            <h3 class="mb-0">Rp {{ number_format($courierStats->sum('total_cost'), 0, ',', '.') }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Average Shipping Cost</p>
                            <h3 class="mb-0">Rp {{ number_format($avgShippingCost, 0, ',', '.') }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Courier Usage Statistics -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Courier Usage Distribution</h5>
                </div>
                <div class="card-body">
                    @if($courierStats->count() > 0)
                        <canvas id="courierChart" height="300"></canvas>
                        
                        <div class="table-responsive mt-4">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Courier</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Total Cost</th>
                                        <th class="text-end">Avg Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courierStats as $stat)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ strtoupper($stat->shipping_courier) }}</span>
                                            </td>
                                            <td class="text-end">{{ number_format($stat->total_orders) }}</td>
                                            <td class="text-end">Rp {{ number_format($stat->total_cost, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($stat->total_cost / $stat->total_orders, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">No shipping data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Shipping Cost Distribution -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shipping Cost Distribution</h5>
                </div>
                <div class="card-body">
                    @if($costDistribution->count() > 0)
                        <canvas id="costDistributionChart" height="300"></canvas>
                        
                        <div class="table-responsive mt-4">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Cost Range</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalOrders = $costDistribution->sum('count'); @endphp
                                    @foreach($costDistribution as $dist)
                                        <tr>
                                            <td>{{ $dist->range }}</td>
                                            <td class="text-end">{{ number_format($dist->count) }}</td>
                                            <td class="text-end">{{ number_format(($dist->count / $totalOrders) * 100, 1) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">No cost distribution data</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Monthly Trends -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Monthly Shipping Trends (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    @if($monthlyTrends->count() > 0)
                        <canvas id="monthlyTrendsChart" height="100"></canvas>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">No monthly trend data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
                        '#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545',
                        '#fd7e14', '#ffc107', '#198754', '#20c997', '#0dcaf0'
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
                    backgroundColor: '#0d6efd'
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
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        yAxisID: 'y'
                    },
                    {
                        label: 'Average Cost (Rp)',
                        data: {!! json_encode($monthlyTrends->pluck('avg_cost')) !!},
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
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
