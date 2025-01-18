<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TicketmasterService
{
    private $apiKey;
    private $baseUrl = 'https://app.ticketmaster.com/discovery/v2';

    public function __construct()
    {
        $this->apiKey = config('services.ticketmaster.api_key');
    }

    public function searchEvents($location, $startDate = null, $endDate = null)
    {
        $cacheKey = "events_search_{$location}_{$startDate}_{$endDate}";

        Log::info('Starting event search', [
            'location' => $location,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return Cache::remember($cacheKey, 3600, function () use ($location, $startDate, $endDate) {
            $googlePlacesService = app(GooglePlacesService::class);
            $coordinates = $googlePlacesService->getCoordinates($location);

            if (!$coordinates) {
                Log::error('Could not get coordinates for location', ['location' => $location]);
                return [];
            }

            $params = [
                'apikey' => $this->apiKey,
                'latlong' => "{$coordinates['latitude']},{$coordinates['longitude']}",
                'radius' => '50',
                'unit' => 'km',
                'size' => 100,
                'sort' => 'date,asc',
                'locale' => '*'
            ];

            if ($startDate) {
                $startDateTime = Carbon::parse($startDate)->startOfDay();
                $params['startDateTime'] = $startDateTime->format('Y-m-d\TH:i:s\Z');
            }

            if ($endDate) {
                $endDateTime = Carbon::parse($endDate)->endOfDay();
                $params['endDateTime'] = $endDateTime->format('Y-m-d\TH:i:s\Z');
            }

            Log::info('Making API request with params', ['params' => $params]);

            try {
                $response = Http::get("{$this->baseUrl}/events", $params);
                $data = $response->json();

                Log::debug('Raw API Response', [
                    'status' => $response->status(),
                    'body' => json_encode($data)
                ]);

                if (!$response->successful()) {
                    Log::error('API request failed', [
                        'status' => $response->status(),
                        'error' => $data['errors'] ?? 'Unknown error'
                    ]);
                    return [];
                }

                $events = $data['_embedded']['events'] ?? [];

                Log::info('API request successful', [
                    'found_events' => count($events),
                    'response_status' => $response->status()
                ]);

                Log::info('Events found', [
                    'count' => count($events),
                    'first_event' => $events[0] ?? null
                ]);

                return $events;

            } catch (\Exception $e) {
                Log::error('API request exception', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return [];
            }
        });
    }

    public function getEventDetails($eventId)
    {
        $cacheKey = "event_details_{$eventId}";

        return Cache::remember($cacheKey, 3600, function () use ($eventId) {
            $response = Http::get("{$this->baseUrl}/events/{$eventId}", [
                'apikey' => $this->apiKey
            ]);

            return $response->json() ?? null;
        });
    }
}
