# Implementation Plan: Admin Panel JastipHype

## Overview

Admin Panel akan diimplementasikan menggunakan Laravel framework dengan Repository-Service Pattern. Implementasi akan menggunakan TailAdmin Laravel template untuk UI, dengan fokus pada modular architecture yang memisahkan concerns antara presentation, business logic, dan data access.

**Technology Stack:**
- PHP 8.2+ dengan Laravel 12.x (CURRENT)
- Bootstrap 5.3 (via CDN - no build conflicts)
- Vanilla JavaScript / jQuery untuk interactivity
- MySQL/MariaDB untuk database
- Redis untuk caching dan online users tracking
- Chart.js untuk data visualization
- DataTables.js untuk tables
- Pest PHP untuk testing (ALREADY INSTALLED)

**Implementation Approach:**
- Incremental development dengan testing di setiap tahap
- Repository-Service Pattern untuk clean architecture
- Model Observers untuk automatic operations
- Queue system untuk background jobs (email, notifications)

**Current State Analysis:**
- ✅ Laravel 12.x installed with Pest PHP
- ✅ Basic models exist: User, Product, Order, Brand, Category, Review, Payment
- ✅ Frontend controllers exist for customer-facing features
- ✅ Midtrans and RajaOngkir services already implemented
- ✅ Customer website using Tailwind CSS v3 (stable, tidak akan diubah)
- ✅ Admin routes structure created (routes/admin.php)
- ✅ AdminMiddleware created and registered
- ✅ Admin layouts created with Bootstrap 5.3 (via CDN, no conflicts)
- ✅ Dashboard skeleton view created
- ✅ is_admin field added to users table
- ❌ No admin repositories or services yet
- ❌ No admin views for CRUD operations (will use Bootstrap 5)
- ❌ Missing database tables: banners, discounts, activity_logs, settings, visitor_logs, customer_messages
- ❌ No soft deletes on Product and Review models
- ❌ Brand model missing fields: status, display_order, logo_path, soft deletes


## Tasks

- [ ] 1. Setup dan Infrastructure
  - [x] 1.1 Install TailAdmin Laravel template
    - Clone/install TailAdmin Laravel from GitHub
    - Configure Tailwind CSS and Alpine.js
    - Setup base assets (CSS, JS)
    - _Requirements: 11.1_
  
  - [x] 1.2 Create admin route structure
    - Create routes/admin.php file
    - Configure route prefix `/admin` and middleware group
    - Register admin routes in bootstrap/app.php or RouteServiceProvider
    - _Requirements: 10.1, 10.2_
  
  - [x] 1.3 Create AdminMiddleware
    - Create app/Http/Middleware/AdminMiddleware.php
    - Check user role/is_admin field
    - Redirect non-admin users to unauthorized page
    - Register middleware in app/Http/Kernel.php
    - _Requirements: 10.1, 10.2_
  
  - [x] 1.4 Create base admin layout (Bootstrap 5)
    - Create resources/views/admin/layouts/app.blade.php (Bootstrap 5.3 via CDN)
    - Create sidebar component with navigation menu (resources/views/admin/layouts/sidebar.blade.php)
    - Create navbar component with user profile dropdown (resources/views/admin/layouts/navbar.blade.php)
    - Setup breadcrumb navigation (integrated in app.blade.php)
    - Create dashboard view (resources/views/admin/dashboard/index.blade.php)
    - Update DashboardController to return view
    - _Requirements: 11.1, 11.2, 11.3_
    - _Status: ✅ COMPLETED - Bootstrap 5 layout created, no build conflicts_
  
  - [x] 1.5 Configure Redis and Queue
    - Verify Redis configuration in .env ✅
    - Configure queue driver (database) ✅
    - Queue jobs table migration exists ✅
    - Test queue connection ✅
    - Created TestQueueJob and verified execution ✅
    - _Requirements: 1.4, 18.1, 18.2_
    - _Status: ✅ COMPLETED - Database queue working, Redis optional for production_

- [x] 2. Database Schema dan Migrations
  - [x] 2.1 Update existing models with missing fields
    - Add migration to add role/is_admin field to users table
    - Add migration to add soft deletes to products table
    - Add migration to add soft deletes to reviews table
    - Update Brand model and migration: add status, display_order, logo_path fields, add soft deletes
    - Update Product model to use SoftDeletes trait
    - Update Review model to use SoftDeletes trait
    - _Requirements: 10.1, 2.5, 6.6, 4.5.1, 4.5.6_

  - [x] 2.2 Create migrations untuk tables baru
    - Create banners table migration (id, title, image_path, link_url, type, order, status, start_date, end_date, timestamps)
    - Create discounts table migration (id, code, type, value, min_order_amount, max_uses, uses_count, uses_per_customer, start_date, end_date, status, applicable_to, applicable_ids, timestamps)
    - Create activity_logs table migration (id, user_id, action, module, entity_id, old_values, new_values, ip_address, user_agent, created_at)
    - Create settings table migration (id, group, key, value, type, timestamps)
    - Create visitor_logs table migration (id, ip_address, user_agent, page_url, user_id, session_id, visited_at, created_at)
    - Create customer_messages table migration (id, customer_id, admin_id, message, type, status, parent_id, timestamps)
    - _Requirements: 12.1-12.10, 16.1-16.8, 13.1-13.3, 9.1-9.7, 18.1-18.8, 19.1-19.7_

  - [x] 2.3 Create Eloquent models untuk tables baru
    - Create Banner model with relationships and scopes
    - Create Discount model with relationships and scopes
    - Create ActivityLog model with relationships
    - Create Setting model with get/set helpers
    - Create VisitorLog model with unique visitor methods
    - Create CustomerMessage model with threading relationships
    - _Requirements: All data model requirements_

  - [x] 2.4 Create model factories untuk testing
    - Create BannerFactory
    - Create DiscountFactory
    - Create ActivityLogFactory
    - Create SettingFactory
    - Create VisitorLogFactory
    - Create CustomerMessageFactory
    - Update existing factories (Product, Order, User) to include admin users
    - _Requirements: Testing requirements_

