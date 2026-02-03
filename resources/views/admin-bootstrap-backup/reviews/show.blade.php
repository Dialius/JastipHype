@extends('admin.layouts.app')

@section('title', 'Review Details')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Review Details</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to List
        </a>
    </div>
</div>

<div class="row">
    <!-- Review Content -->
    <div class="col-lg-8">
        <!-- Product Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Product Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    @if($review->product->productImages->first())
                    <img src="{{ asset('storage/' . $review->product->productImages->first()->image_path) }}" 
                         alt="{{ $review->product->name }}" 
                         class="rounded me-3"
                         style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-image text-muted fs-3"></i>
                    </div>
                    @endif
                    <div>
                        <h6 class="mb-1">{{ $review->product->name }}</h6>
                        <p class="text-muted mb-1">SKU: {{ $review->product->sku }}</p>
                        <p class="mb-0">
                            <span class="badge bg-primary">{{ $review->product->brand->name }}</span>
                            <span class="badge bg-secondary">{{ $review->product->category->name }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Content -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Review Content</h5>
            </div>
            <div class="card-body">
                <!-- Rating -->
                <div class="mb-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="badge bg-warning text-dark">{{ $review->rating }} / 5</span>
                        @if($review->verified_purchase)
                        <span class="badge bg-success ms-2">
                            <i class="bi bi-check-circle"></i> Verified Purchase
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Title -->
                @if($review->title)
                <div class="mb-3">
                    <h6>{{ $review->title }}</h6>
                </div>
                @endif

                <!-- Comment -->
                <div class="mb-3">
                    <p class="mb-0">{{ $review->comment }}</p>
                </div>

                <!-- Images -->
                @if($review->images->count() > 0)
                <div class="mb-3">
                    <label class="form-label fw-medium">Review Images</label>
                    <div class="row g-2">
                        @foreach($review->images as $image)
                        <div class="col-md-3">
                            <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="Review Image" 
                                     class="img-fluid rounded"
                                     style="width: 100%; height: 150px; object-fit: cover;">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Date -->
                <div class="text-muted small">
                    <i class="bi bi-clock"></i> Posted on {{ $review->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

        <!-- Admin Response -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Admin Response</h5>
            </div>
            <div class="card-body">
                @if($review->response)
                <!-- Existing Response -->
                <div class="alert alert-info mb-3">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-reply-fill me-2 mt-1"></i>
                        <div class="flex-grow-1">
                            <p class="mb-2">{{ $review->response->response }}</p>
                            <small class="text-muted">
                                Responded by <strong>{{ $review->response->user->name }}</strong> 
                                on {{ $review->response->created_at->format('d M Y, H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Response Form -->
                <form action="{{ route('admin.reviews.respond', $review) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="response" class="form-label">
                            {{ $review->response ? 'Update Response' : 'Add Response' }}
                        </label>
                        <textarea class="form-control @error('response') is-invalid @enderror" 
                                  id="response" 
                                  name="response" 
                                  rows="4" 
                                  required
                                  placeholder="Write your response to this review...">{{ old('response', $review->response->response ?? '') }}</textarea>
                        @error('response')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">This response will be visible to customers on the product page.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-2"></i>{{ $review->response ? 'Update Response' : 'Send Response' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 60px; height: 60px;">
                        <span class="fs-4 text-primary fw-bold">{{ $review->user_initial }}</span>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <h6 class="mb-1">{{ $review->user->name }}</h6>
                    <p class="text-muted small mb-0">{{ $review->user->email }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Reviews:</span>
                    <strong>{{ $review->user->reviews->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Orders:</span>
                    <strong>{{ $review->user->orders->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Member Since:</span>
                    <strong>{{ $review->user->created_at->format('M Y') }}</strong>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <!-- Approve (placeholder) -->
                <button type="button" 
                        class="btn btn-success w-100 mb-2"
                        onclick="approveReview({{ $review->id }})"
                        disabled
                        title="Reviews are approved by default">
                    <i class="bi bi-check-circle me-2"></i>Approve Review
                </button>

                <!-- Reject -->
                <button type="button" 
                        class="btn btn-warning w-100 mb-2"
                        data-bs-toggle="modal" 
                        data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle me-2"></i>Reject Review
                </button>

                <!-- Delete -->
                <button type="button" 
                        class="btn btn-danger w-100"
                        onclick="deleteReview({{ $review->id }})">
                    <i class="bi bi-trash me-2"></i>Delete Review
                </button>

                <hr>

                <!-- View Product -->
                <a href="{{ route('products.show', $review->product) }}" 
                   class="btn btn-outline-primary w-100 mb-2"
                   target="_blank">
                    <i class="bi bi-box-arrow-up-right me-2"></i>View Product
                </a>

                <!-- View Customer -->
                <a href="{{ route('admin.customers.show', $review->user) }}" 
                   class="btn btn-outline-secondary w-100">
                    <i class="bi bi-person me-2"></i>View Customer
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject this review? It will be hidden from the product page.</p>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason (Optional)</label>
                        <textarea class="form-control" 
                                  id="reason" 
                                  name="reason" 
                                  rows="3"
                                  placeholder="Enter reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Reject Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" action="{{ route('admin.reviews.destroy', $review) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Approve Form -->
<form id="approve-form" action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('scripts')
<script>
    function deleteReview(id) {
        if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
            document.getElementById('delete-form').submit();
        }
    }

    function approveReview(id) {
        if (confirm('Are you sure you want to approve this review?')) {
            document.getElementById('approve-form').submit();
        }
    }
</script>
@endpush
