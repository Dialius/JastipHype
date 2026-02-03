@extends('admin.layouts.app')

@section('title', 'Add New Product')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Product</h1>
            <nav class="mt-2 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('admin.products.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Products</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Add New</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:hover:bg-gray-700">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Products
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Basic Information -->
            <div class="mb-6 overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Basic Information</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Product Name <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                        <input type="text" name="slug" id="slug"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('slug') border-red-500 @enderror"
                               value="{{ old('slug') }}" placeholder="Leave empty to auto-generate">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">URL-friendly version of the name. Leave empty to auto-generate.</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            SKU <span class="text-red-600">*</span>
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="text" name="sku" id="sku" required
                                   class="block w-full flex-1 rounded-none rounded-l-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('sku') border-red-500 @enderror"
                                   value="{{ old('sku') }}" placeholder="e.g., NIKE-TS-001">
                            <button type="button" id="generateSKU"
                                    class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                                <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                                </svg>
                                Generate
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="mr-1 inline h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            Format: BRAND-CATEGORY-NUMBER (e.g., NIKE-TS-001)
                        </p>
                        @error('sku')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="6"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div class="mb-6 overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Pricing & Inventory</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="priceDisplay" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Price (Rp) <span class="text-red-600">*</span>
                            </label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="text" id="priceDisplay" required
                                       class="block w-full rounded-md border-gray-300 pl-12 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       placeholder="0">
                                <input type="hidden" name="price" id="priceValue" value="{{ old('price') }}">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: Rp 1.000.000</p>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stockDisplay" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Stock <span class="text-red-600">*</span>
                            </label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <input type="text" id="stockDisplay" required
                                       class="block w-full rounded-md border-gray-300 pr-16 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       placeholder="0">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 sm:text-sm">units</span>
                                </div>
                                <input type="hidden" name="stock" id="stockValue" value="{{ old('stock') }}">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Available quantity</p>
                            @error('stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="weightDisplay" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weight (grams)</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <input type="text" id="weightDisplay"
                                       class="block w-full rounded-md border-gray-300 pr-12 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       placeholder="0">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 sm:text-sm">gr</span>
                                </div>
                                <input type="hidden" name="weight" id="weightValue" value="{{ old('weight') }}">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">For shipping calculation</p>
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Product Images</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload gambar produk dengan kategori (Depan, Belakang, Detail, Lainnya)</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <!-- Image Size Recommendation -->
                    <div class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Rekomendasi Ukuran Gambar</h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                                    <ul class="list-disc space-y-1 pl-5">
                                        <li><strong>Ukuran Ideal:</strong> 800 x 1000 px (Rasio 4:5)</li>
                                        <li><strong>Ukuran Minimum:</strong> 600 x 750 px</li>
                                        <li><strong>Format:</strong> JPG, PNG, WEBP</li>
                                        <li><strong>Ukuran File:</strong> Maksimal 2MB per gambar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload Sections -->
                    @foreach(['front' => 'Gambar Depan (Front)', 'back' => 'Gambar Belakang (Back)', 'detail' => 'Gambar Detail', 'other' => 'Gambar Lainnya (Other)'] as $type => $label)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
                        <input type="file" name="images[{{ $type }}][]" class="image-input mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                               multiple accept="image/jpeg,image/png,image/jpg,image/webp" data-type="{{ $type }}">
                        <div class="image-preview-container mt-2 grid grid-cols-2 gap-2 sm:grid-cols-4" data-type="{{ $type }}"></div>
                    </div>
                    @endforeach

                    @error('images')
                        <div class="rounded-md bg-red-50 p-4">
                            <p class="text-sm text-red-800">{{ $message }}</p>
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Category & Brand -->
            <div class="mb-6 overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Organization</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category <span class="text-red-600">*</span>
                        </label>
                        <select name="category_id" id="category_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('category_id') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                        <select name="brand_id" id="brand_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('brand_id') border-red-500 @enderror">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-6 overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Status</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800">
                        <label for="isActive" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            Active (visible to customers)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="px-6 py-4 space-y-2">
                    <button type="submit" class="inline-flex w-full justify-center items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Create Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="inline-flex w-full justify-center items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:hover:bg-gray-700">
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
        if (priceValue.value) {
            priceDisplay.value = formatNumber(priceValue.value);
        }
        
        priceDisplay.addEventListener('input', function(e) {
            let value = unformatNumber(e.target.value);
            value = value.replace(/[^0-9]/g, '');
            priceValue.value = value;
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
        if (stockValue.value) {
            stockDisplay.value = formatNumber(stockValue.value);
        }
        
        stockDisplay.addEventListener('input', function(e) {
            let value = unformatNumber(e.target.value);
            value = value.replace(/[^0-9]/g, '');
            stockValue.value = value;
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
        if (weightValue.value) {
            weightDisplay.value = formatNumber(weightValue.value);
        }
        
        weightDisplay.addEventListener('input', function(e) {
            let value = unformatNumber(e.target.value);
            value = value.replace(/[^0-9]/g, '');
            weightValue.value = value;
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
        
        const brandName = brandSelect.options[brandSelect.selectedIndex].text;
        const brandCode = brandName.substring(0, 4).toUpperCase().replace(/[^A-Z]/g, '');
        
        const categoryName = categorySelect.options[categorySelect.selectedIndex].text;
        const categoryCode = categoryName.substring(0, 2).toUpperCase().replace(/[^A-Z]/g, '');
        
        const randomNum = Math.floor(Math.random() * 900) + 100;
        const sku = `${brandCode}-${categoryCode}-${randomNum}`;
        
        skuInput.value = sku;
        skuInput.classList.add('border-green-500');
        setTimeout(() => {
            skuInput.classList.remove('border-green-500');
        }, 2000);
    });
    
    // Image Preview
    document.querySelectorAll('.image-input').forEach(input => {
        input.addEventListener('change', function(e) {
            const type = this.getAttribute('data-type');
            const previewContainer = document.querySelector(`.image-preview-container[data-type="${type}"]`);
            previewContainer.innerHTML = '';
            
            const files = Array.from(e.target.files);
            
            files.forEach((file) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="h-24 w-full rounded-lg object-cover" alt="Preview">
                            <div class="mt-1 text-xs text-gray-500 truncate">${file.name}</div>
                        `;
                        previewContainer.appendChild(div);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        });
    });
</script>
@endpush