- [x] 3. Repository Layer Implementation
  - [x] 3.1 Create repository directory structure
    - Create app/Repositories/Contracts directory
    - Create app/Repositories/Eloquent directory
    - Create app/Providers/RepositoryServiceProvider.php
    - Register RepositoryServiceProvider in config/app.php
    - _Requirements: Architecture design_

  - [x] 3.2 Create repository interfaces
    - Create ProductRepositoryInterface
    - Create OrderRepositoryInterface
    - Create CustomerRepositoryInterface (UserRepositoryInterface)
    - Create BannerRepositoryInterface
    - Create DiscountRepositoryInterface
    - Create ReviewRepositoryInterface
    - Create BrandRepositoryInterface
    - Create PaymentRepositoryInterface
    - Create ActivityLogRepositoryInterface
    - Create SettingsRepositoryInterface
    - Create VisitorLogRepositoryInterface
    - Create CustomerMessageRepositoryInterface
    - _Requirements: Architecture design_

  - [x] 3.3 Implement Eloquent repositories
    - Implement ProductRepository with pagination, search, filter, low stock methods
    - Implement OrderRepository with status filters, date range, recent orders
    - Implement CustomerRepository with stats, online users, messaging
    - Implement BannerRepository with ordering and active banners
    - Implement DiscountRepository with applicability checks
    - Implement ReviewRepository with moderation methods
    - Implement BrandRepository with product count and revenue stats
    - Implement PaymentRepository with payment method distribution
    - Implement ActivityLogRepository with filtering
    - Implement SettingsRepository with group get/set
    - Implement VisitorLogRepository with unique visitor calculations
    - Implement CustomerMessageRepository with threading
    - Add query optimization (eager loading, indexing)
    - _Requirements: 2.1, 2.2, 3.1, 3.2, 4.1, 4.2, 6.1, 6.2, 8.1, 8.2_

  - [x] 3.4 Bind repositories in RepositoryServiceProvider
    - Bind all repository interfaces to their implementations
    - Test repository bindings work correctly
    - _Requirements: Architecture design_

  - [ ]* 3.5 Write property test untuk repository layer
    - **Property 4: Data Persistence Round-Trip**
    - Test create dan retrieve operations untuk semua entities
    - **Validates: Requirements 2.3, 5.1, 12.6, 16.1**

- [x] 4. Service Layer Implementation
  - [x] 4.1 Create service classes with business logic
    - Create app/Services/ProductService.php (CRUD, stock management, image handling)
    - Create app/Services/OrderService.php (status management, cancellation, timeline)
    - Create app/Services/CustomerService.php (suspension, analytics, messaging)
    - Create app/Services/BannerService.php (image handling, ordering, scheduling)
    - Create app/Services/DiscountService.php (validation, applicability checks)
    - Create app/Services/AnalyticsService.php (revenue, product performance, calculations)
    - Create app/Services/OnlineUsersService.php (Redis tracking, TTL management)
    - Create app/Services/VisitorTrackingService.php (unique visitors, trends)
    - Create app/Services/BrandService.php (logo handling, statistics)
    - Create app/Services/MessageService.php (threading, notifications)
    - Create app/Services/ReviewService.php (moderation, admin responses)
    - Create app/Services/PaymentService.php (Midtrans sync, verification)
    - Create app/Services/NotificationService.php (email queueing, templates)
    - _Requirements: Architecture design_

  - [x] 4.2 Implement file upload handling
    - Implement image upload untuk products (multiple images, validation)
    - Implement image upload untuk banners dengan dimension validation (per type)
    - Implement logo upload untuk brands dengan dimension validation (200x200-1000x1000px)
    - Add image optimization dan thumbnail generation using Intervention Image
    - Create FileUploadService untuk reusable upload logic
    - _Requirements: 2.6, 12.3, 12.4, 4.5.2_

  - [ ]* 4.3 Write property tests untuk file validation
    - **Property 7: File Validation Rejection**
    - **Property 37: Brand Logo Validation**
    - Test dengan various file formats, sizes, dan dimensions
    - **Validates: Requirements 2.6, 12.3, 12.4, 4.5.2**

- [x] 5. Model Observers Implementation
  - [x] 5.1 Create observer classes
    - Create app/Observers/ProductObserver.php (stock status updates)
    - Create app/Observers/OrderObserver.php (email notifications, stock restoration)
    - Create app/Observers/ReviewObserver.php (rating updates, notifications)
    - Create app/Observers/BrandObserver.php (slug generation, deletion validation)
    - Create app/Observers/CustomerMessageObserver.php (email notifications, status updates)
    - Create app/Observers/VisitorLogObserver.php (cache updates, cleanup)
    - _Requirements: 2.7, 3.4, 3.5, 6.4_

  - [x] 5.2 Register observers
    - Register all observers in app/Providers/EventServiceProvider.php or AppServiceProvider
    - Test observer events fire correctly
    - _Requirements: 2.7, 3.4, 3.5, 6.4_

  - [ ]* 5.3 Write property tests untuk observers
    - **Property 8: Stock Status Synchronization**
    - **Property 9: Order Cancellation Stock Restoration**
    - **Property 28: Notification Email Queueing**
    - **Validates: Requirements 2.7, 3.5, 3.4**

- [x] 6. Dashboard Implementation
  - [x] 6.1 Create DashboardController
    - Create app/Http/Controllers/Admin/DashboardController.php
    - Implement index method with all dashboard metrics
    - Inject AnalyticsService, OnlineUsersService, VisitorTrackingService
    - Calculate total revenue (today, week, month, year)
    - Get order statistics with status breakdown
    - Get user statistics (total, active, new)
    - Get online users count from Redis
    - Get unique visitors (daily, monthly)
    - Get product statistics with low stock alerts
    - Get recent orders (last 10)
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9, 1.10_

  - [x] 6.2 Create dashboard view
    - Create resources/views/admin/dashboard/index.blade.php
    - Add metrics cards (revenue, orders, users, products, online users, visitors)
    - Add revenue chart using Chart.js (line chart with date range selector)
    - Add visitor trends chart (daily/monthly toggle)
    - Add recent orders table with status badges
    - Add low stock alerts section with product links
    - Add quick action buttons (Add Product, View Orders, etc.)
    - Make responsive for mobile/tablet
    - _Requirements: 1.1-1.10, 11.2_

  - [x] 6.3 Add dashboard route
    - Add route in routes/admin.php: GET /admin/dashboard
    - Set as default admin landing page
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 6.4 Write property tests untuk dashboard analytics
    - **Property 10: Revenue Calculation Accuracy**
    - **Property 11: Order Status Distribution**
    - **Property 12: Low Stock Detection**
    - **Property 13: Recent Orders Ordering**
    - **Property 34: Online Users Accuracy**
    - **Property 35: Visitor Count Uniqueness**
    - **Validates: Requirements 1.1, 1.2, 1.4, 1.5, 1.6, 1.7, 1.10**

