@extends('admin.layouts.app')

@section('title', 'Product Performance Analytics')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Product Performance</h1>
            <p class="text-muted mb-0">Top selling products by revenue and quantity</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                <i class="bi bi-download me-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Limit Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.analytics.products') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="limit" class="form-label">Number of Products</label>
                    <select class="form-select" id="limit" name="limit" onchange="this.form.submit()">
                        <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>Top 10</option>
                        <option value="20" {{ request('limit') == 20 ? 'selected' : '' }}>Top 20</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>Top 50</option>
                        <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>Top 100</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Top Products Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Top Products by Revenue</h6>
        </div>
        <div class="card-body">
            <canvas id="productsChart" height="80"></canvas>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product Performance Details</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="productsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Rank</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Total Quantity Sold</th>
                            <th>Total Revenue</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr>
                            <td>
                                <span class="badge 
                                    @if($index === 0) bg-warning
                                    @elseif($index === 1) bg-secondary
                                    @elseif($index === 2) bg-info
                                    @else bg-light text-dark
                                    @endif">
                                    #{{ $index + 1 }}
                                </span>
                            </td>
                            <td>{{ $product->id }}</td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ number_format($product->total_quantity) }} units</span>
                            </td>
                            <td>
                                <strong class="text-success">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No product data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Product Performance Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.analytics.export') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="type" value="products">
                    
                    <div class="mb-3">
                        <label class="form-label">Export Format</label>
                        <select name="format" class="form-select" required>
                            <option value="csv">CSV</option>
                            <option value="excel">Excel (Coming Soon)</option>
                            <option value="pdf">PDF (Coming Soon)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .text-gray-800 {
        color: #5a5c69 !important;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Prepare chart data
    const products = @json($products);
    const labels = products.map(p => p.name.length > 20 ? p.name.substring(0, 20) + '...' : p.name);
    const revenues = products.map(p => parseFloat(p.total_revenue));
    
    // Create chart
    const ctx = document.getElementById('productsChart').getContext('2d');
    const productsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: revenues,
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: '#4e73df',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.x.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
