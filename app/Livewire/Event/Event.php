<?php

namespace App\Livewire\Event;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\EventAggregatorService;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;
use App\Models\LocalEvent;
use Carbon\Carbon;

class Event extends Component
{
    use WithPagination;

    public $loading = true;
    public $search = '';
    public $category;

    // Reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function mount(EventAggregatorService $eventService)
    {
        Log::info('Event component mounted');

        $latestTrip = Trip::latest()->first();

        if ($latestTrip) {
            Log::info('Fetching events for trip', [
                'location' => $latestTrip->location,
                'dates' => [
                    'start' => $latestTrip->start_date,
                    'end' => $latestTrip->end_date
                ]
            ]);

            $fetchedEvents = $eventService->getEvents(
                $latestTrip->location,
                $latestTrip->start_date,
                $latestTrip->end_date
            );

            foreach ($fetchedEvents as $event) {
                // Debug the raw event data
               // Log::debug('Raw event data', ['event' => $event]);

                $startDate = \Carbon\Carbon::parse($event['start_date'])->format('Y-m-d');
                $imageUrl = isset($event['images'][0]['url']) ? $event['images'][0]['url'] : null;

                $price = isset($event['price']) && $event['price'] > 0 ? $event['price'] : null;
                $priceMin = isset($event['priceRanges'][0]['min']) ? $event['priceRanges'][0]['min'] : null;

                // Simplified category extraction using direct classifications field
                $category = 'Unknown';
                if (!empty($event['classifications']) && is_array($event['classifications'])) {
                    Log::debug('Processing classifications', [
                        'event_name' => $event['title'],
                        'classifications' => $event['classifications']
                    ]);

                    foreach ($event['classifications'] as $classification) {
                        if (isset($classification['segment']['name'])) {
                            $category = $classification['segment']['name'];
                            break;
                        }
                    }
                }

               /* Log::debug('Event category extraction result', [
                    'event_name' => $event['title'],
                    'category' => $category,
                    'has_classifications' => isset($event['classifications'])
                ]);*/

                LocalEvent::updateOrCreate(
                    [
                        'trip_id' => $latestTrip->id,
                        'name' => $event['title']
                    ],
                    [
                        'location' => $event['location'],
                        'start_date' => $startDate,
                        'description' => $event['description'],
                        'category' => $category,
                        'type' => $event['source'],
                        'price' => $price,
                        'google_place_id' => null,
                        'image_url' => $event['image'],
                    ]
                );
            }

            Log::info('Events saved to database successfully', [
                'count' => count($fetchedEvents)
            ]);
        }

        $this->loading = false;
    }

    private function getEvents()
    {
        $latestTrip = Trip::latest()->first();

        if (!$latestTrip) {
            return collect();
        }

        return LocalEvent::where('trip_id', $latestTrip->id)
            ->when($this->search, function($query) { //when $this->search je null ili '' -> false
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('location', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('start_date')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.event.event', [
            'events' => $this->getEvents()
        ])->layout('layouts.event');
    }
}