- [ ] 7. Product Management Implementation
  - [x] 7.1 Create ProductController untuk admin
    - Create app/Http/Controllers/Admin/ProductController.php
    - Implement index method (list dengan pagination, search by name/SKU, filter by brand/category/status)
    - Implement create method (show form)
    - Implement store method (validate and save product with images)
    - Implement edit method (show form with existing data)
    - Implement update method (validate and update product)
    - Implement destroy method (soft delete)
    - Implement bulk actions (bulkActivate, bulkDeactivate, bulkDelete)
    - Inject ProductService and ProductRepository
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.8_

  - [x] 7.2 Create product views
    - Create resources/views/admin/products/index.blade.php (DataTables with search, filter, pagination)
    - Create resources/views/admin/products/create.blade.php (form with image upload, brand/category select)
    - Create resources/views/admin/products/edit.blade.php (form with existing data and image preview)
    - Add image upload preview with drag-and-drop
    - Add validation error messages display
    - Add success/error toast notifications
    - Add bulk action checkboxes and buttons
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 11.4, 11.6_

  - [x] 7.3 Add product routes
    - Add resource routes in routes/admin.php: admin.products.*
    - Add bulk action routes (POST /admin/products/bulk-activate, etc.)
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 7.4 Write property tests untuk product management
    - **Property 1: Pagination Consistency**
    - **Property 2: Filter Correctness**
    - **Property 3: Search Inclusivity**
    - **Property 5: Update Idempotence**
    - **Property 6: Soft Delete Preservation**
    - **Validates: Requirements 2.1, 2.2, 2.4, 2.5**

- [ ] 8. Checkpoint - Test Product Management
  - Ensure all product CRUD operations work correctly
  - Test image upload dan validation
  - Test search dan filter functionality
  - Ask user if questions arise

- [x] 9. Brand Management Implementation
  - [x] 9.1 Create BrandController untuk admin
    - Create app/Http/Controllers/Admin/BrandController.php
    - Implement index method (list dengan brand statistics: product count, revenue)
    - Implement create method (show form)
    - Implement store method (validate and save brand with logo upload)
    - Implement edit method (show form with existing data)
    - Implement update method (validate and update brand, handle logo replacement)
    - Implement destroy method (validate no products exist, then delete)
    - Implement updateOrder method (drag-and-drop ordering)
    - Inject BrandService and BrandRepository
    - _Requirements: 5.1, 5.2, 4.5.1, 4.5.2, 4.5.3, 4.5.4, 4.5.5, 4.5.6, 4.5.7_

  - [x] 9.2 Create brand views
    - Create resources/views/admin/brands/index.blade.php (cards or table with logo, stats)
    - Create resources/views/admin/brands/create.blade.php (form with logo upload)
    - Create resources/views/admin/brands/edit.blade.php (form with logo preview and replacement)
    - Add drag-and-drop ordering interface using SortableJS
    - Display product count and revenue statistics per brand
    - Add logo preview with dimension requirements display
    - _Requirements: 4.5.1, 4.5.5, 4.5.6_

  - [x] 9.3 Add brand routes
    - Add resource routes in routes/admin.php: admin.brands.*
    - Add POST /admin/brands/update-order route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 9.4 Write property tests untuk brand management
    - **Property 14: Referential Integrity Protection**
    - **Property 36: Brand Deletion Protection**
    - **Property 40: Brand Statistics Accuracy**
    - **Validates: Requirements 5.3, 4.5.4, 4.5.5**

- [x] 10. Category Management Implementation
  - [x] 10.1 Create CategoryController untuk admin
    - Create app/Http/Controllers/Admin/CategoryController.php
    - Implement index method (list dengan nested categories display)
    - Implement create method (show form with parent category selection)
    - Implement store method (validate and save category)
    - Implement edit method (show form with existing data)
    - Implement update method (validate and update category)
    - Implement destroy method (validate no products exist, then delete)
    - Inject CategoryService and CategoryRepository
    - _Requirements: 5.4, 5.5, 5.6, 5.7_

  - [x] 10.2 Create category views
    - Create resources/views/admin/categories/index.blade.php (tree view or nested list)
    - Create resources/views/admin/categories/create.blade.php (form with parent select)
    - Create resources/views/admin/categories/edit.blade.php (form with parent select)
    - Display product count per category
    - Add hierarchy visualization
    - _Requirements: 5.4, 5.5, 5.6, 5.7_

  - [x] 10.3 Add category routes
    - Add resource routes in routes/admin.php: admin.categories.*
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 10.4 Write property tests untuk category hierarchy
    - **Property 15: Category Hierarchy Consistency**
    - Test parent-child relationships dan circular reference prevention
    - **Validates: Requirements 5.7**

- [x] 11. Order Management Implementation
  - [x] 11.1 Create OrderController untuk admin
    - Create app/Http/Controllers/Admin/OrderController.php
    - Implement index method (list dengan filters: status, date range, payment method)
    - Implement show method (detail dengan order timeline, items, customer info, payment status)
    - Implement updateStatus method (change order status with email notification)
    - Implement cancel method (cancel order with stock restoration and reason)
    - Implement generateInvoice method (PDF generation using DomPDF or similar)
    - Implement export method (CSV/Excel export with date range filter)
    - Inject OrderService, PaymentService, NotificationService
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.8_

  - [x] 11.2 Create order views
    - Create resources/views/admin/orders/index.blade.php (DataTables with advanced filters)
    - Create resources/views/admin/orders/show.blade.php (detail with timeline, payment status, shipping tracking)
    - Add status update modal with confirmation and email notification toggle
    - Add cancellation modal with reason input
    - Display payment status from Midtrans (real-time sync button)
    - Display shipping tracking from RajaOngkir (if available)
    - Add invoice download button
    - Add export button with date range picker
    - _Requirements: 3.1, 3.2, 3.3, 3.6, 3.7_

  - [x] 11.3 Add order routes
    - Add resource routes in routes/admin.php: admin.orders.index, show
    - Add POST /admin/orders/{id}/update-status route
    - Add POST /admin/orders/{id}/cancel route
    - Add GET /admin/orders/{id}/invoice route
    - Add GET /admin/orders/export route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 11.4 Write property tests untuk order management
    - **Property 9: Order Cancellation Stock Restoration**
    - **Property 23: Export Data Completeness**
    - **Validates: Requirements 3.5, 3.8**

