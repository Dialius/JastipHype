@extends('admin.layouts.app')

@section('title', 'Review Management')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Review Management</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Reviews</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $statistics['total'] }}</div>
                    <div class="stat-label">Total Reviews</div>
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
                    <div class="stat-value">{{ $statistics['approved'] }}</div>
                    <div class="stat-label">Approved</div>
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
                    <div class="stat-value">{{ $statistics['pending'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                    <i class="bi bi-star-half"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $statistics['average_rating'] }}</div>
                    <div class="stat-label">Avg Rating</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-3">
            <!-- Search -->
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" 
                       class="form-control" 
                       id="search" 
                       name="search" 
                       value="{{ $filters['search'] ?? '' }}" 
                       placeholder="Search by title or comment...">
            </div>

            <!-- Rating Filter -->
            <div class="col-md-2">
                <label for="rating" class="form-label">Rating</label>
                <select class="form-select" id="rating" name="rating">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ ($filters['rating'] ?? '') == $i ? 'selected' : '' }}>
                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Product Filter -->
            <div class="col-md-3">
                <label for="product_id" class="form-label">Product</label>
                <select class="form-select" id="product_id" name="product_id">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ ($filters['product_id'] ?? '') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort -->
            <div class="col-md-2">
                <label for="sort_by" class="form-label">Sort By</label>
                <select class="form-select" id="sort_by" name="sort_by">
                    <option value="created_at" {{ ($filters['sort_by'] ?? 'created_at') == 'created_at' ? 'selected' : '' }}>Date</option>
                    <option value="rating" {{ ($filters['sort_by'] ?? '') == 'rating' ? 'selected' : '' }}>Rating</option>
                </select>
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

<!-- Reviews List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Reviews ({{ $reviews->total() }})</h5>
    </div>
    <div class="card-body p-0">
        @if($reviews->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr>
                        <!-- Product -->
                        <td>
                            <div class="d-flex align-items-center">
                                @if($review->product->productImages->first())
                                <img src="{{ asset('storage/' . $review->product->productImages->first()->image_path) }}" 
                                     alt="{{ $review->product->name }}" 
                                     class="rounded me-2"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-medium">{{ Str::limit($review->product->name, 30) }}</div>
                                    <small class="text-muted">{{ $review->product->sku }}</small>
                                </div>
                            </div>
                        </td>

                        <!-- Customer -->
                        <td>
                            <div>
                                <div class="fw-medium">{{ $review->user->name }}</div>
                                <small class="text-muted">{{ $review->user->email }}</small>
                            </div>
                        </td>

                        <!-- Rating -->
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning text-dark me-2">
                                    <i class="bi bi-star-fill"></i> {{ $review->rating }}
                                </span>
                                @if($review->verified_purchase)
                                <span class="badge bg-success" title="Verified Purchase">
                                    <i class="bi bi-check-circle"></i>
                                </span>
                                @endif
                            </div>
                        </td>

                        <!-- Review -->
                        <td>
                            <div>
                                @if($review->title)
                                <div class="fw-medium">{{ Str::limit($review->title, 40) }}</div>
                                @endif
                                <div class="text-muted small">{{ Str::limit($review->comment, 60) }}</div>
                                @if($review->images->count() > 0)
                                <small class="text-primary">
                                    <i class="bi bi-image"></i> {{ $review->images->count() }} image(s)
                                </small>
                                @endif
                            </div>
                        </td>

                        <!-- Date -->
                        <td>
                            <small>{{ $review->created_at->format('d M Y') }}</small>
                            <br>
                            <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                        </td>

                        <!-- Actions -->
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.reviews.show', $review) }}" 
                                   class="btn btn-outline-primary"
                                   title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger"
                                        onclick="deleteReview({{ $review->id }})"
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
            {{ $reviews->links('vendor.pagination.bootstrap-5-simple') }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-star" style="font-size: 4rem; color: #dee2e6;"></i>
            <p class="mt-3 text-muted">No reviews found.</p>
        </div>
        @endif
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function deleteReview(id) {
        if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
            const form = document.getElementById('delete-form');
            form.action = `/admin/reviews/${id}`;
            form.submit();
        }
    }
</script>
@endpush
