<?php

namespace App\Livewire\Places;

use App\Models\Trip;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RestaurantFinder extends Component
{
    public $restaurants = [];
    public $search;
    public $loading = false;

    public function mount()
    {
        $trip = Trip::where('user_id', Auth::id())->latest()->first();
        $this->search = $trip->location;
        $this->searchRestaurants();
    }

    public function setUserLocation($lat, $lng)
    {
        $this->search = $this->getLocationName($lat, $lng);
        $this->searchRestaurants();
    }
    public function getLocationName($lat, $lng)
    {
        $apiKey = env('GOOGLE_PLACES_API_KEY');  // we need to set our own API key in .env file
        $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$apiKey}";
        $geocodeResponse = Http::get($geocodeUrl)->json();

        if (isset($geocodeResponse['results'][0]['formatted_address'])) {
            return $geocodeResponse['results'][0]['formatted_address'];
        }

        return "{$lat},{$lng}";
    }

    public function searchRestaurants()
    {
        $this->loading = true;
        $apiKey = env('GOOGLE_PLACES_API_KEY');  // we  need to set our own API key in .env file

        try {
            // First we  get location coordinates for the search location
            $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($this->search)."&key=".$apiKey;
            $geocodeResponse = Http::get($geocodeUrl)->json();

            if (isset($geocodeResponse['results'][0]['geometry']['location'])) {
                $location = $geocodeResponse['results'][0]['geometry']['location'];

                // Then we  search for restaurants near that location
                $placesUrl = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?";
                $params = [
                    'location' => $location['lat'].','.$location['lng'],
                    'radius' => '5000',
                    'type' => 'restaurant',
                    'key' => $apiKey
                ];

                $fullPlacesUrl = $placesUrl.http_build_query($params);
                $response = Http::get($fullPlacesUrl);
                $response = $response->json();

                if (isset($response['results'])) {
                    $this->restaurants = $response['results'];
                }
            }
        } catch (\Exception $e) {
            // Handle exception
        }

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.places.restaurant-finder')->layout('layouts.trip');
    }
}
