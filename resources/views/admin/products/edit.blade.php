@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
            <nav class="mt-2 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('admin.products.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Products</a>
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
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Products
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content (2 columns) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
                            <p class="text-sm text-gray-600">Product details and description</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                            Product Name <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="name" id="name" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 sm:text-sm @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   value="{{ old('name', $product->name) }}" 
                                   placeholder="Enter product name"
                                   required>
                        </div>
                        @error('name')
                            <p class="mt-2 flex items-center text-sm text-red-600">
                                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="slug" class="block text-sm font-semibold text-gray-900 mb-2">
                                Slug
                            </label>
                            <input type="text" name="slug" id="slug" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 sm:text-sm @error('slug') border-red-300 @enderror" 
                                   value="{{ old('slug', $product->slug) }}"
                                   placeholder="product-slug">
                            @error('slug')
                                <p class="mt-2 flex items-center text-sm text-red-600">
                                    <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-semibold text-gray-900 mb-2">
                                SKU <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="sku" id="sku" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 sm:text-sm @error('sku') border-red-300 @enderror" 
                                   value="{{ old('sku', $product->sku) }}"
                                   placeholder="SKU-001"
                                   required>
                            @error('sku')
                                <p class="mt-2 flex items-center text-sm text-red-600">
                                    <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="6" 
                                  class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 sm:text-sm @error('description') border-red-300 @enderror"
                                  placeholder="Enter product description...">{{ old('description', $product->description) }}</textarea>
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
            </div>

            <!-- Pricing & Inventory -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-600">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Pricing & Inventory</h2>
                            <p class="text-sm text-gray-600">Set price, stock, and weight</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-900 mb-2">
                                Price (Rp) <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="price" id="price" 
                                       class="block w-full rounded-lg border-gray-300 pl-12 shadow-sm transition duration-150 focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 sm:text-sm @error('price') border-red-300 @enderror" 
                                       value="{{ old('price', $product->price) }}" 
                                       min="0" step="0.01"
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('price')
                                <p class="mt-2 flex items-center text-sm text-red-600">
                                    <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-semibold text-gray-900 mb-2">
                                Stock <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="stock" id="stock" 
                                       class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 sm:text-sm @error('stock') border-red-300 @enderror" 
                                       value="{{ old('stock', $product->stock) }}" 
                                       min="0"
                                       placeholder="0"
                                       required>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 sm:text-sm">pcs</span>
                                </div>
                            </div>
                            @error('stock')
                                <p class="mt-2 flex items-center text-sm text-red-600">
                                    <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="weight" class="block text-sm font-semibold text-gray-900 mb-2">
                                Weight
                            </label>
                            <div class="relative">
                                <input type="number" name="weight" id="weight" 
                                       class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 sm:text-sm @error('weight') border-red-300 @enderror" 
                                       value="{{ old('weight', $product->weight) }}" 
                                       min="0" step="0.01"
                                       placeholder="0">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 sm:text-sm">gr</span>
                                </div>
                            </div>
                            @error('weight')
                                <p class="mt-2 flex items-center text-sm text-red-600">
                                    <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-600">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Product Images</h2>
                            <p class="text-sm text-gray-600">Upload and manage product photos</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-5">
                    @php
                        $existingImages = json_decode($product->images, true) ?? [];
                    @endphp

                    @if(!empty($existingImages))
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Current Images</label>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                            @foreach($existingImages as $index => $imagePath)
                            <div class="relative group">
                                <div class="aspect-square overflow-hidden rounded-xl border-2 border-gray-200 bg-gray-50 transition-all duration-200 group-hover:border-purple-400 group-hover:shadow-lg">
                                    <img src="{{ image_url($imagePath) }}" alt="Product Image" class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-110">
                                </div>
                                <div class="mt-2">
                                    <label class="relative flex items-center cursor-pointer">
                                        <input type="checkbox" name="remove_images[]" value="{{ $imagePath }}" id="remove{{ $index }}" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-600 transition duration-150">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Remove</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div>
                        <label for="imageInput" class="block text-sm font-semibold text-gray-900 mb-2">
                            Add New Images
                        </label>
                        <div class="mt-2 flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-10 transition-colors duration-200 hover:border-purple-400 hover:bg-purple-50">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                    <label for="imageInput" class="relative cursor-pointer rounded-md bg-white font-semibold text-purple-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-purple-600 focus-within:ring-offset-2 hover:text-purple-500">
                                        <span>Upload files</span>
                                        <input type="file" name="images[]" id="imageInput" class="sr-only" 
                                               multiple accept="image/jpeg,image/png,image/jpg,image/webp">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs leading-5 text-gray-600">PNG, JPG, WEBP up to 2MB each</p>
                            </div>
                        </div>
                        @error('images')
                            <p class="mt-2 flex items-center text-sm text-red-600">
                                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div id="imagePreview" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 hidden"></div>
                </div>
            </div>
        </div>

        <!-- Sidebar (1 column) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Category & Brand -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-600">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Organization</h2>
                            <p class="text-sm text-gray-600">Category and brand</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-2">
                            Category <span class="text-red-600">*</span>
                        </label>
                        <select name="category_id" id="category_id" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-20 sm:text-sm @error('category_id') border-red-300 @enderror" 
                                required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 flex items-center text-sm text-red-600">
                                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror>
                    </div>

                    <div>
                        <label for="brand_id" class="block text-sm font-semibold text-gray-900 mb-2">
                            Brand
                        </label>
                        <select name="brand_id" id="brand_id" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-150 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-20 sm:text-sm @error('brand_id') border-red-300 @enderror">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="mt-2 flex items-center text-sm text-red-600">
                                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-cyan-50 to-sky-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Visibility</h2>
                            <p class="text-sm text-gray-600">Product status</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative flex items-start rounded-lg border-2 border-gray-200 p-4 transition-colors duration-200 hover:border-cyan-300 hover:bg-cyan-50">
                        <div class="flex h-6 items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="isActive" value="1" 
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }} 
                                   class="h-5 w-5 rounded border-gray-300 text-cyan-600 focus:ring-cyan-600 transition duration-150">
                        </div>
                        <div class="ml-3">
                            <label for="isActive" class="font-semibold text-gray-900 cursor-pointer">Active Product</label>
                            <p class="text-sm text-gray-600">Make this product visible to customers on the storefront</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-200">
                <div class="p-6 space-y-3">
                    <button type="submit" class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Update Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="inline-flex w-full items-center justify-center rounded-lg bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition-all duration-200 hover:bg-gray-50 hover:shadow-md">
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
    const COMPRESSION_QUALITY = 0.7;
    const MAX_IMAGE_WIDTH = 1500;
    const MAX_IMAGE_HEIGHT = 1500;

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
        
        // Calculate total size of NEW files only
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
                Compressing Images... (${(totalSize / 1024 / 1024).toFixed(2)} MB)
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

    // Image Preview for new uploads
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('imagePreview');
    
    imageInput.addEventListener('change', function(e) {
        previewContainer.innerHTML = '';
        const files = Array.from(e.target.files);
        
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'relative group';
                        
                        col.innerHTML = `
                            <div class="aspect-square overflow-hidden rounded-xl border-2 border-purple-200 bg-purple-50 transition-all duration-200 group-hover:border-purple-400 group-hover:shadow-lg">
                                <img src="\${e.target.result}" alt="Preview" class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-110">
                            </div>
                            <div class="mt-2">
                                <p class="text-xs text-gray-600 truncate font-medium">\${file.name}</p>
                                <p class="text-xs text-gray-500">\${(file.size / 1024).toFixed(1)} KB</p>
                            </div>
                        `;
                        
                        previewContainer.appendChild(col);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    });
    
    // Drag and drop functionality
    const dropZone = imageInput.closest('.border-dashed');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-purple-500', 'bg-purple-100');
        }, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-purple-500', 'bg-purple-100');
        }, false);
    });
    
    dropZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        imageInput.files = files;
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        imageInput.dispatchEvent(event);
    }, false);
</script>
@endpush
