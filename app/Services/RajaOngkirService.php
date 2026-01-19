<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected ?string $apiKey;
    protected string $baseUrl;
    protected string $accountType;

    public function __construct()
    {
        $this->apiKey = config('rajaongkir.api_key');
        
        if (empty($this->apiKey)) {
            throw new \Exception('RajaOngkir API key is not configured. Please set RAJAONGKIR_API_KEY in your .env file.');
        }
        
        $this->accountType = config('rajaongkir.account_type', 'starter');
        $this->baseUrl = $this->accountType === 'pro' 
            ? 'https://pro.rajaongkir.com/api' 
            : 'https://api.rajaongkir.com/starter';
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        return Cache::remember('rajaongkir_provinces', 60 * 24 * 7, function () {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get("{$this->baseUrl}/province");

            if ($response->successful() && $response->json('rajaongkir.status.code') == 200) {
                $results = $response->json('rajaongkir.results');
                
                // If API returns data, use it
                if (!empty($results)) {
                    return $results;
                }
            }

            // Fallback mock data for development (when API key invalid/expired)
            return $this->getMockProvinces();
        });
    }

    /**
     * Mock provinces data for development
     */
    private function getMockProvinces()
    {
        return [
            ['province_id' => '1', 'province' => 'Bali'],
            ['province_id' => '2', 'province' => 'Bangka Belitung'],
            ['province_id' => '3', 'province' => 'Banten'],
            ['province_id' => '4', 'province' => 'Bengkulu'],
            ['province_id' => '5', 'province' => 'DI Yogyakarta'],
            ['province_id' => '6', 'province' => 'DKI Jakarta'],
            ['province_id' => '7', 'province' => 'Gorontalo'],
            ['province_id' => '8', 'province' => 'Jambi'],
            ['province_id' => '9', 'province' => 'Jawa Barat'],
            ['province_id' => '10', 'province' => 'Jawa Tengah'],
            ['province_id' => '11', 'province' => 'Jawa Timur'],
            ['province_id' => '12', 'province' => 'Kalimantan Barat'],
            ['province_id' => '13', 'province' => 'Kalimantan Selatan'],
            ['province_id' => '14', 'province' => 'Kalimantan Tengah'],
            ['province_id' => '15', 'province' => 'Kalimantan Timur'],
            ['province_id' => '16', 'province' => 'Kalimantan Utara'],
            ['province_id' => '17', 'province' => 'Kepulauan Riau'],
            ['province_id' => '18', 'province' => 'Lampung'],
            ['province_id' => '19', 'province' => 'Maluku'],
            ['province_id' => '20', 'province' => 'Maluku Utara'],
            ['province_id' => '21', 'province' => 'Nusa Tenggara Barat (NTB)'],
            ['province_id' => '22', 'province' => 'Nusa Tenggara Timur (NTT)'],
            ['province_id' => '23', 'province' => 'Papua'],
            ['province_id' => '24', 'province' => 'Papua Barat'],
            ['province_id' => '25', 'province' => 'Riau'],
            ['province_id' => '26', 'province' => 'Sulawesi Barat'],
            ['province_id' => '27', 'province' => 'Sulawesi Selatan'],
            ['province_id' => '28', 'province' => 'Sulawesi Tengah'],
            ['province_id' => '29', 'province' => 'Sulawesi Tenggara'],
            ['province_id' => '30', 'province' => 'Sulawesi Utara'],
            ['province_id' => '31', 'province' => 'Sumatera Barat'],
            ['province_id' => '32', 'province' => 'Sumatera Selatan'],
            ['province_id' => '33', 'province' => 'Sumatera Utara'],
            ['province_id' => '34', 'province' => 'Aceh'],
        ];
    }

    /**
     * Get cities by province ID
     */
    public function getCities($provinceId = null)
    {
        $cacheKey = $provinceId ? "rajaongkir_cities_{$provinceId}" : 'rajaongkir_cities_all';
        
        return Cache::remember($cacheKey, 60 * 24 * 7, function () use ($provinceId) {
            $url = "{$this->baseUrl}/city";
            
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get($url, $provinceId ? ['province' => $provinceId] : []);

            if ($response->successful() && $response->json('rajaongkir.status.code') == 200) {
                $results = $response->json('rajaongkir.results');
                
                // If API returns data, use it
                if (!empty($results)) {
                    return $results;
                }
            }

            // Fallback mock data
            return $this->getMockCities($provinceId);
        });
    }

    /**
     * Mock cities data for development
     */
    private function getMockCities($provinceId = null)
    {
        $cities = [
            // DKI Jakarta (province_id = 6)
            ['city_id' => '151', 'province_id' => '6', 'type' => 'Kota', 'city_name' => 'Jakarta Barat', 'postal_code' => '11220'],
            ['city_id' => '152', 'province_id' => '6', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat', 'postal_code' => '10540'],
            ['city_id' => '153', 'province_id' => '6', 'type' => 'Kota', 'city_name' => 'Jakarta Selatan', 'postal_code' => '12230'],
            ['city_id' => '154', 'province_id' => '6', 'type' => 'Kota', 'city_name' => 'Jakarta Timur', 'postal_code' => '13330'],
            ['city_id' => '155', 'province_id' => '6', 'type' => 'Kota', 'city_name' => 'Jakarta Utara', 'postal_code' => '14340'],
            
            // Jawa Barat (province_id = 9)
            ['city_id' => '22', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Bandung', 'postal_code' => '40111'],
            ['city_id' => '23', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Bandung', 'postal_code' => '40311'],
            ['city_id' => '24', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Bekasi', 'postal_code' => '17121'],
            ['city_id' => '25', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Bekasi', 'postal_code' => '17837'],
            ['city_id' => '78', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Bogor', 'postal_code' => '16119'],
            ['city_id' => '80', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Cirebon', 'postal_code' => '45116'],
            ['city_id' => '114', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Depok', 'postal_code' => '16416'],
            
            // Jawa Tengah (province_id = 10)
            ['city_id' => '398', 'province_id' => '10', 'type' => 'Kota', 'city_name' => 'Semarang', 'postal_code' => '50135'],
            ['city_id' => '399', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Semarang', 'postal_code' => '50511'],
            ['city_id' => '445', 'province_id' => '10', 'type' => 'Kota', 'city_name' => 'Surakarta (Solo)', 'postal_code' => '57111'],
            
            // Jawa Timur (province_id = 11)
            ['city_id' => '444', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Surabaya', 'postal_code' => '60119'],
            ['city_id' => '256', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Malang', 'postal_code' => '65112'],
            
            // DI Yogyakarta (province_id = 5)
            ['city_id' => '501', 'province_id' => '5', 'type' => 'Kota', 'city_name' => 'Yogyakarta', 'postal_code' => '55111'],
        ];

        // Filter by province if specified
        if ($provinceId) {
            return array_values(array_filter($cities, function($city) use ($provinceId) {
                return $city['province_id'] == $provinceId;
            }));
        }

        return $cities;
    }

    /**
     * Get shipping cost
     * 
     * @param int $origin Origin city ID
     * @param int $destination Destination city ID
     * @param int $weight Weight in grams
     * @param string $courier Courier code (jne, pos, tiki, etc)
     */
    public function getCost($origin, $destination, $weight, $courier = 'jne')
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->post("{$this->baseUrl}/cost", [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier
        ]);

        if ($response->successful() && $response->json('rajaongkir.status.code') == 200) {
            $results = $response->json('rajaongkir.results');
            
            if (!empty($results)) {
                return $results;
            }
        }

        // Fallback mock cost data
        return $this->getMockCost($weight, $courier);
    }

    /**
     * Mock shipping cost data for development
     */
    private function getMockCost($weight, $courier)
    {
        $baseRate = ceil($weight / 1000) * 9000; // Rp 9.000 per kg
        
        $costs = [
            'jne' => [
                'code' => 'jne',
                'name' => 'JNE',
                'costs' => [
                    [
                        'service' => 'REG',
                        'description' => 'Layanan Reguler',
                        'cost' => [
                            ['value' => $baseRate, 'etd' => '2-3', 'note' => '']
                        ]
                    ],
                    [
                        'service' => 'YES',
                        'description' => 'Yakin Esok Sampai',
                        'cost' => [
                            ['value' => $baseRate * 2, 'etd' => '1-1', 'note' => '']
                        ]
                    ],
                ]
            ],
            'pos' => [
                'code' => 'pos',
                'name' => 'POS Indonesia',
                'costs' => [
                    [
                        'service' => 'Paket Kilat Khusus',
                        'description' => 'Pos Kilat Khusus',
                        'cost' => [
                            ['value' => $baseRate * 0.8, 'etd' => '2-4', 'note' => '']
                        ]
                    ],
                ]
            ],
            'tiki' => [
                'code' => 'tiki',
                'name' => 'TIKI',
                'costs' => [
                    [
                        'service' => 'REG',
                        'description' => 'Regular Service',
                        'cost' => [
                            ['value' => $baseRate * 0.9, 'etd' => '3-5', 'note' => '']
                        ]
                    ],
                    [
                        'service' => 'ONS',
                        'description' => 'Over Night Service',
                        'cost' => [
                            ['value' => $baseRate * 1.8, 'etd' => '1-1', 'note' => '']
                        ]
                    ],
                ]
            ],
        ];

        return isset($costs[$courier]) ? [$costs[$courier]] : [];
    }

    /**
     * Get multiple courier costs at once
     */
    public function getMultipleCosts($origin, $destination, $weight, array $couriers = ['jne', 'pos', 'tiki'])
    {
        $results = [];
        
        foreach ($couriers as $courier) {
            $cost = $this->getCost($origin, $destination, $weight, $courier);
            if ($cost) {
                $results[] = $cost[0]; // RajaOngkir returns array with single courier result
            }
        }

        return $results;
    }

    /**
     * Get province by ID
     */
    public function getProvince($provinceId)
    {
        $provinces = $this->getProvinces();
        return collect($provinces)->firstWhere('province_id', $provinceId);
    }

    /**
     * Get city by ID
     */
    public function getCity($cityId)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get("{$this->baseUrl}/city", ['id' => $cityId]);

        if ($response->successful() && $response->json('rajaongkir.status.code') == 200) {
            return $response->json('rajaongkir.results');
        }

        return null;
    }
}
