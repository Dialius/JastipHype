<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SystemMonitorController extends Controller
{
    /**
     * Display system status dashboard
     */
    public function index()
    {
        $systemHealth = $this->getSystemHealth();
        $databaseStats = $this->getDatabaseStats();
        $serviceStatus = $this->checkServices();
        
        return view('admin.system.index', compact('systemHealth', 'databaseStats', 'serviceStatus'));
    }

    /**
     * Check external services status
     */
    public function checkServices()
    {
        $services = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'mail' => $this->checkMail(),
            'midtrans' => $this->checkMidtrans(),
            'rajaongkir' => $this->checkRajaOngkir(),
        ];

        if (request()->wantsJson()) {
            return response()->json($services);
        }

        return $services;
    }

    /**
     * Get database statistics
     */
    public function getDatabaseStats()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $databaseName = DB::getDatabaseName();
            $tableKey = "Tables_in_{$databaseName}";
            
            $stats = [];
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                
                // Get row count
                $count = DB::table($tableName)->count();
                
                // Get table size
                $size = DB::select("
                    SELECT 
                        ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
                    FROM information_schema.TABLES 
                    WHERE table_schema = ? AND table_name = ?
                ", [$databaseName, $tableName]);
                
                $stats[] = [
                    'name' => $tableName,
                    'rows' => number_format($count),
                    'size' => $size[0]->size_mb ?? 0,
                ];
            }
            
            // Get total database size
            $totalSize = DB::select("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.TABLES 
                WHERE table_schema = ?
            ", [$databaseName]);
            
            return [
                'tables' => $stats,
                'total_size' => $totalSize[0]->size_mb ?? 0,
                'table_count' => count($stats),
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'tables' => [],
                'total_size' => 0,
                'table_count' => 0,
            ];
        }
    }

    /**
     * Get recent error logs
     */
    public function errorLogs(Request $request)
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!File::exists($logFile)) {
            return response()->json([
                'logs' => [],
                'message' => 'No log file found',
            ]);
        }
        
        $lines = $request->input('lines', 100);
        $content = File::get($logFile);
        $logLines = explode("\n", $content);
        $recentLogs = array_slice(array_reverse($logLines), 0, $lines);
        
        // Parse error logs
        $errors = [];
        foreach ($recentLogs as $line) {
            if (preg_match('/\[(.*?)\] (\w+)\.(\w+): (.*)/', $line, $matches)) {
                $errors[] = [
                    'timestamp' => $matches[1],
                    'environment' => $matches[2],
                    'level' => $matches[3],
                    'message' => $matches[4],
                ];
            }
        }
        
        return response()->json([
            'logs' => $errors,
            'total' => count($errors),
        ]);
    }

    /**
     * Get system health metrics
     */
    protected function getSystemHealth()
    {
        return [
            'disk_usage' => $this->getDiskUsage(),
            'memory_usage' => $this->getMemoryUsage(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_time' => now()->format('Y-m-d H:i:s'),
            'uptime' => $this->getServerUptime(),
        ];
    }

    /**
     * Get disk usage
     */
    protected function getDiskUsage()
    {
        try {
            $total = @disk_total_space('/');
            $free = @disk_free_space('/');
            
            if ($total === false || $free === false) {
                return [
                    'total' => 'N/A',
                    'used' => 'N/A',
                    'free' => 'N/A',
                    'percentage' => 0,
                ];
            }
            
            $used = $total - $free;
            
            return [
                'total' => $this->formatBytes($total),
                'used' => $this->formatBytes($used),
                'free' => $this->formatBytes($free),
                'percentage' => round(($used / $total) * 100, 2),
            ];
        } catch (\Exception $e) {
            return [
                'total' => 'N/A',
                'used' => 'N/A',
                'free' => 'N/A',
                'percentage' => 0,
            ];
        }
    }

    /**
     * Get memory usage
     */
    protected function getMemoryUsage()
    {
        $memory = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        
        return [
            'current' => $this->formatBytes($memory),
            'peak' => $this->formatBytes($memoryPeak),
        ];
    }

    /**
     * Get server uptime
     */
    protected function getServerUptime()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return 'N/A (Windows)';
        }
        
        try {
            if (function_exists('shell_exec') && !in_array('shell_exec', explode(',', ini_get('disable_functions')))) {
                $uptime = @shell_exec('uptime -p 2>&1');
                return trim($uptime) ?: 'N/A';
            }
            return 'N/A (shell_exec disabled)';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Check database connection
     */
    protected function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'online',
                'message' => 'Database connection successful',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check cache connection
     */
    protected function checkCache()
    {
        try {
            Cache::put('system_monitor_test', 'test', 10);
            $value = Cache::get('system_monitor_test');
            Cache::forget('system_monitor_test');
            
            return [
                'status' => $value === 'test' ? 'online' : 'offline',
                'message' => $value === 'test' ? 'Cache is working' : 'Cache test failed',
                'driver' => config('cache.default'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'message' => 'Cache error: ' . $e->getMessage(),
                'driver' => config('cache.default'),
            ];
        }
    }

    /**
     * Check mail configuration
     */
    protected function checkMail()
    {
        try {
            $config = config('mail');
            return [
                'status' => 'configured',
                'message' => 'Mail is configured',
                'driver' => $config['default'] ?? 'N/A',
                'host' => config('mail.mailers.smtp.host') ?? 'N/A',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Mail configuration error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check Midtrans configuration
     */
    protected function checkMidtrans()
    {
        try {
            $serverKey = config('midtrans.server_key');
            $clientKey = config('midtrans.client_key');
            
            if (empty($serverKey) || empty($clientKey)) {
                return [
                    'status' => 'not_configured',
                    'message' => 'Midtrans credentials not configured',
                ];
            }
            
            return [
                'status' => 'configured',
                'message' => 'Midtrans is configured',
                'environment' => config('midtrans.environment', 'sandbox'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Midtrans error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check RajaOngkir configuration
     */
    protected function checkRajaOngkir()
    {
        try {
            $apiKey = config('rajaongkir.api_key');
            
            if (empty($apiKey)) {
                return [
                    'status' => 'not_configured',
                    'message' => 'RajaOngkir API key not configured',
                ];
            }
            
            return [
                'status' => 'configured',
                'message' => 'RajaOngkir is configured',
                'type' => config('rajaongkir.type', 'starter'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'RajaOngkir error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
