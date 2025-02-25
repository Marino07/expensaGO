<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Planner;

class TripPlannerService
{
    private $googlePlacesService;
    private $usedPlaceIds = [];

    public function __construct(GooglePlacesService $googlePlacesService)
    {
        $this->googlePlacesService = $googlePlacesService;
    }

    public function generatePlan($trip)
    {
        $userPreference = $trip->userPreference;

        if (!$userPreference) {
            throw new Exception('Please complete your preferences first.');
        }

        $coordinates = $this->googlePlacesService->getCoordinates($trip->location);

        if (!$coordinates) {
            throw new Exception('Unable to find coordinates for this location.');
        }

        $planner = Planner::create([
            'trip_id' => $trip->id,
            'user_preference_id' => $userPreference->id,
            'status' => 'processing',
        ]);

        $preferences = $userPreference->preferences;
        $locationString = "{$coordinates['latitude']},{$coordinates['longitude']}";

        $activePreferences = array_filter($preferences, fn($value) => $value === true);
        $preferenceKeys = array_keys($activePreferences);

        if (empty($preferenceKeys)) {
            $preferenceKeys = ['tourist_attraction'];
        }

        $totalPreferences = count($preferenceKeys);

        for ($day = 1; $day <= $trip->duration; $day++) {
            $this->generateDayPlan($planner, $day, $locationString, $preferenceKeys, $totalPreferences);
        }

        $planner->update(['status' => 'completed']);
        return $planner;
    }

    private function generateDayPlan($planner, $day, $locationString, $preferenceKeys, $totalPreferences)
    {
        $mainPlaceType = $this->getPriorityPlaceType();
        $mainAttractions = $this->getUniquePlaces($locationString, $mainPlaceType, 1);
        $mainAttraction = $mainAttractions[0] ?? null;

        if ($mainAttraction) {
            $mainAttraction = $this->processPlace($mainAttraction, $mainPlaceType);
        }

        $availableTypes = array_filter($preferenceKeys, fn($pref) => !in_array($pref, ['attractions']));

        if (empty($availableTypes)) {
            $availableTypes = ['restaurants'];
        }

        $availableTypes = array_values($availableTypes);
        $otherPlaces = $this->getSecondaryPlaces($locationString, $availableTypes, $day, count($preferenceKeys));

        $planner->plannerDays()->create([
            'planer_id' => $planner->id,
            'day_number' => $day,
            'main_attraction' => array_merge($mainAttraction ?? [], [
                'preference_name' => $this->getFriendlyPreferenceName($mainPlaceType)
            ]),
            'places_to_visit' => $this->addPreferenceNames($otherPlaces),
            'estimated_costs' => $this->calculateDayEstimatedCosts($mainAttraction, $otherPlaces)
        ]);
    }

