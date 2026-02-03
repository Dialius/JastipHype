@extends('admin.layouts.app')

@section('title', 'Banner Management')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Banner Management</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Banners</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Banner
        </a>
    </div>
</div>

<!-- Banner Type Specifications -->
<div class="card mb-4">
    <div class="card-body">
        <h6 class="card-title mb-3">Banner Specifications</h6>
        <div class="row">
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                        <i class="bi bi-image text-primary fs-4"></i>
                    </div>
                    <div>
                        <strong>Hero Banner</strong>
                        <div class="text-muted small">1920 x 600 px</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                        <i class="bi bi-image text-success fs-4"></i>
                    </div>
                    <div>
                        <strong>Secondary Banner</strong>
                        <div class="text-muted small">1200 x 400 px</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                        <i class="bi bi-image text-warning fs-4"></i>
                    </div>
                    <div>
                        <strong>Promo Banner</strong>
                        <div class="text-muted small">800 x 300 px</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Banners List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Banners</h5>
    </div>
    <div class="card-body">
        @if($banners->count() > 0)
        <div id="banners-sortable" class="row g-3">
            @foreach($banners as $banner)
            <div class="col-md-6 col-lg-4" data-id="{{ $banner->id }}">
                <div class="card h-100 banner-card">
                    <!-- Banner Image -->
                    <div class="position-relative">
                        @if($banner->image_path)
                        <img src="{{ asset('storage/' . $banner->image_path) }}" 
                             class="card-img-top" 
                             alt="{{ $banner->title }}"
                             style="height: 200px; object-fit: cover;">
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 m-2">
                            @php
                                $now = now();
                                $isActive = $banner->status === 'active';
                                $isScheduled = $banner->start_date && $now->lt($banner->start_date);
                                $isExpired = $banner->end_date && $now->gt($banner->end_date);
                                
                                if ($isExpired) {
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
                        </div>
                        
                        <!-- Drag Handle -->
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-dark bg-opacity-50 drag-handle" style="cursor: move;">
                                <i class="bi bi-grip-vertical"></i>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Banner Info -->
                    <div class="card-body">
                        <h6 class="card-title">{{ $banner->title }}</h6>
                        <p class="card-text text-muted small mb-2">
                            <span class="badge bg-light text-dark">{{ ucfirst($banner->type) }}</span>
                            <span class="badge bg-light text-dark">Order: {{ $banner->order }}</span>
                        </p>
                        
                        @if($banner->link_url)
                        <p class="card-text small mb-2">
                            <i class="bi bi-link-45deg"></i>
                            <a href="{{ $banner->link_url }}" target="_blank" class="text-decoration-none">
                                {{ Str::limit($banner->link_url, 30) }}
                            </a>
                        </p>
                        @endif
                        
                        @if($banner->start_date || $banner->end_date)
                        <p class="card-text small text-muted mb-2">
                            @if($banner->start_date)
                            <i class="bi bi-calendar-check"></i> {{ $banner->start_date->format('d M Y') }}
                            @endif
                            @if($banner->end_date)
                            - {{ $banner->end_date->format('d M Y') }}
                            @endif
                        </p>
                        @endif
                    </div>
                    
                    <!-- Actions -->
                    <div class="card-footer bg-transparent">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('admin.banners.edit', $banner) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-{{ $banner->status === 'active' ? 'warning' : 'success' }}"
                                    onclick="toggleStatus({{ $banner->id }})">
                                <i class="bi bi-{{ $banner->status === 'active' ? 'pause' : 'play' }}-circle"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="deleteBanner({{ $banner->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-image" style="font-size: 4rem; color: #dee2e6;"></i>
            <p class="mt-3 text-muted">No banners yet. Create your first banner!</p>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Banner
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

@push('styles')
<style>
    .banner-card {
        transition: all 0.3s ease;
    }
    
    .banner-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .drag-handle {
        cursor: move;
    }
    
    .sortable-ghost {
        opacity: 0.4;
    }
</style>
@endpush

@push('scripts')
<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    // Initialize Sortable
    const sortable = new Sortable(document.getElementById('banners-sortable'), {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        onEnd: function(evt) {
            // Get new order
            const items = Array.from(evt.to.children);
            const order = items.map(item => item.dataset.id);
            
            // Send to server
            fetch('{{ route("admin.banners.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: order })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showToast('Banner order updated successfully', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to update banner order', 'error');
            });
        }
    });
    
    // Toggle banner status
    function toggleStatus(id) {
        if (confirm('Are you sure you want to toggle this banner status?')) {
            const form = document.getElementById('toggle-form');
            form.action = `/admin/banners/${id}/toggle-status`;
            form.submit();
        }
    }
    
    // Delete banner
    function deleteBanner(id) {
        if (confirm('Are you sure you want to delete this banner? This action cannot be undone.')) {
            const form = document.getElementById('delete-form');
            form.action = `/admin/banners/${id}`;
            form.submit();
        }
    }
    
    // Show toast notification
    function showToast(message, type) {
        // Simple toast implementation
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>
@endpush
