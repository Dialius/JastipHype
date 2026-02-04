# 🖼️ Image Helper Quick Reference Guide

**For Developers:** Use this guide when working with images in the application.

---

## 📚 Available Helper Functions

### 1. Product Images
```php
// Get product's primary image (front view by default)
{{ product_image_url($product) }}

// Get specific image type
{{ product_image_url($product, 'back') }}
{{ product_image_url($product, 'side') }}
{{ product_image_url($product, 'detail') }}
```

**Usage:**
- Product cards
- Product detail pages
- Cart items
- Order history
- Wishlist items

---

### 2. Category Images
```php
// Get category image
{{ category_image_url($category) }}
```

**Usage:**
- Category listings
- Category cards
- Navigation menus
- Home page category section

---

### 3. Brand Logos
```php
// Get brand logo
{{ brand_logo_url($brand) }}
```

**Usage:**
- Brand listings
- Brand cards
- Product detail pages
- Brand showcase sections

---

### 4. Banner Images
```php
// Get banner image (handles both direct images and product-linked banners)
{{ banner_image_url($banner) }}
```

**Usage:**
- Hero carousels
- Promotional banners
- Featured sections

---

### 5. Generic Images
```php
// Get any image URL with proper handling
{{ image_url($path) }}
{{ image_url($path, 'public') }}  // Specify disk
{{ image_url($path, 's3') }}      // For S3 storage
```

**Usage:**
- Review images
- User avatars
- Custom uploads
- Any other images

---

## ✅ DO's

### ✅ Always Use Helper Functions
```php
<!-- ✅ CORRECT -->
<img src="{{ product_image_url($product) }}" alt="{{ $product->name }}">
<img src="{{ category_image_url($category) }}" alt="{{ $category->name }}">
<img src="{{ brand_logo_url($brand) }}" alt="{{ $brand->name }}">
<img src="{{ banner_image_url($banner) }}" alt="{{ $banner->title }}">
```

### ✅ Use Components When Available
```php
<!-- ✅ CORRECT - Use product card component -->
<x-product-card :product="$product" />

<!-- ✅ CORRECT - Use product gallery component -->
<x-product-gallery :product="$product" />
```

### ✅ Eager Load Relationships
```php
// ✅ CORRECT - Load images with products
$products = Product::with(['brand', 'productImages'])->get();

// ✅ CORRECT - Load images with categories
$categories = Category::with('image')->get();
```

### ✅ Use Pagination
```php
// ✅ CORRECT - Paginate large lists
$products = Product::with(['brand', 'productImages'])
    ->paginate(20);
```

---

## ❌ DON'Ts

### ❌ Never Use Direct Accessor
```php
<!-- ❌ WRONG - Don't use accessor directly -->
<img src="{{ $product->image_url }}">

<!-- ✅ CORRECT - Use helper function -->
<img src="{{ product_image_url($product) }}">
```

### ❌ Never Call ImageHelper Directly in Views
```php
<!-- ❌ WRONG - Don't call class directly -->
<img src="{{ ImageHelper::getImageUrl($path) }}">

<!-- ✅ CORRECT - Use helper function -->
<img src="{{ image_url($path) }}">
```

### ❌ Never Use Direct Storage Paths
```php
<!-- ❌ WRONG - Don't use direct paths -->
<img src="{{ asset('storage/' . $product->image) }}">

<!-- ✅ CORRECT - Use helper function -->
<img src="{{ product_image_url($product) }}">
```

### ❌ Never Load All Records Without Pagination
```php
// ❌ WRONG - Loads everything
$products = Product::all();

// ✅ CORRECT - Use pagination
$products = Product::paginate(20);
```

---

## 🎯 Common Patterns

### Pattern 1: Product Card
```php
<div class="product-card">
    <a href="{{ route('products.show', $product->slug) }}">
        <img src="{{ product_image_url($product) }}" 
             alt="{{ $product->name }}">
        <h3>{{ $product->name }}</h3>
        <p>Rp {{ number_format($product->price, 0, ',', '.') }}</p>
    </a>
</div>
```

### Pattern 2: Product Gallery
```php
@foreach($product->productImages as $image)
    <img src="{{ image_url($image->image_path) }}" 
         alt="{{ $product->name }}">
@endforeach
```

### Pattern 3: Category Grid
```php
@foreach($categories as $category)
    <a href="{{ route('products.index', ['category' => $category->slug]) }}">
        <img src="{{ category_image_url($category) }}" 
             alt="{{ $category->name }}">
        <h3>{{ $category->name }}</h3>
    </a>
@endforeach
```

### Pattern 4: Brand Showcase
```php
@foreach($brands as $brand)
    <div class="brand-card">
        <img src="{{ brand_logo_url($brand) }}" 
             alt="{{ $brand->name }}">
        <h3>{{ $brand->name }}</h3>
    </div>
@endforeach
```

---

## 🔧 Controller Best Practices

### Loading Products
```php
// ✅ CORRECT - Eager load relationships
public function index()
{
    $products = Product::with(['brand', 'productImages', 'category'])
        ->paginate(20);
    
    return view('products.index', compact('products'));
}
```

