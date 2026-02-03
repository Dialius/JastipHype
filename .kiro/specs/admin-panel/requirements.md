# Requirements Document - Admin Panel

## Introduction

Admin Panel adalah sistem manajemen backend untuk platform e-commerce JastipHype yang memungkinkan administrator untuk mengelola seluruh aspek website termasuk produk, pesanan, pengguna, revenue, dan konfigurasi sistem. Panel ini akan menyediakan dashboard analytics, tools manajemen data, dan kontrol penuh atas operasional marketplace streetwear/hype products.

### Struktur Menu Admin Panel

Admin panel akan memiliki struktur menu sebagai berikut:

**1. Dashboard**
   - Overview metrics (Revenue, Orders, Users, Products)
   - Online users counter (real-time)
   - Visitor analytics (daily/monthly unique visitors)
   - Revenue chart (daily/weekly/monthly)
   - Visitor trends chart
   - Recent orders list
   - Low stock alerts
   - Quick stats cards

**2. Products**
   - Product List (dengan search, filter, pagination)
   - Add New Product
   - Edit Product
   - Product Categories
   - Product Brands (full CRUD dengan logo, statistics)
   - Bulk Import/Export

**3. Orders**
   - Order List (dengan filter by status, date, payment method)
   - Order Detail (full information + timeline)
   - Order Status Management
   - Shipping Tracking
   - Export Orders

**4. Customers**
   - Customer List (dengan search dan filter)
   - Customer Detail (profile, order history, spending)
   - Customer Status Management (activate/suspend)
   - Customer Messaging (send message, view history)
   - Bulk Messaging (segmented notifications)
   - Export Customers

**5. Reviews**
   - Review List (dengan filter by rating, status, product)
   - Review Moderation (approve/reject)
   - Review Response
   - Delete Review

**6. Marketing**
   - Banner Management (upload, preview, schedule)
   - Discount Codes (create, edit, analytics)
   - Promotion Management
   - Email Templates

**7. Payments**
   - Payment Transactions List
   - Payment Status Tracking
   - Payment Method Analytics
   - Midtrans Integration Status
   - Manual Verification

**8. Shipping**
   - Courier Management (enable/disable)
   - Shipping Settings (origin, zones)
   - Free Shipping Rules
   - Shipping Analytics

**9. Analytics & Reports**
   - Revenue Analytics (trends, breakdown)
   - Sales Reports
   - Product Performance
   - Customer Analytics
   - Payment Method Distribution
   - Export Reports (PDF/Excel)

**10. System**
   - Activity Logs
   - Error Logs
   - System Health Monitor
   - Database Statistics
   - Service Status (Midtrans, RajaOngkir, SMTP)
   - Online Users Tracking
   - Visitor Analytics (daily/monthly trends)

**11. Settings**
   - General Settings (site info, logo, contact)
   - Payment Settings (Midtrans configuration)
   - Shipping Settings (RajaOngkir configuration)
   - Email Settings (SMTP configuration)
   - Notification Settings (enable/disable per event)
   - Admin Users Management

### Fitur-Fitur Utama yang Akan Diimplementasikan

**Dashboard & Monitoring:**
- Real-time metrics dan KPI
- Online users counter (real-time tracking)
- Visitor analytics (daily/monthly unique visitors)
- Visitor trends visualization
- Interactive charts dan graphs
- Quick action buttons
- Alert notifications untuk stok rendah, pesanan baru, dll

**Product Management:**
- CRUD operations lengkap
- Multiple image upload dengan preview
- Stock management dengan auto-update status
- Bulk operations (import/export CSV/Excel)
- Category dan brand management dengan hierarchy
- Brand management dengan logo upload dan statistics
- Brand revenue tracking
- Product variants (size, color) jika diperlukan

**Order Management:**
- Comprehensive order list dengan advanced filtering
- Detailed order view dengan timeline
- Status update dengan email notification otomatis
- Shipping tracking integration
- Print invoice/packing slip
- Order cancellation dengan auto stock return

