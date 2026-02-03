<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ExportImportController extends Controller
{
    /**
     * Display export/import page
     */
    public function index()
    {
        return view('admin.export-import.index');
    }

    /**
     * Export products to CSV
     */
    public function exportProducts(Request $request)
    {
        try {
            $format = $request->input('format', 'csv');
            $filename = 'products_' . date('Y-m-d_His') . '.' . $format;

            $products = Product::with(['brand', 'category'])->get();

            $headers = ['ID', 'Name', 'SKU', 'Brand', 'Category', 'Price', 'Sale Price', 'Stock', 'Weight', 'Status', 'Created At'];
            
            $rows = [];
            foreach ($products as $product) {
                $rows[] = [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->brand->name ?? '',
                    $product->category->name ?? '',
                    $product->price,
                    $product->sale_price ?? '',
                    $product->stock,
                    $product->weight,
                    $product->status,
                    $product->created_at->format('Y-m-d H:i:s'),
                ];
            }

            return $this->downloadCSV($headers, $rows, $filename);

        } catch (\Exception $e) {
            Log::error('Export products failed: ' . $e->getMessage());
            return redirect()->route('admin.export-import.index')
                ->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Export orders to CSV
     */
    public function exportOrders(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $format = $request->input('format', 'csv');
            $filename = 'orders_' . date('Y-m-d_His') . '.' . $format;

            $query = Order::with(['user', 'items']);

            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->input('start_date'));
            }

            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->input('end_date'));
            }

            $orders = $query->get();

            $headers = [
                'Order Number', 'Customer Name', 'Customer Email', 'Total Amount', 
                'Shipping Cost', 'Status', 'Payment Status', 'Payment Method', 
                'Shipping Address', 'Created At'
            ];

            $rows = [];
            foreach ($orders as $order) {
                $rows[] = [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->total_amount,
                    $order->shipping_cost,
                    $order->status,
                    $order->payment_status ?? 'pending',
                    $order->payment_method ?? '',
                    $order->shipping_address,
                    $order->created_at->format('Y-m-d H:i:s'),
                ];
            }

            return $this->downloadCSV($headers, $rows, $filename);

        } catch (\Exception $e) {
            Log::error('Export orders failed: ' . $e->getMessage());
            return redirect()->route('admin.export-import.index')
                ->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Export customers to CSV
     */
    public function exportCustomers(Request $request)
    {
        try {
            $format = $request->input('format', 'csv');
            $filename = 'customers_' . date('Y-m-d_His') . '.' . $format;

            $customers = User::where('is_admin', false)
                ->withCount('orders')
                ->get();

            $headers = ['ID', 'Name', 'Email', 'Phone', 'Total Orders', 'Status', 'Registered At'];

            $rows = [];
            foreach ($customers as $customer) {
                $rows[] = [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->phone ?? '',
                    $customer->orders_count,
                    $customer->is_suspended ? 'Suspended' : 'Active',
                    $customer->created_at->format('Y-m-d H:i:s'),
                ];
            }

            return $this->downloadCSV($headers, $rows, $filename);

        } catch (\Exception $e) {
            Log::error('Export customers failed: ' . $e->getMessage());
            return redirect()->route('admin.export-import.index')
                ->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Import products from CSV
     */
    public function importProducts(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt|max:10240',
            ]);

            $file = $request->file('file');
            $data = $this->readCSV($file);

            if (empty($data)) {
                return redirect()->route('admin.export-import.index')
                    ->with('error', 'File is empty or invalid.');
            }

            // Skip header row
            $header = array_shift($data);

            $imported = 0;
            $updated = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // +2 because we skipped header and arrays are 0-indexed

                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Validate required fields
                    if (empty($row[1])) { // Name
                        $errors[] = "Row {$rowNumber}: Product name is required";
                        continue;
                    }

                    if (empty($row[2])) { // SKU
                        $errors[] = "Row {$rowNumber}: SKU is required";
                        continue;
                    }

                    // Check if product exists
                    $product = Product::where('sku', $row[2])->first();

                    $productData = [
                        'name' => $row[1],
                        'sku' => $row[2],
                        'brand_id' => $this->getBrandIdByName($row[3] ?? null),
                        'category_id' => $this->getCategoryIdByName($row[4] ?? null),
                        'price' => !empty($row[5]) ? (float) $row[5] : 0,
                        'sale_price' => !empty($row[6]) ? (float) $row[6] : null,
                        'stock' => !empty($row[7]) ? (int) $row[7] : 0,
                        'weight' => !empty($row[8]) ? (int) $row[8] : 0,
                        'status' => !empty($row[9]) ? $row[9] : 'active',
                        'description' => 'Imported product',
                    ];

                    if ($product) {
                        $product->update($productData);
                        $updated++;
                    } else {
                        Product::create($productData);
                        $imported++;
                    }

                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    Log::error("Import error on row {$rowNumber}: " . $e->getMessage());
                }
            }

            $message = "Successfully imported {$imported} new products and updated {$updated} existing products.";

            if (count($errors) > 0) {
                return redirect()->route('admin.export-import.index')
                    ->with('warning', $message . " Found " . count($errors) . " errors.")
                    ->with('import_errors', array_slice($errors, 0, 10)); // Limit to 10 errors
            }

            return redirect()->route('admin.export-import.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Import products failed: ' . $e->getMessage());
            return redirect()->route('admin.export-import.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download sample import template
     */
    public function downloadTemplate(string $type)
    {
        try {
            $templates = [
                'products' => [
                    'filename' => 'product_import_template.csv',
                    'headers' => ['ID', 'Name', 'SKU', 'Brand', 'Category', 'Price', 'Sale Price', 'Stock', 'Weight', 'Status', 'Created At'],
                    'sample' => ['', 'Supreme Box Logo Hoodie', 'SUP-HOODIE-001', 'Supreme', 'Hoodies', '500000', '450000', '10', '500', 'active', ''],
                ],
                'orders' => [
                    'filename' => 'order_export_template.csv',
                    'headers' => ['Order Number', 'Customer Name', 'Customer Email', 'Total Amount', 'Shipping Cost', 'Status', 'Payment Status', 'Payment Method', 'Shipping Address', 'Created At'],
                    'sample' => ['ORD-2026-001', 'John Doe', 'john@example.com', '500000', '15000', 'pending', 'pending', 'bank_transfer', 'Jakarta', ''],
                ],
                'customers' => [
                    'filename' => 'customer_export_template.csv',
                    'headers' => ['ID', 'Name', 'Email', 'Phone', 'Total Orders', 'Status', 'Registered At'],
                    'sample' => ['', 'John Doe', 'john@example.com', '081234567890', '0', 'Active', ''],
                ],
            ];

            if (!isset($templates[$type])) {
                abort(404, 'Template not found');
            }

            $template = $templates[$type];
            $rows = [$template['sample']];

            return $this->downloadCSV($template['headers'], $rows, $template['filename']);

        } catch (\Exception $e) {
            Log::error('Download template failed: ' . $e->getMessage());
            return redirect()->route('admin.export-import.index')
                ->with('error', 'Download template failed: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Download CSV file
     */
    protected function downloadCSV(array $headers, array $rows, string $filename)
    {
        $callback = function() use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fputcsv($file, $headers);
            
            // Write rows
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Helper: Read CSV file
     */
    protected function readCSV($file): array
    {
        $data = [];
        $handle = fopen($file->getPathname(), 'r');
        
        if ($handle === false) {
            throw new \Exception('Unable to open file');
        }

        // Skip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
            rewind($handle);
        }

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $data[] = $row;
        }

        fclose($handle);
        return $data;
    }

    /**
     * Helper: Get brand ID by name
     */
    protected function getBrandIdByName(?string $name): ?int
    {
        if (empty($name)) {
            return null;
        }

        $brand = Brand::where('name', $name)->first();
        
        if (!$brand) {
            // Try to create brand if it doesn't exist
            try {
                $brand = Brand::create([
                    'name' => $name,
                    'slug' => \Str::slug($name),
                    'description' => 'Auto-created from import',
                    'is_active' => true,
                ]);
            } catch (\Exception $e) {
                Log::warning("Failed to create brand '{$name}': " . $e->getMessage());
                return null;
            }
        }
        
        return $brand ? $brand->id : null;
    }

    /**
     * Helper: Get category ID by name
     */
    protected function getCategoryIdByName(?string $name): ?int
    {
        if (empty($name)) {
            return null;
        }

        $category = Category::where('name', $name)->first();
        
        if (!$category) {
            // Try to create category if it doesn't exist
            try {
                $category = Category::create([
                    'name' => $name,
                    'slug' => \Str::slug($name),
                    'description' => 'Auto-created from import',
                    'is_active' => true,
                ]);
            } catch (\Exception $e) {
                Log::warning("Failed to create category '{$name}': " . $e->getMessage());
                return null;
            }
        }
        
        return $category ? $category->id : null;
    }
}
