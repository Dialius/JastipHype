# Design Document - Admin Panel JastipHype

## Overview

Admin Panel untuk JastipHype adalah sistem backend management yang dibangun menggunakan Laravel framework dengan arsitektur MVC yang diperluas menggunakan Repository-Service Pattern untuk memisahkan business logic dari data access layer. Panel ini akan menyediakan interface yang user-friendly untuk mengelola seluruh aspek e-commerce platform termasuk products, orders, customers, payments, shipping, dan analytics.

### Technology Stack

**Backend:**
- Laravel 12.x (PHP 8.2+) - CURRENT
- MySQL/MariaDB untuk database
- Redis untuk caching dan session management
- Queue system untuk background jobs (email, notifications)

**Frontend:**
- Blade templating engine
- Bootstrap 5.3 (via CDN - no build conflicts with customer site)
- Vanilla JavaScript / jQuery untuk interactivity
- Chart.js untuk data visualization
- DataTables.js untuk advanced table features
- Font Awesome untuk icons

**Template Approach:**

**Bootstrap 5 Admin Panel** (✅ IMPLEMENTED)
- Bootstrap 5.3 via CDN (tidak perlu compile)
- Mature dan stable
- Large community dan dokumentasi lengkap
- Banyak plugins dan components tersedia
- **Tidak akan konflik dengan Tailwind customer website** ✅
- Mudah di-customize
- Responsive dan mobile-friendly
- Support untuk dark mode

**Implementation Status:**
- ✅ Base layout created (app.blade.php)
- ✅ Sidebar navigation (sidebar.blade.php)
- ✅ Top navbar with user dropdown (navbar.blade.php)
- ✅ Dashboard view with metrics cards
- ✅ Responsive design (mobile-friendly)
- ✅ Bootstrap Icons integrated
- ✅ No build process required (CDN)
- ✅ Zero conflicts with customer Tailwind site

**Why Bootstrap over Tailwind for Admin:**
1. **Zero Conflict** - Tidak mengganggu customer website yang pakai Tailwind
2. **Faster Development** - Components sudah jadi, tinggal pakai
3. **Mature Ecosystem** - Banyak plugins gratis (DataTables, Chart.js, dll)
4. **Easy Maintenance** - Via CDN, tidak perlu build process
5. **Proven** - Dipakai jutaan admin panels di production


## Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        Presentation Layer                    │
│  (Blade Views + Alpine.js + Tailwind CSS)                   │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                       Controller Layer                       │
│  (Admin Controllers - Handle HTTP Requests/Responses)       │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                        Service Layer                         │
│  (Business Logic - Validation, Processing, Orchestration)   │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      Repository Layer                        │
│  (Data Access - Eloquent Queries, Database Operations)      │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                         Model Layer                          │
│  (Eloquent Models - Product, Order, User, etc.)             │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                          Database                            │
│                      (MySQL/MariaDB)                         │
└─────────────────────────────────────────────────────────────┘

External Services:
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│   Midtrans   │  │  RajaOngkir  │  │  SMTP Server │
└──────────────┘  └──────────────┘  └──────────────┘
```

### Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── ProductController.php
│   │       ├── OrderController.php
│   │       ├── CustomerController.php
│   │       ├── ReviewController.php
│   │       ├── BannerController.php
│   │       ├── BrandController.php
│   │       ├── CategoryController.php
│   │       ├── DiscountController.php
│   │       ├── PaymentController.php
│   │       ├── ShippingController.php
│   │       ├── AnalyticsController.php
│   │       ├── SettingsController.php
│   │       ├── ActivityLogController.php
│   │       ├── MessageController.php
│   │       └── VisitorAnalyticsController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   └── Requests/
│       └── Admin/
│           ├── ProductRequest.php
│           ├── BannerRequest.php
│           └── DiscountRequest.php
├── Services/
│   ├── ProductService.php
│   ├── OrderService.php
│   ├── CustomerService.php
│   ├── PaymentService.php
│   ├── ShippingService.php
│   ├── AnalyticsService.php
│   ├── NotificationService.php
│   ├── OnlineUsersService.php
│   ├── VisitorTrackingService.php
│   ├── BrandService.php
│   └── MessageService.php
├── Repositories/
│   ├── Contracts/
│   │   ├── ProductRepositoryInterface.php
│   │   ├── OrderRepositoryInterface.php
│   │   └── ...
│   └── Eloquent/
│       ├── ProductRepository.php
│       ├── OrderRepository.php
│       └── ...
├── Models/
│   ├── Product.php
│   ├── Order.php
│   ├── User.php
│   ├── Banner.php
│   ├── Discount.php
│   ├── ActivityLog.php
│   └── ...
└── Providers/
    └── RepositoryServiceProvider.php

resources/
└── views/
    └── admin/
        ├── layouts/
        │   ├── app.blade.php
        │   ├── sidebar.blade.php
        │   └── navbar.blade.php
        ├── dashboard/
        │   └── index.blade.php
        ├── products/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   └── edit.blade.php
        ├── orders/
        │   ├── index.blade.php
        │   └── show.blade.php
        └── ...

routes/
└── admin.php (Admin routes with prefix /admin)
```

### Design Patterns

