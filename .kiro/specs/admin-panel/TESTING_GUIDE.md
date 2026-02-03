# Admin Panel Testing Guide - JastipHype

## Prerequisites

### 1. Setup Admin User
Pertama, kita perlu membuat user admin:

```bash
# Buka Laravel Tinker
php artisan tinker
```

Kemudian jalankan:
```php
// Buat admin user baru
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@jastiphype.com';
$user->password = bcrypt('password123');
$user->is_admin = true;
$user->email_verified_at = now();
$user->save();

// Atau update user yang sudah ada
$user = App\Models\User::find(1); // Ganti dengan ID user Anda
$user->is_admin = true;
$user->save();

// Keluar dari tinker
exit
```

### 2. Setup Storage Link
Pastikan storage link sudah dibuat untuk upload gambar:

```bash
php artisan storage:link
```

### 3. Seed Test Data (Optional)
Jika belum ada data, seed database:

```bash
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=BrandSeeder
php artisan db:seed --class=ProductSeeder
```

---

## Testing Steps

### Step 1: Login ke Admin Panel

1. **Jalankan server Laravel**:
   ```bash
   php artisan serve
   ```

2. **Buka browser** dan akses:
   ```
   http://localhost:8000/admin/login
   ```

3. **Login dengan kredensial admin**:
   - Email: `admin@jastiphype.com`
   - Password: `password123`

4. **Verifikasi redirect** ke dashboard:
   ```
   http://localhost:8000/admin/dashboard
   ```

---

### Step 2: Test Dashboard

**URL**: `http://localhost:8000/admin/dashboard`

#### Checklist:
- [ ] Dashboard loads tanpa error
- [ ] Stats cards menampilkan data:
  - Total Revenue
  - Total Orders
  - Total Customers
  - Total Products
  - Page Views
  - Delivered Orders
- [ ] Revenue Overview section tampil
- [ ] Order Status Distribution tampil
- [ ] Visitor Stats tampil
- [ ] Low Stock Alert tampil
- [ ] Recent Orders table tampil

#### Screenshot Areas:
- Stats cards (6 cards di atas)
- Revenue chart placeholder
- Order status distribution
- Visitor stats & Low stock alert (side by side)
- Recent orders table

---

### Step 3: Test Product Management

#### 3.1 Product List
**URL**: `http://localhost:8000/admin/products`

**Checklist**:
- [ ] Product list tampil dengan table
- [ ] Product images tampil
- [ ] Search box berfungsi
- [ ] Filter by category berfungsi
- [ ] Filter by brand berfungsi
- [ ] Filter by status berfungsi
- [ ] Filter by stock status berfungsi
- [ ] Pagination berfungsi
- [ ] Status toggle switch berfungsi (AJAX)
- [ ] Bulk select checkbox berfungsi
- [ ] Action buttons (View, Edit, Delete) tampil

**Test Search**:
1. Ketik nama product di search box
2. Klik tombol search
3. Verifikasi hasil sesuai

**Test Filter**:
1. Pilih category dari dropdown
2. Klik search
3. Verifikasi hanya product dari category tersebut yang tampil

**Test Status Toggle**:
1. Klik toggle switch pada salah satu product
2. Verifikasi status berubah tanpa reload page
3. Refresh page, verifikasi status tetap berubah

#### 3.2 Create Product
**URL**: `http://localhost:8000/admin/products/create`

**Checklist**:
- [ ] Form tampil dengan semua field
- [ ] Category dropdown terisi
- [ ] Brand dropdown terisi
- [ ] Image upload field ada

**Test Create**:
1. Isi form:
   - Name: `Test Product`
   - SKU: `TEST-001`
   - Category: Pilih salah satu
   - Brand: Pilih salah satu
   - Price: `100000`
   - Stock: `50`
   - Weight: `500`
   - Description: `Test description`
   - Active: Checked

2. Upload 2-3 gambar (JPG/PNG, max 2MB)

3. Klik "Create Product"

4. Verifikasi:
   - Redirect ke product list
   - Success message tampil
   - Product baru muncul di list
   - Gambar tersimpan di `storage/app/public/products/`

**Test Validation**:
1. Submit form kosong
2. Verifikasi error messages tampil
3. Isi hanya Name, submit
4. Verifikasi error untuk required fields

#### 3.3 Edit Product
**URL**: `http://localhost:8000/admin/products/{id}/edit`

**Checklist**:
- [ ] Form terisi dengan data existing
- [ ] Existing images tampil
- [ ] Remove image checkbox berfungsi

**Test Edit**:
1. Klik Edit pada salah satu product
2. Ubah beberapa field (name, price, stock)
3. Centang "Remove" pada salah satu gambar
4. Upload 1 gambar baru
5. Klik "Update Product"
6. Verifikasi:
   - Changes tersimpan
   - Gambar lama yang di-remove terhapus
   - Gambar baru muncul

