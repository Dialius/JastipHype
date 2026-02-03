{{--
    Admin Data Table Component
    
    Usage:
    <x-admin.data-table>
        <x-slot name="header">
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
        </x-slot>
        
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><x-admin.status-badge status="success" label="Active" /></td>
                <td>...</td>
            </tr>
        @endforeach
    </x-admin.data-table>
--}}

@props([
    'striped' => false,
    'hover' => true,
    'responsive' => true,
])

<div @class([
    'overflow-x-auto rounded-lg border border-gray-200 dark:border-strokedark' => $responsive,
])>
    <table {{ $attributes->merge(['class' => 'w-full table-auto']) }}>
        @if(isset($header))
            <thead>
                <tr class="bg-gray-50 dark:bg-meta-4 border-b border-gray-200 dark:border-strokedark">
                    {{ $header }}
                </tr>
            </thead>
        @endif
        
        <tbody @class([
            '[&>tr:nth-child(even)]:bg-gray-50 dark:[&>tr:nth-child(even)]:bg-boxdark-2' => $striped,
            '[&>tr]:hover:bg-gray-50 dark:[&>tr]:hover:bg-boxdark-2' => $hover,
        ])>
            {{ $slot }}
        </tbody>
    </table>
</div>

@once
@push('styles')
<style>
    /* Data Table Styles */
    .data-table-wrapper table th {
        @apply px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-bodydark;
    }
    
    .data-table-wrapper table td {
        @apply px-4 py-4 text-sm text-gray-700 dark:text-bodydark border-b border-gray-100 dark:border-strokedark;
    }
    
    .data-table-wrapper table tbody tr:last-child td {
        @apply border-b-0;
    }
</style>
@endpush
@endonce
