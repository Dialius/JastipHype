@extends('admin.layouts.app')

@section('title', 'Review Details')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Review Details</h1>
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
                            <a href="{{ route('admin.reviews.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Reviews</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Details</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to List
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Review Content (2 columns) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Product Info -->
        <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Product Information</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    @if($review->product->productImages->first())
                    <img src="{{ \App\Helpers\ImageHelper::getImageUrl($review->product->productImages->first()->image_path) }}" 
                         alt="{{ $review->product->name }}" 
                         class="h-20 w-20 rounded-lg object-cover mr-4">
                    @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-gray-100 mr-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    @endif
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">{{ $review->product->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">SKU: {{ $review->product->sku }}</p>
                        <div class="mt-2 flex gap-2">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                {{ $review->product->brand->name }}
                            </span>
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                {{ $review->product->category->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Content -->
        <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Review Content</h2>
            </div>
            <div class="p-6 space-y-4">
                <!-- Rating -->
                <div>
                    <div class="flex items-center gap-3">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                            {{ $review->rating }} / 5
                        </span>
                        @if($review->verified_purchase)
                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            Verified Purchase
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Title -->
                @if($review->title)
                <div>
                    <h3 class="text-base font-semibold text-gray-900">{{ $review->title }}</h3>
                </div>
                @endif

                <!-- Comment -->
                <div>
                    <p class="text-sm text-gray-700">{{ $review->comment }}</p>
                </div>

                <!-- Images -->
                @if($review->images->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Review Images</label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                        @foreach($review->images as $image)
                        <a href="{{ \App\Helpers\ImageHelper::getImageUrl($image->image_path) }}" target="_blank" class="group">
                            <img src="{{ \App\Helpers\ImageHelper::getImageUrl($image->image_path) }}" 
                                 alt="Review Image" 
                                 class="h-32 w-full rounded-lg object-cover ring-1 ring-gray-200 group-hover:ring-2 group-hover:ring-blue-500">
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif>

                <!-- Date -->
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Posted on {{ $review->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

        <!-- Admin Response -->
        <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Admin Response</h2>
            </div>
            <div class="p-6 space-y-4">
                @if($review->response)
                <!-- Existing Response -->
                <div class="rounded-md bg-blue-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.793 2.232a.75.75 0 01-.025 1.06L3.622 7.25h10.003a5.375 5.375 0 010 10.75H10.75a.75.75 0 010-1.5h2.875a3.875 3.875 0 000-7.75H3.622l4.146 3.957a.75.75 0 01-1.036 1.085l-5.5-5.25a.75.75 0 010-1.085l5.5-5.25a.75.75 0 011.06.025z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-blue-800">{{ $review->response->response }}</p>
                            <p class="mt-2 text-xs text-blue-700">
                                Responded by <strong>{{ $review->response->user->name }}</strong> 
                                on {{ $review->response->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Response Form -->
                <form action="{{ route('admin.reviews.respond', $review) }}" method="POST">
                    @csrf
                    <div>
                        <label for="response" class="block text-sm font-medium text-gray-700">
                            {{ $review->response ? 'Update Response' : 'Add Response' }}
                        </label>
                        <textarea id="response" name="response" rows="4" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('response') border-red-500 @enderror" 
                                  placeholder="Write your response to this review...">{{ old('response', $review->response->response ?? '') }}</textarea>
                        @error('response')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">This response will be visible to customers on the product page.</p>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            {{ $review->response ? 'Update Response' : 'Send Response' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar (1 column) -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Customer Info -->
        <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Customer Information</h2>
            </div>
            <div class="p-6">
                <div class="text-center mb-4">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">
                        <span class="text-2xl font-bold text-blue-600">{{ $review->user_initial }}</span>
                    </div>
                </div>
                <div class="text-center mb-4">
                    <h3 class="text-base font-semibold text-gray-900">{{ $review->user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $review->user->email }}</p>
                </div>
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Reviews:</span>
                        <span class="font-semibold text-gray-900">{{ $review->user->reviews->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Orders:</span>
                        <span class="font-semibold text-gray-900">{{ $review->user->orders->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Member Since:</span>
                        <span class="font-semibold text-gray-900">{{ $review->user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
            <div class="border-b border-gray-200 bg-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Actions</h2>
            </div>
            <div class="p-6 space-y-3">
                <!-- Approve (placeholder) -->
                <button type="button" 
                        class="inline-flex w-full items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 opacity-50 cursor-not-allowed"
                        onclick="approveReview({{ $review->id }})"
                        disabled
                        title="Reviews are approved by default">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Approve Review
                </button>

                <!-- Reject -->
                <button type="button" 
                        class="inline-flex w-full items-center justify-center rounded-md bg-yellow-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500"
                        onclick="document.getElementById('rejectModal').classList.remove('hidden')">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Reject Review
                </button>

                <!-- Delete -->
                <button type="button" 
                        class="inline-flex w-full items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500"
                        onclick="deleteReview({{ $review->id }})">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Delete Review
                </button>

                <div class="border-t border-gray-200 pt-3 space-y-3">
                    <!-- View Product -->
                    <a href="{{ route('products.show', $review->product) }}" 
                       class="inline-flex w-full items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                       target="_blank">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        View Product
                    </a>

                    <!-- View Customer -->
                    <a href="{{ route('admin.customers.show', $review->user) }}" 
                       class="inline-flex w-full items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        View Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="document.getElementById('rejectModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Reject Review</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">Are you sure you want to reject this review? It will be hidden from the product page.</p>
                                <div>
                                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason (Optional)</label>
                                    <textarea id="reason" name="reason" rows="3" 
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm"
                                              placeholder="Enter reason for rejection..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-yellow-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 sm:ml-3 sm:w-auto">
                        Reject Review
                    </button>
                    <button type="button" 
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                            onclick="document.getElementById('rejectModal').classList.add('hidden')">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<!-- Approve Form -->
<form id="approve-form" action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="hidden">
    @csrf
</form>
@endsection

@push('scripts')
<script>
    function deleteReview(id) {
        if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
            document.getElementById('delete-form').submit();
        }
    }

    function approveReview(id) {
        if (confirm('Are you sure you want to approve this review?')) {
            document.getElementById('approve-form').submit();
        }
    }
</script>
@endpush