**Customer Management:**
- Customer database lengkap
- Customer segmentation (by spending, activity)
- Customer lifetime value tracking
- Account status management
- Customer communication history
- Direct messaging to customers
- Bulk messaging dengan segmentation
- Message threading dan conversation view
- Email notifications untuk messages

**Review & Content Moderation:**
- Review approval workflow
- Spam detection
- Admin response capability
- Rating analytics

**Marketing Tools:**
- Banner management dengan live preview
- Drag-and-drop banner ordering
- Banner scheduling (start/end date)
- Discount code generator
- Promotion campaign tracking
- Email template customization

**Payment & Financial:**
- Real-time payment status dari Midtrans
- Payment reconciliation
- Revenue analytics dengan multiple views
- Commission/fee tracking
- Financial reports export

**Shipping Management:**
- Courier selection dan configuration
- Shipping cost calculator
- Free shipping rules
- Shipping zone management
- Delivery performance tracking

**System Administration:**
- Comprehensive activity logging
- Error monitoring dan debugging
- System health checks
- Service integration status
- Database backup tools
- Admin user roles dan permissions
- Online users tracking (real-time)
- Visitor analytics dan trends
- Automatic log cleanup

**Reporting & Analytics:**
- Customizable date range reports
- Multiple export formats (PDF, Excel, CSV)
- Scheduled reports (daily/weekly/monthly)
- Visual analytics dengan charts
- Comparative analysis (period over period)

### Template dan UI/UX

Admin panel akan menggunakan template modern dan responsive dengan fitur:
- Sidebar navigation dengan collapsible menu
- Top navbar dengan user profile dan notifications
- Breadcrumb navigation
- Data tables dengan sorting, searching, pagination
- Modal dialogs untuk quick actions
- Toast notifications untuk feedback
- Loading states dan skeleton screens
- Responsive design untuk mobile/tablet access
- Dark mode support (optional)
- Customizable dashboard widgets

## Glossary

- **Admin_Panel**: Sistem backend untuk administrator mengelola platform JastipHype
- **Dashboard**: Halaman utama yang menampilkan metrics dan analytics penting
- **Revenue_Analytics**: Sistem pelacakan dan analisis pendapatan dari transaksi
- **Order_Management**: Sistem untuk mengelola pesanan pelanggan
- **User_Management**: Sistem untuk mengelola data dan status pengguna
- **Product_Management**: Sistem untuk mengelola katalog produk
- **Payment_Tracker**: Sistem untuk memantau status pembayaran
- **Moderation_System**: Sistem untuk moderasi konten seperti review
- **Administrator**: Pengguna dengan hak akses penuh ke admin panel
- **Midtrans**: Payment gateway yang digunakan untuk pemrosesan pembayaran
- **RajaOngkir**: Layanan integrasi pengiriman
- **CRUD**: Create, Read, Update, Delete operations

## Requirements

### Requirement 1: Dashboard dan Analytics

**User Story:** Sebagai administrator, saya ingin melihat dashboard dengan metrics penting, sehingga saya dapat memantau performa bisnis secara real-time.

#### Acceptance Criteria

1. WHEN administrator mengakses admin panel, THE Dashboard SHALL menampilkan total revenue dalam periode yang dipilih
2. WHEN dashboard dimuat, THE Dashboard SHALL menampilkan jumlah total pesanan dengan breakdown berdasarkan status (pending, processing, completed, cancelled)
3. WHEN dashboard dimuat, THE Dashboard SHALL menampilkan jumlah total pengguna terdaftar dan pengguna aktif
4. WHEN dashboard dimuat, THE Dashboard SHALL menampilkan jumlah pengguna yang sedang online (aktif dalam 5 menit terakhir)
5. WHEN dashboard dimuat, THE Dashboard SHALL menampilkan jumlah pengunjung unik hari ini dan bulan ini
6. WHEN dashboard dimuat, THE Dashboard SHALL menampilkan jumlah total produk dengan breakdown berdasarkan status (active, out of stock, draft)
7. WHEN administrator memilih periode waktu, THE Revenue_Analytics SHALL menampilkan grafik revenue dalam periode tersebut
8. WHEN dashboard dimuat, THE Dashboard SHALL menampilkan grafik tren pengunjung (harian/bulanan)
9. WHEN dashboard dimuat, THE Dashboard SHALL menampilkan daftar pesanan terbaru (minimal 10 pesanan terakhir)
10. WHEN dashboard dimuat, THE Dashboard SHALL menampilkan produk dengan stok rendah (threshold dapat dikonfigurasi)

