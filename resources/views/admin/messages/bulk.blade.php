@extends('admin.layouts.app')

@section('title', 'Bulk Messaging')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-2">Bulk Messaging</h1>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-700 hover:text-blue-600">
                        Customers
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-gray-500">Bulk Messaging</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Send Message to Multiple Customers</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.messages.send-bulk') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Subject <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject') }}" 
                                   required
                                   placeholder="e.g., Special Promotion for Our Valued Customers">
                            @error('subject')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('message') border-red-500 @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      required
                                      placeholder="Enter your message here...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Maximum 2000 characters</p>
                        </div>

                        <!-- Segmentation -->
                        <div>
                            <label for="segment" class="block text-sm font-medium text-gray-700 mb-2">
                                Customer Segment <span class="text-red-500">*</span>
                            </label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('segment') border-red-500 @enderror" 
                                    id="segment" 
                                    name="segment" 
                                    required>
                                <option value="">Select Segment</option>
                                <option value="all" {{ old('segment') == 'all' ? 'selected' : '' }}>All Customers</option>
                                <option value="active" {{ old('segment') == 'active' ? 'selected' : '' }}>Active Customers Only</option>
                                <option value="suspended" {{ old('segment') == 'suspended' ? 'selected' : '' }}>Suspended Customers</option>
                                <option value="high_spenders" {{ old('segment') == 'high_spenders' ? 'selected' : '' }}>High Spenders</option>
                                <option value="recent_orders" {{ old('segment') == 'recent_orders' ? 'selected' : '' }}>Recent Orders</option>
                            </select>
                            @error('segment')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Advanced Filters -->
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Advanced Filters (Optional)</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="min_spending" class="block text-sm font-medium text-gray-700 mb-2">
                                        Minimum Spending (Rp)
                                    </label>
                                    <input type="number" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           id="min_spending" 
                                           name="min_spending" 
                                           value="{{ old('min_spending') }}"
                                           min="0"
                                           placeholder="e.g., 1000000">
                                </div>
                                <div>
                                    <label for="max_spending" class="block text-sm font-medium text-gray-700 mb-2">
                                        Maximum Spending (Rp)
                                    </label>
                                    <input type="number" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           id="max_spending" 
                                           name="max_spending" 
                                           value="{{ old('max_spending') }}"
                                           min="0"
                                           placeholder="e.g., 10000000">
                                </div>
                                <div>
                                    <label for="min_orders" class="block text-sm font-medium text-gray-700 mb-2">
                                        Minimum Orders
                                    </label>
                                    <input type="number" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           id="min_orders" 
                                           name="min_orders" 
                                           value="{{ old('min_orders') }}"
                                           min="0"
                                           placeholder="e.g., 5">
                                </div>
                                <div>
                                    <label for="days_since_order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Days Since Last Order
                                    </label>
                                    <input type="number" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           id="days_since_order" 
                                           name="days_since_order" 
                                           value="{{ old('days_since_order') }}"
                                           min="0"
                                           placeholder="e.g., 30">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between pt-4">
                            <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Send Bulk Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Segmentation Guide -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-blue-600 rounded-t-lg">
                    <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Segmentation Guide
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1">All Customers</h4>
                        <p class="text-sm text-gray-600">Send to all registered customers</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Active Customers Only</h4>
                        <p class="text-sm text-gray-600">Send only to customers with active accounts</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Suspended Customers</h4>
                        <p class="text-sm text-gray-600">Send only to suspended accounts</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1">High Spenders</h4>
                        <p class="text-sm text-gray-600">Customers who spent more than Rp 1,000,000</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Recent Orders</h4>
                        <p class="text-sm text-gray-600">Customers who ordered in the last 30 days</p>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-amber-500 rounded-t-lg">
                    <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Important Notes
                    </h3>
                </div>
                <div class="p-6">
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Messages will be sent via email to all selected customers</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Use advanced filters to narrow down your audience</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Preview your message before sending</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Bulk messages are queued and sent in the background</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
