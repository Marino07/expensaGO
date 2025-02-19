<?php

namespace App\Jobs;

use App\Models\User;
use App\Livewire\Event\Event;
use App\Services\EventAggregatorService;
use App\Livewire\Places\PlaceFinder;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;

class ExecuteLivewireLogic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tripId;
    protected $userId;

    public function __construct($tripId, $userId)
    {
        $this->tripId = $tripId;
        $this->userId = $userId;
    }

    public function handle(EventAggregatorService $eventService)
    {
        try {
            $user = User::where('id', $this->userId)->first();

            if (!$user) {
                throw new \Exception("User not found with ID: {$this->userId}");
            }

            Auth::setUser($user);

            // Instan of the Event componen
            $eventComponent = new Event();
            $eventComponent->mount($eventService);

            // Instance of the PlaceFinder componentt
            $placeComponent = new PlaceFinder();
            $placeComponent->mount();

            \Log::info('Livewire components executed successfully', [
                'userId' => $this->userId,
                'tripId' => $this->tripId
            ]);

        } catch (\Exception $e) {
            \Log::error('Error executing Livewire logic: ' . $e->getMessage(), [
                'userId' => $this->userId,
                'tripId' => $this->tripId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}