- [x] 12. Customer Management Implementation
  - [x] 12.1 Create CustomerController untuk admin
    - Create app/Http/Controllers/Admin/CustomerController.php
    - Implement index method (list dengan search by name/email, filter by status)
    - Implement show method (detail dengan order history, spending analytics, message history)
    - Implement update method (update customer data)
    - Implement suspend method (suspend account with reason)
    - Implement activate method (reactivate suspended account)
    - Implement export method (CSV/Excel export)
    - Inject CustomerService and CustomerRepository
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7_

  - [x] 12.2 Create MessageController untuk admin
    - Create app/Http/Controllers/Admin/MessageController.php
    - Implement sendMessage method (send message to customer with email notification)
    - Implement getMessages method (get message history with threading)
    - Implement bulkMessage method (send to multiple customers with segmentation)
    - Inject MessageService and NotificationService
    - _Requirements: 4.8, 4.9, 4.10, 19.1, 19.2, 19.4, 19.7_

  - [x] 12.3 Create customer views
    - Create resources/views/admin/customers/index.blade.php (DataTables with search and filter)
    - Create resources/views/admin/customers/show.blade.php (detail with tabs: profile, orders, analytics, messages)
    - Create messaging interface component (conversation view with reply form)
    - Create bulk messaging modal (with segmentation options: spending range, order count, last order date)
    - Add customer analytics charts (spending over time, order frequency)
    - Add suspend/activate buttons with confirmation modals
    - _Requirements: 4.1, 4.2, 4.3, 4.8, 4.9_

  - [x] 12.4 Add customer and message routes
    - Add resource routes in routes/admin.php: admin.customers.*
    - Add POST /admin/customers/{id}/suspend route
    - Add POST /admin/customers/{id}/activate route
    - Add GET /admin/customers/export route
    - Add POST /admin/messages/send route
    - Add GET /admin/messages/{customerId} route
    - Add POST /admin/messages/bulk-send route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 12.5 Write property tests untuk customer management
    - **Property 17: User Suspension Access Control**
    - **Property 18: User Statistics Accuracy**
    - **Property 38: Customer Message Delivery**
    - **Property 39: Message Threading Consistency**
    - **Validates: Requirements 4.5, 4.6, 4.7, 4.8, 19.1, 19.7**

- [x] 13. Checkpoint - Test Order dan Customer Management
  - Test order status updates dan email notifications
  - Test order cancellation dan stock restoration
  - Test customer messaging dan threading
  - Test bulk messaging functionality
  - Ask user if questions arise

- [x] 14. Banner Management Implementation
  - [x] 14.1 Create BannerController untuk admin
    - Create app/Http/Controllers/Admin/BannerController.php
    - Implement index method (list dengan banner preview thumbnails)
    - Implement create method (show form with banner type selection and dimension specs)
    - Implement store method (validate image dimensions per type, save banner)
    - Implement edit method (show form with existing data and image preview)
    - Implement update method (validate and update banner, handle image replacement)
    - Implement destroy method (delete banner)
    - Implement updateOrder method (reorder banners via drag-and-drop)
    - Implement toggleStatus method (activate/deactivate banner)
    - Inject BannerService and BannerRepository
    - _Requirements: 12.1, 12.2, 12.3, 12.4, 12.6, 12.7, 12.8, 12.9, 12.10_

  - [x] 14.2 Create banner views
    - Create resources/views/admin/banners/index.blade.php (cards with preview and drag-and-drop)
    - Create resources/views/admin/banners/create.blade.php (form with type selector, dimension display, live preview)
    - Create resources/views/admin/banners/edit.blade.php (form with image preview and replacement)
    - Add banner type specifications display (Hero: 1920x600px, Secondary: 1200x400px, Promo: 800x300px)
    - Add live preview component
    - Add drag-and-drop ordering using SortableJS
    - Add scheduling fields (start_date, end_date) with date pickers
    - Display calculated status (active, scheduled, expired, inactive)
    - _Requirements: 12.1, 12.2, 12.5, 12.7, 12.9_

  - [x] 14.3 Add banner routes
    - Add resource routes in routes/admin.php: admin.banners.*
    - Add POST /admin/banners/update-order route
    - Add POST /admin/banners/{id}/toggle-status route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 14.4 Write property tests untuk banner management
    - **Property 19: Banner Status Calculation**
    - **Property 20: Banner Ordering Uniqueness**
    - **Validates: Requirements 12.9, 12.10, 12.7**

- [x] 15. Review Management Implementation
  - [x] 15.1 Create ReviewController untuk admin
    - Create app/Http/Controllers/Admin/ReviewController.php
    - Implement index method (list dengan filters: rating, status, product)
    - Implement show method (detail dengan review content, images, user info)
    - Implement approve method (approve review and make visible)
    - Implement reject method (reject review with reason)
    - Implement respond method (add admin response to review)
    - Implement destroy method (soft delete review)
    - Inject ReviewService and ReviewRepository
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5, 6.6, 6.7_

  - [x] 15.2 Create review views
    - Create resources/views/admin/reviews/index.blade.php (DataTables with filters and rating display)
    - Create resources/views/admin/reviews/show.blade.php (detail with approve/reject buttons, response form)
    - Add review analytics section (average rating, rating distribution chart)
    - Add admin response form with rich text editor
    - Add rejection reason modal
    - Display review images if present
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5, 6.7_

  - [x] 15.3 Add review routes
    - Add routes in routes/admin.php: admin.reviews.index, show, destroy
    - Add POST /admin/reviews/{id}/approve route
    - Add POST /admin/reviews/{id}/reject route
    - Add POST /admin/reviews/{id}/respond route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 15.4 Write property tests untuk review management
    - **Property 16: Review Approval Visibility**
    - **Validates: Requirements 6.4, 6.5**

