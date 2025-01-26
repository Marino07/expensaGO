<?php

namespace App\Livewire\Event;

use App\Models\SubEvent;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\EventAggregatorService;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;
use App\Models\LocalEvent;
use Carbon\Carbon;
use App\Jobs\SendEventReminder;
use Illuminate\Support\Facades\Auth;

class Event extends Component
{
    use WithPagination;

    public $loading = true;
    public $search = '';
    public $selectedCategory = 'all';
    public $selectedPrice = 'all';
    public $selectedDate = 'all';
    public $activeFilter = null;
    public $subscribedEvents = [];

    // Reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Add this method
    public function setFilter($filter)
    {
        $this->activeFilter = $this->activeFilter === $filter ? null : $filter;
        $this->resetPage();
    }

    // Dodajemo getter za kategorije iz baze
    public function getCategories()
    {
        return ['all'] + LocalEvent::distinct('category')->pluck('category')->toArray();
    }

    // Dodajemo metodu za ažuriranje filtera iz modala
    public function updateFilters($category = null, $price = null, $date = null)
    {
        $this->selectedCategory = $category ?? $this->selectedCategory;
        $this->selectedPrice = $price ?? $this->selectedPrice;
        $this->selectedDate = $date ?? $this->selectedDate;
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
                $startDate = Carbon::parse($event['start_date'])->format('Y-m-d');

                // Store event with proper price handling
                LocalEvent::updateOrCreate(
                    [
                        'trip_id' => $latestTrip->id,
                        'name' => $event['title']
                    ],
                    [
                        'location' => $event['location'],
                        'start_date' => $startDate,
                        'description' => $event['description'],
                        'category' => $event['category'] ?? 'Other',
                        'type' => $event['source'],
                        'price' => $event['price'],  // Average or single price
                        'price_min' => $event['price_min'], // Minimum price if range exists
                        'price_max' => $event['price_max'], // Maximum price if range exists
                        'google_place_id' => null,
                        'image_url' => $event['image'],
                        'free' => $event['free'] ?? false,
                    ]
                );
            }

            Log::info('Events saved to database successfully', [
                'count' => count($fetchedEvents)
            ]);
        }
        $subscribedEvents = SubEvent::where('user_id', auth()->id())->pluck('event_id')->toArray();
        $this->subscribedEvents = array_flip($subscribedEvents);

        $this->loading = false;
    }

    public function subscribeToEvent($eventId)
    {
        /*
        $event = LocalEvent::find($eventId);
        $user = Auth::user();

        if (!$event) {
            session()->flash('error', 'Event not found.');
            return;
        }

        if (!$user) {
            session()->flash('error', 'Please login first.');
            return;
        }

        try {
            SendEventReminder::dispatch($event, $user);
            $this->subscribedEvents[$eventId] = true;
            session()->flash('message', 'Notification sent successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send notification: ' . $e->getMessage());
            \Log::error('Notification failed:', ['error' => $e->getMessage()]);
        } */

        $event = LocalEvent::find($eventId);
        SubEvent::firstOrCreate([
            'event_id' => $eventId,
            'user_id' => Auth::id()
        ]);
        $this->subscribedEvents[$eventId] = true;
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
            ->when($this->selectedCategory !== 'all', function($query) {
                $query->where('category', $this->selectedCategory);
            })
            ->when($this->selectedPrice !== 'all', function($query) {
                switch($this->selectedPrice) {
                    case 'free':
                        $query->where('free', true);
                        break;
                    case 'paid':
                        $query->where('free', false);
                        break;
                }
            })
            ->when($this->selectedDate !== 'all', function($query) {
                switch($this->selectedDate) {
                    case 'today':
                        $query->whereDate('start_date', Carbon::today());
                        break;
                    case 'week':
                        $query->whereBetween('start_date', [
                            Carbon::now(),
                            Carbon::now()->endOfWeek()
                        ]);
                        break;
                    case 'month':
                        $query->whereBetween('start_date', [
                            Carbon::now(),
                            Carbon::now()->endOfMonth()
                        ]);
                        break;
                }
            })
            ->when($this->activeFilter, function($query) {
                switch($this->activeFilter) {
                    case 'today':
                        $query->whereDate('start_date', Carbon::today());
                        break;
                        case 'weekend':
                            $today = Carbon::now();
                            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY); // Kraj tekuće nedjelje

                            $query->whereBetween('start_date', [
                                $today->format('Y-m-d'), // curr date
                                $endOfWeek->format('Y-m-d') // end of week
                            ]);
                            break;
                    case 'free':
                        $query->where('free', true);
                        break;
                }
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
