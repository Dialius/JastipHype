<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Display revenue analytics
     */
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $analytics = $this->analyticsService->getRevenueAnalytics($startDate, $endDate);

        return view('admin.analytics.revenue', compact('analytics', 'startDate', 'endDate'));
    }

    /**
     * Get revenue breakdown by payment method
     */
    public function revenueByPaymentMethod(Request $request)
    {
        $data = $this->analyticsService->getRevenueByPaymentMethod();

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.analytics.payment-methods', compact('data'));
    }

    /**
     * Display product performance analytics
     */
    public function productPerformance(Request $request)
    {
        $limit = $request->input('limit', 10);
        $products = $this->analyticsService->getProductPerformance($limit);

        return view('admin.analytics.products', compact('products'));
    }

    /**
     * Display customer analytics
     */
    public function customerAnalytics(Request $request)
    {
        $analytics = $this->analyticsService->getCustomerAnalytics();

        return view('admin.analytics.customers', compact('analytics'));
    }

    /**
     * Export analytics report
     */
    public function exportReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:revenue,products,customers',
            'format' => 'required|in:pdf,excel,csv',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $type = $request->input('type');
        $format = $request->input('format');
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Get data based on type
        $data = match ($type) {
            'revenue' => $this->analyticsService->getRevenueAnalytics($startDate, $endDate),
            'products' => $this->analyticsService->getProductPerformance(50),
            'customers' => $this->analyticsService->getCustomerAnalytics(),
            default => [],
        };

        // Generate export based on format
        return match ($format) {
            'pdf' => $this->exportPdf($type, $data, $startDate, $endDate),
            'excel' => $this->exportExcel($type, $data, $startDate, $endDate),
            'csv' => $this->exportCsv($type, $data, $startDate, $endDate),
            default => back()->with('error', 'Invalid export format'),
        };
    }

    /**
     * Export data as PDF
     */
    protected function exportPdf(string $type, array $data, $startDate, $endDate)
    {
        // TODO: Implement PDF export using DomPDF or similar
        // For now, return a simple response
        return back()->with('info', 'PDF export will be implemented in a future update');
    }

    /**
     * Export data as Excel
     */
    protected function exportExcel(string $type, array $data, $startDate, $endDate)
    {
        // TODO: Implement Excel export using Laravel Excel
        // For now, return a simple response
        return back()->with('info', 'Excel export will be implemented in a future update');
    }

    /**
     * Export data as CSV
     */
    protected function exportCsv(string $type, array $data, $startDate, $endDate)
    {
        $filename = "{$type}_report_" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($type, $data) {
            $file = fopen('php://output', 'w');
            
            // Write headers and data based on type
            match ($type) {
                'revenue' => $this->writeRevenueCSV($file, $data),
                'products' => $this->writeProductsCSV($file, $data),
                'customers' => $this->writeCustomersCSV($file, $data),
                default => null,
            };

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Write revenue data to CSV
     */
    protected function writeRevenueCSV($file, array $data): void
    {
        fputcsv($file, ['Period', 'Revenue']);
        fputcsv($file, ['Today', 'Rp ' . number_format($data['today'], 0, ',', '.')]);
        fputcsv($file, ['This Week', 'Rp ' . number_format($data['this_week'], 0, ',', '.')]);
        fputcsv($file, ['This Month', 'Rp ' . number_format($data['this_month'], 0, ',', '.')]);
        fputcsv($file, ['This Year', 'Rp ' . number_format($data['this_year'], 0, ',', '.')]);
        fputcsv($file, ['Total', 'Rp ' . number_format($data['total'], 0, ',', '.')]);
    }

    /**
     * Write products data to CSV
     */
    protected function writeProductsCSV($file, $products): void
    {
        fputcsv($file, ['Product ID', 'Product Name', 'Total Quantity Sold', 'Total Revenue']);
        
        foreach ($products as $product) {
            fputcsv($file, [
                $product->id,
                $product->name,
                $product->total_quantity,
                'Rp ' . number_format($product->total_revenue, 0, ',', '.'),
            ]);
        }
    }

    /**
     * Write customers data to CSV
     */
    protected function writeCustomersCSV($file, array $data): void
    {
        // Write statistics
        fputcsv($file, ['Customer Statistics']);
        fputcsv($file, ['Total Customers', $data['statistics']['total'] ?? 0]);
        fputcsv($file, ['Active Customers', $data['statistics']['active'] ?? 0]);
        fputcsv($file, ['New This Month', $data['statistics']['new_this_month'] ?? 0]);
        fputcsv($file, []);
        
        // Write top customers
        fputcsv($file, ['Top Customers']);
        fputcsv($file, ['Customer ID', 'Name', 'Email', 'Total Orders', 'Total Spent']);
        
        foreach ($data['top_customers'] as $customer) {
            fputcsv($file, [
                $customer->id,
                $customer->name,
                $customer->email,
                $customer->total_orders ?? 0,
                'Rp ' . number_format($customer->total_spent ?? 0, 0, ',', '.'),
            ]);
        }
    }
}