- [x] 16. Discount Management Implementation
  - [x] 16.1 Create DiscountController untuk admin
    - Create app/Http/Controllers/Admin/DiscountController.php
    - Implement index method (list dengan discount analytics: usage count, total discount amount)
    - Implement create method (show form with all discount rules)
    - Implement store method (validate and save discount code)
    - Implement edit method (show form with existing data)
    - Implement update method (validate and update discount)
    - Implement destroy method (delete discount)
    - Implement toggleStatus method (activate/deactivate discount)
    - Inject DiscountService and DiscountRepository
    - _Requirements: 16.1, 16.2, 16.3, 16.4, 16.5, 16.6, 16.7, 16.8_

  - [x] 16.2 Create discount views
    - Create resources/views/admin/discounts/index.blade.php (table with usage statistics)
    - Create resources/views/admin/discounts/create.blade.php (form with type, value, limits, applicability)
    - Create resources/views/admin/discounts/edit.blade.php (form with existing data)
    - Add discount type selector (percentage/fixed amount)
    - Add applicability selection (all products, specific products, specific categories)
    - Add usage limit fields (max_uses, uses_per_customer)
    - Add date range picker for validity period
    - Display usage statistics and remaining uses
    - _Requirements: 16.1, 16.2, 16.3, 16.4, 16.5, 16.6_

  - [x] 16.3 Add discount routes
    - Add resource routes in routes/admin.php: admin.discounts.*
    - Add POST /admin/discounts/{id}/toggle-status route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 16.4 Write property tests untuk discount management
    - **Property 21: Discount Usage Limit Enforcement**
    - **Property 22: Discount Applicability Rules**
    - **Validates: Requirements 16.8, 16.5**

- [x] 17. Payment Tracking Implementation
  - [x] 17.1 Create PaymentController untuk admin
    - Create app/Http/Controllers/Admin/PaymentController.php
    - Implement index method (list dengan filters: status, payment method, date range)
    - Implement show method (detail dari Midtrans API dengan transaction info)
    - Implement syncStatus method (sync payment status from Midtrans)
    - Implement manualVerify method (manual verification with confirmation)
    - Implement analytics method (payment method distribution, success rate)
    - Inject PaymentService and PaymentRepository
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5, 8.6_

  - [x] 17.2 Integrate dengan Midtrans API
    - Use existing MidtransService to fetch transaction status
    - Implement webhook handler untuk payment status updates (already exists in WebhookController)
    - Display transaction details (transaction_id, status, amount, payment_type, timestamps)
    - Handle failed payments with error details
    - _Requirements: 8.3, 8.4_

  - [x] 17.3 Create payment views
    - Create resources/views/admin/payments/index.blade.php (DataTables with filters)
    - Create resources/views/admin/payments/show.blade.php (detail with Midtrans data)
    - Create resources/views/admin/payments/analytics.blade.php (payment analytics dashboard)
    - Add payment method analytics dashboard (pie chart, distribution table)
    - Add sync status button for real-time updates
    - Add manual verification modal with confirmation
    - Display payment timeline and status history
    - _Requirements: 8.1, 8.2, 8.3, 8.6_

  - [x] 17.4 Add payment routes
    - Add routes in routes/admin.php: admin.payments.index, show
    - Add POST /admin/payments/{id}/sync-status route
    - Add POST /admin/payments/{id}/manual-verify route
    - Add GET /admin/payments/analytics route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 17.5 Write property tests untuk payment tracking
    - **Property 29: Payment Method Distribution Accuracy**
    - **Validates: Requirements 8.6**

- [x] 18. Analytics dan Reports Implementation
  - [x] 18.1 Create AnalyticsController untuk admin
    - Create app/Http/Controllers/Admin/AnalyticsController.php
    - Implement revenue method (revenue analytics dengan date range selection)
    - Implement revenueByPaymentMethod method (breakdown by payment method)
    - Implement productPerformance method (top selling products by revenue/quantity)
    - Implement customerAnalytics method (new customers, retention rate, top customers)
    - Implement exportReport method (generate PDF/Excel reports)
    - Inject AnalyticsService
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5, 7.6, 7.7_

  - [x] 18.2 Create analytics views
    - Create resources/views/admin/analytics/revenue.blade.php (charts with date range picker)
    - Create resources/views/admin/analytics/products.blade.php (top products table and chart)
    - Create resources/views/admin/analytics/customers.blade.php (customer analytics dashboard)
    - Add revenue chart (line chart with daily/weekly/monthly toggle)
    - Add payment method breakdown (pie chart and table)
    - Add top products ranking (bar chart and table)
    - Add date range picker component
    - Add export buttons (PDF, Excel, CSV)
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.6_

  - [x] 18.3 Add analytics routes
    - Add routes in routes/admin.php: admin.analytics.*
    - Add GET /admin/analytics/revenue route
    - Add GET /admin/analytics/products route
    - Add GET /admin/analytics/customers route
    - Add POST /admin/analytics/export route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 18.4 Write property tests untuk analytics
    - **Property 30: Revenue Breakdown Consistency**
    - **Property 31: Top Products Ranking Correctness**
    - **Validates: Requirements 7.2, 7.4**

- [x] 19. Checkpoint - Test Analytics dan Reports
  - Test revenue calculations dengan various date ranges
  - Test report exports (PDF/Excel)
  - Test payment method distribution
  - Test top products ranking
  - Ask user if questions arise

- [x] 20. Online Users dan Visitor Tracking Implementation
  - [x] 20.1 Implement OnlineUsersService dengan Redis
    - Create app/Services/OnlineUsersService.php
    - Implement trackActivity method (store user activity in Redis with TTL 5 minutes)
    - Implement getOnlineUsers method (get list of online users)
    - Implement getOnlineCount method (get count of online users)
    - Use Redis with key pattern: online_users:{user_id}
    - _Requirements: 1.4, 18.1, 18.2_

  - [x] 20.2 Implement VisitorTrackingService
    - Create app/Services/VisitorTrackingService.php
    - Implement logVisit method (record visitor log with IP, user agent, page, timestamp)
    - Implement getUniqueVisitorsToday method (count unique IPs today)
    - Implement getUniqueVisitorsThisMonth method (count unique IPs this month)
    - Implement getVisitorTrends method (daily/monthly aggregation for charts)
    - Implement cleanupOldLogs method (delete logs older than 90 days)
    - _Requirements: 1.5, 18.3, 18.4, 18.5, 18.6, 18.7, 18.8_

  - [x] 20.3 Create tracking middleware
    - Create app/Http/Middleware/TrackVisitor.php (log every request to visitor_logs)
    - Create app/Http/Middleware/UpdateOnlineStatus.php (update Redis for authenticated users)
    - Register middleware in app/Http/Kernel.php (apply to web routes)
    - _Requirements: 18.2, 18.7_

  - [x] 20.4 Create VisitorAnalyticsController
    - Create app/Http/Controllers/Admin/VisitorAnalyticsController.php
    - Implement index method (visitor analytics dashboard)
    - Display daily/monthly unique visitors
    - Display visitor trends chart
    - Display online users list with last activity
    - _Requirements: 1.4, 1.5, 18.3, 18.4, 18.5, 18.6_

  - [x] 20.5 Create visitor analytics views
    - Create resources/views/admin/visitors/index.blade.php
    - Add visitor metrics cards (today, this month, online now)
    - Add visitor trends chart (line chart with daily/monthly toggle)
    - Add online users table with real-time updates
    - Add page views statistics
    - _Requirements: 1.4, 1.5, 18.3, 18.4, 18.5, 18.6_

  - [x] 20.6 Add visitor analytics routes
    - Add GET /admin/visitors route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 20.7 Write property tests untuk visitor tracking
    - **Property 34: Online Users Accuracy**
    - **Property 35: Visitor Count Uniqueness**
    - **Validates: Requirements 1.4, 18.3, 18.4**

