@extends('admin.layouts.app')

@section('title', 'Customer Detail - ' . $customer->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Customer Detail</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active">{{ $customer->name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @if(($customer->status ?? 'active') == 'active')
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#suspendModal">
                    <i class="bi bi-x-circle"></i> Suspend
                </button>
            @else
                <form action="{{ route('admin.customers.activate', $customer->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Activate this customer account?')">
                        <i class="bi bi-check-circle"></i> Activate
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Customer Info Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                        <i class="bi bi-person fs-1 text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $customer->name }}</h4>
                    <p class="text-muted mb-3">{{ $customer->email }}</p>
                    
                    @if(($customer->status ?? 'active') == 'active')
                        <span class="badge bg-success mb-3">Active</span>
                    @else
                        <span class="badge bg-danger mb-3">Suspended</span>
                    @endif

                    <hr>

                    <div class="text-start">
                        <p class="mb-2"><strong>Phone:</strong> {{ $customer->phone ?? '-' }}</p>
                        <p class="mb-2"><strong>Customer ID:</strong> #{{ $customer->id }}</p>
                        <p class="mb-2"><strong>Registered:</strong> {{ $customer->created_at->format('d M Y') }}</p>
                        <p class="mb-0"><strong>Last Updated:</strong> {{ $customer->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics and Details -->
        <div class="col-md-8 mb-4">
            <!-- Analytics Cards -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Total Orders</p>
                                    <h3 class="mb-0">{{ $analytics['total_orders'] }}</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-cart fs-4 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Total Spent</p>
                                    <h3 class="mb-0">Rp {{ number_format($analytics['total_spent'], 0, ',', '.') }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-currency-dollar fs-4 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Average Order Value</p>
                                    <h3 class="mb-0">Rp {{ number_format($analytics['average_order_value'] ?? 0, 0, ',', '.') }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-graph-up fs-4 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Total Reviews</p>
                                    <h3 class="mb-0">{{ $analytics['total_reviews'] }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-star fs-4 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <ul class="nav nav-tabs card-header-tabs border-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#orders" style="font-weight: 500;">
                                <i class="bi bi-cart me-1"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#profile" style="font-weight: 500;">
                                <i class="bi bi-person me-1"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#messages" style="font-weight: 500;">
                                <i class="bi bi-chat-dots me-1"></i> Messages
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Orders Tab -->
                        <div class="tab-pane fade show active" id="orders">
                            <h5 class="mb-3">Recent Orders</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'processing' => 'info',
                                                        'shipped' => 'primary',
                                                        'delivered' => 'success',
                                                        'cancelled' => 'danger'
                                                    ];
                                                    $color = $statusColors[$order->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">{{ ucfirst($order->status) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-3">
                                                <p class="text-muted mb-0">No orders yet</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($analytics['total_orders'] > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.orders.index', ['customer_id' => $customer->id]) }}" class="btn btn-outline-primary">
                                    View All Orders
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- Profile Tab -->
                        <div class="tab-pane fade" id="profile">
                            <h5 class="mb-3">Customer Information</h5>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th width="200">Full Name</th>
                                        <td>{{ $customer->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $customer->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if(($customer->status ?? 'active') == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Suspended</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Registered Date</th>
                                        <td>{{ $customer->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $customer->updated_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    @if($analytics['last_order_date'])
                                    <tr>
                                        <th>Last Order</th>
                                        <td>{{ $analytics['last_order_date']->format('d M Y H:i') }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Messages Tab -->
                        @include('admin.customers.partials.messages-tab')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Suspend Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.customers.suspend', $customer->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Suspend Customer Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        This will prevent the customer from logging in and making purchases.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Suspension <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Enter reason for suspension..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Suspend Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
