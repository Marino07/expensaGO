<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GooglePlacesService
{
    private $apiKey;
    private $baseUrl = 'https://maps.googleapis.com/maps/api/place';

    public function __construct()
    {
        $this->apiKey = config('services.google.places_api_key');
    }

    public function searchPlaces($location, $type, $radius = 5000)
    {
        $cacheKey = "places_search_{$location}_{$type}_{$radius}";

        return Cache::remember($cacheKey, 3600, function () use ($location, $type, $radius) {
            $response = Http::get("{$this->baseUrl}/nearbysearch/json", [
                'key' => $this->apiKey,
                'location' => $location,
                'radius' => $radius,
                'type' => $type,
            ]);

            return $response->json()['results'] ?? [];
        });
    }

    public function getPlaceDetails($placeId)
    {
        $cacheKey = "place_details_{$placeId}";

        return Cache::remember($cacheKey, 3600, function () use ($placeId) {
            $response = Http::get("{$this->baseUrl}/details/json", [
                'key' => $this->apiKey,
                'place_id' => $placeId,
                'fields' => 'name,rating,formatted_address,price_level,photos,opening_hours,website'
            ]);

            return $response->json()['result'] ?? null;
        });
    }

    public function searchNearbyAttractions($location, $preferences = [])
    {
        $types = $preferences['attraction_types'] ?? ['tourist_attraction'];
        $results = [];

        foreach ($types as $type) {
            $places = $this->searchPlaces($location, $type);
            $results = array_merge($results, $places);
        }

        return array_slice($results, 0, 5);
    }

    public function getCoordinates(string $location)
    {
        $cacheKey = "geocode_" . md5($location);

        return Cache::remember($cacheKey, 86400, function () use ($location) {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $location,
                'key' => $this->apiKey
            ]);

            $data = $response->json();

            if ($data['status'] === 'OK' && !empty($data['results'])) {
                $location = $data['results'][0]['geometry']['location'];
                return [
                    'latitude' => $location['lat'],
                    'longitude' => $location['lng']
                ];
            }

            return null;
        });
    }
}
