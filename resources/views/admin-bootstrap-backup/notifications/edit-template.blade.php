@extends('admin.layouts.app')

@section('title', 'Edit Email Template')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Email Template: {{ ucwords(str_replace('-', ' ', $template)) }}</h1>
        <a href="{{ route('admin.notifications.templates') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Templates
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Template Editor</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.notifications.templates.update', $template) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">Template Content</label>
                            <textarea name="content" id="content" class="form-control font-monospace" rows="20" required>{{ old('content', $content) }}</textarea>
                            <div class="form-text">Use Blade syntax for dynamic content</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Template
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="previewBtn">
                                <i class="bi bi-eye"></i> Preview
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Available Variables</h5>
                </div>
                <div class="card-body">
                    @if(count($variables) > 0)
                        <p class="text-muted small">You can use these variables in your template:</p>
                        <ul class="list-unstyled">
                            @foreach($variables as $var => $description)
                                <li class="mb-2">
                                    <code class="text-primary">@{{ '$' . $var }}</code>
                                    <p class="small text-muted mb-0">{{ $description }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No variables available for this template.</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Blade Syntax Help</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Common Blade directives:</p>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <code>@{{ '$variable' }}</code><br>
                            <span class="text-muted">Output variable</span>
                        </li>
                        <li class="mb-2">
                            <code>@@if($condition) ... @@endif</code><br>
                            <span class="text-muted">Conditional</span>
                        </li>
                        <li class="mb-2">
                            <code>@@foreach($items as $item) ... @@endforeach</code><br>
                            <span class="text-muted">Loop</span>
                        </li>
                        <li class="mb-2">
                            <code>@{{ 'number_format($price)' }}</code><br>
                            <span class="text-muted">Format number</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
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
    const previewBtn = document.getElementById('previewBtn');
    
    previewBtn.addEventListener('click', function() {
        const previewContent = document.getElementById('previewContent');
        
        previewContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        previewModal.show();
        
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
                previewContent.innerHTML = data.html;
            } else {
                previewContent.innerHTML = '<div class="alert alert-danger">Failed to load preview: ' + (data.error || 'Unknown error') + '</div>';
            }
        })
        .catch(error => {
            previewContent.innerHTML = '<div class="alert alert-danger">Error loading preview: ' + error.message + '</div>';
        });
    });
});
</script>
@endpush