**1. Repository Pattern**
- Abstraksi data access layer
- Memudahkan testing dengan mock repositories
- Memisahkan query logic dari business logic

**2. Service Pattern**
- Encapsulate business logic
- Reusable across controllers
- Single Responsibility Principle

**3. Middleware Pattern**
- Authentication dan authorization
- Activity logging
- Request validation

**4. Observer Pattern**
- Model events untuk automatic logging
- Email notifications pada status changes
- Stock updates pada order cancellation


## Components and Interfaces

### Core Components

#### 1. Dashboard Component

**Purpose:** Menampilkan overview metrics dan analytics

**Key Features:**
- Revenue cards (today, week, month, year)
- Order statistics dengan breakdown status
- User statistics (total, active, new, online now)
- Visitor analytics (daily visitors, monthly visitors, unique visitors)
- Online users counter (real-time)
- Product statistics (total, active, low stock)
- Revenue chart (line/bar chart)
- Visitor trends chart (daily/monthly)
- Recent orders table
- Low stock alerts
- Quick action buttons

**Online Users Tracking:**
- Track active sessions in last 5 minutes
- Store user activity in cache (Redis)
- Update on each page view
- Display count of currently online users

**Visitor Analytics:**
- Track unique visitors by IP + User Agent
- Daily visitor count (unique per day)
- Monthly visitor count (unique per month)
- Store in database table for historical data
- Chart showing visitor trends

**Data Flow:**
```
DashboardController → AnalyticsService → Multiple Repositories → Aggregated Data
                   → OnlineUsersService → Redis Cache
                   → VisitorTrackingService → Visitor Logs
```

#### 2. Product Management Component

**Purpose:** CRUD operations untuk products

**Interfaces:**

```php
interface ProductRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id); // Soft delete
    public function updateStock(int $id, int $quantity);
    public function getLowStock(int $threshold = 10);
    public function search(string $query);
}

interface ProductServiceInterface
{
    public function createProduct(array $data);
    public function updateProduct(int $id, array $data);
    public function deleteProduct(int $id);
    public function handleImageUpload($image);
    public function updateStockStatus(int $id);
}
```

**Features:**
- Product listing dengan DataTables (search, sort, filter, pagination)
- Create/Edit form dengan image upload (multiple images)
- Image preview dan management
- Stock management dengan auto-status update
- Category dan brand assignment
- Bulk actions (delete, activate, deactivate)
- Export to CSV/Excel

#### 3. Order Management Component

**Purpose:** Mengelola customer orders

**Interfaces:**

```php
interface OrderRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function updateStatus(int $id, string $status);
    public function getByStatus(string $status);
    public function getByDateRange(string $startDate, string $endDate);
    public function getRecentOrders(int $limit = 10);
}

interface OrderServiceInterface
{
    public function updateOrderStatus(int $id, string $status);
    public function cancelOrder(int $id, string $reason);
    public function exportOrders(array $filters);
    public function getOrderTimeline(int $id);
}
```

**Features:**
- Order listing dengan advanced filters
- Order detail view dengan timeline
- Status management dengan email notification
- Payment status tracking dari Midtrans
- Shipping tracking dari RajaOngkir
- Invoice generation (PDF)
- Order cancellation dengan stock return
- Export orders

#### 4. Customer Management Component

**Purpose:** Mengelola user accounts dan interaksi dengan customers

**Interfaces:**

```php
interface CustomerRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function update(int $id, array $data);
    public function updateStatus(int $id, string $status);
    public function getCustomerStats(int $id);
    public function getCustomerOrders(int $id);
    public function getOnlineUsers();
}

interface CustomerServiceInterface
{
    public function suspendCustomer(int $id, string $reason);
    public function activateCustomer(int $id);
    public function getCustomerAnalytics(int $id);
    public function sendMessageToCustomer(int $customerId, string $message);
    public function getCustomerMessages(int $customerId);
}
```

**Features:**
- Customer listing dengan search dan filter
- Customer detail dengan order history
- Customer analytics (total spending, order count, average order value)
- Account status management (active/suspended)
- Export customers
- **Customer Interaction:**
  - Send direct message to customer
  - View message history with customer
  - Respond to customer inquiries
  - Send bulk notifications to customer segments
  - Customer support ticket system

**Customer Interaction Flow:**
```
Admin → CustomerController → CustomerService → MessageRepository
                                             → NotificationService → Email/SMS
```

#### 5. Banner Management Component

**Purpose:** Mengelola homepage banners

**Interfaces:**

```php
interface BannerRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function updateOrder(array $orderData);
    public function getActiveBanners();
}

interface BannerServiceInterface
{
    public function createBanner(array $data, $image);
    public function updateBanner(int $id, array $data, $image = null);
    public function handleImageUpload($image, string $type);
    public function validateImageDimensions($image, string $type);
    public function reorderBanners(array $orderData);
}
```

**Features:**
- Banner listing dengan preview thumbnails
- Create/Edit form dengan image upload
- Image dimension validation (per banner type)
- Live preview sebelum save
- Drag-and-drop ordering
- Link/URL management
- Schedule management (start/end date)
- Status management (active/inactive)

**Banner Types:**
- Hero Banner: 1920x600px
- Secondary Banner: 1200x400px
- Promo Banner: 800x300px

