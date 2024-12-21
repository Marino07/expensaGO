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
    private $usedPlaceIds = [];

    public function mount(Trip $trip)
    {
        $this->trip = $trip;
        $this->planner = $trip->planner;
    }

    private function getPreferenceType($preference)
    {
        $mappings = [
            'events' => 'event',
            'nature' => 'park|natural_feature',
            'shopping' => 'shopping_mall|store',
            'attractions' => 'tourist_attraction|point_of_interest',
            'restaurants' => 'restaurant',
            'localCuisine' => 'restaurant',
        ];

        return $mappings[$preference] ?? 'tourist_attraction';
    }

    private function getFriendlyPreferenceName($placeTypes)
    {
        $typeArray = explode('|', $placeTypes);
        $mappings = [
            'event' => 'Event',
            'park' => 'Nature',
            'natural_feature' => 'Nature',
            'shopping_mall' => 'Shopping',
            'store' => 'Shopping',
            'tourist_attraction' => 'Tourist Attraction',
            'point_of_interest' => 'Attraction',
            'restaurant' => 'Restaurant',
        ];

        foreach ($typeArray as $type) {
            if (isset($mappings[$type])) {
                return $mappings[$type];
            }
        }

        return 'Other';
    }

    private function addPreferenceNames($places)
    {
        if (!is_array($places)) {
            return $places;
        }

        return array_map(function($place) {
            if (isset($place['types'])) {
                $place['preference_name'] = $this->getFriendlyPreferenceName($place['types'][0]);
            }
            return $place;
        }, $places);
    }

    private function getUniquePlaces($locationString, $placeType, $limit = 1, $radius = 5000)
    {
        $googlePlaces = new GooglePlacesService();
        $attempts = 0;
        $places = [];
        $maxAttempts = 3;
        $radiusIncrement = 2000; // Smaller radius increments

        while (count($places) < $limit && $attempts < $maxAttempts) {
            $currentRadius = $radius + ($attempts * $radiusIncrement);

            $newPlaces = $googlePlaces->searchPlaces(
                $locationString,
                $placeType,
                $currentRadius,
                true // Add strictBounds parameter
            );

            foreach ($newPlaces as $place) {
                if (!isset($place['place_id']) || in_array($place['place_id'], $this->usedPlaceIds)) {
                    continue;
                }

                // Calculate distance from city center
                if (isset($place['geometry']['location'])) {
                    $distance = $this->calculateDistance(
                        $locationString,
                        "{$place['geometry']['location']['lat']},{$place['geometry']['location']['lng']}"
                    );

                    // Skip if too far from city center
                    if ($distance > $currentRadius) {
                        continue;
                    }
                }

                $this->usedPlaceIds[] = $place['place_id'];
                $places[] = $place;
                if (count($places) >= $limit) {
                    break;
                }
            }
            $attempts++;
        }

        return $places;
    }

    private function calculateDistance($point1, $point2)
    {
        [$lat1, $lon1] = explode(',', $point1);
        [$lat2, $lon2] = explode(',', $point2);

        $radius = 6371000; // Earth's radius in meters

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat/2) * sin($deltaLat/2) +
            cos($lat1) * cos($lat2) *
            sin($deltaLon/2) * sin($deltaLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $radius * $c; // Distance in meters
    }

    private function getDefaultImage($preferenceType)
    {
        $defaultImages = [
            'event' => '/images/defaults/event.jpg',
            'park' => '/images/defaults/nature.jpg',
            'natural_feature' => '/images/defaults/nature.jpg',
            'shopping_mall' => '/images/defaults/shopping.jpg',
            'store' => '/images/defaults/shopping.jpg',
            'restaurant' => '/images/defaults/restaurant.jpg',
            'tourist_attraction' => '/images/defaults/attraction.jpg',
            'point_of_interest' => '/images/defaults/attraction.jpg',
        ];

        return $defaultImages[$preferenceType] ?? '/images/defaults/default.jpg';
    }

    private function processPlacePhoto($place, $placeType)
    {
        $googlePlaces = new GooglePlacesService();

        if (!empty($place['photos'][0]['photo_reference'])) {
            $place['photo_url'] = $googlePlaces->getPhotoUrl($place['photos'][0]['photo_reference']);
        } else {
            $place['photo_url'] = $this->getDefaultImage($placeType);
        }

        return $place;
    }

    public function generatePlan()
    {
        $this->dispatch('generation-started');

        $userPreference = $this->trip->userPreference;

        if (!$userPreference) {
            $this->addError('preferences', 'Please complete your preferences first.');
            return;
        }

        $googlePlaces = new GooglePlacesService();
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

        // Get active preferences (where value is true)
        $activePreferences = array_filter($preferences, function($value) {
            return $value === true;
        });
        $preferenceKeys = array_keys($activePreferences);

        if (empty($preferenceKeys)) {
            $preferenceKeys = ['tourist_attraction'];
        }

        $totalPreferences = count($preferenceKeys);

        for ($day = 1; $day <= $this->trip->duration; $day++) {
            // Rotate main preference each day
            $mainPreferenceIndex = ($day - 1) % $totalPreferences;
            $mainPreference = $preferenceKeys[$mainPreferenceIndex];
            $mainPlaceType = $this->getPreferenceType($mainPreference);

            // Get main attraction
            $mainAttractions = $this->getUniquePlaces($locationString, $mainPlaceType, 1);
            $mainAttraction = $mainAttractions[0] ?? null;

            if ($mainAttraction) {
                $mainAttraction = $this->processPlacePhoto($mainAttraction, $mainPlaceType);
            }

            // Get secondary places from other preferences
            $otherPlaces = [];
            $secondaryPreferences = $preferenceKeys;
            unset($secondaryPreferences[$mainPreferenceIndex]); // Remove main preference
            $secondaryPreferences = array_values($secondaryPreferences); // Reindex array

            // Take next 2 preferences in rotation for secondary places
            for ($i = 0; $i < 3 && count($secondaryPreferences) > 0; $i++) {
                $secondaryIndex = ($day + $i) % count($secondaryPreferences);
                $secondaryType = $this->getPreferenceType($secondaryPreferences[$secondaryIndex]);

                $places = $this->getUniquePlaces($locationString, $secondaryType, 1);
                if (!empty($places)) {
                    $otherPlaces[] = $places[0];
                }
            }

            $otherPlaces = array_map(function($place) use ($secondaryPreferences, $secondaryIndex) {
                return $this->processPlacePhoto($place, $this->getPreferenceType($secondaryPreferences[$secondaryIndex]));
            }, $otherPlaces);

            $this->planner->plannerDays()->create([
                'planer_id' => $this->planner->id,
                'day_number' => $day,
                'main_attraction' => array_merge($mainAttraction, [
                    'preference_name' => $this->getFriendlyPreferenceName($mainPlaceType)
                ]),
                'places_to_visit' => $this->addPreferenceNames($otherPlaces),
                'estimated_costs' => ['min' => 50, 'max' => 150],
            ]);
        }

        $this->planner->update(['status' => 'completed']);
        $this->dispatch('generation-completed');

        return redirect()->route('trip-planner', ['trip' => $this->trip->id]);
    }

    public function render()
    {
        return view('livewire.trip.trip-planner', [
            'plannerDays' => $this->planner ? $this->planner->plannerDays : collect([])
        ])->layout('layouts.trip');
    }
}
