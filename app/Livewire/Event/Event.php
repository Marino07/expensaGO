<?php

namespace App\Livewire\Event;

use Livewire\Component;
use App\Services\EventAggregatorService;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;

class Event extends Component
{
    public $events = [];
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

            $this->events = $eventService->getEvents(
                $latestTrip->location,
                $latestTrip->start_date,
                $latestTrip->end_date
            );

            Log::info('Events fetched successfully', [
                'count' => count($this->events)
            ]);

            // Dump events to browser
            dd($this->events);
        }

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.event.event')->layout('layouts.event');
    }
}
