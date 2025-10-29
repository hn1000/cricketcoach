<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeolocationService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google.maps_api_key', '');
    }

    /**
     * Convert an address or postcode to coordinates
     *
     * @param string $address
     * @return array|null ['latitude' => float, 'longitude' => float]
     */
    public function geocode(string $address): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning('Google Maps API key not configured');
            return null;
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === 'OK' && !empty($data['results'])) {
                    $location = $data['results'][0]['geometry']['location'];

                    return [
                        'latitude' => $location['lat'],
                        'longitude' => $location['lng'],
                    ];
                }
            }

            Log::warning('Geocoding failed', [
                'address' => $address,
                'status' => $data['status'] ?? 'unknown',
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Geocoding exception', [
                'address' => $address,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Calculate distance between two coordinates (in miles)
     * Using Haversine formula
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float Distance in miles
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusMiles = 3959;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusMiles * $c;
    }

    /**
     * Get bounding box coordinates for a given point and radius
     *
     * @param float $latitude
     * @param float $longitude
     * @param float $radiusMiles
     * @return array ['minLat' => float, 'maxLat' => float, 'minLng' => float, 'maxLng' => float]
     */
    public function getBoundingBox(float $latitude, float $longitude, float $radiusMiles): array
    {
        $earthRadiusMiles = 3959;

        // Convert radius from miles to radians
        $angularRadius = $radiusMiles / $earthRadiusMiles;

        $minLat = $latitude - rad2deg($angularRadius);
        $maxLat = $latitude + rad2deg($angularRadius);

        // Account for longitude changes with latitude
        $deltaLng = rad2deg(asin(sin($angularRadius) / cos(deg2rad($latitude))));

        $minLng = $longitude - $deltaLng;
        $maxLng = $longitude + $deltaLng;

        return [
            'minLat' => $minLat,
            'maxLat' => $maxLat,
            'minLng' => $minLng,
            'maxLng' => $maxLng,
        ];
    }
}
