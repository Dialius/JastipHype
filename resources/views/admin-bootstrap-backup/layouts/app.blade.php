<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    
    <!-- Toastify CSS for notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
    <!-- Custom Admin Styles -->
    <style>
        :root {
            --sidebar-width: 240px;
            --navbar-height: 60px;
            --primary-color: #2196F3;
            --sidebar-bg: #191C24;
            --body-bg: #f4f5f7;
            --card-bg: #ffffff;
            --border-color: #e3e6f0;
            --text-primary: #212529;
            --text-secondary: #858796;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 0.875rem;
            background: var(--body-bg);
            color: var(--text-primary);
        }
        
        /* Sidebar - Hide Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
        
        .sidebar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #fff;
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-brand {
            padding: 1.5rem 1.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            color: #fff;
            flex-shrink: 0;
        }
        
        .sidebar-brand i {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-right: 0.75rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .nav-item {
            margin: 0.25rem 0.75rem;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.6);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            font-weight: 400;
            font-size: 0.875rem;
            text-decoration: none;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
            text-decoration: none;
        }
        
        .nav-link.active {
            background: var(--primary-color);
            color: #fff;
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1rem;
        }
        
        .nav-divider {
            padding: 1.5rem 1rem 0.5rem 1rem;
            margin: 0.5rem 0;
        }
        
        .nav-divider span {
            color: rgba(255,255,255,0.4);
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .sidebar-footer {
            position: sticky;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 1rem;
            background: var(--sidebar-bg);
            border-top: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
            z-index: 10;
        }
        
        .sidebar-footer .btn-outline-light {
            border-color: rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }
        
        .sidebar-footer .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.3);
            color: #fff;
            text-decoration: none;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: var(--body-bg);
        }
        
        /* Navbar */
        .admin-navbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0 1.5rem;
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        /* Content Area */
        .content-wrapper {
            padding: 1.5rem;
        }
        
        /* Cards */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
            background: var(--card-bg);
        }
        
        .card-header {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
            font-weight: 600;
            font-size: 0.9375rem;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Stat Cards */
        .stat-card {
            border-radius: 0.5rem;
            padding: 1.5rem;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0.75rem 0 0.25rem;
        }
        
        .stat-label {
            color: var(--text-secondary);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-info {
            font-size: 0.8125rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border-color);
        }
        
        /* Badges */
        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
            border-radius: 0.25rem;
            font-size: 0.75rem;
        }
        
        /* Tables */
        .table {
            margin-bottom: 0;
            font-size: 0.875rem;
        }
        
        .table thead th {
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 0.75rem;
            background: #f8f9fc;
        }
        
        .table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }
        
        .table tbody tr:hover {
            background: #f8f9fc;
        }
        
        /* Buttons */
        .btn {
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: #1976D2;
            border-color: #1976D2;
        }
        
        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-secondary);
        }
        
        .btn-outline-secondary:hover {
            background: #f8f9fc;
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        /* Progress Bar */
        .progress {
            height: 6px;
            border-radius: 0.25rem;
            background: var(--border-color);
        }
        
        .progress-bar {
            border-radius: 0.25rem;
        }
        
        /* Page Title */
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0;
        }
        
        /* Code */
        code {
            background: #f8f9fc;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            color: var(--text-primary);
        }
        
        /* Loading Spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .spinner-overlay .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3rem;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .empty-state-icon {
            font-size: 4rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }
        
        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .empty-state-text {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }
        
        /* Skeleton Loader */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
        }
        
        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }
        
        .skeleton-text {
            height: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
        }
        
        .skeleton-title {
            height: 1.5rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            width: 60%;
        }
        
        .skeleton-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
            
            /* Mobile-friendly tables */
            .table-responsive {
                font-size: 0.8125rem;
            }
            
            /* Stack buttons on mobile */
            .btn-group {
                flex-direction: column;
            }
            
            .btn-group .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            /* Larger touch targets */
            .btn {
                min-height: 44px;
            }
            
            /* Hide some columns on mobile */
            .d-none-mobile {
                display: none !important;
            }
            
            /* Card spacing */
            .card {
                margin-bottom: 1rem;
            }
            
            /* Stat cards full width */
            .stat-card {
                margin-bottom: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            /* Even smaller screens */
            .page-title {
                font-size: 1.25rem;
            }
            
            .stat-value {
                font-size: 1.5rem;
            }
            
            /* Stack form elements */
            .row.g-3 > * {
                margin-bottom: 0.75rem;
            }
        }
        
        /* ===== GLOBAL FIXES ===== */
        
        /* Fix Pagination Arrow Size - AGGRESSIVE FIX */
        .pagination .page-link {
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
            padding: 0.375rem 0.75rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        /* Hide ALL SVG in pagination and use text instead */
        .pagination .page-link svg {
            display: none !important;
        }
        
        /* Add text arrows instead of SVG */
        .pagination .page-item:first-child .page-link::before {
            content: '‹' !important;
            font-size: 1.25rem !important;
            line-height: 1 !important;
        }
        
        .pagination .page-item:last-child .page-link::before {
            content: '›' !important;
            font-size: 1.25rem !important;
            line-height: 1 !important;
        }
        
        /* Remove any icon content */
        .pagination .page-link i,
        .pagination .page-link::after {
            display: none !important;
        }
        
        /* Fix any remaining large elements */
        .pagination * {
            max-width: 3rem !important;
            max-height: 3rem !important;
        }
        
        /* Fix Table Overflow */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
        }
        
        .card-body {
            overflow: visible;
        }
        
        /* Fix Card Footer */
        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid var(--border-color);
            padding: 1rem;
        }
        
        /* Ensure Content Doesn't Overflow */
        .main-content {
            overflow-x: hidden;
        }
        
        /* Fix Status Pills - Make them visible */
        .nav-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .nav-pills .nav-link {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background-color: #f8f9fa;
            color: #495057;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid #dee2e6;
        }
        
        .nav-pills .nav-link:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary-color, #2196F3);
            color: white;
            border-color: var(--primary-color, #2196F3);
        }
        
        .nav-pills .badge {
            margin-left: 0.5rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    @include('admin.layouts.sidebar')
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        @include('admin.layouts.navbar')
        
        <!-- Content -->
        <div class="content-wrapper">
            <!-- Main Content -->
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    
    <!-- Toastify JS for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <!-- Custom Admin Scripts -->
    <script>
        // Toast Notification Helper
        window.showToast = function(message, type = 'success') {
            const backgroundColor = {
                'success': 'linear-gradient(to right, #00b09b, #96c93d)',
                'error': 'linear-gradient(to right, #ff5f6d, #ffc371)',
                'warning': 'linear-gradient(to right, #f7971e, #ffd200)',
                'info': 'linear-gradient(to right, #2196F3, #64B5F6)'
            };
            
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: backgroundColor[type] || backgroundColor['info']
                }
            }).showToast();
        };
        
        // Form Validation Helper
        window.validateForm = function(formElement) {
            let isValid = true;
            const inputs = formElement.querySelectorAll('input[required], select[required], textarea[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    input.classList.remove('is-valid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }
            });
            
            return isValid;
        };
        
        // Loading Button Helper
        window.setButtonLoading = function(button, loading = true) {
            if (loading) {
                button.disabled = true;
                button.dataset.originalText = button.innerHTML;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
            } else {
                button.disabled = false;
                button.innerHTML = button.dataset.originalText || button.innerHTML;
            }
        };
        
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Prevent sidebar from scrolling to top when clicking links
            const sidebarNav = document.querySelector('.sidebar-nav');
            const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Store current scroll position
                    if (sidebarNav) {
                        const scrollPos = sidebarNav.scrollTop;
                        // Restore scroll position after a brief delay
                        setTimeout(() => {
                            sidebarNav.scrollTop = scrollPos;
                        }, 0);
                    }
                });
            });
            
            // Initialize DataTables for all tables with class 'datatable'
            if ($.fn.DataTable) {
                $('.datatable').each(function() {
                    const table = $(this);
                    const hasExport = table.data('export') !== false;
                    
                    const config = {
                        responsive: true,
                        pageLength: 25,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search...",
                            lengthMenu: "Show _MENU_ entries",
                            info: "Showing _START_ to _END_ of _TOTAL_ entries",
                            infoEmpty: "Showing 0 to 0 of 0 entries",
                            infoFiltered: "(filtered from _MAX_ total entries)",
                            paginate: {
                                first: "First",
                                last: "Last",
                                next: "Next",
                                previous: "Previous"
                            }
                        },
                        dom: hasExport ? 
                            "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                            "<'row'<'col-sm-12 col-md-6'B>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" :
                            "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                        order: [[0, 'desc']]
                    };
                    
                    if (hasExport) {
                        config.buttons = [
                            {
                                extend: 'copy',
                                className: 'btn btn-sm btn-secondary',
                                text: '<i class="bi bi-clipboard"></i> Copy'
                            },
                            {
                                extend: 'csv',
                                className: 'btn btn-sm btn-secondary',
                                text: '<i class="bi bi-filetype-csv"></i> CSV'
                            },
                            {
                                extend: 'excel',
                                className: 'btn btn-sm btn-secondary',
                                text: '<i class="bi bi-file-earmark-excel"></i> Excel'
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-sm btn-secondary',
                                text: '<i class="bi bi-file-earmark-pdf"></i> PDF'
                            },
                            {
                                extend: 'print',
                                className: 'btn btn-sm btn-secondary',
                                text: '<i class="bi bi-printer"></i> Print'
                            }
                        ];
                    }
                    
                    table.DataTable(config);
                });
            }
            
            // Real-time form validation
            document.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
                input.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid') && this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            });
            
            // Form submit with validation
            document.querySelectorAll('form[data-validate="true"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!validateForm(this)) {
                        e.preventDefault();
                        showToast('Please fill in all required fields', 'error');
                        return false;
                    }
                    
                    const submitButton = this.querySelector('button[type="submit"]');
                    if (submitButton) {
                        setButtonLoading(submitButton, true);
                    }
                });
            });
            
            // Show toast for flash messages
            @if(session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif
            
            @if(session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif
            
            @if(session('warning'))
                showToast('{{ session('warning') }}', 'warning');
            @endif
            
            @if(session('info'))
                showToast('{{ session('info') }}', 'info');
            @endif
            
            // Show validation errors as toast
            @if($errors->any())
                @foreach($errors->all() as $error)
                    showToast('{{ $error }}', 'error');
                @endforeach
            @endif
        });
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    @stack('scripts')
</body>
</html>