- [x] 21. Settings Management Implementation
  - [x] 21.1 Create SettingsController untuk admin
    - Create app/Http/Controllers/Admin/SettingsController.php
    - Implement index method (show settings page with tabs)
    - Implement updateGeneral method (site info, logo, contact)
    - Implement updatePayment method (Midtrans config: server key, client key, environment)
    - Implement updateShipping method (RajaOngkir config: API key, origin city)
    - Implement updateEmail method (SMTP config: host, port, username, password, encryption)
    - Implement updateNotifications method (enable/disable per event)
    - Implement testEmail method (test SMTP connection)
    - Implement testMidtrans method (test Midtrans connection)
    - Inject SettingsService
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5, 9.6, 9.7_

  - [x] 21.2 Create settings views
    - Create resources/views/admin/settings/index.blade.php (tabbed interface)
    - Add General Settings tab (site name, logo upload, contact info, social media)
    - Add Payment Settings tab (Midtrans credentials, test connection button)
    - Add Shipping Settings tab (RajaOngkir API key, origin city selector)
    - Add Email Settings tab (SMTP configuration, test email button)
    - Add Notification Settings tab (checkboxes for each event type)
    - Add form validation and error messages
    - Add test connection modals with results display
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5, 9.6, 9.7_

  - [x] 21.3 Add settings routes
    - Add GET /admin/settings route
    - Add POST /admin/settings/general route
    - Add POST /admin/settings/payment route
    - Add POST /admin/settings/shipping route
    - Add POST /admin/settings/email route
    - Add POST /admin/settings/notifications route
    - Add POST /admin/settings/test-email route
    - Add POST /admin/settings/test-midtrans route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 21.4 Write property tests untuk settings
    - **Property 32: Form Validation Consistency**
    - **Validates: Requirements 9.2**

- [x] 22. Activity Logs Implementation
  - [x] 22.1 Create ActivityLogController
    - Create app/Http/Controllers/Admin/ActivityLogController.php
    - Implement index method (list dengan filters: user, action, module, date range)
    - Implement show method (detail dengan old/new values comparison)
    - Inject ActivityLogRepository
    - _Requirements: 13.1, 13.2, 13.3_

  - [x] 22.2 Implement activity logging di controllers
    - Create ActivityLogService helper untuk log operations
    - Add logging untuk create operations (log entity created)
    - Add logging untuk update operations (log old values vs new values)
    - Add logging untuk delete operations (log entity deleted)
    - Add logging untuk status changes (log status transitions)
    - Add logging untuk login/logout (log authentication events)
    - Capture IP address and user agent
    - _Requirements: 10.7_

  - [x] 22.3 Create activity log views
    - Create resources/views/admin/activity-logs/index.blade.php (DataTables with filters)
    - Create resources/views/admin/activity-logs/show.blade.php (detail with diff view)
    - Add filter dropdowns (user, action type, module, date range)
    - Add search functionality
    - Display old vs new values in readable format (JSON diff or table)
    - Add color coding for different action types
    - _Requirements: 13.1, 13.2, 13.3_

  - [x] 22.4 Add activity log routes
    - Add routes in routes/admin.php: admin.activity-logs.index, show
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 22.5 Write property tests untuk activity logging
    - **Property 25: Activity Log Completeness**
    - **Validates: Requirements 10.7**

- [x] 23. System Monitoring Implementation
  - [x] 23.1 Create SystemMonitorController
    - Create app/Http/Controllers/Admin/SystemMonitorController.php
    - Implement index method (system status dashboard)
    - Implement checkServices method (test Midtrans, RajaOngkir, SMTP connections)
    - Implement databaseStats method (get table row counts, database size)
    - Implement errorLogs method (display recent error logs)
    - Implement systemHealth method (disk usage, memory, cache status)
    - _Requirements: 13.4, 13.5, 13.6, 13.7_

  - [x] 23.2 Create system monitoring views
    - Create resources/views/admin/system/index.blade.php (system status dashboard)
    - Add service status indicators (green/red badges for Midtrans, RajaOngkir, SMTP)
    - Add database statistics table (table names, row counts)
    - Add error logs section with pagination and filtering
    - Add system health metrics (disk usage progress bar, database size)
    - Add refresh buttons for real-time status checks
    - _Requirements: 13.4, 13.5, 13.6, 13.7_

  - [x] 23.3 Add system monitoring routes
    - Add GET /admin/system route
    - Add POST /admin/system/check-services route
    - Add GET /admin/system/database-stats route
    - Add GET /admin/system/error-logs route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

- [x] 24. Notification Management Implementation
  - [x] 24.1 Create NotificationController
    - Create app/Http/Controllers/Admin/NotificationController.php
    - Implement templates method (list email templates)
    - Implement editTemplate method (edit template with variable placeholders)
    - Implement updateTemplate method (save template changes)
    - Implement previewTemplate method (preview with sample data)
    - Implement history method (notification history with status)
    - Implement retry method (retry failed email)
    - Inject NotificationService
    - _Requirements: 14.1, 14.2, 14.3, 14.4, 14.5, 14.6, 14.7_

  - [x] 24.2 Create notification views
    - Create resources/views/admin/notifications/templates.blade.php (template list)
    - Create resources/views/admin/notifications/edit-template.blade.php (editor with preview)
    - Create resources/views/admin/notifications/history.blade.php (notification history table)
    - Add rich text editor for template body
    - Add variable placeholder helper (show available variables)
    - Add preview modal with sample data
    - Add notification history with status badges (sent, failed, pending)
    - Add retry button for failed notifications
    - _Requirements: 14.1, 14.2, 14.3, 14.4, 14.5_

  - [x] 24.3 Add notification routes
    - Add GET /admin/notifications/templates route
    - Add GET /admin/notifications/templates/{id}/edit route
    - Add POST /admin/notifications/templates/{id} route
    - Add POST /admin/notifications/templates/{id}/preview route
    - Add GET /admin/notifications/history route
    - Add POST /admin/notifications/{id}/retry route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