### Requirement 2: Product Management

**User Story:** Sebagai administrator, saya ingin mengelola produk, sehingga saya dapat mengontrol katalog yang ditampilkan kepada pelanggan.

#### Acceptance Criteria

1. WHEN administrator mengakses product management, THE Product_Management SHALL menampilkan daftar semua produk dengan pagination
2. WHEN administrator mencari produk, THE Product_Management SHALL memfilter produk berdasarkan nama, SKU, brand, atau category
3. WHEN administrator membuat produk baru, THE Product_Management SHALL menyimpan data produk dengan semua field yang diperlukan (nama, deskripsi, harga, stok, gambar, brand, category)
4. WHEN administrator mengedit produk, THE Product_Management SHALL memperbarui data produk dan menyimpan history perubahan
5. WHEN administrator menghapus produk, THE Product_Management SHALL melakukan soft delete dan mempertahankan data untuk referensi pesanan
6. WHEN administrator mengupload gambar produk, THE Product_Management SHALL memvalidasi format file (jpg, png, webp) dan ukuran maksimal 5MB
7. WHEN administrator mengatur stok produk, THE Product_Management SHALL memperbarui status availability secara otomatis (in stock/out of stock)
8. WHEN administrator mengaktifkan/menonaktifkan produk, THE Product_Management SHALL mengubah visibility produk di frontend

### Requirement 3: Order Management

**User Story:** Sebagai administrator, saya ingin mengelola pesanan, sehingga saya dapat memproses dan melacak status pesanan pelanggan.

#### Acceptance Criteria

1. WHEN administrator mengakses order management, THE Order_Management SHALL menampilkan daftar semua pesanan dengan pagination
2. WHEN administrator memfilter pesanan, THE Order_Management SHALL menampilkan pesanan berdasarkan status, tanggal, atau payment method
3. WHEN administrator melihat detail pesanan, THE Order_Management SHALL menampilkan informasi lengkap (items, customer, shipping, payment, timeline)
4. WHEN administrator mengubah status pesanan, THE Order_Management SHALL memperbarui status dan mengirim notifikasi email kepada pelanggan
5. WHEN pesanan dibatalkan, THE Order_Management SHALL mengembalikan stok produk secara otomatis
6. WHEN administrator melihat pesanan, THE Payment_Tracker SHALL menampilkan status pembayaran dari Midtrans
7. WHEN administrator mengakses detail pesanan, THE Order_Management SHALL menampilkan tracking number dan status pengiriman dari RajaOngkir
8. WHEN administrator mengexport data pesanan, THE Order_Management SHALL menghasilkan file CSV atau Excel dengan data pesanan dalam periode yang dipilih

### Requirement 4: User Management

**User Story:** Sebagai administrator, saya ingin mengelola pengguna, sehingga saya dapat mengontrol akses dan aktivitas pengguna di platform.

#### Acceptance Criteria

1. WHEN administrator mengakses user management, THE User_Management SHALL menampilkan daftar semua pengguna dengan pagination
2. WHEN administrator mencari pengguna, THE User_Management SHALL memfilter pengguna berdasarkan nama, email, atau status
3. WHEN administrator melihat detail pengguna, THE User_Management SHALL menampilkan informasi profil, history pesanan, dan aktivitas
4. WHEN administrator mengedit data pengguna, THE User_Management SHALL memperbarui informasi pengguna
5. WHEN administrator menonaktifkan pengguna, THE User_Management SHALL mencegah pengguna tersebut login dan melakukan transaksi
6. WHEN administrator mengaktifkan kembali pengguna, THE User_Management SHALL mengembalikan akses pengguna
7. WHEN administrator melihat pengguna, THE User_Management SHALL menampilkan total spending dan jumlah pesanan dari pengguna tersebut
8. WHEN administrator mengirim pesan ke pengguna, THE User_Management SHALL menyimpan pesan dan mengirim notifikasi email kepada pengguna
9. WHEN administrator melihat history pesan dengan pengguna, THE User_Management SHALL menampilkan semua pesan dalam format thread/conversation
10. WHEN administrator mengirim notifikasi bulk, THE User_Management SHALL memungkinkan pengiriman pesan ke multiple pengguna berdasarkan segmentasi

