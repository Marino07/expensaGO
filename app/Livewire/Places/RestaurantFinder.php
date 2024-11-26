<?php

namespace App\Livewire\Places;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class RestaurantFinder extends Component
{
    public $restaurants = [];
    public $search = 'Zagreb';
    public $loading = false;

    public function mount()
    {
        $this->searchRestaurants();
    }

    public function searchRestaurants()
    {
        $this->loading = true;
        $apiKey = env('GOOGLE_PLACES_API_KEY'); // app key for places and geo

        try {
            // First we  get location coordinates for Zagreb
            $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($this->search)."&key=".$apiKey;
            $geocodeResponse = Http::get($geocodeUrl)->json();

            //dd('Geocode response received', $geocodeResponse);

            if (isset($geocodeResponse['results'][0]['geometry']['location'])) {
                $location = $geocodeResponse['results'][0]['geometry']['location'];

                //dd('Location found', $location);

                // Then we  search for restaurants near that location
                $placesUrl = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?";
                $params = [
                    'location' => $location['lat'].','.$location['lng'],
                    'radius' => '5000',
                    'type' => 'restaurant',
                    'key' => $apiKey
                ];

                // checking full url
                $fullPlacesUrl = $placesUrl.http_build_query($params);
                //dd('Places URL', $fullPlacesUrl);

                $response = Http::get($fullPlacesUrl);

                // checking all response
                //dd('Places response', $response);

                $response = $response->json();
                //dd('Places response JSON', $response);

                if (isset($response['results'])) {
                    $this->restaurants = $response['results'];
                }
            }
        } catch (\Exception $e) {
            dd('Exception caught', $e->getMessage());
        }

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.places.restaurant-finder')->layout('layouts.trip');
    }
}