#### 6. Review Management Component

**Purpose:** Moderasi product reviews

**Interfaces:**

```php
interface ReviewRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function updateStatus(int $id, string $status);
    public function addAdminResponse(int $id, string $response);
    public function delete(int $id); // Soft delete
}

interface ReviewServiceInterface
{
    public function approveReview(int $id);
    public function rejectReview(int $id, string $reason);
    public function respondToReview(int $id, string $response);
}
```

**Features:**
- Review listing dengan filters (rating, status, product)
- Review detail view
- Approve/Reject actions
- Admin response capability
- Delete inappropriate reviews
- Review analytics (average rating, distribution)

#### 6.5. Brand Management Component

**Purpose:** Mengelola brand/merek produk

**Interfaces:**

```php
interface BrandRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15, array $filters = []);
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function hasProducts(int $id): bool;
    public function getProductCount(int $id): int;
    public function search(string $query);
}

interface BrandServiceInterface
{
    public function createBrand(array $data, $logo = null);
    public function updateBrand(int $id, array $data, $logo = null);
    public function deleteBrand(int $id);
    public function handleLogoUpload($logo);
    public function getBrandStatistics(int $id);
}
```

**Features:**
- Brand listing dengan search dan pagination
- Create brand dengan logo upload
- Edit brand (name, slug, description, logo)
- Delete brand (dengan validasi - tidak bisa delete jika ada produk)
- Brand logo management (upload, preview, delete)
- Brand statistics (jumlah produk, total revenue dari produk brand)
- Bulk operations (activate/deactivate multiple brands)
- Brand status management (active/inactive)
- Brand ordering/sorting untuk display priority

**Brand Data:**
- Name (required, unique)
- Slug (auto-generated dari name)
- Description (optional)
- Logo (image file, max 2MB, formats: jpg, png, webp)
- Status (active/inactive)
- Display Order (integer untuk sorting)
- Meta data (SEO title, description)

**Validation Rules:**
- Name: required, unique, max 100 characters
- Logo: image, max 2MB, dimensions min 200x200px, max 1000x1000px
- Slug: auto-generated, unique, URL-friendly
- Cannot delete brand if it has associated products

**Brand-Product Relationship:**
- One brand can have many products
- Product must belong to one brand
- When brand is deactivated, products remain but show "Brand Unavailable"
- Brand statistics show total products and revenue


#### 7. Payment Tracking Component

**Purpose:** Monitor payment transactions

**Interfaces:**

```php
interface PaymentRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function getByStatus(string $status);
    public function getByPaymentMethod(string $method);
    public function updateStatus(int $id, string $status);
}

interface PaymentServiceInterface
{
    public function syncWithMidtrans(string $orderId);
    public function manualVerification(int $id, array $data);
    public function getPaymentMethodDistribution();
    public function getPaymentAnalytics(array $filters);
}
```

**Features:**
- Payment transaction listing
- Filter by status, payment method, date
- Midtrans integration untuk real-time status
- Manual verification capability
- Payment method analytics
- Failed payment tracking dengan error details

#### 8. Analytics & Reports Component

**Purpose:** Generate reports dan analytics

**Interfaces:**

```php
interface AnalyticsServiceInterface
{
    public function getRevenueAnalytics(string $period, array $filters = []);
    public function getProductPerformance(array $filters = []);
    public function getCustomerAnalytics(array $filters = []);
    public function getPaymentMethodDistribution(array $filters = []);
    public function exportReport(string $type, array $filters = []);
}
```

**Features:**
- Revenue analytics dengan multiple views (daily, weekly, monthly)
- Revenue breakdown by payment method
- Top selling products
- Customer analytics (new customers, retention rate)
- Sales trends visualization
- Export reports (PDF, Excel)

#### 9. Settings Component

**Purpose:** System configuration management

**Interfaces:**

```php
interface SettingsRepositoryInterface
{
    public function get(string $key);
    public function set(string $key, $value);
    public function getGroup(string $group);
    public function updateGroup(string $group, array $data);
}

interface SettingsServiceInterface
{
    public function updateGeneralSettings(array $data);
    public function updatePaymentSettings(array $data);
    public function updateShippingSettings(array $data);
    public function updateEmailSettings(array $data);
    public function testEmailConnection(array $data);
    public function testMidtransConnection(array $data);
}
```

**Settings Groups:**
- General (site name, logo, contact info)
- Payment (Midtrans credentials)
- Shipping (RajaOngkir API, origin city)
- Email (SMTP configuration)
- Notifications (enable/disable per event)

#### 10. Activity Log Component

**Purpose:** Track admin activities

**Interfaces:**

```php
interface ActivityLogRepositoryInterface
{
    public function log(string $action, string $module, array $data);
    public function getAllPaginated(int $perPage = 15, array $filters = []);
    public function getByUser(int $userId);
    public function getByModule(string $module);
}
```

**Logged Activities:**
- Login/Logout
- Create/Update/Delete operations
- Status changes
- Settings modifications
- Bulk operations

**Log Data:**
- User ID dan name
- Action type
- Module/Entity
- Old value vs New value
- IP Address
- Timestamp


## Data Models

### Database Schema

