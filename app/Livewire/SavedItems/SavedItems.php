<?php

namespace App\Livewire\SavedItems;

use App\Models\SavedItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SavedItems extends Component
{
    public $savedPlaces = [];
    public $savedEvents = [];

    public function mount()
    {
        $this->loadSavedItems();
    }

    public function loadSavedItems()
    {
        // Load saved places
        $this->savedPlaces = SavedItem::where('user_id', Auth::id())
            ->where('type', 'place')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->place_name,
                    'location' => $item->place_address,
                    'category' => $item->place_details['types'] ?? 'Place',
                    'image' => $this->getPlaceImage($item->place_details['photo_reference'] ?? null),
                    'rating' => $item->place_details['rating'] ?? null,
                    'place_id' => $item->api_place_id ?? null
                ];
            });

        // Load saved events
        $this->savedEvents = SavedItem::where('user_id', Auth::id())
            ->where('type', 'event')
            ->with('event')
            ->get()
            ->map(function ($item) {
                $event = $item->event;
                return [
                    'id' => $item->id,
                    'name' => $event->name,
                    'location' => $event->location,
                    'date' => $event->start_date,
                    'image' => $event->image_url,
                    'category' => $event->category,
                    'is_free' => $event->free,
                    'price_display' => $this->getPriceDisplay($event),
                    'url' => $event->event_url

                ];
            });
    }

    private function getPlaceImage($photoReference)
    {
        if (!$photoReference) {
            return 'https://via.placeholder.com/400x300?text=No+Image';
        }

        $apiKey = env('GOOGLE_PLACES_API_KEY');
        return "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photo_reference={$photoReference}&key={$apiKey}";
    }

    private function getPriceDisplay($event)
    {
        if ($event->free) {
            return 'Free';
        }

        if ($event->price) {
            return '$' . number_format($event->price, 2);
        }

        if ($event->min_price && $event->max_price) {
            $averagePrice = ($event->min_price + $event->max_price) / 2;
            return '$' . number_format($averagePrice, 2);
        }

        return 'Price not fixed';
    }

    public function removeItem($id, $type)
    {
        SavedItem::where('id', $id)->where('user_id', Auth::id())->delete();
        $this->loadSavedItems();

        $this->dispatch('item-removed', [
            'message' => ucfirst($type) . ' removed successfully'
        ]);
    }

    public function render()
    {
        return view('livewire.saved-items.saved-items')->layout('layouts.event');
    }
}
