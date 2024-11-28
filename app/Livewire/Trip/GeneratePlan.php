<?php

namespace App\Livewire\Trip;

use Livewire\Component;
use App\Models\Trip;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeneratePlan extends Component
{
    public $trip;
    public $plan = [];
    public $errorMessage = '';

    public function mount(Trip $trip)
    {
        $this->trip = $trip;
        $this->generatePlan();
    }

    public function generatePlan()
    {
        // Pretpostavimo da imamo API ključ za Google Places API
        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $location = $this->getLocationCoordinates($this->trip->location);

        if (!$location) {
            $this->errorMessage = 'Invalid location specified.';
            return;
        }

        // Dobijanje atrakcija u blizini
        $placesUrl = "https://maps.googleapis.com/maps/api/place/nearbysearch/json";
        $params = [
            'location' => $location['lat'] . ',' . $location['lng'],
            'radius' => '5000',
            'type' => 'tourist_attraction',
            'key' => $apiKey
        ];

        $response = Http::get($placesUrl, $params)->json();
        Log::info('Google Places API response', ['response' => $response]);

        $attractions = $response['results'] ?? [];

        // Provera da li je niz atrakcija prazan
        if (empty($attractions)) {
            $this->errorMessage = 'No attractions found for the specified location.';
            return;
        }

        // Generisanje plana za svaki dan
        $startDate = new \DateTime($this->trip->start_date);
        $endDate = new \DateTime($this->trip->end_date);
        $interval = $startDate->diff($endDate)->days;

        for ($i = 0; $i <= $interval; $i++) {
            $day = $startDate->modify('+1 day')->format('Y-m-d');
            $attraction = $attractions[array_rand($attractions)];
            $restaurant = $this->getNearbyRestaurant($attraction['geometry']['location']);

            $this->plan[] = [
                'day' => $day,
                'attraction' => $attraction['name'],
                'restaurant' => $restaurant['name'] ?? 'No restaurant found',
                'walking_time' => $this->calculateWalkingTime($attraction['geometry']['location'], $restaurant['geometry']['location'] ?? null),
                'attraction_cost' => rand(10, 50), // Pretpostavimo cenu ulaznice
                'restaurant_cost' => rand(20, 100) // Pretpostavimo cenu obroka
            ];
        }
    }

    public function getLocationCoordinates($location)
    {
        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json";
        $params = [
            'address' => $location,
            'key' => $apiKey
        ];

        $response = Http::get($geocodeUrl, $params)->json();
        Log::info('Google Geocode API response', ['response' => $response]);

        if (isset($response['results'][0]['geometry']['location'])) {
            return $response['results'][0]['geometry']['location'];
        }

        return null;
    }

    public function getNearbyRestaurant($location)
    {
        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $placesUrl = "https://maps.googleapis.com/maps/api/place/nearbysearch/json";
        $params = [
            'location' => $location['lat'] . ',' . $location['lng'],
            'radius' => '1000',
            'type' => 'restaurant',
            'key' => $apiKey
        ];

        $response = Http::get($placesUrl, $params)->json();
        return $response['results'][0] ?? null;
    }

    public function calculateWalkingTime($start, $end)
    {
        if ($end === null) {
            return 'N/A';
        }
        // Pretpostavimo da imamo funkciju koja računa vreme šetnje između dve tačke
        return rand(10, 30) . ' minutes';
    }

    public function render()
    {
        return view('livewire.trip.generate-plan', ['plan' => $this->plan, 'errorMessage' => $this->errorMessage])->layout('layouts.trip');
    }
}
