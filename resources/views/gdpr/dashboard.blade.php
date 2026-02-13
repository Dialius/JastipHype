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
            <h2 class="text-xl font-semibold mb-4">📥 Export Data Anda</h2>
            <p class="text-gray-600 mb-4">
                Download semua data pribadi Anda dalam format JSON. File akan tersedia selama 7 hari.
            </p>
            <form action="{{ route('gdpr.request-export') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Request Data Export
                </button>
            </form>

            @if($exportRequests->count() > 0)
                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Riwayat Export:</h3>
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
            <h2 class="text-xl font-semibold mb-4">🗑️ Hapus Data Anda</h2>
            <p class="text-gray-600 mb-4">
                Request penghapusan permanen semua data pribadi Anda. Proses ini tidak dapat dibatalkan.
            </p>
            <button 
                onclick="openDeleteModal()" 
                class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700"
            >
                Request Data Deletion
            </button>

            @if($deletionRequests->count() > 0)
                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Riwayat Deletion:</h3>
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
                                    <p class="text-sm text-gray-600 mt-2">Alasan: {{ $request->reason }}</p>
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
        <h2 class="text-xl font-semibold mb-4">📋 Hak Privasi Anda</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <h3 class="font-semibold mb-2">✅ Hak Akses</h3>
                <p class="text-sm text-gray-600">Anda dapat mengakses dan mendownload data pribadi Anda kapan saja.</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">✏️ Hak Perbaikan</h3>
                <p class="text-sm text-gray-600">Anda dapat memperbaiki data yang tidak akurat melalui profile settings.</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">🗑️ Hak Penghapusan</h3>
                <p class="text-sm text-gray-600">Anda dapat meminta penghapusan data pribadi Anda.</p>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold mb-4">⚠️ Konfirmasi Penghapusan Data</h3>
        <p class="text-gray-600 mb-4">
            Tindakan ini akan menghapus semua data pribadi Anda secara permanen, termasuk:
        </p>
        <ul class="list-disc pl-6 mb-4 text-sm text-gray-600">
            <li>Informasi profil</li>
            <li>Riwayat pesanan (akan dianonimkan)</li>
            <li>Review dan wishlist</li>
            <li>Shopping cart</li>
        </ul>
        <p class="text-red-600 font-semibold mb-4">Proses ini tidak dapat dibatalkan!</p>
        
        <form action="{{ route('gdpr.request-deletion') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Alasan (opsional):</label>
                <textarea 
                    name="reason" 
                    rows="3" 
                    class="w-full border rounded px-3 py-2"
                    placeholder="Mengapa Anda ingin menghapus data?"
                ></textarea>
            </div>
            <div class="flex gap-3">
                <button 
                    type="button" 
                    onclick="closeDeleteModal()" 
                    class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                >
                    Ya, Hapus Data Saya
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
