<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;
    protected $customerRepository;

    public function __construct(
        CustomerService $customerService,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerService = $customerService;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'), // name, email
            'status' => $request->input('status'), // active, suspended
            'sort' => $request->input('sort', 'latest'), // latest, oldest, most_orders, highest_spending
        ];

        $customers = $this->customerRepository->paginate(15, $filters);

        // Get statistics
        $stats = [
            'total' => $this->customerRepository->count(),
            'active' => $this->customerRepository->countByStatus('active'),
            'suspended' => $this->customerRepository->countByStatus('suspended'),
            'new_this_month' => $this->customerRepository->countNewThisMonth(),
        ];

        return view('admin.customers.index', compact('customers', 'filters', 'stats'));
    }

    /**
     * Display the specified customer
     */
    public function show($id)
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return redirect()
                ->route('admin.customers.index')
                ->with('error', 'Customer not found!');
        }

        // Load relationships
        $customer->load(['orders', 'reviews']);

        // Get customer analytics
        $analytics = [
            'total_orders' => $customer->orders->count(),
            'total_spent' => $customer->orders->where('status', 'delivered')->sum('total'),
            'average_order_value' => $customer->orders->where('status', 'delivered')->avg('total'),
            'last_order_date' => $customer->orders->sortByDesc('created_at')->first()?->created_at,
            'total_reviews' => $customer->reviews->count(),
        ];

        // Get recent orders
        $recentOrders = $customer->orders()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.customers.show', compact('customer', 'analytics', 'recentOrders'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit($id)
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return redirect()
                ->route('admin.customers.index')
                ->with('error', 'Customer not found!');
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, $id)
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return redirect()
                ->route('admin.customers.index')
                ->with('error', 'Customer not found!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
        ]);

        $this->customerService->updateCustomer($id, $validated);

        return redirect()
            ->route('admin.customers.show', $id)
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * Suspend customer account
     */
    public function suspend(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return redirect()
                ->back()
                ->with('error', 'Customer not found!');
        }

        $this->customerService->suspendCustomer($id, $validated['reason']);

        return redirect()
            ->route('admin.customers.show', $id)
            ->with('success', 'Customer account suspended successfully!');
    }

    /**
     * Activate customer account
     */
    public function activate($id)
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return redirect()
                ->back()
                ->with('error', 'Customer not found!');
        }

        $this->customerService->activateCustomer($id);

        return redirect()
            ->route('admin.customers.show', $id)
            ->with('success', 'Customer account activated successfully!');
    }

    /**
     * Export customers to CSV
     */
    public function export(Request $request)
    {
        $filters = [
            'status' => $request->input('status'),
        ];

        $customers = $this->customerRepository->getWithFilters($filters, 999999)->items();

        $filename = 'customers_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Total Orders',
                'Total Spent',
                'Status',
                'Registered At',
            ]);

            // Data
            foreach ($customers as $customer) {
                $totalOrders = $customer->orders()->count();
                $totalSpent = $customer->orders()->where('status', 'delivered')->sum('total');
                
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->phone ?? '-',
                    $totalOrders,
                    $totalSpent,
                    $customer->status,
                    $customer->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
