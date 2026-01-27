<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RajaOngkirService;

class LocationController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function getProvinces()
    {
        try {
            $provinces = $this->rajaOngkir->getProvinces();
            
            // Format response untuk kompatibilitas dengan frontend
            return response()->json([
                'rajaongkir' => [
                    'status' => ['code' => 200],
                    'results' => $provinces
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCities($provinceId)
    {
        try {
            $cities = $this->rajaOngkir->getCities($provinceId);
            
            // Format response untuk kompatibilitas dengan frontend
            return response()->json([
                'rajaongkir' => [
                    'status' => ['code' => 200],
                    'results' => $cities
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCost(Request $request)
    {
        try {
            $validated = $request->validate([
                'destination' => 'required|integer',
                'weight' => 'required|integer|min:1',
                'courier' => 'string'
            ]);

            $origin = config('services.rajaongkir.origin', 151);
            $destination = $validated['destination'];
            $weight = $validated['weight'];
            $courier = $validated['courier'] ?? 'jne';

            // Get multiple couriers
            $couriers = ['jne', 'tiki', 'pos'];
            $results = $this->rajaOngkir->getMultipleCosts($origin, $destination, $weight, $couriers);
            
            // Format response untuk kompatibilitas dengan frontend
            return response()->json([
                'rajaongkir' => [
                    'status' => ['code' => 200],
                    'results' => $results
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSubdistricts($cityId)
    {
        try {
            $subdistricts = $this->rajaOngkir->getSubdistricts($cityId);
            
            // Format response untuk kompatibilitas dengan frontend
            return response()->json([
                'rajaongkir' => [
                    'status' => ['code' => 200],
                    'results' => $subdistricts
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
