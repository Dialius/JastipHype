@extends('admin.layouts.app')

@section('title', 'Notification History')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Notification History</h1>
        <a href="{{ route('admin.notifications.templates') }}" class="btn btn-secondary">
            <i class="bi bi-envelope"></i> Email Templates
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Queued Notifications</h5>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Queue</th>
                                <th>Attempts</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->id }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $notification->queue }}</span>
                                    </td>
                                    <td>
                                        @if($notification->attempts > 0)
                                            <span class="badge bg-warning">{{ $notification->attempts }} attempts</span>
                                        @else
                                            <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary view-payload-btn" 
                                                data-payload="{{ base64_encode($notification->payload) }}">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $notifications->links('vendor.pagination.bootstrap-5-simple') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">No notifications in queue</p>
                </div>
            @endif
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Failed Notifications</h5>
        </div>
        <div class="card-body">
            @php
                $failedJobs = \DB::table('failed_jobs')->orderBy('failed_at', 'desc')->paginate(10, ['*'], 'failed_page');
            @endphp

            @if($failedJobs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Queue</th>
                                <th>Exception</th>
                                <th>Failed At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($failedJobs as $job)
                                <tr>
                                    <td>{{ $job->id }}</td>
                                    <td>
                                        <span class="badge bg-danger">{{ $job->queue }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($job->exception, 50) }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($job->failed_at)->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <form action="{{ route('admin.notifications.retry', $job->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning">
                                                <i class="bi bi-arrow-clockwise"></i> Retry
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-danger view-exception-btn" 
                                                data-exception="{{ base64_encode($job->exception) }}">
                                            <i class="bi bi-exclamation-triangle"></i> View Error
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $failedJobs->links('vendor.pagination.bootstrap-5-simple') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-check-circle" style="font-size: 3rem; color: #28a745;"></i>
                    <p class="text-muted mt-3">No failed notifications</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Payload Modal -->
<div class="modal fade" id="payloadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Job Payload</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="payloadContent" class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Exception Modal -->
<div class="modal fade" id="exceptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exception Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="exceptionContent" class="bg-light p-3 rounded text-danger" style="max-height: 400px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payloadModal = new bootstrap.Modal(document.getElementById('payloadModal'));
    const exceptionModal = new bootstrap.Modal(document.getElementById('exceptionModal'));
    
    // View payload
    document.querySelectorAll('.view-payload-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const payload = atob(this.dataset.payload);
            document.getElementById('payloadContent').textContent = payload;
            payloadModal.show();
        });
    });
    
    // View exception
    document.querySelectorAll('.view-exception-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const exception = atob(this.dataset.exception);
            document.getElementById('exceptionContent').textContent = exception;
            exceptionModal.show();
        });
    });
});
</script>
@endpush
