@extends('admin.layouts.app')

@section('title', 'Export & Import Data')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Export & Import Data</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading">Import Errors:</h6>
            <ul class="mb-0">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Export Section -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-download"></i> Export Data</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Export your data to CSV format</p>

                    <!-- Export Products -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Products</h6>
                        <p class="text-muted small">Export all products with their details</p>
                        <form action="{{ route('admin.export.products') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="format" value="csv">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-filetype-csv"></i> Export as CSV
                            </button>
                        </form>
                    </div>

                    <hr>

                    <!-- Export Orders -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Orders</h6>
                        <p class="text-muted small">Export orders with optional date range filter</p>
                        <form action="{{ route('admin.export.orders') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <label for="start_date" class="form-label small">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="end_date" class="form-label small">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm">
                                </div>
                            </div>
                            <input type="hidden" name="format" value="csv">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-filetype-csv"></i> Export as CSV
                            </button>
                        </form>
                    </div>

                    <hr>

                    <!-- Export Customers -->
                    <div class="mb-0">
                        <h6 class="fw-bold">Customers</h6>
                        <p class="text-muted small">Export customer list (excluding sensitive data)</p>
                        <form action="{{ route('admin.export.customers') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="format" value="csv">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-filetype-csv"></i> Export as CSV
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Section -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-upload"></i> Import Data</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Import data from CSV files</p>

                    <!-- Import Products -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Products</h6>
                        <p class="text-muted small">Import products from CSV file</p>
                        
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle"></i>
                            <strong>Important:</strong> Download the template first to see the required format
                        </div>

                        <a href="{{ route('admin.import.template', 'products') }}" class="btn btn-sm btn-outline-primary mb-3">
                            <i class="bi bi-download"></i> Download Template
                        </a>

                        <form action="{{ route('admin.import.products') }}" method="POST" enctype="multipart/form-data" id="importProductsForm">
                            @csrf
                            <div class="mb-3">
                                <label for="products_file" class="form-label">Select File</label>
                                <input type="file" 
                                       name="file" 
                                       id="products_file" 
                                       class="form-control form-control-sm" 
                                       accept=".csv,.txt"
                                       required>
                                <small class="text-muted">Accepted formats: CSV (Max: 10MB)</small>
                            </div>
                            <button type="submit" class="btn btn-sm btn-info">
                                <i class="bi bi-upload"></i> Import Products
                            </button>
                        </form>
                    </div>

                    <hr>

                    <!-- Import Orders (Coming Soon) -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Orders</h6>
                        <p class="text-muted small">Import orders feature coming soon</p>
                        <button class="btn btn-sm btn-secondary" disabled>
                            <i class="bi bi-upload"></i> Import Orders (Coming Soon)
                        </button>
                    </div>

                    <hr>

                    <!-- Import Customers (Coming Soon) -->
                    <div class="mb-0">
                        <h6 class="fw-bold">Customers</h6>
                        <p class="text-muted small">Import customers feature coming soon</p>
                        <button class="btn btn-sm btn-secondary" disabled>
                            <i class="bi bi-upload"></i> Import Customers (Coming Soon)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Guidelines -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Import Guidelines</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold">Product Import Rules:</h6>
                    <ul class="small">
                        <li>Name and SKU are required fields</li>
                        <li>If ID is provided and product exists, it will be updated</li>
                        <li>If ID is empty or product doesn't exist, a new product will be created</li>
                        <li>Brand and Category must match existing names (case-sensitive)</li>
                        <li>Price and Stock must be numeric values</li>
                        <li>Status must be either 'active' or 'inactive'</li>
                        <li>Weight should be in grams</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold">Tips for Successful Import:</h6>
                    <ul class="small">
                        <li>Always download and use the provided template</li>
                        <li>Don't modify the header row</li>
                        <li>Ensure data types match the expected format</li>
                        <li>Test with a small batch first</li>
                        <li>Check for errors after import and fix them</li>
                        <li>Keep a backup of your original data</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show loading indicator on form submit
    const importForm = document.getElementById('importProductsForm');
    if (importForm) {
        importForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importing...';
        });
    }
});
</script>
@endpush
