@extends('admin.layouts.app')

@section('title', 'Export & Import Data')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Export & Import Data</h1>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('warning') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <svg class="fill-current h-6 w-6 text-amber-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg relative" role="alert">
            <h6 class="font-semibold mb-2">Import Errors:</h6>
            <ul class="list-disc list-inside space-y-1">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Export Section -->
        <div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-blue-600 rounded-t-lg">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export Data
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <p class="text-gray-600">Export your data to CSV format</p>

                    <!-- Export Products -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Products</h3>
                        <p class="text-sm text-gray-600 mb-3">Export all products with their details</p>
                        <form action="{{ route('admin.export.products') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="format" value="csv">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export as CSV
                            </button>
                        </form>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Export Orders -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Orders</h3>
                        <p class="text-sm text-gray-600 mb-3">Export orders with optional date range filter</p>
                        <form action="{{ route('admin.export.orders') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label for="start_date" class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <input type="hidden" name="format" value="csv">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export as CSV
                            </button>
                        </form>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Export Customers -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Customers</h3>
                        <p class="text-sm text-gray-600 mb-3">Export customer list (excluding sensitive data)</p>
                        <form action="{{ route('admin.export.customers') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="format" value="csv">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export as CSV
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Section -->
        <div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-cyan-500 rounded-t-lg">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Import Data
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <p class="text-gray-600">Import data from CSV files</p>

                    <!-- Import Products -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Products</h3>
                        <p class="text-sm text-gray-600 mb-3">Import products from CSV file</p>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                            <p class="text-sm text-blue-800 flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span><strong>Important:</strong> Download the template first to see the required format</span>
                            </p>
                        </div>

                        <a href="{{ route('admin.import.template', 'products') }}" class="inline-flex items-center gap-2 px-4 py-2 mb-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download Template
                        </a>

                        <form action="{{ route('admin.import.products') }}" method="POST" enctype="multipart/form-data" id="importProductsForm">
                            @csrf
                            <div class="mb-3">
                                <label for="products_file" class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
                                <input type="file" 
                                       name="file" 
                                       id="products_file" 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                       accept=".csv,.txt"
                                       required>
                                <p class="mt-1 text-xs text-gray-500">Accepted formats: CSV (Max: 10MB)</p>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 inline-flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Import Products
                            </button>
                        </form>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Import Orders (Coming Soon) -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Orders</h3>
                        <p class="text-sm text-gray-600 mb-3">Import orders feature coming soon</p>
                        <button class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed inline-flex items-center gap-2 text-sm" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Import Orders (Coming Soon)
                        </button>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Import Customers (Coming Soon) -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Customers</h3>
                        <p class="text-sm text-gray-600 mb-3">Import customers feature coming soon</p>
                        <button class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed inline-flex items-center gap-2 text-sm" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Import Customers (Coming Soon)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Guidelines -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Import Guidelines</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Product Import Rules:</h3>
                    <ul class="text-sm text-gray-600 space-y-2 list-disc list-inside">
                        <li>Name and SKU are required fields</li>
                        <li>If ID is provided and product exists, it will be updated</li>
                        <li>If ID is empty or product doesn't exist, a new product will be created</li>
                        <li>Brand and Category must match existing names (case-sensitive)</li>
                        <li>Price and Stock must be numeric values</li>
                        <li>Status must be either 'active' or 'inactive'</li>
                        <li>Weight should be in grams</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Tips for Successful Import:</h3>
                    <ul class="text-sm text-gray-600 space-y-2 list-disc list-inside">
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
            submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Importing...';
        });
    }
});
</script>
@endpush
