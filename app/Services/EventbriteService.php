<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EventbriteService
{
    private $apiKey;
    private $baseUrl = 'https://www.eventbriteapi.com/v3';

    public function __construct()
    {
        $this->apiKey = config('services.eventbrite.api_key');
    }

    public function searchEvents($location, $startDate = null, $endDate = null)
    {
        $cacheKey = "eventbrite_events_{$location}_{$startDate}_{$endDate}";

        return Cache::remember($cacheKey, 3600, function () use ($location, $startDate, $endDate) {
            $googlePlacesService = app(GooglePlacesService::class);
            $coordinates = $googlePlacesService->getCoordinates($location);

            if (!$coordinates) {
                Log::error('Could not get coordinates for Eventbrite search', ['location' => $location]);
                return [];
            }

            $params = [
                'location.latitude' => $coordinates['latitude'],
                'location.longitude' => $coordinates['longitude'],
                'location.within' => '50km',
                'expand' => 'venue,ticket_availability',
                'token' => $this->apiKey
            ];

            if ($startDate) {
                $params['start_date.range_start'] = Carbon::parse($startDate)->toIso8601String();
            }
            if ($endDate) {
                $params['start_date.range_end'] = Carbon::parse($endDate)->toIso8601String();
            }

            try {
                $response = Http::withToken($this->apiKey)
                    ->get("{$this->baseUrl}/events/search", $params);

                if (!$response->successful()) {
                    Log::error('Eventbrite API error', [
                        'status' => $response->status(),
                        'error' => $response->json()
                    ]);
                    return [];
                }

                $events = $response->json()['events'] ?? [];

                Log::info('Eventbrite events found', ['count' => count($events)]);

                return $events;
            } catch (\Exception $e) {
                Log::error('Eventbrite API exception', ['message' => $e->getMessage()]);
                return [];
            }
        });
    }
}
