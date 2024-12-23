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
    public $averageDayCost;

    public function mount(Trip $trip)
    {
        $this->trip = $trip;
        $this->planner = $trip->planner;
        $this->averageDayCost = $trip->budget / $trip->duration;
    }

    private function getPreferenceType($preference)
    {
        $mappings = [
            'events' => 'event',
            'nature' => 'park|natural_feature',
            'shopping' => 'shopping_mall|store',
            'attractions' => 'tourist_attraction|point_of_interest|event',  // dodao event
            'restaurants' => 'restaurant',
            'localCuisine' => 'restaurant',
        ];

        return $mappings[$preference] ?? 'tourist_attraction|point_of_interest';  // dodao point_of_interest
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
        $radiusIncrement = 2000;

        while (count($places) < $limit && $attempts < $maxAttempts) {
            $currentRadius = $radius + ($attempts * $radiusIncrement);

            $newPlaces = $googlePlaces->searchPlaces(
                $locationString,
                $placeType,
                $currentRadius,
                true
            );

            // Sort places by rating and user_ratings_total
            usort($newPlaces, function($a, $b) {
                // Calculate popularity score (50% rating, 50% number of reviews)
                $scoreA = (isset($a['rating']) ? $a['rating'] : 0) * 0.5 +
                         (isset($a['user_ratings_total']) ? min($a['user_ratings_total'] / 1000, 5) : 0) * 0.5;
                $scoreB = (isset($b['rating']) ? $b['rating'] : 0) * 0.5 +
                         (isset($b['user_ratings_total']) ? min($b['user_ratings_total'] / 1000, 5) : 0) * 0.5;

                return $scoreB <=> $scoreA;
            });

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

    private function processPlace($place, $placeType)
    {
        $place = $this->processPlacePhoto($place, $placeType);

        // Dobavi detaljne informacije o mestu
        if (isset($place['place_id'])) {
            $googlePlaces = new GooglePlacesService();
            $details = $googlePlaces->getPlaceDetails($place['place_id']);
            if ($details) {
                $place = array_merge($place, $details);
            }
        }

        $place['price_info'] = $this->getPriceInfo($place);
        return $place;
    }

    private function getPriceInfo($place)
    {
        // Za besplatne lokacije
        if (in_array('park', $place['types'] ?? []) ||
            in_array('natural_feature', $place['types'] ?? [])) {
            return 'Free';
        }

        // Za restorane
        if (in_array('restaurant', $place['types'] ?? [])) {
            if (isset($place['price_level'])) {
                return match($place['price_level']) {
                    1 => 'Inexpensive',
                    2 => 'Moderate',
                    3 => 'Expensive',
                    4 => 'Very Expensive',
                    default => 'Price N/A'
                };
            }
        }

        // Za muzeje i atrakcije
        if (isset($place['price_level']) || isset($place['price'])) {
            return isset($place['price']) ? $place['price'] : 'Paid entry';
        }

        // Za zatvorene lokacije
        if (isset($place['business_status']) && $place['business_status'] === 'CLOSED_PERMANENTLY') {
            return 'Closed';
        }

        return 'Price N/A';
    }

    private function getPriorityPlaceType()
    {
        return 'tourist_attraction|point_of_interest|event';  // dodao event
    }

    private function limitOtherPlaces($places, $totalPreferences)
    {
        // If we have many preferences, limit 'Other' places more strictly
        $maxOthers = ($totalPreferences >= 3) ? 1 : 2;
        $otherCount = 0;
        $resultPlaces = [];

        foreach ($places as $place) {
            if (!isset($place['types']) || !is_array($place['types'])) {
                continue;
            }

            $isOther = true;
            foreach ($place['types'] as $type) {
                if (in_array($type, [
                    'restaurant',
                    'tourist_attraction',
                    'park',
                    'shopping_mall',
                    'event',
                    'point_of_interest',
                    'natural_feature'
                ])) {
                    $isOther = false;
                    break;
                }
            }

            if ($isOther) {
                if ($otherCount < $maxOthers) {
                    $resultPlaces[] = $place;
                    $otherCount++;
                }
            } else {
                $resultPlaces[] = $place;
            }
        }

        return $resultPlaces;
    }

    private function getSecondaryPlaces($locationString, $availableTypes, $day, $totalPreferences)
    {
        $otherPlaces = [];
        $usedTypes = [];
        $maxPlaces = 4;

        // If we have enough preferences, don't allow repeating types
        $allowRepeatingTypes = $totalPreferences < 3;

        for ($i = 0; $i < $maxPlaces && count($availableTypes) > 0; $i++) {
            $prefIndex = ($day + $i) % count($availableTypes);
            $currentType = $this->getPreferenceType($availableTypes[$prefIndex]);

            // Skip if type was already used and we have enough preferences
            if (!$allowRepeatingTypes && in_array($currentType, $usedTypes)) {
                continue;
            }

            $places = $this->getUniquePlaces($locationString, $currentType, 1);
            if (!empty($places)) {
                $place = $places[0];
                $place = $this->processPlace($place, $currentType);
                $otherPlaces[] = $place;
                $usedTypes[] = $currentType;
            }
        }

        return $otherPlaces;
    }

    private function calculateDayEstimatedCosts($mainAttraction, $otherPlaces)
    {
        // Uklanjamo procenu troškova jer nemamo pouzdane podatke
        return ['min' => null, 'max' => null];
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
            // Always ensure first place is an attraction or event
            $mainPlaceType = $this->getPriorityPlaceType();

            // Get main attraction (guaranteed to be a tourist spot)
            $mainAttractions = $this->getUniquePlaces($locationString, $mainPlaceType, 1);
            $mainAttraction = $mainAttractions[0] ?? null;

            if ($mainAttraction) {
                $mainAttraction = $this->processPlace($mainAttraction, $mainPlaceType);
            }

            // Get secondary places from user preferences
            $availableTypes = array_filter($preferenceKeys, function($pref) {
                return !in_array($pref, ['attractions']);
            });

            if (empty($availableTypes)) {
                $availableTypes = ['restaurants'];
            }

            $availableTypes = array_values($availableTypes);

            // Use new method for getting secondary places
            $otherPlaces = $this->getSecondaryPlaces(
                $locationString,
                $availableTypes,
                $day,
                count($preferenceKeys)
            );

            // Limit 'Other' places based on total preferences
            $otherPlaces = array_values($this->limitOtherPlaces($otherPlaces, count($preferenceKeys)));

            $this->planner->plannerDays()->create([
                'planer_id' => $this->planner->id,
                'day_number' => $day,
                'main_attraction' => array_merge($mainAttraction ?? [], [
                    'preference_name' => $this->getFriendlyPreferenceName($mainPlaceType)
                ]),
                'places_to_visit' => $this->addPreferenceNames($otherPlaces),
                'estimated_costs' => $this->calculateDayEstimatedCosts($mainAttraction, $otherPlaces)
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