#### Products Table
```sql
products
- id (PK)
- name
- slug
- description
- price
- stock
- status (enum: active, inactive, out_of_stock)
- brand_id (FK)
- category_id (FK)
- sku
- weight
- created_at
- updated_at
- deleted_at (soft delete)
```

#### Orders Table
```sql
orders
- id (PK)
- user_id (FK)
- order_number (unique)
- status (enum: pending, processing, shipped, completed, cancelled)
- payment_status (enum: pending, paid, failed, refunded)
- payment_method
- subtotal
- shipping_cost
- discount_amount
- total
- shipping_address (JSON)
- notes
- created_at
- updated_at
```

#### Order Items Table
```sql
order_items
- id (PK)
- order_id (FK)
- product_id (FK)
- product_name (snapshot)
- product_price (snapshot)
- quantity
- subtotal
```

#### Banners Table
```sql
banners
- id (PK)
- title
- image_path
- link_url
- type (enum: hero, secondary, promo)
- order (integer for sorting)
- status (enum: active, inactive, scheduled)
- start_date (nullable)
- end_date (nullable)
- created_at
- updated_at
```

#### Discounts Table
```sql
discounts
- id (PK)
- code (unique)
- type (enum: percentage, fixed)
- value
- min_order_amount
- max_uses (nullable)
- uses_count
- uses_per_customer (nullable)
- start_date
- end_date
- status (enum: active, inactive, expired)
- applicable_to (enum: all, products, categories)
- applicable_ids (JSON, nullable)
- created_at
- updated_at
```

#### Reviews Table
```sql
reviews
- id (PK)
- product_id (FK)
- user_id (FK)
- rating (1-5)
- comment
- status (enum: pending, approved, rejected)
- admin_response (nullable)
- rejection_reason (nullable)
- created_at
- updated_at
- deleted_at (soft delete)
```

#### Activity Logs Table
```sql
activity_logs
- id (PK)
- user_id (FK)
- action (string: create, update, delete, login, etc.)
- module (string: product, order, user, etc.)
- entity_id (nullable)
- old_values (JSON, nullable)
- new_values (JSON, nullable)
- ip_address
- user_agent
- created_at
```

#### Settings Table
```sql
settings
- id (PK)
- group (string: general, payment, shipping, email, notification)
- key (string)
- value (text)
- type (enum: string, integer, boolean, json)
- created_at
- updated_at
```

#### Visitor Logs Table
```sql
visitor_logs
- id (PK)
- ip_address
- user_agent
- page_url
- user_id (FK, nullable - for logged in users)
- session_id
- visited_at (timestamp)
- created_at
```

#### Online Users Cache (Redis)
```
Key: online_users:{user_id}
Value: {
    "user_id": 123,
    "name": "John Doe",
    "last_activity": "2024-01-15 10:30:00",
    "current_page": "/products"
}
TTL: 300 seconds (5 minutes)
```

#### Customer Messages Table
```sql
customer_messages
- id (PK)
- customer_id (FK)
- admin_id (FK, nullable)
- message
- type (enum: admin_to_customer, customer_to_admin)
- status (enum: sent, read, replied)
- parent_id (FK, nullable - for threading)
- created_at
- updated_at
```

#### Brands Table
```sql
brands
- id (PK)
- name (unique)
- slug (unique)
- description (text, nullable)
- logo_path (nullable)
- status (enum: active, inactive)
- display_order (integer, default 0)
- meta_title (nullable)
- meta_description (nullable)
- created_at
- updated_at
- deleted_at (soft delete)
```

### Eloquent Relationships

**Product Model:**
```php
class Product extends Model
{
    public function brand() { return $this->belongsTo(Brand::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function images() { return $this->hasMany(ProductImage::class); }
}
```

**Order Model:**
```php
class Order extends Model
{
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function discount() { return $this->belongsTo(Discount::class); }
}
```

**User Model:**
```php
class User extends Model
{
    public function orders() { return $this->hasMany(Order::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function activityLogs() { return $this->hasMany(ActivityLog::class); }
    public function messages() { return $this->hasMany(CustomerMessage::class, 'customer_id'); }
    public function sentMessages() { return $this->hasMany(CustomerMessage::class, 'admin_id'); }
    public function visitorLogs() { return $this->hasMany(VisitorLog::class); }
}
```

**Brand Model:**
```php
class Brand extends Model
{
    public function products() { return $this->hasMany(Product::class); }
    
    public function getProductCountAttribute() {
        return $this->products()->count();
    }
    
    public function getTotalRevenueAttribute() {
        return $this->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->sum('order_items.subtotal');
    }
}
```

**CustomerMessage Model:**
```php
class CustomerMessage extends Model
{
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
    public function parent() { return $this->belongsTo(CustomerMessage::class, 'parent_id'); }
    public function replies() { return $this->hasMany(CustomerMessage::class, 'parent_id'); }
}
```

**VisitorLog Model:**
```php
class VisitorLog extends Model
{
    public function user() { return $this->belongsTo(User::class); }
    
    public static function getUniqueVisitorsToday() {
        return static::whereDate('visited_at', today())
            ->distinct('ip_address')
            ->count('ip_address');
    }
    
    public static function getUniqueVisitorsThisMonth() {
        return static::whereMonth('visited_at', now()->month)
            ->whereYear('visited_at', now()->year)
            ->distinct('ip_address')
            ->count('ip_address');
    }
}
```

