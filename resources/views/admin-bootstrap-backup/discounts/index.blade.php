@extends('admin.layouts.app')

@section('title', 'Discount Management')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Discount Management</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Discounts</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Create Discount
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-tag-fill"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $statistics['total'] }}</div>
                    <div class="stat-label">Total Discounts</div>
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
                    <div class="stat-value">{{ $statistics['active'] }}</div>
                    <div class="stat-label">Active</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($statistics['total_uses']) }}</div>
                    <div class="stat-label">Total Uses</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="stat-value">Rp {{ number_format($statistics['total_discount_amount'], 0, ',', '.') }}</div>
                    <div class="stat-label">Total Discount</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Discounts List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Discounts ({{ $discounts->total() }})</h5>
    </div>
    <div class="card-body p-0">
        @if($discounts->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Usage</th>
                        <th>Valid Period</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discounts as $discount)
                    <tr>
                        <!-- Code -->
                        <td>
                            <div>
                                <strong class="text-primary">{{ $discount->code }}</strong>
                                @if($discount->min_order_amount)
                                <br><small class="text-muted">Min: Rp {{ number_format($discount->min_order_amount, 0, ',', '.') }}</small>
                                @endif
                            </div>
                        </td>

                        <!-- Type & Value -->
                        <td>
                            @if($discount->type === 'percentage')
                            <span class="badge bg-info">Percentage</span>
                            @else
                            <span class="badge bg-success">Fixed Amount</span>
                            @endif
                        </td>

                        <td>
                            <strong>
                                @if($discount->type === 'percentage')
                                {{ $discount->value }}%
                                @else
                                Rp {{ number_format($discount->value, 0, ',', '.') }}
                                @endif
                            </strong>
                        </td>

                        <!-- Usage -->
                        <td>
                            <div>
                                <strong>{{ number_format($discount->uses_count) }}</strong>
                                @if($discount->max_uses)
                                / {{ number_format($discount->max_uses) }}
                                @else
                                / ∞
                                @endif
                            </div>
                            @if($discount->remaining_uses !== null)
                            <small class="text-muted">{{ $discount->remaining_uses }} left</small>
                            @endif
                        </td>

                        <!-- Valid Period -->
                        <td>
                            @if($discount->start_date || $discount->end_date)
                            <small>
                                @if($discount->start_date)
                                From: {{ $discount->start_date->format('d M Y') }}<br>
                                @endif
                                @if($discount->end_date)
                                To: {{ $discount->end_date->format('d M Y') }}
                                @endif
                            </small>
                            @else
                            <small class="text-muted">No limit</small>
                            @endif
                        </td>

                        <!-- Status -->
                        <td>
                            @php
                                $now = now();
                                $isActive = $discount->status === 'active';
                                $isScheduled = $discount->start_date && $now->lt($discount->start_date);
                                $isExpired = $discount->end_date && $now->gt($discount->end_date);
                                $isMaxedOut = $discount->max_uses && $discount->uses_count >= $discount->max_uses;
                                
                                if ($isMaxedOut) {
                                    $statusBadge = 'secondary';
                                    $statusText = 'Maxed Out';
                                } elseif ($isExpired) {
                                    $statusBadge = 'danger';
                                    $statusText = 'Expired';
                                } elseif ($isScheduled) {
                                    $statusBadge = 'info';
                                    $statusText = 'Scheduled';
                                } elseif ($isActive) {
                                    $statusBadge = 'success';
                                    $statusText = 'Active';
                                } else {
                                    $statusBadge = 'secondary';
                                    $statusText = 'Inactive';
                                }
                            @endphp
                            <span class="badge bg-{{ $statusBadge }}">{{ $statusText }}</span>
                        </td>

                        <!-- Actions -->
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.discounts.edit', $discount) }}" 
                                   class="btn btn-outline-primary"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-{{ $discount->status === 'active' ? 'warning' : 'success' }}"
                                        onclick="toggleStatus({{ $discount->id }})"
                                        title="Toggle Status">
                                    <i class="bi bi-{{ $discount->status === 'active' ? 'pause' : 'play' }}-circle"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-outline-danger"
                                        onclick="deleteDiscount({{ $discount->id }})"
                                        title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer">
            {{ $discounts->links('vendor.pagination.bootstrap-5-simple') }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-tag" style="font-size: 4rem; color: #dee2e6;"></i>
            <p class="mt-3 text-muted">No discounts yet. Create your first discount!</p>
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create Discount
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Toggle Status Form -->
<form id="toggle-form" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('scripts')
<script>
    function toggleStatus(id) {
        if (confirm('Are you sure you want to toggle this discount status?')) {
            const form = document.getElementById('toggle-form');
            form.action = `/admin/discounts/${id}/toggle-status`;
            form.submit();
        }
    }

    function deleteDiscount(id) {
        if (confirm('Are you sure you want to delete this discount? This action cannot be undone.')) {
            const form = document.getElementById('delete-form');
            form.action = `/admin/discounts/${id}`;
            form.submit();
        }
    }
</script>
@endpush