#### 3.4 Delete Product
**Test Delete**:
1. Klik tombol Delete pada salah satu product
2. Verifikasi modal confirmation muncul
3. Klik "Delete"
4. Verifikasi:
   - Product terhapus dari list
   - Success message tampil
   - Gambar terhapus dari storage

#### 3.5 Bulk Delete
**Test Bulk Delete**:
1. Centang checkbox pada 2-3 products
2. Verifikasi tombol "Delete Selected" muncul
3. Klik "Delete Selected"
4. Confirm deletion
5. Verifikasi semua selected products terhapus

---

### Step 4: Test Brand Management

#### 4.1 Brand List
**URL**: `http://localhost:8000/admin/brands`

**Checklist**:
- [ ] Brands tampil dalam card grid
- [ ] Logo tampil (atau placeholder jika tidak ada)
- [ ] Statistics tampil (Products, Revenue)
- [ ] Status badge tampil
- [ ] Action buttons tampil
- [ ] Drag handle tampil

**Test Drag-and-Drop**:
1. Drag salah satu brand card ke posisi lain
2. Drop card
3. Verifikasi order berubah
4. Refresh page
5. Verifikasi order tetap tersimpan

**Test Status Toggle**:
1. Klik toggle icon pada salah satu brand
2. Verifikasi status badge berubah (Active/Inactive)
3. Verifikasi icon berubah
4. Refresh page, verifikasi status tetap berubah

#### 4.2 Create Brand
**URL**: `http://localhost:8000/admin/brands/create`

**Test Create**:
1. Isi form:
   - Name: `Test Brand`
   - Description: `Test brand description`
   - Status: Active
   - Display Order: `0`

2. Upload logo (PNG/JPG, 200x200 - 1000x1000px)

3. Verifikasi preview logo muncul

4. Klik "Create Brand"

5. Verifikasi:
   - Redirect ke brand list
   - Success message tampil
   - Brand baru muncul dengan logo
   - Logo tersimpan di `storage/app/public/brands/`

**Test Logo Validation**:
1. Upload gambar terlalu kecil (< 200x200px)
2. Verifikasi error message
3. Upload gambar terlalu besar (> 1000x1000px)
4. Verifikasi error message
5. Upload file bukan gambar
6. Verifikasi error message

#### 4.3 Edit Brand
**URL**: `http://localhost:8000/admin/brands/{id}/edit`

**Test Edit**:
1. Klik Edit pada salah satu brand
2. Ubah name dan description
3. Centang "Remove current logo"
4. Upload logo baru
5. Klik "Update Brand"
6. Verifikasi:
   - Changes tersimpan
   - Logo lama terhapus
   - Logo baru muncul

#### 4.4 Delete Brand
**Test Delete (Should Fail if Has Products)**:
1. Pilih brand yang memiliki products
2. Klik Delete
3. Verifikasi error message: "Cannot delete brand. It has X products..."

**Test Delete (Should Success if No Products)**:
1. Buat brand baru tanpa products
2. Klik Delete
3. Confirm deletion
4. Verifikasi brand terhapus

---

### Step 5: Test Category Management

#### 5.1 Category List
**URL**: `http://localhost:8000/admin/categories`

**Checklist**:
- [ ] Categories tampil dalam table
- [ ] Hierarchy tampil dengan indentation
- [ ] Arrow indicators untuk child categories
- [ ] Parent badges tampil
- [ ] Product count badges tampil
- [ ] Action buttons tampil

**Verify Hierarchy**:
1. Lihat indentation pada child categories
2. Verifikasi arrow indicators (→) muncul
3. Verifikasi parent badge tampil untuk child categories

#### 5.2 Create Category
**URL**: `http://localhost:8000/admin/categories/create`

**Test Create Top-Level Category**:
1. Isi form:
   - Name: `Test Category`
   - Parent: None (Top Level)
   - Description: `Test description`

2. Klik "Create Category"

3. Verifikasi:
   - Category muncul di list
   - Tidak ada indentation (top-level)
   - Parent column shows "-"

**Test Create Subcategory**:
1. Isi form:
   - Name: `Test Subcategory`
   - Parent: Pilih category yang sudah ada
   - Description: `Test subcategory`

2. Klik "Create Category"

3. Verifikasi:
   - Subcategory muncul di bawah parent
   - Ada indentation
   - Arrow indicator muncul
   - Parent badge tampil

#### 5.3 Edit Category
**URL**: `http://localhost:8000/admin/categories/{id}/edit`

**Test Change Parent**:
1. Edit salah satu category
2. Ubah parent ke category lain
3. Klik "Update Category"
4. Verifikasi hierarchy berubah di list

**Test Circular Reference Prevention**:
1. Edit parent category
2. Coba set child-nya sebagai parent
3. Verifikasi error message muncul
4. Changes tidak tersimpan

