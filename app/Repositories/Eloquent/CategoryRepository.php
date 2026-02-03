<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    protected int $cacheTTL = 600; // 10 minutes

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function all()
    {
        return Cache::remember('categories.all', $this->cacheTTL, function () {
            return $this->model->orderBy('order')->get();
        });
    }

    /**
     * Clear category cache
     */
    public function clearCache()
    {
        Cache::forget('categories.all');
    }
}
