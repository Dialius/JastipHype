@extends('admin.layouts.app')

@section('title', 'Payment Tracking')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Payment Tracking</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Payments</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.payments.analytics') }}" class="btn btn-outline-primary">
            <i class="bi bi-graph-up me-2"></i>Analytics
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-credit-card-fill"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($statistics['total']) }}</div>
                    <div class="stat-label">Total Transactions</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($statistics['paid']) }}</div>
                    <div class="stat-label">Successful</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($statistics['pending']) }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                    <i class="bi bi-percent"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $statistics['success_rate'] }}%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="row g-3">
            <!-- Search -->
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" 
                       class="form-control" 
                       id="search" 
                       name="search" 
                       value="{{ $filters['search'] ?? '' }}" 
                       placeholder="Transaction ID or Order Number">
            </div>

            <!-- Status Filter -->
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="settlement" {{ ($filters['status'] ?? '') == 'settlement' ? 'selected' : '' }}>Settlement</option>
                    <option value="capture" {{ ($filters['status'] ?? '') == 'capture' ? 'selected' : '' }}>Capture</option>
                    <option value="paid" {{ ($filters['status'] ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="deny" {{ ($filters['status'] ?? '') == 'deny' ? 'selected' : '' }}>Denied</option>
                    <option value="cancel" {{ ($filters['status'] ?? '') == 'cancel' ? 'selected' : '' }}>Cancelled</option>
                    <option value="expire" {{ ($filters['status'] ?? '') == 'expire' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <!-- Payment Type Filter -->
            <div class="col-md-2">
                <label for="payment_type" class="form-label">Payment Type</label>
                <select class="form-select" id="payment_type" name="payment_type">
                    <option value="">All Types</option>
                    <option value="qris" {{ ($filters['payment_type'] ?? '') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="gopay" {{ ($filters['payment_type'] ?? '') == 'gopay' ? 'selected' : '' }}>GoPay</option>
                    <option value="shopeepay" {{ ($filters['payment_type'] ?? '') == 'shopeepay' ? 'selected' : '' }}>ShopeePay</option>
                    <option value="bank_transfer" {{ ($filters['payment_type'] ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="echannel" {{ ($filters['payment_type'] ?? '') == 'echannel' ? 'selected' : '' }}>Mandiri Bill</option>
                    <option value="cstore" {{ ($filters['payment_type'] ?? '') == 'cstore' ? 'selected' : '' }}>Convenience Store</option>
                </select>
            </div>

            <!-- Date From -->
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" 
                       class="form-control" 
                       id="date_from" 
                       name="date_from" 
                       value="{{ $filters['date_from'] ?? '' }}">
            </div>

            <!-- Date To -->
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" 
                       class="form-control" 
                       id="date_to" 
                       name="date_to" 
                       value="{{ $filters['date_to'] ?? '' }}">
            </div>

            <!-- Actions -->
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Payments List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Payments ({{ $payments->total() }})</h5>
    </div>
    <div class="card-body p-0">
        @if($payments->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <!-- Transaction ID -->
                        <td>
                            <small class="font-monospace">{{ $payment->transaction_id }}</small>
                        </td>

                        <!-- Order -->
                        <td>
                            @if($payment->order)
                            <a href="{{ route('admin.orders.show', $payment->order) }}" class="text-decoration-none">
                                {{ $payment->order->order_number }}
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Customer -->
                        <td>
                            @if($payment->order && $payment->order->user)
                            <div>
                                <div class="fw-medium">{{ $payment->order->user->name }}</div>
                                <small class="text-muted">{{ $payment->order->user->email }}</small>
                            </div>
                            @else
                            <span class="text-muted">Guest</span>
                            @endif
                        </td>

                        <!-- Payment Type -->
                        <td>
                            <span class="badge bg-info">{{ $payment->getPaymentTypeName() }}</span>
                        </td>

                        <!-- Amount -->
                        <td>
                            <strong>Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</strong>
                        </td>

                        <!-- Status -->
                        <td>
                            @php
                                if ($payment->isSuccess()) {
                                    $badge = 'success';
                                } elseif ($payment->isPending()) {
                                    $badge = 'warning';
                                } else {
                                    $badge = 'danger';
                                }
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ $payment->getStatusLabel() }}</span>
                        </td>

                        <!-- Date -->
                        <td>
                            <small>{{ $payment->created_at->format('d M Y') }}</small>
                            <br>
                            <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                        </td>

                        <!-- Actions -->
                        <td>
                            <a href="{{ route('admin.payments.show', $payment) }}" 
                               class="btn btn-sm btn-outline-primary"
                               title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer">
            {{ $payments->appends($filters)->links('vendor.pagination.bootstrap-5-simple') }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-credit-card" style="font-size: 4rem; color: #dee2e6;"></i>
            <p class="mt-3 text-muted">No payments found.</p>
        </div>
        @endif
    </div>
</div>
@endsection