- [x] 25. Shipping Management Implementation
  - [x] 25.1 Create ShippingController untuk admin
    - Create app/Http/Controllers/Admin/ShippingController.php
    - Implement index method (shipping settings dashboard)
    - Implement updateCouriers method (enable/disable couriers)
    - Implement updateOrigin method (set origin address)
    - Implement updateFreeShipping method (set free shipping rules)
    - Implement updateZones method (manage shipping zones)
    - Implement analytics method (shipping analytics)
    - Use existing RajaOngkirService for courier data
    - _Requirements: 15.1, 15.2, 15.3, 15.4, 15.5, 15.6, 15.7_

  - [x] 25.2 Create shipping views
    - Create resources/views/admin/shipping/index.blade.php (tabbed interface)
    - Add Couriers tab (list with enable/disable toggles)
    - Add Origin Settings tab (city selector, postal code)
    - Add Free Shipping tab (minimum order amount, applicable zones)
    - Add Shipping Zones tab (manage delivery areas)
    - Add Analytics tab (courier usage distribution, average shipping cost)
    - Display available couriers from RajaOngkir
    - Add shipping cost preview calculator
    - _Requirements: 15.1, 15.2, 15.3, 15.5, 15.6, 15.7_

  - [x] 25.3 Add shipping routes
    - Add GET /admin/shipping route
    - Add POST /admin/shipping/couriers route
    - Add POST /admin/shipping/origin route
    - Add POST /admin/shipping/free-shipping route
    - Add POST /admin/shipping/zones route
    - Add GET /admin/shipping/analytics route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

- [x] 26. Data Export dan Import Implementation
  - [x] 26.1 Create ExportImportController
    - Create app/Http/Controllers/Admin/ExportImportController.php
    - Implement exportProducts method (CSV/Excel with all fields)
    - Implement exportOrders method (CSV/Excel with date range filter)
    - Implement exportCustomers method (CSV/Excel excluding sensitive data)
    - Implement importProducts method (validate and import from CSV/Excel)
    - Implement downloadTemplate method (download sample import template)
    - Use Laravel Excel package (maatwebsite/excel)
    - _Requirements: 20.1, 20.2, 20.3, 20.4, 20.5_

  - [x] 26.2 Create export/import views
    - Create resources/views/admin/export-import/index.blade.php
    - Add Export section with entity selector and date range picker
    - Add Import section with file upload and validation
    - Add sample template download links
    - Display import errors with row details (if validation fails)
    - Add progress indicator for large imports
    - _Requirements: 20.1, 20.2, 20.3_

  - [x] 26.3 Add export/import routes
    - Add GET /admin/export-import route
    - Add POST /admin/export/products route
    - Add POST /admin/export/orders route
    - Add POST /admin/export/customers route
    - Add POST /admin/import/products route
    - Add GET /admin/import/template/{type} route
    - Apply admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 26.4 Write property tests untuk export/import
    - **Property 23: Export Data Completeness**
    - **Property 24: Import Validation Completeness**
    - **Validates: Requirements 20.1, 20.2, 20.3, 20.4, 20.5**

- [x] 27. Authentication dan Authorization Implementation
  - [x] 27.1 Create admin authentication controller
    - Create app/Http/Controllers/Admin/AuthController.php
    - Implement showLogin method (display login page)
    - Implement login method (authenticate admin user)
    - Implement logout method (destroy session)
    - Check user role/is_admin field
    - Implement "remember me" functionality
    - _Requirements: 10.1, 10.2, 10.3, 10.5_

  - [x] 27.2 Update AdminMiddleware
    - Verify AdminMiddleware checks role/is_admin field correctly
    - Redirect non-admin users to 403 page
    - Handle session expiry (redirect to login with intended URL)
    - Store intended URL for post-login redirect
    - _Requirements: 10.1, 10.2, 10.4_

  - [x] 27.3 Create admin authentication views
    - Create resources/views/admin/auth/login.blade.php (login form)
    - Add email and password fields
    - Add "remember me" checkbox
    - Add error message display
    - Use admin layout (separate from customer layout)
    - _Requirements: 10.1, 10.3, 10.5_

  - [x] 27.4 Implement role-based access control (optional)
    - Create permissions table migration (if implementing RBAC)
    - Create Permission model
    - Assign permissions to routes/actions
    - Check permissions in middleware or policies
    - _Requirements: 10.6_

  - [x] 27.5 Add authentication routes
    - Add GET /admin/login route (showLogin)
    - Add POST /admin/login route (login)
    - Add POST /admin/logout route (logout)
    - Exclude login routes from admin middleware
    - _Requirements: 10.1, 10.2_

  - [ ]* 27.6 Write property tests untuk authentication
    - **Property 26: Role-Based Access Control**
    - **Property 27: Session Management**
    - **Validates: Requirements 10.1, 10.2, 10.3, 10.5**

- [x] 28. Checkpoint - Test Authentication dan Authorization
  - Test admin login dan logout
  - Test non-admin access denial
  - Test session expiry
  - Test role-based permissions (if implemented)
  - Ask user if questions arise

- [x] 29. UI/UX Enhancements
  - [x] 29.1 Implement DataTables features
    - Add DataTables.js to all list pages (products, orders, customers, etc.)
    - Configure server-side processing for large datasets
    - Add column sorting functionality
    - Add search functionality (global and per-column)
    - Add pagination controls with page size selector
    - Add column visibility toggles
    - Add export buttons (Copy, CSV, Excel, PDF, Print)
    - _Requirements: 11.5_

  - [x] 29.2 Implement form validation dan feedback
    - Add client-side validation using Alpine.js
    - Add real-time validation feedback (show errors as user types)
    - Add success/error toast notifications using Alpine.js or Toastr
    - Add loading states for async operations (buttons, forms)
    - Add confirmation modals for destructive actions (delete, cancel)
    - Ensure all forms have proper CSRF protection
    - _Requirements: 11.4, 11.6_

  - [x] 29.3 Implement responsive design
    - Test all pages on mobile devices (320px, 375px, 768px)
    - Fix mobile layout issues (sidebar, tables, forms)
    - Add mobile-friendly navigation (hamburger menu)
    - Optimize tables for mobile view (responsive tables or cards)
    - Test on tablet devices (768px, 1024px)
    - Ensure touch-friendly buttons and inputs
    - _Requirements: 11.2_

  - [x] 29.4 Add UI components
    - Create reusable Blade components (buttons, badges, cards, modals)
    - Add loading spinners and skeleton screens
    - Add empty state illustrations
    - Add breadcrumb navigation to all pages
    - Add page titles and descriptions
    - Ensure consistent spacing and typography
    - _Requirements: 11.1, 11.3, 11.7_

  - [ ]* 29.5 Write property tests untuk UI functionality
    - **Property 33: Data Table Sorting Correctness**
    - **Validates: Requirements 11.5**

