<?php

namespace App\Livewire\Trip;

use App\Models\Trip;
use Livewire\Component;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ItineraryBuilder extends Component
{
    public $trip;
    public $date;
    public $timeSlots = [];
    public $events = [];
    public $transport = [];
    public $showActivityModal = false;
    public $selectedSlot = null;
    public $activityName;
    public $activityDescription;

    public function mount(Trip $trip)
    {
        $this->trip = $trip;
        $this->loadEvents();
        $this->initializeTimeSlots();
    }

    public function loadEvents()
    {
        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $location = $this->getLocationCoordinates($this->trip->location);

        $eventsUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json";
        $params = [
            'query' => 'events in ' . $this->trip->location,
            'key' => $apiKey,
            'location' => $location['lat'] . ',' . $location['lng'],
            'radius' => '5000'
        ];

        $response = Http::get($eventsUrl, $params)->json();
        $this->events = $response['results'] ?? [];
    }

    public function getTransitOptions($origin, $destination)
    {
        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/directions/json";

        $params = [
            'origin' => $origin,
            'destination' => $destination,
            'mode' => 'transit',
            'key' => $apiKey
        ];

        return Http::get($url, $params)->json();
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

    public function initializeTimeSlots()
    {
        // Define time periods
        $periods = [
            'morning' => ['start' => '08:00', 'end' => '12:00'],
            'afternoon' => ['start' => '12:00', 'end' => '17:00'],
            'evening' => ['start' => '17:00', 'end' => '22:00']
        ];

        foreach ($periods as $periodName => $times) {
            $start = strtotime($times['start']);
            $end = strtotime($times['end']);

            // Create 1-hour slots
            for ($time = $start; $time < $end; $time += 3600) {
                $this->timeSlots[] = [
                    'id' => count($this->timeSlots) + 1,
                    'time' => date('H:i', $time),
                    'period' => $periodName,
                    'activity' => null,
                    'location' => null
                ];
            }
        }
    }

    public function addToItinerary($placeId)
    {
        // Get place details from Google Places API
        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/place/details/json";

        $response = Http::get($url, [
            'place_id' => $placeId,
            'key' => $apiKey
        ])->json();

        if (isset($response['result'])) {
            $event = $this->trip->events()->create([
                'name' => $response['result']['name'],
                'location' => $response['result']['formatted_address'],
                'start_date' => $this->date,
                'end_date' => $this->date,
                'description' => $response['result']['name'] . ' at ' . $response['result']['formatted_address'],
                'type' => 'local_event',
                'price' => 0.00,
                'google_place_id' => $placeId
            ]);

            $this->dispatch('eventAdded', $event->id);
            session()->flash('message', 'Event added to itinerary successfully!');
        }
    }

    public function addActivity($slotId)
    {
        $this->validate([
            'activityName' => 'required|min:3',
            'activityDescription' => 'required'
        ]);

        $activity = $this->trip->events()->create([
            'name' => $this->activityName,
            'location' => $this->trip->location,
            'start_date' => $this->date,
            'end_date' => $this->date,
            'description' => $this->activityDescription,
            'type' => 'activity',
            'price' => 0.00
        ]);

        $this->timeSlots = collect($this->timeSlots)->map(function($slot) use ($activity) {
            if ($slot['id'] === $this->selectedSlot['id']) {
                $slot['activity'] = $activity;
            }
            return $slot;
        })->all();

        $this->reset(['showActivityModal', 'activityName', 'activityDescription']);
        $this->dispatch('activityAdded');
    }

    public function openActivityModal($slotId)
    {
        $this->selectedSlot = collect($this->timeSlots)->firstWhere('id', $slotId);
        $this->showActivityModal = true;
    }

    public function render()
    {
        return view('livewire.trip.itinerary-builder')->layout('layouts.trip');
    }
}
