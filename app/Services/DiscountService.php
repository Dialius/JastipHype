<?php

namespace App\Services;

use App\Repositories\Contracts\DiscountRepositoryInterface;

class DiscountService
{
    protected $discountRepository;

    public function __construct(DiscountRepositoryInterface $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    /**
     * Create a new discount
     */
    public function create(array $data)
    {
        // Ensure code is uppercase
        if (isset($data['code'])) {
            $data['code'] = strtoupper($data['code']);
        }

        return $this->discountRepository->create($data);
    }

    /**
     * Update a discount
     */
    public function update($id, array $data)
    {
        // Ensure code is uppercase
        if (isset($data['code'])) {
            $data['code'] = strtoupper($data['code']);
        }

        return $this->discountRepository->update($id, $data);
    }

    /**
     * Delete a discount
     */
    public function delete($id)
    {
        return $this->discountRepository->delete($id);
    }

    /**
     * Get active discounts
     */
    public function getActive()
    {
        return $this->discountRepository->getActive();
    }

    /**
     * Find discount by code
     */
    public function findByCode($code)
    {
        return $this->discountRepository->findByCode(strtoupper($code));
    }

    /**
     * Validate discount
     */
    public function validate($code, $orderTotal, $productIds = [])
    {
        $discount = $this->findByCode($code);

        if (!$discount) {
            return ['valid' => false, 'message' => 'Discount code not found'];
        }

        if (!$discount->isValid()) {
            return ['valid' => false, 'message' => 'Discount code is not valid or has expired'];
        }

        if ($discount->min_order_amount && $orderTotal < $discount->min_order_amount) {
            return [
                'valid' => false,
                'message' => "Minimum order amount is Rp " . number_format($discount->min_order_amount, 0, ',', '.')
            ];
        }

        // Check applicability to products
        if ($discount->applicable_to !== 'all' && !empty($productIds)) {
            $applicable = false;
            foreach ($productIds as $productId) {
                if ($discount->isApplicableToProduct($productId)) {
                    $applicable = true;
                    break;
                }
            }

            if (!$applicable) {
                return ['valid' => false, 'message' => 'Discount code is not applicable to these products'];
            }
        }

        $discountAmount = $discount->calculateDiscount($orderTotal);

        return [
            'valid' => true,
            'discount' => $discount,
            'discount_amount' => $discountAmount,
        ];
    }

    /**
     * Apply discount
     */
    public function apply($id)
    {
        return $this->discountRepository->incrementUsage($id);
    }

    /**
     * Get discount statistics
     */
    public function getStatistics()
    {
        return $this->discountRepository->getStatistics();
    }
}
