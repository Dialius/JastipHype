@extends('admin.layouts.app')

@section('title', 'Brands')

@section('page-title', 'Brands')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Brands</h2>
            <nav class="mt-2 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-300">Home</a>
                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-gray-800 dark:text-white/90">Brands</span>
            </nav>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Brand
        </a>
    </div>

    {{-- Brands Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="brandsContainer">
        @forelse($brands as $brand)
        <div class="brand-card group rounded-2xl border border-gray-200 bg-white p-5 transition hover:shadow-lg dark:border-gray-800 dark:bg-white/[0.03]" data-brand-id="{{ $brand->id }}">
            {{-- Logo Section --}}
            <div class="relative mb-4 flex justify-center">
                @if($brand->logo)
                    <img src="{{ asset('images/brands/' . $brand->logo) }}" 
                         alt="{{ $brand->name }}" 
                         class="h-24 w-24 object-contain">
                @elseif($brand->logo_path)
                    <img src="{{ brand_logo_url($brand) }}" 
                         alt="{{ $brand->name }}" 
                         class="h-24 w-24 object-contain">
                @else
                    <div class="flex h-24 w-24 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                @endif
                
                {{-- Featured Badge --}}
                @if($brand->is_featured)
                    <span class="absolute -top-2 -right-2 inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-500">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Featured
                    </span>
                @endif
            </div>

            {{-- Brand Info --}}
            <div class="mb-4 text-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $brand->name }}</h3>
                @if($brand->description)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($brand->description, 50) }}</p>
                @endif
                
                {{-- Status Badge --}}
                <div class="mt-3">
                    @if($brand->status === 'active')
                        <span class="inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">Active</span>
                    @else
                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">Inactive</span>
                    @endif
                </div>
            </div>

            {{-- Statistics --}}
            <div class="mb-4 grid grid-cols-2 gap-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                <div class="text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Products</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $brand->product_count ?? 0 }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Revenue</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white/90">Rp {{ number_format(($brand->total_revenue ?? 0) / 1000, 0) }}K</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="flex flex-1 items-center justify-center gap-1 rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    Edit
                </a>
                <button type="button" class="flex items-center justify-center rounded-lg border border-gray-200 px-3 py-2 text-gray-500 transition hover:bg-red-50 hover:text-red-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-red-500/10 dark:hover:text-red-400 delete-btn" data-id="{{ $brand->id }}" data-name="{{ $brand->name }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
                <button type="button" class="flex items-center justify-center rounded-lg border border-gray-200 px-3 py-2 text-gray-500 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 toggle-status-btn" data-id="{{ $brand->id }}" title="Toggle Status">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </button>
            </div>

            {{-- Drag Handle --}}
            <div class="drag-handle mt-4 flex cursor-move justify-center border-t border-gray-100 pt-3 dark:border-gray-800">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" />
                </svg>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-sm font-semibold text-gray-800 dark:text-white/90">No brands found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first brand.</p>
                <a href="{{ route('admin.brands.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Your First Brand
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 z-[99999] hidden overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-700 dark:bg-gray-800">
            <div class="text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800 dark:text-white/90">Confirm Delete</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to delete brand <strong id="deleteBrandName" class="text-gray-800 dark:text-white/90"></strong>?
                </p>
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                    This action cannot be undone.
                </p>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
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
@endsection

@push('styles')
<style>
    .brand-card {
        cursor: default;
    }
    .brand-card .drag-handle {
        cursor: move;
    }
    .sortable-ghost {
        opacity: 0.4;
    }
    .sortable-drag {
        opacity: 0.8;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Initialize Sortable
    const brandsContainer = document.getElementById('brandsContainer');
    
    if (brandsContainer && brandsContainer.children.length > 0) {
        new Sortable(brandsContainer, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                const orders = [];
                const items = brandsContainer.querySelectorAll('[data-brand-id]');
                
                items.forEach((item, index) => {
                    orders.push({
                        id: item.dataset.brandId,
                        order: index
                    });
                });
                
                fetch('/admin/brands/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ orders: orders })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Order updated successfully');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    location.reload();
                });
            }
        });
    }
    
    // Delete Brand
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const brandId = this.dataset.id;
            const brandName = this.dataset.name;
            
            document.getElementById('deleteBrandName').textContent = brandName;
            document.getElementById('deleteForm').action = `/admin/brands/${brandId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        });
    });
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    // Toggle Status
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const brandId = this.dataset.id;
            const button = this;
            
            fetch(`/admin/brands/${brandId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = button.closest('.brand-card');
                    const badge = card.querySelector('.inline-flex.rounded-full');
                    
                    if (data.status === 'active') {
                        badge.className = 'inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500';
                        badge.textContent = 'Active';
                    } else {
                        badge.className = 'inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400';
                        badge.textContent = 'Inactive';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
</script>
@endpush
