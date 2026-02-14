@extends('admin.layouts.app')

@section('title', 'Product Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1" style="color: #1a1a1a;">
                        <i class="bi bi-box-seam text-primary me-2"></i>
                        Product Management
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Manage your product catalog and inventory
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Add New Product
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #2196F3 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Products</p>
                            <h3 class="mb-0 fw-bold">{{ $products->count() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-box-seam fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #28A745 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Active Products</p>
                            <h3 class="mb-0 fw-bold">{{ $products->where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-check-circle fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #FFC107 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Low Stock</p>
                            <h3 class="mb-0 fw-bold">{{ $products->where('stock', '<', 10)->where('stock', '>', 0)->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-exclamation-triangle fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #DC3545 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Out of Stock</p>
                            <h3 class="mb-0 fw-bold">{{ $products->where('stock', 0)->count() }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-x-circle fs-4 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-list-ul text-primary me-2"></i>
                        Product List
                    </h5>
                </div>
                <div class="col-auto">
                    <span class="badge bg-light text-dark">{{ $products->count() }} items</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 datatable" data-export="true" id="productsTable">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th class="px-4 py-3" style="width: 60px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th class="py-3">Product</th>
                            <th class="py-3">SKU</th>
                            <th class="py-3">Category</th>
                            <th class="py-3">Brand</th>
                            <th class="py-3">Price</th>
                            <th class="py-3 text-center">Stock</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr class="border-bottom">
                            <td class="px-4">
                                <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <img src="{{ product_image_url($product) }}" 
                                             alt="{{ $product->name }}" 
                                             class="rounded"
                                             style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #e3e6f0;">
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark mb-1">{{ Str::limit($product->name, 40) }}</div>
                                        @if($product->is_featured)
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">
                                                <i class="bi bi-star-fill"></i> Featured
                                            </span>
                                        @endif
                                        @if($product->productImages->count() > 0)
                                            <small class="text-muted d-block">
                                                <i class="bi bi-images"></i> {{ $product->productImages->count() }} images
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <code class="bg-light px-2 py-1 rounded">{{ $product->sku ?? '-' }}</code>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                    {{ $product->brand->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="fw-semibold text-dark">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                @if($product->sale_price)
                                    <small class="text-muted text-decoration-line-through">
                                        Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                                    </small>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                @if($product->stock > 10)
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>{{ $product->stock }}
                                    </span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-warning px-3 py-2">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $product->stock }}
                                    </span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>0
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                @if($product->is_active)
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                        <i class="bi bi-pause-circle-fill me-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip" 
                                       title="Edit Product">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip" 
                                                title="Delete Product">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <h5 class="text-muted mb-2">No Products Found</h5>
                                    <p class="text-muted mb-3">Start by adding your first product</p>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>Add Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fc;
    }
    
    .btn-group .btn {
        border-radius: 0;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    
    code {
        font-size: 0.875rem;
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Select all checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endpush
