@extends('admin.layouts.app')

@section('title', 'Add New Product')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Add New Product</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Products
        </a>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                               value="{{ old('slug') }}" placeholder="Leave empty to auto-generate">
                        <small class="text-muted">URL-friendly version of the name. Leave empty to auto-generate.</small>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SKU <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" 
                                   value="{{ old('sku') }}" required placeholder="e.g., NIKE-TS-001">
                            <button type="button" class="btn btn-outline-primary" id="generateSKU">
                                <i class="bi bi-magic"></i> Generate
                            </button>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> 
                            Format: BRAND-CATEGORY-NUMBER (e.g., NIKE-TS-001)
                        </small>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="6">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Pricing & Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="priceDisplay" class="form-control" placeholder="0" required>
                                <input type="hidden" name="price" id="priceValue" value="{{ old('price') }}">
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                Format: Rp 1.000.000
                            </small>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="stockDisplay" class="form-control" placeholder="0" required>
                                <span class="input-group-text">units</span>
                                <input type="hidden" name="stock" id="stockValue" value="{{ old('stock') }}">
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                Available quantity
                            </small>
                            @error('stock')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Weight (grams)</label>
                            <div class="input-group">
                                <input type="text" id="weightDisplay" class="form-control" placeholder="0">
                                <span class="input-group-text">gr</span>
                                <input type="hidden" name="weight" id="weightValue" value="{{ old('weight') }}">
                            </div>
                            <small class="text-muted">For shipping calculation</small>
                            @error('weight')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Images</h5>
                    <small class="text-muted">Upload product images with categories (Front, Back, Detail, Other)</small>
                </div>
                <div class="card-body">
                    <!-- Image Size Recommendation -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading mb-2">
                            <i class="bi bi-info-circle"></i> Recommended Image Size
                        </h6>
                        <ul class="mb-0 small">
                            <li><strong>Ideal Size:</strong> 800 x 1000 px (4:5 Ratio)</li>
                            <li><strong>Minimum Size:</strong> 600 x 750 px</li>
                            <li><strong>Format:</strong> JPG, PNG, WEBP</li>
                            <li><strong>File Size:</strong> Maximum 2MB per image</li>
                            <li><strong>Background:</strong> White or transparent (for consistency)</li>
                        </ul>
                    </div>

                    <!-- Image Upload Sections -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-image text-primary"></i> Front Image
                        </label>
                        <input type="file" name="images[front][]" class="form-control image-input" 
                               multiple accept="image/jpeg,image/png,image/jpg,image/webp" data-type="front">
                        <small class="text-muted">Front view photo - Main image displayed first</small>
                        <div class="image-preview-container row g-2 mt-2" data-type="front"></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-image text-success"></i> Back Image
                        </label>
                        <input type="file" name="images[back][]" class="form-control image-input" 
                               multiple accept="image/jpeg,image/png,image/jpg,image/webp" data-type="back">
                        <small class="text-muted">Back view photo</small>
                        <div class="image-preview-container row g-2 mt-2" data-type="back"></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-zoom-in text-warning"></i> Detail Images
                        </label>
                        <input type="file" name="images[detail][]" class="form-control image-input" 
                               multiple accept="image/jpeg,image/png,image/jpg,image/webp" data-type="detail">
                        <small class="text-muted">Product detail photos (logo, stitching, material, label, etc)</small>
                        <div class="image-preview-container row g-2 mt-2" data-type="detail"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-images text-secondary"></i> Other Images
                        </label>
                        <input type="file" name="images[other][]" class="form-control image-input" 
                               multiple accept="image/jpeg,image/png,image/jpg,image/webp" data-type="other">
                        <small class="text-muted">Other photos (packaging, tags, accessories, lifestyle, etc.)</small>
                        <div class="image-preview-container row g-2 mt-2" data-type="other"></div>
                    </div>

                    @error('images')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Category & Brand -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Organization</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">
                            Active (visible to customers)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-check-circle me-2"></i>Create Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Number Formatting Functions
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function unformatNumber(str) {
        return str.replace(/\./g, '');
    }
    
    // Price Input Formatting
    const priceDisplay = document.getElementById('priceDisplay');
    const priceValue = document.getElementById('priceValue');
    
    if (priceDisplay) {
        // Set initial value if exists
        if (priceValue.value) {
            priceDisplay.value = formatNumber(priceValue.value);
        }
        
        priceDisplay.addEventListener('input', function(e) {
            let value = unformatNumber(e.target.value);
            
            // Remove non-numeric characters
            value = value.replace(/[^0-9]/g, '');
            
            // Update hidden input with raw value
            priceValue.value = value;
            
            // Format display value
            if (value) {
                e.target.value = formatNumber(value);
            }
        });
        
        priceDisplay.addEventListener('blur', function(e) {
            if (!e.target.value) {
                e.target.value = '0';
                priceValue.value = '0';
            }
        });
    }
    
    // Stock Input Formatting
    const stockDisplay = document.getElementById('stockDisplay');
    const stockValue = document.getElementById('stockValue');
    
    if (stockDisplay) {
        // Set initial value if exists
        if (stockValue.value) {
            stockDisplay.value = formatNumber(stockValue.value);
        }
        
        stockDisplay.addEventListener('input', function(e) {
            let value = unformatNumber(e.target.value);
            
            // Remove non-numeric characters
            value = value.replace(/[^0-9]/g, '');
            
            // Update hidden input with raw value
            stockValue.value = value;
            
            // Format display value
            if (value) {
                e.target.value = formatNumber(value);
            }
        });
        
        stockDisplay.addEventListener('blur', function(e) {
            if (!e.target.value) {
                e.target.value = '0';
                stockValue.value = '0';
            }
        });
    }
    
    // Weight Input Formatting
    const weightDisplay = document.getElementById('weightDisplay');
    const weightValue = document.getElementById('weightValue');
    
    if (weightDisplay) {
        // Set initial value if exists
        if (weightValue.value) {
            weightDisplay.value = formatNumber(weightValue.value);
        }
        
        weightDisplay.addEventListener('input', function(e) {
            let value = unformatNumber(e.target.value);
            
            // Remove non-numeric characters
            value = value.replace(/[^0-9]/g, '');
            
            // Update hidden input with raw value
            weightValue.value = value;
            
            // Format display value
            if (value) {
                e.target.value = formatNumber(value);
            }
        });
        
        weightDisplay.addEventListener('blur', function(e) {
            if (!e.target.value) {
                e.target.value = '0';
                weightValue.value = '0';
            }
        });
    }
    
    // SKU Auto-Generator
    document.getElementById('generateSKU')?.addEventListener('click', function() {
        const brandSelect = document.querySelector('select[name="brand_id"]');
        const categorySelect = document.querySelector('select[name="category_id"]');
        const skuInput = document.getElementById('sku');
        
        if (!brandSelect.value || !categorySelect.value) {
            alert('Please select Brand and Category first');
            return;
        }
        
        // Get brand name
        const brandName = brandSelect.options[brandSelect.selectedIndex].text;
        const brandCode = brandName.substring(0, 4).toUpperCase().replace(/[^A-Z]/g, '');
        
        // Get category name
        const categoryName = categorySelect.options[categorySelect.selectedIndex].text;
        const categoryCode = categoryName.substring(0, 2).toUpperCase().replace(/[^A-Z]/g, '');
        
        // Generate random number
        const randomNum = Math.floor(Math.random() * 900) + 100; // 100-999
        
        // Format: BRAND-CAT-NUM
        const sku = `${brandCode}-${categoryCode}-${randomNum}`;
        
        skuInput.value = sku;
        
        // Show success feedback
        skuInput.classList.add('is-valid');
        setTimeout(() => {
            skuInput.classList.remove('is-valid');
        }, 2000);
    });
    
    // Image Preview for each category
    document.querySelectorAll('.image-input').forEach(input => {
        input.addEventListener('change', function(e) {
            const type = this.getAttribute('data-type');
            const previewContainer = document.querySelector(`.image-preview-container[data-type="${type}"]`);
            previewContainer.innerHTML = '';
            
            const files = Array.from(e.target.files);
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3';
                        
                        const typeLabels = {
                            'front': 'Depan',
                            'back': 'Belakang',
                            'detail': 'Detail',
                            'other': 'Lainnya'
                        };
                        
                        col.innerHTML = `
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" alt="Preview" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <small class="text-muted d-block">${file.name}</small>
                                    <span class="badge bg-primary">${typeLabels[type]}</span>
                                </div>
                            </div>
                        `;
                        
                        previewContainer.appendChild(col);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        });
    });
</script>
@endpush
