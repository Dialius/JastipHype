@extends('admin.layouts.app')

@section('title', 'Edit Discount')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Edit Discount</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Discounts</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<form action="{{ route('admin.discounts.update', $discount) }}" method="POST" id="discount-form">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <!-- Code -->
                    <div class="mb-3">
                        <label for="code" class="form-label">Discount Code <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('code') is-invalid @enderror" 
                               id="code" 
                               name="code" 
                               value="{{ old('code', $discount->code) }}" 
                               required
                               style="text-transform: uppercase;"
                               placeholder="e.g., SUMMER2026">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Code will be automatically converted to uppercase</div>
                    </div>

                    <!-- Type & Value -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type" 
                                    required
                                    onchange="updateValueLabel()">
                                <option value="">Select Type</option>
                                <option value="percentage" {{ old('type', $discount->type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('type', $discount->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="value" class="form-label">
                                <span id="value-label">Discount Value</span> <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('value') is-invalid @enderror" 
                                   id="value" 
                                   name="value" 
                                   value="{{ old('value', $discount->value) }}" 
                                   required
                                   min="0"
                                   step="0.01">
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text" id="value-help">Enter discount value</div>
                        </div>
                    </div>

                    <!-- Min Order Amount -->
                    <div class="mb-3">
                        <label for="min_order_amount" class="form-label">Minimum Order Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                   class="form-control @error('min_order_amount') is-invalid @enderror" 
                                   id="min_order_amount" 
                                   name="min_order_amount" 
                                   value="{{ old('min_order_amount', $discount->min_order_amount) }}" 
                                   min="0"
                                   step="1000">
                            @error('min_order_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Optional: Minimum order amount required to use this discount</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="active" {{ old('status', $discount->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $discount->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Usage Limits</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="max_uses" class="form-label">Maximum Total Uses</label>
                            <input type="number" 
                                   class="form-control @error('max_uses') is-invalid @enderror" 
                                   id="max_uses" 
                                   name="max_uses" 
                                   value="{{ old('max_uses', $discount->max_uses) }}" 
                                   min="1">
                            @error('max_uses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave empty for unlimited uses</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="uses_per_customer" class="form-label">Uses Per Customer</label>
                            <input type="number" 
                                   class="form-control @error('uses_per_customer') is-invalid @enderror" 
                                   id="uses_per_customer" 
                                   name="uses_per_customer" 
                                   value="{{ old('uses_per_customer', $discount->uses_per_customer) }}" 
                                   min="1">
                            @error('uses_per_customer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave empty for unlimited per customer</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valid Period -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Valid Period</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="datetime-local" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date', $discount->start_date ? $discount->start_date->format('Y-m-d\TH:i') : '') }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave empty to start immediately</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="datetime-local" 
                                   class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date', $discount->end_date ? $discount->end_date->format('Y-m-d\TH:i') : '') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave empty for no expiration</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applicability -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Applicability</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="applicable_to" class="form-label">Apply To <span class="text-danger">*</span></label>
                        <select class="form-select @error('applicable_to') is-invalid @enderror" 
                                id="applicable_to" 
                                name="applicable_to" 
                                required
                                onchange="toggleApplicableIds()">
                            <option value="all" {{ old('applicable_to', $discount->applicable_to) == 'all' ? 'selected' : '' }}>All Products</option>
                            <option value="products" {{ old('applicable_to', $discount->applicable_to) == 'products' ? 'selected' : '' }}>Specific Products</option>
                            <option value="categories" {{ old('applicable_to', $discount->applicable_to) == 'categories' ? 'selected' : '' }}>Specific Categories</option>
                        </select>
                        @error('applicable_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Products Selection -->
                    <div id="products-selection" style="display: none;">
                        <label class="form-label">Select Products</label>
                        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.75rem;">
                            @foreach($products as $product)
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="applicable_ids[]" 
                                       value="{{ $product->id }}" 
                                       id="product_{{ $product->id }}"
                                       {{ in_array($product->id, old('applicable_ids', $discount->applicable_ids ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="product_{{ $product->id }}">
                                    {{ $product->name }} ({{ $product->sku }})
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Categories Selection -->
                    <div id="categories-selection" style="display: none;">
                        <label class="form-label">Select Categories</label>
                        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.75rem;">
                            @foreach($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="applicable_ids[]" 
                                       value="{{ $category->id }}" 
                                       id="category_{{ $category->id }}"
                                       {{ in_array($category->id, old('applicable_ids', $discount->applicable_ids ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category_{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Update Discount
                </button>
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
                <button type="button" 
                        class="btn btn-outline-danger ms-auto" 
                        onclick="deleteDiscount()">
                    <i class="bi bi-trash me-2"></i>Delete Discount
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Discount Info -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Discount Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Created:</small>
                        <div>{{ $discount->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Last Updated:</small>
                        <div>{{ $discount->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Total Uses:</small>
                        <div><strong>{{ number_format($discount->uses_count) }}</strong></div>
                    </div>
                    @if($discount->remaining_uses !== null)
                    <div>
                        <small class="text-muted">Remaining Uses:</small>
                        <div><strong>{{ number_format($discount->remaining_uses) }}</strong></div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Discount Guidelines</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Discount Types</h6>
                    <ul class="small mb-4">
                        <li><strong>Percentage:</strong> Discount as % of order total (e.g., 10% off)</li>
                        <li><strong>Fixed Amount:</strong> Fixed discount in Rupiah (e.g., Rp 50,000 off)</li>
                    </ul>

                    <h6 class="mb-3">Best Practices</h6>
                    <ul class="small mb-4">
                        <li>Use clear, memorable codes (e.g., SUMMER2026)</li>
                        <li>Set minimum order amounts to protect margins</li>
                        <li>Limit uses to prevent abuse</li>
                        <li>Set expiration dates for urgency</li>
                        <li>Test discount before activating</li>
                    </ul>

                    <h6 class="mb-3">Examples</h6>
                    <div class="small">
                        <div class="mb-2">
                            <strong>New Customer:</strong><br>
                            Code: WELCOME10<br>
                            Type: Percentage (10%)<br>
                            Min Order: Rp 100,000
                        </div>
                        <div class="mb-2">
                            <strong>Flash Sale:</strong><br>
                            Code: FLASH50K<br>
                            Type: Fixed (Rp 50,000)<br>
                            Max Uses: 100
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Delete Form -->
<form id="delete-form" action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function deleteDiscount() {
        if (confirm('Are you sure you want to delete this discount? This action cannot be undone.')) {
            document.getElementById('delete-form').submit();
        }
    }

    function updateValueLabel() {
        const type = document.getElementById('type').value;
        const label = document.getElementById('value-label');
        const help = document.getElementById('value-help');
        const input = document.getElementById('value');
        
        if (type === 'percentage') {
            label.textContent = 'Percentage (%)';
            help.textContent = 'Enter percentage value (0-100)';
            input.max = 100;
        } else if (type === 'fixed') {
            label.textContent = 'Fixed Amount (Rp)';
            help.textContent = 'Enter fixed discount amount in Rupiah';
            input.removeAttribute('max');
        } else {
            label.textContent = 'Discount Value';
            help.textContent = 'Enter discount value';
            input.removeAttribute('max');
        }
    }

    function toggleApplicableIds() {
        const applicableTo = document.getElementById('applicable_to').value;
        const productsDiv = document.getElementById('products-selection');
        const categoriesDiv = document.getElementById('categories-selection');
        
        productsDiv.style.display = 'none';
        categoriesDiv.style.display = 'none';
        
        if (applicableTo === 'products') {
            productsDiv.style.display = 'block';
        } else if (applicableTo === 'categories') {
            categoriesDiv.style.display = 'block';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateValueLabel();
        toggleApplicableIds();
        
        // Auto-uppercase code input
        document.getElementById('code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    });
</script>
@endpush
