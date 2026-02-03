@extends('admin.layouts.app')

@section('title', 'Payment Details')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Payment Details</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to List
        </a>
    </div>
</div>

<div class="row">
    <!-- Payment Information -->
    <div class="col-lg-8">
        <!-- Transaction Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Transaction Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Transaction ID</label>
                        <div class="font-monospace">{{ $payment->transaction_id }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Order Number</label>
                        <div>
                            @if($payment->order)
                            <a href="{{ route('admin.orders.show', $payment->order) }}">
                                {{ $payment->order->order_number }}
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Payment Type</label>
                        <div><span class="badge bg-info">{{ $payment->getPaymentTypeName() }}</span></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Status</label>
                        <div>
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
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Amount</label>
                        <div class="fs-4 fw-bold text-primary">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-6">
                        @if($payment->fraud_status)
                        <label class="text-muted small">Fraud Status</label>
                        <div><span class="badge bg-secondary">{{ ucfirst($payment->fraud_status) }}</span></div>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <label class="text-muted small">Transaction Time</label>
                        <div>{{ $payment->transaction_time ? $payment->transaction_time->format('d M Y, H:i') : '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Settlement Time</label>
                        <div>{{ $payment->settlement_time ? $payment->settlement_time->format('d M Y, H:i') : '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Expiry Time</label>
                        <div>{{ $payment->expiry_time ? $payment->expiry_time->format('d M Y, H:i') : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        @if($payment->payment_data)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Payment Instructions</h5>
            </div>
            <div class="card-body">
                @php $instructions = $payment->getInstructions(); @endphp
                
                @if($instructions['type'] === 'bank_transfer')
                    <div class="alert alert-info">
                        <strong>Bank: {{ $instructions['bank'] }}</strong><br>
                        Virtual Account: <code>{{ $instructions['va_number'] }}</code>
                    </div>
                @elseif($instructions['type'] === 'qris')
                    <div class="alert alert-info">
                        <strong>QRIS Payment</strong><br>
                        Acquirer: {{ ucfirst($instructions['acquirer']) }}
                    </div>
                @elseif($instructions['type'] === 'gopay')
                    <div class="alert alert-info">
                        <strong>GoPay Payment</strong><br>
                        @if($instructions['deeplink'])
                        <a href="{{ $instructions['deeplink'] }}" target="_blank">Open in GoPay App</a>
                        @endif
                    </div>
                @elseif($instructions['type'] === 'cstore')
                    <div class="alert alert-info">
                        <strong>{{ ucfirst($instructions['store']) }}</strong><br>
                        Payment Code: <code>{{ $instructions['payment_code'] }}</code>
                    </div>
                @else
                    <div class="alert alert-secondary">
                        Payment Type: {{ $payment->getPaymentTypeName() }}
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Order Items -->
        @if($payment->order && $payment->order->items->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payment->order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Customer Info -->
        @if($payment->order && $payment->order->user)
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 60px; height: 60px;">
                        <span class="fs-4 text-primary fw-bold">{{ strtoupper(substr($payment->order->user->name, 0, 1)) }}</span>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <h6 class="mb-1">{{ $payment->order->user->name }}</h6>
                    <p class="text-muted small mb-0">{{ $payment->order->user->email }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Phone:</span>
                    <strong>{{ $payment->order->user->phone ?? '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Member Since:</span>
                    <strong>{{ $payment->order->user->created_at->format('M Y') }}</strong>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <!-- Sync Status -->
                <form action="{{ route('admin.payments.sync-status', $payment) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-arrow-repeat me-2"></i>Sync from Midtrans
                    </button>
                </form>

                <!-- Manual Verify -->
                <button type="button" 
                        class="btn btn-warning w-100 mb-2"
                        data-bs-toggle="modal" 
                        data-bs-target="#verifyModal">
                    <i class="bi bi-check-circle me-2"></i>Manual Verification
                </button>

                <hr>

                <!-- View Order -->
                @if($payment->order)
                <a href="{{ route('admin.orders.show', $payment->order) }}" 
                   class="btn btn-outline-primary w-100">
                    <i class="bi bi-box-arrow-up-right me-2"></i>View Order
                </a>
                @endif
            </div>
        </div>

        <!-- Payment Info -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Payment Info</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">Created:</small>
                    <div>{{ $payment->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div>
                    <small class="text-muted">Last Updated:</small>
                    <div>{{ $payment->updated_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manual Verification Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.payments.manual-verify', $payment) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Manual Payment Verification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> Manual verification should only be used when automatic sync fails or for special cases.
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="settlement">Settlement (Paid)</option>
                            <option value="paid">Paid</option>
                            <option value="cancel">Cancel</option>
                            <option value="expire">Expire</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3"
                                  placeholder="Enter verification notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Verify Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
