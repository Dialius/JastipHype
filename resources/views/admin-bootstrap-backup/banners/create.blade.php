@extends('admin.layouts.app')

@section('title', 'Create Banner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Create New Banner</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">Banners</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Banner Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" id="banner-form">
                    @csrf
                    
                    <!-- Banner Type - PALING ATAS -->
                    <div class="mb-4">
                        <label for="type" class="form-label">Banner Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" 
                                name="type" 
                                required
                                onchange="updateFormFields()">
                            <option value="">-- Select Banner Type --</option>
                            <option value="hero" {{ old('type') == 'hero' ? 'selected' : '' }}>
                                🎯 Hero Banner - Main homepage banner (Full screen with countdown)
                            </option>
                            <option value="limited" {{ old('type') == 'limited' ? 'selected' : '' }}>
                                ⭐ Limited Edition Banner - "Limited Edition Drops" section (Product showcase)
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="type-info" class="alert alert-info mt-2" style="display: none;">
                            <small id="type-description"></small>
                        </div>
                        <div class="alert alert-warning mt-2">
                            <small>
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Note:</strong> To manage "Shop by Category" images, use the 
                                <a href="{{ route('admin.categories.images.edit') }}" class="alert-link">Category Images</a> menu
                            </small>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Banner Title <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Optional: Short description for the banner</div>
                    </div>

                    <!-- Product Selection -->
                    <div class="mb-3" id="product-field">
                        <label for="product_id" class="form-label">
                            Link to Product 
                            <span class="text-danger" id="product-required" style="display: none;">*</span>
                            <span class="text-muted" id="product-optional">(Optional)</span>
                        </label>
                        <select class="form-select @error('product_id') is-invalid @enderror" 
                                id="product_id" 
                                name="product_id">
                            <option value="">-- No Product Link --</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->brand->name ?? 'No Brand' }} - {{ $product->name }} (Stock: {{ $product->stock }})
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="product-help"></div>
                    </div>

                    <!-- Countdown Settings - HANYA UNTUK HERO -->
                    <div id="countdown-fields" style="display: none;">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="show_countdown" name="show_countdown" value="1" {{ old('show_countdown', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_countdown">
                                    Show Countdown Timer
                                </label>
                            </div>
                            <div class="form-text">Enable/disable countdown timer display</div>
                        </div>

                        <div class="mb-3">
                            <label for="countdown_target" class="form-label">Countdown Target Date</label>
                            <input type="datetime-local" 
                                   class="form-control @error('countdown_target') is-invalid @enderror" 
                                   id="countdown_target" 
                                   name="countdown_target" 
                                   value="{{ old('countdown_target') }}">
                            @error('countdown_target')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Target date/time for countdown (leave empty for default)</div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3" id="image-field">
                        <label for="image" class="form-label">Banner Image</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/jpeg,image/jpg,image/png,image/webp"
                               onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Optional if product is selected. Accepted formats: JPG, PNG, WebP. Max size: 2MB</div>
                    </div>

                    <!-- Image Preview -->
                    <div class="mb-3" id="preview-container" style="display: none;">
                        <label class="form-label">Preview</label>
                        <div class="border rounded p-2 bg-light">
                            <img id="image-preview" src="" alt="Preview" class="img-fluid" style="max-height: 300px;">
                        </div>
                    </div>

                    <!-- Button Fields - HANYA UNTUK HERO -->
                    <div id="button-fields" style="display: none;">
                        <!-- Button Text -->
                        <div class="mb-3">
                            <label for="button_text" class="form-label">Button Text</label>
                            <input type="text" 
                                   class="form-control @error('button_text') is-invalid @enderror" 
                                   id="button_text" 
                                   name="button_text" 
                                   value="{{ old('button_text') }}" 
                                   placeholder="Shop Now">
                            @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Custom button text (default: "Shop Now" for products)</div>
                        </div>

                        <!-- Button Link -->
                        <div class="mb-3">
                            <label for="button_link" class="form-label">Button Link</label>
                            <input type="url" 
                                   class="form-control @error('button_link') is-invalid @enderror" 
                                   id="button_link" 
                                   name="button_link" 
                                   value="{{ old('button_link') }}" 
                                   placeholder="https://example.com">
                            @error('button_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Custom button link (auto-generated for products)</div>
                        </div>
                    </div>

                    <!-- General Link -->
                    <div class="mb-3">
                        <label for="link" class="form-label">General Link URL</label>
                        <input type="url" 
                               class="form-control @error('link') is-invalid @enderror" 
                               id="link" 
                               name="link" 
                               value="{{ old('link') }}" 
                               placeholder="https://example.com">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Optional: URL to redirect when banner background is clicked</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('is_active') is-invalid @enderror" 
                                id="is_active" 
                                name="is_active" 
                                required>
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Schedule -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="datetime-local" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: When banner should start showing</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="datetime-local" 
                                   class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: When banner should stop showing</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Create Banner
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
                <h5 class="mb-0">Banner Specifications</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-image text-primary fs-4"></i>
                        </div>
                        <div>
                            <strong>Hero Banner</strong>
                            <div class="text-muted small">Full screen - 1920x1080px</div>
                        </div>
                    </div>
                    <p class="small text-muted mb-2"><strong>Untuk:</strong> Banner utama homepage paling atas</p>
                    <p class="small text-muted mb-2"><strong>Ukuran:</strong> 1920x1080px (16:9 landscape)</p>
                    <p class="small text-muted mb-2"><strong>Yang Bisa Diatur:</strong></p>
                    <ul class="small text-muted mb-0">
                        <li>Gambar background (product atau upload)</li>
                        <li>Brand name (otomatis dari product)</li>
                        <li>Title & Description</li>
                        <li>Countdown timer (on/off)</li>
                        <li>Target countdown date</li>
                        <li>Button text & link</li>
                    </ul>
                </div>

                <hr>

                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-star text-warning fs-4"></i>
                        </div>
                        <div>
                            <strong>Limited Edition Banner</strong>
                            <div class="text-muted small">2 kolom - 800x800px</div>
                        </div>
                    </div>
                    <p class="small text-muted mb-2"><strong>Untuk:</strong> Bagian "Limited Edition Drops"</p>
                    <p class="small text-muted mb-2"><strong>Ukuran:</strong> 800x800px (1:1 square)</p>
                    <p class="small text-muted mb-2"><strong>Yang Bisa Diatur:</strong></p>
                    <ul class="small text-muted mb-0">
                        <li>Pilih product limited edition</li>
                        <li>Brand name (otomatis dari product)</li>
                        <li>Product name & description</li>
                        <li>Stock bar (otomatis dari stok product)</li>
                        <li>Harga (otomatis dari product)</li>
                        <li>Gambar product (otomatis)</li>
                    </ul>
                    <p class="small text-info mt-2 mb-0"><i class="bi bi-info-circle me-1"></i>Semua data otomatis dari product yang dipilih</p>
                </div>

                <hr>

                <div class="alert alert-warning">
                    <h6 class="mb-2"><i class="bi bi-info-circle me-1"></i> Shop by Category</h6>
                    <p class="small mb-2">To manage category images in the "Shop by Category" section, use the dedicated page:</p>
                    <a href="{{ route('admin.categories.images.edit') }}" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-grid-3x3-gap me-1"></i> Category Images
                    </a>
                </div>

                <hr>

                <h6 class="mb-3">Tips Upload Gambar</h6>
                <ul class="small text-muted mb-0">
                    <li><strong>Hero:</strong> Gunakan gambar landscape 1920x1080px</li>
                    <li><strong>Limited:</strong> Pilih product, gambar otomatis dari product</li>
                    <li><strong>Category:</strong> Gunakan menu <a href="{{ route('admin.categories.images.edit') }}">Category Images</a></li>
                    <li>Format: JPG, PNG, atau WebP</li>
                    <li>Max size: 2MB per file</li>
                    <li>Gunakan gambar berkualitas tinggi</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Type descriptions
    const typeInfo = {
        hero: {
            title: '🎯 Hero Banner',
            description: '<strong>Untuk:</strong> Banner utama homepage paling atas<br><strong>Ukuran:</strong> 1920x1080px (16:9 landscape)<br><strong>Fitur:</strong> Countdown timer, full screen, product showcase',
            productRequired: false,
            productHelp: 'Pilih product untuk menampilkan gambar dan info product, atau upload gambar sendiri',
            showCountdown: true,
            showImage: true,
            showButtons: true
        },
        limited: {
            title: '⭐ Limited Edition Banner',
            description: '<strong>Untuk:</strong> Bagian "Limited Edition Drops"<br><strong>Ukuran:</strong> 800x800px (otomatis dari product)<br><strong>Catatan:</strong> Semua data otomatis dari product (brand, stock, harga, gambar)',
            productRequired: true,
            productHelp: 'WAJIB: Pilih product limited edition. Semua data akan otomatis dari product.',
            showCountdown: false,
            showImage: false,
            showButtons: false
        }
    };

    function updateFormFields() {
        const type = document.getElementById('type').value;
        const typeInfoDiv = document.getElementById('type-info');
        const typeDescription = document.getElementById('type-description');
        const productRequired = document.getElementById('product-required');
        const productOptional = document.getElementById('product-optional');
        const productHelp = document.getElementById('product-help');
        const countdownFields = document.getElementById('countdown-fields');
        const imageField = document.getElementById('image-field');
        const buttonFields = document.getElementById('button-fields');
        const productField = document.getElementById('product_id');

        if (type && typeInfo[type]) {
            const info = typeInfo[type];
            
            // Show type info
            typeInfoDiv.style.display = 'block';
            typeDescription.innerHTML = info.description;
            
            // Product field
            productHelp.innerHTML = info.productHelp;
            if (info.productRequired) {
                productRequired.style.display = 'inline';
                productOptional.style.display = 'none';
                productField.required = true;
            } else {
                productRequired.style.display = 'none';
                productOptional.style.display = 'inline';
                productField.required = false;
            }
            
            // Countdown fields (only for hero)
            countdownFields.style.display = info.showCountdown ? 'block' : 'none';
            
            // Image field (hide for limited - uses product image)
            if (imageField) {
                imageField.style.display = info.showImage ? 'block' : 'none';
            }
            
            // Button fields (only for hero)
            if (buttonFields) {
                buttonFields.style.display = info.showButtons ? 'block' : 'none';
            }
        } else {
            typeInfoDiv.style.display = 'none';
            countdownFields.style.display = 'none';
        }
    }

    function previewImage(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('preview-container');
        const preview = document.getElementById('image-preview');
        
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateFormFields();
    });
</script>
@endpush