### Requirement 4.5: Brand Management (Extended)

**User Story:** Sebagai administrator, saya ingin mengelola brand produk secara detail, sehingga saya dapat mengorganisir katalog berdasarkan merek.

#### Acceptance Criteria

1. WHEN administrator membuat brand baru, THE Product_Management SHALL menyimpan data brand dengan nama, slug, deskripsi, dan logo
2. WHEN administrator mengupload logo brand, THE Product_Management SHALL memvalidasi dimensi (min 200x200px, max 1000x1000px) dan ukuran maksimal 2MB
3. WHEN administrator mengedit brand, THE Product_Management SHALL memperbarui data brand dan auto-generate slug jika nama berubah
4. WHEN administrator menghapus brand yang memiliki produk, THE Product_Management SHALL mencegah penghapusan dan menampilkan jumlah produk terkait
5. WHEN administrator melihat brand list, THE Product_Management SHALL menampilkan jumlah produk dan total revenue untuk setiap brand
6. WHEN administrator mengatur display order brand, THE Product_Management SHALL menyimpan urutan dan menampilkan brand sesuai urutan di frontend
7. WHEN administrator mengaktifkan/menonaktifkan brand, THE Product_Management SHALL mengubah status brand tanpa mempengaruhi produk yang sudah ada

### Requirement 5: Brand dan Category Management

**User Story:** Sebagai administrator, saya ingin mengelola brand dan category, sehingga saya dapat mengorganisir katalog produk dengan baik.

#### Acceptance Criteria

1. WHEN administrator membuat brand baru, THE Product_Management SHALL menyimpan data brand dengan nama, logo, dan deskripsi
2. WHEN administrator mengedit brand, THE Product_Management SHALL memperbarui data brand
3. WHEN administrator menghapus brand yang memiliki produk, THE Product_Management SHALL mencegah penghapusan dan menampilkan peringatan
4. WHEN administrator membuat category baru, THE Product_Management SHALL menyimpan data category dengan nama, slug, dan parent category (jika ada)
5. WHEN administrator mengedit category, THE Product_Management SHALL memperbarui data category
6. WHEN administrator menghapus category yang memiliki produk, THE Product_Management SHALL mencegah penghapusan dan menampilkan peringatan
7. WHEN administrator mengatur hierarchy category, THE Product_Management SHALL mendukung nested categories (parent-child relationship)

### Requirement 6: Review Management

**User Story:** Sebagai administrator, saya ingin mengelola review produk, sehingga saya dapat memoderasi konten dan menjaga kualitas feedback pelanggan.

#### Acceptance Criteria

1. WHEN administrator mengakses review management, THE Moderation_System SHALL menampilkan daftar semua review dengan pagination
2. WHEN administrator memfilter review, THE Moderation_System SHALL menampilkan review berdasarkan rating, status, atau produk
3. WHEN administrator melihat detail review, THE Moderation_System SHALL menampilkan konten review, rating, pengguna, produk, dan tanggal
4. WHEN administrator menyetujui review, THE Moderation_System SHALL mempublikasikan review di halaman produk
5. WHEN administrator menolak review, THE Moderation_System SHALL menyembunyikan review dan mencatat alasan penolakan
6. WHEN administrator menghapus review yang tidak pantas, THE Moderation_System SHALL melakukan soft delete dan menyimpan log
7. WHEN administrator merespons review, THE Moderation_System SHALL menyimpan response dan menampilkannya di halaman produk

