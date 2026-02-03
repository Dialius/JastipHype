{{--
    Admin File Upload Component (Drag & Drop)
    
    Usage:
    <x-admin.form.file-upload 
        name="images" 
        label="Product Images"
        accept="image/*"
        multiple
        :previews="$existingImages ?? []"
        :error="$errors->first('images')"
    />
--}}

@props([
    'name' => '',
    'label' => '',
    'accept' => 'image/*',
    'multiple' => false,
    'maxSize' => 2, // MB
    'maxFiles' => 5,
    'previews' => [], // Existing image URLs
    'error' => null,
    'help' => null,
    'required' => false,
])

@php
    $hasError = !empty($error);
    $inputId = $name . '_input';
    $uniqueId = 'upload_' . uniqid();
@endphp

<div {{ $attributes->merge(['class' => 'mb-4.5']) }}
     x-data="{
         isDragging: false,
         files: [],
         previews: @json($previews),
         maxFiles: {{ $maxFiles }},
         maxSize: {{ $maxSize * 1024 * 1024 }},
         
         handleDrop(e) {
             this.isDragging = false;
             if (e.dataTransfer.files.length) {
                 this.addFiles(e.dataTransfer.files);
             }
         },
         
         handleFileSelect(e) {
             if (e.target.files.length) {
                 this.addFiles(e.target.files);
             }
         },
         
         addFiles(newFiles) {
             const fileArray = Array.from(newFiles);
             
             for (const file of fileArray) {
                 if (this.files.length + this.previews.length >= this.maxFiles) {
                     alert('Maximum ' + this.maxFiles + ' files allowed');
                     break;
                 }
                 
                 if (file.size > this.maxSize) {
                     alert(file.name + ' is too large. Max size is {{ $maxSize }}MB');
                     continue;
                 }
                 
                 if (!file.type.match('image.*') && '{{ $accept }}' === 'image/*') {
                     alert(file.name + ' is not an image');
                     continue;
                 }
                 
                 this.files.push(file);
             }
             
             this.updateInput();
         },
         
         removeFile(index) {
             this.files.splice(index, 1);
             this.updateInput();
         },
         
         removePreview(index) {
             this.previews.splice(index, 1);
         },
         
         updateInput() {
             const input = this.$refs.fileInput;
             const dt = new DataTransfer();
             this.files.forEach(file => dt.items.add(file));
             input.files = dt.files;
         },
         
         getPreviewUrl(file) {
             return URL.createObjectURL(file);
         }
     }">
    
    @if($label)
        <label class="mb-2.5 block text-sm font-medium text-gray-900 dark:text-white">
            {{ $label }}
            @if($required)
                <span class="text-meta-1">*</span>
            @endif
        </label>
    @endif
    
    <!-- Dropzone Area -->
    <div 
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="handleDrop($event)"
        @click="$refs.fileInput.click()"
        :class="isDragging ? 'border-admin-primary bg-admin-primary/5' : 'border-gray-300 dark:border-form-strokedark'"
        class="relative flex flex-col items-center justify-center p-6 border-2 border-dashed rounded-lg cursor-pointer hover:border-admin-primary hover:bg-gray-50 dark:hover:bg-boxdark-2 transition-colors"
    >
        <input 
            type="file" 
            name="{{ $name }}{{ $multiple ? '[]' : '' }}"
            id="{{ $inputId }}"
            x-ref="fileInput"
            @change="handleFileSelect($event)"
            accept="{{ $accept }}"
            @if($multiple) multiple @endif
            @if($required && empty($previews)) required @endif
            class="sr-only"
        >
        
        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
        </svg>
        
        <p class="mb-1 text-sm font-medium text-gray-700 dark:text-bodydark">
            <span class="text-admin-primary">Click to upload</span> or drag and drop
        </p>
        <p class="text-xs text-gray-500 dark:text-bodydark2">
            @if($accept === 'image/*')
                PNG, JPG or WEBP (max {{ $maxSize }}MB)
            @else
                Max {{ $maxSize }}MB per file
            @endif
            @if($multiple)
                - Up to {{ $maxFiles }} files
            @endif
        </p>
    </div>
    
    <!-- Previews -->
    <div x-show="files.length > 0 || previews.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <!-- Existing Previews -->
        <template x-for="(preview, index) in previews" :key="'existing-' + index">
            <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 dark:border-strokedark bg-gray-100 dark:bg-boxdark-2">
                <img :src="preview" class="w-full h-full object-cover" alt="Preview">
                <button 
                    type="button"
                    @click.stop="removePreview(index)"
                    class="absolute top-1 right-1 p-1 rounded-full bg-red-500 text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
        
        <!-- New File Previews -->
        <template x-for="(file, index) in files" :key="'new-' + index">
            <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 dark:border-strokedark bg-gray-100 dark:bg-boxdark-2">
                <img :src="getPreviewUrl(file)" class="w-full h-full object-cover" alt="Preview">
                <button 
                    type="button"
                    @click.stop="removeFile(index)"
                    class="absolute top-1 right-1 p-1 rounded-full bg-red-500 text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="absolute bottom-0 left-0 right-0 bg-black/60 px-2 py-1">
                    <p class="text-xs text-white truncate" x-text="file.name"></p>
                </div>
            </div>
        </template>
    </div>
    
    @if($help && !$hasError)
        <p class="mt-2 text-xs text-gray-500 dark:text-bodydark2">{{ $help }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-2 text-xs text-meta-1">{{ $error }}</p>
    @endif
</div>
