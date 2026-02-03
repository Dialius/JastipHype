@extends('admin.layouts.app')

@section('title', 'Shipping Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Shipping Management</h1>
        <a href="{{ route('admin.shipping.analytics') }}" class="btn btn-outline-primary">
            <i class="bi bi-graph-up"></i> View Analytics
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="shippingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="couriers-tab" data-bs-toggle="tab" data-bs-target="#couriers" type="button" role="tab">
                <i class="bi bi-truck"></i> Couriers
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="origin-tab" data-bs-toggle="tab" data-bs-target="#origin" type="button" role="tab">
                <i class="bi bi-geo-alt"></i> Origin Settings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="free-shipping-tab" data-bs-toggle="tab" data-bs-target="#free-shipping" type="button" role="tab">
                <i class="bi bi-gift"></i> Free Shipping
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="zones-tab" data-bs-toggle="tab" data-bs-target="#zones" type="button" role="tab">
                <i class="bi bi-map"></i> Shipping Zones
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="shippingTabContent">
        <!-- Couriers Tab -->
        <div class="tab-pane fade show active" id="couriers" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Available Couriers</h5>
                    <p class="text-muted small mb-0">Select which courier services to enable for your store</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipping.couriers.update') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            @foreach($availableCouriers as $courier)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 {{ in_array($courier['code'], $enabledCouriers) ? 'border-primary' : '' }}">
                                        <div class="card-body">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="couriers[]" 
                                                       value="{{ $courier['code'] }}"
                                                       id="courier_{{ $courier['code'] }}"
                                                       {{ in_array($courier['code'], $enabledCouriers) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="courier_{{ $courier['code'] }}">
                                                    {{ $courier['name'] }}
                                                    @if($courier['popular'])
                                                        <span class="badge bg-success ms-1">Popular</span>
                                                    @endif
                                                </label>
                                            </div>
                                            <p class="text-muted small mb-0 mt-2">{{ $courier['description'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Courier Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Origin Settings Tab -->
        <div class="tab-pane fade" id="origin" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Origin Address</h5>
                    <p class="text-muted small mb-0">Set your warehouse or store location for shipping calculations</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipping.origin.update') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="province_id" class="form-label">Province <span class="text-danger">*</span></label>
                                <select name="province_id" id="province_id" class="form-select" required>
                                    <option value="">Select Province</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province['province_id'] }}">{{ $province['province'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="origin_city_id" class="form-label">City <span class="text-danger">*</span></label>
                                <select name="origin_city_id" id="origin_city_id" class="form-select" required>
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city['city_id'] }}" 
                                                data-city-name="{{ $city['type'] }} {{ $city['city_name'] }}"
                                                {{ $originCity == $city['city_id'] ? 'selected' : '' }}>
                                            {{ $city['type'] }} {{ $city['city_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="origin_city_name" id="origin_city_name" value="{{ $originCityName }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="origin_postal_code" class="form-label">Postal Code <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="origin_postal_code" 
                                       id="origin_postal_code" 
                                       class="form-control" 
                                       value="{{ old('origin_postal_code', $originPostalCode) }}"
                                       placeholder="e.g., 12345"
                                       required>
                            </div>
                        </div>

                        @if($originCity)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>Current Origin:</strong> {{ $originCityName }}, Postal Code: {{ $originPostalCode }}
                            </div>
                        @endif

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Origin Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Free Shipping Tab -->
        <div class="tab-pane fade" id="free-shipping" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Free Shipping Rules</h5>
                    <p class="text-muted small mb-0">Configure free shipping promotions for your customers</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipping.free-shipping.update') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="free_shipping_enabled" 
                                       id="free_shipping_enabled"
                                       value="1"
                                       {{ $freeShippingEnabled ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="free_shipping_enabled">
                                    Enable Free Shipping
                                </label>
                            </div>
                            <small class="text-muted">When enabled, customers will get free shipping if they meet the minimum order amount</small>
                        </div>

                        <div class="mb-3" id="free_shipping_settings" style="{{ $freeShippingEnabled ? '' : 'display: none;' }}">
                            <label for="free_shipping_min_amount" class="form-label">Minimum Order Amount (Rp)</label>
                            <input type="number" 
                                   name="free_shipping_min_amount" 
                                   id="free_shipping_min_amount" 
                                   class="form-control" 
                                   value="{{ old('free_shipping_min_amount', $freeShippingMinAmount) }}"
                                   min="0"
                                   step="1000"
                                   placeholder="e.g., 100000">
                            <small class="text-muted">Orders above this amount will qualify for free shipping</small>
                        </div>

                        @if($freeShippingEnabled)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i>
                                <strong>Free Shipping Active:</strong> Orders above Rp {{ number_format($freeShippingMinAmount, 0, ',', '.') }} get free shipping
                            </div>
                        @endif

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Free Shipping Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Shipping Zones Tab -->
        <div class="tab-pane fade" id="zones" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shipping Zones</h5>
                    <p class="text-muted small mb-0">Manage delivery areas and zone-specific settings</p>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-map" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="mt-3">Shipping Zones Feature</h5>
                        <p class="text-muted">This feature is coming soon. You'll be able to:</p>
                        <ul class="list-unstyled text-muted">
                            <li><i class="bi bi-check-circle text-success"></i> Define custom shipping zones</li>
                            <li><i class="bi bi-check-circle text-success"></i> Set zone-specific rates</li>
                            <li><i class="bi bi-check-circle text-success"></i> Manage delivery restrictions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Province change handler
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('origin_city_id');
    const cityNameInput = document.getElementById('origin_city_name');
    
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            
            if (!provinceId) {
                citySelect.innerHTML = '<option value="">Select City</option>';
                return;
            }
            
            // Show loading
            citySelect.innerHTML = '<option value="">Loading cities...</option>';
            citySelect.disabled = true;
            
            // Fetch cities
            fetch(`/admin/shipping/cities?province_id=${provinceId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                citySelect.innerHTML = '<option value="">Select City</option>';
                
                if (data.success && data.cities) {
                    data.cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.city_id;
                        option.textContent = `${city.type} ${city.city_name}`;
                        option.dataset.cityName = `${city.type} ${city.city_name}`;
                        citySelect.appendChild(option);
                    });
                }
                
                citySelect.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching cities:', error);
                citySelect.innerHTML = '<option value="">Error loading cities</option>';
                citySelect.disabled = false;
            });
        });
    }
    
    // City change handler - update hidden city name field
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.cityName) {
                cityNameInput.value = selectedOption.dataset.cityName;
            }
        });
    }
    
    // Free shipping toggle
    const freeShippingToggle = document.getElementById('free_shipping_enabled');
    const freeShippingSettings = document.getElementById('free_shipping_settings');
    
    if (freeShippingToggle) {
        freeShippingToggle.addEventListener('change', function() {
            if (this.checked) {
                freeShippingSettings.style.display = 'block';
            } else {
                freeShippingSettings.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
