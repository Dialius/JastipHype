@extends('admin.layouts.app')

@section('title', 'Payment Analytics')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Payment Analytics</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                <li class="breadcrumb-item active">Analytics</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to List
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                <i class="bi bi-credit-card-fill"></i>
            </div>
            <div class="stat-value">{{ number_format($statistics['total']) }}</div>
            <div class="stat-label">Total Transactions</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-value">Rp {{ number_format($statistics['total_amount'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                <i class="bi bi-percent"></i>
            </div>
            <div class="stat-value">{{ $statistics['success_rate'] }}%</div>
            <div class="stat-label">Success Rate</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                <i class="bi bi-clock-fill"></i>
            </div>
            <div class="stat-value">{{ number_format($statistics['pending']) }}</div>
            <div class="stat-label">Pending Payments</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Payment Method Distribution -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Payment Method Distribution</h5>
            </div>
            <div class="card-body">
                @if($methodDistribution->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Total Amount</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalCount = $methodDistribution->sum('count'); @endphp
                            @foreach($methodDistribution as $method)
                            <tr>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $method->payment_type)) }}
                                    </span>
                                </td>
                                <td class="text-end">{{ number_format($method->count) }}</td>
                                <td class="text-end">Rp {{ number_format($method->total, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <strong>{{ $totalCount > 0 ? round(($method->count / $totalCount) * 100, 1) : 0 }}%</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    No payment data available
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Status Distribution</h5>
            </div>
            <div class="card-body">
                @if($statusDistribution->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalCount = $statusDistribution->sum('count'); @endphp
                            @foreach($statusDistribution as $status)
                            <tr>
                                <td>
                                    @php
                                        $badgeClass = in_array($status->transaction_status, ['settlement', 'capture', 'paid']) ? 'success' : 
                                                     (in_array($status->transaction_status, ['pending', 'unpaid']) ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">
                                        {{ ucfirst($status->transaction_status) }}
                                    </span>
                                </td>
                                <td class="text-end">{{ number_format($status->count) }}</td>
                                <td class="text-end">
                                    <strong>{{ $totalCount > 0 ? round(($status->count / $totalCount) * 100, 1) : 0 }}%</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    No status data available
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Revenue by Payment Method -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Revenue by Payment Method</h5>
            </div>
            <div class="card-body">
                @if($revenueByMethod->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th class="text-end">Total Revenue</th>
                                <th class="text-end">% of Total</th>
                                <th>Revenue Bar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalRevenue = $revenueByMethod->sum('revenue'); @endphp
                            @foreach($revenueByMethod as $method)
                            <tr>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $method->payment_type)) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($method->revenue, 0, ',', '.') }}</strong>
                                </td>
                                <td class="text-end">
                                    {{ $totalRevenue > 0 ? round(($method->revenue / $totalRevenue) * 100, 1) : 0 }}%
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-primary" 
                                             role="progressbar" 
                                             style="width: {{ $totalRevenue > 0 ? ($method->revenue / $totalRevenue) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th>Total</th>
                                <th class="text-end">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                                <th class="text-end">100%</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    No revenue data available
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
