<?php

namespace App\Livewire\SavedItems;

use App\Models\SavedItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class SavedItems extends Component
{
    use WithPagination;

    protected function getStaticPlaces()
    {
        return [
            [
                'id' => 1,
                'place_name' => 'Amazing Restaurant',
                'place_address' => '123 Main Street, London',
                'place_details' => [
                    'rating' => 4.5,
                    'user_ratings_total' => 253,
                    'price_level' => 2,
                    'types' => ['restaurant', 'food'],
                    'opening_hours' => ['open_now' => true],
                ]
            ],
            [
                'id' => 2,
                'place_name' => 'Cool Cafe',
                'place_address' => '456 Park Avenue, London',
                'place_details' => [
                    'rating' => 4.8,
                    'user_ratings_total' => 542,
                    'price_level' => 1,
                    'types' => ['cafe', 'restaurant'],
                    'opening_hours' => ['open_now' => false],
                ]
            ],
            [
                'id' => 3,
                'place_name' => 'Local Pub',
                'place_address' => '789 River Road, London',
                'place_details' => [
                    'rating' => 4.2,
                    'user_ratings_total' => 128,
                    'price_level' => 2,
                    'types' => ['bar', 'restaurant'],
                    'opening_hours' => ['open_now' => true],
                ]
            ],
        ];
    }

    protected function getStaticEvents()
    {
        return [
            [
                'id' => 1,
                'event' => [
                    'name' => 'Summer Music Festival',
                    'description' => 'Amazing outdoor music festival featuring top artists',
                    'location' => 'Hyde Park, London',
                    'start_date' => '2024-07-15',
                    'category' => 'Music',
                    'image_url' => 'https://example.com/images/festival.jpg',
                    'price' => 49.99,
                    'free' => false
                ]
            ],
            [
                'id' => 2,
                'event' => [
                    'name' => 'Art Exhibition',
                    'description' => 'Contemporary art showcase by local artists',
                    'location' => 'Modern Gallery, London',
                    'start_date' => '2024-06-20',
                    'category' => 'Art',
                    'image_url' => null,
                    'price' => null,
                    'free' => true
                ]
            ],
            [
                'id' => 3,
                'event' => [
                    'name' => 'Food & Wine Festival',
                    'description' => 'Taste the best local and international cuisine',
                    'location' => 'Trafalgar Square, London',
                    'start_date' => '2024-08-01',
                    'category' => 'Food',
                    'image_url' => 'https://example.com/images/food.jpg',
                    'price' => 29.99,
                    'free' => false
                ]
            ]
        ];
    }

    public function removeItem($id)
    {
        // Simulacija brisanja
        $this->dispatch('item-removed', [
            'message' => 'Item removed successfully'
        ]);
    }

    public function render()
    {
        return view('livewire.saved-items.saved-items', [
            'savedPlaces' => collect($this->getStaticPlaces()),
            'savedEvents' => collect($this->getStaticEvents())
        ])->layout('layouts.application');
    }
}
