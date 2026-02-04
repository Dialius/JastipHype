@extends('admin.layouts.app')

@section('title', 'Banner Management')

@section('page-title', 'Banners')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Banner Management</h2>
            <nav class="mt-2 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-300">Home</a>
                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-gray-800 dark:text-white/90">Banners</span>
            </nav>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Banner
        </a>
    </div>

    {{-- Banner Type Specifications --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white/90">Banner Specifications</h3>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="flex items-center gap-4 rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                    <svg class="h-6 w-6 fill-gray-800 dark:fill-white/90" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white/90">Hero Banner</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">1920 x 600 px</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                    <svg class="h-6 w-6 fill-gray-800 dark:fill-white/90" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white/90">Secondary Banner</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">1200 x 400 px</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                    <svg class="h-6 w-6 fill-gray-800 dark:fill-white/90" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white/90">Promo Banner</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">800 x 300 px</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Banners Grid --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 py-4 md:px-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All Banners</h3>
        </div>

        @if($banners->count() > 0)
        <div class="px-5 pb-5 md:px-6 md:pb-6">
            <div id="banners-sortable" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($banners as $banner)
                <div class="banner-card group overflow-hidden rounded-2xl border border-gray-200 bg-white transition-all duration-300 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800" data-id="{{ $banner->id }}" data-banner-id="{{ $banner->id }}">
                    {{-- Banner Image --}}
                    <div class="relative">
                        @if($banner->image_path)
                        <img src="{{ banner_image_url($banner) }}" 
                             class="h-40 w-full object-cover" 
                             alt="{{ $banner->title }}">
                        @else
                        <div class="flex h-40 items-center justify-center bg-gray-100 dark:bg-gray-700">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                        
                        {{-- Status Badge --}}
                        <div class="absolute right-2 top-2">
                            <span class="status-badge inline-flex rounded-full {{ $banner->is_active ? 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }} px-2.5 py-0.5 text-xs font-medium">
                                {{ $banner->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        {{-- Drag Handle --}}
                        <div class="absolute left-2 top-2 opacity-0 transition-opacity group-hover:opacity-100">
                            <span class="drag-handle inline-flex cursor-move items-center rounded-lg bg-white/80 px-2 py-1 text-xs font-medium text-gray-700 backdrop-blur dark:bg-gray-800/80 dark:text-gray-300">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                    {{-- Banner Info --}}
                    <div class="p-4">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ $banner->title }}</h4>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                {{ ucfirst($banner->type) }}
                            </span>
                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                Order: {{ $banner->order }}
                            </span>
                        </div>
                        
                        @if($banner->link_url)
                        <p class="mt-2 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                            <a href="{{ $banner->link_url }}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">
                                {{ Str::limit($banner->link_url, 25) }}
                            </a>
                        </p>
                        @endif
                        
                        @if($banner->start_date || $banner->end_date)
                        <p class="mt-2 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            @if($banner->start_date){{ $banner->start_date->format('d M Y') }}@endif
                            @if($banner->end_date)- {{ $banner->end_date->format('d M Y') }}@endif
                        </p>
                        @endif
                    </div>
                    
                    {{-- Actions --}}
                    <div class="border-t border-gray-100 px-4 py-3 dark:border-gray-700">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                Edit
                            </a>
                            <button type="button" 
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 {{ $banner->status === 'active' ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }}"
                                    onclick="toggleStatus({{ $banner->id }})">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    @if($banner->status === 'active')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                    @endif
                                </svg>
                                {{ $banner->status === 'active' ? 'Pause' : 'Activate' }}
                            </button>
                            <button type="button" 
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-red-50 hover:text-red-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                    onclick="deleteBanner({{ $banner->id }})">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="px-5 py-12 text-center md:px-6">
            <div class="flex flex-col items-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-sm font-semibold text-gray-800 dark:text-white/90">No banners yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create your first banner to get started!</p>
                <a href="{{ route('admin.banners.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add New Banner
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Delete Form --}}
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- Toggle Status Form --}}
<form id="toggle-form" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('styles')
<style>
    .sortable-ghost {
        opacity: 0.4;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Initialize Sortable
    const sortableEl = document.getElementById('banners-sortable');
    if (sortableEl) {
        const sortable = new Sortable(sortableEl, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                const items = Array.from(evt.to.children);
                const order = items.map(item => item.dataset.id);
                
                fetch('{{ route("admin.banners.update-order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Banner order updated successfully', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to update banner order', 'error');
                });
            }
        });
    }
    
    function toggleStatus(id) {
        showConfirmModal({
            title: 'Toggle Banner Status',
            message: 'Are you sure you want to change this banner status?',
            type: 'warning',
            confirmText: 'Yes, Toggle Status',
            onConfirm: () => {
                fetch(`/admin/banners/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const statusBadge = document.querySelector(`[data-banner-id="${id}"] .status-badge`);
                        if (statusBadge) {
                            if (data.is_active) {
                                statusBadge.className = 'status-badge inline-flex rounded-full bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400 px-2.5 py-0.5 text-xs font-medium';
                                statusBadge.textContent = 'Active';
                            } else {
                                statusBadge.className = 'status-badge inline-flex rounded-full bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 px-2.5 py-0.5 text-xs font-medium';
                                statusBadge.textContent = 'Inactive';
                            }
                        }
                        showToast('Banner status updated successfully', 'success');
                    } else {
                        showToast('Failed to update banner status', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while updating status', 'error');
                });
            }
        });
    }
    
    function deleteBanner(id) {
        showConfirmModal({
            title: 'Delete Banner',
            message: 'Are you sure you want to delete this banner? This action cannot be undone.',
            type: 'danger',
            confirmText: 'Yes, Delete',
            onConfirm: () => {
                const form = document.getElementById('delete-form');
                form.action = `/admin/banners/${id}`;
                form.submit();
            }
        });
    }
</script>
@endpush