### Model Observers

**ProductObserver:**
- Update stock status saat stock berubah
- Log product changes

**OrderObserver:**
- Send email notification saat status berubah
- Update stock saat order cancelled
- Log order changes

**ReviewObserver:**
- Update product rating average
- Send notification ke customer saat approved

**BrandObserver:**
- Auto-generate slug dari name
- Log brand changes
- Validate deletion (prevent if has products)

**CustomerMessageObserver:**
- Send email notification to customer when admin sends message
- Update message status to 'read' when viewed
- Log message activities

**VisitorLogObserver:**
- Update online users cache in Redis
- Clean up old visitor logs (older than 90 days)


## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property Reflection

After analyzing all acceptance criteria, several patterns emerged that allow us to consolidate redundant properties:

**Pagination Properties:** Requirements 2.1, 3.1, 4.1, 6.1, 8.1, 13.1 all test pagination functionality. These can be consolidated into a single generic pagination property that applies to all list views.

**Filter Properties:** Requirements 2.2, 3.2, 4.2, 6.2, 8.2, 13.2 all test filtering functionality. These can be consolidated into properties that test filtering logic generically.

**CRUD Properties:** Many requirements test basic create, update, delete operations. These follow similar patterns and can be consolidated where the logic is identical.

**Status Toggle Properties:** Requirements 2.8, 4.5, 4.6, 6.4, 6.5, 12.8, 15.2, 16.7 all test status toggling. These can be consolidated into properties about state transitions.

**Export Properties:** Requirements 3.8, 7.6, 17.1, 17.4, 17.5 all test export functionality. These can be consolidated into properties about data serialization.

**Validation Properties:** Requirements 2.6, 12.3, 12.4, 17.2 all test input validation. These can be consolidated into properties about validation rules.

After reflection, we'll focus on unique, high-value properties that provide comprehensive coverage without redundancy.

### Core Properties

**Property 1: Pagination Consistency**
*For any* entity list (products, orders, users, reviews, payments, logs), when paginating with page size N, the total number of items across all pages should equal the total count, and no items should be duplicated or missing across pages.
**Validates: Requirements 2.1, 3.1, 4.1, 6.1, 8.1, 13.1**

**Property 2: Filter Correctness**
*For any* filterable entity list and any valid filter criteria, all returned items should match the filter criteria, and no items matching the criteria should be excluded.
**Validates: Requirements 2.2, 3.2, 4.2, 6.2, 8.2, 13.2**

**Property 3: Search Inclusivity**
*For any* searchable entity and search term, if an entity contains the search term in any searchable field (name, SKU, email, etc.), it should appear in search results.
**Validates: Requirements 2.2, 4.2**

**Property 4: Data Persistence Round-Trip**
*For any* entity (product, order, user, banner, discount, etc.), creating an entity with valid data and then retrieving it should return an equivalent entity with all fields intact.
**Validates: Requirements 2.3, 5.1, 5.4, 12.6, 16.1**

**Property 5: Update Idempotence**
*For any* entity, updating it with the same data multiple times should result in the same final state as updating it once.
**Validates: Requirements 2.4, 4.4, 5.2, 5.5**

**Property 6: Soft Delete Preservation**
*For any* soft-deletable entity (product, review), after soft deletion, the entity should not appear in normal queries but should still exist in the database with a deleted_at timestamp.
**Validates: Requirements 2.5, 6.6**

**Property 7: File Validation Rejection**
*For any* file upload (product image, banner image), files that violate format or size constraints should be rejected, and valid files should be accepted.
**Validates: Requirements 2.6, 12.3, 12.4**

**Property 8: Stock Status Synchronization**
*For any* product, when stock quantity changes, if stock becomes zero or negative, status should automatically update to "out_of_stock", and if stock becomes positive, status should update to "active" (if not manually set to inactive).
**Validates: Requirements 2.7**

**Property 9: Order Cancellation Stock Restoration**
*For any* order with items, when the order is cancelled, the stock quantity for each product in the order should increase by the ordered quantity.
**Validates: Requirements 3.5**

**Property 10: Revenue Calculation Accuracy**
*For any* date range, the total revenue should equal the sum of all completed orders' total amounts within that date range.
**Validates: Requirements 1.1, 7.1**

**Property 11: Order Status Distribution**
*For any* set of orders, the sum of orders in each status category (pending, processing, shipped, completed, cancelled) should equal the total number of orders.
**Validates: Requirements 1.2**

**Property 12: Low Stock Detection**
*For any* stock threshold T, all products with stock quantity less than or equal to T should appear in the low stock list, and no products with stock greater than T should appear.
**Validates: Requirements 1.7**

**Property 13: Recent Orders Ordering**
*For any* request for recent orders with limit N, the returned orders should be the N most recent orders sorted by creation date descending, and all returned orders should have creation dates greater than or equal to any non-returned orders.
**Validates: Requirements 1.6**

**Property 14: Referential Integrity Protection**
*For any* entity with dependent entities (brand with products, category with products), deletion attempts should be rejected when dependencies exist, and should succeed when no dependencies exist.
**Validates: Requirements 5.3, 5.6**

