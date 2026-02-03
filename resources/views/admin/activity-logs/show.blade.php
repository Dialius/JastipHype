@extends('admin.layouts.app')

@section('title', 'Activity Log Detail')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Activity Log Detail</h1>
            <p class="text-muted mb-0">View detailed information about this activity</p>
        </div>
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="row">
        <!-- Activity Information -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activity Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Action:</th>
                            <td>
                                @php
                                    $actionColors = [
                                        'create' => 'success',
                                        'update' => 'info',
                                        'delete' => 'danger',
                                        'status_change' => 'warning',
                                        'login' => 'primary',
                                        'logout' => 'secondary',
                                    ];
                                    $color = $actionColors[$log->action] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Module:</th>
                            <td><span class="badge bg-light text-dark">{{ ucfirst($log->module) }}</span></td>
                        </tr>
                        <tr>
                            <th>Entity ID:</th>
                            <td>
                                @if($log->entity_id)
                                    <code>#{{ $log->entity_id }}</code>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Timestamp:</th>
                            <td>
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                                <br>
                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">User:</th>
                            <td>
                                @if($log->user)
                                    <strong>{{ $log->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $log->user->email }}</small>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>IP Address:</th>
                            <td><code>{{ $log->ip_address }}</code></td>
                        </tr>
                        <tr>
                            <th>User Agent:</th>
                            <td>
                                <small>{{ $log->user_agent }}</small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Changes Comparison -->
    @if($log->old_values || $log->new_values)
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Changes</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @if($log->old_values)
                <div class="col-md-6">
                    <h6 class="text-danger">Old Values</h6>
                    <div class="bg-light p-3 rounded">
                        <pre class="mb-0"><code>{{ json_encode(json_decode($log->old_values), JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
                @endif

                @if($log->new_values)
                <div class="col-md-6">
                    <h6 class="text-success">New Values</h6>
                    <div class="bg-light p-3 rounded">
                        <pre class="mb-0"><code>{{ json_encode(json_decode($log->new_values), JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
                @endif
            </div>

            @if($log->old_values && $log->new_values)
            <hr class="my-4">
            <h6 class="mb-3">Detailed Comparison</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Field</th>
                            <th class="text-danger">Old Value</th>
                            <th class="text-success">New Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $oldData = json_decode($log->old_values, true) ?? [];
                            $newData = json_decode($log->new_values, true) ?? [];
                            $allKeys = array_unique(array_merge(array_keys($oldData), array_keys($newData)));
                        @endphp
                        @foreach($allKeys as $key)
                        <tr>
                            <td><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong></td>
                            <td class="text-danger">
                                @if(isset($oldData[$key]))
                                    @if(is_array($oldData[$key]))
                                        <code>{{ json_encode($oldData[$key]) }}</code>
                                    @else
                                        {{ $oldData[$key] }}
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-success">
                                @if(isset($newData[$key]))
                                    @if(is_array($newData[$key]))
                                        <code>{{ json_encode($newData[$key]) }}</code>
                                    @else
                                        {{ $newData[$key] }}
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

<style>
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    pre {
        background: transparent;
        border: none;
        padding: 0;
        margin: 0;
        font-size: 0.875rem;
    }
</style>
@endsection
