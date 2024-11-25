<?php

namespace App\Livewire\Trip;

use App\Models\Trip;
use Livewire\Component;

class ManageTrips extends Component
{
    public function deleteTrip(Trip $trip)
    {
        $trip->delete();
        session()->flash('message', 'Trip deleted successfully.');

    }
    public function finishTrip(Trip $trip)
    {
        $trip->update(['status' => 'completed']);
        session()->flash('message', 'Trip completed successfully.');
    }
    public function render()
    {
        $trips = Trip::where('user_id', auth()->user()->id)
        ->where(function ($query) {
            $query->where('status', 'active')
                  ->orWhere('status', 'completed');
        })
        ->get();
        return view('livewire.trip.manage-trips',compact('trips'))->layout('layouts.trip');
    }
}
