<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    protected $productRepository;
    protected $fileUploadService;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        FileUploadService $fileUploadService
    ) {
        $this->productRepository = $productRepository;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Create a new product
     */
    public function create(array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        } else {
            // Ensure provided slug is unique
            $data['slug'] = $this->generateUniqueSlug($data['slug']);
        }

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return $this->productRepository->create($data);
    }

    /**
     * Create a new product (alias for create)
     */
    public function createProduct(array $data)
    {
        return $this->create($data);
    }

    /**
     * Update a product
     */
    public function update($id, array $data)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return null;
        }

        // Update slug if name changed
        if (isset($data['name']) && $data['name'] !== $product->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $id);
        } elseif (isset($data['slug']) && $data['slug'] !== $product->slug) {
            $data['slug'] = $this->generateUniqueSlug($data['slug'], $id);
        }

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            // Delete old image
            if ($product->image) {
                $this->deleteImage($product->image);
            }
            $data['image'] = $this->uploadImage($data['image']);
        }

        return $this->productRepository->update($id, $data);
    }

    /**
     * Update a product (alias for update)
     */
    public function updateProduct($id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete a product (soft delete)
     */
    public function delete($id)
    {
        return $this->productRepository->delete($id);
    }

    /**
     * Delete a product (alias for delete)
     */
    public function deleteProduct($id)
    {
        return $this->delete($id);
    }

    /**
     * Get products with filters
     */
    public function getWithFilters(array $filters, $perPage = 15)
    {
        return $this->productRepository->getWithFilters($filters, $perPage);
    }

    /**
     * Get low stock products
     */
    public function getLowStock($threshold = 10)
    {
        return $this->productRepository->getLowStock($threshold);
    }

    /**
     * Bulk update product status
     */
    public function bulkUpdateStatus(array $ids, $status)
    {
        return $this->productRepository->bulkUpdateStatus($ids, $status);
    }

    /**
     * Update stock
     */
    public function updateStock($id, $quantity)
    {
        $product = $this->productRepository->find($id);
        
        if (!$product) {
            return null;
        }

        return $this->productRepository->update($id, [
            'stock' => $product->stock + $quantity
        ]);
    }

    /**
     * Upload product image
     */
    /**
     * Upload product image
     */
    protected function uploadImage($image)
    {
        return $this->fileUploadService->upload($image, 'products');
    }

    /**
     * Delete product image
     */
    protected function deleteImage($imagePath)
    {
        $this->fileUploadService->delete($imagePath);
    }

    /**
     * Generate unique slug
     */
    protected function generateUniqueSlug($text, $ignoreId = null)
    {
        $slug = Str::slug($text);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists
        while (true) {
            $query = \App\Models\Product::where('slug', $slug);
            
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
