@extends('admin.layouts.app')

@section('title', 'Edit Email Template')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.notifications.templates') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Templates
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit Template</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">{{ ucwords(str_replace('-', ' ', $template)) }}</h1>
            <p class="mt-1 text-sm text-gray-500">Customize email template content and variables</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.notifications.templates') }}" 
               class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to Templates
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Editor (2 columns) -->
    <div class="lg:col-span-2">
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Template Editor</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.notifications.templates.update', $template) }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Template Content
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="20" 
                                  required
                                  class="block w-full rounded-md border-gray-300 font-mono text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-500 @enderror">{{ old('content', $content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            <svg class="inline h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                            </svg>
                            Use Blade syntax for dynamic content. See available variables in the sidebar.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" 
                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Save Template
                        </button>
                        <button type="button" 
                                onclick="previewTemplate()"
                                class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Preview
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar (1 column) -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Available Variables -->
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Available Variables</h2>
            </div>
            <div class="p-6">
                @if(count($variables) > 0)
                    <p class="text-sm text-gray-600 mb-4">You can use these variables in your template:</p>
                    <div class="space-y-3">
                        @foreach($variables as $var => $description)
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <code class="block rounded bg-blue-50 px-2 py-1 text-sm font-mono text-blue-700">&#123;&#123; ${{ $var }} &#125;&#125;</code>
                            <p class="mt-1 text-xs text-gray-600">{{ $description }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-md bg-gray-50 p-4">
                        <p class="text-sm text-gray-500">No variables available for this template.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Blade Syntax Help -->
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Blade Syntax Help</h2>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Common Blade directives:</p>
                <div class="space-y-3">
                    <div>
                        <code class="block rounded bg-gray-100 px-2 py-1 text-xs font-mono text-gray-800">&#123;&#123; $variable &#125;&#125;</code>
                        <p class="mt-1 text-xs text-gray-600">Output variable</p>
                    </div>
                    <div>
                        <code class="block rounded bg-gray-100 px-2 py-1 text-xs font-mono text-gray-800">&#64;if($condition) ... &#64;endif</code>
                        <p class="mt-1 text-xs text-gray-600">Conditional statement</p>
                    </div>
                    <div>
                        <code class="block rounded bg-gray-100 px-2 py-1 text-xs font-mono text-gray-800">&#64;foreach($items as $item) ... &#64;endforeach</code>
                        <p class="mt-1 text-xs text-gray-600">Loop through items</p>
                    </div>
                    <div>
                        <code class="block rounded bg-gray-100 px-2 py-1 text-xs font-mono text-gray-800">&#123;&#123; number_format($price) &#125;&#125;</code>
                        <p class="mt-1 text-xs text-gray-600">Format numbers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closePreviewModal()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl sm:align-middle">
            <!-- Header -->
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Template Preview</h3>
                    <button type="button" onclick="closePreviewModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-gray-50 px-6 py-6">
                <div id="previewContent" class="rounded-lg bg-white p-6 shadow-sm">
                    <!-- Loading state -->
                    <div class="flex items-center justify-center py-12">
                        <svg class="h-8 w-8 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                <div class="flex justify-end">
                    <button type="button" onclick="closePreviewModal()" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewTemplate() {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewContent');
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Show loading
    content.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <svg class="h-8 w-8 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    `;
    
    // Fetch preview
    fetch('/admin/notifications/templates/{{ $template }}/preview', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.html) {
            content.innerHTML = data.html;
        } else {
            content.innerHTML = `
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Failed to load preview</h3>
                            <p class="mt-1 text-sm text-red-700">${data.error || 'Unknown error'}</p>
                        </div>
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        content.innerHTML = `
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Error loading preview</h3>
                        <p class="mt-1 text-sm text-red-700">${error.message}</p>
                    </div>
                </div>
            </div>
        `;
    });
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreviewModal();
    }
});
</script>
@endpush