**Property 15: Category Hierarchy Consistency**
*For any* category with a parent category, the parent-child relationship should be bidirectional (parent.children includes child, child.parent equals parent), and no circular references should exist.
**Validates: Requirements 5.7**

**Property 16: Review Approval Visibility**
*For any* review, when status changes from "pending" to "approved", the review should become visible in product review queries, and when status is "rejected" or "pending", it should not be visible.
**Validates: Requirements 6.4, 6.5**

**Property 17: User Suspension Access Control**
*For any* user, when status is set to "suspended", authentication attempts should fail and order creation should be blocked, and when status is "active", these operations should succeed.
**Validates: Requirements 4.5, 4.6**

**Property 18: User Statistics Accuracy**
*For any* user, the displayed total spending should equal the sum of all their completed orders' total amounts, and order count should equal the number of their orders.
**Validates: Requirements 4.7**

**Property 19: Banner Status Calculation**
*For any* banner with start_date and end_date, the calculated status should be "scheduled" if current date is before start_date, "expired" if current date is after end_date, "active" if within date range and status flag is active, and "inactive" otherwise.
**Validates: Requirements 12.9, 12.10**

**Property 20: Banner Ordering Uniqueness**
*For any* set of banners, each banner should have a unique order value, and reordering should maintain uniqueness without gaps.
**Validates: Requirements 12.7**

**Property 21: Discount Usage Limit Enforcement**
*For any* discount code with max_uses limit, when uses_count reaches max_uses, the discount should automatically become inactive and should not be applicable to new orders.
**Validates: Requirements 16.8**

**Property 22: Discount Applicability Rules**
*For any* discount with applicable_to rules (products/categories), the discount should only apply to orders containing items that match the applicability criteria.
**Validates: Requirements 16.5**

**Property 23: Export Data Completeness**
*For any* data export operation (products, orders, users), the exported file should contain all entities matching the filter criteria with all specified fields present.
**Validates: Requirements 3.8, 7.6, 17.1, 17.4, 17.5**

**Property 24: Import Validation Completeness**
*For any* import operation, all rows with invalid data should be rejected with specific error messages, and all rows with valid data should be imported successfully.
**Validates: Requirements 17.2, 17.3**

**Property 25: Activity Log Completeness**
*For any* important action (create, update, delete, status change), an activity log entry should be created with user_id, action type, module, entity_id, timestamp, and IP address.
**Validates: Requirements 10.7**

**Property 26: Role-Based Access Control**
*For any* admin panel route, access should be granted only to users with "admin" role, and denied to users without admin role with appropriate redirect.
**Validates: Requirements 10.1, 10.2**

**Property 27: Session Management**
*For any* admin login, a session should be created with configurable expiry time, and after expiry or logout, the session should be invalidated and subsequent requests should require re-authentication.
**Validates: Requirements 10.3, 10.5**

**Property 28: Notification Email Queueing**
*For any* order status change, if email notifications are enabled for that event, an email job should be queued with correct recipient and template data.
**Validates: Requirements 3.4**

**Property 29: Payment Method Distribution Accuracy**
*For any* set of payments, the sum of payments in each payment method category should equal the total number of payments, and percentages should sum to 100%.
**Validates: Requirements 7.2, 8.6**

**Property 30: Revenue Breakdown Consistency**
*For any* date range, the sum of revenue across all payment methods should equal the total revenue for that period.
**Validates: Requirements 7.2**

**Property 31: Top Products Ranking Correctness**
*For any* request for top N selling products, the returned products should be the N products with highest revenue/quantity, sorted in descending order, and all returned products should have metrics greater than or equal to any non-returned products.
**Validates: Requirements 7.4**

**Property 32: Form Validation Consistency**
*For any* form submission with invalid data, validation should fail with specific error messages for each invalid field, and with valid data, validation should pass and data should be processed.
**Validates: Requirements 9.2, 11.6**

**Property 33: Data Table Sorting Correctness**
*For any* sortable column in a data table, sorting ascending should order items from lowest to highest by that column, and sorting descending should order from highest to lowest.
**Validates: Requirements 11.5**

**Property 34: Online Users Accuracy**
*For any* user with activity in the last 5 minutes, they should appear in the online users list, and users with no activity in the last 5 minutes should not appear.
**Validates: Requirements 1.3 (extended)**

**Property 35: Visitor Count Uniqueness**
*For any* date, the daily visitor count should equal the number of unique IP addresses that visited on that date, with no duplicates counted.
**Validates: Requirements 1.3 (extended)**

**Property 36: Brand Deletion Protection**
*For any* brand with associated products, deletion attempts should be rejected, and brands without products should be deletable.
**Validates: Requirements 5.3 (extended)**

**Property 37: Brand Logo Validation**
*For any* brand logo upload, images meeting dimension and size requirements (200x200 to 1000x1000px, max 2MB, jpg/png/webp) should be accepted, and images violating these constraints should be rejected.
**Validates: Requirements 5.1 (extended)**

**Property 38: Customer Message Delivery**
*For any* message sent from admin to customer, the message should be stored in database, an email notification should be queued, and the message should appear in the customer's message history.
**Validates: Requirements 4.3 (extended)**

