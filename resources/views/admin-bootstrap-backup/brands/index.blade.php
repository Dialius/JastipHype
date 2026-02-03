@extends('admin.layouts.app')

@section('title', 'Brands')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Brands</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Brands</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Brand
        </a>
    </div>
</div>

<!-- Brands Grid -->
<div class="row" id="brandsContainer">
    @forelse($brands as $brand)
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4" data-brand-id="{{ $brand->id }}">
        <div class="card h-100 brand-card">
            <div class="card-body">
                <!-- Logo -->
                <div class="text-center mb-3 position-relative">
                    @if($brand->logo)
                        <img src="{{ asset('images/brands/' . $brand->logo) }}" 
                             alt="{{ $brand->name }}" 
                             class="brand-logo"
                             style="max-width: 120px; max-height: 120px; object-fit: contain;">
                    @elseif($brand->logo_path)
                        <img src="{{ Storage::url($brand->logo_path) }}" 
                             alt="{{ $brand->name }}" 
                             class="brand-logo"
                             style="max-width: 120px; max-height: 120px; object-fit: contain;">
                    @else
                        <div class="brand-logo-placeholder">
                            <i class="bi bi-image" style="font-size: 3rem; color: #cbd5e0;"></i>
                        </div>
                    @endif
                    
                    <!-- Featured Badge -->
                    @if($brand->is_featured)
                        <span class="position-absolute top-0 end-0 badge bg-warning">
                            <i class="bi bi-star-fill"></i> Featured
                        </span>
                    @endif
                </div>

                <!-- Brand Info -->
                <div class="text-center mb-3">
                    <h5 class="mb-1">{{ $brand->name }}</h5>
                    @if($brand->description)
                        <p class="text-muted small mb-2">{{ Str::limit($brand->description, 60) }}</p>
                    @endif
                    
                    <!-- Status Badge -->
                    <span class="badge bg-{{ $brand->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($brand->status) }}
                    </span>
                </div>

                <!-- Statistics -->
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <small class="text-muted d-block">Products</small>
                        <strong>{{ $brand->product_count ?? 0 }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Revenue</small>
                        <strong>Rp {{ number_format(($brand->total_revenue ?? 0) / 1000, 0) }}K</strong>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                       class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger delete-btn" 
                            data-id="{{ $brand->id }}"
                            data-name="{{ $brand->name }}">
                        <i class="bi bi-trash"></i>
                    </button>
                    <button type="button" 
                            class="btn btn-sm btn-outline-secondary toggle-status-btn" 
                            data-id="{{ $brand->id }}"
                            title="Toggle Status">
                        <i class="bi bi-toggle-{{ $brand->status === 'active' ? 'on' : 'off' }}"></i>
                    </button>
                </div>

                <!-- Drag Handle -->
                <div class="drag-handle text-center mt-2">
                    <i class="bi bi-grip-vertical text-muted"></i>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #858796;"></i>
                <p class="mt-3 mb-0 text-muted">No brands found</p>
                <a href="{{ route('admin.brands.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-circle me-2"></i>Add Your First Brand
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete brand <strong id="deleteBrandName"></strong>?</p>
                <p class="text-danger small mb-0">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    This action cannot be undone. Make sure the brand has no products.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .brand-card {
        transition: all 0.3s ease;
        cursor: move;
    }
    
    .brand-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .brand-logo-placeholder {
        width: 120px;
        height: 120px;
        background: #f8f9fc;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .drag-handle {
        cursor: move;
        padding: 0.5rem;
        margin: -0.5rem -1rem -1rem;
        border-top: 1px solid var(--border-color);
    }
    
    .drag-handle:hover {
        background: #f8f9fc;
    }
    
    .sortable-ghost {
        opacity: 0.4;
    }
    
    .sortable-drag {
        opacity: 0.8;
    }
</style>
@endpush

@push('scripts')
<!-- SortableJS for drag-and-drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    // Initialize Sortable for drag-and-drop ordering
    const brandsContainer = document.getElementById('brandsContainer');
    
    if (brandsContainer && brandsContainer.children.length > 0) {
        new Sortable(brandsContainer, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                // Get new order
                const orders = [];
                const items = brandsContainer.querySelectorAll('[data-brand-id]');
                
                items.forEach((item, index) => {
                    orders.push({
                        id: item.dataset.brandId,
                        order: index
                    });
                });
                
                // Send to server
                fetch('/admin/brands/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ orders: orders })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Order updated successfully');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert on error
                    location.reload();
                });
            }
        });
    }
    
    // Delete Brand
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const brandId = this.dataset.id;
            const brandName = this.dataset.name;
            
            document.getElementById('deleteBrandName').textContent = brandName;
            document.getElementById('deleteForm').action = `/admin/brands/${brandId}`;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });
    
    // Toggle Status
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const brandId = this.dataset.id;
            const button = this;
            
            fetch(`/admin/brands/${brandId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button icon
                    const icon = button.querySelector('i');
                    icon.className = data.status === 'active' ? 'bi bi-toggle-on' : 'bi bi-toggle-off';
                    
                    // Update badge
                    const card = button.closest('.card');
                    const badge = card.querySelector('.badge');
                    badge.className = `badge bg-${data.status === 'active' ? 'success' : 'secondary'}`;
                    badge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    
                    console.log(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update status');
            });
        });
    });
</script>
@endpush
