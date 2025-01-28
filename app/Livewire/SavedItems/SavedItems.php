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
                    'category' => $item->place_details['types'][0] ?? 'Place',
                    'image' => $this->getPlaceImage($item->place_details['photo_reference'] ?? null)
                ];
            });

        // Load saved events
        $this->savedEvents = SavedItem::where('user_id', Auth::id())
            ->where('type', 'event')
            ->with('event')
            ->get()
            ->map(function ($item) {
                $event = $item->event;

                // Determine price display
                $priceDisplay = 'N/A';
                $isFree = false;

                if ($event) {
                    if ($event->free) {
                        $priceDisplay = 'Free';
                        $isFree = true;
                    } elseif ($event->price) {
                        $priceDisplay = '€' . number_format($event->price, 2);
                    } elseif ($event->price_min && $event->price_max) {
                        $avgPrice = ($event->price_min + $event->price_max) / 2;
                        $priceDisplay = '~€' . number_format($avgPrice, 2);
                    } else {
                        $priceDisplay = 'Price TBA';
                    }
                }

                return [
                    'id' => $item->id,
                    'name' => $event->name ?? $item->place_name,
                    'location' => $event->location ?? $item->place_address,
                    'date' => $event->start_date ?? null,
                    'image' => $event->image_url ?? null,
                    'category' => $event->category ?? 'Event',
                    'is_free' => $isFree,
                    'price_display' => $priceDisplay
                ];
            });
    }

    private function getPlaceImage($photoReference)
    {
        if (!$photoReference) {
            return 'https://via.placeholder.com/400x300?text=No+Image';
        }

        $apiKey = env('GOOGLE_PLACES_API_KEY');
        return "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference={$photoReference}&key={$apiKey}";
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
        return view('livewire.saved-items.saved-items')
            ->layout('layouts.application');
    }
}