- [ ] 30. Integration Testing
  - [ ]* 30.1 Write integration tests untuk critical flows
    - Test complete order flow (view list → view detail → update status → cancel order)
    - Test product management flow (create product → upload images → edit → soft delete)
    - Test customer messaging flow (send message → view thread → reply → email notification)
    - Test discount application flow (create discount → apply to order → validate usage limit)
    - Test banner scheduling flow (create banner → schedule → auto activate/expire)
    - Test authentication flow (login → access protected route → logout → access denied)
    - Test export/import flow (export products → modify → import → validate changes)
    - Use Pest PHP for all integration tests
    - _Requirements: All critical workflows_

- [x] 31. Performance Optimization
  - [x] 31.1 Optimize database queries
    - Add database indexes untuk frequently queried columns (user_id, status, created_at, etc.)
    - Implement eager loading untuk relationships (use with() in queries)
    - Add query caching untuk analytics (cache revenue, stats for 5 minutes)
    - Use select() to load only needed columns
    - Optimize N+1 query problems
    - _Requirements: Performance_

  - [x] 31.2 Implement caching strategy
    - Cache dashboard metrics (5 minutes TTL using Redis)
    - Cache settings (until updated, use cache tags)
    - Cache online users count (1 minute TTL in Redis)
    - Cache visitor statistics (5 minutes TTL)
    - Cache product/brand/category lists (10 minutes TTL)
    - Implement cache invalidation on updates
    - _Requirements: Performance_

  - [x] 31.3 Optimize assets
    - Minify CSS and JavaScript files
    - Optimize images (compress, use WebP format)
    - Implement lazy loading for images
    - Use CDN for static assets (optional)
    - Enable browser caching
    - _Requirements: Performance_

- [ ] 32. Final Testing dan Bug Fixes
  - [ ] 32.1 Manual testing checklist
    - Test all CRUD operations (create, read, update, delete) for each module
    - Test file uploads dengan various formats (jpg, png, webp, invalid formats)
    - Test pagination, search, dan filters on all list pages
    - Test email notifications (order status, customer messages, etc.)
    - Test export/import functionality (CSV, Excel)
    - Test responsive design on mobile (iPhone, Android) and tablet (iPad)
    - Test error handling dan validation (empty fields, invalid data, etc.)
    - Test authentication (login, logout, session expiry, remember me)
    - Test authorization (non-admin access, role-based permissions)
    - Test all external integrations (Midtrans, RajaOngkir, SMTP)
    - _Requirements: All_

  - [ ] 32.2 Fix identified bugs
    - Document all bugs found during testing (create issue list)
    - Prioritize bugs (critical, high, medium, low)
    - Fix critical bugs first (security, data loss, crashes)
    - Fix high priority bugs (major functionality broken)
    - Fix medium/low priority bugs (UI issues, minor bugs)
    - Retest after each fix to ensure no regressions
    - _Requirements: All_

  - [ ] 32.3 Run all automated tests
    - Run all property-based tests (minimum 100 iterations each)
    - Run all unit tests
    - Run all integration tests
    - Ensure all tests pass
    - Check test coverage (aim for 80%+ overall)
    - _Requirements: Testing requirements_

- [ ] 33. Documentation
  - [ ] 33.1 Create admin user guide
    - Document how to access admin panel (URL, login credentials)
    - Document dashboard features and metrics
    - Document product management (add, edit, delete, bulk operations)
    - Document order management (view, update status, cancel, export)
    - Document customer management (view, suspend, messaging)
    - Document banner management (create, schedule, reorder)
    - Document discount management (create, configure, track usage)
    - Document settings configuration (payment, shipping, email, notifications)
    - Add screenshots untuk key features
    - Document common workflows (processing orders, handling customer inquiries)
    - Create troubleshooting section (common issues and solutions)
    - _Requirements: Documentation_

  - [ ] 33.2 Create technical documentation
    - Document architecture dan design decisions (Repository-Service Pattern)
    - Document database schema (ERD diagram, table descriptions)
    - Document API endpoints (if any REST APIs created)
    - Document deployment process (server requirements, installation steps)
    - Document environment variables (.env configuration)
    - Document caching strategy (Redis keys, TTLs)
    - Document queue configuration (jobs, workers)
    - Document testing approach (unit, property-based, integration)
    - Create code style guide (PSR-12, naming conventions)
    - _Requirements: Documentation_

- [ ] 34. Final Checkpoint - Complete Testing
  - Run all property-based tests (minimum 100 iterations each)
  - Run all unit tests
  - Run integration tests
  - Verify all requirements are met (cross-check with requirements.md)
  - Verify all acceptance criteria are satisfied
  - Test on production-like environment
  - Perform security audit (SQL injection, XSS, CSRF, authentication)
  - Perform performance testing (load testing, stress testing)
  - Ask user for final review and approval

## Notes

- Tasks marked with `*` are optional testing tasks that can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation at key milestones
- Property tests validate universal correctness properties with minimum 100 iterations
- Unit tests validate specific examples and edge cases
- Integration tests validate end-to-end workflows
- All tests should reference their corresponding design document properties using the tag format: `Feature: admin-panel, Property {number}: {property_text}`
- Pest PHP is already installed and configured for testing
- Use Laravel Excel (maatwebsite/excel) for export/import functionality
- Use Chart.js or ApexCharts for data visualization
- Use DataTables.js for advanced table features
- Use SortableJS for drag-and-drop ordering
- All admin routes should use `/admin` prefix and admin middleware
- All views should extend admin layout (resources/views/admin/layouts/app.blade.php)
- Follow PSR-12 coding standards
- Use type hints and return types in all methods
- Document all public methods with PHPDoc comments
