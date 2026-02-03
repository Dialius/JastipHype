<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use App\Repositories\Contracts\SettingsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingController extends Controller
{
    protected RajaOngkirService $rajaOngkirService;
    protected SettingsRepositoryInterface $settingsRepository;

    public function __construct(
        RajaOngkirService $rajaOngkirService,
        SettingsRepositoryInterface $settingsRepository
    ) {
        $this->rajaOngkirService = $rajaOngkirService;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Display shipping settings dashboard
     */
    public function index()
    {
        // Get current settings
        $enabledCouriers = $this->settingsRepository->get('shipping.enabled_couriers', ['jne', 'pos', 'tiki']);
        $originCity = $this->settingsRepository->get('shipping.origin_city_id');
        $originCityName = $this->settingsRepository->get('shipping.origin_city_name');
        $originPostalCode = $this->settingsRepository->get('shipping.origin_postal_code');
        $freeShippingEnabled = $this->settingsRepository->get('shipping.free_shipping_enabled', false);
        $freeShippingMinAmount = $this->settingsRepository->get('shipping.free_shipping_min_amount', 0);
        
        // Get available couriers from RajaOngkir
        $availableCouriers = $this->getAvailableCouriers();
        
        // Get provinces for origin selection
        $provinces = $this->rajaOngkirService->getProvinces();
        
        // Get cities if province is selected
        $cities = [];
        if ($originCity) {
            $cityData = $this->rajaOngkirService->getCityById($originCity);
            if ($cityData) {
                $provinceId = $cityData['province_id'] ?? null;
                if ($provinceId) {
                    $cities = $this->rajaOngkirService->getCities($provinceId);
                }
            }
        }
        
        return view('admin.shipping.index', compact(
            'enabledCouriers',
            'availableCouriers',
            'originCity',
            'originCityName',
            'originPostalCode',
            'provinces',
            'cities',
            'freeShippingEnabled',
            'freeShippingMinAmount'
        ));
    }

    /**
     * Update enabled couriers
     */
    public function updateCouriers(Request $request)
    {
        $request->validate([
            'couriers' => 'nullable|array',
            'couriers.*' => 'string|in:jne,pos,tiki,jnt,sicepat,anteraja,ninja,lion,idexpress,wahana,sap,jet,dse,first,rpx,pandu,cahaya,star,pcp,rex,sentral,indah,pahala,ncs,rosalia,slis,expedito,jtl,esl,til,dakota,jtl,sap'
        ]);

        $couriers = $request->input('couriers', []);
        
        $this->settingsRepository->set('shipping.enabled_couriers', $couriers);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Courier settings updated successfully.');
    }

    /**
     * Update origin address
     */
    public function updateOrigin(Request $request)
    {
        $request->validate([
            'origin_city_id' => 'required|integer',
            'origin_city_name' => 'required|string',
            'origin_postal_code' => 'required|string|max:10',
        ]);

        $this->settingsRepository->set('shipping.origin_city_id', $request->input('origin_city_id'));
        $this->settingsRepository->set('shipping.origin_city_name', $request->input('origin_city_name'));
        $this->settingsRepository->set('shipping.origin_postal_code', $request->input('origin_postal_code'));

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Origin address updated successfully.');
    }

    /**
     * Update free shipping rules
     */
    public function updateFreeShipping(Request $request)
    {
        $request->validate([
            'free_shipping_enabled' => 'required|boolean',
            'free_shipping_min_amount' => 'nullable|numeric|min:0',
        ]);

        $this->settingsRepository->set('shipping.free_shipping_enabled', $request->boolean('free_shipping_enabled'));
        $this->settingsRepository->set('shipping.free_shipping_min_amount', $request->input('free_shipping_min_amount', 0));

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Free shipping settings updated successfully.');
    }

    /**
     * Update shipping zones (future feature)
     */
    public function updateZones(Request $request)
    {
        // This is a placeholder for future zone management
        // For now, we'll just return success
        return redirect()->route('admin.shipping.index')
            ->with('info', 'Shipping zones feature coming soon.');
    }

    /**
     * Display shipping analytics
     */
    public function analytics()
    {
        // Get courier usage statistics
        $courierStats = DB::table('orders')
            ->select('shipping_courier', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(shipping_cost) as total_cost'))
            ->whereNotNull('shipping_courier')
            ->groupBy('shipping_courier')
            ->orderBy('total_orders', 'desc')
            ->get();

        // Calculate average shipping cost
        $avgShippingCost = DB::table('orders')
            ->whereNotNull('shipping_cost')
            ->avg('shipping_cost');

        // Get shipping cost distribution
        $costDistribution = DB::table('orders')
            ->select(
                DB::raw('CASE 
                    WHEN shipping_cost < 10000 THEN "< 10k"
                    WHEN shipping_cost < 20000 THEN "10k - 20k"
                    WHEN shipping_cost < 50000 THEN "20k - 50k"
                    WHEN shipping_cost < 100000 THEN "50k - 100k"
                    ELSE "> 100k"
                END as range'),
                DB::raw('COUNT(*) as count')
            )
            ->whereNotNull('shipping_cost')
            ->groupBy('range')
            ->get();

        // Get monthly shipping trends
        $monthlyTrends = DB::table('orders')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(shipping_cost) as total_cost'),
                DB::raw('AVG(shipping_cost) as avg_cost')
            )
            ->whereNotNull('shipping_cost')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return view('admin.shipping.analytics', compact(
            'courierStats',
            'avgShippingCost',
            'costDistribution',
            'monthlyTrends'
        ));
    }

    /**
     * Get cities by province (AJAX)
     */
    public function getCitiesByProvince(Request $request)
    {
        $request->validate([
            'province_id' => 'required|integer'
        ]);

        $cities = $this->rajaOngkirService->getCities($request->input('province_id'));

        return response()->json([
            'success' => true,
            'cities' => $cities
        ]);
    }

    /**
     * Get available couriers
     */
    protected function getAvailableCouriers(): array
    {
        return [
            [
                'code' => 'jne',
                'name' => 'JNE (Jalur Nugraha Ekakurir)',
                'description' => 'JNE Regular, YES, OKE',
                'popular' => true
            ],
            [
                'code' => 'pos',
                'name' => 'POS Indonesia',
                'description' => 'Pos Kilat Khusus, Express',
                'popular' => true
            ],
            [
                'code' => 'tiki',
                'name' => 'TIKI (Titipan Kilat)',
                'description' => 'Regular, ONS, ECO',
                'popular' => true
            ],
            [
                'code' => 'jnt',
                'name' => 'J&T Express',
                'description' => 'Regular service',
                'popular' => true
            ],
            [
                'code' => 'sicepat',
                'name' => 'SiCepat Express',
                'description' => 'Regular, BEST, HALU',
                'popular' => true
            ],
            [
                'code' => 'anteraja',
                'name' => 'AnterAja',
                'description' => 'Regular, Next Day',
                'popular' => false
            ],
            [
                'code' => 'ninja',
                'name' => 'Ninja Xpress',
                'description' => 'Standard, Express',
                'popular' => false
            ],
            [
                'code' => 'lion',
                'name' => 'Lion Parcel',
                'description' => 'Regular, One Day',
                'popular' => false
            ],
            [
                'code' => 'idexpress',
                'name' => 'ID Express',
                'description' => 'Regular service',
                'popular' => false
            ],
            [
                'code' => 'wahana',
                'name' => 'Wahana',
                'description' => 'Regular service',
                'popular' => false
            ],
        ];
    }
}