### Requirement 7: Revenue Analytics dan Reports

**User Story:** Sebagai administrator, saya ingin melihat analytics revenue dan generate reports, sehingga saya dapat menganalisis performa finansial bisnis.

#### Acceptance Criteria

1. WHEN administrator mengakses revenue analytics, THE Revenue_Analytics SHALL menampilkan total revenue dalam periode yang dipilih (hari ini, minggu ini, bulan ini, custom range)
2. WHEN administrator melihat revenue breakdown, THE Revenue_Analytics SHALL menampilkan revenue berdasarkan payment method
3. WHEN administrator melihat revenue trends, THE Revenue_Analytics SHALL menampilkan grafik revenue harian/mingguan/bulanan
4. WHEN administrator melihat product performance, THE Revenue_Analytics SHALL menampilkan top selling products berdasarkan revenue dan quantity
5. WHEN administrator melihat payment analytics, THE Payment_Tracker SHALL menampilkan breakdown status pembayaran (success, pending, failed)
6. WHEN administrator mengexport report, THE Revenue_Analytics SHALL menghasilkan file PDF atau Excel dengan data analytics lengkap
7. WHEN administrator melihat commission analytics, THE Revenue_Analytics SHALL menampilkan total commission/fee yang dibayarkan ke payment gateway

### Requirement 8: Payment Tracking

**User Story:** Sebagai administrator, saya ingin melacak status pembayaran, sehingga saya dapat memantau transaksi dan menangani masalah pembayaran.

#### Acceptance Criteria

1. WHEN administrator mengakses payment tracker, THE Payment_Tracker SHALL menampilkan daftar semua transaksi pembayaran dengan pagination
2. WHEN administrator memfilter pembayaran, THE Payment_Tracker SHALL menampilkan transaksi berdasarkan status, payment method, atau tanggal
3. WHEN administrator melihat detail pembayaran, THE Payment_Tracker SHALL menampilkan informasi lengkap dari Midtrans (transaction_id, status, amount, payment_type, timestamps)
4. WHEN pembayaran gagal, THE Payment_Tracker SHALL menampilkan alasan kegagalan dari Midtrans
5. WHEN administrator melakukan manual verification, THE Payment_Tracker SHALL memungkinkan update status pembayaran dengan konfirmasi
6. WHEN administrator melihat payment method analytics, THE Payment_Tracker SHALL menampilkan distribusi penggunaan payment method (bank transfer, e-wallet, QRIS, dll)

### Requirement 9: Settings dan Configuration

**User Story:** Sebagai administrator, saya ingin mengatur konfigurasi sistem, sehingga saya dapat menyesuaikan pengaturan platform sesuai kebutuhan.

#### Acceptance Criteria

1. WHEN administrator mengakses settings, THE Admin_Panel SHALL menampilkan konfigurasi umum (site name, logo, contact info, social media)
2. WHEN administrator mengubah settings, THE Admin_Panel SHALL memvalidasi input dan menyimpan perubahan
3. WHEN administrator mengatur payment settings, THE Admin_Panel SHALL memungkinkan konfigurasi Midtrans (server key, client key, environment)
4. WHEN administrator mengatur shipping settings, THE Admin_Panel SHALL memungkinkan konfigurasi RajaOngkir (API key, origin city)
5. WHEN administrator mengatur email settings, THE Admin_Panel SHALL memungkinkan konfigurasi SMTP (host, port, username, password, encryption)
6. WHEN administrator mengatur notification settings, THE Admin_Panel SHALL memungkinkan enable/disable notifikasi email untuk berbagai events
7. WHEN administrator mengatur low stock threshold, THE Admin_Panel SHALL menyimpan nilai threshold untuk alert stok rendah

### Requirement 10: Authentication dan Authorization

**User Story:** Sebagai sistem, saya ingin memastikan hanya administrator yang terauthorisasi dapat mengakses admin panel, sehingga data dan operasional platform terlindungi.

#### Acceptance Criteria