### Loading Single Product
```php
// ✅ CORRECT - Load all necessary relationships
public function show($slug)
{
    $product = Product::with([
        'brand',
        'category',
        'productImages',
        'reviews.user'
    ])->where('slug', $slug)->firstOrFail();
    
    return view('products.show', compact('product'));
}
```

### API Endpoints
```php
// ✅ CORRECT - Add image_url to JSON response
public function search(Request $request)
{
    $products = Product::where('name', 'LIKE', "%{$request->q}%")
        ->with(['brand', 'productImages'])
        ->get()
        ->map(function($product) {
            $product->image_url = product_image_url($product);
            return $product;
        });
    
    return response()->json($products);
}
```

---

## 🎨 Frontend JavaScript

### Using Image URLs in Alpine.js
```html
<div x-data="{ products: [] }">
    <template x-for="product in products">
        <img :src="product.image_url || '/images/placeholder-product.svg'" 
             :alt="product.name">
    </template>
</div>
```

### Using Image URLs in Fetch
```javascript
fetch('/api/products')
    .then(res => res.json())
    .then(products => {
        products.forEach(product => {
            // product.image_url is already set by API
            console.log(product.image_url);
        });
    });
```

---

## 🛠️ Troubleshooting

### Image Not Displaying?

1. **Check if helper is used:**
   ```php
   <!-- ✅ Should be -->
   {{ product_image_url($product) }}
   
   <!-- ❌ Not -->
   {{ $product->image_url }}
   ```

2. **Check if relationship is loaded:**
   ```php
   // In controller
   $product = Product::with('productImages')->find($id);
   ```

3. **Check if image exists:**
   ```php
   @if($product->productImages->count() > 0)
       <img src="{{ product_image_url($product) }}">
   @else
       <img src="{{ asset('images/placeholder-product.svg') }}">
   @endif
   ```

4. **Check storage link:**
   ```bash
   php artisan storage:link
   ```

---

## 📦 Placeholder Images

### Default Placeholder
```php
{{ asset('images/placeholder-product.svg') }}
```

### Automatic Fallback
All helper functions automatically return placeholder if image is missing:
```php
// If product has no images, returns placeholder automatically
{{ product_image_url($product) }}
```

---

## 🚀 Performance Tips

### 1. Use Pagination
```php
// ✅ GOOD - Paginate large lists
$products = Product::paginate(20);

// ❌ BAD - Load everything
$products = Product::all();
```

### 2. Eager Load Relationships
```php
// ✅ GOOD - One query
$products = Product::with('productImages')->get();

// ❌ BAD - N+1 queries
$products = Product::all();
foreach($products as $product) {
    $product->productImages; // Separate query each time
}
```

### 3. Limit Results
```php
// ✅ GOOD - Limit results
$products = Product::latest()->limit(8)->get();

// ❌ BAD - Get all
$products = Product::latest()->get();
```

---

## 📝 Checklist for New Features

When adding new image functionality:

- [ ] Use appropriate helper function
- [ ] Eager load relationships in controller
- [ ] Add pagination if listing multiple items
- [ ] Provide fallback for missing images
- [ ] Test with and without images
- [ ] Check mobile responsiveness
- [ ] Verify serverless compatibility

---

## 🎓 Examples by Use Case

### Use Case 1: Product Listing Page
```php
// Controller
public function index()
{
    $products = Product::with(['brand', 'productImages'])
        ->paginate(20);
    return view('products.index', compact('products'));
}

// View
@foreach($products as $product)
    <x-product-card :product="$product" />
@endforeach

{{ $products->links() }}
```

### Use Case 2: Product Detail Page
```php
// Controller
public function show($slug)
{
    $product = Product::with(['brand', 'productImages', 'reviews'])
        ->where('slug', $slug)
        ->firstOrFail();
    return view('products.show', compact('product'));
}

// View
<x-product-gallery :product="$product" />
```

### Use Case 3: Admin Product List
```php
// Controller
public function index()
{
    $products = Product::with(['brand', 'category', 'productImages'])
        ->paginate(20);
    return view('admin.products.index', compact('products'));
}

// View
@foreach($products as $product)
    <tr>
        <td>
            <img src="{{ product_image_url($product) }}" 
                 class="w-10 h-10 object-cover">
        </td>
        <td>{{ $product->name }}</td>
    </tr>
@endforeach
```

---

## 🔗 Related Documentation

- `IMAGE_DISPLAY_COMPREHENSIVE_AUDIT.md` - Full audit report
- `AUDIT_COMPLETE_SUMMARY.md` - Executive summary
- `VERIFICATION_COMPLETE.md` - Verification report
- `app/Helpers/ImageHelper.php` - Helper implementation
- `app/Helpers/helpers.php` - Global helper functions

---

## 💡 Quick Tips

1. **Always use helpers** - They handle all edge cases
2. **Eager load** - Prevent N+1 query problems
3. **Paginate** - Keep payloads small
4. **Test without images** - Ensure fallbacks work
5. **Follow patterns** - Use established code patterns

---

**Last Updated:** February 5, 2026  
**Maintained By:** Development Team  
**Questions?** Check the comprehensive audit documentation
