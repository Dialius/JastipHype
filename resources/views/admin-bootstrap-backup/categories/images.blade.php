@extends('admin.layouts.app')

@section('title', 'Category Images - Shop by Category')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Shop by Category Images</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">Content</a></li>
                <li class="breadcrumb-item active">Category Images</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Upload Category Images</h5>
                <p class="text-muted small mb-0 mt-2">Upload gambar untuk 4 kategori yang ditampilkan di bagian "Shop by Category" homepage</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.images.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    @foreach($categories as $index => $category)
                    <div class="mb-4 pb-4 {{ $index < count($categories) - 1 ? 'border-bottom' : '' }}">
                        <input type="hidden" name="categories[{{ $category->id }}][id]" value="{{ $category->id }}">
                        
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6 class="mb-2">{{ $category->name }}</h6>
                                <p class="text-muted small mb-0">{{ $category->products_count }} products</p>
                            </div>
                            
                            <div class="col-md-8">
                                <!-- Current Image -->
                                @if($category->image)
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Current Image</label>
                                    <div class="border rounded p-2 bg-light">
                                        <img src="{{ Storage::url($category->image) }}" 
                                             alt="{{ $category->name }}" 
                                             class="img-fluid rounded"
                                             style="max-height: 200px; object-fit: cover;">
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Upload New Image -->
                                <div>
                                    <label for="image_{{ $category->id }}" class="form-label">
                                        {{ $category->image ? 'Replace Image' : 'Upload Image' }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('categories.'.$category->id.'.image') is-invalid @enderror" 
                                           id="image_{{ $category->id }}" 
                                           name="categories[{{ $category->id }}][image]" 
                                           accept="image/jpeg,image/jpg,image/png,image/webp"
                                           onchange="previewImage(event, {{ $category->id }})">
                                    @error('categories.'.$category->id.'.image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Recommended: 600x600px (1:1 square). Max 2MB</div>
                                </div>
                                
                                <!-- Preview -->
                                <div id="preview_{{ $category->id }}" class="mt-3" style="display: none;">
                                    <label class="form-label small text-muted">Preview</label>
                                    <div class="border rounded p-2 bg-light">
                                        <img id="preview_img_{{ $category->id }}" 
                                             src="" 
                                             alt="Preview" 
                                             class="img-fluid rounded"
                                             style="max-height: 200px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Actions -->
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update All Images
                        </button>
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Specifications Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Image Specifications</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-grid text-success fs-4"></i>
                        </div>
                        <div>
                            <strong>Shop by Category</strong>
                            <div class="text-muted small">Grid 4 kolom</div>
                        </div>
                    </div>
                    <p class="small text-muted mb-2"><strong>Untuk:</strong> Bagian "Shop by Category" di homepage</p>
                    <p class="small text-muted mb-2"><strong>Ukuran:</strong> 600x600px per kategori (1:1 square)</p>
                    <p class="small text-muted mb-2"><strong>Format:</strong> JPG, PNG, atau WebP</p>
                    <p class="small text-muted mb-2"><strong>Max Size:</strong> 2MB per file</p>
                </div>

                <hr>

                <div class="mb-4">
                    <h6 class="mb-3">4 Kategori Tetap</h6>
                    <ul class="small text-muted mb-0">
                        <li><strong>Accessories</strong> - Tas, belt, topi, dll</li>
                        <li><strong>Clothing</strong> - Kaos, kemeja, celana, dll</li>
                        <li><strong>Hoodies</strong> - Hoodie, sweater, jaket</li>
                        <li><strong>Sneakers</strong> - Sepatu sneakers</li>
                    </ul>
                    <p class="small text-info mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Kategori ini tetap dan tidak bisa ditambah/dikurangi. Hanya gambarnya yang bisa diubah.
                    </p>
                </div>

                <hr>

                <div class="mb-4">
                    <h6 class="mb-3">Tips Upload Gambar</h6>
                    <ul class="small text-muted mb-0">
                        <li>Gunakan gambar square 600x600px</li>
                        <li>Gambar harus jelas dan menarik</li>
                        <li>Pilih gambar yang representatif untuk kategori</li>
                        <li>Gunakan gambar berkualitas tinggi</li>
                        <li>Hindari gambar dengan teks terlalu banyak</li>
                        <li>Pastikan gambar tidak blur atau pixelated</li>
                    </ul>
                </div>

                <hr>

                <div>
                    <h6 class="mb-3">Preview Homepage</h6>
                    <p class="small text-muted mb-2">Gambar akan muncul di homepage dalam grid 2x2 (mobile) atau 4 kolom (desktop)</p>
                    <div class="border rounded p-2 bg-light">
                        <div class="row g-2">
                            @foreach($categories as $category)
                            <div class="col-6">
                                <div class="bg-dark rounded" style="aspect-ratio: 1/1; position: relative;">
                                    @if($category->image)
                                    <img src="{{ Storage::url($category->image) }}" 
                                         alt="{{ $category->name }}"
                                         class="w-100 h-100 rounded"
                                         style="object-fit: cover; opacity: 0.7;">
                                    @endif
                                    <div class="position-absolute bottom-0 start-0 p-2 text-white">
                                        <small class="fw-bold">{{ $category->name }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event, categoryId) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('preview_' + categoryId);
        const preview = document.getElementById('preview_img_' + categoryId);
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    }
</script>
@endpush