1. WHEN pengguna mengakses admin panel, THE Admin_Panel SHALL memvalidasi bahwa pengguna memiliki role administrator
2. WHEN pengguna non-admin mencoba mengakses admin panel, THE Admin_Panel SHALL menolak akses dan redirect ke halaman unauthorized
3. WHEN administrator login, THE Admin_Panel SHALL membuat session dengan expiry time yang dapat dikonfigurasi
4. WHEN session administrator expired, THE Admin_Panel SHALL redirect ke halaman login
5. WHEN administrator logout, THE Admin_Panel SHALL menghapus session dan redirect ke halaman login
6. WHERE role-based access control diimplementasikan, THE Admin_Panel SHALL membatasi akses fitur berdasarkan permission level
7. WHEN administrator melakukan aksi penting (delete, status change), THE Admin_Panel SHALL mencatat log aktivitas dengan timestamp dan user info

### Requirement 11: UI/UX dan Template Integration

**User Story:** Sebagai administrator, saya ingin menggunakan interface yang user-friendly dan modern, sehingga saya dapat bekerja dengan efisien.

#### Acceptance Criteria

1. THE Admin_Panel SHALL menggunakan template admin yang responsive dan modern (Bootstrap/Tailwind based)
2. WHEN administrator mengakses admin panel dari mobile device, THE Admin_Panel SHALL menampilkan layout yang responsive
3. THE Admin_Panel SHALL menyediakan sidebar navigation dengan menu yang terorganisir berdasarkan kategori
4. WHEN administrator melakukan aksi (create, update, delete), THE Admin_Panel SHALL menampilkan feedback visual (success message, error message, loading state)
5. THE Admin_Panel SHALL menggunakan data tables dengan fitur sorting, searching, dan pagination
6. WHEN administrator mengisi form, THE Admin_Panel SHALL menampilkan validasi real-time dan error messages yang jelas
7. THE Admin_Panel SHALL menyediakan breadcrumb navigation untuk memudahkan navigasi antar halaman

### Requirement 12: Banner Management

**User Story:** Sebagai administrator, saya ingin mengelola banner homepage, sehingga saya dapat mengontrol konten promosi yang ditampilkan kepada pengunjung.

#### Acceptance Criteria

1. WHEN administrator mengakses banner management, THE Admin_Panel SHALL menampilkan daftar semua banner dengan preview thumbnail
2. WHEN administrator membuat banner baru, THE Admin_Panel SHALL memungkinkan upload gambar dengan spesifikasi ukuran yang ditampilkan (contoh: 1920x600px untuk hero banner)
3. WHEN administrator mengupload gambar banner, THE Admin_Panel SHALL memvalidasi dimensi gambar sesuai dengan tipe banner yang dipilih
4. WHEN administrator mengupload gambar banner, THE Admin_Panel SHALL memvalidasi format file (jpg, png, webp) dan ukuran maksimal 2MB
5. WHEN administrator membuat/edit banner, THE Admin_Panel SHALL menampilkan live preview dari banner yang akan ditampilkan
6. WHEN administrator mengatur banner, THE Admin_Panel SHALL memungkinkan pengaturan link/URL tujuan ketika banner diklik
7. WHEN administrator mengatur banner, THE Admin_Panel SHALL memungkinkan pengaturan urutan tampilan banner (priority/order)
8. WHEN administrator mengaktifkan/menonaktifkan banner, THE Admin_Panel SHALL mengubah visibility banner di homepage
9. WHEN administrator mengatur banner, THE Admin_Panel SHALL memungkinkan penjadwalan banner (start date dan end date)
10. WHEN administrator melihat banner list, THE Admin_Panel SHALL menampilkan status banner (active, scheduled, expired, inactive)

### Requirement 13: System Monitoring dan Activity Logs

**User Story:** Sebagai administrator, saya ingin memantau aktivitas sistem dan melihat log, sehingga saya dapat mengidentifikasi masalah dan melacak perubahan penting.

#### Acceptance Criteria

