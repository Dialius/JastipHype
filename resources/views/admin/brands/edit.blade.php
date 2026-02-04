@extends('admin.layouts.app')

@section('title', 'Edit Brand')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Brand</h1>
            <nav class="mt-2 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">Home</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('admin.brands.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Brands</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.brands.index') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Brands
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <x-admin.form-card title="Basic Information" description="Brand details and description" icon="tag" color="blue">
                <div class="space-y-5">
                    <x-admin.form-input 
                        label="Brand Name" 
                        name="name" 
                        :required="true"
                        :value="old('name', $brand->name)"
                        :error="$errors->first('name')"
                        placeholder="Enter brand name"
                    />

                    <x-admin.form-input 
                        label="Slug" 
                        name="slug"
                        :value="old('slug', $brand->slug)"
                        :error="$errors->first('slug')"
                        placeholder="brand-slug"
                    />

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description', $brand->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 flex items-center text-sm text-red-600">
                                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </x-admin.form-card>

            <!-- Brand Logo -->
            <x-admin.form-card title="Brand Logo" description="Upload or replace brand logo" icon="image" color="purple">
                <div class="space-y-4">
                    @if($brand->logo_path)
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Current Logo</label>
                        <div class="flex justify-center p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                            <img src="{{ brand_logo_url($brand) }}" alt="Current Logo" class="h-32 w-32 object-contain bg-gray-50 rounded-lg p-2 border border-gray-200">
                        </div>
                        <div class="mt-3 relative flex items-start rounded-lg border-2 border-gray-200 p-3 transition-colors duration-200 hover:border-red-300 hover:bg-red-50">
                            <div class="flex h-6 items-center">
                                <input type="checkbox" name="remove_logo" value="1" id="removeLogo"
                                       class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-600 transition duration-150">
                            </div>
                            <div class="ml-3">
                                <label for="removeLogo" class="font-medium text-gray-900 cursor-pointer">Remove current logo</label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label for="logoInput" class="block text-sm font-semibold text-gray-900 mb-2">
                            {{ $brand->logo_path ? 'Replace Logo' : 'Upload Logo' }}
                        </label>
                        <div class="mt-2 flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-10 transition-colors duration-200 hover:border-purple-400 hover:bg-purple-50">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                    <label for="logoInput" class="relative cursor-pointer rounded-md bg-white font-semibold text-purple-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-purple-600 focus-within:ring-offset-2 hover:text-purple-500">
                                        <span>Upload a file</span>
                                        <input type="file" name="logo" id="logoInput" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/webp">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs leading-5 text-gray-600">PNG, JPG, WEBP up to 2MB</p>
                                <p class="text-xs leading-5 text-gray-500 mt-1">Recommended: 200x200px to 1000x1000px (square)</p>
                            </div>
                        </div>
                        @error('logo')
                            <p class="mt-2 flex items-center text-sm text-red-600">
                                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div id="logoPreview" class="hidden">
                        <label class="block text-sm font-semibold text-gray-900 mb-2">New Logo Preview</label>
                        <div class="flex justify-center p-4 bg-purple-50 rounded-xl border-2 border-purple-200">
                            <img id="logoPreviewImage" src="" alt="Logo Preview" class="max-w-xs rounded-lg">
                        </div>
                    </div>
                </div>
            </x-admin.form-card>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status & Settings -->
            <x-admin.form-card title="Status & Settings" description="Brand visibility and order" icon="cog" color="orange">
                <div class="space-y-5">
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-900 mb-2">
                            Status <span class="text-red-600">*</span>
                        </label>
                        <select name="status" id="status" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-20 sm:text-sm @error('status') border-red-300 @enderror">
                            <option value="active" {{ old('status', $brand->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $brand->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-2 flex items-center text-sm text-red-600">
                                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <x-admin.form-input 
                        label="Display Order" 
                        name="display_order" 
                        type="number"
                        :value="old('display_order', $brand->display_order ?? 0)"
                        :error="$errors->first('display_order')"
                        help="Lower numbers appear first"
                    />

                    <div class="relative flex items-start rounded-lg border-2 border-gray-200 p-4 transition-colors duration-200 hover:border-orange-300 hover:bg-orange-50">
                        <div class="flex h-6 items-center">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" id="isFeatured" value="1" {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}
                                   class="h-5 w-5 rounded border-gray-300 text-orange-600 focus:ring-orange-600 transition duration-150">
                        </div>
                        <div class="ml-3">
                            <label for="isFeatured" class="font-semibold text-gray-900 cursor-pointer flex items-center">
                                <svg class="h-5 w-5 text-amber-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Featured Brand
                            </label>
                            <p class="text-sm text-gray-600">Featured brands will be displayed on the homepage</p>
                        </div>
                    </div>
                </div>
            </x-admin.form-card>

            <!-- Actions -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-200">
                <div class="p-6 space-y-3">
                    <button type="submit" class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Update Brand
                    </button>
                    <a href="{{ route('admin.brands.index') }}" class="inline-flex w-full items-center justify-center rounded-lg bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition-all duration-200 hover:bg-gray-50 hover:shadow-md">
                        <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
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
    // Configuration
    const MAX_TOTAL_SIZE_MB = 4.5;
    const MAX_TOTAL_BYTES = MAX_TOTAL_SIZE_MB * 1024 * 1024;
    const COMPRESSION_QUALITY = 0.8;
    const MAX_IMAGE_WIDTH = 1000;
    const MAX_IMAGE_HEIGHT = 1000;

    // Image Compression Helper
    async function compressImage(file) {
        return new Promise((resolve, reject) => {
            if (!file.type.match(/image.*/)) {
                resolve(file);
                return;
            }

            const img = new Image();
            img.src = URL.createObjectURL(file);
            
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                // Resume dimensions if needed
                if (width > MAX_IMAGE_WIDTH || height > MAX_IMAGE_HEIGHT) {
                    if (width > height) {
                        height *= MAX_IMAGE_WIDTH / width;
                        width = MAX_IMAGE_WIDTH;
                    } else {
                        width *= MAX_IMAGE_HEIGHT / height;
                        height = MAX_IMAGE_HEIGHT;
                    }
                }

                canvas.width = width;
                canvas.height = height;
                
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                canvas.toBlob((blob) => {
                    if (!blob) {
                        reject(new Error('Canvas compression failed'));
                        return;
                    }
                    
                    const compressedFile = new File([blob], file.name, {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });
                    
                    resolve(compressedFile);
                }, 'image/jpeg', COMPRESSION_QUALITY);
            };
            
            img.onerror = () => reject(new Error('Image loading failed'));
        });
    }

    // Form Submission Interceptor
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Calculate total size
        let totalSize = 0;
        const fileInputs = document.querySelectorAll('input[type="file"]');
        const filesToProcess = [];

        fileInputs.forEach(input => {
            if (input.files.length) {
                Array.from(input.files).forEach(file => {
                    totalSize += file.size;
                    filesToProcess.push({ input, file });
                });
            }
        });

        if (totalSize > MAX_TOTAL_BYTES) {
            // Show loading state
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Compressing... (${(totalSize / 1024 / 1024).toFixed(2)} MB)
            `;

            try {
                // Compress all images
                const compressionPromises = filesToProcess.map(async (item) => {
                    const compressed = await compressImage(item.file);
                    return { ...item, compressed };
                });

                const results = await Promise.all(compressionPromises);
                
                // Group by input to reassign
                const inputMap = new Map();
                let newTotalSize = 0;

                results.forEach(({ input, compressed }) => {
                    if (!inputMap.has(input)) {
                        inputMap.set(input, new DataTransfer());
                    }
                    inputMap.get(input).items.add(compressed);
                    newTotalSize += compressed.size;
                });

                // Update inputs with compressed files
                inputMap.forEach((dataTransfer, input) => {
                    input.files = dataTransfer.files;
                });

                // Check size again
                if (newTotalSize > MAX_TOTAL_BYTES) {
                    alert(`Total file size (${(newTotalSize / 1024 / 1024).toFixed(2)} MB) still exceeds the limit of ${MAX_TOTAL_SIZE_MB} MB even after compression. Please upload fewer or smaller images.`);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    return;
                }

                // Proceed with submission
                form.submit();

            } catch (error) {
                console.error('Compression error:', error);
                alert('An error occurred while compressing images. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        } else {
            // Size is okay, just submit
            form.submit();
        }
    });

    // Logo Preview
    document.getElementById('logoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('logoPreview');
        const previewImage = document.getElementById('logoPreviewImage');
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            };
            
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    });
</script>
@endpush
