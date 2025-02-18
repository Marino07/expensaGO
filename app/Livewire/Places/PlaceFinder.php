<?php

namespace App\Livewire\Places;

use App\Models\Trip;
use Livewire\Component;
use App\Models\SavedItem;
use App\Models\SuggestionImages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PlaceFinder extends Component
{
    public $places = [];
    public $search;
    public $loading = false;
    public $sortCriteria = 'rating';
    public $placeType = 'restaurant';
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

    public function updated($propertyName)
    {
        if ($propertyName === 'search') {
            $this->searchPlaces();
        }
    }

    public function searchPlaces()
    {
        $this->loading = true;
        $apiKey = env('GOOGLE_PLACES_API_KEY');

        try {
            if (empty($this->search)) {
                return;
            }

            // First get location coordinates
            $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($this->search)."&key=".$apiKey;
            $geocodeResponse = Http::get($geocodeUrl)->json();

            if (isset($geocodeResponse['results'][0]['geometry']['location'])) {
                $location = $geocodeResponse['results'][0]['geometry']['location'];
                $this->geo_lat_lng = $location['lat'] . ',' . $location['lng'];

                // Search for places
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
                    // Add photo URLs to each place
                    $this->places = array_map(function($place) use ($apiKey) {
                        if (isset($place['photos'][0]['photo_reference'])) {
                            $place['photo'] = $this->getPhotoUrl($place['photos'][0]['photo_reference'], $apiKey);
                            try {
                                SuggestionImages::updateOrCreate(
                                    ['place_image' => $place['photo']], // search criteria
                                    ['place_image' => $place['photo'], 'place_name' => $place['name'], 'place_location' => $place['vicinity']]  // values to update/create
                                );
                            } catch (\Exception $e) {
                                // Silently handle the error to prevent page crash
                                \Log::error('Failed to save suggestion image: ' . $e->getMessage());
                            }
                        }
                        return $place;
                    }, $response['results']);

                    $this->sortPlaces();
                    $this->dispatch('places-updated');
                }
            }
        } catch (\Exception $e) {
            // Handle exception
        }

        $this->loading = false;
    }

    private function getPhotoUrl($photoReference, $apiKey, $maxwidth = 400)
    {
        return "https://maps.googleapis.com/maps/api/place/photo?"
             . "maxwidth={$maxwidth}&"
             . "photo_reference={$photoReference}&"
             . "key={$apiKey}";
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

    public function savePlace($placeId)
    {
        $place = collect($this->places)->firstWhere('place_id', $placeId);

        if (!$place) {
            return;
        }

        SavedItem::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'type' => 'place',
                'api_place_id' => $placeId,
            ],
            [
                'place_name' => $place['name'],
                'place_address' => $place['vicinity'] ?? null,
                'place_details' => [
                    'location' => $place['geometry']['location'] ?? null,
                    'rating' => $place['rating'] ?? null,
                    'types' => $place['types'] ?? [],
                    'photo_reference' => $place['photos'][0]['photo_reference'] ?? null,
                ]
            ]
        );

        $this->dispatch('place-saved');
    }

    public function removeSavedPlace($placeId)
    {
        SavedItem::where('user_id', Auth::id())
                 ->where('api_place_id', $placeId)
                 ->delete();

        $this->dispatch('place-removed');
    }

    public function isPlaceSaved($placeId)
    {
        return SavedItem::where('user_id', Auth::id())
                       ->where('api_place_id', $placeId)
                       ->exists();
    }

    public function toggleSavePlace($placeId)
    {
        if ($this->isPlaceSaved($placeId)) {
            $this->removeSavedPlace($placeId);
        } else {
            $this->savePlace($placeId);
        }
    }

    public function setPlaceType($type)
    {
        $this->placeType = $type;
        $this->searchPlaces();
    }

    public function setSortCriteria($criteria)
    {
        $this->sortCriteria = $criteria;
        $this->sortPlaces();
    }

    public function render()
    {
        return view('livewire.places.place-finder')->layout('layouts.trip');
    }
}
