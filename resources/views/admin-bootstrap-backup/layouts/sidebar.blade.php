<div class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <i class="bi bi-star-fill"></i>
        <span>JastipHype</span>
    </div>
    
    <!-- Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Divider -->
            <li class="nav-divider">
                <span>CATALOG</span>
            </li>
            
            <!-- Products -->
            <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Products</span>
                </a>
            </li>
            
            <!-- Brands -->
            <li class="nav-item">
                <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <i class="bi bi-award"></i>
                    <span>Brands</span>
                </a>
            </li>
            
            <!-- Categories -->
            <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-folder"></i>
                    <span>Categories</span>
                </a>
            </li>
            
            <!-- Divider -->
            <li class="nav-divider">
                <span>SALES</span>
            </li>
            
            <!-- Orders -->
            <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i>
                    <span>Orders</span>
                </a>
            </li>
            
            <!-- Customers -->
            <li class="nav-item">
                <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Customers</span>
                </a>
            </li>
            
            <!-- Reviews -->
            <li class="nav-item">
                <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="bi bi-star"></i>
                    <span>Reviews</span>
                </a>
            </li>
            
            <!-- Payments -->
            <li class="nav-item">
                <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>
            
            <!-- Divider -->
            <li class="nav-divider">
                <span>MARKETING</span>
            </li>
            
            <!-- Banners -->
            <li class="nav-item">
                <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') && !request()->routeIs('admin.categories.images.*') ? 'active' : '' }}">
                    <i class="bi bi-image"></i>
                    <span>Banners</span>
                </a>
            </li>
            
            <!-- Category Images -->
            <li class="nav-item">
                <a href="{{ route('admin.categories.images.edit') }}" class="nav-link {{ request()->routeIs('admin.categories.images.*') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>Category Images</span>
                </a>
            </li>
            
            <!-- Discounts -->
            <li class="nav-item">
                <a href="{{ route('admin.discounts.index') }}" class="nav-link {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}">
                    <i class="bi bi-tag"></i>
                    <span>Discounts</span>
                </a>
            </li>
            
            <!-- Bulk Messaging -->
            <li class="nav-item">
                <a href="{{ route('admin.messages.bulk') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots"></i>
                    <span>Bulk Messaging</span>
                </a>
            </li>
            
            <!-- Divider -->
            <li class="nav-divider">
                <span>ANALYTICS</span>
            </li>
            
            <!-- Analytics -->
            <li class="nav-item">
                <a href="{{ route('admin.analytics.revenue') }}" class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i>
                    <span>Reports</span>
                </a>
            </li>
            
            <!-- Visitors -->
            <li class="nav-item">
                <a href="{{ route('admin.visitors.index') }}" class="nav-link {{ request()->routeIs('admin.visitors.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Visitors</span>
                </a>
            </li>
            
            <!-- Activity Logs -->
            <li class="nav-item">
                <a href="{{ route('admin.activity-logs.index') }}" class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Activity Logs</span>
                </a>
            </li>
            
            <!-- Divider -->
            <li class="nav-divider">
                <span>SETTINGS</span>
            </li>
            
            <!-- Settings -->
            <li class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
            
            <!-- Shipping -->
            <li class="nav-item">
                <a href="{{ route('admin.shipping.index') }}" class="nav-link {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i>
                    <span>Shipping</span>
                </a>
            </li>
            
            <!-- Notifications -->
            <li class="nav-item">
                <a href="{{ route('admin.notifications.templates') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i>
                    <span>Notifications</span>
                </a>
            </li>
            
            <!-- Export/Import -->
            <li class="nav-item">
                <a href="{{ route('admin.export-import.index') }}" class="nav-link {{ request()->routeIs('admin.export-import.*', 'admin.export.*', 'admin.import.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-up"></i>
                    <span>Export/Import</span>
                </a>
            </li>
            
            <!-- System Monitor -->
            <li class="nav-item">
                <a href="{{ route('admin.system.index') }}" class="nav-link {{ request()->routeIs('admin.system.*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer"></i>
                    <span>System Monitor</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light w-100" target="_blank">
            <i class="bi bi-globe me-2"></i>View Site
        </a>
    </div>
</div>
