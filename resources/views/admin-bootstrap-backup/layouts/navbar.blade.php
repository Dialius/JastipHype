<nav class="admin-navbar">
    <div class="d-flex align-items-center">
        <!-- Mobile Sidebar Toggle -->
        <button class="btn btn-link d-md-none me-3" id="sidebarToggle" type="button">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        <!-- Page Title (optional, can be overridden) -->
        <div class="d-none d-md-block">
            @yield('navbar-title')
        </div>
    </div>
    
    <div class="d-flex align-items-center">
        <!-- Notifications -->
        <div class="dropdown me-3">
            <button class="btn btn-link position-relative p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell fs-5"></i>
                @if(isset($adminNotificationCount) && $adminNotificationCount > 0)
                <span class="position-absolute badge rounded-pill bg-danger" style="top: 0; right: 0; font-size: 0.65rem; padding: 0.25em 0.5em;">
                    {{ $adminNotificationCount > 99 ? '99+' : $adminNotificationCount }}
                </span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="min-width: 350px; max-height: 500px;">
                <li class="dropdown-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Notifications</span>
                    @if(isset($adminNotificationCount) && $adminNotificationCount > 0)
                    <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $adminNotificationCount }}</span>
                    @endif
                </li>
                <li><hr class="dropdown-divider"></li>
                
                @if(isset($adminNotifications) && count($adminNotifications) > 0)
                    @foreach($adminNotifications as $notification)
                    <li>
                        <a class="dropdown-item py-3 {{ !$notification['read'] ? 'bg-light' : '' }}" href="{{ $notification['url'] }}">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-{{ $notification['color'] }} bg-opacity-10 p-2">
                                        <i class="bi {{ $notification['icon'] }} text-{{ $notification['color'] }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold small">{{ $notification['title'] }}</div>
                                    <div class="text-muted small">{{ $notification['message'] }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-clock"></i> {{ $notification['time']->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    @if(!$loop->last)
                    <li><hr class="dropdown-divider my-0"></li>
                    @endif
                    @endforeach
                    
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-center small text-primary" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-arrow-right-circle"></i> View All Notifications
                        </a>
                    </li>
                @else
                    <li>
                        <div class="dropdown-item text-muted text-center py-4">
                            <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                            <div>No new notifications</div>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
        
        <!-- User Profile -->
        <div class="dropdown">
            <button class="btn btn-link d-flex align-items-center text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <span class="ms-2 d-none d-md-inline">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down ms-2 d-none d-md-inline"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                    <i class="bi bi-person me-2"></i>Profile
                </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                    <i class="bi bi-gear me-2"></i>Settings
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.notification-dropdown {
    max-height: 500px;
    overflow-y: auto;
    /* Hide scrollbar for Chrome, Safari and Opera */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none;  /* IE and Edge */
}

.notification-dropdown::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

.notification-dropdown .dropdown-item {
    white-space: normal;
    transition: background-color 0.2s;
}

.notification-dropdown .dropdown-item:hover {
    background-color: #f8f9fa !important;
}

.notification-dropdown .dropdown-item.bg-light {
    background-color: #e7f3ff !important;
}

.notification-dropdown .dropdown-item.bg-light:hover {
    background-color: #d0e7ff !important;
}
</style>
