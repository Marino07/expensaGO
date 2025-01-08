<?php

namespace App\Livewire\Places;

use App\Models\Trip;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PlaceFinder extends Component
{
    public $places = [];
    public $search;
    public $loading = false;
    public $sortCriteria = 'rating';
    public $placeType = 'bar';
    public $var;
    public $geo_lat_lng;
    public $tutorialState;

    public function mount()
    {
        $trip = Trip::where('user_id', Auth::id())->latest()->first();
        if(!$trip){
            $this->search = 'London';
        }else{
            $this->search = $trip->location;
        }

        $this->tutorialState = auth()->user()->tutorial_completed;
        $this->searchPlaces();
    }
    public function logout()
    {
        Auth::logout(); // Odjava korisnika
        return redirect('/'); // Preusmeravanje na početnu stranicu
    }

    public function setUserLocation($lat, $lng)
    {
        $this->search = $this->getLocationName($lat, $lng);
        $this->searchPlaces();
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

    public function searchPlaces()
    {
        $this->loading = true;
        $apiKey = env('GOOGLE_PLACES_API_KEY');  // we need to set our own API key in .env file

        try {
            // First we get location coordinates for the search location
            $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($this->search)."&key=".$apiKey;
            $geocodeResponse = Http::get($geocodeUrl)->json();

            if (isset($geocodeResponse['results'][0]['geometry']['location'])) {
                $location = $geocodeResponse['results'][0]['geometry']['location'];
                $this->geo_lat_lng = $location['lat'] . ',' . $location['lng'];

                // Then we search for places near that location
                $placesUrl = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?";
                $params = [
                    'location' => $this->geo_lat_lng,
                    'radius' => '5000',
                    'type' => $this->placeType,
                    'key' => $apiKey
                ];

                $fullPlacesUrl = $placesUrl.http_build_query($params);
                $response = Http::get($fullPlacesUrl);
                $response = $response->json();

                if (isset($response['results'])) {
                    $this->places = $response['results'];
                    $this->sortPlaces();
                }
            }
        } catch (\Exception $e) {
            // Handle exception
        }

        $this->loading = false;

    }

    public function sortPlaces()
    {
        usort($this->places, function ($a, $b) {
            $ratingA = $a['rating'] ?? 0;
            $ratingB = $b['rating'] ?? 0;
            $priceA = $a['price_level'] ?? PHP_INT_MAX;
            $priceB = $b['price_level'] ?? PHP_INT_MAX;

            if ($this->sortCriteria == 'rating') {
                if ($ratingA == $ratingB) {
                    return $priceA <=> $priceB;
                }
                return $ratingB <=> $ratingA;
            } elseif ($this->sortCriteria == 'price') {
                if ($priceA == $priceB) {
                    return $ratingB <=> $ratingA;
                }
                return $priceA <=> $priceB;
            }

            return 0;
        });
    }

    public function updatedSortCriteria()
    {
        $this->sortPlaces();
    }

    public function test()
    {
        $this->var = 'test';
    }
    public function changeTutorial()
    {
        $this->tutorialState = !$this->tutorialState;
        $user = auth()->user();
        $user->tutorial_completed = $this->tutorialState;
        $user->save();
    }

    public function render()
    {
        return view('livewire.places.place-finder')->layout('layouts.trip');
    }
}
