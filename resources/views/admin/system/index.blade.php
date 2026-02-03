@extends('admin.layouts.app')

@section('title', 'System Monitor')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Monitor</h1>
            <p class="mt-1 text-sm text-gray-500">Monitor system health and service status</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button type="button" onclick="refreshAll()" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Refresh All
            </button>
        </div>
    </div>
</div>

<!-- System Health Cards -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
    <!-- PHP Version -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
        <div class="border-l-4 border-blue-600 p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">PHP Version</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $systemHealth['php_version'] }}</p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Laravel Version -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
        <div class="border-l-4 border-green-600 p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold uppercase tracking-wide text-green-600">Laravel Version</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $systemHealth['laravel_version'] }}</p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Time -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
        <div class="border-l-4 border-cyan-600 p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold uppercase tracking-wide text-cyan-600">Server Time</p>
                    <p class="mt-2 text-xl font-bold text-gray-900">{{ $systemHealth['server_time'] }}</p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Memory Usage -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
        <div class="border-l-4 border-yellow-600 p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold uppercase tracking-wide text-yellow-600">Memory Usage</p>
                    <p class="mt-2 text-xl font-bold text-gray-900">{{ $systemHealth['memory_usage']['current'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">Peak: {{ $systemHealth['memory_usage']['peak'] }}</p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Disk Usage -->
<div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200 mb-6">
    <div class="border-b border-gray-200 bg-white px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-900">Disk Usage</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="relative pt-1">
                    <div class="overflow-hidden h-8 text-xs flex rounded-lg bg-gray-200">
                        <div style="width: {{ $systemHealth['disk_usage']['percentage'] }}%" 
                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $systemHealth['disk_usage']['percentage'] > 80 ? 'bg-red-600' : ($systemHealth['disk_usage']['percentage'] > 60 ? 'bg-yellow-500' : 'bg-green-600') }}">
                            <span class="font-semibold">{{ $systemHealth['disk_usage']['percentage'] }}%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1">
                <div class="text-sm">
                    <p class="mb-1"><span class="font-semibold">Used:</span> {{ $systemHealth['disk_usage']['used'] }} / {{ $systemHealth['disk_usage']['total'] }}</p>
                    <p><span class="font-semibold">Free:</span> {{ $systemHealth['disk_usage']['free'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Service Status -->
<div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200 mb-6">
    <div class="border-b border-gray-200 bg-white px-6 py-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Service Status</h2>
            <button type="button" onclick="checkServices()" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Refresh
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Service</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                        <svg class="inline h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                        </svg>
                        Database
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $serviceStatus['database']['status'] === 'online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($serviceStatus['database']['status']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $serviceStatus['database']['message'] }}</td>
                </tr>
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                        <svg class="inline h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                        Cache
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $serviceStatus['cache']['status'] === 'online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($serviceStatus['cache']['status']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $serviceStatus['cache']['message'] }}
                        @if(isset($serviceStatus['cache']['driver']))
                            <br><span class="text-xs text-gray-400">Driver: {{ $serviceStatus['cache']['driver'] }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                        <svg class="inline h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        Mail (SMTP)
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $serviceStatus['mail']['status'] === 'configured' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($serviceStatus['mail']['status']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $serviceStatus['mail']['message'] }}
                        @if(isset($serviceStatus['mail']['host']))
                            <br><span class="text-xs text-gray-400">Host: {{ $serviceStatus['mail']['host'] }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                        <svg class="inline h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                        Midtrans
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $serviceStatus['midtrans']['status'] === 'configured' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $serviceStatus['midtrans']['status'])) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $serviceStatus['midtrans']['message'] }}
                        @if(isset($serviceStatus['midtrans']['environment']))
                            <br><span class="text-xs text-gray-400">Environment: {{ $serviceStatus['midtrans']['environment'] }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                        <svg class="inline h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                        RajaOngkir
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $serviceStatus['rajaongkir']['status'] === 'configured' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $serviceStatus['rajaongkir']['status'])) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $serviceStatus['rajaongkir']['message'] }}
                        @if(isset($serviceStatus['rajaongkir']['type']))
                            <br><span class="text-xs text-gray-400">Type: {{ $serviceStatus['rajaongkir']['type'] }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Database Statistics -->
<div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200 mb-6">
    <div class="border-b border-gray-200 bg-white px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-900">Database Statistics</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-500">Total Tables</p>
                <p class="text-2xl font-bold text-gray-900">{{ $databaseStats['table_count'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Size</p>
                <p class="text-2xl font-bold text-gray-900">{{ $databaseStats['total_size'] }} MB</p>
            </div>
        </div>
        <div class="overflow-x-auto" style="max-height: 400px;">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Table Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Rows</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Size (MB)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($databaseStats['tables'] as $table)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-mono text-gray-900">{{ $table['name'] }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $table['rows'] }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $table['size'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Error Logs -->
<div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
    <div class="border-b border-gray-200 bg-white px-6 py-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Recent Error Logs</h2>
            <button type="button" onclick="loadErrorLogs()" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Refresh
            </button>
        </div>
    </div>
    <div class="p-6">
        <div id="errorLogsContainer">
            <p class="text-sm text-gray-500">Click refresh to load error logs...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function refreshAll() {
        location.reload();
    }

    function checkServices() {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="inline h-4 w-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Checking...';

        fetch('{{ route("admin.system.check-services") }}', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
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
        container.innerHTML = '<p class="text-sm text-gray-500">Loading error logs...</p>';

        fetch('{{ route("admin.system.error-logs") }}?lines=50', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.logs.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500">No error logs found</p>';
                return;
            }

            let html = '<div class="overflow-x-auto" style="max-height: 400px;">';
            html += '<table class="min-w-full divide-y divide-gray-200">';
            html += '<thead class="bg-gray-50 sticky top-0"><tr><th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Time</th><th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Level</th><th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Message</th></tr></thead>';
            html += '<tbody class="divide-y divide-gray-200 bg-white">';

            data.logs.forEach(log => {
                const levelClass = log.level === 'ERROR' ? 'bg-red-100 text-red-800' : (log.level === 'WARNING' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800');
                html += `<tr>
                    <td class="whitespace-nowrap px-6 py-4 text-xs text-gray-500">${log.timestamp}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm"><span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold ${levelClass}">${log.level}</span></td>
                    <td class="px-6 py-4 text-xs text-gray-500">${log.message}</td>
                </tr>`;
            });

            html += '</tbody></table></div>';
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<p class="text-sm text-red-600">Failed to load error logs</p>';
        });
    }
</script>
@endpush
@endsection
