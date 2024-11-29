<?php

namespace App\Livewire;

use App\Models\Trip;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SOSComponent extends Component
{
    public $emergencyServices = [];
    public $type = 'police';

    public function mount()
    {
        $this->searchEmergencyServices($this->type);
    }

    public function searchEmergencyServices($type = 'hospital')
    {
        $this->type = $type;
        $trip = Trip::where('user_id', auth()->id())->latest()->first();
        $location = $this->getLocationCoordinates('Zagreb'); // Pretvorite naziv grada u koordinate
        if (!$location) {
            $this->emergencyServices = [];
            return;
        }

        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $placesUrl = "https://maps.googleapis.com/maps/api/place/nearbysearch/json";
        $params = [
            'location' => $location,
            'radius' => '5000',
            'type' => $type,
            'key' => $apiKey
        ];

        $response = Http::get($placesUrl, $params)->json();
        $this->emergencyServices = $response['results'] ?? [];
    }

    private function getLocationCoordinates($address)
    {
        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json";
        $params = [
            'address' => $address,
            'key' => $apiKey
        ];

        $response = Http::get($geocodeUrl, $params)->json();
        if (isset($response['results'][0]['geometry']['location'])) {
            $location = $response['results'][0]['geometry']['location'];
            return $location['lat'] . ',' . $location['lng'];
        }

        return null;
    }

    public function render()
    {
        return view('livewire.sos-component')->layout('layouts.trip');
    }
}