#### 5.4 Delete Category
**Test Delete (Should Fail if Has Products)**:
1. Pilih category yang memiliki products
2. Klik Delete
3. Verifikasi error message

**Test Delete (Should Fail if Has Children)**:
1. Pilih category yang memiliki subcategories
2. Klik Delete
3. Verifikasi error message

**Test Delete (Should Success if Empty)**:
1. Buat category baru tanpa products dan children
2. Klik Delete
3. Confirm deletion
4. Verifikasi category terhapus

---

## Common Issues & Solutions

### Issue 1: "Class not found" Error
**Solution**:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Issue 2: Storage Link Not Working
**Solution**:
```bash
php artisan storage:link
```

Verifikasi link dibuat:
```bash
ls -la public/storage
```

### Issue 3: Images Not Uploading
**Check**:
1. Folder `storage/app/public/` writable
2. Storage link exists
3. File size < 2MB
4. File format: JPG, PNG, WEBP

**Fix Permissions** (Linux/Mac):
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Issue 4: 403 Forbidden (Not Admin)
**Solution**:
```bash
php artisan tinker
```
```php
$user = App\Models\User::where('email', 'your@email.com')->first();
$user->is_admin = true;
$user->save();
exit
```

### Issue 5: CSRF Token Mismatch
**Solution**:
```bash
php artisan config:clear
php artisan cache:clear
```

Clear browser cookies dan refresh.

---

## Testing Checklist Summary

### Products ✓
- [ ] List products
- [ ] Search products
- [ ] Filter products
- [ ] Create product with images
- [ ] Edit product
- [ ] Delete product
- [ ] Bulk delete
- [ ] Status toggle (AJAX)
- [ ] Stock update (AJAX)

### Brands ✓
- [ ] List brands
- [ ] Create brand with logo
- [ ] Edit brand
- [ ] Delete brand
- [ ] Drag-and-drop ordering
- [ ] Status toggle (AJAX)
- [ ] Statistics display

### Categories ✓
- [ ] List categories with hierarchy
- [ ] Create top-level category
- [ ] Create subcategory
- [ ] Edit category
- [ ] Change parent
- [ ] Delete category
- [ ] Circular reference prevention

### General ✓
- [ ] Dashboard loads
- [ ] All stats display correctly
- [ ] Navigation works
- [ ] Breadcrumbs work
- [ ] Flash messages display
- [ ] Validation works
- [ ] Error handling works
- [ ] Responsive design (test on mobile)

---

## Browser Testing

Test pada multiple browsers:
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge

---

## Mobile Testing

Test responsive design:
- [ ] iPhone (375px)
- [ ] iPad (768px)
- [ ] Desktop (1920px)

---

## Performance Testing

Check:
- [ ] Page load time < 2 seconds
- [ ] No console errors
- [ ] No 404 errors
- [ ] Images load properly
- [ ] AJAX requests complete quickly

---

## Security Testing

Verify:
- [ ] Non-admin users cannot access admin routes
- [ ] CSRF protection works
- [ ] File upload validation works
- [ ] SQL injection prevented (use Eloquent)
- [ ] XSS prevented (Blade escaping)

---

## Next Steps After Testing

1. **Document Issues**: Catat semua bugs yang ditemukan
2. **Fix Critical Bugs**: Perbaiki bugs yang menghalangi functionality
3. **Optimize**: Improve performance jika ada issues
4. **Continue Development**: Lanjut ke Task 11 (Order Management)

---

## Quick Test Commands

```bash
# Clear all caches
php artisan optimize:clear

# Check routes
php artisan route:list --name=admin

# Check storage link
ls -la public/storage

# Create admin user
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->is_admin = true;
>>> $user->save();
>>> exit

# Run server
php artisan serve
```

---

## Test Data Creation

Jika perlu test data lebih banyak:

```bash
php artisan tinker
```

```php
// Create categories
App\Models\Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
App\Models\Category::create(['name' => 'Clothing', 'slug' => 'clothing']);

// Create brands
App\Models\Brand::create(['name' => 'Nike', 'slug' => 'nike', 'status' => 'active']);
App\Models\Brand::create(['name' => 'Adidas', 'slug' => 'adidas', 'status' => 'active']);

// Create products
App\Models\Product::create([
    'name' => 'Test Product',
    'slug' => 'test-product',
    'sku' => 'TEST-001',
    'category_id' => 1,
    'brand_id' => 1,
    'price' => 100000,
    'stock' => 50,
    'is_active' => true,
    'images' => json_encode([])
]);

exit
```

---

## Conclusion

Ikuti testing guide ini step by step untuk memastikan semua functionality bekerja dengan baik. Catat semua issues yang ditemukan dan prioritaskan fixes berdasarkan severity.

**Happy Testing! 🚀**
