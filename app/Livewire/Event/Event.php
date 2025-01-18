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
                $startDate = \Carbon\Carbon::parse($event['start_date'])->format('Y-m-d');
                $imageUrl = isset($event['images'][0]['url']) ? $event['images'][0]['url'] : null;

                Log::info('Image URL', ['image_url' => $imageUrl]);

                $price = isset($event['price']) && $event['price'] > 0 ? $event['price'] : null;
                $priceMin = isset($event['priceRanges'][0]['min']) ? $event['priceRanges'][0]['min'] : null;
                LocalEvent::updateOrCreate(
                    [
                        'trip_id' => $latestTrip->id,
                        'name' => $event['title'],
                        'location' => $event['location'],
                        'start_date' => $startDate,
                        'description' => $event['description'],
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

    public function render()
    {
        $latestTrip = Trip::latest()->first();
        $events = LocalEvent::where('trip_id', $latestTrip->id)->paginate(10);

        return view('livewire.event.event', [
            'events' => $events
        ])->layout('layouts.event');
    }
}