1. WHEN administrator mengakses activity logs, THE Admin_Panel SHALL menampilkan log semua aktivitas penting (login, create, update, delete) dengan pagination
2. WHEN administrator memfilter logs, THE Admin_Panel SHALL menampilkan logs berdasarkan user, action type, module, atau tanggal
3. WHEN administrator melihat detail log, THE Admin_Panel SHALL menampilkan informasi lengkap (user, action, timestamp, IP address, old value, new value)
4. WHEN administrator mengakses system monitoring, THE Admin_Panel SHALL menampilkan status koneksi ke service eksternal (Midtrans, RajaOngkir, SMTP)
5. WHEN administrator melihat error logs, THE Admin_Panel SHALL menampilkan daftar error yang terjadi di sistem dengan detail stack trace
6. WHEN administrator mengakses database statistics, THE Admin_Panel SHALL menampilkan jumlah records di setiap tabel utama
7. WHEN administrator melihat system health, THE Admin_Panel SHALL menampilkan disk usage, database size, dan cache status

### Requirement 14: Notification Management

**User Story:** Sebagai administrator, saya ingin mengelola notifikasi sistem, sehingga saya dapat mengontrol komunikasi dengan pengguna.

#### Acceptance Criteria

1. WHEN administrator mengakses notification management, THE Admin_Panel SHALL menampilkan daftar template email notifikasi
2. WHEN administrator mengedit template email, THE Admin_Panel SHALL memungkinkan customisasi subject dan body dengan variable placeholders
3. WHEN administrator melihat preview template, THE Admin_Panel SHALL menampilkan preview email dengan sample data
4. WHEN administrator mengatur notification settings, THE Admin_Panel SHALL memungkinkan enable/disable notifikasi untuk setiap event (order placed, payment confirmed, order shipped, dll)
5. WHEN administrator melihat notification history, THE Admin_Panel SHALL menampilkan log email yang telah dikirim dengan status (sent, failed, pending)
6. WHEN email gagal terkirim, THE Admin_Panel SHALL menampilkan error message dan memungkinkan retry
7. WHEN administrator mengatur notification, THE Admin_Panel SHALL memungkinkan pengaturan CC/BCC untuk notifikasi tertentu (contoh: order notification ke admin email)

### Requirement 15: Shipping Management

**User Story:** Sebagai administrator, saya ingin mengelola pengaturan pengiriman, sehingga saya dapat mengontrol opsi dan biaya pengiriman.

#### Acceptance Criteria

1. WHEN administrator mengakses shipping management, THE Admin_Panel SHALL menampilkan daftar courier yang tersedia dari RajaOngkir
2. WHEN administrator mengaktifkan/menonaktifkan courier, THE Admin_Panel SHALL mengubah availability courier di checkout page
3. WHEN administrator mengatur origin address, THE Admin_Panel SHALL menyimpan konfigurasi alamat pengiriman default (city, postal code)
4. WHEN administrator melihat shipping costs, THE Admin_Panel SHALL menampilkan estimasi biaya pengiriman untuk berbagai courier dan service
5. WHEN administrator mengatur shipping settings, THE Admin_Panel SHALL memungkinkan pengaturan free shipping threshold (minimum order amount)
6. WHEN administrator mengatur shipping zones, THE Admin_Panel SHALL memungkinkan pengaturan area pengiriman yang dilayani
7. WHEN administrator melihat shipping analytics, THE Admin_Panel SHALL menampilkan distribusi penggunaan courier dan average shipping cost

### Requirement 16: Discount dan Promotion Management

**User Story:** Sebagai administrator, saya ingin mengelola discount dan promosi, sehingga saya dapat menjalankan campaign marketing.

#### Acceptance Criteria

