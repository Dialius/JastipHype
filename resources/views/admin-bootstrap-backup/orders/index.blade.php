@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Orders</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>
        </nav>
    </div>
    <div>
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="bi bi-download me-2"></i>Export
        </button>
    </div>
</div>

<!-- Status Filter Tabs -->
<div class="card mb-4">
    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    All Orders
                    <span class="badge bg-secondary ms-2">{{ $statusCounts['all'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.index', ['status' => 'pending']) }}">
                    Pending
                    <span class="badge bg-warning ms-2">{{ $statusCounts['pending'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'processing' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.index', ['status' => 'processing']) }}">
                    Processing
                    <span class="badge bg-info ms-2">{{ $statusCounts['processing'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'shipped' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.index', ['status' => 'shipped']) }}">
                    Shipped
                    <span class="badge bg-primary ms-2">{{ $statusCounts['shipped'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'delivered' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.index', ['status' => 'delivered']) }}">
                    Delivered
                    <span class="badge bg-success ms-2">{{ $statusCounts['delivered'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'cancelled' ? 'active' : '' }}" 
                   href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}">
                    Cancelled
                    <span class="badge bg-danger ms-2">{{ $statusCounts['cancelled'] }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
            <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">
            
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Order number, customer..." 
                       value="{{ $filters['search'] ?? '' }}">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="">All Methods</option>
                    <option value="bank_transfer" {{ ($filters['payment_method'] ?? '') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="credit_card" {{ ($filters['payment_method'] ?? '') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="e-wallet" {{ ($filters['payment_method'] ?? '') === 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="qris" {{ ($filters['payment_method'] ?? '') === 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control" 
                       value="{{ $filters['date_from'] ?? '' }}">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control" 
                       value="{{ $filters['date_to'] ?? '' }}">
            </div>
            
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">All Orders</h5>
            <small class="text-muted">{{ $orders->total() }} orders found</small>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <div>
                                <span class="fw-semibold text-primary">#{{ $order->order_number }}</span>
                            </div>
                            <small class="text-muted">{{ $order->items->count() }} items</small>
                        </td>
                        <td>
                            <div>
                                <div class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</div>
                                <small class="text-muted">{{ $order->user->email ?? $order->customer_email }}</small>
                            </div>
                        </td>
                        <td>
                            <div>{{ $order->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td class="fw-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            @if($order->payment)
                                @php
                                    // Get payment method logo
                                    $paymentType = $order->payment->payment_type ?? 'unknown';
                                    $paymentData = $order->payment->payment_data ?? [];
                                    
                                    // Determine logo path
                                    $logoPath = null;
                                    $paymentLabel = '';
                                    
                                    switch($paymentType) {
                                        case 'qris':
                                            $logoPath = '/images/payment/ewallet/qris.svg';
                                            $paymentLabel = 'QRIS';
                                            break;
                                        case 'gopay':
                                            $logoPath = '/images/payment/ewallet/gopay.svg';
                                            $paymentLabel = 'GoPay';
                                            break;
                                        case 'shopeepay':
                                            $logoPath = '/images/payment/ewallet/shopeepay.svg';
                                            $paymentLabel = 'ShopeePay';
                                            break;
                                        case 'bank_transfer':
                                            $bank = strtolower($paymentData['va_numbers'][0]['bank'] ?? 'bca');
                                            $logoPath = '/images/payment/banks/' . $bank . '.svg';
                                            $paymentLabel = strtoupper($bank);
                                            break;
                                        case 'echannel':
                                            $logoPath = '/images/payment/banks/mandiri.svg';
                                            $paymentLabel = 'Mandiri';
                                            break;
                                        default:
                                            $paymentLabel = ucfirst($paymentType);
                                    }
                                @endphp
                                
                                <div class="text-center">
                                    @if($logoPath && file_exists(public_path($logoPath)))
                                        <img src="{{ asset($logoPath) }}" 
                                             alt="{{ $paymentLabel }}" 
                                             style="height: 32px; width: auto; max-width: 80px; object-fit: contain; display: block; margin: 0 auto;"
                                             title="{{ $paymentLabel }}">
                                    @else
                                        <div class="badge bg-secondary" style="font-size: 0.75rem;">
                                            {{ $paymentLabel }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
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
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 2.5rem; color: #858796;"></i>
                            <p class="mt-3 mb-0 text-muted">No orders found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer">
        {{ $orders->links('vendor.pagination.bootstrap-5-simple') }}
    </div>
    @endif
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.orders.export') }}" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-download me-2"></i>Export CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Fix DataTables Pagination Arrow */
    .pagination {
        margin: 0;
    }
    
    .pagination .page-link {
        font-size: 0.875rem !important;
        padding: 0.375rem 0.75rem !important;
        border-radius: 0.25rem !important;
        margin: 0 0.125rem !important;
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--primary-color, #2196F3) !important;
        border-color: var(--primary-color, #2196F3) !important;
    }
    
    /* Fix Large Arrow Icons */
    .pagination .page-link svg,
    .pagination .page-link i {
        font-size: 0.75rem !important;
        width: auto !important;
        height: auto !important;
    }
    
    /* Prevent Overflow */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .card-body {
        overflow: visible !important;
    }
    
    /* Fix Table Layout */
    .table {
        width: 100%;
        margin-bottom: 0;
    }
    
    .table th,
    .table td {
        vertical-align: middle;
        padding: 0.75rem;
        white-space: nowrap;
    }
    
    /* Status Pills Styling */
    .nav-pills .nav-link {
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        margin-right: 0.5rem;
        transition: all 0.2s;
    }
    
    .nav-pills .nav-link:hover {
        background-color: rgba(33, 150, 243, 0.1);
    }
    
    .nav-pills .nav-link.active {
        background-color: var(--primary-color, #2196F3);
    }
    
    /* Card Footer Pagination */
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid var(--border-color, #e3e6f0);
        padding: 1rem;
    }
    
    /* Payment Logo Styling */
    .payment-logo-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .payment-logo {
        height: 24px;
        width: auto;
        max-width: 60px;
        object-fit: contain;
        border-radius: 4px;
        padding: 2px;
        background: #f8f9fa;
        border: 1px solid #e3e6f0;
    }
    
    .payment-info {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }
    
    .payment-name {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #212529;
        line-height: 1.2;
    }
    
    .payment-status-badge {
        font-size: 0.6875rem;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        font-weight: 500;
        display: inline-block;
        width: fit-content;
    }
    
    /* Responsive Table */
    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 0.75rem;
            padding: 0.5rem;
        }
        
        .payment-logo {
            height: 20px;
            max-width: 50px;
        }
        
        .payment-name {
            font-size: 0.75rem;
        }
    }
</style>
@endpush
