<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Fake storage
        Storage::fake('public');
        
        // Create admin user
        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);
    }

    /** @test */
    public function admin_can_upload_brand_logo()
    {
        $this->actingAs($this->admin);
        
        $file = UploadedFile::fake()->image('brand-logo.jpg', 500, 500);
        
        $response = $this->post(route('admin.brands.store'), [
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'status' => 'active',
            'logo' => $file,
        ]);
        
        $response->assertRedirect(route('admin.brands.index'));
        $response->assertSessionHas('success');
        
        // Assert file was stored
        $brand = Brand::where('slug', 'test-brand')->first();
        $this->assertNotNull($brand);
        $this->assertNotNull($brand->logo_path);
        Storage::disk('public')->assertExists($brand->logo_path);
    }

    /** @test */
    public function admin_can_upload_product_images()
    {
        $this->actingAs($this->admin);
        
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        
        $frontImage = UploadedFile::fake()->image('front.jpg');
        $backImage = UploadedFile::fake()->image('back.jpg');
        
        $response = $this->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'sku' => 'TEST-001',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 100000,
            'stock' => 10,
            'is_active' => true,
            'images' => [
                'front' => [$frontImage],
                'back' => [$backImage],
            ],
        ]);
        
        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');
        
        // Assert files were stored
        $product = Product::where('sku', 'TEST-001')->first();
        $this->assertNotNull($product);
        $this->assertCount(2, $product->productImages);
        
        foreach ($product->productImages as $image) {
            Storage::disk('public')->assertExists($image->image_path);
        }
    }

    /** @test */
    public function admin_can_upload_category_image()
    {
        $this->actingAs($this->admin);
        
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->image('category.jpg');
        
        $response = $this->post(route('admin.categories.images.update'), [
            'categories' => [
                $category->id => [
                    'id' => $category->id,
                    'image' => $file,
                ],
            ],
        ]);
        
        $response->assertRedirect(route('admin.categories.images.edit'));
        $response->assertSessionHas('success');
        
        // Assert file was stored
        $category->refresh();
        $this->assertNotNull($category->image);
        Storage::disk('public')->assertExists($category->image);
    }

    /** @test */
    public function file_upload_validates_file_type()
    {
        $this->actingAs($this->admin);
        
        $file = UploadedFile::fake()->create('document.pdf', 1000);
        
        $response = $this->post(route('admin.brands.store'), [
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'status' => 'active',
            'logo' => $file,
        ]);
        
        $response->assertSessionHasErrors('logo');
    }

    /** @test */
    public function file_upload_validates_file_size()
    {
        $this->actingAs($this->admin);
        
        // Create a file larger than 2MB
        $file = UploadedFile::fake()->image('large.jpg')->size(3000);
        
        $response = $this->post(route('admin.brands.store'), [
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'status' => 'active',
            'logo' => $file,
        ]);
        
        $response->assertSessionHasErrors('logo');
    }

    /** @test */
    public function old_file_is_deleted_when_uploading_new_one()
    {
        $this->actingAs($this->admin);
        
        // Create brand with logo
        $oldFile = UploadedFile::fake()->image('old-logo.jpg');
        $brand = Brand::factory()->create();
        $oldPath = $oldFile->store('brands', 'public');
        $brand->update(['logo_path' => $oldPath]);
        
        // Upload new logo
        $newFile = UploadedFile::fake()->image('new-logo.jpg');
        
        $response = $this->put(route('admin.brands.update', $brand->id), [
            'name' => $brand->name,
            'slug' => $brand->slug,
            'status' => $brand->status,
            'logo' => $newFile,
        ]);
        
        $response->assertRedirect(route('admin.brands.index'));
        
        // Assert old file was deleted
        Storage::disk('public')->assertMissing($oldPath);
        
        // Assert new file exists
        $brand->refresh();
        Storage::disk('public')->assertExists($brand->logo_path);
    }
}
