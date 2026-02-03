# Task 11: Product Image Categories - COMPLETED ✅

## Feature Overview
Menambahkan sistem kategorisasi gambar produk dengan 4 kategori:
- **Depan (Front)**: Foto tampak depan produk
- **Belakang (Back)**: Foto tampak belakang produk  
- **Detail**: Foto detail produk (logo, jahitan, material, dll)
- **Lainnya (Other)**: Foto lainnya (packaging, tag, accessories, dll)

## Changes Made

### 1. Database Migration
**File**: `database/migrations/2026_01_31_120622_add_type_to_product_images_table.php`

Added `type` column to `product_images` table:
```php
$table->enum('type', ['front', 'back', 'detail', 'other'])->default('other');
```

### 2. Product Create Form
**File**: `resources/views/admin/products/create.blade.php`

- Replaced single file input with 4 separate inputs (one per category)
- Each input supports multiple file uploads
- Added live preview for each category with badge labels
- Preview shows image thumbnail + filename + category badge

**Form Structure**:
```html
<input type="file" name="images[front][]" multiple>
<input type="file" name="images[back][]" multiple>
<input type="file" name="images[detail][]" multiple>
<input type="file" name="images[other][]" multiple>
```

### 3. JavaScript Preview
Updated image preview to:
- Handle multiple file inputs
- Show preview per category
- Display category badge (Depan, Belakang, Detail, Lainnya)
- Maintain separate preview containers for each type

### 4. Controller Update
**File**: `app/Http/Controllers/Admin/ProductController.php`

Updated `store()` method to:
- Validate images per category
- Create product first
- Loop through each image type
- Save to `product_images` table with:
  - `product_id`
  - `image_path`
  - `type` (front/back/detail/other)
  - `order` (sequential)
  - `is_primary` (first image only)

**Upload Logic**:
```php
foreach ($imageTypes as $type) {
    if ($request->hasFile("images.{$type}")) {
        foreach ($request->file("images.{$type}") as $image) {
            $path = $image->store('products', 'public');
            ProductImage::create([...]);
        }
    }
}
```

## Benefits

1. **Better Organization**: Images are categorized by purpose
2. **Flexible Display**: Frontend can show specific image types (e.g., only front view in listings)
3. **User-Friendly**: Clear labels help admin know what to upload
4. **Multiple Images**: Each category supports multiple uploads
5. **Live Preview**: Admin can see what they're uploading before submitting

## Usage

### Creating Product with Images:

1. Go to `/admin/products/create`
2. Fill in product details
3. Upload images by category:
   - **Depan**: Main product photo (front view)
   - **Belakang**: Back view of product
   - **Detail**: Close-up shots (logo, stitching, material)
   - **Lainnya**: Packaging, tags, accessories
4. Preview appears immediately below each upload
5. Submit form

### Database Structure:

```
product_images table:
- id
- product_id (FK to products)
- image_path (storage path)
- type (enum: front, back, detail, other)
- order (display order)
- is_primary (boolean, first image = true)
- created_at
- updated_at
```

## Next Steps

- [ ] Update product edit form with same categorization
- [ ] Update frontend product display to use categorized images
- [ ] Add drag-and-drop reordering within categories
- [ ] Add image cropping/resizing before upload

## Files Modified

1. `database/migrations/2026_01_31_120622_add_type_to_product_images_table.php` (NEW)
2. `resources/views/admin/products/create.blade.php`
3. `app/Http/Controllers/Admin/ProductController.php`

## Status
✅ **COMPLETED** - Product images now support categorization with live preview
