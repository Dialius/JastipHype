@extends('admin.layouts.app')

@section('title', 'Category Images - Shop by Category')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Shop by Category Images</h1>
            <p class="mt-1 text-sm text-gray-500">Upload gambar untuk 4 kategori yang ditampilkan di bagian "Shop by Category" homepage</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Form (2 columns) -->
    <div class="lg:col-span-2">
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Upload Category Images</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.categories.images.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        @foreach($categories as $index => $category)
                        <div class="rounded-lg border border-gray-200 p-6">
                            <input type="hidden" name="categories[{{ $category->id }}][id]" value="{{ $category->id }}">
                            
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $category->products_count ?? 0 }} products</p>
                                </div>
                                
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                    Category {{ $index + 1 }}
                                </span>
                            </div>
                            
                            <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Current Image -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                                    @if($category->image)
                                    <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50">
                                        @php
                                            // Fallback if helper not loaded
                                            if (function_exists('image_url')) {
                                                $adminCatImageUrl = image_url($category->image);
                                            } else {
                                                if (filter_var($category->image, FILTER_VALIDATE_URL)) {
                                                    $adminCatImageUrl = $category->image;
                                                } else {
                                                    $adminCatImageUrl = asset('storage/' . ltrim($category->image, '/'));
                                                }
                                            }
                                        @endphp
                                        <img src="{{ $adminCatImageUrl }}" 
                                             alt="{{ $category->name }}" 
                                             class="h-48 w-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                        <div class="absolute bottom-2 left-2 right-2">
                                            <span class="inline-flex items-center rounded-md bg-green-500 px-2 py-1 text-xs font-medium text-white">
                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Active
                                            </span>
                                        </div>
                                    </div>
                                    @else
                                    <div class="flex h-48 items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500">No image uploaded</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Upload New Image -->
                                <div>
                                    <label for="image_{{ $category->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $category->image ? 'Replace Image' : 'Upload Image' }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input type="file" 
                                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-blue-500 @error('categories.'.$category->id.'.image') border-red-500 @enderror" 
                                               id="image_{{ $category->id }}" 
                                               name="categories[{{ $category->id }}][image]" 
                                               accept="image/jpeg,image/jpg,image/png,image/webp"
                                               onchange="previewImage(event, {{ $category->id }})">
                                        @error('categories.'.$category->id.'.image')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-2 text-xs text-gray-500">
                                            <svg class="inline h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Recommended: 600x600px (1:1 square). Max 2MB
                                        </p>
                                    </div>
                                    
                                    <!-- Preview -->
                                    <div id="preview_{{ $category->id }}" class="mt-4 hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                                        <div class="relative overflow-hidden rounded-lg border-2 border-blue-500 bg-gray-50">
                                            <img id="preview_img_{{ $category->id }}" 
                                                 src="" 
                                                 alt="Preview" 
                                                 class="h-48 w-full object-cover">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                            <div class="absolute bottom-2 left-2 right-2">
                                                <span class="inline-flex items-center rounded-md bg-blue-500 px-2 py-1 text-xs font-medium text-white">
                                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    New Preview
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            Update All Images
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Specifications Sidebar (1 column) -->
    <div class="lg:col-span-1">
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Image Specifications</h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="rounded-lg bg-blue-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Shop by Category</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Grid 4 kolom di homepage untuk navigasi kategori utama</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Image Requirements</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Size</dt>
                            <dd class="mt-1 text-sm text-gray-900">600x600px (1:1 square)</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Format</dt>
                            <dd class="mt-1 text-sm text-gray-900">JPG, PNG, or WebP</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Max Size</dt>
                            <dd class="mt-1 text-sm text-gray-900">2MB per file</dd>
                        </div>
                    </dl>
                </div>

                <hr class="border-gray-200">

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">4 Fixed Categories</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="mr-2 h-5 w-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Accessories</strong> - Tas, belt, topi, dll</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="mr-2 h-5 w-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Clothing</strong> - Kaos, kemeja, celana, dll</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="mr-2 h-5 w-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Hoodies</strong> - Hoodie, sweater, jaket</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="mr-2 h-5 w-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Sneakers</strong> - Sepatu sneakers</span>
                        </li>
                    </ul>
                    <div class="mt-3 rounded-md bg-yellow-50 p-3">
                        <p class="text-xs text-yellow-800">
                            <svg class="inline h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Kategori ini tetap dan tidak bisa ditambah/dikurangi
                        </p>
                    </div>
                </div>

                <hr class="border-gray-200">

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Upload Tips</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="mr-2 h-5 w-5 flex-shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Gunakan gambar square 600x600px</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="mr-2 h-5 w-5 flex-shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Gambar harus jelas dan menarik</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="mr-2 h-5 w-5 flex-shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Pilih gambar representatif untuk kategori</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="mr-2 h-5 w-5 flex-shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Hindari gambar blur atau pixelated</span>
                        </li>
                    </ul>
                </div>

                <hr class="border-gray-200">

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Homepage Preview</h3>
                    <p class="text-xs text-gray-500 mb-3">Gambar akan muncul dalam grid 2x2 (mobile) atau 4 kolom (desktop)</p>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($categories as $category)
                            <div class="relative overflow-hidden rounded-md bg-gray-800" style="aspect-ratio: 1/1;">
                                @if($category->image)
                                <img src="{{ Storage::url($category->image) }}" 
                                     alt="{{ $category->name }}"
                                     class="h-full w-full object-cover opacity-70">
                                @else
                                <div class="flex h-full items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-2">
                                    <p class="text-xs font-semibold text-white">{{ $category->name }}</p>
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
            // Check file size (2MB = 2097152 bytes)
            if (file.size > 2097152) {
                alert('File size must be less than 2MB');
                event.target.value = '';
                previewContainer.classList.add('hidden');
                return;
            }
            
            // Check file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('File must be JPG, PNG, or WebP');
                event.target.value = '';
                previewContainer.classList.add('hidden');
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
        }
    }
</script>
@endpush
