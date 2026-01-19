<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RajaOngkirService;

class ShippingController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        $provinces = $this->rajaOngkir->getProvinces();
        return response()->json($provinces);
    }

    /**
     * Get cities by province
     */
    public function getCities(Request $request)
    {
        $provinceId = $request->query('province_id');
        $cities = $this->rajaOngkir->getCities($provinceId);
        return response()->json($cities);
    }

    /**
     * Calculate shipping cost
     */
    public function calculateCost(Request $request)
    {
        $validated = $request->validate([
            'destination_city' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'couriers' => 'array'
        ]);

        $origin = config('rajaongkir.origin_city');
        $destination = $validated['destination_city'];
        $weight = $validated['weight'];
        $couriers = $validated['couriers'] ?? config('rajaongkir.couriers');

        try {
            $results = $this->rajaOngkir->getMultipleCosts($origin, $destination, $weight, $couriers);
            
            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate shipping cost',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
