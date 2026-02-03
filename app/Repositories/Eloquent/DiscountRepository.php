<?php

namespace App\Repositories\Eloquent;

use App\Models\Discount;
use App\Repositories\Contracts\DiscountRepositoryInterface;

class DiscountRepository extends BaseRepository implements DiscountRepositoryInterface
{
    public function __construct(Discount $model)
    {
        parent::__construct($model);
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function paginate($perPage = 15)
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getActive()
    {
        return $this->model->active()->get();
    }

    public function findByCode($code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function isApplicableToProduct($discountId, $productId)
    {
        $discount = $this->find($discountId);
        return $discount ? $discount->isApplicableToProduct($productId) : false;
    }

    public function incrementUsage($id)
    {
        $discount = $this->find($id);
        if ($discount) {
            $discount->incrementUsage();
            return true;
        }
        return false;
    }

    public function getStatistics()
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('status', 'active')->count(),
            'total_uses' => $this->model->sum('uses_count'),
            'total_discount_amount' => $this->model->sum('value'),
        ];
    }
}
