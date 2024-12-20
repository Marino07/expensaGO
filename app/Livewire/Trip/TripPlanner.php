<?php

namespace App\Livewire\Trip;

use App\Models\Trip;
use App\Models\Planner;
use Livewire\Component;
use App\Services\GooglePlacesService;

class TripPlanner extends Component
{
    public Trip $trip;
    public $days = [];
    public $planner = null;

    public function mount(Trip $trip)
    {
        $this->trip = $trip;
        $this->planner = $trip->planner;
    }

    public function generatePlan()
    {
        $userPreference = $this->trip->userPreference;

        if (!$userPreference) {
            $this->addError('preferences', 'Please complete your preferences first.');
            return;
        }

        $googlePlaces = new GooglePlacesService();

        // Get coordinates from location string
        $coordinates = $googlePlaces->getCoordinates($this->trip->location);

        if (!$coordinates) {
            $this->addError('location', 'Unable to find coordinates for this location.');
            return;
        }

        $this->planner = Planner::create([
            'trip_id' => $this->trip->id,
            'user_preference_id' => $userPreference->id,
            'status' => 'processing',
        ]);

        $preferences = $userPreference->preferences;
        $locationString = "{$coordinates['latitude']},{$coordinates['longitude']}";

        for ($day = 1; $day <= $this->trip->duration; $day++) {
            $mainAttraction = $googlePlaces->searchPlaces(
                $locationString,
                $preferences['main_interest'] ?? 'tourist_attraction'
            )[0] ?? null;

            $places = $googlePlaces->searchPlaces(
                $locationString,
                'point_of_interest'
            );

            $this->planner->plannerDays()->create([
                'planer_id' => $this->planner->id,
                'day_number' => $day,
                'main_attraction' => $mainAttraction,
                'places_to_visit' => array_slice($places, 0, 3),
                'estimated_costs' => ['min' => 50, 'max' => 150],
            ]);
        }

        $this->planner->update(['status' => 'completed']);

        return redirect()->route('trip-planner', ['trip' => $this->trip->id]);
    }

    public function render()
    {
        return view('livewire.trip.trip-planner', [
            'plannerDays' => $this->planner ? $this->planner->plannerDays : collect([])
        ])->layout('layouts.trip');
    }
}
