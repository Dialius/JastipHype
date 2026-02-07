@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Order #{{ $order->order_number }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active">#{{ $order->order_number }}</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-outline-secondary" target="_blank">
            <i class="bi bi-printer me-2"></i>Print Invoice
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product)
                                            <img src="{{ product_image_url($item->product) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="rounded me-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $item->product_name }}</div>
                                            <small class="text-muted">SKU: {{ $item->product->sku ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Subtotal:</td>
                                <td class="fw-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Shipping:</td>
                                <td class="fw-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Discount:</td>
                                <td class="fw-semibold text-success">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr class="table-active">
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold fs-5">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($timeline as $event)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-{{ $event['color'] }}">
                            <i class="{{ $event['icon'] }}"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">{{ $event['label'] }}</h6>
                            <p class="text-muted mb-1">{{ $event['description'] }}</p>
                            <small class="text-muted">{{ $event['timestamp']->format('d M Y, H:i') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Order Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Status</h5>
            </div>
            <div class="card-body">
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
                
                <div class="text-center mb-3">
                    <span class="badge bg-{{ $config['color'] }} fs-6 px-3 py-2">{{ $config['label'] }}</span>
                </div>

                @if($order->status !== 'cancelled' && $order->status !== 'delivered')
                <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                    <i class="bi bi-arrow-repeat me-2"></i>Update Status
                </button>
                @endif

                @if($order->status !== 'cancelled')
                <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                    <i class="bi bi-x-circle me-2"></i>Cancel Order
                </button>
                @endif
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Name</small>
                    <strong>{{ $order->customer_name ?? ($order->user->name ?? 'Guest') }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Email</small>
                    <strong>{{ $order->customer_email ?? ($order->user->email ?? '-') }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Phone</small>
                    <strong>{{ $order->customer_phone ?? ($order->user->phone ?? '-') }}</strong>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Shipping Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Address</small>
                    <strong>{{ $order->shipping_address ?? '-' }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">City</small>
                    <strong>{{ $order->shipping_city ?? '-' }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Postal Code</small>
                    <strong>{{ $order->shipping_postal_code ?? '-' }}</strong>
                </div>
                @if($order->shipping_courier)
                <div class="mb-3">
                    <small class="text-muted d-block">Courier</small>
                    <strong>{{ strtoupper($order->shipping_courier) }} - {{ $order->shipping_service }}</strong>
                </div>
                @endif
                @if($order->shipping_cost)
                <div class="mb-3">
                    <small class="text-muted d-block">Shipping Cost</small>
                    <strong>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</strong>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Information -->
        @if($order->payment)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Payment Information</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPaymentModal">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary sync-payment-btn" data-id="{{ $order->id }}">
                        <i class="bi bi-arrow-repeat"></i> Sync
                    </button>
                </div>
            </div>
            <div class="card-body">
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
                            $paymentLabel = strtoupper($bank) . ' Virtual Account';
                            break;
                        case 'echannel':
                            $logoPath = '/images/payment/banks/mandiri.svg';
                            $paymentLabel = 'Mandiri Bill Payment';
                            break;
                        default:
                            $paymentLabel = ucfirst($paymentType);
                    }
                    
                    // Status badge color
                    $statusColor = 'secondary';
                    $statusText = ucfirst($order->payment->transaction_status ?? 'pending');
                    
                    if (in_array($order->payment->transaction_status, ['settlement', 'capture', 'paid'])) {
                        $statusColor = 'success';
                        $statusText = 'Paid';
                    } elseif ($order->payment->transaction_status === 'pending') {
                        $statusColor = 'warning';
                        $statusText = 'Pending';
                    } elseif (in_array($order->payment->transaction_status, ['deny', 'cancel', 'expire', 'failed'])) {
                        $statusColor = 'danger';
                        $statusText = 'Failed';
                    }
                @endphp
                
                <div class="text-center py-3">
                    @if($logoPath && file_exists(public_path($logoPath)))
                        <img src="{{ asset($logoPath) }}" 
                             alt="{{ $paymentLabel }}" 
                             style="height: 80px; width: auto; max-width: 160px; object-fit: contain; display: block; margin: 0 auto;"
                             title="{{ $paymentLabel }}">
                    @else
                        <div class="badge bg-secondary fs-5 px-4 py-3">
                            {{ $paymentLabel }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="send_email" value="1" id="sendEmail" checked>
                        <label class="form-check-label" for="sendEmail">
                            Send email notification to customer
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This will cancel the order and restore product stock.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cancellation Reason</label>
                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                    </div>
                    @if($order->payment && $order->payment->status === 'paid')
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="refund" value="1" id="processRefund">
                        <label class="form-check-label" for="processRefund">
                            Process refund (if payment was made)
                        </label>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->
@if($order->payment)
<div class="modal fade" id="editPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Payment Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="transaction_status" class="form-select" required>
                            <option value="pending" {{ $order->payment->transaction_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="settlement" {{ $order->payment->transaction_status === 'settlement' ? 'selected' : '' }}>Settlement (Paid)</option>
                            <option value="capture" {{ $order->payment->transaction_status === 'capture' ? 'selected' : '' }}>Capture (Paid)</option>
                            <option value="paid" {{ $order->payment->transaction_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="deny" {{ $order->payment->transaction_status === 'deny' ? 'selected' : '' }}>Denied</option>
                            <option value="cancel" {{ $order->payment->transaction_status === 'cancel' ? 'selected' : '' }}>Cancelled</option>
                            <option value="expire" {{ $order->payment->transaction_status === 'expire' ? 'selected' : '' }}>Expired</option>
                            <option value="failed" {{ $order->payment->transaction_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        <small class="text-muted">Current: {{ ucfirst($order->payment->transaction_status) }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control" 
                               value="{{ $order->payment->transaction_id }}" 
                               placeholder="Enter transaction ID">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Code / VA Number</label>
                        <input type="text" name="payment_code" class="form-control" 
                               value="{{ $order->payment->payment_code }}" 
                               placeholder="Enter payment code or VA number">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" name="gross_amount" class="form-control" 
                               value="{{ $order->payment->gross_amount }}" 
                               placeholder="Enter amount" required>
                        <small class="text-muted">Current: Rp {{ number_format($order->payment->gross_amount, 0, ',', '.') }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Add notes about this payment update..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Changing payment status to "Paid" will automatically update order status to "Processing".</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 50px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -35px;
        top: 30px;
        bottom: -20px;
        width: 2px;
        background: #e3e6f0;
    }
    
    .timeline-item:last-child::before {
        display: none;
    }
    
    .timeline-marker {
        position: absolute;
        left: -45px;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }
    
    .timeline-content {
        background: #f8f9fc;
        padding: 1rem;
        border-radius: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Sync Payment Status
    document.querySelectorAll('.sync-payment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.id;
            const button = this;
            const icon = button.querySelector('i');
            
            // Add loading state
            icon.classList.add('bi-arrow-repeat');
            icon.style.animation = 'spin 1s linear infinite';
            button.disabled = true;
            
            fetch(`/admin/orders/${orderId}/sync-payment-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payment status synced successfully!');
                    location.reload();
                } else {
                    alert('Failed to sync payment status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to sync payment status');
            })
            .finally(() => {
                icon.style.animation = '';
                button.disabled = false;
            });
        });
    });
</script>

<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush
