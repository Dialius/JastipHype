@extends('admin.layouts.app')

@section('title', 'Bulk Messaging')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0">Bulk Messaging</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
                <li class="breadcrumb-item active">Bulk Messaging</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Send Message to Multiple Customers</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.messages.send-bulk') }}" method="POST">
                        @csrf

                        <!-- Subject -->
                        <div class="mb-3">
                            <label for="subject" class="form-label">Email Subject <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject') }}" 
                                   required
                                   placeholder="e.g., Special Promotion for Our Valued Customers">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      required
                                      placeholder="Enter your message here...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 2000 characters</div>
                        </div>

                        <!-- Segmentation -->
                        <div class="mb-3">
                            <label for="segment" class="form-label">Customer Segment <span class="text-danger">*</span></label>
                            <select class="form-select @error('segment') is-invalid @enderror" 
                                    id="segment" 
                                    name="segment" 
                                    required>
                                <option value="">Select Segment</option>
                                <option value="all" {{ old('segment') == 'all' ? 'selected' : '' }}>All Customers</option>
                                <option value="active" {{ old('segment') == 'active' ? 'selected' : '' }}>Active Customers Only</option>
                                <option value="suspended" {{ old('segment') == 'suspended' ? 'selected' : '' }}>Suspended Customers</option>
                                <option value="high_spenders" {{ old('segment') == 'high_spenders' ? 'selected' : '' }}>High Spenders</option>
                                <option value="recent_orders" {{ old('segment') == 'recent_orders' ? 'selected' : '' }}>Recent Orders</option>
                            </select>
                            @error('segment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Advanced Filters -->
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Advanced Filters (Optional)</h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="min_spending" class="form-label">Minimum Spending (Rp)</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="min_spending" 
                                               name="min_spending" 
                                               value="{{ old('min_spending') }}"
                                               min="0"
                                               placeholder="e.g., 1000000">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="max_spending" class="form-label">Maximum Spending (Rp)</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="max_spending" 
                                               name="max_spending" 
                                               value="{{ old('max_spending') }}"
                                               min="0"
                                               placeholder="e.g., 10000000">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="min_orders" class="form-label">Minimum Orders</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="min_orders" 
                                               name="min_orders" 
                                               value="{{ old('min_orders') }}"
                                               min="0"
                                               placeholder="e.g., 5">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="days_since_order" class="form-label">Days Since Last Order</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="days_since_order" 
                                               name="days_since_order" 
                                               value="{{ old('days_since_order') }}"
                                               min="0"
                                               placeholder="e.g., 30">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Send Bulk Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Segmentation Guide</h6>
                </div>
                <div class="card-body">
                    <h6 class="mb-2">All Customers</h6>
                    <p class="small text-muted mb-3">Send to all registered customers</p>

                    <h6 class="mb-2">Active Customers Only</h6>
                    <p class="small text-muted mb-3">Send only to customers with active accounts</p>

                    <h6 class="mb-2">Suspended Customers</h6>
                    <p class="small text-muted mb-3">Send only to suspended accounts</p>

                    <h6 class="mb-2">High Spenders</h6>
                    <p class="small text-muted mb-3">Customers who spent more than Rp 1,000,000</p>

                    <h6 class="mb-2">Recent Orders</h6>
                    <p class="small text-muted mb-3">Customers who ordered in the last 30 days</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Important Notes</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Messages will be sent via email to all selected customers</li>
                        <li class="mb-2">Use advanced filters to narrow down your audience</li>
                        <li class="mb-2">Preview your message before sending</li>
                        <li class="mb-0">Bulk messages are queued and sent in the background</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
