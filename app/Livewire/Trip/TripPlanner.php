<?php

namespace App\Livewire\Trip;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Planner;
use Livewire\Component;
use App\Services\TripPlannerService;
use Illuminate\Support\Facades\Auth;

class TripPlanner extends Component
{
    public Trip $trip;
    public $days = [];
    public $planner = null;
    public $averageDayCost;
    public $cardsToShow;
    private TripPlannerService $plannerService;

    public function boot(TripPlannerService $plannerService)
    {
        $this->plannerService = $plannerService;
    }

    public function mount(Trip $trip)
    {
        $this->trip = $trip;
        $this->planner = $trip->planner;
        $this->averageDayCost = $trip->budget / $trip->duration;
        $this->UnlockCards();
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function generatePlan()
    {
        $this->dispatch('generation-started');

        try {
            $this->planner = $this->plannerService->generatePlan($this->trip);
            $this->dispatch('generation-completed');
            return redirect()->route('trip-planner', ['trip' => $this->trip->id]);
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
            return null;
        }
    }

    public function UnlockCards()
    {
        $this->cardsToShow = $this->plannerService->calculateCardsToShow($this->trip);
    }

    public function render()
    {
        return view('livewire.trip.trip-planner', [
            'plannerDays' => $this->planner ? $this->planner->plannerDays : collect([])
        ])->layout('layouts.trip');
    }
}