**Property 39: Message Threading Consistency**
*For any* message with replies, the parent-child relationship should be maintained (parent.replies includes child, child.parent equals parent), and message threads should be retrievable in chronological order.
**Validates: Requirements 4.3 (extended)**

**Property 40: Brand Statistics Accuracy**
*For any* brand, the displayed product count should equal the number of products associated with that brand, and total revenue should equal the sum of completed order items for that brand's products.
**Validates: Requirements 5.1 (extended)**


## Error Handling

### Error Categories

**1. Validation Errors**
- Invalid input data (format, type, range)
- Missing required fields
- Constraint violations (unique, foreign key)

**Response:**
```php
return response()->json([
    'success' => false,
    'message' => 'Validation failed',
    'errors' => [
        'field_name' => ['Error message 1', 'Error message 2']
    ]
], 422);
```

**2. Authentication/Authorization Errors**
- Unauthenticated access
- Insufficient permissions
- Session expired

**Response:**
```php
// Redirect to login with intended URL
return redirect()->route('admin.login')
    ->with('error', 'Please login to continue')
    ->with('intended', request()->url());
```

**3. Resource Not Found Errors**
- Entity doesn't exist
- Soft-deleted entity accessed

**Response:**
```php
abort(404, 'Resource not found');
```

**4. Business Logic Errors**
- Cannot delete entity with dependencies
- Stock insufficient for operation
- Discount code expired or limit reached

**Response:**
```php
return back()->with('error', 'Cannot delete brand with existing products');
```

**5. External Service Errors**
- Midtrans API failure
- RajaOngkir API failure
- SMTP connection failure

**Response:**
```php
Log::error('Midtrans API Error', ['error' => $e->getMessage()]);
return back()->with('error', 'Payment service temporarily unavailable');
```

**6. File Upload Errors**
- Invalid file format
- File size exceeded
- Upload failed

**Response:**
```php
return back()->with('error', 'Image must be JPG, PNG, or WebP and less than 5MB');
```

### Error Handling Strategy

**Controller Level:**
```php
try {
    $result = $this->productService->createProduct($request->validated());
    return redirect()->route('admin.products.index')
        ->with('success', 'Product created successfully');
} catch (ValidationException $e) {
    return back()->withErrors($e->errors())->withInput();
} catch (BusinessLogicException $e) {
    return back()->with('error', $e->getMessage())->withInput();
} catch (\Exception $e) {
    Log::error('Product creation failed', ['error' => $e->getMessage()]);
    return back()->with('error', 'An unexpected error occurred')->withInput();
}
```

**Service Level:**
```php
public function createProduct(array $data)
{
    DB::beginTransaction();
    try {
        // Validate business rules
        if ($this->productRepository->existsBySku($data['sku'])) {
            throw new BusinessLogicException('SKU already exists');
        }
        
        // Create product
        $product = $this->productRepository->create($data);
        
        // Handle images
        if (isset($data['images'])) {
            $this->handleImageUpload($product, $data['images']);
        }
        
        DB::commit();
        return $product;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

**Global Exception Handler:**
```php
// app/Exceptions/Handler.php
public function render($request, Throwable $exception)
{
    if ($request->is('admin/*')) {
        if ($exception instanceof AuthenticationException) {
            return redirect()->route('admin.login');
        }
        
        if ($exception instanceof AuthorizationException) {
            return response()->view('admin.errors.403', [], 403);
        }
    }
    
    return parent::render($request, $exception);
}
```

### Logging Strategy

**Activity Logging:**
- All create, update, delete operations
- Status changes
- Login/logout events
- Settings modifications

**Error Logging:**
- All exceptions with stack traces
- External API failures
- Database errors
- File operation failures

**Log Channels:**
```php
// config/logging.php
'channels' => [
    'admin_activity' => [
        'driver' => 'daily',
        'path' => storage_path('logs/admin-activity.log'),
        'level' => 'info',
        'days' => 90,
    ],
    'admin_errors' => [
        'driver' => 'daily',
        'path' => storage_path('logs/admin-errors.log'),
        'level' => 'error',
        'days' => 90,
    ],
]
```


## Testing Strategy

### Dual Testing Approach

The admin panel will use a comprehensive testing strategy combining unit tests and property-based tests to ensure correctness and reliability.

**Unit Tests:**
- Focus on specific examples and edge cases
- Test integration points between components
- Verify error conditions and exception handling
- Test specific business logic scenarios

**Property-Based Tests:**
- Verify universal properties across all inputs
- Use randomized data generation for comprehensive coverage
- Test invariants and mathematical properties
- Minimum 100 iterations per property test

Both approaches are complementary and necessary for comprehensive coverage. Unit tests catch concrete bugs and verify specific scenarios, while property tests verify general correctness across a wide range of inputs.

### Property-Based Testing Configuration

**Framework:** Use **Pest PHP** with **Pest Property Testing Plugin** for Laravel

**Installation:**
```bash
composer require pestphp/pest --dev
composer require pestphp/pest-plugin-laravel --dev
composer require pestphp/pest-plugin-faker --dev
```

**Configuration:**
- Each property test must run minimum 100 iterations
- Each test must reference its design document property
- Tag format: `Feature: admin-panel, Property {number}: {property_text}`

**Example Property Test:**
```php
<?php

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

