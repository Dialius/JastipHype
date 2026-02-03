@extends('admin.layouts.app')

@section('title', 'Email Templates')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Email Templates</h1>
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

    <div class="row">
        @foreach($templates as $template)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $template['title'] }}</h5>
                        <p class="card-text text-muted">{{ $template['description'] }}</p>
                        <p class="small text-muted mb-0">
                            <i class="bi bi-file-code"></i> {{ $template['path'] }}
                        </p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('admin.notifications.templates.edit', $template['name']) }}" 
                           class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i> Edit Template
                        </a>
                        <button type="button" 
                                class="btn btn-sm btn-outline-secondary preview-btn"
                                data-template="{{ $template['name'] }}">
                            <i class="bi bi-eye"></i> Preview
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Template Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    
    document.querySelectorAll('.preview-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const template = this.dataset.template;
            const previewContent = document.getElementById('previewContent');
            
            previewContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            previewModal.show();
            
            fetch(`/admin/notifications/templates/${template}/preview`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    previewContent.innerHTML = data.html;
                } else {
                    previewContent.innerHTML = '<div class="alert alert-danger">Failed to load preview</div>';
                }
            })
            .catch(error => {
                previewContent.innerHTML = '<div class="alert alert-danger">Error loading preview</div>';
            });
        });
    });
});
</script>
@endpush
