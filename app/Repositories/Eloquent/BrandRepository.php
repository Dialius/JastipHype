<?php

namespace App\Repositories\Eloquent;

use App\Models\Brand;
use App\Repositories\Contracts\BrandRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    protected int $cacheTTL = 600; // 10 minutes

    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }

    public function getWithProductCount()
    {
        return Cache::remember('brands.with_product_count', $this->cacheTTL, function () {
            return $this->model->withCount('products')->orderBy('display_order')->get();
        });
    }

    public function getStatistics($brandId)
    {
        $cacheKey = "brands.statistics.{$brandId}";

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($brandId) {
            $brand = $this->find($brandId);
            
            if (!$brand) {
                return null;
            }

            $products = $brand->products;
            $revenue = $brand->products()
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('orders.status', ['processing', 'shipped', 'delivered'])
                ->sum('order_items.subtotal');

            return [
                'product_count' => $products->count(),
                'active_products' => $products->where('is_active', true)->count(),
                'total_revenue' => $revenue,
            ];
        });
    }

    public function updateOrder(array $orderData)
    {
        foreach ($orderData as $id => $order) {
            $this->model->where('id', $id)->update(['display_order' => $order]);
        }
        
        // Clear cache after updating order
        Cache::forget('brands.with_product_count');
        
        return true;
    }

    public function hasProducts($brandId)
    {
        $brand = $this->find($brandId);
        return $brand ? $brand->products()->exists() : false;
    }

    /**
     * Clear brand cache
     */
    public function clearCache($brandId = null)
    {
        Cache::forget('brands.with_product_count');
        
        if ($brandId) {
            Cache::forget("brands.statistics.{$brandId}");
        }
    }
}
