@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('page-title', 'Banners')

@section('content')
<div class="space-y-6" x-data="{ showDeleteModal: false }">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Edit Banner</h2>
            <nav class="mt-2">
                <ol class="flex items-center gap-2 text-sm">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Dashboard</a>
                    </li>
                    <li class="text-gray-400">/</li>
                    <li>
                        <a href="{{ route('admin.banners.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Banners</a>
                    </li>
                    <li class="text-gray-400">/</li>
                    <li class="text-blue-600 dark:text-blue-400">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Form (2 columns) --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Banner Information</h3>
                </div>
                <div class="p-5 md:p-6">
                    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" id="banner-form">
                        @csrf
                        @method('PUT')
                        
                        {{-- Banner Type --}}
                        <div class="mb-6">
                            <label for="type" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Banner Type <span class="text-red-500">*</span>
                            </label>
                            <select id="type" 
                                    name="type" 
                                    required
                                    onchange="updateFormFields()"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('type') border-red-500 dark:border-red-500 @enderror">
                                <option value="">-- Pilih Tipe Banner --</option>
                                <option value="hero" {{ old('type', $banner->type) == 'hero' ? 'selected' : '' }}>
                                    🎯 Hero Banner - Banner utama homepage
                                </option>
                                <option value="limited" {{ old('type', $banner->type) == 'limited' ? 'selected' : '' }}>
                                    ⭐ Limited Edition Banner - Product showcase
                                </option>
                            </select>
                            @error('type')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            
                            <div id="type-info" class="mt-3 hidden rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-500/10">
                                <p class="text-sm text-blue-800 dark:text-blue-300" id="type-description"></p>
                            </div>
                            
                            <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-500/10">
                                <p class="text-sm text-amber-800 dark:text-amber-300">
                                    <svg class="mr-1 inline h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <strong>Catatan:</strong> Untuk mengatur gambar "Shop by Category", gunakan menu 
                                    <a href="{{ route('admin.categories.images.edit') }}" class="underline">Category Images</a>
                                </p>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-100 dark:border-gray-800">
                        
                        {{-- Title --}}
                        <div class="mb-6">
                            <label for="title" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Banner Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $banner->title) }}" 
                                   required
                                   class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('title') border-red-500 dark:border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-6">
                            <label for="description" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('description') border-red-500 dark:border-red-500 @enderror">{{ old('description', $banner->description) }}</textarea>
                            @error('description')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Optional: Short description for the banner</p>
                        </div>

                        {{-- Product Selection --}}
                        <div class="mb-6" id="product-field">
                            <label for="product_id" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Link to Product 
                                <span class="hidden text-red-500" id="product-required">*</span>
                                <span class="text-gray-500 dark:text-gray-400" id="product-optional">(Optional)</span>
                            </label>
                            <select id="product_id" 
                                    name="product_id"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('product_id') border-red-500 dark:border-red-500 @enderror">
                                <option value="">-- No Product Link --</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id', $banner->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->brand->name ?? 'No Brand' }} - {{ $product->name }} (Stock: {{ $product->stock }})
                                </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400" id="product-help"></p>
                        </div>

                        {{-- Countdown Settings --}}
                        <div id="countdown-fields" class="hidden space-y-6">
                            <div>
                                <label class="flex cursor-pointer items-center gap-3">
                                    <input type="checkbox" 
                                           id="show_countdown" 
                                           name="show_countdown" 
                                           value="1" 
                                           {{ old('show_countdown', $banner->show_countdown) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Show Countdown Timer</span>
                                </label>
                                <p class="ml-7 mt-1.5 text-xs text-gray-500 dark:text-gray-400">Enable/disable countdown timer display</p>
                            </div>

                            <div>
                                <label for="countdown_target" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Countdown Target Date
                                </label>
                                <input type="datetime-local" 
                                       id="countdown_target" 
                                       name="countdown_target" 
                                       value="{{ old('countdown_target', $banner->countdown_target ? $banner->countdown_target->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('countdown_target') border-red-500 dark:border-red-500 @enderror">
                                @error('countdown_target')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Target date/time for countdown</p>
                            </div>
                        </div>

                        {{-- Current Image --}}
                        @if($banner->image_path)
                        <div class="mb-6">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Current Image</label>
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                                <img src="{{ banner_image_url($banner) }}" 
                                     alt="{{ $banner->title }}" 
                                     class="mx-auto max-h-72 rounded-lg">
                            </div>
                        </div>
                        @endif

                        {{-- Image Upload --}}
                        <div class="mb-6" id="image-field">
                            <label for="image" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Replace Image
                            </label>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/jpeg,image/jpg,image/png,image/webp"
                                   onchange="previewImage(event)"
                                   class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-blue-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('image') border-red-500 dark:border-red-500 @enderror">
                            @error('image')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Leave empty to keep current image. Accepted: JPG, PNG, WebP. Max: 2MB</p>
                        </div>

                        {{-- New Image Preview --}}
                        <div class="mb-6 hidden" id="preview-container">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">New Image Preview</label>
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                                <img id="image-preview" src="" alt="Preview" class="mx-auto max-h-72 rounded-lg">
                            </div>
                        </div>

                        {{-- Button Fields --}}
                        <div id="button-fields" class="hidden space-y-6">
                            <div>
                                <label for="button_text" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Button Text
                                </label>
                                <input type="text" 
                                       id="button_text" 
                                       name="button_text" 
                                       value="{{ old('button_text', $banner->button_text) }}" 
                                       placeholder="Shop Now"
                                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('button_text') border-red-500 dark:border-red-500 @enderror">
                                @error('button_text')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Optional: Custom button text</p>
                            </div>

                            <div>
                                <label for="button_link" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Button Link
                                </label>
                                <input type="url" 
                                       id="button_link" 
                                       name="button_link" 
                                       value="{{ old('button_link', $banner->button_link) }}" 
                                       placeholder="https://example.com"
                                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('button_link') border-red-500 dark:border-red-500 @enderror">
                                @error('button_link')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Optional: Custom button link</p>
                            </div>
                        </div>

                        {{-- General Link --}}
                        <div class="mb-6">
                            <label for="link" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                General Link URL
                            </label>
                            <input type="url" 
                                   id="link" 
                                   name="link" 
                                   value="{{ old('link', $banner->link) }}" 
                                   placeholder="https://example.com"
                                   class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('link') border-red-500 dark:border-red-500 @enderror">
                            @error('link')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Optional: URL to redirect when banner is clicked</p>
                        </div>

                        {{-- Status --}}
                        <div class="mb-6">
                            <label for="is_active" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="is_active" 
                                    name="is_active" 
                                    required
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('is_active') border-red-500 dark:border-red-500 @enderror">
                                <option value="1" {{ old('is_active', $banner->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $banner->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Schedule --}}
                        <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="start_date" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Start Date
                                </label>
                                <input type="datetime-local" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date', $banner->start_date ? $banner->start_date->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('start_date') border-red-500 dark:border-red-500 @enderror">
                                @error('start_date')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Optional: When banner starts</p>
                            </div>
                            <div>
                                <label for="end_date" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    End Date
                                </label>
                                <input type="datetime-local" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date', $banner->end_date ? $banner->end_date->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 @error('end_date') border-red-500 dark:border-red-500 @enderror">
                                @error('end_date')
                                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Optional: When banner ends</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-wrap gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Banner
                            </button>
                            <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="button" 
                                    @click="showDeleteModal = true"
                                    class="ml-auto inline-flex items-center gap-2 rounded-lg border border-red-200 bg-white px-5 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:border-red-800 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-500/10">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Banner
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar (1 column) --}}
        <div class="space-y-6 lg:col-span-1">
            {{-- Banner Info --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Banner Info</h3>
                </div>
                <div class="space-y-4 p-5 md:p-6">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Created</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $banner->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Last Updated</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $banner->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Display Order</p>
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">{{ $banner->order }}</span>
                    </div>
                </div>
            </div>

            {{-- Specifications --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 md:px-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Banner Specifications</h3>
                </div>
                <div class="space-y-5 p-5 md:p-6">
                    {{-- Hero Banner --}}
                    <div>
                        <div class="mb-3 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white/90">Hero Banner</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">1920x1080px</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-800">

                    {{-- Limited Edition Banner --}}
                    <div>
                        <div class="mb-3 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                                <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white/90">Limited Edition</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">800x800px</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-800">

                    {{-- Category Images Link --}}
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-500/10">
                        <h4 class="mb-2 font-medium text-gray-800 dark:text-white/90">Shop by Category</h4>
                        <a href="{{ route('admin.categories.images.edit') }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            Category Images
                        </a>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-800">

                    {{-- Tips --}}
                    <div>
                        <h4 class="mb-3 font-medium text-gray-800 dark:text-white/90">Tips</h4>
                        <ul class="space-y-1.5 text-sm text-gray-600 dark:text-gray-400">
                            <li>• Hero: Landscape 1920x1080px</li>
                            <li>• Limited: Gambar dari product</li>
                            <li>• Format: JPG, PNG, WebP</li>
                            <li>• Max size: 2MB per file</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDeleteModal" 
         x-cloak
         class="fixed inset-0 z-[99999] overflow-y-auto"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-700 dark:bg-gray-800"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">Delete Banner</h3>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete this banner? This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button type="button" 
                            @click="showDeleteModal = false"
                            class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Configuration
    const MAX_TOTAL_SIZE_MB = 3.5;
    const MAX_TOTAL_BYTES = MAX_TOTAL_SIZE_MB * 1024 * 1024;
    const COMPRESSION_QUALITY = 0.8; // Higher quality for banners
    const MAX_IMAGE_WIDTH = 1920; // Specialized for Hero Banners
    const MAX_IMAGE_HEIGHT = 1920;

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
    const form = document.getElementById('banner-form');
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

    const typeInfo = {
        hero: {
            title: '🎯 Hero Banner',
            description: '<strong>Untuk:</strong> Banner utama homepage<br><strong>Ukuran:</strong> 1920x1080px<br><strong>Fitur:</strong> Countdown timer, full screen',
            productRequired: false,
            productHelp: 'Pilih product untuk menampilkan gambar atau upload sendiri',
            showCountdown: true,
            showImage: true,
            showButtons: true
        },
        limited: {
            title: '⭐ Limited Edition Banner',
            description: '<strong>Untuk:</strong> Bagian "Limited Edition Drops"<br><strong>Ukuran:</strong> 800x800px (otomatis)<br><strong>Catatan:</strong> Semua data dari product',
            productRequired: true,
            productHelp: 'WAJIB: Pilih product limited edition',
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
            
            typeInfoDiv.classList.remove('hidden');
            typeDescription.innerHTML = info.description;
            
            productHelp.innerHTML = info.productHelp;
            if (info.productRequired) {
                productRequired.classList.remove('hidden');
                productOptional.classList.add('hidden');
                productField.required = true;
            } else {
                productRequired.classList.add('hidden');
                productOptional.classList.remove('hidden');
                productField.required = false;
            }
            
            if (info.showCountdown) {
                countdownFields.classList.remove('hidden');
            } else {
                countdownFields.classList.add('hidden');
            }
            
            if (imageField) {
                if (info.showImage) {
                    imageField.classList.remove('hidden');
                } else {
                    imageField.classList.add('hidden');
                }
            }
            
            if (buttonFields) {
                if (info.showButtons) {
                    buttonFields.classList.remove('hidden');
                } else {
                    buttonFields.classList.add('hidden');
                }
            }
        } else {
            typeInfoDiv.classList.add('hidden');
            countdownFields.classList.add('hidden');
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
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateFormFields();
    });
</script>
@endpush