1. WHEN administrator membuat discount code, THE Admin_Panel SHALL menyimpan kode dengan tipe (percentage, fixed amount), nilai, dan batasan penggunaan
2. WHEN administrator mengatur discount, THE Admin_Panel SHALL memungkinkan pengaturan minimum order amount untuk discount
3. WHEN administrator mengatur discount, THE Admin_Panel SHALL memungkinkan pengaturan periode validity (start date, end date)
4. WHEN administrator mengatur discount, THE Admin_Panel SHALL memungkinkan pengaturan usage limit (total uses, uses per customer)
5. WHEN administrator mengatur discount, THE Admin_Panel SHALL memungkinkan pengaturan applicable products/categories
6. WHEN administrator melihat discount analytics, THE Admin_Panel SHALL menampilkan jumlah penggunaan dan total discount amount
7. WHEN administrator mengaktifkan/menonaktifkan discount, THE Admin_Panel SHALL mengubah availability discount code di checkout
8. WHEN discount code digunakan melebihi limit, THE Admin_Panel SHALL otomatis menonaktifkan discount code

### Requirement 18: Online Users dan Visitor Tracking

**User Story:** Sebagai administrator, saya ingin melihat statistik pengunjung dan pengguna online, sehingga saya dapat memantau traffic website secara real-time.

#### Acceptance Criteria

1. WHEN administrator mengakses dashboard, THE Admin_Panel SHALL menampilkan jumlah pengguna yang sedang online (aktif dalam 5 menit terakhir)
2. WHEN pengguna melakukan aktivitas di website, THE System SHALL memperbarui status online di cache dengan TTL 5 menit
3. WHEN administrator melihat visitor analytics, THE Admin_Panel SHALL menampilkan jumlah pengunjung unik hari ini berdasarkan IP address
4. WHEN administrator melihat visitor analytics, THE Admin_Panel SHALL menampilkan jumlah pengunjung unik bulan ini berdasarkan IP address
5. WHEN administrator melihat visitor trends, THE Admin_Panel SHALL menampilkan grafik pengunjung harian untuk 30 hari terakhir
6. WHEN administrator melihat visitor trends, THE Admin_Panel SHALL menampilkan grafik pengunjung bulanan untuk 12 bulan terakhir
7. WHEN sistem mencatat visitor log, THE System SHALL menyimpan IP address, user agent, page URL, dan timestamp
8. WHEN visitor log berusia lebih dari 90 hari, THE System SHALL otomatis menghapus log tersebut untuk menghemat storage

### Requirement 19: Customer Interaction dan Messaging

**User Story:** Sebagai administrator, saya ingin berkomunikasi dengan customer, sehingga saya dapat memberikan support dan menjawab pertanyaan mereka.

#### Acceptance Criteria

1. WHEN administrator mengirim pesan ke customer, THE Admin_Panel SHALL menyimpan pesan di database dan mengirim notifikasi email ke customer
2. WHEN administrator melihat customer detail, THE Admin_Panel SHALL menampilkan history pesan dengan customer dalam format conversation/thread
3. WHEN customer membalas pesan, THE Admin_Panel SHALL menampilkan notifikasi ke administrator
4. WHEN administrator mengirim bulk message, THE Admin_Panel SHALL memungkinkan pemilihan customer berdasarkan segmentasi (total spending, order count, last order date)
5. WHEN administrator melihat message list, THE Admin_Panel SHALL menampilkan status pesan (sent, read, replied)
6. WHEN customer membaca pesan dari admin, THE System SHALL otomatis mengupdate status pesan menjadi "read"
7. WHEN administrator membalas pesan customer, THE System SHALL menyimpan reply dengan parent_id untuk threading

### Requirement 20: Data Export dan Import

**User Story:** Sebagai administrator, saya ingin export dan import data, sehingga saya dapat melakukan backup dan bulk operations.

#### Acceptance Criteria

1. WHEN administrator mengexport data produk, THE Product_Management SHALL menghasilkan file CSV/Excel dengan semua field produk
2. WHEN administrator mengimport data produk, THE Product_Management SHALL memvalidasi format file dan data sebelum menyimpan
3. WHEN import data gagal, THE Product_Management SHALL menampilkan error report dengan detail baris yang bermasalah
4. WHEN administrator mengexport data pesanan, THE Order_Management SHALL menghasilkan file dengan data pesanan lengkap dalam periode yang dipilih
5. WHEN administrator mengexport data pengguna, THE User_Management SHALL menghasilkan file dengan data pengguna (excluding sensitive data seperti password)
