@extends('admin.layouts.app')

@section('title', 'System Monitor')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">System Monitor</h1>
            <p class="text-muted mb-0">Monitor system health and service status</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="refreshAll()">
            <i class="bi bi-arrow-clockwise me-2"></i>Refresh All
        </button>
    </div>

    <!-- System Health Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">PHP Version</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $systemHealth['php_version'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-code-square fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Laravel Version</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $systemHealth['laravel_version'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-layers fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Server Time</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                {{ $systemHealth['server_time'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Memory Usage</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                {{ $systemHealth['memory_usage']['current'] }}
                            </div>
                            <small class="text-muted">Peak: {{ $systemHealth['memory_usage']['peak'] }}</small>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-memory fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disk Usage -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Disk Usage</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar bg-{{ $systemHealth['disk_usage']['percentage'] > 80 ? 'danger' : ($systemHealth['disk_usage']['percentage'] > 60 ? 'warning' : 'success') }}" 
                             role="progressbar" 
                             style="width: {{ $systemHealth['disk_usage']['percentage'] }}%"
                             aria-valuenow="{{ $systemHealth['disk_usage']['percentage'] }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ $systemHealth['disk_usage']['percentage'] }}%
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="mb-0">
                        <strong>Used:</strong> {{ $systemHealth['disk_usage']['used'] }} / {{ $systemHealth['disk_usage']['total'] }}
                        <br>
                        <strong>Free:</strong> {{ $systemHealth['disk_usage']['free'] }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Status -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Service Status</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkServices()">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="servicesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="bi bi-database me-2"></i><strong>Database</strong></td>
                            <td>
                                <span class="badge bg-{{ $serviceStatus['database']['status'] === 'online' ? 'success' : 'danger' }}">
                                    {{ ucfirst($serviceStatus['database']['status']) }}
                                </span>
                            </td>
                            <td>{{ $serviceStatus['database']['message'] }}</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-lightning me-2"></i><strong>Cache</strong></td>
                            <td>
                                <span class="badge bg-{{ $serviceStatus['cache']['status'] === 'online' ? 'success' : 'danger' }}">
                                    {{ ucfirst($serviceStatus['cache']['status']) }}
                                </span>
                            </td>
                            <td>
                                {{ $serviceStatus['cache']['message'] }}
                                @if(isset($serviceStatus['cache']['driver']))
                                    <br><small class="text-muted">Driver: {{ $serviceStatus['cache']['driver'] }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-envelope me-2"></i><strong>Mail (SMTP)</strong></td>
                            <td>
                                <span class="badge bg-{{ $serviceStatus['mail']['status'] === 'configured' ? 'success' : 'warning' }}">
                                    {{ ucfirst($serviceStatus['mail']['status']) }}
                                </span>
                            </td>
                            <td>
                                {{ $serviceStatus['mail']['message'] }}
                                @if(isset($serviceStatus['mail']['host']))
                                    <br><small class="text-muted">Host: {{ $serviceStatus['mail']['host'] }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-credit-card me-2"></i><strong>Midtrans</strong></td>
                            <td>
                                <span class="badge bg-{{ $serviceStatus['midtrans']['status'] === 'configured' ? 'success' : 'warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $serviceStatus['midtrans']['status'])) }}
                                </span>
                            </td>
                            <td>
                                {{ $serviceStatus['midtrans']['message'] }}
                                @if(isset($serviceStatus['midtrans']['environment']))
                                    <br><small class="text-muted">Environment: {{ $serviceStatus['midtrans']['environment'] }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-truck me-2"></i><strong>RajaOngkir</strong></td>
                            <td>
                                <span class="badge bg-{{ $serviceStatus['rajaongkir']['status'] === 'configured' ? 'success' : 'warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $serviceStatus['rajaongkir']['status'])) }}
                                </span>
                            </td>
                            <td>
                                {{ $serviceStatus['rajaongkir']['message'] }}
                                @if(isset($serviceStatus['rajaongkir']['type']))
                                    <br><small class="text-muted">Type: {{ $serviceStatus['rajaongkir']['type'] }}</small>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Database Statistics -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Database Statistics</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Total Tables:</strong> {{ $databaseStats['table_count'] }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Total Size:</strong> {{ $databaseStats['total_size'] }} MB</p>
                </div>
            </div>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-sm table-bordered">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>Table Name</th>
                            <th>Rows</th>
                            <th>Size (MB)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($databaseStats['tables'] as $table)
                        <tr>
                            <td><code>{{ $table['name'] }}</code></td>
                            <td>{{ $table['rows'] }}</td>
                            <td>{{ $table['size'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Error Logs -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Recent Error Logs</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="loadErrorLogs()">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
            </button>
        </div>
        <div class="card-body">
            <div id="errorLogsContainer">
                <p class="text-muted">Click refresh to load error logs...</p>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }
    .border-left-success {
        border-left: 4px solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 4px solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>

@push('scripts')
<script>
    function refreshAll() {
        location.reload();
    }

    function checkServices() {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Checking...';

        fetch('{{ route("admin.system.check-services") }}', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update table with new data
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to check services');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    function loadErrorLogs() {
        const container = document.getElementById('errorLogsContainer');
        container.innerHTML = '<p class="text-muted">Loading error logs...</p>';

        fetch('{{ route("admin.system.error-logs") }}?lines=50', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.logs.length === 0) {
                container.innerHTML = '<p class="text-muted">No error logs found</p>';
                return;
            }

            let html = '<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">';
            html += '<table class="table table-sm table-bordered">';
            html += '<thead class="table-light sticky-top"><tr><th>Time</th><th>Level</th><th>Message</th></tr></thead>';
            html += '<tbody>';

            data.logs.forEach(log => {
                const levelClass = log.level === 'ERROR' ? 'danger' : (log.level === 'WARNING' ? 'warning' : 'info');
                html += `<tr>
                    <td><small>${log.timestamp}</small></td>
                    <td><span class="badge bg-${levelClass}">${log.level}</span></td>
                    <td><small>${log.message}</small></td>
                </tr>`;
            });

            html += '</tbody></table></div>';
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<p class="text-danger">Failed to load error logs</p>';
        });
    }
</script>
@endpush
@endsection
