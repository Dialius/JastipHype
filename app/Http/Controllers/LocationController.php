<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key');
        $this->baseUrl = 'https://api.rajaongkir.com/' . config('services.rajaongkir.type', 'starter');
    }

    public function getProvinces()
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'key' => $this->apiKey
            ])->get($this->baseUrl . '/province');

            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCities($provinceId)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'key' => $this->apiKey
            ])->get($this->baseUrl . '/city', [
                'province' => $provinceId
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCost(Request $request)
    {
        // Calculate shipping cost (for future use/EstimateShipping component)
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'key' => $this->apiKey
            ])->post($this->baseUrl . '/cost', [
                'origin' => config('services.rajaongkir.origin', 151), // Default Jakarta Barat
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
