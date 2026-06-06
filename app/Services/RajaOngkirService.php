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
        
        $this->accountType = config('rajaongkir.type') ?? config('rajaongkir.account_type') ?? 'starter';
        if ($this->accountType === 'pro') {
            $this->baseUrl = 'https://pro.rajaongkir.com/api';
        } elseif ($this->accountType === 'basic') {
            $this->baseUrl = 'https://api.rajaongkir.com/basic';
        } else {
            $this->baseUrl = 'https://api.rajaongkir.com/starter';
        }
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        return Cache::remember('rajaongkir_provinces', 60 * 24 * 7, function () {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->timeout(3)->get("{$this->baseUrl}/province");

                if ($response->successful() && $response->json('rajaongkir.status.code') == 200) {
                    $results = $response->json('rajaongkir.results');
                    
                    // If API returns data, use it
                    if (!empty($results)) {
                        return $results;
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('RajaOngkir API error (provinces): ' . $e->getMessage());
            }

            // Fallback mock data for development (when API key invalid/expired/timeout)
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
            
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->timeout(3)->get($url, $provinceId ? ['province' => $provinceId] : []);

                if ($response->successful() && $response->json('rajaongkir.status.code') == 200) {
                    $results = $response->json('rajaongkir.results');
                    
                    // If API returns data, use it
                    if (!empty($results)) {
                        return $results;
                    }
                }
            } catch (\Exception $e) {
                \Log::warning("RajaOngkir API error (cities): " . $e->getMessage());
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
            // Bali (province_id = 1)
            ['city_id' => '17', 'province_id' => '1', 'type' => 'Kabupaten', 'city_name' => 'Badung', 'postal_code' => '80351'],
            ['city_id' => '18', 'province_id' => '1', 'type' => 'Kabupaten', 'city_name' => 'Bangli', 'postal_code' => '80619'],
            ['city_id' => '19', 'province_id' => '1', 'type' => 'Kabupaten', 'city_name' => 'Buleleng', 'postal_code' => '81111'],
            ['city_id' => '20', 'province_id' => '1', 'type' => 'Kota', 'city_name' => 'Denpasar', 'postal_code' => '80227'],
            ['city_id' => '21', 'province_id' => '1', 'type' => 'Kabupaten', 'city_name' => 'Gianyar', 'postal_code' => '80511'],
            
            // Bangka Belitung (province_id = 2)
            ['city_id' => '26', 'province_id' => '2', 'type' => 'Kabupaten', 'city_name' => 'Bangka', 'postal_code' => '33212'],
            ['city_id' => '27', 'province_id' => '2', 'type' => 'Kabupaten', 'city_name' => 'Belitung', 'postal_code' => '33419'],
            ['city_id' => '28', 'province_id' => '2', 'type' => 'Kota', 'city_name' => 'Pangkal Pinang', 'postal_code' => '33115'],
            
            // Banten (province_id = 3)
            ['city_id' => '36', 'province_id' => '3', 'type' => 'Kota', 'city_name' => 'Cilegon', 'postal_code' => '42417'],
            ['city_id' => '106', 'province_id' => '3', 'type' => 'Kabupaten', 'city_name' => 'Lebak', 'postal_code' => '42319'],
            ['city_id' => '455', 'province_id' => '3', 'type' => 'Kota', 'city_name' => 'Tangerang', 'postal_code' => '15111'],
            ['city_id' => '456', 'province_id' => '3', 'type' => 'Kabupaten', 'city_name' => 'Tangerang', 'postal_code' => '15914'],
            ['city_id' => '457', 'province_id' => '3', 'type' => 'Kota', 'city_name' => 'Tangerang Selatan', 'postal_code' => '15332'],
            ['city_id' => '402', 'province_id' => '3', 'type' => 'Kota', 'city_name' => 'Serang', 'postal_code' => '42116'],
            
            // DI Yogyakarta (province_id = 5)
            ['city_id' => '39', 'province_id' => '5', 'type' => 'Kabupaten', 'city_name' => 'Bantul', 'postal_code' => '55715'],
            ['city_id' => '119', 'province_id' => '5', 'type' => 'Kabupaten', 'city_name' => 'Gunung Kidul', 'postal_code' => '55812'],
            ['city_id' => '419', 'province_id' => '5', 'type' => 'Kabupaten', 'city_name' => 'Sleman', 'postal_code' => '55513'],
            ['city_id' => '501', 'province_id' => '5', 'type' => 'Kota', 'city_name' => 'Yogyakarta', 'postal_code' => '55111'],
            
            // DKI Jakarta (province_id = 6)
            ['city_id' => '151', 'province_id' => '6', 'type' => 'Kota', 'city_name' => 'Jakarta Barat', 'postal_code' => '11220'],
            ['city_id' => '152', 'province_id' => '6', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat', 'postal_code' => '10540'],
            ['city_id' => '153', 'province_id' => '6', 'type' => 'Kota', 'city_name' => 'Jakarta Selatan', 'postal_code' => '12230'],
            ['city_id' => '154', 'province_id' => '6', 'type' => 'Kota', 'city_name' => 'Jakarta Timur', 'postal_code' => '13330'],
            ['city_id' => '155', 'province_id' => '6', 'type' => 'Kota', 'city_name' => 'Jakarta Utara', 'postal_code' => '14340'],
            ['city_id' => '156', 'province_id' => '6', 'type' => 'Kabupaten', 'city_name' => 'Kepulauan Seribu', 'postal_code' => '14550'],
            
            // Jawa Barat (province_id = 9)
            ['city_id' => '22', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Bandung', 'postal_code' => '40111'],
            ['city_id' => '23', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Bandung', 'postal_code' => '40311'],
            ['city_id' => '34', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Bandung Barat', 'postal_code' => '40721'],
            ['city_id' => '24', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Bekasi', 'postal_code' => '17121'],
            ['city_id' => '25', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Bekasi', 'postal_code' => '17837'],
            ['city_id' => '78', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Bogor', 'postal_code' => '16119'],
            ['city_id' => '79', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Bogor', 'postal_code' => '16911'],
            ['city_id' => '80', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Cirebon', 'postal_code' => '45116'],
            ['city_id' => '81', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Cirebon', 'postal_code' => '45611'],
            ['city_id' => '114', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Depok', 'postal_code' => '16416'],
            ['city_id' => '160', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Karawang', 'postal_code' => '41311'],
            ['city_id' => '175', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Kuningan', 'postal_code' => '45511'],
            ['city_id' => '260', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Majalengka', 'postal_code' => '45412'],
            ['city_id' => '449', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Sukabumi', 'postal_code' => '43114'],
            ['city_id' => '450', 'province_id' => '9', 'type' => 'Kabupaten', 'city_name' => 'Sukabumi', 'postal_code' => '43311'],
            ['city_id' => '482', 'province_id' => '9', 'type' => 'Kota', 'city_name' => 'Tasikmalaya', 'postal_code' => '46116'],
            
            // Jawa Tengah (province_id = 10)
            ['city_id' => '52', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Banyumas', 'postal_code' => '53114'],
            ['city_id' => '71', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Blora', 'postal_code' => '58219'],
            ['city_id' => '86', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Cilacap', 'postal_code' => '53211'],
            ['city_id' => '113', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Demak', 'postal_code' => '59511'],
            ['city_id' => '169', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Kendal', 'postal_code' => '51314'],
            ['city_id' => '170', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Klaten', 'postal_code' => '57411'],
            ['city_id' => '249', 'province_id' => '10', 'type' => 'Kota', 'city_name' => 'Magelang', 'postal_code' => '56133'],
            ['city_id' => '250', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Magelang', 'postal_code' => '56519'],
            ['city_id' => '327', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Pati', 'postal_code' => '59114'],
            ['city_id' => '335', 'province_id' => '10', 'type' => 'Kota', 'city_name' => 'Pekalongan', 'postal_code' => '51122'],
            ['city_id' => '336', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Pekalongan', 'postal_code' => '51161'],
            ['city_id' => '364', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Purwokerto', 'postal_code' => '53116'],
            ['city_id' => '398', 'province_id' => '10', 'type' => 'Kota', 'city_name' => 'Semarang', 'postal_code' => '50135'],
            ['city_id' => '399', 'province_id' => '10', 'type' => 'Kabupaten', 'city_name' => 'Semarang', 'postal_code' => '50511'],
            ['city_id' => '445', 'province_id' => '10', 'type' => 'Kota', 'city_name' => 'Surakarta (Solo)', 'postal_code' => '57111'],
            ['city_id' => '487', 'province_id' => '10', 'type' => 'Kota', 'city_name' => 'Tegal', 'postal_code' => '52114'],
            
            // Jawa Timur (province_id = 11)
            ['city_id' => '38', 'province_id' => '11', 'type' => 'Kabupaten', 'city_name' => 'Banyuwangi', 'postal_code' => '68416'],
            ['city_id' => '42', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Batu', 'postal_code' => '65311'],
            ['city_id' => '70', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Blitar', 'postal_code' => '66171'],
            ['city_id' => '77', 'province_id' => '11', 'type' => 'Kabupaten', 'city_name' => 'Bojonegoro', 'postal_code' => '62119'],
            ['city_id' => '122', 'province_id' => '11', 'type' => 'Kabupaten', 'city_name' => 'Gresik', 'postal_code' => '61115'],
            ['city_id' => '153', 'province_id' => '11', 'type' => 'Kabupaten', 'city_name' => 'Jember', 'postal_code' => '68113'],
            ['city_id' => '168', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Kediri', 'postal_code' => '64125'],
            ['city_id' => '256', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Malang', 'postal_code' => '65112'],
            ['city_id' => '257', 'province_id' => '11', 'type' => 'Kabupaten', 'city_name' => 'Malang', 'postal_code' => '65163'],
            ['city_id' => '269', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Madiun', 'postal_code' => '63122'],
            ['city_id' => '270', 'province_id' => '11', 'type' => 'Kabupaten', 'city_name' => 'Madiun', 'postal_code' => '63153'],
            ['city_id' => '273', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Mojokerto', 'postal_code' => '61316'],
            ['city_id' => '329', 'province_id' => '11', 'type' => 'Kabupaten', 'city_name' => 'Pasuruan', 'postal_code' => '67153'],
            ['city_id' => '330', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Pasuruan', 'postal_code' => '67118'],
            ['city_id' => '360', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Probolinggo', 'postal_code' => '67215'],
            ['city_id' => '396', 'province_id' => '11', 'type' => 'Kabupaten', 'city_name' => 'Sidoarjo', 'postal_code' => '61219'],
            ['city_id' => '444', 'province_id' => '11', 'type' => 'Kota', 'city_name' => 'Surabaya', 'postal_code' => '60119'],
            ['city_id' => '501', 'province_id' => '11', 'type' => 'Kabupaten', 'city_name' => 'Tuban', 'postal_code' => '62319'],
            
            // Sumatera Utara (province_id = 33)
            ['city_id' => '56', 'province_id' => '33', 'type' => 'Kabupaten', 'city_name' => 'Binjai', 'postal_code' => '20712'],
            ['city_id' => '112', 'province_id' => '33', 'type' => 'Kabupaten', 'city_name' => 'Deli Serdang', 'postal_code' => '20511'],
            ['city_id' => '210', 'province_id' => '33', 'type' => 'Kabupaten', 'city_name' => 'Labuhan Batu', 'postal_code' => '21412'],
            ['city_id' => '258', 'province_id' => '33', 'type' => 'Kota', 'city_name' => 'Medan', 'postal_code' => '20228'],
            ['city_id' => '331', 'province_id' => '33', 'type' => 'Kota', 'city_name' => 'Pematang Siantar', 'postal_code' => '21126'],
            ['city_id' => '484', 'province_id' => '33', 'type' => 'Kabupaten', 'city_name' => 'Tebing Tinggi', 'postal_code' => '20998'],
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
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->timeout(10)->post("{$this->baseUrl}/cost", [
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
        } catch (\Exception $e) {
            \Log::warning("RajaOngkir API error (cost): " . $e->getMessage());
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

    /**
     * Get city by ID (alias for getCity)
     */
    public function getCityById($cityId)
    {
        return $this->getCity($cityId);
    }

    /**
     * Get subdistricts by city ID
     * HYBRID APPROACH: Combines data from BOTH RajaOngkir AND Kodepos APIs
     * This ensures maximum coverage and completeness
     */
    public function getSubdistricts($cityId = null)
    {
        if (!$cityId) {
            return [];
        }

        // Cache subdistricts for 7 days
        $cacheKey = "rajaongkir_subdistricts_{$cityId}";
        
        return Cache::remember($cacheKey, 60 * 24 * 7, function () use ($cityId) {
            $rajaongkirData = [];
            $kodeposData = [];
            
            // ========================================
            // STEP 1: Get data from RajaOngkir API
            // ========================================
            try {
                // If account type is pro or basic, use the official RajaOngkir API
                if ($this->accountType === 'pro' || $this->accountType === 'basic') {
                    $response = Http::withHeaders([
                        'key' => $this->apiKey
                    ])->timeout(3)->get("{$this->baseUrl}/subdistrict", [
                        'city' => $cityId
                    ]);

                    if ($response->successful() && $response->json('rajaongkir.status.code') == 200) {
                        $results = $response->json('rajaongkir.results');
                        if (!empty($results)) {
                            foreach ($results as $item) {
                                $rajaongkirData[] = [
                                    'subdistrict_id' => $item['subdistrict_id'],
                                    'city_id' => $item['city_id'],
                                    'subdistrict_name' => $item['subdistrict_name'],
                                    'postal_code' => '',
                                    'source' => 'rajaongkir_official'
                                ];
                            }
                        }
                    }
                } else {
                    // For starter account, use the third-party Komerce API
                    $response = Http::withHeaders([
                        'key' => $this->apiKey
                    ])->timeout(3)->get("https://rajaongkir.komerce.id/api/v1/location/subdistrict", [
                        'city' => $cityId
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        
                        if (isset($data['rajaongkir']['status']['code']) && 
                            $data['rajaongkir']['status']['code'] == 200 &&
                            isset($data['rajaongkir']['results']) &&
                            is_array($data['rajaongkir']['results']) &&
                            count($data['rajaongkir']['results']) > 0) {
                            
                            foreach ($data['rajaongkir']['results'] as $item) {
                                $rajaongkirData[] = [
                                    'subdistrict_id' => $item['subdistrict_id'],
                                    'city_id' => $item['city_id'],
                                    'subdistrict_name' => $item['subdistrict_name'],
                                    'postal_code' => '',
                                    'source' => 'rajaongkir_komerce'
                                ];
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('RajaOngkir API error (subdistricts): ' . $e->getMessage());
            }

            // ========================================
            // STEP 2: Get data from Kodepos API
            // ========================================
            $city = $this->getCityInfo($cityId);
            if ($city) {
                try {
                    $regencyName = $this->normalizeRegencyName($city['city_name']);
                    $response = Http::timeout(2)->get("https://kodepos.vercel.app/search/", [
                        'q' => $regencyName
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        
                        if (isset($data['data']) && is_array($data['data']) && count($data['data']) > 0) {
                            // Group by district (kecamatan)
                            $seen = [];
                            
                            foreach ($data['data'] as $item) {
                                $districtName = $item['district'] ?? '';
                                $postalCode = $item['code'] ?? '';
                                
                                if ($districtName && !isset($seen[$districtName])) {
                                    $kodeposData[] = [
                                        'subdistrict_id' => 'kp_' . $postalCode,
                                        'city_id' => $cityId,
                                        'subdistrict_name' => $districtName,
                                        'postal_code' => $postalCode,
                                        'source' => 'kodepos'
                                    ];
                                    $seen[$districtName] = true;
                                }
                            }
                            
                            \Log::info("Kodepos API: Found " . count($kodeposData) . " subdistricts for city {$cityId}");
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Kodepos API error: ' . $e->getMessage());
                }
            }

            // ========================================
            // STEP 3: MERGE data from both sources
            // ========================================
            $mergedData = $this->mergeSubdistrictData($rajaongkirData, $kodeposData);
            
            if (count($mergedData) > 0) {
                \Log::info("HYBRID: Total " . count($mergedData) . " subdistricts for city {$cityId} (RajaOngkir: " . count($rajaongkirData) . ", Kodepos: " . count($kodeposData) . ")");
                return $mergedData;
            }

            // ========================================
            // STEP 4: Last resort - Mock data
            // ========================================
            \Log::warning("No data from both APIs for city {$cityId}, using mock data");
            return $this->getMockSubdistricts($cityId);
        });
    }

    /**
     * Merge subdistrict data from RajaOngkir and Kodepos APIs
     * Strategy: MAXIMUM COVERAGE with SMART DUPLICATE HANDLING
     * 
     * Rules:
     * 1. Add ALL data from BOTH APIs
     * 2. Remove EXACT duplicates (same normalized name + same postal code)
     * 3. Keep variations (same name but different postal code = different area)
     * 4. Enrich RajaOngkir data with postal codes from Kodepos when possible
     * 
     * This ensures maximum area coverage while avoiding true duplicates!
     */
    private function mergeSubdistrictData($rajaongkirData, $kodeposData)
    {
        $merged = [];
        $duplicateCheck = []; // Format: "normalized_name|postal_code" => true
        
        // Step 1: Add ALL RajaOngkir data first (priority for shipping)
        foreach ($rajaongkirData as $item) {
            $normalizedName = $this->normalizeSubdistrictName($item['subdistrict_name']);
            $postalCode = $item['postal_code'] ?? '';
            
            // Create unique key: name + postal (empty postal = unique)
            $uniqueKey = $normalizedName . '|' . $postalCode;
            
            if (!isset($duplicateCheck[$uniqueKey])) {
                $merged[] = $item;
                $duplicateCheck[$uniqueKey] = true;
                
                // Also track by name only for enrichment
                if (empty($postalCode)) {
                    $duplicateCheck[$normalizedName . '|empty'] = count($merged) - 1;
                }
            }
        }
        
        // Step 2: Process ALL Kodepos data
        foreach ($kodeposData as $item) {
            $normalizedName = $this->normalizeSubdistrictName($item['subdistrict_name']);
            $postalCode = $item['postal_code'] ?? '';
            
            // Create unique key
            $uniqueKey = $normalizedName . '|' . $postalCode;
            
            // Check if this exact combination already exists
            if (isset($duplicateCheck[$uniqueKey])) {
                // Exact duplicate - skip
                continue;
            }
            
            // Check if we can enrich existing RajaOngkir data (same name, no postal)
            $enrichKey = $normalizedName . '|empty';
            if (isset($duplicateCheck[$enrichKey]) && !empty($postalCode)) {
                // Found RajaOngkir data without postal code - enrich it
                $index = $duplicateCheck[$enrichKey];
                if (isset($merged[$index]) && empty($merged[$index]['postal_code'])) {
                    $merged[$index]['postal_code'] = $postalCode;
                    $merged[$index]['source'] = 'rajaongkir+kodepos';
                    
                    // Update duplicate check with new postal
                    $newKey = $normalizedName . '|' . $postalCode;
                    $duplicateCheck[$newKey] = true;
                    unset($duplicateCheck[$enrichKey]);
                    continue;
                }
            }
            
            // Not a duplicate - add it
            $merged[] = $item;
            $duplicateCheck[$uniqueKey] = true;
        }
        
        // Step 3: Sort by subdistrict name
        usort($merged, function($a, $b) {
            $nameCompare = strcmp($a['subdistrict_name'], $b['subdistrict_name']);
            if ($nameCompare !== 0) {
                return $nameCompare;
            }
            // If same name, sort by postal code
            return strcmp($a['postal_code'] ?? '', $b['postal_code'] ?? '');
        });
        
        // Log statistics
        $rajaongkirCount = count($rajaongkirData);
        $kodeposCount = count($kodeposData);
        $mergedCount = count($merged);
        $duplicatesRemoved = ($rajaongkirCount + $kodeposCount) - $mergedCount;
        
        \Log::info("Merge statistics for city: RajaOngkir={$rajaongkirCount}, Kodepos={$kodeposCount}, Merged={$mergedCount}, Duplicates removed={$duplicatesRemoved}");
        
        return $merged;
    }

    /**
     * Normalize subdistrict name for comparison
     * Removes common prefixes and converts to lowercase
     */
    private function normalizeSubdistrictName($name)
    {
        // Remove common prefixes
        $name = preg_replace('/^(Kecamatan|Kec\.?)\s+/i', '', $name);
        
        // Convert to lowercase and trim
        $name = strtolower(trim($name));
        
        // Remove extra spaces
        $name = preg_replace('/\s+/', ' ', $name);
        
        return $name;
    }

    /**
     * Get city info from cache or API
     */
    private function getCityInfo($cityId)
    {
        $cities = $this->getCities();
        foreach ($cities as $city) {
            if ($city['city_id'] == $cityId) {
                return $city;
            }
        }
        return null;
    }

    /**
     * Normalize regency name for Kodepos API search
     */
    private function normalizeRegencyName($cityName)
    {
        // Remove "Kota" or "Kabupaten" prefix
        $cityName = preg_replace('/^(Kota|Kabupaten)\s+/i', '', $cityName);
        return trim($cityName);
    }

    /**
     * Mock subdistricts data for development
     */
    private function getMockSubdistricts($cityId = null)
    {
        $subdistricts = [
            // Jakarta Selatan (city_id = 153)
            ['subdistrict_id' => '1001', 'city_id' => '153', 'subdistrict_name' => 'Cilandak', 'postal_code' => '12430'],
            ['subdistrict_id' => '1002', 'city_id' => '153', 'subdistrict_name' => 'Jagakarsa', 'postal_code' => '12620'],
            ['subdistrict_id' => '1003', 'city_id' => '153', 'subdistrict_name' => 'Kebayoran Baru', 'postal_code' => '12110'],
            ['subdistrict_id' => '1004', 'city_id' => '153', 'subdistrict_name' => 'Kebayoran Lama', 'postal_code' => '12210'],
            ['subdistrict_id' => '1005', 'city_id' => '153', 'subdistrict_name' => 'Mampang Prapatan', 'postal_code' => '12790'],
            ['subdistrict_id' => '1006', 'city_id' => '153', 'subdistrict_name' => 'Pancoran', 'postal_code' => '12780'],
            ['subdistrict_id' => '1007', 'city_id' => '153', 'subdistrict_name' => 'Pasar Minggu', 'postal_code' => '12510'],
            ['subdistrict_id' => '1008', 'city_id' => '153', 'subdistrict_name' => 'Pesanggrahan', 'postal_code' => '12270'],
            ['subdistrict_id' => '1009', 'city_id' => '153', 'subdistrict_name' => 'Setiabudi', 'postal_code' => '12910'],
            ['subdistrict_id' => '1010', 'city_id' => '153', 'subdistrict_name' => 'Tebet', 'postal_code' => '12810'],

            // Bandung Kota (city_id = 22)
            ['subdistrict_id' => '2001', 'city_id' => '22', 'subdistrict_name' => 'Andir', 'postal_code' => '40181'],
            ['subdistrict_id' => '2002', 'city_id' => '22', 'subdistrict_name' => 'Antapani', 'postal_code' => '40291'],
            ['subdistrict_id' => '2003', 'city_id' => '22', 'subdistrict_name' => 'Arcamanik', 'postal_code' => '40293'],
            ['subdistrict_id' => '2004', 'city_id' => '22', 'subdistrict_name' => 'Astana Anyar', 'postal_code' => '40241'],
            ['subdistrict_id' => '2005', 'city_id' => '22', 'subdistrict_name' => 'Babakan Ciparay', 'postal_code' => '40221'],
            ['subdistrict_id' => '2006', 'city_id' => '22', 'subdistrict_name' => 'Bandung Kidul', 'postal_code' => '40263'],
            ['subdistrict_id' => '2007', 'city_id' => '22', 'subdistrict_name' => 'Bandung Kulon', 'postal_code' => '40212'],
            ['subdistrict_id' => '2008', 'city_id' => '22', 'subdistrict_name' => 'Bandung Wetan', 'postal_code' => '40114'],
            ['subdistrict_id' => '2009', 'city_id' => '22', 'subdistrict_name' => 'Batununggal', 'postal_code' => '40266'],
            ['subdistrict_id' => '2010', 'city_id' => '22', 'subdistrict_name' => 'Bojongloa Kaler', 'postal_code' => '40231'],
            ['subdistrict_id' => '2011', 'city_id' => '22', 'subdistrict_name' => 'Bojongloa Kidul', 'postal_code' => '40235'],
            ['subdistrict_id' => '2012', 'city_id' => '22', 'subdistrict_name' => 'Buahbatu', 'postal_code' => '40286'],
            ['subdistrict_id' => '2013', 'city_id' => '22', 'subdistrict_name' => 'Cibeunying Kaler', 'postal_code' => '40122'],
            ['subdistrict_id' => '2014', 'city_id' => '22', 'subdistrict_name' => 'Cibeunying Kidul', 'postal_code' => '40121'],
            ['subdistrict_id' => '2015', 'city_id' => '22', 'subdistrict_name' => 'Cibiru', 'postal_code' => '40614'],
            ['subdistrict_id' => '2016', 'city_id' => '22', 'subdistrict_name' => 'Cicendo', 'postal_code' => '40172'],
            ['subdistrict_id' => '2017', 'city_id' => '22', 'subdistrict_name' => 'Cidadap', 'postal_code' => '40141'],
            ['subdistrict_id' => '2018', 'city_id' => '22', 'subdistrict_name' => 'Cinambo', 'postal_code' => '40294'],
            ['subdistrict_id' => '2019', 'city_id' => '22', 'subdistrict_name' => 'Coblong', 'postal_code' => '40132'],
            ['subdistrict_id' => '2020', 'city_id' => '22', 'subdistrict_name' => 'Gedebage', 'postal_code' => '40295'],
            ['subdistrict_id' => '2021', 'city_id' => '22', 'subdistrict_name' => 'Kiaracondong', 'postal_code' => '40281'],
            ['subdistrict_id' => '2022', 'city_id' => '22', 'subdistrict_name' => 'Lengkong', 'postal_code' => '40261'],
            ['subdistrict_id' => '2023', 'city_id' => '22', 'subdistrict_name' => 'Mandalajati', 'postal_code' => '40195'],
            ['subdistrict_id' => '2024', 'city_id' => '22', 'subdistrict_name' => 'Panyileukan', 'postal_code' => '40292'],
            ['subdistrict_id' => '2025', 'city_id' => '22', 'subdistrict_name' => 'Rancasari', 'postal_code' => '40286'],
            ['subdistrict_id' => '2026', 'city_id' => '22', 'subdistrict_name' => 'Regol', 'postal_code' => '40251'],
            ['subdistrict_id' => '2027', 'city_id' => '22', 'subdistrict_name' => 'Sukajadi', 'postal_code' => '40162'],
            ['subdistrict_id' => '2028', 'city_id' => '22', 'subdistrict_name' => 'Sukasari', 'postal_code' => '40152'],
            ['subdistrict_id' => '2029', 'city_id' => '22', 'subdistrict_name' => 'Sumur Bandung', 'postal_code' => '40112'],
            ['subdistrict_id' => '2030', 'city_id' => '22', 'subdistrict_name' => 'Ujung Berung', 'postal_code' => '40611'],

            // Surabaya (city_id = 444)
            ['subdistrict_id' => '3001', 'city_id' => '444', 'subdistrict_name' => 'Asemrowo', 'postal_code' => '60182'],
            ['subdistrict_id' => '3002', 'city_id' => '444', 'subdistrict_name' => 'Benowo', 'postal_code' => '60198'],
            ['subdistrict_id' => '3003', 'city_id' => '444', 'subdistrict_name' => 'Bubutan', 'postal_code' => '60174'],
            ['subdistrict_id' => '3004', 'city_id' => '444', 'subdistrict_name' => 'Bulak', 'postal_code' => '60121'],
            ['subdistrict_id' => '3005', 'city_id' => '444', 'subdistrict_name' => 'Dukuh Pakis', 'postal_code' => '60225'],
            ['subdistrict_id' => '3006', 'city_id' => '444', 'subdistrict_name' => 'Gayungan', 'postal_code' => '60235'],
            ['subdistrict_id' => '3007', 'city_id' => '444', 'subdistrict_name' => 'Genteng', 'postal_code' => '60275'],
            ['subdistrict_id' => '3008', 'city_id' => '444', 'subdistrict_name' => 'Gubeng', 'postal_code' => '60281'],
            ['subdistrict_id' => '3009', 'city_id' => '444', 'subdistrict_name' => 'Gunung Anyar', 'postal_code' => '60294'],
            ['subdistrict_id' => '3010', 'city_id' => '444', 'subdistrict_name' => 'Jambangan', 'postal_code' => '60252'],
            ['subdistrict_id' => '3011', 'city_id' => '444', 'subdistrict_name' => 'Karang Pilang', 'postal_code' => '60221'],
            ['subdistrict_id' => '3012', 'city_id' => '444', 'subdistrict_name' => 'Kenjeran', 'postal_code' => '60127'],
            ['subdistrict_id' => '3013', 'city_id' => '444', 'subdistrict_name' => 'Krembangan', 'postal_code' => '60175'],
            ['subdistrict_id' => '3014', 'city_id' => '444', 'subdistrict_name' => 'Lakarsantri', 'postal_code' => '60213'],
            ['subdistrict_id' => '3015', 'city_id' => '444', 'subdistrict_name' => 'Mulyorejo', 'postal_code' => '60115'],
            ['subdistrict_id' => '3016', 'city_id' => '444', 'subdistrict_name' => 'Pabean Cantian', 'postal_code' => '60164'],
            ['subdistrict_id' => '3017', 'city_id' => '444', 'subdistrict_name' => 'Pakal', 'postal_code' => '60197'],
            ['subdistrict_id' => '3018', 'city_id' => '444', 'subdistrict_name' => 'Rungkut', 'postal_code' => '60293'],
            ['subdistrict_id' => '3019', 'city_id' => '444', 'subdistrict_name' => 'Sambikerep', 'postal_code' => '60217'],
            ['subdistrict_id' => '3020', 'city_id' => '444', 'subdistrict_name' => 'Sawahan', 'postal_code' => '60251'],
            ['subdistrict_id' => '3021', 'city_id' => '444', 'subdistrict_name' => 'Semampir', 'postal_code' => '60155'],
            ['subdistrict_id' => '3022', 'city_id' => '444', 'subdistrict_name' => 'Simokerto', 'postal_code' => '60143'],
            ['subdistrict_id' => '3023', 'city_id' => '444', 'subdistrict_name' => 'Sukolilo', 'postal_code' => '60111'],
            ['subdistrict_id' => '3024', 'city_id' => '444', 'subdistrict_name' => 'Sukomanunggal', 'postal_code' => '60189'],
            ['subdistrict_id' => '3025', 'city_id' => '444', 'subdistrict_name' => 'Tambaksari', 'postal_code' => '60136'],
            ['subdistrict_id' => '3026', 'city_id' => '444', 'subdistrict_name' => 'Tandes', 'postal_code' => '60186'],
            ['subdistrict_id' => '3027', 'city_id' => '444', 'subdistrict_name' => 'Tegalsari', 'postal_code' => '60262'],
            ['subdistrict_id' => '3028', 'city_id' => '444', 'subdistrict_name' => 'Tenggilis Mejoyo', 'postal_code' => '60299'],
            ['subdistrict_id' => '3029', 'city_id' => '444', 'subdistrict_name' => 'Wiyung', 'postal_code' => '60229'],
            ['subdistrict_id' => '3030', 'city_id' => '444', 'subdistrict_name' => 'Wonocolo', 'postal_code' => '60237'],
            ['subdistrict_id' => '3031', 'city_id' => '444', 'subdistrict_name' => 'Wonokromo', 'postal_code' => '60243'],

            // Add default subdistricts for other cities
            ['subdistrict_id' => '9001', 'city_id' => 'default', 'subdistrict_name' => 'Kecamatan 1', 'postal_code' => '10001'],
            ['subdistrict_id' => '9002', 'city_id' => 'default', 'subdistrict_name' => 'Kecamatan 2', 'postal_code' => '10002'],
            ['subdistrict_id' => '9003', 'city_id' => 'default', 'subdistrict_name' => 'Kecamatan 3', 'postal_code' => '10003'],
        ];

        // Filter by city if specified
        if ($cityId) {
            $filtered = array_filter($subdistricts, function($subdistrict) use ($cityId) {
                return $subdistrict['city_id'] == $cityId;
            });
            
            // If no specific subdistricts found, return default ones
            if (empty($filtered)) {
                return array_filter($subdistricts, function($subdistrict) {
                    return $subdistrict['city_id'] == 'default';
                });
            }
            
            return array_values($filtered);
        }

        return $subdistricts;
    }
}