    private function getSecondaryPlaces($locationString, $availableTypes, $day, $totalPreferences)
    {
        $otherPlaces = [];
        $usedTypes = [];
        $maxPlaces = 4;
        $allowRepeatingTypes = $totalPreferences < 3;

        for ($i = 0; $i < $maxPlaces && count($availableTypes) > 0; $i++) {
            $prefIndex = ($day + $i) % count($availableTypes);
            $currentType = $this->getPreferenceType($availableTypes[$prefIndex]);

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

    private function limitOtherPlaces($places, $totalPreferences)
    {
        $resultPlaces = [];
        $otherCount = 0;
        $maxOthers = ($totalPreferences >= 3) ? 1 : 2;

        foreach ($places as $place) {
            if (!isset($place['types']) || !is_array($place['types'])) {
                continue;
            }

            $isOther = true;

            foreach ($place['types'] as $type) {
                if (
                    in_array($type, [
                        'restaurant',
                        'tourist_attraction',
                        'park',
                        'shopping_mall',
                        'event',
                        'point_of_interest',
                        'natural_feature'
                    ])
                ) {
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

    private function getPriorityPlaceType()
    {
        return 'tourist_attraction|point_of_interest|event';
    }

    private function getPriceInfo($place)
    {
        if (in_array('park', $place['types'] ?? []) || in_array('natural_feature', $place['types'] ?? [])) {
            return 'Free';
        }

        if (in_array('restaurant', $place['types'] ?? [])) {
            if (isset($place['price_level'])) {
                return match ($place['price_level']) {
                    1 => 'Inexpensive',
                    2 => 'Moderate',
                    3 => 'Expensive',
                    4 => 'Very Expensive',
                    default => 'Price N/A'
                };
            }
        }

        if (isset($place['price_level']) || isset($place['price'])) {
            return isset($place['price']) ? $place['price'] : 'Paid entry';
        }

        if (isset($place['business_status']) && $place['business_status'] === 'CLOSED_PERMANENTLY') {
            return 'Closed';
        }

        return 'Price N/A';
    }

    private function processPlacePhoto($place, $placeType)
    {
        if (!empty($place['photos'][0]['photo_reference'])) {
            $place['photo_url'] = $this->googlePlacesService->getPhotoUrl($place['photos'][0]['photo_reference']);
        } else {
            $place['photo_url'] = $this->getDefaultImage($placeType);
        }

        return $place;
    }

    private function processPlace($place, $placeType)
    {
        $place = $this->processPlacePhoto($place, $placeType);

        if (isset($place['place_id'])) {
            $details = $this->googlePlacesService->getPlaceDetails($place['place_id']);
            if ($details) {
                $place = array_merge($place, $details);
            }
        }

        $place['price_info'] = $this->getPriceInfo($place);
        return $place;
    }

    private function getDefaultImage($preferenceType)
    {
        $defaultImages = [
            'restaurant' => '/images/defaults/restaurant.jpg',
            'store' => '/images/defaults/shopping.jpg',
            'shopping_mall' => '/images/defaults/shopping.jpg',
            'natural_feature' => '/images/defaults/nature.jpg',
            'park' => '/images/defaults/nature.jpg',
            'event' => '/images/defaults/event.jpg',
            'tourist_attraction' => '/images/defaults/attraction.jpg',
            'point_of_interest' => '/images/defaults/attraction.jpg',
        ];

        return $defaultImages[$preferenceType] ?? '/images/defaults/default.jpg';
    }

    private function calculateDistance($point1, $point2)
    {
        [$lat1, $lon1] = explode(',', $point1);
        [$lat2, $lon2] = explode(',', $point2);

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $radius = 6371000;

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radius * $c;
    }

    private function sortPlacesByPopularity($places)
    {
        usort($places, function ($a, $b) {
            $scoreA = (isset($a['rating']) ? $a['rating'] : 0) * 0.5 +
                (isset($a['user_ratings_total']) ? min($a['user_ratings_total'] / 1000, 5) : 0) * 0.5;
            $scoreB = (isset($b['rating']) ? $b['rating'] : 0) * 0.5 +
                (isset($b['user_ratings_total']) ? min($b['user_ratings_total'] / 1000, 5) : 0) * 0.5;

            return $scoreB <=> $scoreA;
        });

        return $places;
    }

    private function getUniquePlaces($locationString, $placeType, $limit = 1, $radius = 5000)
    {
        $places = [];
        $attempts = 0;
        $maxAttempts = 3;
        $radiusIncrement = 2000;

        while (count($places) < $limit && $attempts < $maxAttempts) {
            $currentRadius = $radius + ($attempts * $radiusIncrement);
            $newPlaces = $this->googlePlacesService->searchPlaces($locationString, $placeType, $currentRadius, true);
            $newPlaces = $this->sortPlacesByPopularity($newPlaces);

            foreach ($newPlaces as $place) {
                if (!isset($place['place_id']) || in_array($place['place_id'], $this->usedPlaceIds)) {
                    continue;
                }

                if (isset($place['geometry']['location'])) {
                    $distance = $this->calculateDistance(
                        $locationString,
                        "{$place['geometry']['location']['lat']},{$place['geometry']['location']['lng']}"
                    );

                    if ($distance > $currentRadius) {
                        continue;
                    }
                }

                $places[] = $place;
                $this->usedPlaceIds[] = $place['place_id'];

                if (count($places) >= $limit) {
                    break;
                }
            }

            $attempts++;
        }

        return $places;
    }

    private function addPreferenceNames($places)
    {
        if (!is_array($places)) {
            return $places;
        }

        return array_map(function ($place) {
            if (isset($place['types'])) {
                $place['preference_name'] = $this->getFriendlyPreferenceName($place['types'][0]);
            }
            return $place;
        }, $places);
    }

    private function getFriendlyPreferenceName($placeTypes)
    {
        $typeArray = explode('|', $placeTypes);
        $mappings = [
            'restaurant' => 'Restaurant',
            'tourist_attraction' => 'Tourist Attraction',
            'store' => 'Shopping',
            'shopping_mall' => 'Shopping',
            'natural_feature' => 'Nature',
            'park' => 'Nature',
            'event' => 'Event',
            'point_of_interest' => 'Attraction',
        ];

        foreach ($typeArray as $type) {
            if (isset($mappings[$type])) {
                return $mappings[$type];
            }
        }

        return 'Other';
    }

    private function getPreferenceType($preference)
    {
        $mappings = [
            'events' => 'event',
            'nature' => 'park|natural_feature',
            'shopping' => 'shopping_mall|store',
            'attractions' => 'tourist_attraction|point_of_interest|event',
            'restaurants' => 'restaurant',
            'localCuisine' => 'restaurant',
        ];

        return $mappings[$preference] ?? 'tourist_attraction|point_of_interest';
    }

    public function calculateCardsToShow($trip)
    {
        $startDate = Carbon::parse($trip->start_date);
        $today = Carbon::today();

        if ($today->isBefore($startDate)) {
            return 1;
        }

        if ($today->isSameDay($startDate)) {
            return 2;
        }

        $daysPassed = $startDate->diffInDays($today);

        return min($daysPassed + 2, $trip->duration);
    }

    private function calculateDayEstimatedCosts($mainAttraction, $otherPlaces)
    {
        return ['min' => null, 'max' => null];
    }
}
