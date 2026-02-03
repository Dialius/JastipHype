<?php

namespace App\Services;

use App\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get customers with filters
     */
    public function getWithFilters(array $filters, $perPage = 15)
    {
        return $this->customerRepository->getWithFilters($filters, $perPage);
    }

    /**
     * Get customer by ID
     */
    public function getById($id)
    {
        return $this->customerRepository->find($id);
    }

    /**
     * Get customer statistics
     */
    public function getStatistics()
    {
        return $this->customerRepository->getStatistics();
    }

    /**
     * Get top customers
     */
    public function getTopCustomers($limit = 10)
    {
        return $this->customerRepository->getTopCustomers($limit);
    }

    /**
     * Get customer order history
     */
    public function getOrderHistory($customerId)
    {
        return $this->customerRepository->getOrderHistory($customerId);
    }

    /**
     * Get customer spending analytics
     */
    public function getSpendingAnalytics($customerId)
    {
        return $this->customerRepository->getSpendingAnalytics($customerId);
    }

    /**
     * Suspend customer account
     */
    public function suspend($id, $reason = null)
    {
        return $this->customerRepository->update($id, [
            'is_suspended' => true,
            'suspension_reason' => $reason,
            'suspended_at' => now(),
        ]);
    }

    /**
     * Activate customer account
     */
    public function activate($id)
    {
        return $this->customerRepository->update($id, [
            'is_suspended' => false,
            'suspension_reason' => null,
            'suspended_at' => null,
        ]);
    }

    /**
     * Search customers
     */
    public function search($query)
    {
        return $this->customerRepository->search($query);
    }

    /**
     * Update customer data
     */
    public function updateCustomer($id, array $data)
    {
        return $this->customerRepository->update($id, $data);
    }

    /**
     * Suspend customer account
     */
    public function suspendCustomer($id, $reason)
    {
        return $this->customerRepository->update($id, [
            'suspension_reason' => $reason,
            'suspended_at' => now(),
        ]);
    }

    /**
     * Activate customer account
     */
    public function activateCustomer($id)
    {
        return $this->customerRepository->update($id, [
            'suspension_reason' => null,
            'suspended_at' => null,
        ]);
    }
}
