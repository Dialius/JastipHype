# Quick Test Reference - Admin Panel

## 🚀 Quick Start (5 Minutes)

### 1. Setup Test Data
```bash
# Run setup script
php setup-admin-test.php

# Create storage link
php artisan storage:link

# Start server
php artisan serve
```

### 2. Login
- URL: `http://localhost:8000/admin/login`
- Email: `admin@jastiphype.com`
- Password: `password123`

### 3. Test Routes
```
✓ Dashboard:   /admin/dashboard
✓ Products:    /admin/products
✓ Brands:      /admin/brands
✓ Categories:  /admin/categories
```

---

## 📋 Quick Test Checklist

### Dashboard (2 min)
- [ ] Stats cards tampil (6 cards)
- [ ] Charts placeholder tampil
- [ ] Recent orders tampil
- [ ] Low stock alert tampil

### Products (5 min)
- [ ] List tampil dengan images
- [ ] Search berfungsi
- [ ] Create product + upload images
- [ ] Edit product
- [ ] Delete product
- [ ] Status toggle (AJAX)

### Brands (3 min)
- [ ] Card grid tampil
- [ ] Create brand + upload logo
- [ ] Drag-and-drop ordering
- [ ] Edit brand
- [ ] Status toggle (AJAX)

### Categories (3 min)
- [ ] Tree structure tampil
- [ ] Create category
- [ ] Create subcategory
- [ ] Edit category
- [ ] Delete validation

---

## 🐛 Common Issues

### Issue: 403 Forbidden
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->is_admin = true;
>>> $user->save();
>>> exit
```

### Issue: Images Not Showing
```bash
php artisan storage:link
```

### Issue: CSRF Token Mismatch
```bash
php artisan config:clear
php artisan cache:clear
```

### Issue: Class Not Found
```bash
composer dump-autoload
php artisan optimize:clear
```

---

## 🎯 Test Scenarios

### Scenario 1: Create Product with Images
1. Go to `/admin/products/create`
2. Fill form:
   - Name: `Test Product`
   - SKU: `TEST-001`
   - Category: Select any
   - Price: `100000`
   - Stock: `50`
3. Upload 2-3 images
4. Click "Create Product"
5. ✓ Verify product appears in list with images

### Scenario 2: Brand Drag-and-Drop
1. Go to `/admin/brands`
2. Drag a brand card to new position
3. Drop card
4. Refresh page
5. ✓ Verify order persists

### Scenario 3: Category Hierarchy
1. Go to `/admin/categories/create`
2. Create parent: `Electronics`
3. Create child: `Phones` (parent: Electronics)
4. Go to `/admin/categories`
5. ✓ Verify indentation shows hierarchy

### Scenario 4: Deletion Protection
1. Go to `/admin/brands`
2. Try delete brand with products
3. ✓ Verify error message appears
4. Go to `/admin/categories`
5. Try delete category with products
6. ✓ Verify error message appears

---

## 📊 Test Data Summary

After running `setup-admin-test.php`:

**Categories**:
- Streetwear (parent)
  - T-Shirts (child)
  - Hoodies (child)
- Sneakers
- Accessories

**Brands**:
- Supreme
- Nike
- Off-White
- Stüssy

**Products**:
- Supreme Box Logo Hoodie (Stock: 15)
- Nike Air Jordan 1 (Stock: 8)
- Supreme T-Shirt (Stock: 5) ⚠️ Low Stock
- Nike Dunk Low (Stock: 0) ❌ Out of Stock

---

## 🔍 What to Look For

### ✅ Good Signs
- No console errors
- Images load properly
- AJAX updates work without reload
- Validation prevents bad data
- Success messages appear
- Redirects work correctly

### ❌ Red Flags
- 500 errors
- 404 errors
- Images not loading
- AJAX not working
- Validation not working
- No error messages

---

## 📸 Screenshot Checklist

Take screenshots of:
1. Dashboard overview
2. Product list with filters
3. Product create form
4. Brand card grid
5. Brand drag-and-drop
6. Category tree structure
7. Validation errors
8. Success messages

---

## ⏱️ Time Estimates

- **Setup**: 5 minutes
- **Dashboard Test**: 2 minutes
- **Products Test**: 5 minutes
- **Brands Test**: 3 minutes
- **Categories Test**: 3 minutes
- **Total**: ~20 minutes

---

## 🎓 Testing Tips

1. **Test in Order**: Dashboard → Products → Brands → Categories
2. **Use Real Data**: Upload actual images, use realistic names
3. **Test Edge Cases**: Empty forms, invalid data, large files
4. **Check Console**: Open browser DevTools, watch for errors
5. **Test Mobile**: Resize browser to mobile width
6. **Clear Cache**: If something doesn't work, clear cache first

---

## 📞 Need Help?

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

### Check Routes
```bash
php artisan route:list --name=admin
```

### Check Database
```bash
php artisan tinker
>>> App\Models\Product::count()
>>> App\Models\Brand::count()
>>> App\Models\Category::count()
```

---

## ✨ Success Criteria

All tests pass if:
- [ ] All pages load without errors
- [ ] All CRUD operations work
- [ ] Images upload and display
- [ ] Validation prevents bad data
- [ ] AJAX updates work
- [ ] Deletion protection works
- [ ] No console errors
- [ ] Mobile responsive

---

## 🎉 After Testing

1. **Document Issues**: Note any bugs found
2. **Take Screenshots**: Capture working features
3. **Share Results**: Report to team
4. **Continue Development**: Move to next task

---

**Quick Commands**:
```bash
# Setup
php setup-admin-test.php && php artisan storage:link && php artisan serve

# Clear Everything
php artisan optimize:clear

# Check Admin User
php artisan tinker
>>> App\Models\User::where('is_admin', true)->get()
```

**Happy Testing! 🚀**
