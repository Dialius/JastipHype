<?php

namespace App\Repositories\Eloquent;

use App\Models\Banner;
use App\Repositories\Contracts\BannerRepositoryInterface;

class BannerRepository extends BaseRepository implements BannerRepositoryInterface
{
    public function __construct(Banner $model)
    {
        parent::__construct($model);
    }

    public function getAllOrdered()
    {
        return $this->model->orderBy('order', 'asc')->get();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function getLastOrder()
    {
        return $this->model->orderBy('order', 'desc')->first();
    }

    public function getActive()
    {
        return $this->model->active()->ordered()->get();
    }

    public function getByType($type)
    {
        return $this->model->ofType($type)->ordered()->get();
    }

    public function updateOrder(array $orderData)
    {
        foreach ($orderData as $index => $id) {
            $this->model->where('id', $id)->update(['order' => $index + 1]);
        }
        return true;
    }

    public function toggleStatus($id)
    {
        $banner = $this->find($id);
        if ($banner) {
            $newStatus = $banner->status === 'active' ? 'inactive' : 'active';
            $banner->update(['status' => $newStatus]);
            return $banner;
        }
        return null;
    }
}
