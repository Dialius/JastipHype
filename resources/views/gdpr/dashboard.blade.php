@extends('layouts.app')

@section('title', 'GDPR Dashboard - JastipHype')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <h1 class="text-3xl font-bold mb-6">Data Privacy Dashboard</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <!-- Export Data -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">📥 Export Your Data</h2>
            <p class="text-gray-600 mb-4">
                Download all your personal data in JSON format. The file will be available for 7 days.
            </p>
            <form action="{{ route('gdpr.request-export') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Request Data Export
                </button>
            </form>

            @if($exportRequests->count() > 0)
                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Export History:</h3>
                    <div class="space-y-2">
                        @foreach($exportRequests as $request)
                            <div class="border rounded p-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-sm text-gray-600">
                                            {{ $request->created_at->format('d M Y H:i') }}
                                        </span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded
                                            @if($request->status === 'completed') bg-green-100 text-green-800
                                            @elseif($request->status === 'processing') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'failed') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                    @if($request->status === 'completed' && !$request->isExpired())
                                        <a href="{{ route('gdpr.download-export', $request->id) }}" 
                                           class="text-blue-600 hover:underline text-sm">
                                            Download
                                        </a>
                                    @elseif($request->isExpired())
                                        <span class="text-red-600 text-sm">Expired</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Delete Data -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">🗑️ Delete Your Data</h2>
            <p class="text-gray-600 mb-4">
                Request permanent deletion of all your personal data. This process cannot be undone.
            </p>
            <button 
                onclick="openDeleteModal()" 
                class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700"
            >
                Request Data Deletion
            </button>

            @if($deletionRequests->count() > 0)
                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Deletion History:</h3>
                    <div class="space-y-2">
                        @foreach($deletionRequests as $request)
                            <div class="border rounded p-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-sm text-gray-600">
                                            {{ $request->created_at->format('d M Y H:i') }}
                                        </span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded
                                            @if($request->status === 'completed') bg-green-100 text-green-800
                                            @elseif($request->status === 'approved') bg-blue-100 text-blue-800
                                            @elseif($request->status === 'processing') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                </div>
                                @if($request->reason)
                                    <p class="text-sm text-gray-600 mt-2">Reason: {{ $request->reason }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Your Rights -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">📋 Your Privacy Rights</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <h3 class="font-semibold mb-2">✅ Right to Access</h3>
                <p class="text-sm text-gray-600">You can access and download your personal data at any time.</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">✏️ Right to Rectification</h3>
                <p class="text-sm text-gray-600">You can correct inaccurate data through profile settings.</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">🗑️ Right to Erasure</h3>
                <p class="text-sm text-gray-600">You can request deletion of your personal data.</p>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold mb-4">⚠️ Confirm Data Deletion</h3>
        <p class="text-gray-600 mb-4">
            This action will permanently delete all your personal data, including:
        </p>
        <ul class="list-disc pl-6 mb-4 text-sm text-gray-600">
            <li>Profile information</li>
            <li>Order history (will be anonymized)</li>
            <li>Reviews and wishlist</li>
            <li>Shopping cart</li>
        </ul>
        <p class="text-red-600 font-semibold mb-4">This process cannot be undone!</p>
        
        <form action="{{ route('gdpr.request-deletion') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Reason (optional):</label>
                <textarea 
                    name="reason" 
                    rows="3" 
                    class="w-full border rounded px-3 py-2"
                    placeholder="Why do you want to delete your data?"
                ></textarea>
            </div>
            <div class="flex gap-3">
                <button 
                    type="button" 
                    onclick="closeDeleteModal()" 
                    class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                >
                    Yes, Delete My Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