/**
 * Feature: admin-panel, Property 4: Data Persistence Round-Trip
 * 
 * For any entity (product, order, user, banner, discount, etc.), 
 * creating an entity with valid data and then retrieving it should 
 * return an equivalent entity with all fields intact.
 */
test('product creation and retrieval preserves all data', function () {
    // Run 100 iterations with random data
    for ($i = 0; $i < 100; $i++) {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        $productData = [
            'name' => fake()->words(3, true),
            'slug' => fake()->slug(),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(10000, 1000000),
            'stock' => fake()->numberBetween(0, 100),
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'sku' => fake()->unique()->regexify('[A-Z]{3}[0-9]{5}'),
            'weight' => fake()->numberBetween(100, 5000),
        ];
        
        $product = Product::create($productData);
        $retrieved = Product::find($product->id);
        
        expect($retrieved->name)->toBe($productData['name'])
            ->and($retrieved->slug)->toBe($productData['slug'])
            ->and($retrieved->description)->toBe($productData['description'])
            ->and($retrieved->price)->toBe($productData['price'])
            ->and($retrieved->stock)->toBe($productData['stock'])
            ->and($retrieved->brand_id)->toBe($productData['brand_id'])
            ->and($retrieved->category_id)->toBe($productData['category_id'])
            ->and($retrieved->sku)->toBe($productData['sku'])
            ->and($retrieved->weight)->toBe($productData['weight']);
    }
});
```

### Unit Testing Strategy

**Test Organization:**
```
tests/
├── Feature/
│   └── Admin/
│       ├── DashboardTest.php
│       ├── ProductManagementTest.php
│       ├── OrderManagementTest.php
│       ├── CustomerManagementTest.php
│       ├── BannerManagementTest.php
│       ├── ReviewManagementTest.php
│       ├── PaymentTrackingTest.php
│       ├── AnalyticsTest.php
│       └── SettingsTest.php
└── Unit/
    ├── Services/
    │   ├── ProductServiceTest.php
    │   ├── OrderServiceTest.php
    │   └── AnalyticsServiceTest.php
    └── Repositories/
        ├── ProductRepositoryTest.php
        └── OrderRepositoryTest.php
```

**Example Unit Test:**
```php
<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    /** @test */
    public function admin_can_view_product_list()
    {
        Product::factory()->count(15)->create();
        
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index'));
        
        $response->assertStatus(200)
            ->assertViewIs('admin.products.index')
            ->assertViewHas('products');
    }

    /** @test */
    public function admin_can_create_product_with_valid_data()
    {
        $productData = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test description',
            'price' => 100000,
            'stock' => 10,
            'brand_id' => Brand::factory()->create()->id,
            'category_id' => Category::factory()->create()->id,
            'sku' => 'TEST001',
            'weight' => 500,
        ];
        
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), $productData);
        
        $response->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success');
        
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'sku' => 'TEST001',
        ]);
    }

    /** @test */
    public function product_creation_fails_with_invalid_data()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => '', // Invalid: empty name
                'price' => -100, // Invalid: negative price
            ]);
        
        $response->assertSessionHasErrors(['name', 'price']);
    }

    /** @test */
    public function non_admin_cannot_access_product_management()
    {
        $user = User::factory()->create(); // Regular user
        
        $response = $this->actingAs($user)
            ->get(route('admin.products.index'));
        
        $response->assertStatus(403);
    }
}
```

### Test Coverage Goals

**Minimum Coverage Targets:**
- Controllers: 80%
- Services: 90%
- Repositories: 85%
- Models: 70%

**Critical Paths (100% Coverage Required):**
- Authentication and authorization
- Payment processing
- Order status changes
- Stock management
- Data export/import

### Testing Best Practices

1. **Use Factories:** Create model factories for all entities to generate test data
2. **Database Transactions:** Use RefreshDatabase trait to reset database between tests
3. **Mock External Services:** Mock Midtrans, RajaOngkir, and SMTP in tests
4. **Test Isolation:** Each test should be independent and not rely on other tests
5. **Descriptive Names:** Use descriptive test method names that explain what is being tested
6. **Arrange-Act-Assert:** Follow AAA pattern in all tests
7. **Edge Cases:** Test boundary conditions, empty inputs, and maximum values
8. **Error Scenarios:** Test all error handling paths

### Continuous Integration

**GitHub Actions Workflow:**
```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.1
          extensions: mbstring, pdo, pdo_mysql
          
      - name: Install Dependencies
        run: composer install --prefer-dist --no-progress
        
      - name: Run Tests
        run: php artisan test --coverage --min=80
```

### Manual Testing Checklist

Before deployment, manually verify:
- [ ] All CRUD operations work correctly
- [ ] File uploads work with various formats and sizes
- [ ] Pagination works on all list pages
- [ ] Filters and search return correct results
- [ ] Email notifications are sent correctly
- [ ] Export functions generate correct files
- [ ] Import functions validate and process data correctly
- [ ] Responsive design works on mobile devices
- [ ] All error messages are user-friendly
- [ ] Activity logs are created for important actions

